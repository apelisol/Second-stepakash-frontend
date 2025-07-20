<?php
$wallet_id = $this->session->userdata('wallet_id');
$session_id = $this->session->userdata('session_id');
$phone_session = $this->session->userdata('phone');
$checkout_token = $this->session->userdata('checkout_token');

if(!$wallet_id || !$session_id  || !$phone_session) {
    redirect('logout');
}
if(!empty($checkout_token)) {
    redirect('payment_form');
}
?>

<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Mobile Money Interface">
    <meta name="author" content="">
    <title>STEPAKASH - Transactions</title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?php echo base_url() ?>manifest.json" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?php echo base_url() ?>assets/img/stepak_180.png" sizes="180x180">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/stepak_32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/stepak_16.png" sizes="16x16" type="image/png">

    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#059669', 
                            dark: '#10b981',   
                        },
                        secondary: {
                            DEFAULT: '#065f46', // Emerald-700
                            dark: '#047857',    // Emerald-600
                        },
                        accent: {
                            DEFAULT: '#b59a3e', // Custom gold
                            dark: '#d4af37',    // Rich gold
                        },
                        dark: {
                            DEFAULT: '#1e293b', // Slate-800
                            light: '#334155',   // Slate-700
                        },
                        light: {
                            DEFAULT: '#f8fafc', // Slate-50
                            dark: '#e2e8f0',    // Slate-200
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02)',
                        'hard': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'glow': '0 0 10px 2px rgba(181, 154, 62, 0.3)',
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'shimmer': 'shimmer 2s infinite linear',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' },
                        }
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .text-shadow {
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            }
            .gradient-text {
                @apply bg-gradient-to-r from-accent to-primary bg-clip-text text-transparent;
            }
            .bg-gradient-primary {
                @apply bg-gradient-to-br from-primary to-secondary;
            }
            .bg-gradient-accent {
                @apply bg-gradient-to-br from-accent to-yellow-400;
            }
            .sidebar-transition {
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .fade-transition {
                transition: opacity 0.3s ease-in-out;
            }
            .card-hover {
                @apply transition-all duration-300 ease-in-out hover:scale-[1.02] hover:shadow-lg;
            }
            .menu-item-hover {
                @apply transition-all duration-200 hover:bg-opacity-10 hover:bg-primary hover:text-primary hover:pl-6;
            }
            .btn-hover {
                @apply transition-all duration-200 hover:scale-105 hover:shadow-md;
            }
            .quick-action-hover {
                @apply transition-all duration-300 hover:scale-105 hover:shadow-lg hover:border-accent;
            }
            .shimmer-bg {
                background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
                background-size: 200% 100%;
            }
        }
    </style>
</head>

