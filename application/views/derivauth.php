<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="STEPAKASH - Modern Money Transaction Platform">
    <meta name="author" content="">
    <title>StepaKash - Connect Deriv Account</title>
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
<body class="h-100">
    <div class="auth-wrapper">
        <div class="auth-content-container">
            <!-- Left Feature Panel -->
            <div class="auth-feature-panel">
                <div class="auth-feature-content">
                    <h2>Connect Your Deriv Account</h2>
                    <p>Authorize StepaKash to access your Deriv account to enable seamless transactions.</p>
                    <ul class="auth-feature-list">
                        <li>
                            <i class="fas fa-user-plus"></i>
                            <span>Secure OAuth connection</span>
                        </li>
                        <li>
                            <i class="fas fa-gift"></i>
                            <span>Only basic account information is accessed</span>
                        </li>
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <span>Your credentials are never stored</span>
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
                    
                    <div class="text-center">
                        <h4 class="auth-title mb-3">Connect Deriv Account</h4>
                        <p class="auth-subtitle mb-4">First step to create your StepaKash account</p>
                        
                        <div class="deriv-login-container">
                            <button class="btn btn-deriv w-100" id="derivSignupBtn">
                                <i class="fab fa-connectdevelop me-2"></i> Authorize Deriv
                            </button>
                            <p class="small mt-3">
                                By connecting, you authorize StepaKash to access your Deriv account information 
                                for transaction purposes.
                            </p>
                            
                            <div class="mt-4">
                                <a href="<?php echo base_url() ?>login" class="text-muted">Already have an account? Sign In</a>
                            </div>
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
            const derivSignupBtn = document.getElementById('derivSignupBtn');
            
            if (derivSignupBtn) {
                derivSignupBtn.addEventListener('click', function() {
                    initiateDerivOAuth();
                });
            }

            async function initiateDerivOAuth() {
                try {
                    // Change button to loading state
                    setButtonLoadingState('Authorizing...');
                    
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
                        resetButtonState();
                        showError('Failed to connect to Deriv: ' + (data.message || 'Unknown error'));
                    }
                    
                } catch (error) {
                    resetButtonState();
                    showError('Network error. Please try again.');
                    console.error('Deriv OAuth error:', error);
                }
            }

            function setButtonLoadingState(message) {
                const button = document.getElementById('derivSignupBtn');
                if (button) {
                    button.disabled = true;
                    button.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        ${message}
                    `;
                }
            }

            function resetButtonState() {
                const button = document.getElementById('derivSignupBtn');
                if (button) {
                    button.disabled = false;
                    button.innerHTML = `
                        <i class="fab fa-connectdevelop me-2"></i> Authorize Deriv
                    `;
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
                
                setTimeout(() => {
                    errorAlert.remove();
                }, 5000);
            }
        });
    </script>
</body>
</html>