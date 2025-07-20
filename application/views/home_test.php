

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

<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Mobile Money Interface">
    <meta name="author" content="">
    <title>STEPAKASH</title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?php echo base_url() ?>manifest.json" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?php echo base_url() ?>assets/img/stepak_180.png" sizes="180x180">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/stepak_32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/stepak_16.png" sizes="16x16" type="image/png">

    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&amp;display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #00a54f;
            --secondary-color: #007f3d;
            --accent-color: #d4af37;
            --accent-hover: #ba9a2f;
            --text-color: #333333;
            --light-text: #666666;
            --background: #f5f7f5;
            --card-bg: #ffffff;
            --success: #00a54f;
            --danger: #e74c3c;
            --warning: #f39c12;
            --header-height: 60px;
            --footer-height: 70px;
            --sidebar-width: 280px;
        }

        [data-bs-theme="dark"] {
            --primary-color: #00a54f;
            --secondary-color: #007f3d;
            --accent-color: #d4af37;
            --accent-hover: #ba9a2f;
            --text-color: #f5f5f5;
            --light-text: #cccccc;
            --background: #121212;
            --card-bg: #1e1e1e;
            --success: #00a54f;
            --danger: #e74c3c;
            --warning: #f39c12;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--background);
            color: var(--text-color);
            padding-top: var(--header-height);
            padding-bottom: var(--footer-height);
            min-height: 100vh;
            transition: all 0.3s ease;
            overflow-x: hidden;
        }

        .header {
            height: var(--header-height);
            background-color: var(--card-bg);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
        }

        .footer {
            height: var(--footer-height);
            background-color: var(--card-bg);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .nav-pills .nav-link {
            color: var(--text-color);
        }

        .card {
            background-color: var(--card-bg);
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: #333;
        }

        .btn-accent:hover {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
            color: #333;
        }

        .balance-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px;
            padding: 20px;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .balance-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            transform: rotate(30deg);
        }

        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .quick-action:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: translateY(-5px);
        }

        .quick-action-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            margin-bottom: 8px;
            font-size: 20px;
        }

        .quick-action-icon.bg-accent {
            background-color: var(--accent-color);
        }

        .transaction-item {
            border-left: 4px solid var(--primary-color);
            padding-left: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .transaction-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .profile-dropdown {
            position: absolute;
            right: 15px;
            top: var(--header-height);
            width: 250px;
            background-color: var(--card-bg);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 15px;
            z-index: 1000;
            display: none;
        }

        .profile-dropdown.show {
            display: block;
        }

        .flag-icon {
            width: 24px;
            height: 16px;
            vertical-align: middle;
            margin-left: 5px;
        }

        .verification-badge {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            line-height: 20px;
            font-size: 12px;
            margin-left: 5px;
        }

        .theme-toggle {
            cursor: pointer;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .theme-toggle:hover {
            transform: rotate(30deg);
        }

        /* Sidebar styles */
        .sidebar-wrap {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 999;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            width: var(--sidebar-width);
            height: 100%;
            transition: all 0.3s ease;
            overflow-y: auto;
            z-index: 1001;
        }

        .sidebar.active {
            left: 0;
            pointer-events: auto;
        }

        .sidebar-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        /* Form styles */
        .form-control {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            background-color: var(--card-bg);
            color: var(--text-color);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 165, 79, 0.25);
        }

        /* Modal styles */
        .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
            border-radius: 15px;
        }

        .modal-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .modal-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        
        @media (max-width: 576px) {
            .modal-content {
                border-radius: 30px;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            :root {
                --header-height: 56px;
                --footer-height: 60px;
            }


            .quick-action-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .balance-card {
                padding: 15px;
            }

            .balance-card h2 {
                font-size: 1.5rem;
            }

            .footer .btn {
                padding: 0.5rem;
                font-size: 0.8rem;
            }

            .footer .btn i {
                font-size: 1rem;
                margin-bottom: 0.2rem;
            }

           

            .sidebar {
                width: 85%;
            }
        }

        @media (min-width: 768px) {
            .sidebar-wrap {
                display: none;
            }

            .header {
                padding-left: 0;
            }

            .container {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            .container {
                max-width: 960px;
            }
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 1140px;
            }
        }

        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }
            #printableReceipt, #printableReceipt * {
                visibility: visible;
            }
            #printableReceipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <button class="btn btn-link text-decoration-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div class="col text-center">
                    <img src="<?php echo base_url() ?>assets/img/stepak.png" alt="Stepakash" height="30">
                </div>
                <div class="col-auto d-flex align-items-center">
                    <div class="theme-toggle me-3" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </div>
                    <div class="position-relative">
                        <button class="btn btn-link text-decoration-none" id="profileToggle">
                            <i class="fas fa-user-circle"></i>
                            <?php if ($agent == 1) : ?>
                                <span class="verification-badge">✓</span>
                            <?php endif; ?>
                        </button>
                        <div class="profile-dropdown" id="profileDropdown">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user-circle fa-3x me-3"></i>
                                <div>
                                    <h6 class="mb-0"><?php echo $this->session->userdata('phone'); ?></h6>
                                    <small class="text-muted">Wallet ID: <?php echo $this->session->userdata('wallet_id'); ?></small>
                                </div>
                            </div>
                            <hr>
                            <a href="<?php echo base_url() ?>changepassword" class="d-block py-2 text-decoration-none">
                                <i class="fas fa-key me-2"></i> Change Password
                            </a>
                            <a href="<?php echo base_url() ?>logout" class="d-block py-2 text-decoration-none text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="sidebar-wrap sidebar-overlay" id="sidebar">
        <div class="sidebar">
            <div class="sidebar-header p-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-circle fa-3x me-3"></i>
                    <div>
                        <h5 class="mb-0"><?php echo $this->session->userdata('phone'); ?></h5>
                        <small>Wallet ID: <?php echo $this->session->userdata('wallet_id'); ?></small>
                    </div>
                </div>
            </div>
            <hr class="my-0">
            <div class="sidebar-menu p-3">
                <a href="<?php echo base_url() ?>home" class="d-block py-3 text-white text-decoration-none">
                    <i class="fas fa-home me-3"></i> Home
                </a>
                <a href="<?php echo base_url() ?>transactions" class="d-block py-3 text-white text-decoration-none">
                    <i class="fas fa-exchange-alt me-3"></i> Transactions
                </a>
                <a href="<?php echo base_url() ?>changepassword" class="d-block py-3 text-white text-decoration-none">
                    <i class="fas fa-key me-3"></i> Change Password
                </a>
                <hr class="my-2">
                <a href="<?php echo base_url() ?>logout" class="d-block py-3 text-white text-decoration-none">
                    <i class="fas fa-sign-out-alt me-3"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container py-3">
       

        <!-- Balance Card -->
        <div class="card balance-card">
            <div class="card-body text-center">
                <p class="mb-1">Total Balance</p>
                <h2 class="mb-0">KES <?php echo number_format($total_balance, 2, '.', ','); ?> <img src="https://flagcdn.com/ke.svg" class="flag-icon"></h2>
            </div>
        </div>

        <!-- Flash Message -->
        <?php if ($flash = $this->session->flashdata('msg')) : ?>
            <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                <?php echo $flash; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="quick-action" data-bs-toggle="modal" data-bs-target="#depositMpesaModal">
                            <div class="quick-action-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <small>Deposit Mpesa</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="quick-action" data-bs-toggle="modal" data-bs-target="#withdrawMpesaModal">
                            <div class="quick-action-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <small>Withdraw Mpesa</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="quick-action" data-bs-toggle="modal" data-bs-target="#depositModal">
                            <div class="quick-action-icon bg-accent">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <small>Transfer</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="quick-action" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                            <div class="quick-action-icon bg-accent">
                                <i class="fas fa-download"></i>
                            </div>
                            <small>Receive</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Recent Transactions</h5>
                <div class="list-group list-group-flush">
                    <?php if ($transactions) : ?>
                        <?php foreach ($transactions as $trans) : ?>
                            <a href="#" class="list-group-item list-group-item-action transaction-item" data-bs-toggle="modal" data-bs-target="#transactionModal" data-transaction-id="<?php echo $trans['transaction_number']; ?>">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1"><?php echo $trans['transaction_type']; ?></h6>
                                        <small class="text-muted"><?php echo $trans['transaction_number']; ?></small>
                                    </div>
                                    <div class="text-end">
                                        <strong class="<?php echo $trans['status_color']; ?>"><?php echo $trans['currency'] . ' ' . $trans['amount']; ?></strong>
                                        <div class="text-muted small"><?php echo $trans['created_at']; ?></div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="text-center py-3 text-muted">No transactions found</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Navigation -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <button class="btn w-100 py-3 text-center" data-bs-toggle="modal" data-bs-target="#mpesaActionsModal">
                        <i class="fas fa-mobile-alt d-block mb-1"></i>
                        <small>M-Pesa</small>
                    </button>
                </div>
                <div class="col-4">
                    <a href="<?php echo base_url() ?>home" class="btn w-100 py-3 text-center">
                        <i class="fas fa-home d-block mb-1"></i>
                        <small>Home</small>
                    </a>
                </div>
                <div class="col-4">
                    <button class="btn w-100 py-3 text-center" data-bs-toggle="modal" data-bs-target="#derivActionsModal">
                        <i class="fas fa-exchange-alt d-block mb-1"></i>
                        <small>Deriv</small>
                    </button>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mpesa Actions Modal -->
    <div class="modal fade" id="mpesaActionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">M-Pesa Services</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 text-center mb-3">
                            <button class="btn btn-outline-primary w-100 py-4" data-bs-toggle="modal" data-bs-target="#depositMpesaModal" data-bs-dismiss="modal">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                <div>Deposit</div>
                            </button>
                        </div>
                        <div class="col-6 text-center mb-3">
                            <button class="btn btn-outline-primary w-100 py-4" data-bs-toggle="modal" data-bs-target="#withdrawMpesaModal" data-bs-dismiss="modal">
                                <i class="fas fa-wallet fa-2x mb-2"></i>
                                <div>Withdraw</div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deriv Actions Modal -->
    <div class="modal fade" id="derivActionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deriv Services</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 text-center mb-3">
                            <button class="btn btn-outline-primary w-100 py-4" data-bs-toggle="modal" data-bs-target="#depositModal" data-bs-dismiss="modal">
                                <i class="fas fa-exchange-alt fa-2x mb-2"></i>
                                <div>Transfer</div>
                            </button>
                        </div>
                        <div class="col-6 text-center mb-3">
                            <button class="btn btn-outline-primary w-100 py-4" data-bs-toggle="modal" data-bs-target="#withdrawModal" data-bs-dismiss="modal">
                                <i class="fas fa-download fa-2x mb-2"></i>
                                <div>Receive</div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deposit Mpesa Modal -->
    <div class="modal fade" id="depositMpesaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deposit from M-Pesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo base_url() ?>Main/DepositFromMpesa" onsubmit="return disableDerivDeposit()">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $this->session->userdata('phone'); ?>" readonly required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (KES)</label>
                            <input type="number" class="form-control" id="amount" name="amount" step="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="deposit_button" class="btn btn-primary">Deposit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Withdraw Mpesa Modal -->
    <div class="modal fade" id="withdrawMpesaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Withdraw to M-Pesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo base_url() ?>Main/WithdrawToMpesa" onsubmit="return disableWithdrawButton()">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $this->session->userdata('phone'); ?>" readonly required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (KES)</label>
                            <input type="number" class="form-control" id="amount" name="amount" min="10" step="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="mpesa_withdraw" class="btn btn-primary">Withdraw</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Deposit (Transfer) Modal -->
    <div class="modal fade" id="depositModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer to Deriv</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo base_url() ?>Main/DepositToDeriv" onsubmit="return disableDepositButton()">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="crNumberdepo" class="form-label">CR Number</label>
                            <input type="text" class="form-control" id="crNumberdepo" name="crNumber" style="text-transform: uppercase;" value="<?php echo $this->session->userdata('account_number'); ?>" placeholder="CR1234567" required>
                            <div class="error-message-cr text-danger small mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="amountdepo" class="form-label">Amount (KES)</label>
                            <input type="number" class="form-control" id="amountdepo" name="amount" placeholder="0.00" autocomplete="off" required>
                            <div class="error-message-amount text-danger small mt-1"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="depo" class="btn btn-primary" disabled>Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Withdraw (Receive) Modal -->
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Withdraw from Deriv</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo base_url() ?>Main/WithdrawFromDeriv" onsubmit="return disableSubmitButtonWithdraw()">
                    <div class="modal-body">
                        <div class="alert alert-info small mb-3">
                            <strong>Instructions:</strong> Go to your deriv.com account > Click Cashier > Payment agent > Withdraw. 
                            A verification link will be sent to your email. Click on that link and confirm the amount, 
                            exact amount equivalent to your Stepak withdraw. Then Select STEPAKASH as payment agent, 
                            leave blank payment agent ID, ENTER AMOUNT IN USD.
                        </div>
                        <div class="mb-3">
                            <label for="crNumberWithdraw" class="form-label">CR Number</label>
                            <input type="text" class="form-control" id="crNumberWithdraw" name="crNumber_withdraw" style="text-transform: uppercase;" value="<?php echo $this->session->userdata('account_number'); ?>" required>
                            <div class="error-message-cr-withdraw text-danger small mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="amountWithdraw" class="form-label">Amount (USD)</label>
                            <input type="number" class="form-control" id="amountWithdraw" name="deriv_amount" step="0.01" required>
                            <div class="error-message-amount-withdraw text-danger small mt-1"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="withdrawButton" class="btn btn-primary" disabled>Withdraw</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div class="modal fade" id="transactionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="transactionDetails">
                    <!-- Transaction details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printReceipt()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden div for printing -->
    <div id="printableReceipt" style="display: none;"></div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- html2canvas for printing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <!-- jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <script>
        // Theme toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const currentTheme = localStorage.getItem('theme') || 'light';
            
            // Set initial theme
            document.documentElement.setAttribute('data-bs-theme', currentTheme);
            updateThemeIcon(currentTheme);
            
            // Toggle theme on click
            themeToggle.addEventListener('click', function() {
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                document.documentElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });
            
            function updateThemeIcon(theme) {
                const icon = themeToggle.querySelector('i');
                if (theme === 'dark') {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                } else {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                }
            }
            
            // Profile dropdown toggle
            const profileToggle = document.getElementById('profileToggle');
            const profileDropdown = document.getElementById('profileDropdown');
            
            profileToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                profileDropdown.classList.remove('show');
            });
            
            // Sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarWrap = document.querySelector('.sidebar-wrap');
            
            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('active');
                sidebarWrap.classList.toggle('active');
            });
            
            // Close sidebar when clicking outside
            document.addEventListener('click', function(e) {
                if (sidebar && !sidebar.contains(e.target)) {
                    sidebar.classList.remove('active');
                    sidebarWrap.classList.remove('active');
                }
            });
            
            // Close sidebar when clicking on a link
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarWrap.classList.remove('active');
                });
            });
            
            // Transaction modal handling
            const transactionModal = new bootstrap.Modal(document.getElementById('transactionModal'));
            const transactionDetails = document.getElementById('transactionDetails');
            
            document.querySelectorAll('.transaction-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const transactionId = this.getAttribute('data-transaction-id');
                    fetchTransactionDetails(transactionId);
                });
            });
            
            function fetchTransactionDetails(transactionId) {
                // Show loading state
                transactionDetails.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                
                // Fetch transaction details via AJAX
                fetch("<?php echo base_url() ?>query_receipt", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `request_id=${transactionId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        // Format the transaction details
                        const transaction = data.data;
                        const html = `
                            <div class="receipt" id="receiptContent">
                                <div class="d-flex justify-content-between mb-3">
                                    <img src="<?php echo base_url() ?>assets/img/stepak.png" height="40" alt="Stepakash">
                                    <div class="text-end">
                                        <h6 class="mb-0">Receipt</h6>
                                        <small class="text-muted">${transaction.transaction_date}</small>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Transaction ID</small>
                                        <strong>${transaction.transaction_number}</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted d-block">Status</small>
                                        <span class="badge bg-${transaction.status === 'completed' ? 'success' : 'warning'}">${transaction.status}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Amount</small>
                                    <h4 class="mb-0">${transaction.currency} ${transaction.amount}</h4>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Transaction Type</small>
                                    <strong>${transaction.transaction_type}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Description</small>
                                    <strong>${transaction.description}</strong>
                                </div>
                                <hr>
                                <div class="text-center text-muted small">
                                    Thank you for using Stepakash
                                </div>
                            </div>
                        `;
                        transactionDetails.innerHTML = html;
                        transactionModal.show();
                    } else {
                        transactionDetails.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                        transactionModal.show();
                    }
                })
                .catch(error => {
                    transactionDetails.innerHTML = `<div class="alert alert-danger">Failed to load transaction details</div>`;
                    transactionModal.show();
                });
            }
            
            // Form validation for Deriv deposit
            const crNumberInput = document.getElementById("crNumberdepo");
            const amountInput = document.getElementById("amountdepo");
            const crErrorDiv = document.querySelector(".error-message-cr");
            const amountErrorDiv = document.querySelector(".error-message-amount");
            const submitButton = document.getElementById("depo");
            
            crNumberInput.addEventListener("input", updateButtonState);
            amountInput.addEventListener("input", updateButtonState);
            
            function updateButtonState() {
                const crNumber = crNumberInput.value;
                const amountKsh = parseFloat(amountInput.value);
                const startsWithCR = crNumber.startsWith("CR");
                const isExactLength = crNumber.length === 9 || crNumber.length === 8;
                const hasSpecialCharacters = /[!@#^&*()_+{}\[\]:;<>,.?~\\\/]/.test(crNumber);
                
                if (!startsWithCR) {
                    crErrorDiv.textContent = "CR number should start with 'CR', e.g., CR1234567.";
                } else if (!isExactLength) {
                    crErrorDiv.textContent = "CR number should be exactly 9 or 8 characters long, including 'CR'.";
                } else if (hasSpecialCharacters) {
                    crErrorDiv.textContent = "CR number cannot contain special characters.";
                } else {
                    crErrorDiv.textContent = "";
                }
                
                if (isNaN(amountKsh)) {
                    amountErrorDiv.textContent = "Please enter a valid amount.";
                } else {
                    amountErrorDiv.textContent = "";
                }
                
                submitButton.disabled = !startsWithCR || !isExactLength || hasSpecialCharacters || isNaN(amountKsh);
            }
            
            // Form validation for Deriv withdraw
            const crNumberInputWithdraw = document.getElementById("crNumberWithdraw");
            const amountInputWithdraw = document.getElementById("amountWithdraw");
            const crErrorDivWithdraw = document.querySelector(".error-message-cr-withdraw");
            const amountErrorDivWithdraw = document.querySelector(".error-message-amount-withdraw");
            const withdrawButton = document.getElementById("withdrawButton");
            
            crNumberInputWithdraw.addEventListener("input", updateButtonStateWithdraw);
            amountInputWithdraw.addEventListener("input", updateButtonStateWithdraw);
            
            function updateButtonStateWithdraw() {
                const crNumberWithdraw = crNumberInputWithdraw.value;
                const amountUSDWithdraw = parseFloat(amountInputWithdraw.value);
                const startsWithCRWithdraw = crNumberWithdraw.startsWith("CR");
                const isExactLengthWithdraw = crNumberWithdraw.length === 9 || crNumberWithdraw.length === 8;
                
                if (!startsWithCRWithdraw) {
                    crErrorDivWithdraw.textContent = "CR number should start with 'CR', e.g., CR1234567.";
                } else if (!isExactLengthWithdraw) {
                    crErrorDivWithdraw.textContent = "CR number should be exactly 9 or 8 char long, including 'CR'.";
                } else {
                    crErrorDivWithdraw.textContent = "";
                }
                
                if (isNaN(amountUSDWithdraw)) {
                    amountErrorDivWithdraw.textContent = "Minimum Amount 10 USD";
                } else if (amountUSDWithdraw < 10) {
                    amountErrorDivWithdraw.textContent = "Minimum withdraw 10 USD";
                } else {
                    const message = "Amount to receive: " + (amountUSDWithdraw * <?php echo $deriv_sell; ?>).toFixed(2) + " KES";
                    amountErrorDivWithdraw.textContent = message;
                }
                
                withdrawButton.disabled = !startsWithCRWithdraw || !isExactLengthWithdraw || isNaN(amountUSDWithdraw) || amountUSDWithdraw < 10;
            }
            
            // Print receipt function
            window.printReceipt = function() {
                const receiptContent = document.getElementById('receiptContent').outerHTML;
                const printableDiv = document.getElementById('printableReceipt');
                printableDiv.innerHTML = receiptContent;
                
                // Use html2canvas to capture the receipt as an image
                html2canvas(printableDiv).then(canvas => {
                    // Create a new window with the image
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write('<html><head><title>Receipt</title></head><body>');
                    printWindow.document.write('<img src="' + canvas.toDataURL() + '" style="max-width:100%;">');
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    
                    // Wait for the image to load before printing
                    printWindow.onload = function() {
                        printWindow.print();
                    };
                });
            };
            
            // Disable buttons during form submission
            window.disableWithdrawButton = function() {
                const withdrawButton = document.getElementById("mpesa_withdraw");
                withdrawButton.disabled = true;
                withdrawButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                return true;
            };
            
            window.disableDepositButton = function() {
                const derivButton = document.getElementById("depo");
                derivButton.disabled = true;
                derivButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                return true;
            };
            
            window.disableSubmitButtonWithdraw = function() {
                const withdrawButton = document.getElementById("withdrawButton");
                withdrawButton.disabled = true;
                withdrawButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                return true;
            };
            
            window.disableDerivDeposit = function() {
                const depositButton = document.getElementById("deposit_button");
                depositButton.disabled = true;
                depositButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                return true;
            };
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>