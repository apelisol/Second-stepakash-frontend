<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="StepaKash - OTP Verification">
    <meta name="author" content="">
    <title>OTP Verification | StepaKash</title>
    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?php echo base_url() ?>manifest.json" />
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?php echo base_url() ?>assets/img/header-icon.png" sizes="180x180">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/header-icon.png" sizes="32x32" type="image/png">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/header-icon.png" sizes="16x16" type="image/png">
    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="<?php echo base_url() ?>assets/css/otp.css" rel="stylesheet">
    
</head>
<body class="d-flex flex-column h-100" data-page="otp-verification">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <div class="auth-container mb-4">
                    <div class="auth-logo">
                        <img src="<?php echo base_url() ?>assets/img/login-icon2.png" alt="STEPAKASH Logo" />
                    </div>
                    <h2 class="auth-title">OTP Verification</h2>
                    <p class="auth-subtitle">Enter the verification code sent to your phone</p>

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

                    <form action="<?php echo base_url() ?>Auth/ConfirmOtp" method="POST" id="otpForm">
                        <div class="form-floating mb-4 error-container">
                            <input type="number" class="form-control" name="otp" id="otp" placeholder="123456" autocomplete="off" required>
                            <label for="otp">Verification Code</label>
                            <div id="otpError" class="error-message"></div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="verifyBtn">
                                <span class="spinner"></span>
                                <span class="btn-text">Verify Code</span>
                            </button>
                        </div>
                    </form>

                    <div class="back-to-login">
                        <a href="<?php echo base_url() ?>login">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form submission handler
        document.querySelector('#otpForm').addEventListener('submit', function(e) {
            const otpInput = document.getElementById('otp');
            const otpError = document.getElementById('otpError');
            const verifyBtn = document.getElementById('verifyBtn');
            let isValid = true;
            
            // Validate OTP
            if (!otpInput.value.trim()) {
                otpError.textContent = 'Verification code is required';
                otpInput.classList.add('is-invalid');
                isValid = false;
            } else if (otpInput.value.trim().length < 4) {
                otpError.textContent = 'Code must be at least 4 digits';
                otpInput.classList.add('is-invalid');
                isValid = false;
            } else {
                otpError.textContent = '';
                otpInput.classList.remove('is-invalid');
            }
            
            if (isValid) {
                // Show loading state
                verifyBtn.classList.add('loading');
                verifyBtn.querySelector('.btn-text').textContent = 'Verifying...';
            } else {
                e.preventDefault();
            }
        });

        // Reset button state when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const verifyBtn = document.getElementById('verifyBtn');
            if (verifyBtn) {
                verifyBtn.classList.remove('loading');
                verifyBtn.querySelector('.btn-text').textContent = 'Verify Code';
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