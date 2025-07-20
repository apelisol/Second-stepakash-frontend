<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="StepaKash - Reset Password">
    <meta name="author" content="">
    <title>Reset Password | StepaKash</title>
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
    <link href="<?php echo base_url() ?>assets/css/reset.css" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <div class="auth-container mb-4">
                    <div class="auth-logo">
                        <img src="<?php echo base_url() ?>assets/img/stepak_72.png" alt="STEPAKASH Logo" />
                    </div>
                    <h2 class="auth-title">Reset Password</h2>
                    <p class="auth-subtitle">Create a new password for your account</p>

                    <?php if($this->session->flashdata('msg')): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('msg'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo base_url() ?>Auth/updatepassword" method="POST" id="resetForm">
                        <input type="hidden" name="wallet_id" value="<?php echo $this->session->userdata('wallet_id'); ?>">
                        <input type="hidden" name="phone" value="<?php echo $this->session->userdata('phone'); ?>">

                        <div class="form-floating mb-4 error-container">
                            <input type="password" class="form-control" name="password" id="password" placeholder="New Password" required minlength="4">
                            <label for="password">New Password</label>
                            <div id="passwordError" class="error-message"></div>
                        </div>

                        <div class="form-floating mb-4 error-container">
                            <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password" required minlength="4">
                            <label for="confirmpassword">Confirm Password</label>
                            <div id="confirmError" class="error-message"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="resetBtn">
                                <span class="spinner" id="resetSpinner"></span>
                                <span id="resetText">Update Password</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Form submission
            $('#resetForm').submit(function(e) {
                e.preventDefault();
                
                // Get form values
                const password = $('#password').val();
                const confirmPassword = $('#confirmpassword').val();
                let isValid = true;
                
                // Reset previous error messages
                $('#passwordError').text('');
                $('#confirmError').text('');
                
                // Validate password
                if (!password) {
                    $('#passwordError').text('Please enter a new password');
                    isValid = false;
                } else if (password.length < 4) {
                    $('#passwordError').text('Password must be at least 4 characters');
                    isValid = false;
                }
                
                // Validate confirm password
                if (!confirmPassword) {
                    $('#confirmError').text('Please confirm your password');
                    isValid = false;
                } else if (password !== confirmPassword) {
                    $('#confirmError').text('Passwords do not match');
                    isValid = false;
                }
                
                if (isValid) {
                    // Show loading state
                    $('#resetSpinner').show();
                    $('#resetText').text('Updating...');
                    $('#resetBtn').addClass('loading');
                    
                    // Submit form via AJAX
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else if (response.error) {
                                $('#passwordError').text(response.error);
                            }
                        },
                        error: function(xhr) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                $('#passwordError').text(response.error || 'Password update failed');
                            } catch(e) {
                                $('#resetForm')[0].submit();
                            }
                        },
                        complete: function() {
                            $('#resetSpinner').hide();
                            $('#resetText').text('Update Password');
                            $('#resetBtn').removeClass('loading');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>