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
        } elseif ($status == 'success' || $status == 'error') {
            // Get user's Deriv details from database
            $wallet_id = $this->session->userdata('wallet_id');
            $user_condition = array('wallet_id' => $wallet_id);
            $user_data = $this->Operations->SearchByCondition('customers', $user_condition);

            if (!empty($user_data) && $user_data[0]['deriv_account'] == 1 && !empty($user_data[0]['deriv_token'])) {
                // User has Deriv account connected
                $deriv_token = $user_data[0]['deriv_token'];
                $deriv_balance_data = $this->getDerivBalance($deriv_token);

                if ($deriv_balance_data && !isset($deriv_balance_data['error'])) {
                    $data['deriv_connected'] = true;
                    $data['deriv_balance'] = $deriv_balance_data['balance'];
                    $data['deriv_currency'] = $deriv_balance_data['currency'];
                    $data['deriv_account'] = $deriv_balance_data['account'];
                    $data['deriv_last_update'] = $deriv_balance_data['timestamp'];

                    // Calculate equivalent KES value using buy rate
                    $data['deriv_balance_kes'] = $deriv_balance_data['balance'] * $data['buyrate'];

                    // Store Deriv token in session for AJAX requests
                    $this->session->set_userdata('deriv_token', $deriv_token);
                    $this->session->set_userdata('deriv_account', $deriv_balance_data['account']);
                } else {
                    $data['deriv_connected'] = false;
                    $data['deriv_error'] = isset($deriv_balance_data['error']) ? $deriv_balance_data['error'] : 'Failed to fetch balance';
                }
            } else {
                $data['deriv_connected'] = false;
            }

            $this->load->view('includes/header');
            $this->load->view('home', $data);
            $this->load->view('includes/footer', $data);
        } else {
            $this->session->set_flashdata('msg', 'something went wrong');
            redirect('logout');
        }
    }


    private function getDerivBalance($token)
    {
        // Check for cached balance first (valid for 2 minutes)
        $cacheKey = 'deriv_balance_' . md5($token);
        $cached = $this->cache->get($cacheKey);

        if ($cached !== false) {
            return $cached;
        }

        try {
            $appId = 76420; // Your app ID
            $url = "wss://ws.derivws.com/websockets/v3?app_id={$appId}";

            // Create WebSocket client with proper options
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);

            $client = new \WebSocket\Client($url, [
                'timeout' => 10,
                'headers' => [
                    'Origin' => base_url(),
                    'User-Agent' => 'Stepakash/1.0'
                ],
            ], ['context' => $context]);

            // Step 1: Authorize with token
            $authRequest = json_encode([
                "authorize" => $token
            ]);

            $client->send($authRequest);
            $authResponse = $client->receive();
            $authData = json_decode($authResponse, true);

            if (isset($authData['error'])) {
                $client->close();
                throw new Exception('Authorization failed: ' . $authData['error']['message']);
            }

            if (!isset($authData['authorize'])) {
                $client->close();
                throw new Exception('Invalid authorization response');
            }

            // Step 2: Get balance
            $balanceRequest = json_encode([
                "balance" => 1,
                "subscribe" => 0 // Don't subscribe, just get current balance
            ]);

            $client->send($balanceRequest);
            $balanceResponse = $client->receive();
            $balanceData = json_decode($balanceResponse, true);

            $client->close();

            if (isset($balanceData['error'])) {
                throw new Exception('Balance request failed: ' . $balanceData['error']['message']);
            }

            if (!isset($balanceData['balance'])) {
                throw new Exception('Invalid balance response structure');
            }

            $result = [
                'balance' => floatval($balanceData['balance']['balance']),
                'currency' => $balanceData['balance']['currency'] ?? 'USD',
                'account' => $authData['authorize']['loginid'],
                'timestamp' => time(),
                'status' => 'success'
            ];

            // Cache the result for 2 minutes
            $this->cache->save($cacheKey, $result, 120);

            // Update database with latest balance
            $this->updateDerivBalanceInDB($this->session->userdata('wallet_id'), $result);

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Deriv Balance API Error: ' . $e->getMessage());

            // Try to return cached data if available, even if expired
            $staleCache = $this->cache->get($cacheKey . '_stale');
            if ($staleCache !== false) {
                $staleCache['stale'] = true;
                $staleCache['error_message'] = $e->getMessage();
                return $staleCache;
            }

            // Return error response
            return [
                'error' => $e->getMessage(),
                'balance' => 0.00,
                'currency' => 'USD',
                'account' => '',
                'timestamp' => time(),
                'status' => 'error'
            ];
        }
    }
    private function updateDerivBalanceInDB($wallet_id, $balance_data)
    {
        try {
            $update_data = [
                'deriv_balance' => $balance_data['balance'],
                'deriv_currency' => $balance_data['currency'],
                'deriv_last_sync' => date('Y-m-d H:i:s')
            ];

            $condition = ['wallet_id' => $wallet_id];
            $this->Operations->UpdateData('customers', $condition, $update_data);
        } catch (Exception $e) {
            log_message('error', 'Failed to update Deriv balance in DB: ' . $e->getMessage());
        }
    }

    /**
     * AJAX endpoint to refresh Deriv balance
     */
    public function refresh_deriv_balance()
    {
        header('Content-Type: application/json');

        if (!$this->input->is_ajax_request()) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
            return;
        }

        $deriv_token = $this->session->userdata('deriv_token');
        if (!$deriv_token) {
            echo json_encode(['status' => 'error', 'message' => 'No Deriv account connected']);
            return;
        }

        // Force refresh by clearing cache
        $cacheKey = 'deriv_balance_' . md5($deriv_token);
        $this->cache->delete($cacheKey);

        $balance_data = $this->getDerivBalance($deriv_token);

        if (isset($balance_data['error'])) {
            echo json_encode([
                'status' => 'error',
                'message' => $balance_data['error'],
                'balance' => '0.00',
                'currency' => 'USD'
            ]);
            return;
        }

        // Get current rates to calculate KES equivalent
        $url = APP_INSTANCE . 'home_data';
        $session_id = $this->session->userdata('session_id');
        $body = array('session_id' => $session_id);
        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);

        $buyrate = isset($decode['data']['buyrate']) ? $decode['data']['buyrate'] : 130; // fallback rate
        $balance_kes = $balance_data['balance'] * $buyrate;

        echo json_encode([
            'status' => 'success',
            'balance' => number_format($balance_data['balance'], 2),
            'currency' => $balance_data['currency'],
            'balance_kes' => number_format($balance_kes, 2),
            'account' => $balance_data['account'],
            'last_update' => date('H:i:s', $balance_data['timestamp']),
            'is_stale' => isset($balance_data['stale']) ? $balance_data['stale'] : false
        ]);
    }

    /**
     * Connect user's Deriv account
     */
    public function connect_deriv_account()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('home');
        }

        $deriv_token = $this->input->post('deriv_token');
        $wallet_id = $this->session->userdata('wallet_id');

        if (empty($deriv_token) || empty($wallet_id)) {
            $this->session->set_flashdata('msg', 'Invalid request data');
            redirect('home');
        }

        // Validate token by testing balance fetch
        $balance_test = $this->getDerivBalance($deriv_token);

        if (isset($balance_test['error'])) {
            $this->session->set_flashdata('msg', 'Invalid Deriv token: ' . $balance_test['error']);
            redirect('home');
        }

        // Save Deriv details to database
        $deriv_data = [
            'deriv_account' => 1,
            'deriv_token' => $deriv_token,
            'deriv_login_id' => $balance_test['account'],
            'deriv_currency' => $balance_test['currency'],
            'deriv_balance' => $balance_test['balance'],
            'deriv_verified' => 1,
            'deriv_verification_date' => date('Y-m-d H:i:s'),
            'deriv_last_sync' => date('Y-m-d H:i:s')
        ];

        $condition = ['wallet_id' => $wallet_id];
        $update_result = $this->Operations->UpdateData('customers', $condition, $deriv_data);

        if ($update_result) {
            $this->session->set_flashdata('msg', 'Deriv account connected successfully');
        } else {
            $this->session->set_flashdata('msg', 'Failed to connect Deriv account');
        }

        redirect('home');
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
