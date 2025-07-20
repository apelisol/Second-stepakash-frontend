<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="STEPAKASH - Connect Your Deriv Account">
    <meta name="author" content="">
    <title>Connect Deriv Account - STEPAKASH</title>
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
<body class="h-100" data-page="deriv-auth">
    <div class="auth-wrapper">
        <div class="auth-content-container">
            <!-- Left Feature Panel -->
            <div class="auth-feature-panel">
                <div class="auth-feature-content">
                    <h2>Connect Your Deriv Account</h2>
                    <p>Link your Deriv trading account with STEPAKASH to enable seamless M-Pesa deposits and withdrawals.</p>
                    <ul class="auth-feature-list">
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure connection using OAuth authentication</span>
                        </li>
                        <li>
                            <i class="fas fa-sync-alt"></i>
                            <span>Real-time synchronization with your Deriv balance</span>
                        </li>
                        <li>
                            <i class="fas fa-mobile-alt"></i>
                            <span>Instant M-Pesa deposits and withdrawals</span>
                        </li>
                        <li>
                            <i class="fas fa-eye"></i>
                            <span>We only access necessary account information</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Right Authorization Container -->
            <div class="auth-form-container">
                <div class="auth-container">
                    <div class="auth-logo">
                        <img src="<?php echo base_url() ?>assets/img/login-icon.png" alt="STEPAKASH Logo" />
                    </div>
                    
                    <div class="text-center mb-4">
                        <h3 class="auth-title">Authorize Deriv Connection</h3>
                        <p class="auth-subtitle">Connect your Deriv account to continue with registration</p>
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
                    
                    <!-- Authorization Step -->
                    <div class="deriv-auth-panel">
                        <div class="auth-step-indicator mb-4">
                            <div class="step active">
                                <span class="step-number">1</span>
                                <span class="step-text">Connect Deriv</span>
                            </div>
                            <div class="step-line"></div>
                            <div class="step">
                                <span class="step-number">2</span>
                                <span class="step-text">Complete Registration</span>
                            </div>
                        </div>
                        
                        <div class="deriv-connect-info mb-4">
                            <div class="info-card">
                                <h6><i class="fas fa-info-circle me-2"></i>What happens next?</h6>
                                <ul class="small mb-0">
                                    <li>You'll be redirected to Deriv's secure login page</li>
                                    <li>Login with your existing Deriv credentials</li>
                                    <li>Authorize STEPAKASH to access your account</li>
                                    <li>Return here to complete your STEPAKASH registration</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-3">
                            <button class="btn btn-deriv btn-lg" id="derivAuthBtn">
                                <i class="fab fa-connectdevelop me-2"></i>
                                <span class="btn-text">Connect Deriv Account</span>
                                <span class="spinner" style="display: none;"></span>
                            </button>
                            
                            <div class="text-center">
                                <small class="text-muted">
                                    Don't have a Deriv account? 
                                    <a href="https://deriv.com" target="_blank" class="text-decoration-none">
                                        Create one here <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                </small>
                            </div>
                        </div>
                        
                        <div class="security-notice mt-4">
                            <div class="alert alert-info d-flex align-items-start">
                                <i class="fas fa-lock me-2 mt-1"></i>
                                <div class="small">
                                    <strong>Security Notice:</strong> We use industry-standard OAuth 2.0 authentication. 
                                    STEPAKASH will never see your Deriv password and only accesses the minimum 
                                    required information for transactions.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="auth-footer">
                        <div class="text-center mb-3">
                            <a href="<?php echo base_url() ?>login" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i> Back to Sign In
                            </a>
                        </div>
                        © <?php echo date('Y'); ?> STEPAKASH. All rights reserved.
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
            const derivAuthBtn = document.getElementById('derivAuthBtn');
            
            derivAuthBtn.addEventListener('click', function() {
                initiateDerivOAuth();
            });
            
            // Check if returning from Deriv OAuth
            checkForDerivAuthSuccess();
            
            async function initiateDerivOAuth() {
                const btnText = derivAuthBtn.querySelector('.btn-text');
                const spinner = derivAuthBtn.querySelector('.spinner');
                
                try {
                    // Show loading state
                    btnText.textContent = 'Connecting...';
                    spinner.style.display = 'inline-block';
                    derivAuthBtn.disabled = true;
                    
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
                        throw new Error('Failed to connect to Deriv: ' + (data.message || 'Unknown error'));
                    }
                    
                } catch (error) {
                    showError('Network error. Please try again.');
                    console.error('Deriv OAuth error:', error);
                } finally {
                    // Reset button state
                    btnText.textContent = 'Connect Deriv Account';
                    spinner.style.display = 'none';
                    derivAuthBtn.disabled = false;
                }
            }
            
            async function checkForDerivAuthSuccess() {
                const urlParams = new URLSearchParams(window.location.search);
                const derivAuth = urlParams.get('deriv_auth');
                
                if (derivAuth === 'success') {
                    showLoadingOverlay('Processing Deriv authorization...');
                    
                    try {
                        const response = await fetch('<?php echo base_url() ?>Auth/GetDerivSessionData', {
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
                            // Store session data and redirect to registration completion
                            sessionStorage.setItem('deriv_auth_complete', 'true');
                            showSuccess('Deriv account connected successfully! Redirecting...');
                            
                            setTimeout(() => {
                                window.location.href = '<?php echo base_url() ?>Auth/CompleteRegistration';
                            }, 1500);
                        } else {
                            throw new Error(data.message || 'Failed to retrieve Deriv account information');
                        }
                    } catch (error) {
                        showError(error.message || 'Error processing authorization');
                        console.error('Error:', error);
                    } finally {
                        hideLoadingOverlay();
                        // Clean up URL
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }
                }
            }
            
            function showLoadingOverlay(message) {
                const overlay = document.createElement('div');
                overlay.id = 'loadingOverlay';
                overlay.className = 'loading-overlay';
                overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.8);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 9999;
                `;
                overlay.innerHTML = `
                    <div class="loading-content text-center text-white">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>${message}</p>
                    </div>
                `;
                document.body.appendChild(overlay);
            }
            
            function hideLoadingOverlay() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) {
                    overlay.remove();
                }
            }
            
            function showError(message) {
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger alert-dismissible fade show';
                errorAlert.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    max-width: 400px;
                `;
                errorAlert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(errorAlert);
                
                setTimeout(() => {
                    if (errorAlert.parentNode) {
                        errorAlert.remove();
                    }
                }, 5000);
            }
            
            function showSuccess(message) {
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show';
                successAlert.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    max-width: 400px;
                `;
                successAlert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(successAlert);
                
                setTimeout(() => {
                    if (successAlert.parentNode) {
                        successAlert.remove();
                    }
                }, 5000);
            }
        });
    </script>
    
    <!-- Custom CSS for this page -->
    <style>
        .btn-deriv {
            background: linear-gradient(135deg, #ff444f, #ff6b47);
            border: none;
            color: white;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-deriv:hover {
            background: linear-gradient(135deg, #e63946, #f77f00);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 68, 79, 0.3);
        }
        
        .btn-deriv:disabled {
            opacity: 0.7;
            transform: none;
            box-shadow: none;
        }
        
        .auth-step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e9ecef;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .step.active .step-number {
            background: #007bff;
            color: white;
        }
        
        .step-text {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .step.active .step-text {
            color: #007bff;
            font-weight: 600;
        }
        
        .step-line {
            width: 60px;
            height: 2px;
            background: #e9ecef;
            margin: 0 1rem;
            margin-top: -28px;
        }
        
        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            border-left: 4px solid #007bff;
        }
        
        .info-card h6 {
            color: #007bff;
            margin-bottom: 0.5rem;
        }
        
        .info-card ul {
            padding-left: 1rem;
        }
        
        .info-card li {
            margin-bottom: 0.25rem;
        }
        
        .security-notice .alert {
            border-radius: 8px;
        }
        
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    
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