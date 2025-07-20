<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="StepaKash - Deriv M-PESA Transfers">
    <meta name="author" content="">
    <title>StepaKash</title>
    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?php echo base_url() ?>manifest.json" />
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?php echo base_url() ?>assets/img/header-icon.png" sizes="180x180">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/header-icon2.png" sizes="32x32" type="image/png">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/header-icon2.png" sizes="16x16" type="image/png">
    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="<?php echo base_url() ?>assets/css/login.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/css/login2.css" rel="stylesheet">
</head>
<body class="h-100" data-page="signin">
    <div class="auth-wrapper">
        <div class="auth-content-container">
            <!-- Left Feature Panel -->
            <div class="auth-feature-panel">
                <div class="auth-feature-content">
                    <h2>Welcome Back to StepaKash</h2>
                    <p>Log in to manage your Deriv payments with M-Pesa — deposit or withdraw seamlessly.</p>
                    <ul class="auth-feature-list">
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure and encrypted M-Pesa transactions</span>
                        </li>
                        <li>
                            <i class="fas fa-bolt"></i>
                            <span>Instant deposits and withdrawals to/from Deriv via M-Pesa</span>
                        </li>
                        <li>
                            <i class="fas fa-chart-line"></i>
                            <span>Track and manage your Deriv-M-Pesa transaction history</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Right Form Container -->
            <div class="auth-form-container">
                <div class="auth-container">
                    <div class="auth-logo">
                        <img src="<?php echo base_url() ?>assets/img/login-icon2.png" alt="STEPAKASH Logo" />
                    </div>
                    
                    <div class="form-tab-switcher">
                        <div class="form-tab active">Login</div>
                        <a href="<?php echo base_url() ?>derivauth" class="form-tab">Create Account</a>
                    </div>
                    
                    <?php
                    $flash = $this->session->flashdata('msg');
                    if($flash) {
                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                '. $flash.'
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                        $this->session->unset_userdata('msg');
                    }
                    ?>
                    
                    <!-- Login Form -->
                    <div class="form-panel active" id="loginForm">
                        <form action="<?php echo base_url() ?>Auth/Login" method="POST" id="loginFormSubmit">
                            <div class="form-floating mb-4 error-container">
                                <input type="tel" name="phone" class="form-control" id="loginPhone" placeholder="Phone" autocomplete="off" required>
                                <label for="loginPhone">Phone Number (e.g. 712345678)</label>
                                <div id="loginPhoneError" class="error-message"></div>
                            </div>
                            <div class="form-floating mb-4 error-container">
                                <input type="password" class="form-control" name="password" id="loginpassword" placeholder="Password" autocomplete="off" minlength="4" required>
                                <label for="loginpassword">Password</label>
                                <div id="loginPasswordError" class="error-message"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="rememberMe">
                                    <label class="form-check-label small" for="rememberMe">Remember me</label>
                                </div>
                                <a href="<?php echo base_url() ?>forgotpassword" class="small text-decoration-none">Forgot password?</a>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="loginBtn">
                                    <span class="spinner"></span>
                                    <span class="btn-text">Login</span>
                                </button>
                            </div>
                        </form>

                        <!-- Deriv Login Option -->
                        <div class="divider">
                            <span class="divider-text">OR</span>
                        </div>
                        <div class="deriv-login-container">
                            <!-- <button class="btn btn-deriv w-100 mb-2" id="derivRegisterBtn">
                                <i class="fab fa-connectdevelop me-2"></i> Sign up with Deriv
                            </button> -->
                            <a href="" target="_blank" class="btn btn-deriv w-100 mb-2"  id="derivSignupBtn">
                                <i class="fas fa-external-link-alt me-2"></i> Sign up with Deriv
                            </a>
                            <p class="small mt-2">By connecting your Deriv account, you agree to our <a href="<?php echo base_url() ?>terms" class="text-decoration-none">Terms</a> and <a href="<?php echo base_url() ?>privacy" class="text-decoration-none">Privacy Policy</a></p>
                        </div>
                    </div>
                    
                    <div class="auth-footer">
                        © <?php echo date('Y'); ?> StepaKash. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/pwa-services.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation functions
            function validatePassword() {
                const passwordInput = document.getElementById('loginpassword');
                const passwordValue = passwordInput.value;
                const passwordError = document.getElementById('loginPasswordError');
                
                if (passwordValue.length < 4) {
                    passwordError.textContent = 'Password must be at least 4 characters long';
                    passwordInput.classList.add('is-invalid');
                    passwordInput.classList.remove('is-valid');
                    return false;
                } else {
                    passwordError.textContent = '';
                    passwordInput.classList.remove('is-invalid');
                    passwordInput.classList.add('is-valid');
                    return true;
                }
            }

            // Login form validation
            document.querySelector('#loginFormSubmit').addEventListener('submit', function(e) {
                const phoneInput = document.getElementById('loginPhone');
                const passwordInput = document.getElementById('loginpassword');
                const phoneError = document.getElementById('loginPhoneError');
                const passwordError = document.getElementById('loginPasswordError');
                const loginBtn = document.getElementById('loginBtn');
                let isValid = true;
                
                // Validate phone
                if (!phoneInput.value.trim()) {
                    phoneError.textContent = 'Phone number is required';
                    phoneInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    phoneError.textContent = '';
                    phoneInput.classList.remove('is-invalid');
                }
                
                // Validate password
                if (!passwordInput.value.trim()) {
                    passwordError.textContent = 'Password is required';
                    passwordInput.classList.add('is-invalid');
                    isValid = false;
                } else if (passwordInput.value.length < 4) {
                    passwordError.textContent = 'Password must be at least 4 characters';
                    passwordInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    passwordError.textContent = '';
                    passwordInput.classList.remove('is-invalid');
                }
                
                if (isValid) {
                    // Show loading state
                    loginBtn.classList.add('loading');
                    loginBtn.querySelector('.btn-text').textContent = 'Authenticating...';
                } else {
                    e.preventDefault();
                }
            });

            // Reset button states when form submission is complete
            const loginBtn = document.getElementById('loginBtn');
            
            if (loginBtn) {
                loginBtn.classList.remove('loading');
                loginBtn.querySelector('.btn-text').textContent = 'Sign In';
            }

            // Deriv OAuth Integration
            document.getElementById('derivLoginBtn').addEventListener('click', function() {
                initiateDerivOAuth('login');
            });

            document.getElementById('derivRegisterBtn').addEventListener('click', function() {
                initiateDerivOAuth('register');
            });

            // UI Helper Functions
            function showLoadingState(message) {
                const loadingOverlay = document.createElement('div');
                loadingOverlay.id = 'loadingOverlay';
                loadingOverlay.className = 'loading-overlay';
                loadingOverlay.innerHTML = `
                    <div class="loading-content">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">${message}</p>
                    </div>
                `;
                document.body.appendChild(loadingOverlay);
            }

            function hideLoadingState() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) {
                    overlay.remove();
                }
            }

            function showError(message) {
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger alert-dismissible fade show';
                errorAlert.style.position = 'fixed';
                errorAlert.style.top = '20px';
                errorAlert.style.right = '20px';
                errorAlert.style.zIndex = '9999';
                errorAlert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(errorAlert);
                
                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    errorAlert.remove();
                }, 5000);
            }

            function showSuccess(message) {
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show';
                successAlert.style.position = 'fixed';
                successAlert.style.top = '20px';
                successAlert.style.right = '20px';
                successAlert.style.zIndex = '9999';
                successAlert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(successAlert);
                
                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    successAlert.remove();
                }, 5000);
            }

            // Deriv OAuth Functions
            async function initiateDerivOAuth(context = 'login') {
                try {
                    showLoadingState('Connecting to Deriv...');
                    const response = await fetch('<?php echo base_url() ?>Auth/DerivOAuth', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        // Redirect to Deriv OAuth
                        window.location.href = data.oauth_url;
                    } else {
                        hideLoadingState();
                        showError('Failed to connect to Deriv: ' + (data.message || 'Unknown error'));
                    }
                    
                } catch (error) {
                    hideLoadingState();
                    showError('Network error. Please try again.');
                    console.error('Deriv OAuth error:', error);
                }
            }

            //Deriv Oauth register
            async function initiateDerivOAuth(context = 'register') {
                try {
                    showLoadingState('Redirecting to Deriv...');
                    
                    const response = await fetch('<?php echo base_url() ?>Auth/DerivOAuth', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        // Redirect to Deriv OAuth
                        window.location.href = data.oauth_url;
                    } else {
                        hideLoadingState();
                        showError('Failed to connect to Deriv: ' + (data.message || 'Unknown error'));
                    }
                    
                } catch (error) {
                    hideLoadingState();
                    showError('Network error. Please try again.');
                    console.error('Deriv OAuth error:', error);
                }
            }

        });



    </script>
    <!-- Required jquery and libraries -->
    <script src="<?php echo base_url() ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/popper.min.js"></script>
    <script src="<?php echo base_url() ?>assets/vendor/bootstrap-5/js/bootstrap.bundle.min.js"></script>
    <!-- Customized jquery file  -->
    <script src="<?php echo base_url() ?>assets/js/main.js"></script>
    <script src="<?php echo base_url() ?>assets/js/color-scheme.js"></script>
    <!-- PWA app service registration and works -->
    <script src="<?php echo base_url() ?>assets/js/pwa-services.js"></script>
    <!-- page level custom script -->
    <script src="<?php echo base_url() ?>assets/js/app.js"></script>
</body>
</html>