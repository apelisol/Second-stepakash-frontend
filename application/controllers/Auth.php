<?php
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Operations');
        
    }

    // ==================== VIEW METHODS ====================

    public function index()
    {
        $this->load->view('login');
    }

    /**
     * DerivAuthPage method
     * This method loads the Deriv authorization page.
     * It is used to initiate the Deriv OAuth process.
     */
    
    public function derivauth()
    {
        $this->load->view('derivauth');
    }

   /**
     * Updated Signup method to handle Deriv data properly
     */
    public function Signup()
    {
        $data = [];
        
        // Check for Deriv auth success in URL
        $deriv_auth = $this->input->get('deriv_auth');
        
        if ($deriv_auth === 'success') {
            // Get Deriv data from session
            $deriv_data = $this->session->userdata('deriv_data');
            
            if ($deriv_data) {
                $data['deriv_data'] = $deriv_data;
                
                // If we have multiple accounts, pass them to the view
                if (!empty($deriv_data['all_deriv_accounts'])) {
                    $data['deriv_accounts'] = $deriv_data['all_deriv_accounts'];
                }
                
                // Debug: Log the data being passed to view
                log_message('debug', 'Deriv data passed to signup view: ' . print_r($deriv_data, true));
            } else {
                $this->session->set_flashdata('msg', 'Session expired. Please connect your Deriv account again.');
                redirect(base_url('Auth/derivauth'));
                return;
            }
        } else {
            // Handle direct access to signup page (check if coming from callback with params)
            $acct1 = $this->input->get('acct1');
            $token1 = $this->input->get('token1');
            
            if ($acct1 && $token1) {
                // This means we're coming directly from Deriv callback with params in URL
                // Process the callback data here
                $this->processDerivCallback();
                return; // processDerivCallback will handle the redirect
            }
        }
        
        $this->load->view('signup', $data);
    }

    /**
     * Process Deriv callback when parameters come directly to signup URL
     * Now filters for USD accounts only and excludes demo accounts
     */
    private function processDerivCallback()
    {
        // Extract account data from URL parameters
        $accounts = [];
        $i = 1;
        
        // Loop through all possible account parameters
        while ($this->input->get("acct$i") && $this->input->get("token$i")) {
            $account_number = $this->input->get("acct$i");
            $currency = $this->input->get("cur$i") ?: 'USD';
            
            // Only process USD accounts that are not demo accounts (check for CR prefix)
            if (strtoupper($currency) === 'USD' && strpos($account_number, 'CR') === 0) {
                $accounts[] = [
                    'account_number' => $account_number,
                    'token' => $this->input->get("token$i"),
                    'currency' => $currency,
                    'is_real' => true
                ];
            }
            $i++;
        }

        if (empty($accounts)) {
            $this->session->set_flashdata('msg', 'No eligible USD Deriv accounts found (demo accounts not allowed)');
            redirect(base_url('Auth/derivauth'));
            return;
        }

        $primary_account = $accounts[0];
        $additional_info = $this->getDerivAccountInfo($primary_account['token']);
        
        $deriv_data = [
            'deriv_token' => $primary_account['token'],
            'deriv_login_id' => $primary_account['account_number'],
            'deriv_account_number' => $primary_account['account_number'],
            'deriv_currency' => $primary_account['currency'],
            'all_deriv_accounts' => $accounts,
            'deriv_email' => $additional_info['email'] ?? '',
            'fullname' => $additional_info['fullname'] ?? '',
            'is_real_account' => true // Explicitly mark as real account
        ];
        
        $this->session->set_userdata('deriv_data', $deriv_data);
        redirect(base_url('Auth/Signup?deriv_auth=success'));
    }



    /** VerifyOtp method
     * This method loads the OTP verification page.
     * It is used after sending an OTP for password recovery.
     */
    public function verifyotp()
    {
        $this->load->view('otp');
    }

    public function resetpassword()
    {
        $this->load->view('resetpassword');
    }

    public function forgotpassword()
    {
        $this->load->view('forgotpassword');
    }

    public function checkout()
    {
        $this->load->view('checkout');
    }

    // ==================== AUTHENTICATION METHODS ====================

    public function Login()
    {
        $url = APP_INSTANCE . 'login';
        $phone = $this->input->post('phone');
        $password = $this->input->post('password');
        $ip_address = $this->getUserIP();

        $body = array(
            'phone' => $phone,
            'password' => $password,
            'ip_address' => $ip_address,
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);

        log_message('error', 'Login API Response: ' . print_r($decode, true));

        $status = isset($decode['status']) ? $decode['status'] : null;
        $message = isset($decode['message']) ? $decode['message'] : 'Unknown error';
        $data = isset($decode['data']) ? $decode['data'] : null;

        $this->session->set_flashdata('msg', $message);

        if ($status === 'fail') {
            redirect(base_url());
        } elseif ($status === 'success') {
            if ($data) {
                $this->session->set_userdata($data);
                redirect('home');
            } else {
                log_message('error', 'Login API returned success but no data: ' . print_r($decode, true));
                $this->session->set_flashdata('msg', 'Something went wrong');
                redirect(base_url());
            }
        } else {
            log_message('error', 'Unexpected status in Login API: ' . $status);
            $this->session->set_flashdata('msg', 'Please try again');
            redirect(base_url());
        }
    }

    /**
    * Handle user account creation
    */
    public function CreateAccount()
    {
        $url = APP_INSTANCE . 'signup';

        $phone = $this->input->post('phone');
        $password = $this->input->post('password');
        $confirmpassword = $this->input->post('confirmpassword');
        $account_number = $this->input->post('account_number');
        
        // Get optional Deriv data
        $deriv_account = $this->input->post('deriv_account');
        $deriv_token = $this->input->post('deriv_token');
        $deriv_email = $this->input->post('deriv_email');
        $deriv_login_id = $this->input->post('deriv_login_id');
        $deriv_account_number = $this->input->post('deriv_account_number');
        $fullname = $this->input->post('fullname');

        $body = array(
            'phone' => $phone,
            'password' => $password,
            'confirmpassword' => $confirmpassword,
            'account_number' => $account_number,
        );

        // Add optional Deriv fields if they exist
        if (!empty($deriv_account)) {
            $body['deriv_account'] = $deriv_account;
        }
        
        if (!empty($deriv_token)) {
            $body['deriv_token'] = $deriv_token;
        }
        
        if (!empty($deriv_email)) {
            $body['deriv_email'] = $deriv_email;
        }
        
        if (!empty($deriv_login_id)) {
            $body['deriv_login_id'] = $deriv_login_id;
        }
        
        if (!empty($deriv_account_number)) {
            $body['deriv_account_number'] = $deriv_account_number;
        }
        
        if (!empty($fullname)) {
            $body['fullname'] = $fullname;
        }

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);

        $status = $decode['status'];
        $message = $decode['message'];

        if ($status == 'fail') {
            $this->session->set_flashdata('msg', $message);
            redirect('Auth/Signup');
        } elseif ($status == 'success') {
            $this->session->set_flashdata('msg', $message);
            redirect('login');
        } else {
            $this->session->set_flashdata('msg', 'Something went wrong');
            redirect('Auth/Signup');
        }
    }


    /**                                 
     * Logout method
     * FIXED: Improved session handling and redirection
     */
    public function logout()
    {
        if (isset($_SESSION['wallet_id']) && $_SESSION['phone'] === true) {
            $this->session->sess_destroy();
            foreach ($_SESSION as $key => $value) {
                unset($_SESSION[$key]);
            }
            redirect(base_url());
        } else {
            $this->session->sess_destroy();
            foreach ($_SESSION as $key => $value) {
                unset($_SESSION[$key]);
            }
            redirect(base_url());
        }
    }

    /**
    * Initiate Deriv OAuth process
    * FIXED: Ensure correct redirect URI
    */
    public function DerivOAuth()
    {
        header('Content-Type: application/json');
        
        $response = array();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(400);
            $response['status'] = 'fail';
            $response['message'] = 'Only GET request allowed';
            echo json_encode($response);
            return;
        }
        
        // Deriv OAuth configuration
        $app_id = '76420'; 
        $redirect_uri = base_url() . 'Auth/DerivCallback'; 
        $scope = 'read,trade,trading_information,payments'; 
        $state = bin2hex(random_bytes(16)); 
        
        // Store state in session for validation
        $this->session->set_userdata('oauth_state', $state);
        
        // Deriv OAuth URL
        $oauth_url = "https://oauth.deriv.com/oauth2/authorize?" . http_build_query([
            'app_id' => $app_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => $scope,
            'state' => $state
        ]);
        
        $response['status'] = 'success';
        $response['oauth_url'] = $oauth_url;
        
        echo json_encode($response);
    }

   /**
     * Handle Deriv OAuth callback
     * Updated to filter for USD accounts only and exclude demo accounts
     */
    public function DerivCallback()
    {
        // Verify state first
        $received_state = $this->input->get('state');
        $stored_state = $this->session->userdata('oauth_state');
        
        if ($received_state !== $stored_state) {
            $this->session->set_flashdata('msg', 'Invalid state parameter');
            redirect(base_url('Auth/Signup'));
            return;
        }
        
        // Extract account data from URL parameters
        $accounts = [];
        $i = 1;
        
        // Loop through all possible account parameters
        while ($this->input->get("acct$i") && $this->input->get("token$i")) {
            $account_number = $this->input->get("acct$i");
            $currency = $this->input->get("cur$i") ?: 'USD';
            
            // Only process USD accounts that are not demo accounts (check for CR prefix)
            if (strtoupper($currency) === 'USD' && strpos($account_number, 'CR') === 0) {
                $accounts[] = [
                    'account_number' => $account_number,
                    'token' => $this->input->get("token$i"),
                    'currency' => $currency,
                    'is_real' => true
                ];
            }
            $i++;
        }

        if (empty($accounts)) {
            $this->session->set_flashdata('msg', 'No eligible USD Deriv accounts found (demo accounts not allowed)');
            redirect(base_url('Auth/derivauth'));
            return;
        }

        $primary_account = $accounts[0];
        $additional_info = $this->getDerivAccountInfo($primary_account['token']);
        
        $deriv_data = [
            'deriv_token' => $primary_account['token'],
            'deriv_login_id' => $primary_account['account_number'],
            'deriv_account_number' => $primary_account['account_number'],
            'deriv_currency' => $primary_account['currency'],
            'all_deriv_accounts' => $accounts,
            'deriv_email' => $additional_info['email'] ?? '',
            'fullname' => $additional_info['fullname'] ?? '',
            'is_real_account' => true // Explicitly mark as real account
        ];
        
        $this->session->set_userdata('deriv_data', $deriv_data);
        $this->session->unset_userdata('oauth_state');
        redirect(base_url('Auth/Signup?deriv_auth=success'));
    }


    /**
     * Get Deriv account information using WebSocket or HTTP API
     * FIXED: Improved error handling and response structure
     */
    private function getDerivAccountInfo($token)
    {
        try {
            // Use WebSocket API to get account information
            $url = 'wss://ws.derivws.com/websockets/v3?app_id=76420';
            
            // For HTTP API approach (alternative)
            $post_data = json_encode([
                'get_settings' => 1,
                'req_id' => 1
            ]);
            
            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.deriv.com/api/v1/');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($http_code === 200 && $response) {
                $data = json_decode($response, true);
                
                if (isset($data['get_settings'])) {
                    return [
                        'email' => $data['get_settings']['email'] ?? '',
                        'fullname' => trim(($data['get_settings']['first_name'] ?? '') . ' ' . ($data['get_settings']['last_name'] ?? '')),
                        'country' => $data['get_settings']['country'] ?? '',
                        'currency' => $data['get_settings']['currency'] ?? 'USD'
                    ];
                }
            }
            
            return [];
            
        } catch (Exception $e) {
            log_message('error', 'Failed to get Deriv account info: ' . $e->getMessage());
            return [];
        }
    }
        
   

   /**
     * Get Deriv session data after successful auth
     * FIXED: This method should return the session data stored during callback
     */
    public function GetDerivSessionData()
    {
        // Set JSON header
        header('Content-Type: application/json');
        
        // Get Deriv data from session (stored during callback)
        $deriv_data = $this->session->userdata('deriv_data');
        
        if ($deriv_data) {
            echo json_encode([
                'status' => 'success',
                'data' => $deriv_data
            ]);
        } else {
            echo json_encode([
                'status' => 'fail',
                'message' => 'No Deriv account information found in session'
            ]);
        }
    }

    // ==================== PASSWORD RECOVERY METHODS ====================

    public function sendotp()
    {
        $url = APP_INSTANCE . 'sendotp';
        $phone = $this->input->post('phone');

        $body = array(
            'phone' => $phone,
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);
        
        $status = $decode['status'];
        $message = $decode['message'];
        $data = $decode['data'];
        
        $this->session->set_flashdata('msg', $message);

        if ($status == 'fail') {
            redirect('forgotpassword');
        } elseif ($status == 'success') {
            $this->session->set_userdata($data);
            redirect('verifyotp');
        } else {
            $this->session->set_flashdata('msg', 'something went wrong');
            redirect('logout');
        }
    }

    public function ConfirmOtp()
    {
        $url = APP_INSTANCE . 'verifyotp';
        $otp = $this->input->post('otp');

        $body = array(
            'otp' => $otp,
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);
        
        $status = $decode['status'];
        $message = $decode['message'];
        $data = $decode['data'];
        
        $this->session->set_flashdata('msg', $message);

        if ($status == 'fail') {
            redirect('logout');
        } elseif ($status == 'success') {
            $this->session->set_userdata($data);
            redirect('resetpassword');
        } else {
            $this->session->set_flashdata('msg', 'something went wrong');
            redirect('logout');
        }
    }

    public function updatepassword()//function to update password 
    {
        $url = APP_INSTANCE . 'updatepassword';
        $pass1 = $this->input->post('password');
        $pass2 = $this->input->post('confirmpassword');
        $phone = $this->session->userdata('phone');
        $wallet_id = $this->session->userdata('wallet_id');

        $body = array(
            'phone' => $phone,
            'wallet_id' => $wallet_id,
            'confirmpassword' => $pass2,
            'password' => $pass1,
        );

        $response = $this->Operations->CurlPost($url, $body);
        $decode = json_decode($response, true);
        
        $status = $decode['status'];
        $message = $decode['message'];

        if ($status == 'fail') {
            $this->session->set_flashdata('msg', $message);
            redirect('logout');
        } elseif ($status == 'success') {
            $this->session->set_flashdata('msg', $message);
            redirect(base_url());
        } else {
            $this->session->set_flashdata('msg', 'something went wrong');
            redirect(base_url());
        }
    }

    // ==================== UTILITY METHODS ====================

    public function getUserIP()
    {
        $ip = null;

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if (strpos($ip, ',') !== false) {
            $ipList = explode(',', $ip);
            $ip = trim($ipList[0]);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip = '::ffff:' . $ip;
        }

        return $ip;
    }

    private function validateemail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function clean_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    private function hash_password($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    private function verify_password_hash($password, $hash)
    {
        return password_verify($password, $hash);
    }

    // ==================== JWT METHODS ====================

    public function ValidateToken()
    {
        $header = getallheaders();

        if ($header == "") {
            echo json_encode(array('message' => 'Access Denied'));
        }

        $authcode = trim($header['Authorization']);
        if ($authcode == "") {
            echo json_encode(array('message' => 'Authorization Token not Set'));
            die();
        }

        if ($authcode != "") {
            $token = substr($authcode, 7);
            $response = $this->ValidateJwt($token);
            print_r($response);
        }
    }

    public function ValidateJwt($jwt, $secret = 'secret')
    {
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];

        $expiration = json_decode($payload)->exp;
        $is_token_expired = ($expiration - time()) < 0;

        $base64_url_header = $this->base64url_encode($header);
        $base64_url_payload = $this->base64url_encode($payload);
        $signature = hash_hmac('SHA256', "$base64_url_header.$base64_url_payload", $secret, true);
        $base64_url_signature = $this->base64url_encode($signature);

        $is_signature_valid = ($base64_url_signature === $signature_provided);

        if ($is_token_expired || !$is_signature_valid) {
            return json_encode(array('message' => "Timeout"));
        } else {
            $decoded = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $jwt)[1]))));
            $arr = ['message' => 'Access Allowed', 'status' => 200, 'data' => $decoded];
            return json_encode($arr);
        }
    }

    function generate_jwt($payload, $secret = 'secret')
    {
        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $headers_encoded = $this->base64url_encode(json_encode($headers));
        $payload_encoded = $this->base64url_encode(json_encode($payload));
        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);
        $signature_encoded = $this->base64url_encode($signature);
        $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";
        
        return $jwt;
    }

    function base64url_encode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}