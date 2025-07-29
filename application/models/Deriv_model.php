<?php
defined('BASEPATH') or exit('No direct script access allowed');


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
