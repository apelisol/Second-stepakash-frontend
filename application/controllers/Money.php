<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Money extends CI_Controller {
    
    public function __construct()
    {
        
        parent::__construct();
        $this->load->model('Operations');

    }
    
    
      public function stkresults()
    {
        $stkCallbackResponse = file_get_contents('php://input');
          // file_put_contents("leo.txt", $stkCallbackResponse);

        $decode = json_decode($stkCallbackResponse,true);
        $MerchantRequestID = $decode['Body']['stkCallback']['MerchantRequestID'];
        $CheckoutRequestID = $decode['Body']['stkCallback']['CheckoutRequestID'];
        $Amount = $decode['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
        $MpesaReceiptNumber = $decode['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
        $TransactionDate = $decode['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value'];
        $phone = $decode['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'];
        $ResultCode = $decode['Body']['stkCallback']['ResultCode'];

        $CustomerMessage = $decode['Body']['stkCallback']['ResultDesc'];



    }



	



}
