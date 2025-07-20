<?php

/**
 * Auth Controller
 * Handles user authentication, registration, password reset, and JWT operations
 */
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Operations');
    }

    // ========================================
    // VIEW METHODS
    // ========================================

    /**
     * Display login page
     */
    public function index()
    {
        $this->load->view('login');
    }

    /**
     * Display signup page
     */
    public function Signup()
    {
        $this->load->view('signup');
    }

    /**
     * Display forgot password page
     */
    public function forgotpassword()
    {
        $this->load->view('forgotpassword');
    }

    /**
     * Display OTP verification page
     */
    public function verifyotp()
    {
        $this->load->view('otp');
    }

    /**
     * Display reset password page
     */
    public function resetpassword()
    {
        $this->load->view('resetpassword');
    }

    /**
     * Display checkout page
     */
    public function checkout()
    {
        $this->load->view('checkout');
    }

    // ========================================
    // AUTHENTICATION METHODS
    // ========================================

    /**
     * Handle user login
     */
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

        // Log the API response for debugging
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
            $this->session->set_flashdata('msg', 'Something went wrong');
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

        $body = array(
            'phone' => $phone,
            'password' => $password,
            'confirmpassword' => $confirmpassword,
            'account_number' => $account_number,
        );

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
     * Handle user logout
     */
    public function logout()
    {
        if (isset($_SESSION['wallet_id']) && $_SESSION['phone'] === true) {
            // Destroy session and clear all session data
            $this->session->sess_destroy();

            // Remove remaining session data
            foreach ($_SESSION as $key => $value) {
                unset($_SESSION[$key]);
            }

            redirect(base_url());
        } else {
            // Destroy session and clear all session data
            $this->session->sess_destroy();

            // Remove remaining session data
            foreach ($_SESSION as $key => $value) {
                unset($_SESSION[$key]);
            }

            redirect(base_url());
        }
    }

    // ========================================
    // PASSWORD RESET METHODS
    // ========================================

    /**
     * Send OTP for password reset
     */
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
            $this->session->set_flashdata('msg', 'Something went wrong');
            redirect('logout');
        }
    }

    /**
     * Confirm OTP verification
     */
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
            $this->session->set_flashdata('msg', 'Something went wrong');
            redirect('logout');
        }
    }

    /**
     * Update user password
     */
    public function updatepassword()
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
            $this->session->set_flashdata('msg', 'Something went wrong');
            redirect(base_url());
        }
    }

    // ========================================
    // UTILITY METHODS
    // ========================================

    /**
     * Get user's IP address
     * @return string IP address
     */
    public function getUserIP()
    {
        $ip = null;

        // Check if the IP is from a shared internet connection
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } 
        // Check if the IP is from a proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } 
        // Use the remote address if available
        elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // Additional checks for IP addresses
        if (strpos($ip, ',') !== false) {
            // If multiple IP addresses are provided (common in proxy configurations), get the first one
            $ipList = explode(',', $ip);
            $ip = trim($ipList[0]);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // If the IP is an IPv6 address, convert it to IPv4
            $ip = '::ffff:' . $ip;
        }

        return $ip;
    }

    /**
     * Validate email address
     * @param string $email Email to validate
     * @return bool True if valid, false otherwise
     */
    private function validateemail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Clean input data
     * @param string $data Data to clean
     * @return string Cleaned data
     */
    private function clean_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * Hash password using bcrypt
     * @param string $password Password to hash
     * @return string Hashed password
     */
    private function hash_password($password) 
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verify password against hash
     * @param string $password Plain text password
     * @param string $hash Hashed password
     * @return bool True if password matches, false otherwise
     */
    private function verify_password_hash($password, $hash)
    {
        return password_verify($password, $hash);
    }

    // ========================================
    // JWT TOKEN METHODS
    // ========================================

    /**
     * Generate JWT token
     * @param array $payload Token payload
     * @param string $secret Secret key for signing
     * @return string JWT token
     */
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

    /**
     * Base64 URL encode
     * @param string $str String to encode
     * @return string Encoded string
     */
    function base64url_encode($str) 
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    /**
     * Validate authorization token from headers
     */
    public function ValidateToken()
    {
        $header = getallheaders();

        if ($header == "") {
            echo json_encode(array('message' => 'Access Denied'));
            return;
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

    /**
     * Validate JWT token
     * @param string $jwt JWT token to validate
     * @param string $secret Secret key for validation
     * @return string JSON response
     */
    public function ValidateJwt($jwt, $secret = 'secret') 
    {
        // Split the JWT
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];

        // Check the expiration time
        $expiration = json_decode($payload)->exp;
        $is_token_expired = ($expiration - time()) < 0;

        // Build a signature based on the header and payload using the secret
        $base64_url_header = $this->base64url_encode($header);
        $base64_url_payload = $this->base64url_encode($payload);
        $signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
        $base64_url_signature = $this->base64url_encode($signature);

        // Verify it matches the signature provided in the JWT
        $is_signature_valid = ($base64_url_signature === $signature_provided);
        
        if ($is_token_expired || !$is_signature_valid) {
            return json_encode(array('message' => "Timeout"));
        } else {
            $decoded = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $jwt)[1]))));
            $arr = ['message' => 'Access Allowed', 'status' => 200, 'data' => $decoded];
            return json_encode($arr);
        }
    }
}