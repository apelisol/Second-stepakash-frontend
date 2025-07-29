<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Deriv WebSocket Helper Class
 * Handles all Deriv API operations including withdrawals, balance checks, and transactions
 */
class DerivWebSocketHelper 
{
    private $app_id;
    private $api_url;
    private $websocket_url;
    
    public function __construct() 
    {
        $this->app_id = '76420'; // Your registered app ID
        $this->api_url = 'https://api.deriv.com/api/v1/';
        $this->websocket_url = 'wss://ws.derivws.com/websockets/v3?app_id=' . $this->app_id;
    }
    
    /**
     * Get account balance
     */
    public function getBalance($token, $loginid) 
    {
        $request_data = [
            'balance' => 1,
            'account' => $loginid,
            'req_id' => $this->generateReqId()
        ];
        
        $response = $this->makeApiCall($token, $request_data);
        
        if ($response['success'] && isset($response['data']['balance'])) {
            return [
                'status' => 'success',
                'balance' => $response['data']['balance']['balance'] ?? 0,
                'currency' => $response['data']['balance']['currency'] ?? 'USD',
                'loginid' => $response['data']['balance']['loginid'] ?? $loginid
            ];
        }
        
        return [
            'status' => 'error',
            'message' => $response['error'] ?? 'Failed to fetch balance',
            'balance' => 0
        ];
    }
    
    /**
     * Get account settings/information
     */
    public function getAccountSettings($token) 
    {
        $request_data = [
            'get_settings' => 1,
            'req_id' => $this->generateReqId()
        ];
        
        $response = $this->makeApiCall($token, $request_data);
        
        if ($response['success'] && isset($response['data']['get_settings'])) {
            $settings = $response['data']['get_settings'];
            return [
                'status' => 'success',
                'email' => $settings['email'] ?? '',
                'first_name' => $settings['first_name'] ?? '',
                'last_name' => $settings['last_name'] ?? '',
                'country' => $settings['country'] ?? '',
                'currency' => $settings['currency'] ?? 'USD',
                'fullname' => trim(($settings['first_name'] ?? '') . ' ' . ($settings['last_name'] ?? ''))
            ];
        }
        
        return [
            'status' => 'error',
            'message' => $response['error'] ?? 'Failed to fetch account settings'
        ];
    }
    
    /**
     * Withdraw funds using payment agent withdrawal
     */
    public function withdrawFunds($token, $amount, $paymentagent_loginid, $verification_code = null) 
    {
        $request_data = [
            'paymentagent_withdraw' => 1,
            'amount' => floatval($amount),
            'currency' => 'USD',
            'paymentagent_loginid' => $paymentagent_loginid,
            'req_id' => $this->generateReqId()
        ];
        
        // Add verification code if provided
        if ($verification_code) {
            $request_data['verification_code'] = $verification_code;
        }
        
        $response = $this->makeApiCall($token, $request_data);
        
        if ($response['success'] && isset($response['data']['paymentagent_withdraw'])) {
            $withdraw_data = $response['data'];
            return [
                'status' => 'success',
                'transaction_id' => $withdraw_data['transaction_id'] ?? uniqid('deriv_'),
                'paymentagent_name' => $withdraw_data['paymentagent_name'] ?? 'Stepakash',
                'amount' => $amount,
                'currency' => 'USD',
                'withdraw_status' => $withdraw_data['paymentagent_withdraw'] ?? 1
            ];
        }
        
        return [
            'status' => 'error',
            'message' => $response['error'] ?? 'Withdrawal failed',
            'error_code' => $response['error_code'] ?? 'WITHDRAWAL_ERROR'
        ];
    }
    
    /**
     * Get transaction history
     */
    public function getTransactionHistory($token, $limit = 50) 
    {
        $request_data = [
            'statement' => 1,
            'description' => 1,
            'limit' => $limit,
            'req_id' => $this->generateReqId()
        ];
        
        $response = $this->makeApiCall($token, $request_data);
        
        if ($response['success'] && isset($response['data']['statement'])) {
            return [
                'status' => 'success',
                'transactions' => $response['data']['statement']['transactions'] ?? [],
                'count' => $response['data']['statement']['count'] ?? 0
            ];
        }
        
        return [
            'status' => 'error',
            'message' => $response['error'] ?? 'Failed to fetch transaction history'
        ];
    }
    
