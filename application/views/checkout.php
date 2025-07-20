<!-- payment_form.php -->
<form id="stepakash_payment_form" method="post" action="https://stepakash.com/pay">
    <input type="number" name="amount" value="<?php echo $amount; ?>"> <!-- Amount to be paid -->
    <input type="text" name="uniqueId" value="SK0012"> <!-- Payment description -->

    <!-- Add more fields as needed for transaction details -->

    <button type="submit">Proceed to Stepakash Payment</button>
</form>
 