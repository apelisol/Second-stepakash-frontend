

<?php



$wallet_id = $this->session->userdata('wallet_id');

$session_id = $this->session->userdata('session_id');

$phone_session = $this->session->userdata('phone');

$checkout_token = $this->session->userdata('checkout_token');


if(!$wallet_id || !$session_id  || !$phone_session)

{

    redirect('logout');

}
if(!empty($checkout_token))
{
    redirect('payment_form');
}

?>

<!-- main page content -->
<style>
   .currency {
        font-size: 16px;
    }

    .flag-icon {
        width: 24px;
        height: 16px;
        vertical-align: middle;
    }
</style>
<div class="main-container container">

           <?php

        $flash = $this->session->flashdata('msg');

        if($flash) {

            echo '<div class="alert alert-warning alert-dismissible fade show align-self-center text-center text-primary size-12" role="alert"> '. $flash.'

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                        </div>

                        ';

        } else {

            $this->session->unset_userdata('msg');

        }

        ?>

    <!-- balance -->

    <div class="row my-4 text-center">

        <div class="col-12">
        <span id="currency" class="fw-dark mb-2 ms-2 currency currency-left"> </span>
            <h2 class="fw-dark mb-2">KES <?php echo number_format($total_balance, 2, '.', ',');?> <img src="https://flagcdn.com/ke.svg" class="flag-icon"></h2>

            <p class="text-secondary">Total Balance</p>

        </div>

    </div>



   



    <!-- categories list -->

    <div class="row mb-4">

        <div class="col-12">

            <div class="card bg-theme text-white">

                <div class="card-body pb-0">

                    <div class="row justify-content-between gx-0 mx-0 pb-3">





                        <div class="col-auto text-center">

                            <a href="#" id="depositMpesaBot" class="avatar avatar-60 p-1 shadow-sm rounded-15 bg-opac mb-2">

                                <div class="icons bg-success text-white rounded-12 bg-opac">

                                    <i class="fas fa-bank size-22"></i>

                                </div>

                            </a>

                            <p class="size-10">Deposit Mpesa</p>

                        </div>



                        <div class="col-auto text-center">

                            <a href="#" id="withdrawMpesaBot" class="avatar avatar-60 p-1 shadow-sm rounded-15 bg-opac mb-2">

                                <div class="icons bg-success text-white rounded-12 bg-opac">

                                    <i class="fas fa-money-bill-wave-alt size-22"></i>

                                </div>

                            </a>

                            <p class="size-10">Withdraw Mpesa</p>

                        </div>

                        

                        <div class="col-auto text-center">

                            <a href="#" id="crypto_funding" class="avatar avatar-60 p-1 shadow-sm rounded-15 bg-opac mb-2">

                                <div class="icons bg-success text-white rounded-12 bg-opac">

                                    <i class="fas fa-exchange-alt"></i>

                                </div>

                            </a>

                            <p class="size-10">Transfer</p>

                        </div>


                        <div class="col-auto text-center">

                            <a href="#" id="crypto_withdraw" class="avatar avatar-60 p-1 shadow-sm rounded-15 bg-opac mb-2">

                                <div class="icons bg-success text-white rounded-12 bg-opac">

                                <i class="fas fa-arrow-circle-down size-22"></i>

                                </div>

                            </a>

                            <p class="size-10">Receive</p>

                        </div>



                        <!-- <div class="col-auto text-center">

                            <a href="#" id="withdrawDerivBut" class="avatar avatar-60 p-1 shadow-sm rounded-15 bg-opac mb-2">

                                <div class="icons bg-success text-white rounded-12 bg-opac">

                                    <i class="fas fa-arrow-circle-down size-22"></i>

                                </div>

                            </a>

                            <p class="size-10">Withdraw Deriv</p>

                        </div> -->

                        

                       

                    </div>



                    <button class="btn btn-link mt-0 py-1 w-100 bar-more collapsed" type="button"

                        data-bs-toggle="collapse" data-bs-target="#collpasemorelink" aria-expanded="false"

                        aria-controls="collpasemorelink">

                        <span class=""></span>

                    </button>

                </div>

            </div>

        </div>

    </div>









    <!-- Modal for Withdraw Deriv -->

    <div class="modal" id="withdrawDerivModal">

        <div class="modal-dialog" style="width: 100%; height:80vh;">

            <div class="modal-content">

                <h3 class="text-center mb-3">Withdraw From Deriv</h3>

              <form class="text-center" method="POST" action="<?php echo base_url() ?>Main/WithdrawFromDeriv" onsubmit="return disableSubmitButtonWithdraw()">

                    <div class="form-group mb-3">

                    <span class="float-end size-12">

                    Go to your deriv.com account

                     Click Cashier > Payment agent > Withdraw

                    (A verification link will be sent to your email).

                    Click on that link and confirm the amount, exact amount equivalent to your Stepak withdraw. Then Select STEPAKASH as payment agent,

                    Leave blank payment agent ID

                    ENTER AMOUNT IN USD. You will receive the money in your wallet INSTANTLY

                    </span>

                    </div>

                    <div class="form-group mb-3">

                        <label for="crNumber">CR Number</label>

                        <input type="text" id="crNumberwith" style="text-transform: uppercase;"  value="<?php echo  $this->session->userdata('account_number') ?>"  name="crNumber" class="form-control rounded-pill" required>

                   

                        <div class="error-message-cr1" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for "amount">Amount(in USD)</label>

                        <input type="number" id="amount1" step="0.01" name="amount" class="form-control rounded-pill" required>


                        <div class="error-message-amount1" style="color: red;"></div>

                    </div>

                    <div class="col-12 d-grid">

                        <button type="submit" id="crNumberwithdraw"  class="btn btn-default btn-sm shadow-sm rounded-pill" disabled>Withdraw</button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    <!-- Modal for Deposit from Mpesa -->

    <div class="modal fade" id="depositMpesaModal" tabindex="-1" aria-labelledby="depositMpesaModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" style="width: 100%; height: 60vh;">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="depositMpesaModalLabel">Deposit from Mpesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="text-center" method="POST" action="<?php echo base_url() ?>Main/DepositFromMpesa" onsubmit="return disableDerivDeposit()">

                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" id="phone" name="phone" class="form-control rounded-pill" value="<?php echo $this->session->userdata('phone') ?>" readonly placeholder="+254xx" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" id="amount" step="1" name="amount" class="form-control rounded-pill" placeholder="Enter amount" required>
                        </div>

                        <div class="col-12 d-grid">
                            <button type="submit" id="deposit_button" class="btn btn-primary btn-sm shadow-sm rounded-pill">Deposit</button>
                        </div>

                    </form>
                </div>

            </div>

        </div>

    </div>





    <!-- Modal for Withdraw to Mpesa -->

    <div class="modal" id="withdrawMpesaModal">

        <div class="modal-dialog" style="width: 100%; height: 60vh;">

            <div class="modal-content">

                <h3 class="text-center mb-3">Withdraw to Mpesa</h3>

                <form class="text-center" method="POST" action="<?php echo base_url() ?>Main/WithdrawToMpesa" onsubmit="return disableWithdrawButton()">

                    <div class="form-group mb-3">

                        <label for="phone">Phone</label>

                        <input type="text" id="phone" name="phone" class="form-control rounded-pill" placeholder="+254xx"  value="<?php echo  $this->session->userdata('phone') ?>" readonly required>

                    </div>

                    <div class="form-group mb-3">

                        <label for="amount">Amount (in ksh)</label>

                        <input type="number" id="amount" min="10" step="1" name="amount" class="form-control rounded-pill" required>

                    </div>

                    <div class="col-12 d-grid">

                        <button type="submit" id="mpesa_withdraw" class="btn btn-default btn-sm shadow-sm rounded-pill"  >Withdraw</button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    

    

    

      

    

    <div class="modal" id="cryptoFundingModal">

        <div class="modal-dialog" style="width: 100%; height: 80vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the modal -->

                <h3 class="text-center mb-3">Transfer/Funding</h3>

    

                <!-- First row -->

                <div class="row justify-content-between gx-2 mx-2 pb-3">

                    

                    <!-- Deriv (open bottom modal) -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="deriv_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>deriv.jpg" alt="Deriv" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10 font-bold">Deriv Deposit</p>

                    </div>

                    

                    

                    <!-- Stepakash P2P (open bottom modal) -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="stepak_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url() ?>assets/img/sk1.png" alt="STEPAK" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10">Stepakash P2P</p>

                    </div>


                    <!-- Exness Money -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="exness_withdraw_link" >

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>exness.png" alt="Exness" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10">Deposit Exness</p>

                    </div>


                    

                </div>

                    

                    
                <!-- second row -->
                <div class="row justify-content-between gx-2 mx-2 pb-3">

                    <!-- Bitcoin -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bitcoin_link">

                            <div class="icons bg-warning text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-bitcoin" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                        <p class="size-10">Bitcoin (BTC)</p>

                    </div>

    

                    <!-- Ethereum -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2"  id="ethereum_link" >

                            <div class="icons bg-primary text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-ethereum" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                        <p class="size-10">Ethereum (ETH)</p>

                    </div>

                    

                    

                     <!-- USDT (TRC-20) (open bottom modal) -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-info text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-dollar-sign" style="border-radius: 50%;"></i>

                              

                            </div>

                        </a>

                        <p class="size-10">USDT (ERC-20)</p>

                    </div>

    

                </div>

    
                <!-- Third row -->

                <div class="row justify-content-between gx-2 mx-2 pb-3">

                    

                    

                    <!-- Tether USDT -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="trc_link">

                            <div class="icons bg-info text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-dollar-sign" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                        <p class="size-10">USDT (TRC20)</p>

                    </div>

                    

                    

                    <!-- Skrill -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="skrill_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>skrill.png" alt="Skrill" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10">Skrill</p>

                    </div>

    

                 

    

                    <!-- Neteller Money -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="neteller_link" >

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>neteller.png" alt="Airtel Money" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10">Neteller</p>

                    </div>

                    

          

                </div>


                 <!-- fourth row -->

                <div class="row justify-content-between gx-2 mx-2 pb-3">

                    
                    <!-- Tether USDT (TRC20) -->

                    <div class="col-auto text-center">

                    <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="tether_usdt_link">

                        <div class="icons bg-dark text-white rounded-circle" style="padding: 10px;">

                        <img src="<?php echo base_url().'assets/img/' ?>binance.png" alt="BNB" style="border-radius: 50%; width: 50px; height: 50px;">

                        </div>

                    </a>

                    <p class="size-10">Binance Pay</p>

                    </div>

                    

                  
                    
                    <!-- Skrill -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bank_deposit_link">

                            <div class="icons bg-dark text-white rounded-circle" style="padding: 10px;">

                            <i class="fas fa-bank" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                        <p class="size-10">Send To Bank/Till/Paybill</p>

                    </div>


                      <!-- Send Gift -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="send_gift_link">

                            <div class="icons bg-primary text-white rounded-circle" style="padding: 10px;">

                                <i class="fas fa-gift" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                        <p class="size-10">Send a Gift</p>

                    </div>



                </div>

            </div>

        </div>

    </div>

    

    <!-- Deriv Modal -->

    <div class="modal" id="depositModal">

        <div class="modal-dialog" style="width: 100%; height: 60vh;">

            <!-- Modal content -->

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Deriv Deposit</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                   <img src="<?php echo base_url().'assets/img/' ?>deriv.jpg" alt="deriv" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                       

                    </div>

                <form class="text-center" method="POST" action="<?php echo base_url() ?>Main/DepositToDeriv" onsubmit="return disableDepositButton()">

                    <div class="form-group mb-3">

                        <label for="crNumber">CR Number</label>

                        <input type="text" id="crNumberdepo" placeholder="Deriv CR number, eg CR1234567" style="text-transform: uppercase;" value="<?php echo  $this->session->userdata('account_number') ?>"  name="crNumber" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="error-message-cr" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for="amount">Amount (in Ksh)</label>

                        <input type="number" id="amountdepo"  name="amount" placeholder="0.00" autocomplete="off" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="error-message-amount" style="color: red;"></div>

                    </div>

                    <div class="col-12 d-grid">

                        <button type="submit" id="depo" class="btn btn-default btn-sm shadow-sm rounded-pill" id="submitBtn" disabled>Deposit</button>

                    </div>

                </form>



            </div>





        </div>

    </div>



     <!-- Stepakash p2p Modal -->

     <div class="modal" id="stepakashModal">

        <div class="modal-dialog " style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <!-- Modal content -->

            <div class="modal-content">

                <div class="col-auto text-center">

                    <b> <p class="size-14">Stepakash wallet P2P</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                   <img src="<?php echo base_url().'assets/img/' ?>sk1.png" alt="Stepak" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                       

                    </div>

                <form class="text-center" method="POST" action="<?php echo base_url() ?>p2p_transfer" onsubmit="return disableTransferButton()">

                    <div class="form-group mb-3">

                        <label for="crNumber">Wallet ID</label>

                        <input type="text" id="wallet_id" name="wallet_id" placeholder="Stepakash Wallet ID" style="text-transform: uppercase" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="error-message-cr" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for="amount">Amount (in Ksh)</label>

                        <input type="number" id="send_amount" step="0.01" placeholder="0.00" name="amount" min="0" autocomplete="off" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="error-message-amount" style="color: red;"></div>

                    </div>

                    <div class="col-12 d-grid">

                        <button type="submit" id="send_stepak" class="btn btn-default btn-sm shadow-sm rounded-pill" id="submitBtn">Send</button>

                    </div>

                </form>



            </div>





        </div>

    </div>


    <!-- Bitcoin Modal -->

    <div class="modal" id="bitcoinModal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Deposit To Bitcoin</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-warning text-white rounded-circle" style="padding: 10px;">

                                  <i class="fab fa-bitcoin" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                       

                    </div>

                    <form class="text-center" method="POST" action="<?php echo base_url() ?>deposit_bitcoin" onsubmit="return disableBitcoinButton()">
                        <div class="form-group mb-3">
                            <label for="crNumber">Bitcoin Address</label>
                            <input type="text" id="bitcoin_address" name="bitcoin_address" placeholder="Your Bitcoin Address" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="bitcoin-error-message-cr" style="color: red;"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="amount">Amount (in Ksh)</label>
                            <input type="number" id="bitcoin_amount" name="bitcoin_amount" placeholder="0.00" autocomplete="off" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="bitcoin-error-message-amount" style="color: red;"></div>
                        </div>
                        <div class="col-12 d-grid">
                            <button type="submit" id="deposit_bitcoin" class="btn btn-default btn-sm shadow-sm rounded-pill" disabled>Deposit</button>
                        </div>
                    </form>

            </div>

        </div>

    </div>

    
    
    <!-- Ethereum Modal -->

    <div class="modal" id="ethereumModal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Deposit To Ethereum</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-primary text-white rounded-circle" style="padding: 10px;">

                                 <i class="fab fa-ethereum" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                       

                    </div>

                    <form class="text-center" method="POST" action="<?php echo base_url() ?>deposit_ethereum" onsubmit="return disableEthereumButton()">

                        <div class="form-group mb-3">
                            <label for="ethAddress">Ethereum Address</label>
                            <input type="text" id="ethAddress" name="ethereum_address" placeholder="Your Ethereum Address" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="ethereum-error-message-cr" style="color: red;"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="ethAmount">Amount (in Ksh)</label>
                            <input type="number" id="ethAmount" name="ethereum_amount" placeholder="0.00" autocomplete="off" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="ethereum-error-message-amount" style="color: red;"></div>
                        </div>

                        <div class="col-12 d-grid">
                            <button type="submit" id="ethereumDepo" class="btn btn-default btn-sm shadow-sm rounded-pill" id="ethereumSubmitBtn" disabled>Deposit</button>
                        </div>

                    </form>


            </div>

        </div>

    </div>


   <!-- BNB Modal -->

    <div class="modal" id="tetherUSDTModal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Deposit To Binance Pay</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-dark text-white rounded-circle" style="padding: 10px;">

                                 

                                 <img src="<?php echo base_url().'assets/img/' ?>binance.png" alt="BNB" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                       

                    </div>

                <form class="text-center" method="POST" action="<?php echo base_url() ?>deposit_binance" onsubmit="return disableBinanceButton()">

         

                    <div class="form-group mb-3">

                        <label for="crNumber"> USDT Address</label>

                        <input type="text"  id="binance_usdt_address" name="usdt_address" placeholder="Your binance Address" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="binance-error-message-cr" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for="amount">Amount (in Ksh)</label>

                        <input type="number" id="binance_amount" placeholder="0.00" name="usdt_amount" autocomplete="off" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="binance-error-message-amount" style="color: red;"></div>

                    </div>

                    <div class="col-12 d-grid">

                        <button type="submit" class="btn btn-default btn-sm shadow-sm rounded-pill" id="deposit_binance" disabled>Deposit</button>

                    </div>

                </form>

            </div>

        </div>

    </div>



     <!-- Send Gift -->
    <div class="modal" id="send_gift_modal">

        <div class="modal-dialog" style="width: 100%; height:85vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Send Gift</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-primary text-white rounded-circle" style="padding: 10px;">

                            <i class="fas fa-gift" style="border-radius: 50%;"></i>
                            </div>

                        </a>

                       

                    </div>

                <form class="text-center" method="POST" action="<?php echo base_url() ?>send_gift" onsubmit="return disable_gifting_button()">

         

                    <div class="form-group mb-3">

                        <label for="crNumber"> Phone Number</label>

                        <input type="text"  id="mpesa_phone" name="phone"  placeholder="Mpesa Phone Number 07xxx" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="gift-error-message-cr" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for="amount">Amount (in Ksh)</label>

                        <input type="number" id="amount" placeholder="0.00"  name="amount" autocomplete="off" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="gift-error-message-amount" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for="comment">Message</label>

                        <textarea class="form-control" rows="3" name="comment" required placeholder="message.." ></textarea>

                        <!-- Add an error message div -->

                        <div class="gift-error-message-comment" style="color: red;"></div>

                    </div>

                    <div class="col-12 d-grid">

                        <button type="submit" class="btn btn-default btn-sm shadow-sm rounded-pill" id="gift_button">Send Gift</button>

                    </div>

                </form>

            </div>

        </div>

    </div>




     <!-- TETHER USDT ERC 20 Modal -->

    <div class="modal" id="bnbModal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Deposit To USDT (ERC 20)</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-info text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-dollar-sign" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                       

                    </div>

                    <form class="text-center" method="POST" action="<?php echo base_url() ?>deposit_erc"  onsubmit="return disableErcButton()">

                        <div class="form-group mb-3">
                            <label for="USDTAddress">USDT Address</label>
                            <input type="text" id="USDTAddress" name="erc_address" placeholder="your ERC 20 address" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="tether-error-message-cr" style="color: red;"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tetherAmount">Amount (in Ksh)</label>
                            <input type="number" id="tetherAmount" name="erc_amount" placeholder="0.00" autocomplete="off" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="tether-error-message-amount" style="color: red;"></div>
                        </div>

                        <div class="col-12 d-grid">
                            <button type="submit" id="tetherDepo" class="btn btn-default btn-sm shadow-sm rounded-pill" id="tetherSubmitBtn" disabled>Deposit</button>
                        </div>

                    </form>


            </div>

        </div>

    </div>

    

    <!-- TETHER USDT TRC 20 Modal -->

    <div class="modal" id="TRCModal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Deposit To USDT (TRC 20)</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" >

                            <div class="icons bg-info text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-dollar-sign" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                       

                    </div>

                <form class="text-center" method="POST" action="<?php echo base_url() ?>deposit_trc" onsubmit="return disableTrcButton()">

                
                    <div class="form-group mb-3">

                        <label for="crNumber">USDT Address</label>

                        <input type="text" id="trc_address"  name="trc_address" placeholder="your TRC 20 address" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="trc-error-message-cr" style="color: red;"></div>

                    </div>


                    <div class="form-group mb-3">

                        <label for="amount">Amount (in Ksh)</label>

                        <input type="number" id="trc_amount" placeholder="0.00" name="trc_amount" autocomplete="off" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="trc-error-message-amount" style="color: red;"></div>

                    </div>

                    <div class="col-12 d-grid">

                        <button type="submit" id="trc_button" class="btn btn-default btn-sm shadow-sm rounded-pill" id="submitBtn" disabled>Deposit</button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    
    <!-- Skrill Modal -->

    <div class="modal" id="skrillModal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the Skrill modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Deposit To Skrill</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                   <img src="<?php echo base_url().'assets/img/' ?>skrill.png" alt="Deriv" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                       

                    </div>

                <form class="text-center" method="POST" action="<?php echo base_url() ?>deposit_skrill" onsubmit="return disableSkrillButton()" >

                    <div class="form-group mb-3">

                        <label for="crNumber">Skrill Email</label>

                        <input type="text" id="skrill_email"   name="skrill_email" placeholder="skrill email"  class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="skrill-error-message-cr" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for="amount">Amount (in Ksh)</label>

                        <input type="number" id="skrill_amount" placeholder="0.00" name="skrill_amount" autocomplete="off" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="skrill-error-message-amount" style="color: red;"></div>

                    </div>

                    <div class="col-12 d-grid">

                        <button type="submit" id="skrill_button" class="btn btn-default btn-sm shadow-sm rounded-pill" id="submitBtn" disabled>Deposit</button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <!-- Neteller Modal -->

    <div class="modal" id="netellerModal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <div class="col-auto text-center">

                    <b> <p class="size-14">Deposit To Neteller</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                   <img src="<?php echo base_url().'assets/img/' ?>neteller.png" alt="Deriv" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                       

                    </div>

                <form class="text-center" method="POST" action="<?php echo base_url() ?>deposit_neteller" onsubmit="return disableNetellerButton()" >

                    <div class="form-group mb-3">

                        <label for="crNumber">Neteller Email</label>

                        <input type="text" id="neteller_email"   name="neteller_email" placeholder="neteller email" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="neteller-error-message-cr" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for="amount">Amount (in Ksh)</label>

                        <input type="number" id="neteller_amount" placeholder="0.00" name="neteller_amount" autocomplete="off" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="neteller-error-message-amount" style="color: red;"></div>

                    </div>

                    <div class="col-12 d-grid">

                        <button type="submit" id="neteller_button" class="btn btn-default btn-sm shadow-sm rounded-pill" id="submitBtn" disabled>Deposit</button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    

   <!-- WITHDRAWAL MODALS -->

    <div class="modal" id="cryptoReceiveModal">

        <div class="modal-dialog" style="width: 100%; height: 80vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the modal -->

                <h3 class="text-center mb-3">Receive/Withdraw</h3>

    

                <!-- First row -->

                <div class="row justify-content-between gx-2 mx-2 pb-3">

                    

                    <!-- Deriv (open bottom modal) -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="deriv_withdraw_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>deriv.jpg" alt="Deriv" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10 font-bold">Deriv Withdraw</p>

                    </div>

                    

                    <!-- Stepakash P2P (open bottom modal) -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="stepak_withdraw_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url() ?>assets/img/sk1.png" alt="STEPAK" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10">Stepakash P2P</p>

                    </div>



                     <!-- Exness Money -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="exness_withdraw_link" >

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>exness.png" alt="Exness" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10">Withdraw Exness</p>

                    </div>


                    
                </div>

                    

                    

                <div class="row justify-content-between gx-2 mx-2 pb-3">

                    <!-- Bitcoin -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bitcoin_withdraw_link">

                            <div class="icons bg-warning text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-bitcoin" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                        <p class="size-10">Bitcoin (BTC)</p>

                    </div>

    

                    <!-- Ethereum -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2"  id="ethereum_withdraw_link" >

                            <div class="icons bg-primary text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-ethereum" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                        <p class="size-10">Ethereum (ETH)</p>

                    </div>

                    
                     <!-- USDT (TRC-20) (open bottom modal) -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_withdraw_link">

                            <div class="icons bg-info text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-dollar-sign" style="border-radius: 50%;"></i>

                              

                            </div>

                        </a>

                        <p class="size-10">USDT (ERC-20)</p>

                    </div>

                    

                </div>

    

                <!-- third row -->

                <div class="row justify-content-between gx-2 mx-2 pb-3">

                    

                    

                    <!-- Tether USDT -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="trc_withdraw_link">

                            <div class="icons bg-info text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-dollar-sign" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                        <p class="size-10">USDT (TRC20)</p>

                    </div>

                    
                    <!-- Skrill -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="skrill_withdraw_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>skrill.png" alt="Skrill" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10">Skrill</p>

                    </div>

    

                    <!-- Neteller Money -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="neteller_withdraw_link" >

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>neteller.png" alt="Airtel Money" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10">Neteller</p>

                    </div>

                    

          

                </div>

                <!-- fourth row -->

                <div class="row justify-content-between gx-2 mx-2 pb-3">

                    <!-- Binance -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="tether_usdt_withdraw_link">

                            <div class="icons bg-dark text-white rounded-circle" style="padding: 10px;">

                              <img src="<?php echo base_url().'assets/img/' ?>binance.png" alt="BNB" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10">Binance Pay</p>

                    </div>

                    <!-- Withdraw to agent -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="agent_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                              <img src="<?php echo base_url().'assets/img/' ?>sk1.png" alt="STEPAK" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                        <p class="size-10">Withdraw To Agent</p>

                    </div>


                    

                    <!-- Send Gift -->

                    <div class="col-auto text-center">

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="send_gift_link_2">

                            <div class="icons bg-primary text-white rounded-circle" style="padding: 10px;">

                                <i class="fas fa-gift" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                        <p class="size-10">Send a Gift</p>

                    </div>

             
                </div>

            </div>

        </div>

    </div>


      <!-- Deriv Modal -->

    <div class="modal" id="depositwithdraw_Modal">

        <div class="modal-dialog" style="width: 100%; height: 78vh;">

            <!-- Modal content -->

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Deriv Withdraw</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>deriv.jpg" alt="deriv" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                    

                    </div>

                    <form class="text-center" method="POST" action="<?php echo base_url() ?>Main/WithdrawFromDeriv" onsubmit="return disableSubmitButtonWithdraw()">

                        <div class="form-group mb-3">
                            <span class="float-end size-12">
                                Go to your deriv.com account
                                Click Cashier > Payment agent > Withdraw
                                (A verification link will be sent to your email).
                                Click on that link and confirm the amount, exact amount equivalent to your Stepak withdraw. Then Select STEPAKASH as payment agent,
                                Leave blank payment agent ID
                                ENTER AMOUNT IN USD. You will receive the money in your wallet INSTANTLY
                            </span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="crNumberWithdraw">CR Number</label>
                            <input type="text" id="crNumberWithdraw" style="text-transform: uppercase;" value="<?php echo $this->session->userdata('account_number') ?>" name="crNumber_withdraw" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="error-message-cr-withdraw" style="color: red;"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="amountWithdraw">Amount (in USD)</label>
                            <input type="number" id="amountWithdraw" step="0.01" name="deriv_amount" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="error-message-amount-withdraw" style="color: red;"></div>
                        </div>

                        <div class="col-12 d-grid">
                            <button type="submit" id="withdrawButton" class="btn btn-default btn-sm shadow-sm rounded-pill" disabled>Withdraw</button>
                        </div>

                    </form>


            </div>





        </div>

    </div>




    <!-- Stepakash p2p Modal -->

    <div class="modal" id="stepakashwithdraw_Modal">

        <div class="modal-dialog " style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <!-- Modal content -->

            <div class="modal-content">

                <div class="col-auto text-center">

                    <b> <p class="size-14">Stepakash wallet P2P</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" id="bnb_link">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>sk1.png" alt="Stepak" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                    

                    </div>

                <form class="text-center" method="POST" action="<?php echo base_url() ?>p2p_transfer" onsubmit="return disableP2PTransferButton()">

                    <div class="form-group mb-3">

                        <label for="crNumber">Wallet ID</label>

                        <input type="text" id="wallet_id_withdraw" name="wallet_id" placeholder="Stepakash Wallet ID" style="text-transform: uppercase" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="error-message-cr" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for="amount">Amount (in Ksh)</label>

                        <input type="number" id="send_amount_withdraw" step="0.01" placeholder="0.00" name="amount" min="0" autocomplete="off" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="error-message-amount" style="color: red;"></div>

                    </div>

                    <div class="col-12 d-grid">

                        <button type="submit" id="send_stepak_withdraw" class="btn btn-default btn-sm shadow-sm rounded-pill" id="submitBtn">Send</button>

                    </div>

                </form>



            </div>





        </div>

    </div>



    <!-- Bitcoin Modal -->

    <div class="modal" id="bitcoinwithdraw_Modal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Withdraw From Bitcoin</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2">

                            <div class="icons bg-warning text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-bitcoin" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                    

                    </div>

                    <form class="text-center" method="POST" action="<?php echo base_url() ?>withdraw_bitcoin" onsubmit="return disableBitcoinWithdrawButton()">
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <input type="hidden" id="bitcoinAddress" value="bc1q8fgrmv5rs7nt3970ydr04fjljtmhmdp5vngetx" name="bitcoin_address">
                                <span id="bitcoinAddressDisplay" class="">
                                    bc1q8fgrmv5rs7nt3970ydr04fjljtmhmdp5vngetx
                                </span>
                                <button class="btn btn-outline-secondary rounded-pill" onclick="copyBitcoinAddress()">Copy</button>
                            </div>
                            <!-- Add an error message div -->
                            <div class="bitcoin-error-message-cr" style="color: red;"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="bitcoinAmount">Amount (in USD)</label>
                            <input type="number" id="bitcoinAmount" name="bitcoin_amount" placeholder="0.00" autocomplete="off" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="bitcoin_withdraw-error-message-amount" style="color: red;"></div>
                        </div>
                        <div class="col-12 d-grid">
                            <button type="submit" id="withdrawBitcoin" class="btn btn-default btn-sm shadow-sm rounded-pill" >Withdraw</button>
                        </div>
                    </form>
            </div>

        </div>

    </div>





    <!-- Ethereum Modal -->

    <div class="modal" id="ethereumwithdraw_Modal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Withdraw From Ethereum</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" >

                            <div class="icons bg-primary text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-ethereum" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                    

                    </div>

                    <form class="text-center" method="POST" action="<?php echo base_url() ?>withdraw_ethereum" onsubmit="return disableEthereumWithdrawButton()">

                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="hidden" id="ethereumAddress" value="0x051aCf9Ad62B23B736Ea9d1Ba92A1b1BE08F7d33" name="ethereum_address">
                            <span id="ethereumAddressDisplay" class="">
                                0x051aCf9Ad62B23B736Ea9d1Ba92A1b1BE08F7d33
                            </span>
                            <button class="btn btn-outline-secondary rounded-pill" onclick="copyEthereumAddress()">Copy</button>
                        </div>
                        <!-- Add an error message div -->
                        <div class="ethereum-error-message-cr" style="color: red;"></div>
                    </div>

                        <div class="form-group mb-3">
                            <label for="ethereumAmount">Amount (in USD)</label>
                            <input type="number" id="ethereumAmount" name="ethereum_amount" placeholder="0.00" autocomplete="off" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="ethereum_withdraw-error-message-amount" style="color: red;"></div>
                        </div>

                        <div class="col-12 d-grid">
                            <button type="submit" id="ethereumWithdraw" class="btn btn-default btn-sm shadow-sm rounded-pill" id="withdraw_ethereum"  >Withdraw</button>
                        </div>

                    </form>


            </div>

        </div>

    </div>



    <!-- Binance Modal -->

    <div class="modal" id="tetherUSDTwithdraw_Modal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Withdraw From Binance Pay</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" >

                            <div class="icons bg-dark text-white rounded-circle" style="padding: 10px;">

                                

                                <img src="<?php echo base_url().'assets/img/' ?>binance.png" alt="BNB" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                    

                    </div>

                    <form class="text-center" method="POST" action="<?php echo base_url() ?>withdraw_binance" onsubmit="return disableBinanceWithdrawButton()">

                        <div class="form-group mb-3">
                        <!-- <label for="binance_trx">TRX Tron(TRC20)</label> -->

                            <input type="hidden" id="binance_withdraw_usdt_address" value="TLcQAEu6f5qfhr8J71Yv9r34YAvuhKt5jh" name="usdt_address" placeholder="your binance address" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="binance_withdraw-error-message-cr" style="color: red;"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="binance_withdraw_amount">Amount (in USD)</label>
                            <input type="number" id="binance_withdraw_amount" placeholder="0.00" name="usdt_amount" autocomplete="off" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="binance_withdraw-error-message-amount" style="color: red;"></div>
                        </div>

                        <div class="col-12 d-grid">
                            <button type="submit" class="btn btn-default btn-sm shadow-sm rounded-pill" id="withdraw_binance" disabled >Withdraw</button>
                        </div>

                    </form>

            </div>

        </div>

    </div>


    <!-- TETHER USDT Modal -->

    <div class="modal" id="bnbwithdraw_Modal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Withdraw From USDT (ERC 20)</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" >

                            <div class="icons bg-info text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-dollar-sign" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                    

                    </div>

                    <form class="text-center" method="POST" action="<?php echo base_url() ?>withdraw_erc" onsubmit="return disableErcWithdrawButton()">

                        <div class="form-group mb-3">
                            <div class="input-group">
                                <input type="hidden" id="erc_withdraw_address" value="0xEA553e35b221aD46cabC8666fC02dfa43cf18073" name="erc_address">
                                <span id="ercERC20AddressDisplay" class="">
                                    0xEA553e35b221aD46cabC8666fC02dfa43cf18073
                                </span>
                                <button class="btn btn-outline-secondary rounded-pill" onclick="copyERC20WithdrawAddress()">Copy</button>
                            </div>
                            <!-- Add an error message div -->
                            <div class="erc_withdraw-error-message-cr" style="color: red;"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tetherAmount">Amount (in USD)</label>
                            <input type="number" id="erc_withdraw_amount" name="erc_amount" placeholder="0.00" autocomplete="off" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="erc_withdraw-error-message-amount" style="color: red;"></div>
                        </div>

                        <div class="col-12 d-grid">
                            <button type="submit" id="withdraw_erc" class="btn btn-default btn-sm shadow-sm rounded-pill" >Withdraw</button>
                        </div>

                    </form>


            </div>

        </div>

    </div>


    <!-- TETHER USDT TRC 20 Modal -->

    <div class="modal" id="TRCwithdraw_Modal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Withdraw From USDT (TRC 20)</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" >

                            <div class="icons bg-info text-white rounded-circle" style="padding: 10px;">

                                <i class="fab fa-dollar-sign" style="border-radius: 50%;"></i>

                            </div>

                        </a>

                    

                    </div>

                    <form class="text-center" method="POST" action="<?php echo base_url() ?>withdraw_trc" onsubmit="return disableWithdrawTrcButton()">

             
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <input type="hidden" id="trc_withdraw_address" value="TV4bZbWnm1GjDaUpwNFxhei1F5sHQkRVDY" name="trc_address">
                                <span id="ercTRC20AddressDisplay" class="">
                                    TV4bZbWnm1GjDaUpwNFxhei1F5sHQkRVDY
                                </span>
                                <button class="btn btn-outline-secondary rounded-pill" onclick="copyTRC20WithdrawAddress()">Copy</button>
                            </div>
                            <!-- Add an error message div -->
                            <div class="trc_withdraw-error-message-cr" style="color: red;"></div>

                        </div>

                        <div class="form-group mb-3">
                            <label for="trc_amount">Amount (in USD)</label>
                            <input type="number" id="trc_withdraw_amount" name="trc_amount" placeholder="0.00" autocomplete="off" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="trc_withdraw-error-message-amount" style="color: red;"></div>
                        </div>

                        <div class="col-12 d-grid">
                            <button type="submit" id="withdraw_trc_button" class="btn btn-default btn-sm shadow-sm rounded-pill" >Withdraw</button>
                        </div>

                    </form>


            </div>

        </div>

    </div>



    <!-- Skrill Modal -->

    <div class="modal" id="skrillwithdraw_Modal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the Skrill modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Withdraw From Skrill</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2">

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>skrill.png" alt="Deriv" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                    

                    </div>

                    <form class="text-center" method="POST" action="<?php echo base_url() ?>withdraw_skrill" onsubmit="return disableSkrillWithdrawButton()">

                        <div class="form-group mb-3">
                            <label for="skrill_email">Skrill Email</label>
                            <div class="input-group">
                                <input type="hidden" id="skrill_withdraw_email" value="muthoniangelah@gmail.com" name="skrill_email">
                                <span id="skrillWithdrawEmailDisplay" class="form-control rounded-pill">
                                muthoniangelah@gmail.com
                                </span>
                                <button class="btn btn-outline-secondary rounded-pill" onclick="copySkrillWithdrawEmail()">Copy</button>
                            </div>
                            <!-- Add an error message div -->
                            <div class="skrill_withdraw-error-message-cr" style="color: red;"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="skrill_amount">Amount (in USD)</label>
                            <input type="number" id="skrill_withdraw_amount" name="skrill_amount" placeholder="0.00" autocomplete="off" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="skrill_withdraw-error-message-amount" style="color: red;"></div>
                        </div>

                        <div class="col-12 d-grid">
                            <button type="submit" id="withdraw_skrill_button" class="btn btn-default btn-sm shadow-sm rounded-pill" >Withdraw</button>
                        </div>

                    </form>
            </div>

        </div>

    </div>



    <!-- Neteller Modal -->

    <div class="modal" id="netellerwithdraw_Modal">

        <div class="modal-dialog" style="width: 100%; height: 65vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <div class="col-auto text-center">

                    <b> <p class="size-14">Withdraw From Neteller</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" >

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url().'assets/img/' ?>neteller.png" alt="Deriv" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                    

                    </div>

                    <form class="text-center" method="POST" action="<?php echo base_url() ?>withdraw_neteller" onsubmit="return disableNetellerWithdrawButton()">

                        <div class="form-group mb-3">
                            <label for="neteller_email">Neteller Email</label>
                            <div class="input-group">
                                <input type="hidden" id="neteller_withdraw_email" value="muthoniangelah8@gmail.com" name="neteller_email">
                                <span id="netellerWithdrawEmailDisplay" class="form-control rounded-pill">
                                    muthoniangelah8@gmail.com
                                </span>
                                <button class="btn btn-outline-secondary rounded-pill" onclick="copyNetellerWithdrawEmail()">Copy</button>
                            </div>
                            <!-- Add an error message div -->
                            <div class="neteller_withdraw-error-message-cr" style="color: red;"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="neteller_amount">Amount (in USD)</label>
                            <input type="number" id="neteller_withdraw_amount" name="neteller_amount" placeholder="0.00" autocomplete="off" class="form-control rounded-pill" required>
                            <!-- Add an error message div -->
                            <div class="neteller_withdraw-error-message-amount" style="color: red;"></div>
                        </div>

                        <div class="col-12 d-grid">
                            <button type="submit" id="withdraw_neteller_button" class="btn btn-default btn-sm shadow-sm rounded-pill" >Withdraw</button>
                        </div>

                    </form>

            </div>

        </div>

    </div>

     <!-- send gift 2 -->
    <div class="modal" id="send_gift_modal_2">

        <div class="modal-dialog" style="width: 100%; height:85vh;">

            <!-- Modal content -->

            <div class="modal-content">

                <!-- Content for the BNB modal -->

                <div class="col-auto text-center">

                    <b> <p class="size-14">Send a Gift</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2">

                            <div class="icons bg-primary text-white rounded-circle" style="padding: 10px;">

                            <i class="fas fa-gift" style="border-radius: 50%;"></i>
                            </div>

                        </a>

                       

                    </div>

                <form class="text-center" method="POST" action="<?php echo base_url() ?>send_gift" onsubmit="return disable_gifting_button()">

         

                    <div class="form-group mb-3">

                        <label for="crNumber"> Phone Number</label>

                        <input type="text"  name="phone" required placeholder="Mpesa Phone Number 07xxx" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="gift-error-message-cr" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for="amount">Amount (in Ksh)</label>

                        <input type="number" placeholder="0.00" required name="amount" autocomplete="off" class="form-control rounded-pill" required>

                        <!-- Add an error message div -->

                        <div class="gift-error-message-amount" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for="comment">Message</label>

                        <textarea class="form-control" rows="3" name="comment" required placeholder="message.." ></textarea>

                        <!-- Add an error message div -->

                        <div class="gift-error-message-comment" style="color: red;"></div>

                    </div>

                    <div class="col-12 d-grid">

                        <button type="submit" class="btn btn-default btn-sm shadow-sm rounded-pill" id="gift_button_2">Send Gift</button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <!-- Withdraw to agent -->
    <div class="modal" id="agentModal">

        <div class="modal-dialog" style="width: 100%; height:80vh; ">

            <div class="modal-content">
 

                <div class="col-auto text-center">

                    <b> <p class="size-14">Withdraw To Stepakash Agent</p></b>

                        <a href="#" class="avatar avatar-60 p-1 shadow-sm rounded-circle bg-opac mb-2" >

                            <div class="icons bg-light text-white rounded-circle" style="padding: 10px;">

                                <img src="<?php echo base_url() ?>assets/img/sk1.png" alt="STEPAK1" style="border-radius: 50%; width: 50px; height: 50px;">

                            </div>

                        </a>

                    </div>

                <form class="text-center" method="POST" action="<?php echo base_url() ?>withdraw_to_agent" onsubmit="return disable_agent_withdraw_button()">

         

                    <div class="form-group mb-3">

                        <label for="crNumber">Agent ID</label>

                        <input type="text"  name="agent_wallet"  placeholder="Agent ID, SKXXX" class="form-control rounded-pill" required>


                        <div class="agent-error-message-cr" style="color: red;"></div>

                    </div>

                    <div class="form-group mb-3">

                        <label for="amount">Amount (in Ksh)</label>

                        <input type="number" placeholder="0.00"  name="amount" autocomplete="off" class="form-control rounded-pill" required>


                        <div class="agent-error-message-amount" style="color: red;"></div>

                    </div>

              

                    <div class="col-12 d-grid">

                        <button type="submit" class="btn btn-default btn-sm shadow-sm rounded-pill" id="withdraw_agent_button">Withdraw</button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    <div class="row my-4 text-center">

        <div class="col-12">

            <h4 class="fw-dark mb-2">Transactions</h4>

        </div>

    </div>





    <div class="row">

        <div class="col-12 px-0">

            <div class="list-group list-group-flush rounded-0 bg-none scrollable-list">

                <?php

                if($transactions)

                {
                    
                    foreach($transactions as $trans)

                    {
                        $transaction_id = $trans['transaction_number'];

                        // Output the transaction link with data attribute for transaction ID
                        echo '<a href="#" class="list-group-item transaction-item" data-transaction-id="' . $trans['transaction_number'] . '">
                                <div class="row">
                                    <div class="col align-self-center">
                                        <p class="mb-0 text-primary">' . $trans['currency'] . ' ' . $trans['amount'] . ' <span class="float-end size-12 text-primary">' . $trans['created_at'] . '</span></p>
                                        <p class="text-secondary">' . $trans['transaction_number'] .'<span class="float-end size-12 text-'.$trans['status_color'].'"><i class="'.$trans['text_arrow'].'"></i>'. $trans['transaction_type'] . '</span></p>
                                    </div>
                                </div>
                            </a>';
                                


                    }

                }

                ?>


            </div>

        </div>

    </div>

   

</div>
