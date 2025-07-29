<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use WebSocket\Client;

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
            $response = $data;
            $this->load->view('includes/header');
            $this->load->view('home', $response);
            $this->load->view('includes/footer', $response);
        } else {
            $this->session->set_flashdata('msg', 'something went wrong');
            redirect('logout');
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

        // Get user's Deriv token from session or database
        $deriv_token = $this->session->userdata('deriv_token');
        if (!$deriv_token) {
            // Try to get from database if not in session
            $this->load->model('User_model');
            $user = $this->User_model->get_user_by_wallet_id($this->session->userdata('wallet_id'));
            $deriv_token = $user->deriv_token ?? null;
        }

        if (!$deriv_token) {
            $this->session->set_flashdata('msg', 'Deriv account not connected');
            redirect('home');
            return;
        }

        // First verify the withdrawal with our backend
        $body = array(
            'session_id' => $session_id,
            'crNumber' => $crNumber,
            'amount' => $amount,
            'deriv_token' => $deriv_token
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);

        $status = $decode['status'] ?? null;
        $message = $decode['message'] ?? 'Unknown error';
        $data = $decode['data'] ?? null;

        if ($status === 'success') {
            // If backend verification succeeds, proceed with actual Deriv withdrawal
            $withdrawal_result = $this->processDerivWithdrawal($deriv_token, $crNumber, $amount);

            if ($withdrawal_result['success']) {
                $this->session->set_flashdata('msg', 'Withdrawal successful: ' . $withdrawal_result['message']);
            } else {
                $this->session->set_flashdata('msg', 'Withdrawal failed: ' . $withdrawal_result['message']);
            }
        } else {
            $this->session->set_flashdata('msg', $message);
        }

        redirect('home');
    }

    private function processDerivWithdrawal($token, $account_number, $amount)
    {
        try {
            // Connect to Deriv WebSocket
            $app_id = '76420'; // Your Deriv app ID
            $websocket_url = "wss://ws.derivws.com/websockets/v3?app_id={$app_id}&brand=deriv";

            // Initialize WebSocket connection
            $socket = new WebSocket\Client($websocket_url);

            // Authenticate with the token
            $auth_request = [
                "authorize" => $token
            ];
            $socket->send(json_encode($auth_request));

            // Wait for authorization response
            $auth_response = json_decode($socket->receive(), true);

            if (isset($auth_response['error'])) {
                return [
                    'success' => false,
                    'message' => $auth_response['error']['message'] ?? 'Authorization failed'
                ];
            }

            // Prepare withdrawal request
            $withdrawal_request = [
                "paymentagent_withdraw" => 1,
                "amount" => (float)$amount,
                "currency" => "USD",
                "paymentagent_loginid" => $account_number,
                "req_id" => (int)microtime(true)
            ];

            $socket->send(json_encode($withdrawal_request));

            // Get withdrawal response
            $withdrawal_response = json_decode($socket->receive(), true);

            $socket->close();

            if (isset($withdrawal_response['error'])) {
                return [
                    'success' => false,
                    'message' => $withdrawal_response['error']['message'] ?? 'Withdrawal failed'
                ];
            }

            if ($withdrawal_response['paymentagent_withdraw'] == 1) {
                return [
                    'success' => true,
                    'message' => 'Withdrawal processed successfully. Transaction ID: ' .
                        ($withdrawal_response['transaction_id'] ?? 'N/A'),
                    'transaction_id' => $withdrawal_response['transaction_id'] ?? null
                ];
            }

            return [
                'success' => false,
                'message' => 'Withdrawal request was not processed'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'WebSocket error: ' . $e->getMessage()
            ];
        }
    }


    // public function getDerivBalance()
    // {
    //     header('Content-Type: application/json');

    //     $session_id = $this->session->userdata('session_id');
    //     if (!$session_id) {
    //         echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
    //         return;
    //     }

    //     // Get user's Deriv token
    //     $deriv_token = $this->session->userdata('deriv_token');
    //     if (!$deriv_token) {
    //         $this->load->model('User_model');
    //         $user = $this->User_model->get_user_by_session($session_id);
    //         $deriv_token = $user->deriv_token ?? null;
    //     }

    //     if (!$deriv_token) {
    //         echo json_encode(['status' => 'error', 'message' => 'Deriv account not connected']);
    //         return;
    //     }

    //     try {
    //         $app_id = '76420'; // Your Deriv app ID
    //         $websocket_url = "wss://ws.derivws.com/websockets/v3?app_id={$app_id}&brand=deriv";

    //         $socket = new WebSocket\Client($websocket_url);

    //         // Authenticate
    //         $auth_request = ["authorize" => $deriv_token];
    //         $socket->send(json_encode($auth_request));
    //         $auth_response = json_decode($socket->receive(), true);

    //         if (isset($auth_response['error'])) {
    //             throw new Exception($auth_response['error']['message'] ?? 'Authorization failed');
    //         }

    //         // Get balance
    //         $balance_request = ["get_account_status" => 1];
    //         $socket->send(json_encode($balance_request));
    //         $balance_response = json_decode($socket->receive(), true);

    //         $socket->close();

    //         if (isset($balance_response['error'])) {
    //             throw new Exception($balance_response['error']['message'] ?? 'Failed to get balance');
    //         }

    //         $balance = $balance_response['get_account_status']['balance'] ?? 0;
    //         $currency = $balance_response['get_account_status']['currency'] ?? 'USD';

    //         echo json_encode([
    //             'status' => 'success',
    //             'balance' => $balance,
    //             'currency' => $currency
    //         ]);
    //     } catch (Exception $e) {
    //         echo json_encode([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }

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
