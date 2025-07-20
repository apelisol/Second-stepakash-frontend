<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="STEPAKASH - Forgot Password">
    <meta name="author" content="">
    <title>Forgot Password | StepaKash</title>
    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?php echo base_url() ?>manifest.json" />
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?php echo base_url() ?>assets/img/login-icon2.png" sizes="180x180">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/login-icon2.png" sizes="32x32" type="image/png">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/login-icon2.png" sizes="16x16" type="image/png">
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
    <link href="<?php echo base_url() ?>assets/css/forgot-psd.css" rel="stylesheet">
</head>
<body class="h-100" data-page="forgot-password">
    <div class="auth-wrapper">
        <div class="auth-content-container">
            <!-- Left Feature Panel -->
            <div class="auth-feature-panel">
                <div class="auth-feature-content">
                    <h2>Reset Your Password</h2>
                    <p>Secure and quick password recovery for your StepaKash account.</p>
                    <ul class="auth-feature-list">
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure password reset process</span>
                        </li>
                        <li>
                            <i class="fas fa-mobile-alt"></i>
                            <span>SMS verification for security</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Quick and easy recovery</span>
                        </li>
                        <li>
                            <i class="fas fa-lock"></i>
                            <span>Keep your account protected</span>
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
                    
                    <div class="auth-header">
                        <h2 class="auth-title">Forgot Password?</h2>
                        <p class="auth-subtitle">Enter your phone number to reset your password</p>
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

                    <!-- Forgot Password Form -->
                    <form action="<?php echo base_url() ?>Auth/sendotp" method="POST" id="forgotPasswordForm">
                        <div class="form-floating mb-4 error-container">
                            <input type="tel" class="form-control" name="phone" id="phone" placeholder="712345678" autocomplete="off" required>
                            <label for="phone">Phone Number (e.g. 712345678)</label>
                            <div id="phoneError" class="error-message"></div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="resetBtn">
                                <span class="spinner"></span>
                                <span class="btn-text">Send Reset Code</span>
                            </button>
                        </div>
                    </form>

                    <div class="auth-links">
                        <div class="back-to-login">
                            <a href="<?php echo base_url() ?>login" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i>Back to Login
                            </a>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form submission handler
            document.querySelector('#forgotPasswordForm').addEventListener('submit', function(e) {
                const phoneInput = document.getElementById('phone');
                const phoneError = document.getElementById('phoneError');
                const resetBtn = document.getElementById('resetBtn');
                let isValid = true;
                
                // Validate phone
                if (!phoneInput.value.trim()) {
                    phoneError.textContent = 'Phone number is required';
                    phoneInput.classList.add('is-invalid');
                    isValid = false;
                } else if (phoneInput.value.trim().length < 9) {
                    phoneError.textContent = 'Please enter a valid phone number';
                    phoneInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    phoneError.textContent = '';
                    phoneInput.classList.remove('is-invalid');
                    phoneInput.classList.add('is-valid');
                }
                
                if (isValid) {
                    // Show loading state
                    resetBtn.classList.add('loading');
                    resetBtn.querySelector('.btn-text').textContent = 'Requesting Code...';
                    resetBtn.disabled = true;
                } else {
                    e.preventDefault();
                }
            });

            // Reset button state when page loads
            const resetBtn = document.getElementById('resetBtn');
            if (resetBtn) {
                resetBtn.classList.remove('loading');
                resetBtn.querySelector('.btn-text').textContent = 'Send Reset Code';
                resetBtn.disabled = false;
            }

            // Add input validation on typing
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function() {
                const phoneError = document.getElementById('phoneError');
                const value = this.value.trim();
                
                if (value.length === 0) {
                    phoneError.textContent = '';
                    this.classList.remove('is-invalid', 'is-valid');
                } else if (value.length < 9) {
                    phoneError.textContent = 'Phone number should be at least 9 digits';
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                } else {
                    phoneError.textContent = '';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            // Phone number formatting (optional)
            phoneInput.addEventListener('input', function(e) {
                // Remove any non-numeric characters
                let value = e.target.value.replace(/\D/g, '');
                
                // Limit to reasonable phone number length
                if (value.length > 12) {
                    value = value.slice(0, 12);
                }
                
                e.target.value = value;
            });
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