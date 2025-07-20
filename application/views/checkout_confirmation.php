<style>
    /* CSS for placing the button at the bottom right corner */
    .receipt-footer {
        position: relative;
    }
    .complete {
        position: absolute;
        bottom: 10px;
        background-color: #4CAF50; /* Green */
        right: 10px;
    }
    .footer-image {
    position: absolute;
    bottom: 10px;
    left: 10px;
    width: 100px; /* Adjust width as needed */
    height: auto;
}
</style>
 <div class="receipt">
        <div class="receipt-header">
            <img src="<?php echo base_url().'assets/img/sk1.png'; ?>" alt="Stepakash Logo">
            <h3>Confirm Payment</h3>
            <p>Ref #123456</p>
        </div>
        <form method="post" action="<?php echo site_url('payment_confirmation'); ?>">

        <div class="receipt-body">
            <div class="receipt-row">
                <div class="receipt-col">
                    <div class="receipt-item">
                        <label>Transaction ID:</label>
                        <span><?php echo $uniqueId; ?></span>
                        <input type="hidden" name="unique_id" value="<?php echo $uniqueId; ?>" />
                        <input type="hidden" name="partner_id" value="<?php echo $partner_id; ?>" />
                    </div>
                    <div class="receipt-item">
                        <label>Amount:</label>
                        <span>KES <?php echo number_format($amount, 2); ?></span> <!-- Display amount from decoded token -->
                        <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
                    </div>
                    <div class="receipt-item">
                        <label>Transaction Type:</label>
                        <span>M-Pesa Deposit</span>
                    </div>
                </div>
                <div class="receipt-col">
                    <div class="receipt-item">
                        <label>Sender:</label>
                        <span>Jane Doe</span>
                    </div>
                    <div class="receipt-item">
                        <label>Recipient:</label>
                        <span>John Doe</span>
                    </div>
                    <div class="receipt-item">
                        <label>Status:</label>
                        <span>Completed</span>
                    </div>
                </div>
            </div>
            <div class="receipt-row">
                <div class="receipt-col">
                    <div class="receipt-item">
                        <label>Date:</label>
                        <span>February 1, 2024</span>
                    </div>
                </div>
                <div class="receipt-col">
                    <!-- Leave this column empty to maintain layout -->
                    <div class="receipt-item">
                        <label>Complete:</label>
                        <span><button type="submit" class="btn btn-success">Pay Now</button></span>
                    </div>
                </div>
            </div>
        </div>
        </form>

        <div class="receipt-footer">
               <!-- Footer image -->

            <!-- Button to complete transaction -->
            <p>Thank you for using Stepakash!</p>
            <br>


        </div>

 </div>


