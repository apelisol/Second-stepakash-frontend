<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

    private $transaction_id;
    private $session_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Operations');
        $this->load->library('session');
        $this->load->library('encryption');

        $transaction_id = $this->session->userdata('transaction_id');
        $time_frame = $this->session->userdata('time_frame');
        $valid_time_frame = $time_frame && (time() - $time_frame <= 30);
        if (!$transaction_id || !$valid_time_frame) {
            $transaction_id = $this->Operations->OTP(9);
            $time_frame = time();
            $this->session->set_userdata('transaction_id', $transaction_id);
            $this->session->set_userdata('time_frame', $time_frame);
        }

        $session_id = $this->session->userdata('session_id');
        $this->session_id = $session_id;
        $this->transaction_id = $transaction_id;
    }

    // Basic Views
    public function index()
    {
        $this->load->view('login');
    }

    public function login()
    {
        $this->load->view('login');
    }

    public function signup()
    {
        $this->load->view('signup');
    }

    public function terms()
    {
        $this->load->view('terms');
    }

    public function privacy()
    {
        $this->load->view('privacy');
    }

    // Main Application Functions
    public function home()
    {
        try {
            $url = APP_INSTANCE . 'home_data';
            $session_id = $this->session->userdata('session_id');

            // Check if session ID exists
            if (empty($session_id)) {
                log_message('error', 'Session ID missing in home()');
                $this->session->set_flashdata('msg', 'Session expired. Please login again.');
                redirect('logout');
            }

            $body = array('session_id' => $session_id);
            $response = $this->Operations->CurlPost($url, $body);

            // Check if we got a valid response
            if (empty($response)) {
                throw new Exception('Empty response from home_data API');
            }

            $decode = json_decode($response, true);

            // Check if JSON decoding worked
            if ($decode === null) {
                throw new Exception('Invalid JSON response: ' . $response);
            }

            $status = $decode['status'] ?? 'error';
            $message = $decode['message'] ?? 'Unknown error';
            $data = $decode['data'] ?? [];

            // Initialize Deriv-related variables with default values
            $data['deriv_balance'] = null;
            $data['deriv_balance_kes'] = 0;
            $data['deriv_token_status'] = 'no_token';
            $data['deriv_error'] = '';

            if ($status == 'fail') {
                $this->session->set_flashdata('msg', $message);
                redirect('logout');
            } elseif ($status == 'success' || $status == 'error') {
                // Safely get Deriv token and expiration
                $deriv_token = $this->session->userdata('deriv_token') ?? null;
                $token_expiry = $this->session->userdata('deriv_token_expiry') ?? 0;
                $current_time = time();

                if ($deriv_token) {
                    // Check token expiration
                    if ($current_time > $token_expiry) {
                        $data['deriv_token_status'] = 'expired';
                        $data['deriv_error'] = 'Deriv connection expired. Please reconnect.';
                    } else {
                        try {
                            $data['deriv_token_status'] = 'valid';

                            // Get Deriv balance
                            $deriv_balance = $this->getDerivBalance($deriv_token);
                            $data['deriv_balance'] = $deriv_balance;

                            // Calculate KES equivalent if we have valid data
                            if (
                                isset($deriv_balance['balance']) &&
                                isset($data['buyrate']) &&
                                is_numeric($deriv_balance['balance']) &&
                                is_numeric($data['buyrate'])
                            ) {
                                $data['deriv_balance_kes'] = $deriv_balance['balance'] * $data['buyrate'];
                            }
                        } catch (Exception $e) {
                            $data['deriv_error'] = 'Error fetching Deriv balance: ' . $e->getMessage();
                        }
                    }
                }

                // Load views
                $this->load->view('includes/header');
                $this->load->view('home', $data);
                $this->load->view('includes/footer', $data);
            } else {
                throw new Exception("Invalid status: $status");
            }
        } catch (Exception $e) {
            // Log detailed error
            log_message('error', 'Home controller error: ' . $e->getMessage());
            log_message('debug', 'Trace: ' . $e->getTraceAsString());

            // User-friendly message
            $this->session->set_flashdata('msg', 'System error. Please try again later.');
            redirect('logout');
        }
    }

    private function getDerivBalance($token)
    {
        // Check token expiration first
        $expiry = $this->session->userdata('deriv_token_expiry');
        $current_time = time();

        if ($current_time > $expiry) {
            log_message('error', 'Token expired during balance check. Expired at: '
                . date('Y-m-d H:i:s', $expiry));
            return [
                'error' => 'Token expired. Please reauthenticate.',
                'balance' => 0,
                'currency' => 'USD'
            ];
        }

        // Check cache
        $cacheKey = 'deriv_balance_' . md5($token);
        $cached = $this->cache->get($cacheKey);
        if ($cached !== false) {
            log_message('debug', 'Using cached balance');
            return $cached;
        }

        try {
            $appId = 76420;
            $url = "wss://ws.derivws.com/websockets/v3?app_id={$appId}";

            $options = [
                'timeout' => 5,
                'headers' => ['Origin' => base_url()],
                'context' => stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]),
            ];

            $client = new \WebSocket\Client($url, $options);
            log_message('debug', 'WebSocket connection established');

            // Authorize
            $client->send(json_encode(["authorize" => $token]));
            $authResponse = json_decode($client->receive(), true);

            if (isset($authResponse['error'])) {
                throw new Exception('Auth error: ' . $authResponse['error']['message']);
            }
            log_message('debug', 'Authorization successful for balance check');

            // Get balance
            $client->send(json_encode([
                "balance" => 1,
                "subscribe" => 0
            ]));

            $balanceResponse = json_decode($client->receive(), true);
            $client->close();

            if (isset($balanceResponse['error'])) {
                throw new Exception('Balance error: ' . $balanceResponse['error']['message']);
            }

            if (!isset($balanceResponse['balance'])) {
                throw new Exception('Invalid balance response');
            }

            $result = [
                'balance' => $balanceResponse['balance']['balance'],
                'currency' => $balanceResponse['balance']['currency'] ?? 'USD',
                'account' => $authResponse['authorize']['loginid'],
                'timestamp' => time()
            ];

            // Cache for 1 minute
            $this->cache->save($cacheKey, $result, 60);
            log_message('info', 'Balance fetched: ' . $result['balance'] . ' ' . $result['currency']);

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Deriv API Error: ' . $e->getMessage());

            // Return cached data if available
            $staleCache = $this->cache->get($cacheKey);
            if ($staleCache !== false) {
                $staleCache['stale'] = true;
                log_message('debug', 'Using stale cache data');
                return $staleCache;
            }

            return [
                'error' => $e->getMessage(),
                'balance' => 0,
                'currency' => 'USD'
            ];
        }
    }

    /**
     * Refresh Deriv balance via AJAX
     */
    public function refresh_deriv_balance()
    {
        header('Content-Type: application/json');

        if (!$this->input->is_ajax_request()) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
            return;
        }

        $deriv_token = $this->session->userdata('deriv_token');
        $expiry = $this->session->userdata('deriv_token_expiry');

        if (!$deriv_token) {
            echo json_encode(['status' => 'error', 'message' => 'No Deriv account connected']);
            return;
        }

        // Check token expiration
        if (time() > $expiry) {
            log_message('error', 'Token expired during balance refresh');
            echo json_encode([
                'status' => 'error',
                'message' => 'Token expired. Please reauthenticate.'
            ]);
            return;
        }

        $balance = $this->getDerivBalance($deriv_token);

        // Calculate KES equivalent
        $balance_kes = 0;
        if (isset($balance['balance']) && isset($this->data['buyrate'])) {
            $balance_kes = $balance['balance'] * $this->data['buyrate'];
        }

        if (isset($balance['error'])) {
            echo json_encode([
                'status' => 'error',
                'message' => $balance['error'],
                'balance' => number_format($balance['balance'], 2),
                'currency' => $balance['currency'],
                'balance_kes' => number_format($balance_kes, 2),
                'account' => $balance['account'] ?? ''
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'balance' => number_format($balance['balance'], 2),
                'currency' => $balance['currency'],
                'balance_kes' => number_format($balance_kes, 2),
                'account' => $balance['account']
            ]);
        }
    }



    public function transactions()
    {
        $url = APP_INSTANCE . 'home_data';
        $session_id = $this->session->userdata('session_id');
        $body = array(
            'session_id' => $session_id,
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);
        $status = $decode['status'];
        $message = $decode['message'];
        $data = $decode['data'];

        if ($status == 'fail') {
            $this->session->set_flashdata('msg', $message);
            redirect('logout');
        } elseif ($status == 'success') {
            $response = $data;
            $this->load->view('includes/header');
            $this->load->view('transactions', $response);
            $this->load->view('includes/footer');
        } else {
            $this->session->set_flashdata('msg', 'something went wrong');
            redirect('logout');
        }
    }

    // Account Management
    public function changepassword()
    {
        $this->load->view('includes/header');
        $this->load->view('changepassword');
        $this->load->view('includes/footer');
    }

    public function passwordupdate()
    {
        $url = APP_INSTANCE . 'passwordupdate';
        $session_id = $this->session->userdata('session_id');

        $password = $this->input->post('password');
        $confirmpassword = $this->input->post('confirmpassword');

        $body = array(
            'session_id' => $session_id,
            'password' => $password,
            'confirmpassword' => $confirmpassword
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);
        $status = $decode['status'];
        $message = $decode['message'];
        $data = $decode['data'];
        if ($status == 'fail') {
            $this->session->set_flashdata('msg', $message);
            redirect('logout');
        } elseif ($status == 'success') {
            $this->session->set_flashdata('msg', $message);
            redirect('home');
        } else {
            $this->session->set_flashdata('msg', 'something went wrong');
            redirect('logout');
        }
    }

    // Transaction Functions
    public function WithdrawFromDeriv()
    {
        $url = APP_INSTANCE . 'deriv_withdraw';
        $session_id = $this->session->userdata('session_id');
        $crNumber = $this->input->post('crNumber_withdraw');
        $amount = $this->input->post('deriv_amount');
        $body = array(
            'session_id' => $session_id,
            'crNumber' => $crNumber,
            'amount' => $amount
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);

        $status = $decode['status'];
        $message = $decode['message'];
        $data = $decode['data'];

        if ($status == 'fail') {
            $this->session->set_flashdata('msg', $message);
            redirect('logout');
        } elseif ($status == 'success' || $status == 'error') {
            $this->session->set_flashdata('msg', $message);
            redirect('home');
        } else {
            $this->session->set_flashdata('msg', 'something went wrong');
            redirect('logout');
        }
    }

    public function DepositFromMpesa()
    {
        $url = APP_INSTANCE . 'deposit_mpesa';
        $session_id = $this->session->userdata('session_id');
        $phone = $this->input->post('phone');
        $amount = $this->input->post('amount');

        $body = array(
            'session_id' => $session_id,
            'phone' => $phone,
            'amount' => $amount
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);

        $status = $decode['status'];
        $message = $decode['message'];
        $data = $decode['data'];

        if ($status == 'fail') {
            $this->session->set_flashdata('msg', $message);
            redirect('logout');
        } elseif ($status == 'success') {
            $this->session->set_flashdata('msg', $message);
            redirect('home');
        } else {
            $this->session->set_flashdata('msg', 'something went wrong');
            redirect('logout');
        }
    }

    public function WithdrawToMpesa()
    {
        $url = APP_INSTANCE . 'mpesa_withdraw';
        $session_id = $this->session->userdata('session_id');
        $phone = $this->input->post('phone');
        $amount = $this->input->post('amount');

        $body = array(
            'session_id' => $session_id,
            'phone' => $phone,
            'amount' => $amount
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);

        $status = $decode['status'];
        $message = $decode['message'];
        $data = $decode['data'];

        if ($status == 'fail') {
            $this->session->set_flashdata('msg', $message);
            redirect('logout');
        } elseif ($status == 'success' || $status == 'error') {
            $this->session->set_flashdata('msg', $message);
            redirect('home');
        } else {
            $this->session->set_flashdata('msg', 'something went wrong');
            redirect('logout');
        }
    }

    // Deposit to Deriv
    public function DepositToDeriv()
    {
        $url = APP_INSTANCE . 'deriv_deposit';
        $session_id = $this->session->userdata('session_id');
        $crNumber = $this->input->post('crNumber');
        $amount = $this->input->post('amount');
        $amount = number_format((float)$amount, 2, '.', '');
        $transaction_id = $this->transaction_id;

        $body = array(
            'session_id' => $session_id,
            'crNumber' => $crNumber,
            'amount' => $amount,
            'transaction_id' => $transaction_id,
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);

        $status = $decode['status'];
        $message = $decode['message'];
        $data = $decode['data'];

        if ($status == 'fail') {
            $this->session->set_flashdata('msg', $message);
            redirect('home');
        } elseif ($status == 'success') {
            $this->session->set_flashdata('msg', $message);
            redirect('home');
        } elseif ($status == 'error') {
            $this->session->set_flashdata('msg', $message);
            redirect('home');
        } else {
            $this->session->set_flashdata('msg', 'something went wrong');
            redirect('logout');
        }
    }

    // Deriv Functions
    public function initiate($amount, $crNumber)
    {
        $url = APP_INSTANCE . 'home_data';
        $session_id = $this->session->userdata('session_id');

        $body = array(
            'session_id' => $session_id,
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);
        $data = $decode['data'];
        $buy_rate = $data['buyrate'];
        $sell_rate = $data['sellrate'];

        $conversionRate = $buy_rate;
        $amountUSD = ($amount / $conversionRate);

        $send_url = APP_INST . 'home.php';
        $req_id = $this->transaction_id;

        $send_body = array(
            'req_id' => $req_id,
            'amount' => $amountUSD,
            'crNumber' => $crNumber,
        );

        $apiResponse = $this->Operations->CurlPost($send_url, $send_body);
        echo $apiResponse;
    }

    public function balance()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');

        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $json_string = json_encode($data, JSON_PRETTY_PRINT);
        $req_id = $data['balance']['req_id'];
        $file_path = 'derivtransactions/derivdepositing' . date('Y-m-d H:i:s') . '.json';
        file_put_contents($file_path, $json_string);
        $this->process_request($req_id);
    }

    // Payment Processing
    public function checkout()
    {
        // Checkout functionality
    }

    public function process_checkout()
    {
        $amount = $this->input->post('amount');
        $uniqueId = $this->input->post('unique_id');
        $partner_id = $this->input->post('partner_id');

        if ($amount && $uniqueId) {
            $token = $this->generateToken($amount, $uniqueId, $partner_id);
            $this->session->set_userdata('checkout_token', $token);
            redirect("https://stepakash.com/login");
        } else {
            redirect('testing');
        }
    }

    private function generateToken($amount, $uniqueId, $partner_id)
    {
        $data = array(
            'amount' => $amount,
            'uniqueId' => $uniqueId,
            'partner_id' => $partner_id,
        );

        $token = $this->encryption->encrypt(json_encode($data));
        return $token;
    }

    public function stepakash_callback()
    {
        if ($this->session->userdata('checkout_token')) {
            $token = $this->session->userdata('checkout_token');
            $decoded_data = json_decode($this->encryption->decrypt($token), true);

            if ($this->validateTokenData($decoded_data)) {
                $this->complete_payment($decoded_data);
            } else {
                redirect('checkout');
            }
        } else {
            redirect('checkout');
        }
    }

    private function validateTokenData($data)
    {
        return isset($data['amount']) && isset($data['partner_id']) && isset($data['uniqueId']) &&
            is_numeric($data['amount']) && $data['amount'] > 0;
    }

    private function complete_payment($data)
    {
        echo "Payment completed successfully!";
    }


    public function invoice_payment()
    {
        // Invoice payment functionality updated
    }

    // Logout Functionality
    public function logout()
    {
        redirect('logout');
    }
}
