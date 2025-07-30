<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deriv Balance Test - Stepakash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#059669',
                        secondary: '#065f46',
                        accent: '#b59a3e'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Deriv Balance Test</h1>
                    <p class="text-gray-600">Test and monitor Deriv account balances</p>
                </div>
                <img src="assets/img/deriv-logo.png" alt="Deriv" class="h-12">
            </div>
        </div>

        <!-- Connection Status -->
        <div id="connectionStatus" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
            <div class="flex items-center">
                <i class="fas fa-circle-notch animate-spin text-yellow-400 mr-2"></i>
                <span class="text-yellow-700">Initializing connection...</span>
            </div>
        </div>

        <!-- Balance Cards Grid -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <!-- Real Account Balance -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Real Account Balance</h3>
                    <button id="refreshReal" class="p-2 text-gray-500 hover:text-primary transition-colors">
                        <i class="fas fa-refresh"></i>
                    </button>
                </div>
                <div id="realBalance" class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Balance:</span>
                        <span class="text-2xl font-bold text-green-600">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Currency:</span>
                        <span class="text-sm text-gray-800">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Account:</span>
                        <span class="text-sm text-gray-800">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">KES Equivalent:</span>
                        <span class="text-lg font-semibold text-blue-600">--</span>
                    </div>
                </div>
            </div>

            <!-- Demo Account Balance -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Demo Account Balance</h3>
                    <button id="refreshDemo" class="p-2 text-gray-500 hover:text-primary transition-colors">
                        <i class="fas fa-refresh"></i>
                    </button>
                </div>
                <div id="demoBalance" class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Balance:</span>
                        <span class="text-2xl font-bold text-purple-600">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Currency:</span>
                        <span class="text-sm text-gray-800">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Account:</span>
                        <span class="text-sm text-gray-800">--</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Controls -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Test Controls</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Token</label>
                    <input type="text" id="apiToken" placeholder="Enter API token" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                    <select id="accountType" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="current">Current Account</option>
                        <option value="all">All Accounts</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-4 mt-4">
                <button id="connectBtn" class="bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-plug mr-2"></i>Connect
                </button>
                <button id="disconnectBtn" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition-colors" disabled>
                    <i class="fas fa-times mr-2"></i>Disconnect
                </button>
                <button id="testBalanceBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors" disabled>
                    <i class="fas fa-test-tube mr-2"></i>Test Balance
                </button>
            </div>
        </div>

        <!-- Live Updates -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Live Updates</h3>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Auto-refresh:</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="autoRefresh" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/25 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                </div>
            </div>
            <div id="updateLog" class="bg-gray-50 rounded-lg p-4 h-32 overflow-y-auto text-sm">
                <p class="text-gray-500">Balance updates will appear here...</p>
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Information</h3>
            <div id="accountInfo" class="grid md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Login ID:</span>
                        <span id="loginId" class="font-medium">--</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span id="email" class="font-medium">--</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Full Name:</span>
                        <span id="fullName" class="font-medium">--</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Country:</span>
                        <span id="country" class="font-medium">--</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Landing Company:</span>
                        <span id="landingCompany" class="font-medium">--</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Is Virtual:</span>
                        <span id="isVirtual" class="font-medium">--</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        class DerivBalanceTester {
            constructor() {
                this.ws = null;
                this.isConnected = false;
                this.autoRefreshInterval = null;
                this.rates = {
                    deriv_buy: 130, // Default rate, should be fetched from your system
                    deriv_sell: 128
                };
                this.initEventListeners();
                this.fetchRates();
            }

            initEventListeners() {
                document.getElementById('connectBtn').addEventListener('click', () => this.connect());
                document.getElementById('disconnectBtn').addEventListener('click', () => this.disconnect());
                document.getElementById('testBalanceBtn').addEventListener('click', () => this.testBalance());
                document.getElementById('refreshReal').addEventListener('click', () => this.refreshBalance('real'));
                document.getElementById('refreshDemo').addEventListener('click', () => this.refreshBalance('demo'));
                document.getElementById('autoRefresh').addEventListener('change', (e) => this.toggleAutoRefresh(e.target.checked));
            }

            async fetchRates() {
                try {
                    // This should call your backend to get current rates
                    const response = await fetch('/Main/get_rates'); // Adjust URL as needed
                    if (response.ok) {
                        const data = await response.json();
                        this.rates = data;
                    }
                } catch (error) {
                    console.log('Using default rates');
                }
            }

            connect() {
                const token = document.getElementById('apiToken').value;
                if (!token) {
                    this.showError('Please enter an API token');
                    return;
                }

                this.updateConnectionStatus('Connecting...', 'yellow');

                const appId = 76420;
                const wsUrl = `wss://ws.derivws.com/websockets/v3?app_id=${appId}`;

                this.ws = new WebSocket(wsUrl);

                this.ws.onopen = () => {
                    this.authorize(token);
                };

                this.ws.onmessage = (event) => {
                    this.handleMessage(JSON.parse(event.data));
                };

                this.ws.onclose = () => {
                    this.isConnected = false;
                    this.updateConnectionStatus('Disconnected', 'red');
                    this.toggleButtons(false);
                };

                this.ws.onerror = (error) => {
                    this.showError('WebSocket connection failed');
                    this.updateConnectionStatus('Connection failed', 'red');
                };
            }

            authorize(token) {
                this.send({
                    "authorize": token
                });
            }

            send(data) {
                if (this.ws && this.ws.readyState === WebSocket.OPEN) {
                    this.ws.send(JSON.stringify(data));
                }
            }

            handleMessage(data) {
                if (data.error) {
                    this.showError(`API Error: ${data.error.message}`);
                    return;
                }

                if (data.authorize) {
                    this.handleAuthorization(data.authorize);
                } else if (data.balance) {
                    this.handleBalance(data.balance);
                }
            }

            handleAuthorization(authData) {
                this.isConnected = true;
                this.updateConnectionStatus('Connected & Authorized', 'green');
                this.toggleButtons(true);

                // Update account info
                document.getElementById('loginId').textContent = authData.loginid || '--';
                document.getElementById('email').textContent = authData.email || '--';
                document.getElementById('fullName').textContent = authData.fullname || '--';
                document.getElementById('country').textContent = authData.country || '--';
                document.getElementById('landingCompany').textContent = authData.landing_company_fullname || '--';
                document.getElementById('isVirtual').textContent = authData.is_virtual ? 'Yes' : 'No';

                // Get initial balance
                this.getBalance();
            }

            handleBalance(balanceData) {
                const isVirtual = balanceData.loginid && balanceData.loginid.startsWith('VRT');
                const targetDiv = isVirtual ? 'demoBalance' : 'realBalance';

                const balanceContainer = document.getElementById(targetDiv);
                const balanceSpans = balanceContainer.querySelectorAll('span');

                // Update balance display
                balanceSpans[1].textContent = `${balanceData.currency} ${Number(balanceData.balance).toLocaleString()}`;
                balanceSpans[3].textContent = balanceData.currency;
                balanceSpans[5].textContent = balanceData.loginid;

                // Calculate KES equivalent for real accounts
                if (!isVirtual && balanceSpans[7]) {
                    const kesEquivalent = balanceData.balance * this.rates.deriv_sell;
                    balanceSpans[7].textContent = `KES ${kesEquivalent.toLocaleString()}`;
                }

                // Log update
                this.logUpdate(`Balance updated: ${balanceData.loginid} - ${balanceData.currency} ${balanceData.balance}`);
            }

            getBalance(account = 'current') {
                this.send({
                    "balance": 1,
                    "account": account,
                    "subscribe": 0
                });
            }

            testBalance() {
                const accountType = document.getElementById('accountType').value;
                this.getBalance(accountType);
            }

            refreshBalance(type) {
                if (type === 'real') {
                    // Get balance for current real account
                    this.getBalance('current');
                } else {
                    // Get balance for demo account if available
                    this.getBalance('all');
                }
            }

            disconnect() {
                if (this.ws) {
                    this.ws.close();
                }
                this.toggleAutoRefresh(false);
                document.getElementById('autoRefresh').checked = false;
            }

            toggleAutoRefresh(enabled) {
                if (enabled && this.isConnected) {
                    this.autoRefreshInterval = setInterval(() => {
                        this.getBalance();
                    }, 30000); // Refresh every 30 seconds
                    this.logUpdate('Auto-refresh enabled (30s interval)');
                } else {
                    if (this.autoRefreshInterval) {
                        clearInterval(this.autoRefreshInterval);
                        this.autoRefreshInterval = null;
                    }
                    this.logUpdate('Auto-refresh disabled');
                }
            }

            updateConnectionStatus(message, color) {
                const statusDiv = document.getElementById('connectionStatus');
                const icons = {
                    yellow: 'fa-circle-notch animate-spin',
                    green: 'fa-check-circle',
                    red: 'fa-times-circle'
                };

                const colors = {
                    yellow: 'bg-yellow-50 border-yellow-400 text-yellow-700',
                    green: 'bg-green-50 border-green-400 text-green-700',
                    red: 'bg-red-50 border-red-400 text-red-700'
                };

                statusDiv.className = `border-l-4 p-4 mb-6 rounded ${colors[color]}`;
                statusDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${icons[color]} mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;
            }

            toggleButtons(connected) {
                document.getElementById('connectBtn').disabled = connected;
                document.getElementById('disconnectBtn').disabled = !connected;
                document.getElementById('testBalanceBtn').disabled = !connected;
            }

            showError(message) {
                this.logUpdate(`ERROR: ${message}`, 'error');
            }

            logUpdate(message, type = 'info') {
                const log = document.getElementById('updateLog');
                const timestamp = new Date().toLocaleTimeString();
                const colors = {
                    info: 'text-blue-600',
                    error: 'text-red-600',
                    success: 'text-green-600'
                };

                const logEntry = document.createElement('div');
                logEntry.className = `${colors[type]} mb-1`;
                logEntry.innerHTML = `<span class="text-gray-500">[${timestamp}]</span> ${message}`;

                log.appendChild(logEntry);
                log.scrollTop = log.scrollHeight;
            }
        }

        // Initialize the tester when page loads
        document.addEventListener('DOMContentLoaded', () => {
            new DerivBalanceTester();
        });
    </script>
</body>

</html>