<body class="font-sans bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 h-16 bg-white dark:bg-dark shadow-sm z-50 border-b border-gray-100 dark:border-gray-800">
        <div class="container mx-auto px-4 h-full flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <button id="sidebarToggle" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                    <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
                </button>
            </div>
            
            <div class="flex-1 flex justify-center">
                <img src="<?php echo base_url() ?>assets/img/stepakash-home1.png" alt="Stepakash" class="h-8">
            </div>
            
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <button id="profileToggle" class="flex items-center space-x-1 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                        <div class="w-8 h-8 rounded-full bg-gradient-primary flex items-center justify-center text-white">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <?php if ($agent == 1) : ?>
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-accent rounded-full flex items-center justify-center text-xs text-gray-800">✓</span>
                        <?php endif; ?>
                    </button>
                    
                    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-primary flex items-center justify-center text-white">
                                    <i class="fas fa-user text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white"><?php echo $this->session->userdata('phone'); ?></h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Wallet ID: <?php echo $this->session->userdata('wallet_id'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="py-1">
                            <a href="<?php echo base_url() ?>logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden fade-transition opacity-0"></div>

    <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white dark:bg-gray-800 shadow-lg transform -translate-x-full sidebar-transition z-50">
        <div class="h-full flex flex-col">
            <!-- Sidebar Header -->
            <div class="p-4 bg-gradient-to-r from-green-600 to-emerald-500 text-white relative overflow-hidden">
                <div class="absolute inset-0 shimmer-bg animate-shimmer"></div>
                <div class="flex items-center space-x-3 relative z-10">
                    <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold"><?php echo $this->session->userdata('phone'); ?></h3>
                        <p class="text-xs opacity-80">Wallet ID: <?php echo $this->session->userdata('wallet_id'); ?></p>
                    </div>
                </div>
            </div>  
            <!-- Sidebar Menu -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul>
                    <li>
                        <a href="<?php echo base_url() ?>home" class="flex items-center px-6 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors duration-200">
                            <i class="fas fa-home mr-3 text-blue-500"></i> 
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url() ?>transactions" class="flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors duration-200">
                            <i class="fas fa-exchange-alt mr-3 text-green-500"></i>
                            <span>Transactions</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url() ?>changepassword" class="flex items-center px-6 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors duration-200">
                            <i class="fas fa-key mr-3 text-yellow-500"></i>
                            <span>Change Password</span>
                        </a>
                    </li>
                    <li class="border-t border-gray-200 dark:border-gray-700 mt-2 pt-2">
                        <a href="<?php echo base_url() ?>logout" class="flex items-center px-6 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 dark:text-red-400 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- Sidebar Footer - Contact Info -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col space-y-3">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Need Help?</h4>
                    <a href="https://wa.me/254741554994" target="_blank" class="flex items-center justify-center space-x-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fab fa-whatsapp text-xl"></i>
                        <span>Chat on WhatsApp</span>
                    </a>
                    <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                        <p>Support available 24/7</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Footer - Theme Toggle -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Dark Mode</span>
                    <button id="sidebarThemeToggle" class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-moon text-gray-600 dark:text-yellow-300 theme-icon"></i>
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 pt-16 pb-20 px-4">
        <div class="container mx-auto max-w-3xl">
            <!-- Balance Card -->
            <div class="mt-12"></div>
            <div class="bg-gradient-primary rounded-xl shadow-lg overflow-hidden mb-6 relative">
                <div class="absolute inset-0 bg-gradient-to-br from-white to-transparent opacity-10"></div>
                <div class="p-6 relative z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-white text-opacity-90 text-sm">Total Balance</p>
                            <h2 id="balanceAmount" class="text-2xl font-bold text-white flex items-center">
                                KES <?php echo number_format($total_balance, 2, '.', ','); ?>
                                <img src="https://flagcdn.com/ke.svg" class="w-6 h-4 ml-2 rounded-sm shadow">
                            </h2>
                        </div>
                        <button id="balanceToggle" class="p-1 rounded-full bg-white bg-opacity-20 text-white">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="bg-white bg-opacity-10 p-4 flex justify-between items-center">
                    <span class="text-white text-sm">
                        Last updated: 
                        <?php
                            date_default_timezone_set('Africa/Nairobi');
                            echo date('H:i');
                        ?>
                    </span>
                    <button class="text-white text-sm flex items-center" onclick="location.reload()">
                        <i class="fas fa-sync-alt mr-1 animate-spin-slow"></i> Refresh
                    </button>
                </div>
            </div>

            <!-- Flash Message -->
            <?php if ($flash = $this->session->flashdata('msg')) : ?>
                <div id="flashMessage" class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-20 border-l-4 border-yellow-400 text-yellow-700 dark:text-yellow-200 rounded">
                    <div class="flex justify-between items-center">
                        <p><?php echo $flash; ?></p>
                        <button type="button" class="text-yellow-500 hover:text-yellow-700" onclick="document.getElementById('flashMessage').style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <script>
                    setTimeout(function() {
                        var flash = document.getElementById('flashMessage');
                        if (flash) flash.style.display = 'none';
                    }, 4000);
                </script>
            <?php endif; ?>

            <!-- Transactions List -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800 dark:text-white">All Transactions</h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo count($transactions); ?> items</span>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if ($transactions) : ?>
                        <?php foreach ($transactions as $trans) : ?>
                            <button data-modal-target="transactionModal" data-modal-toggle="transactionModal" data-transaction-id="<?php echo $trans['transaction_number']; ?>" class="w-full p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer text-left">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-600 flex items-center justify-center mr-3">
                                            <i class="fas fa-exchange-alt text-gray-500 dark:text-gray-300"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-800 dark:text-gray-200"><?php echo $trans['transaction_type']; ?></h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo $trans['transaction_number']; ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium <?php echo $trans['status_color']; ?>"><?php echo $trans['currency'] . ' ' . $trans['amount']; ?></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo $trans['created_at']; ?></p>
                                    </div>
                                </div>
                            </button>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-exchange-alt text-3xl mb-3 opacity-50"></i>
                            <p>No transactions found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 shadow-lg border-t border-gray-100 dark:border-gray-700 z-40">
        <div class="container mx-auto px-4">
            <div class="flex justify-between">
                <button data-modal-target="mpesaActionsModal" data-modal-toggle="mpesaActionsModal" class="flex flex-col items-center py-3 px-4 text-gray-500 dark:text-gray-400">
                    <i class="fas fa-mobile-alt text-lg mb-1"></i>
                    <span class="text-xs">M-Pesa</span>
                </button>
                <a href="<?php echo base_url() ?>home" class="flex flex-col items-center py-3 px-4 text-gray-500 dark:text-gray-400">
                    <i class="fas fa-home text-lg mb-1"></i>
                    <span class="text-xs">Home</span>
                </a>
                <a href="<?php echo base_url() ?>transactions" class="flex flex-col items-center py-3 px-4 text-primary dark:text-primary-dark">
                    <i class="fas fa-exchange-alt text-lg mb-1"></i>
                    <span class="text-xs">Transactions</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Mpesa Actions Modal -->
    <div id="mpesaActionsModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-4 md:p-5 text-center">
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">M-Pesa Services</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <button data-modal-target="depositMpesaModal" data-modal-toggle="depositMpesaModal" data-modal-hide="mpesaActionsModal" type="button" class="py-8 px-4 text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2 focus:ring-primary focus:text-primary dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <i class="fas fa-money-bill-wave text-2xl mb-2 text-primary"></i>
                            <div>Deposit</div>
                        </button>
                        <button data-modal-target="withdrawMpesaModal" data-modal-toggle="withdrawMpesaModal" data-modal-hide="mpesaActionsModal" type="button" class="py-8 px-4 text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2 focus:ring-primary focus:text-primary dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <i class="fas fa-wallet text-2xl mb-2 text-primary"></i>
                            <div>Withdraw</div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deposit Mpesa Modal -->
    <div id="depositMpesaModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Deposit from M-Pesa
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="depositMpesaModal">
                        <i class="fas fa-times"></i>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form method="POST" action="<?php echo base_url() ?>Main/DepositFromMpesa" onsubmit="return disableDerivDeposit()">
                    <div class="p-4 md:p-5 space-y-4">
                        <div>
                            <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo $this->session->userdata('phone'); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" readonly required>
                        </div>
                        <div>
                            <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount (KES)</label>
                            <input type="number" id="amount" name="amount" step="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Enter amount in KES" required>
                        </div>
                    </div>
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" id="deposit_button" class="text-white bg-gradient-primary hover:opacity-90 focus:ring-4 focus:outline-none focus:ring-primary font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Deposit
                        </button>
                        <button data-modal-hide="depositMpesaModal" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Withdraw Mpesa Modal -->
    <div id="withdrawMpesaModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Withdraw to M-Pesa
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="withdrawMpesaModal">
                        <i class="fas fa-times"></i>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form method="POST" action="<?php echo base_url() ?>Main/WithdrawToMpesa" onsubmit="return disableWithdrawButton()">
                    <div class="p-4 md:p-5 space-y-4">
                        <div>
                            <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                            <input type="text" id="phone" name="phone" value="<?php echo $this->session->userdata('phone'); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" readonly required>
                        </div>
                        <div>
                            <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount (KES)</label>
                            <input type="number" id="amount" name="amount" min="10" step="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Enter amount in KES" required>
                        </div>
                    </div>
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" id="mpesa_withdraw" class="text-white bg-gradient-primary hover:opacity-90 focus:ring-4 focus:outline-none focus:ring-primary font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Withdraw
                        </button>
                        <button data-modal-hide="withdrawMpesaModal" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div id="transactionModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Transaction Details
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="transactionModal">
                        <i class="fas fa-times"></i>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div id="transactionDetails" class="p-4 md:p-5 space-y-4">
                    <!-- Transaction details will be loaded here -->
                    <div class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-primary"></div>
                    </div>
                </div>
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button onclick="printReceipt()" class="text-white bg-gradient-primary hover:opacity-90 focus:ring-4 focus:outline-none focus:ring-primary font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>
                    <button data-modal-hide="transactionModal" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden div for printing -->
    <div id="printableReceipt" style="display: none;"></div>
    <!-- Flowbite JS for modals -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- html2canvas for printing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <!-- jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</body>
</html>