<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle && sidebar && sidebarOverlay) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
            setTimeout(() => {
                sidebarOverlay.classList.toggle('opacity-0');
            }, 10);
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('opacity-0');
            setTimeout(() => {
                sidebarOverlay.classList.add('hidden');
            }, 300);
        });
    }

    // Profile dropdown toggle
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');

    if (profileToggle && profileDropdown) {
        profileToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            profileDropdown.classList.add('hidden');
        });
    }

    // Theme toggle functionality (using the better implementation from first file)
    const themeToggle = document.getElementById('themeToggle');
    const sidebarThemeToggle = document.getElementById('sidebarThemeToggle');
    const themeIcon = document.querySelector('.theme-icon');
    const html = document.documentElement;

    // Initialize theme toggles if they exist
    if (themeToggle || sidebarThemeToggle) {
        // Check for saved theme preference
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
            updateThemeIcons('dark');
        } else {
            html.classList.remove('dark');
            updateThemeIcons('light');
        }

        // Main theme toggle
        if (themeToggle) {
            themeToggle.addEventListener('click', toggleTheme);
        }

        // Sidebar theme toggle
        if (sidebarThemeToggle) {
            sidebarThemeToggle.addEventListener('click', toggleTheme);
        }

        function toggleTheme() {
            html.classList.toggle('dark');
            if (html.classList.contains('dark')) {
                updateThemeIcons('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                updateThemeIcons('light');
                localStorage.setItem('theme', 'light');
            }
        }

        function updateThemeIcons(theme) {
            const icons = document.querySelectorAll('.theme-icon');
            icons.forEach(icon => {
                if (theme === 'dark') {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                } else {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                }
            });
        }
    }

    // Balance toggle (show/hide)
    const balanceToggle = document.getElementById('balanceToggle');
    const balanceAmount = document.getElementById('balanceAmount');

    if (balanceToggle && balanceAmount) {
        let balanceVisible = true;
        const originalBalance = balanceAmount.innerHTML;

        balanceToggle.addEventListener('click', () => {
            balanceVisible = !balanceVisible;
            if (balanceVisible) {
                balanceAmount.innerHTML = originalBalance;
                balanceToggle.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                balanceAmount.innerHTML = 'KES ••••• <img src="https://flagcdn.com/ke.svg" class="w-6 h-4 ml-2 rounded-sm shadow">';
                balanceToggle.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
    }

    // Transaction modal handling
    const transactionModalElement = document.getElementById('transactionModal');
    const transactionDetails = document.getElementById('transactionDetails');

    if (transactionModalElement && transactionDetails) {
        // Initialize modal (assuming you're using a modal library like Flowbite)
        let transactionModal;
        if (typeof Modal !== 'undefined') {
            transactionModal = new Modal(transactionModalElement);
        }

        document.querySelectorAll('[data-modal-toggle="transactionModal"], [data-transaction-id]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const transactionId = this.getAttribute('data-transaction-id');
                if (transactionId) {
                    loadTransactionDetails(transactionId);
                }
            });
        });

        function loadTransactionDetails(transactionId) {
            // Show loading spinner
            transactionDetails.innerHTML = `
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-primary"></div>
                </div>
            `;
            
            // Show modal if using modal library
            if (transactionModal && transactionModal.show) {
                transactionModal.show();
            }
            
            // Simulate API call to fetch transaction details
            setTimeout(() => {
                const transaction = {
                    id: transactionId,
                    type: "Money Transfer",
                    amount: "1,500.00",
                    fee: "27.00",
                    total: "1,527.00",
                    recipient: "254712345678",
                    date: new Date().toLocaleString(),
                    status: "Completed",
                    reference: "NCJ8H2K9P0"
                };

                const html = `
                    <div class="receipt-container bg-white dark:bg-gray-800 p-6 rounded-lg">
                        <div class="receipt-header text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-primary rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-exchange-alt text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">M-PESA</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">${transaction.date}</p>
                        </div>
                        <div class="receipt-details space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Transaction Type:</span>
                                <span class="font-medium text-gray-900 dark:text-white">${transaction.type}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Amount:</span>
                                <span class="font-medium text-gray-900 dark:text-white">KES ${transaction.amount}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Transaction Fee:</span>
                                <span class="font-medium text-gray-900 dark:text-white">KES ${transaction.fee}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Total:</span>
                                <span class="font-medium text-gray-900 dark:text-white">KES ${transaction.total}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Recipient:</span>
                                <span class="font-medium text-gray-900 dark:text-white">${transaction.recipient}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Status:</span>
                                <span class="font-medium text-green-600 dark:text-green-400">${transaction.status}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Reference:</span>
                                <span class="font-medium text-gray-900 dark:text-white">${transaction.reference}</span>
                            </div>
                        </div>
                        <div class="receipt-amount text-center py-4 border-t border-b border-gray-200 dark:border-gray-600 mb-6">
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">KES ${transaction.amount}</p>
                        </div>
                        <div class="receipt-footer text-center text-gray-500 dark:text-gray-400">
                            <p>Thank you for using M-PESA</p>
                        </div>
                    </div>
                `;
                transactionDetails.innerHTML = html;
            }, 1000);
        }
    }

    // Form validation for Deriv deposit
    const crNumberInput = document.getElementById('crNumberdepo');
    const amountInput = document.getElementById('amountdepo');
    const depositButton = document.getElementById('depo');
    
    if (crNumberInput && amountInput && depositButton) {
        crNumberInput.addEventListener('input', validateDepositForm);
        amountInput.addEventListener('input', validateDepositForm);
        
        function validateDepositForm() {
            const crNumberValid = /^CR\d{7}$/.test(crNumberInput.value.toUpperCase());
            const amountValid = amountInput.value > 0;
            
            depositButton.disabled = !(crNumberValid && amountValid);
            
            // Update error messages
            const crError = document.querySelector('.error-message-cr');
            const amountError = document.querySelector('.error-message-amount');
            
            if (crError) {
                crError.textContent = crNumberValid ? '' : 'CR number must be in format CR1234567';
            }
            if (amountError) {
                amountError.textContent = amountValid ? '' : 'Amount must be greater than 0';
            }
        }
    }
    
    // Form validation for Deriv withdrawal
    const crNumberWithdrawInput = document.getElementById('crNumberWithdraw');
    const amountWithdrawInput = document.getElementById('amountWithdraw');
    const withdrawButton = document.getElementById('withdrawButton');
    
    if (crNumberWithdrawInput && amountWithdrawInput && withdrawButton) {
        crNumberWithdrawInput.addEventListener('input', validateWithdrawForm);
        amountWithdrawInput.addEventListener('input', validateWithdrawForm);
        
        function validateWithdrawForm() {
            const crNumberValid = /^CR\d{7}$/.test(crNumberWithdrawInput.value.toUpperCase());
            const amountValid = amountWithdrawInput.value > 0;
            
            withdrawButton.disabled = !(crNumberValid && amountValid);
            
            // Update error messages
            const crError = document.querySelector('.error-message-cr-withdraw');
            const amountError = document.querySelector('.error-message-amount-withdraw');
            
            if (crError) {
                crError.textContent = crNumberValid ? '' : 'CR number must be in format CR1234567';
            }
            if (amountError) {
                amountError.textContent = amountValid ? '' : 'Amount must be greater than 0';
            }
        }
    }

    // Print receipt function
    window.printReceipt = function() {
        const receiptContent = document.querySelector('.receipt-container');
        if (receiptContent) {
            const printableDiv = document.getElementById('printableReceipt') || document.createElement('div');
            printableDiv.innerHTML = receiptContent.outerHTML;
            
            // Use html2canvas if available, otherwise fallback to basic print
            if (typeof html2canvas !== 'undefined') {
                html2canvas(printableDiv).then(canvas => {
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write('<html><head><title>M-Pesa Receipt</title></head><body>');
                    printWindow.document.write('<img src="' + canvas.toDataURL() + '" style="max-width:100%;">');
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    
                    printWindow.onload = function() {
                        printWindow.print();
                    };
                });
            } else {
                // Fallback print method
                const printContents = receiptContent.innerHTML;
                const originalContents = document.body.innerHTML;
                
                document.body.innerHTML = `
                    <div class="p-4">
                        <h2 class="text-xl font-bold mb-4">Transaction Receipt</h2>
                        ${printContents}
                    </div>
                `;
                
                window.print();
                document.body.innerHTML = originalContents;
            }
        }
    };

    // Form submission handlers with loading states
    window.disableDerivDeposit = function() {
        const button = document.getElementById('deposit_button');
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        }
        return true;
    };

    window.disableWithdrawButton = function() {
        const button = document.getElementById('mpesa_withdraw');
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        }
        return true;
    };

    window.disableDepositButton = function() {
        const button = document.getElementById('depo');
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        }
        return true;
    };

    window.disableSubmitButtonWithdraw = function() {
        const button = document.getElementById('withdrawButton');
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        }
        return true;
    };

    // Notification system
    window.showNotification = function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white z-50 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 3000);
    };
    // Keepalive ping to server
    const APP_INSTANCE = '<?php echo APP_INSTANCE; ?>'; // Ensure this is defined in your PHP context
    setInterval(function() {
        fetch(APP_INSTANCE + 'keepalive', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                session_id: getSessionId() // Implement this function
            })
        });
    }, 300000); // Ping every 5 minutes
});
</script>