    /**
     * Authorize with token
     */
    public function authorize($token) 
    {
        $request_data = [
            'authorize' => $token,
            'req_id' => $this->generateReqId()
        ];
        
        $response = $this->makeApiCall($token, $request_data);
        
        if ($response['success'] && isset($response['data']['authorize'])) {
            $auth_data = $response['data']['authorize'];
            return [
                'status' => 'success',
                'loginid' => $auth_data['loginid'] ?? '',
                'email' => $auth_data['email'] ?? '',
                'currency' => $auth_data['currency'] ?? 'USD',
                'country' => $auth_data['country'] ?? '',
                'fullname' => $auth_data['fullname'] ?? ''
            ];
        }
        
        return [
            'status' => 'error',
            'message' => $response['error'] ?? 'Authorization failed'
        ];
    }
    
    /**
     * Send email verification for withdrawal
     */
    public function sendVerificationEmail($token, $type = 'paymentagent_withdraw') 
    {
        $request_data = [
            'verify_email' => $type,
            'req_id' => $this->generateReqId()
        ];
        
        $response = $this->makeApiCall($token, $request_data);
        
        if ($response['success'] && isset($response['data']['verify_email'])) {
            return [
                'status' => 'success',
                'message' => 'Verification email sent successfully'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => $response['error'] ?? 'Failed to send verification email'
        ];
    }
    
    /**
     * Get available payment agents
     */
    public function getPaymentAgents($currency = 'USD') 
    {
        $request_data = [
            'paymentagent_list' => $currency,
            'req_id' => $this->generateReqId()
        ];
        
        $response = $this->makeApiCall(null, $request_data);
        
        if ($response['success'] && isset($response['data']['paymentagent_list'])) {
            return [
                'status' => 'success',
                'agents' => $response['data']['paymentagent_list']['list'] ?? []
            ];
        }
        
        return [
            'status' => 'error',
            'message' => $response['error'] ?? 'Failed to fetch payment agents'
        ];
    }
    
    /**
     * Make API call to Deriv
     */
    private function makeApiCall($token, $data, $timeout = 30) 
    {
        try {
            $headers = ['Content-Type: application/json'];
            
            if ($token) {
                $headers[] = 'Authorization: Bearer ' . $token;
            }
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $this->api_url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 3
            ]);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            curl_close($ch);
            
            if ($curl_error) {
                return [
                    'success' => false,
                    'error' => 'CURL Error: ' . $curl_error,
                    'error_code' => 'CURL_ERROR'
                ];
            }
            
            if ($http_code !== 200) {
                return [
                    'success' => false,
                    'error' => 'HTTP Error: ' . $http_code,
                    'error_code' => 'HTTP_ERROR'
                ];
            }
            
            $decoded_response = json_decode($response, true);
            
            if (!$decoded_response) {
                return [
                    'success' => false,
                    'error' => 'Invalid JSON response',
                    'error_code' => 'JSON_ERROR'
                ];
            }
            
            // Check for API errors
            if (isset($decoded_response['error'])) {
                return [
                    'success' => false,
                    'error' => $decoded_response['error']['message'] ?? 'API Error',
                    'error_code' => $decoded_response['error']['code'] ?? 'API_ERROR',
                    'error_details' => $decoded_response['error']
                ];
            }
            
            return [
                'success' => true,
                'data' => $decoded_response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage(),
                'error_code' => 'EXCEPTION_ERROR'
            ];
        }
    }
    
    /**
     * Generate unique request ID
     */
    private function generateReqId() 
    {
        return rand(1000, 99999) . '_' . time();
    }
    
    /**
     * Validate Deriv account number format
     */
    public function validateAccountNumber($account_number) 
    {
        // Deriv real accounts start with CR followed by numbers
        return preg_match('/^CR\d+$/', $account_number);
    }
    
    /**
     * Validate withdrawal amount
     */
    public function validateWithdrawalAmount($amount) 
    {
        $amount = floatval($amount);
        return $amount >= 1 && $amount <= 10000; // Min $1, Max $10,000
    }
    
    /**
     * Log API operations for debugging
     */
    public function logOperation($operation, $data, $response) 
    {
        $log_data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'operation' => $operation,
            'request_data' => $data,
            'response' => $response
        ];
        
        // Use CodeIgniter's log_message function if available
        if (function_exists('log_message')) {
            log_message('info', 'Deriv API Operation: ' . json_encode($log_data));
        }
        
        // Also save to file for detailed logging
        $log_file = APPPATH . 'logs/deriv_operations_' . date('Y-m-d') . '.log';
        file_put_contents($log_file, json_encode($log_data) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Get server status and rate limits
     */
    public function getServerStatus() 
    {
        $request_data = [
            'ping' => 1,
            'req_id' => $this->generateReqId()
        ];
        
        $response = $this->makeApiCall(null, $request_data);
        
        if ($response['success']) {
            return [
                'status' => 'success',
                'server_time' => $response['data']['time'] ?? time(),
                'ping' => $response['data']['ping'] ?? 'pong'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => $response['error'] ?? 'Server status check failed'
        ];
    }
    
    /**
     * Format currency amount
     */
    public function formatCurrency($amount, $currency = 'USD', $decimals = 2) 
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'KES' => 'KES '
        ];
        
    /**
     * Format currency amount
     */
    public function formatCurrency($amount, $currency = 'USD', $decimals = 2) 
    {
        $symbols = [
            'USD' => ',
            'EUR' => '€',
            'GBP' => '£',
            'KES' => 'KES '
        ];
        
        $symbol = $symbols[$currency] ?? $currency . ' ';
        return $symbol . number_format($amount, $decimals);
    }
    
    /**
     * Convert between currencies (basic implementation)
     */
    public function convertCurrency($amount, $from_currency, $to_currency, $rate = null) 
    {
        if ($from_currency === $to_currency) {
            return $amount;
        }
        
        // If rate is not provided, use a default conversion (you should integrate with a real exchange rate API)
        if (!$rate) {
            $default_rates = [
                'USD_KES' => 150.0, // 1 USD = 150 KES (approximate)
                'KES_USD' => 0.0067, // 1 KES = 0.0067 USD (approximate)
            ];
            
            $rate_key = $from_currency . '_' . $to_currency;
            $rate = $default_rates[$rate_key] ?? 1;
        }
        
        return $amount * $rate;
    }
    
    /**
     * Check if withdrawal is allowed for account
     */
    public function canWithdraw($token, $loginid) 
    {
        // Get account status first
        $auth_response = $this->authorize($token);
        
        if ($auth_response['status'] !== 'success') {
            return [
                'can_withdraw' => false,
                'reason' => 'Authorization failed'
            ];
        }
        
        // Additional checks can be added here
        // For example: account verification status, minimum balance, etc.
        
        return [
            'can_withdraw' => true,
            'reason' => 'Account eligible for withdrawal'
        ];
    }
    
    /**
     * Get withdrawal limits for account
     */
    public function getWithdrawalLimits($token) 
    {
        $request_data = [
            'get_limits' => 1,
            'req_id' => $this->generateReqId()
        ];
        
        $response = $this->makeApiCall($token, $request_data);
        
        if ($response['success'] && isset($response['data']['get_limits'])) {
            $limits = $response['data']['get_limits'];
            return [
                'status' => 'success',
                'daily_withdrawal_limit' => $limits['daily_withdrawal_remaining'] ?? 0,
                'monthly_withdrawal_limit' => $limits['monthly_withdrawal_remaining'] ?? 0,
                'withdrawal_since_inception_monetary' => $limits['withdrawal_since_inception_monetary'] ?? 0
            ];
        }
        
        return [
            'status' => 'error',
            'message' => $response['error'] ?? 'Failed to fetch withdrawal limits'
        ];
    }
    
    /**
     * Process withdrawal with comprehensive error handling
     */
    public function processWithdrawal($token, $amount, $paymentagent_loginid, $verification_code = null) 
    {
        try {
            // Step 1: Validate inputs
            if (!$this->validateAccountNumber($paymentagent_loginid)) {
                return [
                    'status' => 'error',
                    'message' => 'Invalid payment agent login ID format'
                ];
            }
            
            if (!$this->validateWithdrawalAmount($amount)) {
                return [
                    'status' => 'error',
                    'message' => 'Invalid withdrawal amount. Must be between $1 and $10,000'
                ];
            }
            
            // Step 2: Check if withdrawal is allowed
            $can_withdraw = $this->canWithdraw($token, $paymentagent_loginid);
            if (!$can_withdraw['can_withdraw']) {
                return [
                    'status' => 'error',
                    'message' => $can_withdraw['reason']
                ];
            }
            
            // Step 3: Check balance
            $balance_info = $this->getBalance($token, $paymentagent_loginid);
            if ($balance_info['status'] !== 'success') {
                return [
                    'status' => 'error',
                    'message' => 'Unable to verify account balance'
                ];
            }
            
            if ($balance_info['balance'] < $amount) {
                return [
                    'status' => 'error',
                    'message' => 'Insufficient balance. Available:  . number_format($balance_info['balance'], 2)
                ];
            }
            
            // Step 4: Process withdrawal
            $withdrawal_result = $this->withdrawFunds($token, $amount, $paymentagent_loginid, $verification_code);
            
            // Step 5: Log the operation
            $this->logOperation('withdrawal', [
                'amount' => $amount,
                'paymentagent_loginid' => $paymentagent_loginid,
                'has_verification_code' => !empty($verification_code)
            ], $withdrawal_result);
            
            return $withdrawal_result;
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Processing error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get formatted account summary
     */
    public function getAccountSummary($token, $loginid) 
    {
        $summary = [
            'status' => 'success',
            'data' => []
        ];
        
        // Get balance
        $balance_info = $this->getBalance($token, $loginid);
        if ($balance_info['status'] === 'success') {
            $summary['data']['balance'] = $balance_info['balance'];
            $summary['data']['currency'] = $balance_info['currency'];
            $summary['data']['formatted_balance'] = $this->formatCurrency($balance_info['balance'], $balance_info['currency']);
        }
        
        // Get account settings
        $settings_info = $this->getAccountSettings($token);
        if ($settings_info['status'] === 'success') {
            $summary['data']['email'] = $settings_info['email'];
            $summary['data']['fullname'] = $settings_info['fullname'];
            $summary['data']['country'] = $settings_info['country'];
        }
        
        // Get withdrawal limits
        $limits_info = $this->getWithdrawalLimits($token);
        if ($limits_info['status'] === 'success') {
            $summary['data']['daily_withdrawal_remaining'] = $limits_info['daily_withdrawal_limit'];
            $summary['data']['monthly_withdrawal_remaining'] = $limits_info['monthly_withdrawal_limit'];
        }
        
        $summary['data']['loginid'] = $loginid;
        $summary['data']['last_updated'] = date('Y-m-d H:i:s');
        
        return $summary;
    }
    
    /**
     * Validate and sanitize input data
     */
    public function sanitizeInput($data) 
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeInput'], $data);
        }
        
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Generate withdrawal receipt data
     */
    public function generateWithdrawalReceipt($withdrawal_data, $user_info = []) 
    {
        return [
            'receipt_id' => 'WD-' . $withdrawal_data['transaction_id'],
            'timestamp' => date('Y-m-d H:i:s'),
            'amount' => $withdrawal_data['amount'],
            'currency' => $withdrawal_data['currency'],
            'formatted_amount' => $this->formatCurrency($withdrawal_data['amount'], $withdrawal_data['currency']),
            'paymentagent_name' => $withdrawal_data['paymentagent_name'] ?? 'Stepakash',
            'transaction_id' => $withdrawal_data['transaction_id'],
            'status' => $withdrawal_data['status'],
            'user_info' => $user_info
        ];
    }
    
    /**
     * Health check for Deriv API connection
     */
    public function healthCheck() 
    {
        $start_time = microtime(true);
        $server_status = $this->getServerStatus();
        $end_time = microtime(true);
        
        $response_time = round(($end_time - $start_time) * 1000, 2); // in milliseconds
        
        return [
            'status' => $server_status['status'],
            'response_time_ms' => $response_time,
            'server_reachable' => $server_status['status'] === 'success',
            'timestamp' => date('Y-m-d H:i:s'),
            'api_url' => $this->api_url,
            'websocket_url' => $this->websocket_url
        ];
    }
}

/**
 * CodeIgniter Model for Deriv Operations
 * This should be saved as application/models/Deriv_model.php
 */
class Deriv_model extends CI_Model 
{
    private $deriv_helper;
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->library('session');
        
        // Initialize Deriv helper
        require_once APPPATH . 'libraries/DerivWebSocketHelper.php';
        $this->deriv_helper = new DerivWebSocketHelper();
    }
    
    /**
     * Get user's Deriv balance
     */
    public function getUserDerivBalance($user_id) 
    {
        // Get user's Deriv credentials from database
        $query = $this->db->select('deriv_token, deriv_login_id')
                          ->where('id', $user_id)
                          ->get('users');
        
        if ($query->num_rows() > 0) {
            $user = $query->row();
            return $this->deriv_helper->getBalance($user->deriv_token, $user->deriv_login_id);
        }
        
        return [
            'status' => 'error',
            'message' => 'User Deriv credentials not found'
        ];
    }
    
    /**
     * Process withdrawal and update database
     */
    public function processWithdrawalAndUpdate($user_id, $amount, $cr_number, $verification_code = null) 
    {
        // Get user's Deriv credentials
        $query = $this->db->select('deriv_token, deriv_login_id, phone, wallet_id')
                          ->where('id', $user_id)
                          ->get('users');
        
        if ($query->num_rows() === 0) {
            return [
                'status' => 'error',
                'message' => 'User not found'
            ];
        }
        
        $user = $query->row();
        
        // Process withdrawal through Deriv API
        $withdrawal_result = $this->deriv_helper->processWithdrawal(
            $user->deriv_token, 
            $amount, 
            $cr_number, 
            $verification_code
        );
        
        if ($withdrawal_result['status'] === 'success') {
            // Save withdrawal record to database
            $withdrawal_data = [
                'user_id' => $user_id,
                'wallet_id' => $user->wallet_id,
                'transaction_id' => $withdrawal_result['transaction_id'],
                'amount' => $amount,
                'currency' => 'USD',
                'cr_number' => $cr_number,
                'status' => 'completed',
                'deriv_response' => json_encode($withdrawal_result),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('deriv_withdrawals', $withdrawal_data);
            
            // Update user's wallet balance (convert USD to KES)
            $kes_amount = $this->deriv_helper->convertCurrency($amount, 'USD', 'KES');
            $this->updateWalletBalance($user->wallet_id, $kes_amount);
        }
        
        return $withdrawal_result;
    }
    
    /**
     * Update user's wallet balance
     */
    private function updateWalletBalance($wallet_id, $amount) 
    {
        $this->db->set('balance', 'balance + ' . $amount, FALSE)
                 ->where('wallet_id', $wallet_id)
                 ->update('user_wallets');
        
        // Log the balance update
        $transaction_data = [
            'wallet_id' => $wallet_id,
            'transaction_type' => 'deriv_withdrawal',
            'amount' => $amount,
            'currency' => 'KES',
            'status' => 'completed',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('wallet_transactions', $transaction_data);
    }
    
    /**
     * Get withdrawal history for user
     */
    public function getWithdrawalHistory($user_id, $limit = 50) 
    {
        return $this->db->select('*')
                       ->where('user_id', $user_id)
                       ->order_by('created_at', 'DESC')
                       ->limit($limit)
                       ->get('deriv_withdrawals')
                       ->result_array();
    }
}