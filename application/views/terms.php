<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Use - STEPAKASH</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2d5016;
            background-image: linear-gradient(to bottom right, rgba(0, 165, 79, 0.08), rgba(212, 175, 55, 0.08));
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 3px solid #d4af37;
        }

        .header {
            background: linear-gradient(135deg, #2d5016, #1a4f0a);
            color: white;
            text-align: center;
            padding: 40px 20px;
            position: relative;
        }

        .header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #d4af37, #ffd700, #d4af37);
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            color: #d4af37;
        }

        .content {
            padding: 40px;
        }

        h2 {
            color: #1a4f0a;
            font-size: 1.4rem;
            font-weight: 600;
            margin: 30px 0 20px 0;
            padding: 15px 0;
            border-bottom: 3px solid #d4af37;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        h2::after {
            content: "";
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 80px;
            height: 3px;
            background: #2d5016;
        }

        .acknowledgment {
            background: linear-gradient(135deg, #f8fff8, #e8f5e8);
            border: 2px solid #d4af37;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.1);
            position: relative;
        }

        .acknowledgment::before {
            content: "!";
            position: absolute;
            top: -15px;
            left: 30px;
            background: #d4af37;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .acknowledgment h2 {
            color: #1a4f0a;
            font-size: 1.2rem;
            margin-bottom: 20px;
            border-bottom: 2px solid #d4af37;
            text-transform: none;
            letter-spacing: normal;
        }

        .acknowledgment p {
            margin: 15px 0;
            padding-left: 25px;
            position: relative;
            color: #2d5016;
            font-weight: 500;
        }

        .acknowledgment p::before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #d4af37;
            font-weight: bold;
            font-size: 1.1rem;
        }

        ol {
            counter-reset: item;
            padding-left: 0;
            margin: 20px 0;
        }

        ol li {
            display: block;
            margin: 25px 0;
            padding: 25px;
            background: linear-gradient(135deg, #f0f8f0, #ffffff);
            border-radius: 12px;
            border-left: 5px solid #d4af37;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            position: relative;
            color: #2d5016;
            line-height: 1.7;
        }

        ol li::before {
            content: counter(item);
            counter-increment: item;
            position: absolute;
            left: -15px;
            top: 15px;
            background: linear-gradient(135deg, #1a4f0a, #2d5016);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            border: 3px solid #d4af37;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .disclaimer {
            background: linear-gradient(135deg, #fffacd, #fff8dc);
            border: 2px solid #d4af37;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.15);
            position: relative;
        }

        .disclaimer::before {
            content: "⚠";
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: #d4af37;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: bold;
            border: 3px solid white;
        }

        .disclaimer h2 {
            color: #b8860b;
            margin-bottom: 15px;
            text-align: center;
        }

        .disclaimer p {
            color: #8b4513;
            font-weight: 600;
            font-size: 1.1rem;
            text-align: center;
        }

        .footer {
            background: linear-gradient(135deg, #1a4f0a, #2d5016);
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            border-top: 3px solid #d4af37;
        }

        .footer p {
            margin: 0;
            opacity: 0.9;
            color: #d4af37;
        }

        .last-updated {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid #d4af37;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            font-size: 0.9rem;
            color: #2d5016;
        }

        .last-updated strong {
            color: #1a4f0a;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .content {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            ol li {
                padding: 20px 20px 20px 35px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>STEPAKASH</h1>
            <p class="subtitle">Terms of Use & Conditions</p>
        </div>
        
        <div class="content">
            <div class="last-updated">
                <strong>Last Updated:</strong> <?php echo date('F j, Y \a\t g:i A'); ?>
            </div>

            <div class="acknowledgment">
                <h2>By accepting these Terms of Use and using the STEPAKASH Service you acknowledge that:</h2>
                <p>(i) we are not a bank and your Account is not a bank account;</p>
                <p>(ii) Accounts are not insured by any government agency or Deriv Broker;</p>
                <p>(iii) we do not act as a trustee, fiduciary or escrow holder in respect of balances in your Account; and</p>
                <p>(iv) we do not pay you interest on any balances in your Account.</p>
            </div>

            <h2>Terms and Conditions</h2>
            <ol>
                <li>To become a member, you must be 18 years of age and above/over.</li>
                <li>You must be a resident of the country where we provide services.</li>
                <li>You may not permit any other person to use your Account. You may not open more than one Account or have Multiple CR Accounts in our system, without notice, we will close any or all of the Accounts of a Member who has, or whom we reasonably suspect has, unauthorized multiple Accounts.</li>
                <li>We reserve the right to decline any Transaction at our sole discretion in circumstances where that Transaction is fraudulent would be in breach of these Terms of Use or any applicable law and regulation or you have insufficient funds to make the Transaction. We shall not be liable in the event that a DERIV or MPESA refuses to accept your WITHDRAWAL/DEPOSIT or if we do not authorize a Transaction.</li>
                <li>When making a Payment from your Account, you may not designate an amount in excess of the balance (plus the applicable Fees) in your Account at the time the request is made. If you attempt to do so, your Payment request will be denied. The funds on your STEPAKASH WALLET account must also be sufficient to cover any minimum Withdrawal amount. If you do not have sufficient funds on your STEPAKASH WALLET account, your request to withdraw funds will be rejected.</li>
            </ol>

            <div class="disclaimer">
                <h2>DISCLAIMER:</h2>
                <p>Electronic money accounts are not bank accounts. In the unlikely event that we become insolvent, you may lose the electronic money held in your Account.</p>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> STEPAKASH. All rights reserved.</p>
        </div>
    </div>
</body>
</html>