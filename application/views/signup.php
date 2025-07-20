<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="STEPAKASH - Modern Money Transaction Platform">
    <meta name="author" content="">
    <title>StepaKash - Complete Registration</title>
    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?php echo base_url() ?>manifest.json" />
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?php echo base_url() ?>assets/img/stepak_180.png" sizes="180x180">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/stepak_32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/stepak_16.png" sizes="16x16" type="image/png">
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
                    <h2>Complete Your Registration</h2>
                    <p>Just a few more details to create your StepaKash account and start transacting.</p>
                    <ul class="auth-feature-list">
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <span>Deriv account connected</span>
                        </li>
                        <li>
                            <i class="fas fa-user-plus"></i>
                            <span>Create your StepaKash profile</span>
                        </li>
                        <li>
                            <i class="fas fa-mobile-alt"></i>
                            <span>Link your phone number for M-Pesa</span>
                        </li>
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure password protection</span>
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
                        <a href="<?php echo base_url() ?>login" class="form-tab">Login</a>
                        <div class="form-tab active">Create Account</div>
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
                    
                    <!-- Registration Form -->
                    <form action="<?php echo base_url().'Auth/CreateAccount' ?>" method="POST" id="registerFormSubmit">
                        <input type="hidden" name="deriv_account" id="derivAccount" value="1">
                        <input type="hidden" name="deriv_token" id="derivToken" value="<?php echo isset($deriv_data['deriv_token']) ? $deriv_data['deriv_token'] : '' ?>">
                        <input type="hidden" name="deriv_email" id="derivEmail" value="<?php echo isset($deriv_data['deriv_email']) ? $deriv_data['deriv_email'] : '' ?>">
                        <input type="hidden" name="account_number" id="account_number" value="<?php echo isset($deriv_data['deriv_account_number']) ? $deriv_data['deriv_account_number'] : '' ?>">
                        <input type="hidden" name="deriv_login_id" id="derivLoginId" value="<?php echo isset($deriv_data['deriv_login_id']) ? $deriv_data['deriv_login_id'] : '' ?>">
                        <input type="hidden" name="deriv_account_number" id="derivAccountNumber" value="<?php echo isset($deriv_data['deriv_account_number']) ? $deriv_data['deriv_account_number'] : '' ?>">

                
            
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" name="fullname" id="fullname" 
                                   placeholder="Full Name" required
                                   value="<?php echo isset($deriv_data['fullname']) ? $deriv_data['fullname'] : '' ?>">
                            <label for="fullname">Full Name</label>
                        </div>
                        
                        <!--<div class="form-floating mb-4">
                            <input type="email" class="form-control" name="email" id="email" 
                                   placeholder="Email" required
                                   value="<?php echo isset($deriv_data['deriv_email']) ? $deriv_data['deriv_email'] : '' ?>">
                            <label for="email">Email Address</label>
                        </div>-->
                        
                        <div class="form-floating mb-4 error-container">
                            <input type="tel" class="form-control" name="phone" id="phone" placeholder="712345678" autocomplete="off" required>
                            <label for="phone">Phone Number (e.g. 712345678)</label>
                            <div id="phoneError" class="error-message"></div>
                        </div>

                        <div class="form-floating mb-4 error-container">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" minlength="4" required>
                            <label for="password">Password</label>
                            <div id="passwordError" class="error-message"></div>
                        </div>

                        <div class="form-floating mb-4 error-container">
                            <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password" autocomplete="off" minlength="4" required>
                            <label for="confirmpassword">Confirm Password</label>
                            <div id="confirmPasswordError" class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                <label class="form-check-label small" for="termsCheck">
                                    I agree to the <a href="terms" class="text-decoration-none">Terms and Conditions</a>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="registerBtn">
                                <span class="spinner"></span>
                                <span class="btn-text">Complete Registration</span>
                            </button>
                        </div>
                    </form>
                    
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation functions
            function validatePassword() {
                const passwordInput = document.getElementById('password');
                const passwordValue = passwordInput.value;
                const passwordError = document.getElementById('passwordError');
                
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

            function validateConfirmPassword() {
                const passwordInput = document.getElementById('password');
                const confirmPasswordInput = document.getElementById('confirmpassword');
                const confirmPasswordValue = confirmPasswordInput.value;
                const confirmPasswordError = document.getElementById('confirmPasswordError');
                
                if (passwordInput.value !== confirmPasswordValue) {
                    confirmPasswordError.textContent = 'Passwords do not match';
                    confirmPasswordInput.classList.add('is-invalid');
                    confirmPasswordInput.classList.remove('is-valid');
                    return false;
                } else {
                    confirmPasswordError.textContent = '';
                    confirmPasswordInput.classList.remove('is-invalid');
                    confirmPasswordInput.classList.add('is-valid');
                    return true;
                }
            }

            // Register form validation
            const registerForm = document.querySelector('#registerFormSubmit');
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    const phoneInput = document.getElementById('phone');
                    const passwordInput = document.getElementById('password');
                    const confirmPasswordInput = document.getElementById('confirmpassword');
                    const phoneError = document.getElementById('phoneError');
                    const passwordError = document.getElementById('passwordError');
                    const confirmPasswordError = document.getElementById('confirmPasswordError');
                    const registerBtn = document.getElementById('registerBtn');
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
                    
                    // Validate confirm password
                    if (passwordInput.value !== confirmPasswordInput.value) {
                        confirmPasswordError.textContent = 'Passwords do not match';
                        confirmPasswordInput.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        confirmPasswordError.textContent = '';
                        confirmPasswordInput.classList.remove('is-invalid');
                    }
                    
                    // Validate terms checkbox
                    if (!document.getElementById('termsCheck').checked) {
                        isValid = false;
                        showError('Please agree to the Terms and Conditions');
                    }
                    
                    if (isValid) {
                        // Show loading state
                        registerBtn.classList.add('loading');
                        registerBtn.querySelector('.btn-text').textContent = 'Registering...';
                    } else {
                        e.preventDefault();
                    }
                });
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
                
                setTimeout(() => {
                    errorAlert.remove();
                }, 5000);
            }
        });
    </script>
</body>
</html>