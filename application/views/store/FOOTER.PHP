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

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Mobile Money Interface">
    <meta name="author" content="">
    <title>SAFARICOM M-PESA</title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?php echo base_url() ?>manifest.json" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?php echo base_url() ?>assets/img/mpesa_180.png" sizes="180x180">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/mpesa_32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/mpesa_16.png" sizes="16x16" type="image/png">

    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url() ?>assets/css/mpesa.css" rel="stylesheet">
    
    <style>
    :root {
        --mpesa-green: #00A950;
        --mpesa-dark-green: #007D3D;
        --mpesa-light-green: #00C864;
        --mpesa-yellow: #FFC72C;
        --mpesa-orange: #FF8C00;
        --mpesa-red: #E74C3C;
        --mpesa-blue: #3498DB;
        --mpesa-purple: #9B59B6;
        --mpesa-gray: #95A5A6;
        --mpesa-dark: #2C3E50;
        --mpesa-light: #ECF0F1;
        --mpesa-white: #FFFFFF;
        --mpesa-black: #1A1A1A;
        --header-height: 70px;
        --footer-height: 80px;
        --sidebar-width: 280px;
    }

    [data-bs-theme="dark"] {
        --mpesa-green: #00C864;
        --mpesa-dark-green: #00A950;
        --mpesa-light-green: #00E676;
        --mpesa-yellow: #FFD700;
        --mpesa-orange: #FFA500;
        --mpesa-red: #FF6B6B;
        --mpesa-blue: #5D9CEC;
        --mpesa-purple: #AC92EC;
        --mpesa-gray: #A0A0A0;
        --mpesa-dark: #121212;
        --mpesa-light: #252525;
        --mpesa-white: #1E1E1E;
        --mpesa-black: #F5F5F5;
    }

    body {
        font-family: 'Inter', 'Open Sans', sans-serif;
        background-color: var(--mpesa-white);
        color: var(--mpesa-black);
        padding-top: var(--header-height);
        padding-bottom: var(--footer-height);
        min-height: 100vh;
        transition: all 0.4s ease;
        overflow-x: hidden;
        background-image: url('<?php echo base_url() ?>assets/img/mpesa-bg-pattern.png');
        background-size: 400px;
        background-blend-mode: overlay;
        background-color: rgba(255, 255, 255, 0.95);
    }

    [data-bs-theme="dark"] body {
        background-image: url('<?php echo base_url() ?>assets/img/mpesa-bg-pattern-dark.png');
        background-color: rgba(30, 30, 30, 0.95);
    }

    .header {
        height: var(--header-height);
        background-color: var(--mpesa-white);
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        border-bottom: 1px solid rgba(0, 169, 80, 0.2);
    }

    [data-bs-theme="dark"] .header {
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        border-bottom: 1px solid rgba(0, 200, 100, 0.2);
    }

    .footer {
        height: var(--footer-height);
        background-color: var(--mpesa-white);
        box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        border-top: 1px solid rgba(0, 169, 80, 0.2);
    }

    [data-bs-theme="dark"] .footer {
        box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.3);
        border-top: 1px solid rgba(0, 200, 100, 0.2);
    }

    /* M-Pesa Branding */
    .mpesa-brand {
        color: var(--mpesa-green);
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .mpesa-brand .badge {
        background-color: var(--mpesa-yellow);
        color: var(--mpesa-black);
        font-size: 0.6em;
        vertical-align: middle;
        margin-left: 5px;
    }

    /* M-Pesa Card Styles */
    .mpesa-card {
        background-color: var(--mpesa-white);
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 125, 61, 0.1);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    [data-bs-theme="dark"] .mpesa-card {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .mpesa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 125, 61, 0.15);
    }

    [data-bs-theme="dark"] .mpesa-card:hover {
        box-shadow: 0 8px 25px rgba(0, 200, 100, 0.2);
    }

    .mpesa-card .card-header {
        background-color: var(--mpesa-green);
        color: white;
        border-bottom: none;
        padding: 1rem 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .mpesa-card .card-header:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--mpesa-yellow), var(--mpesa-orange));
    }

    .mpesa-card .card-body {
        padding: 1.5rem;
    }

    /* Balance Card */
    .balance-card {
        background: linear-gradient(135deg, var(--mpesa-green), var(--mpesa-dark-green));
        color: white;
        border-radius: 16px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(0, 125, 61, 0.2);
        transition: all 0.4s ease;
        border: 2px solid rgba(255, 255, 255, 0.15);
    }

    .balance-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 125, 61, 0.3);
    }

    .balance-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        transform: rotate(30deg);
        animation: shimmer 3s infinite linear;
    }

    @keyframes shimmer {
        0% {
            opacity: 0.7;
            transform: rotate(30deg) translateX(10px);
        }
        50% {
            opacity: 0.9;
            transform: rotate(30deg) translateX(-10px);
        }
        100% {
            opacity: 0.7;
            transform: rotate(30deg) translateX(10px);
        }
    }

    .balance-amount {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Quick Actions */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .quick-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        border-radius: 12px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        background-color: var(--mpesa-white);
        border: 1px solid rgba(0, 169, 80, 0.1);
        text-decoration: none;
        color: var(--mpesa-black);
    }

    .quick-action:hover {
        background-color: rgba(0, 169, 80, 0.05);
        transform: translateY(-8px);
        box-shadow: 0 8px 15px rgba(0, 125, 61, 0.1);
        color: var(--mpesa-green);
    }

    [data-bs-theme="dark"] .quick-action:hover {
        background-color: rgba(0, 200, 100, 0.1);
    }

    .quick-action-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--mpesa-green), var(--mpesa-dark-green));
        color: white;
        border-radius: 50%;
        margin-bottom: 12px;
        font-size: 24px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 125, 61, 0.2);
        position: relative;
        overflow: hidden;
    }

    .quick-action-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .quick-action:hover .quick-action-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .quick-action:hover .quick-action-icon::before {
        left: 100%;
    }

    .quick-action-label {
        font-size: 0.85rem;
        font-weight: 500;
        text-align: center;
    }

    /* Transactions List */
    .transactions-list {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .transaction-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin-bottom: 0.75rem;
        background-color: var(--mpesa-white);
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        cursor: pointer;
        border-left: 4px solid var(--mpesa-green);
    }

    [data-bs-theme="dark"] .transaction-item {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .transaction-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 125, 61, 0.15);
    }

    .transaction-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 169, 80, 0.1);
        color: var(--mpesa-green);
        border-radius: 50%;
        margin-right: 1rem;
        font-size: 18px;
    }

    .transaction-details {
        flex: 1;
    }

    .transaction-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--mpesa-black);
    }

    .transaction-meta {
        font-size: 0.8rem;
        color: var(--mpesa-gray);
    }

    .transaction-amount {
        font-weight: 700;
        text-align: right;
    }

    .transaction-amount.credit {
        color: var(--mpesa-green);
    }

    .transaction-amount.debit {
        color: var(--mpesa-red);
    }

    /* Profile Dropdown */
    .profile-dropdown {
        position: absolute;
        right: 15px;
        top: var(--header-height);
        width: 300px;
        background-color: var(--mpesa-white);
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        padding: 1.5rem;
        z-index: 1000;
        display: none;
        transform: translateY(-10px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border-top: 3px solid var(--mpesa-yellow);
    }

    [data-bs-theme="dark"] .profile-dropdown {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .profile-dropdown.show {
        display: block;
        transform: translateY(0);
        opacity: 1;
    }

    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .profile-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: var(--mpesa-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 24px;
        color: var(--mpesa-green);
        border: 2px solid var(--mpesa-green);
    }

    .profile-info h6 {
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .profile-info small {
        color: var(--mpesa-gray);
        font-size: 0.8rem;
    }

    .profile-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .profile-menu li {
        margin-bottom: 0.5rem;
    }

    .profile-menu a {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: var(--mpesa-black);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .profile-menu a:hover {
        background-color: rgba(0, 169, 80, 0.1);
        color: var(--mpesa-green);
    }

    .profile-menu a i {
        margin-right: 0.75rem;
        width: 20px;
        text-align: center;
    }

    .profile-menu .divider {
        height: 1px;
        background-color: rgba(0, 169, 80, 0.1);
        margin: 1rem 0;
    }

    /* Sidebar */
    .sidebar-wrap {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 999;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        width: var(--sidebar-width);
        height: 100%;
        background-color: var(--mpesa-white);
        transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        overflow-y: auto;
        z-index: 1001;
        box-shadow: 2px 0 15px rgba(0, 0, 0, 0.15);
        border-right: 1px solid rgba(0, 169, 80, 0.1);
    }

    [data-bs-theme="dark"] .sidebar {
        box-shadow: 2px 0 15px rgba(0, 0, 0, 0.3);
    }

    .sidebar.active {
        left: 0;
        pointer-events: auto;
    }

    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .sidebar-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .sidebar-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--mpesa-green), var(--mpesa-dark-green));
        color: white;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .sidebar-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--mpesa-yellow);
    }

    .sidebar-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 32px;
        border: 3px solid white;
    }

    .sidebar-user {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .sidebar-wallet {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    .sidebar-menu {
        padding: 1rem 0;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 0.85rem 1.5rem;
        color: var(--mpesa-black);
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border-left: 0 solid var(--mpesa-green);
    }

    .sidebar-menu a:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 0;
        background-color: rgba(0, 169, 80, 0.1);
        transition: width 0.3s ease;
        z-index: -1;
    }

    .sidebar-menu a:hover:before {
        width: 100%;
    }

    .sidebar-menu a:hover {
        color: var(--mpesa-green);
        transform: translateX(5px);
        border-left: 4px solid var(--mpesa-green);
    }

    .sidebar-menu a i {
        width: 24px;
        text-align: center;
        margin-right: 1rem;
        color: var(--mpesa-green);
        transition: transform 0.3s ease;
    }

    .sidebar-menu a:hover i {
        transform: scale(1.2);
    }

    .sidebar-menu hr {
        margin: 0.5rem 1.5rem;
        border-color: rgba(0, 169, 80, 0.1);
    }

    .sidebar-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0, 169, 80, 0.1);
        background-color: rgba(0, 169, 80, 0.03);
    }

    /* Buttons */
    .btn-mpesa {
        background: linear-gradient(135deg, var(--mpesa-green), var(--mpesa-dark-green));
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 125, 61, 0.2);
        position: relative;
        overflow: hidden;
    }

    .btn-mpesa:hover {
        background: linear-gradient(135deg, var(--mpesa-dark-green), var(--mpesa-green));
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 125, 61, 0.3);
    }

    .btn-mpesa:active {
        transform: translateY(0);
        box-shadow: 0 2px 5px rgba(0, 125, 61, 0.3);
    }

    .btn-mpesa-outline {
        background: transparent;
        border: 2px solid var(--mpesa-green);
        color: var(--mpesa-green);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-mpesa-outline:hover {
        background-color: rgba(0, 169, 80, 0.1);
        color: var(--mpesa-green);
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0, 125, 61, 0.1);
    }

    .btn-mpesa-yellow {
        background: linear-gradient(135deg, var(--mpesa-yellow), var(--mpesa-orange));
        border: none;
        color: var(--mpesa-black);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(255, 199, 44, 0.2);
    }

    .btn-mpesa-yellow:hover {
        background: linear-gradient(135deg, var(--mpesa-orange), var(--mpesa-yellow));
        color: var(--mpesa-black);
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(255, 199, 44, 0.3);
    }

    /* Bottom Navigation */
    .bottom-nav {
        display: flex;
        justify-content: space-around;
        padding: 0.75rem 0;
    }

    .bottom-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: var(--mpesa-black);
        transition: all 0.3s ease;
        padding: 0.5rem;
        border-radius: 8px;
    }

    .bottom-nav-item:hover {
        color: var(--mpesa-green);
        transform: translateY(-3px);
    }

    .bottom-nav-item.active {
        color: var(--mpesa-green);
    }

    .bottom-nav-icon {
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }

    .bottom-nav-label {
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Modals */
    .modal-mpesa .modal-content {
        background-color: var(--mpesa-white);
        color: var(--mpesa-black);
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 125, 61, 0.2);
        overflow: hidden;
    }

    [data-bs-theme="dark"] .modal-mpesa .modal-content {
        box-shadow: 0 10px 30px rgba(0, 200, 100, 0.2);
    }

    .modal-mpesa .modal-header {
        background: linear-gradient(to right, var(--mpesa-green), var(--mpesa-dark-green));
        color: white;
        border-bottom: none;
        padding: 1.25rem 1.5rem;
    }

    .modal-mpesa .modal-title {
        font-weight: 600;
    }

    .modal-mpesa .modal-body {
        padding: 1.5rem;
    }

    .modal-mpesa .modal-footer {
        border-top: 1px solid rgba(0, 169, 80, 0.1);
        padding: 1.25rem 1.5rem;
    }

    /* Form Elements */
    .form-control-mpesa {
        background-color: var(--mpesa-white);
        color: var(--mpesa-black);
        border: 1px solid rgba(0, 169, 80, 0.2);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control-mpesa:focus {
        border-color: var(--mpesa-green);
        box-shadow: 0 0 0 0.25rem rgba(0, 169, 80, 0.15);
        background-color: var(--mpesa-white);
    }

    .form-label-mpesa {
        font-weight: 500;
        color: var(--mpesa-black);
        margin-bottom: 0.5rem;
    }

    /* Animations */
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(15px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .fade-in {
        animation: fadeIn 0.5s cubic-bezier(0.26, 0.86, 0.44, 0.985) forwards;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .slide-in-right {
        animation: slideInRight 0.5s cubic-bezier(0.26, 0.86, 0.44, 0.985) forwards;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(0, 169, 80, 0.2);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(0, 169, 80, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(0, 169, 80, 0);
        }
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        :root {
            --header-height: 60px;
            --footer-height: 70px;
        }

        .balance-amount {
            font-size: 2rem;
        }

        .quick-actions-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .quick-action-icon {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .sidebar {
            width: 85%;
        }
    }

    @media (min-width: 768px) {
        .sidebar-wrap {
            display: none;
        }

        .container {
            max-width: 720px;
        }
    }

    @media (min-width: 992px) {
        .container {
            max-width: 960px;
        }
    }

    @media (min-width: 1200px) {
        .container {
            max-width: 1140px;
        }
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(0, 169, 80, 0.05);
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(var(--mpesa-green), var(--mpesa-dark-green));
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--mpesa-green);
    }

    /* Print styles */
    @media print {
        body * {
            visibility: hidden;
        }
        #printableReceipt, #printableReceipt * {
            visibility: visible;
        }
        #printableReceipt {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 20px;
        }
    }

    /* Additional M-Pesa specific styles */
    .mpesa-logo {
        height: 30px;
        width: auto;
    }

    .mpesa-tagline {
        font-size: 0.8rem;
        color: var(--mpesa-gray);
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .mpesa-badge {
        background-color: var(--mpesa-yellow);
        color: var(--mpesa-black);
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        display: inline-block;
    }

    .mpesa-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, rgba(0, 169, 80, 0.3), transparent);
        margin: 1.5rem 0;
    }

    .mpesa-notification {
        position: fixed;
        bottom: calc(var(--footer-height) + 20px);
        left: 50%;
        transform: translateX(-50%);
        background-color: var(--mpesa-green);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        box-shadow: 0 5px 15px rgba(0, 125, 61, 0.3);
        z-index: 1100;
        display: none;
    }

    .mpesa-notification.show {
        display: block;
        animation: slideUp 0.5s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }

    /* Service Grid */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .service-item {
        background-color: var(--mpesa-white);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 169, 80, 0.1);
    }

    [data-bs-theme="dark"] .service-item {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .service-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 125, 61, 0.1);
        border-color: var(--mpesa-green);
    }

    .service-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--mpesa-green), var(--mpesa-dark-green));
        color: white;
        border-radius: 50%;
        margin: 0 auto 1rem;
        font-size: 24px;
    }

    .service-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .service-description {
        font-size: 0.85rem;
        color: var(--mpesa-gray);
    }

    /* Stats Cards */
    .stats-card {
        background-color: var(--mpesa-white);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border-left: 4px solid var(--mpesa-green);
    }

    [data-bs-theme="dark"] .stats-card {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .stats-card .stats-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--mpesa-green);
        margin: 0.5rem 0;
    }

    .stats-card .stats-label {
        font-size: 0.9rem;
        color: var(--mpesa-gray);
    }

    /* Enhanced Transaction Receipt */
    .receipt-container {
        background-color: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        margin: 0 auto;
    }

    .receipt-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px dashed #ddd;
    }

    .receipt-title {
        font-weight: 700;
        color: var(--mpesa-green);
        font-size: 1.25rem;
    }

    .receipt-datetime {
        font-size: 0.85rem;
        color: var(--mpesa-gray);
    }

    .receipt-details {
        margin-bottom: 1.5rem;
    }

    .receipt-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .receipt-label {
        font-weight: 500;
        color: var(--mpesa-gray);
    }

    .receipt-value {
        font-weight: 600;
        color: var(--mpesa-black);
    }

    .receipt-amount {
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
        margin: 1.5rem 0;
        color: var(--mpesa-green);
    }

    .receipt-footer {
        text-align: center;
        font-size: 0.85rem;
        color: var(--mpesa-gray);
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px dashed #ddd;
    }

    /* Enhanced Loading Spinner */
    .mpesa-spinner {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        border: 0.25em solid rgba(0, 169, 80, 0.2);
        border-radius: 50%;
        border-top-color: var(--mpesa-green);
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Enhanced Tooltips */
    .tooltip-mpesa .tooltip-inner {
        background-color: var(--mpesa-green);
        color: white;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .tooltip-mpesa.bs-tooltip-auto[data-popper-placement^=top] .tooltip-arrow::before,
    .tooltip-mpesa.bs-tooltip-top .tooltip-arrow::before {
        border-top-color: var(--mpesa-green);
    }

    /* Enhanced Alerts */
    .alert-mpesa {
        background-color: rgba(0, 169, 80, 0.1);
        color: var(--mpesa-green);
        border-left: 4px solid var(--mpesa-green);
        border-radius: 6px;
        padding: 1rem;
    }

    .alert-mpesa-success {
        background-color: rgba(0, 169, 80, 0.1);
        color: var(--mpesa-green);
        border-left: 4px solid var(--mpesa-green);
    }

    .alert-mpesa-warning {
        background-color: rgba(255, 199, 44, 0.1);
        color: var(--mpesa-orange);
        border-left: 4px solid var(--mpesa-orange);
    }

    .alert-mpesa-danger {
        background-color: rgba(231, 76, 60, 0.1);
        color: var(--mpesa-red);
        border-left: 4px solid var(--mpesa-red);
    }

    /* Enhanced Progress Bars */
    .progress-mpesa {
        height: 0.75rem;
        border-radius: 50px;
        background-color: rgba(0, 169, 80, 0.1);
    }

    .progress-bar-mpesa {
        background: linear-gradient(to right, var(--mpesa-green), var(--mpesa-dark-green));
        border-radius: 50px;
    }

    /* Enhanced Badges */
    .badge-mpesa {
        background-color: var(--mpesa-green);
        color: white;
        font-weight: 500;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
    }

    .badge-mpesa-yellow {
        background-color: var(--mpesa-yellow);
        color: var(--mpesa-black);
    }

    .badge-mpesa-orange {
        background-color: var(--mpesa-orange);
        color: white;
    }

    .badge-mpesa-red {
        background-color: var(--mpesa-red);
        color: white;
    }

    .badge-mpesa-blue {
        background-color: var(--mpesa-blue);
        color: white;
    }

    /* Enhanced Tabs */
    .nav-tabs-mpesa {
        border-bottom: 2px solid rgba(0, 169, 80, 0.1);
    }

    .nav-tabs-mpesa .nav-link {
        color: var(--mpesa-gray);
        font-weight: 500;
        border: none;
        padding: 0.75rem 1.5rem;
        position: relative;
    }

    .nav-tabs-mpesa .nav-link.active {
        color: var(--mpesa-green);
        background-color: transparent;
    }

    .nav-tabs-mpesa .nav-link.active:after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: var(--mpesa-green);
    }

    .nav-tabs-mpesa .nav-link:hover:not(.active) {
        color: var(--mpesa-dark-green);
    }

    /* Enhanced Cards */
    .card-mpesa {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    [data-bs-theme="dark"] .card-mpesa {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .card-mpesa:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 125, 61, 0.15);
    }

    .card-mpesa .card-header {
        background-color: var(--mpesa-green);
        color: white;
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .card-mpesa .card-body {
        padding: 1.5rem;
    }

    /* Enhanced List Groups */
    .list-group-mpesa .list-group-item {
        border: none;
        border-bottom: 1px solid rgba(0, 169, 80, 0.1);
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
    }

    .list-group-mpesa .list-group-item:last-child {
        border-bottom: none;
    }

    .list-group-mpesa .list-group-item:hover {
        background-color: rgba(0, 169, 80, 0.05);
    }

    .list-group-mpesa .list-group-item.active {
        background-color: var(--mpesa-green);
        color: white;
        border-color: var(--mpesa-green);
    }

    /* Enhanced Tables */
    .table-mpesa {
        color: var(--mpesa-black);
    }

    .table-mpesa thead th {
        background-color: var(--mpesa-green);
        color: white;
        border-bottom: none;
    }

    .table-mpesa tbody tr:hover {
        background-color: rgba(0, 169, 80, 0.05);
    }

    /* Enhanced Pagination */
    .pagination-mpesa .page-item .page-link {
        color: var(--mpesa-green);
        border: 1px solid rgba(0, 169, 80, 0.2);
        margin: 0 0.25rem;
        border-radius: 6px;
    }

    .pagination-mpesa .page-item.active .page-link {
        background-color: var(--mpesa-green);
        border-color: var(--mpesa-green);
        color: white;
    }

    .pagination-mpesa .page-item.disabled .page-link {
        color: var(--mpesa-gray);
    }

    /* Enhanced Breadcrumb */
    .breadcrumb-mpesa {
        background-color: transparent;
        padding: 0.75rem 0;
    }

    .breadcrumb-mpesa .breadcrumb-item a {
        color: var(--mpesa-green);
        text-decoration: none;
    }

    .breadcrumb-mpesa .breadcrumb-item.active {
        color: var(--mpesa-gray);
    }

    .breadcrumb-mpesa .breadcrumb-item + .breadcrumb-item::before {
        color: var(--mpesa-gray);
    }

    /* Enhanced Accordion */
    .accordion-mpesa .accordion-item {
        border: none;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    [data-bs-theme="dark"] .accordion-mpesa .accordion-item {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .accordion-mpesa .accordion-button {
        background-color: var(--mpesa-white);
        color: var(--mpesa-black);
        font-weight: 500;
        border: none;
        box-shadow: none;
    }

    .accordion-mpesa .accordion-button:not(.collapsed) {
        background-color: var(--mpesa-white);
        color: var(--mpesa-green);
    }

    .accordion-mpesa .accordion-button:focus {
        box-shadow: none;
    }

    .accordion-mpesa .accordion-body {
        background-color: var(--mpesa-white);
    }

    /* Enhanced Carousel */
    .carousel-mpesa .carousel-indicators button {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: var(--mpesa-gray);
        opacity: 0.5;
    }

    .carousel-mpesa .carousel-indicators button.active {
        background-color: var(--mpesa-green);
        opacity: 1;
    }

    .carousel-mpesa .carousel-control-prev-icon,
    .carousel-mpesa .carousel-control-next-icon {
        background-color: var(--mpesa-green);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-size: 50%;
    }

    /* Enhanced Dropdowns */
    .dropdown-menu-mpesa {
        border: none;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        padding: 0.5rem;
    }

    .dropdown-item-mpesa {
        color: var(--mpesa-black);
        border-radius: 6px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .dropdown-item-mpesa:hover {
        background-color: rgba(0, 169, 80, 0.1);
        color: var(--mpesa-green);
    }

    .dropdown-item-mpesa.active {
        background-color: var(--mpesa-green);
        color: white;
    }

    /* Enhanced Toasts */
    .toast-mpesa {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .toast-mpesa .toast-header {
        background-color: var(--mpesa-green);
        color: white;
        border-bottom: none;
    }

    .toast-mpesa .toast-body {
        background-color: var(--mpesa-white);
        color: var(--mpesa-black);
    }

    /* Enhanced Popovers */
    .popover-mpesa {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .popover-mpesa .popover-header {
        background-color: var(--mpesa-green);
        color: white;
        border-bottom: none;
        border-radius: 10px 10px 0 0;
    }

    .popover-mpesa .popover-body {
        background-color: var(--mpesa-white);
        color: var(--mpesa-black);
    }

    .bs-popover-auto[data-popper-placement^=top] > .popover-arrow::after,
    .bs-popover-top > .popover-arrow::after {
        border-top-color: var(--mpesa-green);
    }

    /* Enhanced Offcanvas */
    .offcanvas-mpesa {
        background-color: var(--mpesa-white);
    }

    .offcanvas-mpesa .offcanvas-header {
        background-color: var(--mpesa-green);
        color: white;
    }

    .offcanvas-mpesa .offcanvas-body {
        background-color: var(--mpesa-white);
        color: var(--mpesa-black);
    }

    /* Enhanced Spinners */
    .spinner-mpesa {
        color: var(--mpesa-green);
    }

    /* Enhanced Close Buttons */
    .btn-close-mpesa {
        color: white;
        opacity: 0.8;
    }

    .btn-close-mpesa:hover {
        color: white;
        opacity: 1;
    }

    /* Enhanced Images */
    .img-mpesa {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    [data-bs-theme="dark"] .img-mpesa {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Enhanced Figures */
    .figure-mpesa {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    [data-bs-theme="dark"] .figure-mpesa {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Enhanced Video Embeds */
    .embed-responsive-mpesa {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    [data-bs-theme="dark"] .embed-responsive-mpesa {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Enhanced Code Blocks */
    .code-block-mpesa {
        background-color: var(--mpesa-light);
        border-radius: 8px;
        padding: 1rem;
        overflow-x: auto;
    }

    .code-block-mpesa code {
        color: var(--mpesa-green);
    }

    /* Enhanced Blockquotes */
    .blockquote-mpesa {
        border-left: 4px solid var(--mpesa-green);
        padding-left: 1rem;
        color: var(--mpesa-gray);
    }

    .blockquote-footer-mpesa {
        color: var(--mpesa-green);
    }

    /* Enhanced Horizontal Rules */
    .hr-mpesa {
        height: 2px;
        background: linear-gradient(to right, transparent, var(--mpesa-green), transparent);
        border: none;
        margin: 1.5rem 0;
    }

    /* Enhanced Text Colors */
    .text-mpesa-green {
        color: var(--mpesa-green);
    }

    .text-mpesa-yellow {
        color: var(--mpesa-yellow);
    }

    .text-mpesa-orange {
        color: var(--mpesa-orange);
    }

    .text-mpesa-red {
        color: var(--mpesa-red);
    }

    .text-mpesa-blue {
        color: var(--mpesa-blue);
    }

    .text-mpesa-purple {
        color: var(--mpesa-purple);
    }

    /* Enhanced Background Colors */
    .bg-mpesa-green {
        background-color: var(--mpesa-green);
    }

    .bg-mpesa-yellow {
        background-color: var(--mpesa-yellow);
    }

    .bg-mpesa-orange {
        background-color: var(--mpesa-orange);
    }

    .bg-mpesa-red {
        background-color: var(--mpesa-red);
    }

    .bg-mpesa-blue {
        background-color: var(--mpesa-blue);
    }

    .bg-mpesa-purple {
        background-color: var(--mpesa-purple);
    }

    /* Enhanced Border Colors */
    .border-mpesa-green {
        border-color: var(--mpesa-green);
    }

    .border-mpesa-yellow {
        border-color: var(--mpesa-yellow);
    }

    .border-mpesa-orange {
        border-color: var(--mpesa-orange);
    }

    .border-mpesa-red {
        border-color: var(--mpesa-red);
    }

    .border-mpesa-blue {
        border-color: var(--mpesa-blue);
    }

    .border-mpesa-purple {
        border-color: var(--mpesa-purple);
    }

    /* Enhanced Shadows */
    .shadow-mpesa {
        box-shadow: 0 4px 15px rgba(0, 125, 61, 0.1);
    }

    .shadow-mpesa-lg {
        box-shadow: 0 8px 25px rgba(0, 125, 61, 0.15);
    }

    .shadow-mpesa-yellow {
        box-shadow: 0 4px 15px rgba(255, 199, 44, 0.1);
    }

    .shadow-mpesa-yellow-lg {
        box-shadow: 0 8px 25px rgba(255, 199, 44, 0.15);
    }

    /* Enhanced Opacity */
    .opacity-mpesa-10 {
        opacity: 0.1;
    }

    .opacity-mpesa-25 {
        opacity: 0.25;
    }

    .opacity-mpesa-50 {
        opacity: 0.5;
    }

    .opacity-mpesa-75 {
        opacity: 0.75;
    }

    .opacity-mpesa-100 {
        opacity: 1;
    }

    /* Enhanced Gradients */
    .gradient-mpesa-green {
        background: linear-gradient(135deg, var(--mpesa-green), var(--mpesa-dark-green));
    }

    .gradient-mpesa-yellow {
        background: linear-gradient(135deg, var(--mpesa-yellow), var(--mpesa-orange));
    }

    /* Enhanced Filters */
    .filter-mpesa-green {
        filter: brightness(0) saturate(100%) invert(42%) sepia(93%) saturate(364%) hue-rotate(97deg) brightness(95%) contrast(101%);
    }

    .filter-mpesa-yellow {
        filter: brightness(0) saturate(100%) invert(79%) sepia(89%) saturate(427%) hue-rotate(330deg) brightness(103%) contrast(101%);
    }

    /* Enhanced Transitions */
    .transition-mpesa {
        transition: all 0.3s ease;
    }

    .transition-mpesa-slow {
        transition: all 0.5s ease;
    }

    .transition-mpesa-fast {
        transition: all 0.15s ease;
    }

    /* Enhanced Transforms */
    .transform-mpesa-up {
        transform: translateY(-5px);
    }

    .transform-mpesa-down {
        transform: translateY(5px);
    }

    .transform-mpesa-left {
        transform: translateX(-5px);
    }

    .transform-mpesa-right {
        transform: translateX(5px);
    }

    /* Enhanced Animations */
    @keyframes bounce-mpesa {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    .animate-bounce-mpesa {
        animation: bounce-mpesa 1s infinite;
    }

    @keyframes pulse-mpesa {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .animate-pulse-mpesa {
        animation: pulse-mpesa 2s infinite;
    }

    /* Enhanced Utilities */
    .cursor-pointer {
        cursor: pointer;
    }

    .cursor-default {
        cursor: default;
    }

    .cursor-not-allowed {
        cursor: not-allowed;
    }

    .select-none {
        user-select: none;
    }

    .select-text {
        user-select: text;
    }

    .select-all {
        user-select: all;
    }

    .pointer-events-none {
        pointer-events: none;
    }

    .pointer-events-auto {
        pointer-events: auto;
    }

    /* Enhanced Responsive Utilities */
    @media (min-width: 576px) {
        .sm\:visible {
            visibility: visible;
        }
        .sm\:invisible {
            visibility: hidden;
        }
    }

    @media (min-width: 768px) {
        .md\:visible {
            visibility: visible;
        }
        .md\:invisible {
            visibility: hidden;
        }
    }

    @media (min-width: 992px) {
        .lg\:visible {
            visibility: visible;
        }
        .lg\:invisible {
            visibility: hidden;
        }
    }

    @media (min-width: 1200px) {
        .xl\:visible {
            visibility: visible;
        }
        .xl\:invisible {
            visibility: hidden;
        }
    }

    @media (min-width: 1400px) {
        .xxl\:visible {
            visibility: visible;
        }
        .xxl\:invisible {
            visibility: hidden;
        }
    }

    /* Enhanced Print Styles */
    @media print {
        .print\:visible {
            visibility: visible;
        }
        .print\:invisible {
            visibility: hidden;
        }
    }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container-fluid">
            <div class="row align-items-center h-100">
                <div class="col-auto">
                    <button class="btn btn-link text-decoration-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div class="col text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="<?php echo base_url() ?>assets/img/mpesa-logo.png" alt="M-Pesa" height="30" class="me-2">
                        <span class="mpesa-brand">M-PESA <span class="badge">SAFARICOM</span></span>
                    </div>
                    <div class="mpesa-tagline">Send and receive money with ease</div>
                </div>
                <div class="col-auto d-flex align-items-center">
                    <div class="theme-toggle me-3" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </div>
                    <div class="position-relative">
                        <button class="btn btn-link text-decoration-none" id="profileToggle">
                            <i class="fas fa-user-circle fa-lg"></i>
                            <?php if ($agent == 1) : ?>
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                    <span class="visually-hidden">Verified Agent</span>
                                </span>
                            <?php endif; ?>
                        </button>
                        <div class="profile-dropdown" id="profileDropdown">
                            <div class="profile-header">
                                <div class="profile-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="profile-info">
                                    <h6><?php echo $this->session->userdata('phone'); ?></h6>
                                    <small>M-Pesa ID: <?php echo $this->session->userdata('wallet_id'); ?></small>
                                </div>
                            </div>
                            <ul class="profile-menu">
                                <li><a href="<?php echo base_url() ?>profile"><i class="fas fa-user"></i> My Profile</a></li>
                                <li><a href="<?php echo base_url() ?>transactions"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                                <li><a href="<?php echo base_url() ?>settings"><i class="fas fa-cog"></i> Settings</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo base_url() ?>logout" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="sidebar-wrap" id="sidebarWrap">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h5 class="sidebar-user"><?php echo $this->session->userdata('phone'); ?></h5>
                <small class="sidebar-wallet">M-Pesa ID: <?php echo $this->session->userdata('wallet_id'); ?></small>
            </div>
            <div class="sidebar-menu">
                <a href="<?php echo base_url() ?>home" class="d-block">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="<?php echo base_url() ?>send_money" class="d-block">
                    <i class="fas fa-paper-plane"></i> Send Money
                </a>
                <a href="<?php echo base_url() ?>withdraw" class="d-block">
                    <i class="fas fa-money-bill-wave"></i> Withdraw Cash
                </a>
                <a href="<?php echo base_url() ?>buy_airtime" class="d-block">
                    <i class="fas fa-mobile-alt"></i> Buy Airtime
                </a>
                <a href="<?php echo base_url() ?>pay_bill" class="d-block">
                    <i class="fas fa-receipt"></i> Pay Bill
                </a>
                <a href="<?php echo base_url() ?>loans" class="d-block">
                    <i class="fas fa-hand-holding-usd"></i> M-Shwari
                </a>
                <hr>
                <a href="<?php echo base_url() ?>transactions" class="d-block">
                    <i class="fas fa-history"></i> Transaction History
                </a>
                <a href="<?php echo base_url() ?>saved_beneficiaries" class="d-block">
                    <i class="fas fa-address-book"></i> My Beneficiaries
                </a>
                <hr>
                <a href="<?php echo base_url() ?>settings" class="d-block">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="<?php echo base_url() ?>help" class="d-block">
                    <i class="fas fa-question-circle"></i> Help & Support
                </a>
            </div>
            <div class="sidebar-footer text-center">
                <small class="text-muted">M-Pesa by Safaricom PLC</small>
            </div>
        </div>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
    </div>

    <!-- Main Content -->
    <main class="container py-4">
        <!-- Balance Card -->
        <div class="balance-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="mb-0">M-PESA BALANCE</h6>
                    <small>Available Funds</small>
                </div>
                <div class="theme-toggle" id="balanceThemeToggle">
                    <i class="fas fa-eye-slash"></i>
                </div>
            </div>
            <h2 class="balance-amount">KES <?php echo number_format($total_balance, 2, '.', ','); ?></h2>
            <div class="d-flex justify-content-between">
                <small>Last updated: <?php echo date('H:i'); ?></small>
                <small><i class="fas fa-sync-alt"></i> Refresh</small>
            </div>
        </div>

        <!-- Flash Message -->
        <?php if ($flash = $this->session->flashdata('msg')) : ?>
            <div class="alert alert-mpesa-warning alert-dismissible fade show mb-4" role="alert">
                <?php echo $flash; ?>
                <button type="button" class="btn-close btn-close-mpesa" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="mpesa-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="quick-actions-grid">
                    <a href="#" class="quick-action" data-bs-toggle="modal" data-bs-target="#sendMoneyModal">
                        <div class="quick-action-icon">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <span class="quick-action-label">Send Money</span>
                    </a>
                    <a href="#" class="quick-action" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                        <div class="quick-action-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <span class="quick-action-label">Withdraw Cash</span>
                    </a>
                    <a href="#" class="quick-action" data-bs-toggle="modal" data-bs-target="#buyAirtimeModal">
                        <div class="quick-action-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <span class="quick-action-label">Buy Airtime</span>
                    </a>
                    <a href="#" class="quick-action" data-bs-toggle="modal" data-bs-target="#payBillModal">
                        <div class="quick-action-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <span class="quick-action-label">Pay Bill</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="mpesa-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">M-Pesa Services</h6>
            </div>
            <div class="card-body">
                <div class="services-grid">
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h6 class="service-title">M-Shwari</h6>
                        <p class="service-description">Save & borrow money</p>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-piggy-bank"></i>
                        </div>
                        <h6 class="service-title">Fuliza</h6>
                        <p class="service-description">Overdraft facility</p>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h6 class="service-title">Lipa Na M-Pesa</h6>
                        <p class="service-description">Pay for goods</p>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-globe-africa"></i>
                        </div>
                        <h6 class="service-title">International</h6>
                        <p class="service-description">Send money abroad</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="mpesa-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Recent Transactions</h6>
                <a href="<?php echo base_url() ?>transactions" class="btn btn-sm btn-mpesa-outline">View All</a>
            </div>
            <div class="card-body">
                <div class="transactions-list">
                    <?php if ($transactions) : ?>
                        <?php foreach ($transactions as $trans) : ?>
                            <div class="transaction-item" data-bs-toggle="modal" data-bs-target="#transactionModal" data-transaction-id="<?php echo $trans['transaction_number']; ?>">
                                <div class="transaction-icon">
                                    <?php if ($trans['transaction_type'] == 'Deposit') : ?>
                                        <i class="fas fa-arrow-down"></i>
                                    <?php elseif ($trans['transaction_type'] == 'Withdrawal') : ?>
                                        <i class="fas fa-arrow-up"></i>
                                    <?php else : ?>
                                        <i class="fas fa-exchange-alt"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="transaction-details">
                                    <h6 class="transaction-title"><?php echo $trans['transaction_type']; ?></h6>
                                    <div class="transaction-meta"><?php echo $trans['transaction_number']; ?> • <?php echo $trans['created_at']; ?></div>
                                </div>
                                <div class="transaction-amount <?php echo $trans['transaction_type'] == 'Deposit' ? 'credit' : 'debit'; ?>">
                                    <?php echo $trans['currency'] . ' ' . $trans['amount']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-exchange-alt fa-3x mb-3"></i>
                            <p>No transactions yet</p>
                            <a href="#" class="btn btn-mpesa-outline">Make your first transaction</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Navigation -->
    <footer class="footer">
        <div class="container">
            <div class="bottom-nav">
                <a href="<?php echo base_url() ?>home" class="bottom-nav-item active">
                    <div class="bottom-nav-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="bottom-nav-label">Home</div>
                </a>
                <a href="<?php echo base_url() ?>transactions" class="bottom-nav-item">
                    <div class="bottom-nav-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="bottom-nav-label">History</div>
                </a>
                <a href="#" class="bottom-nav-item" data-bs-toggle="modal" data-bs-target="#scanQRModal">
                    <div class="bottom-nav-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <div class="bottom-nav-label">Scan QR</div>
                </a>
                <a href="<?php echo base_url() ?>help" class="bottom-nav-item">
                    <div class="bottom-nav-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="bottom-nav-label">Help</div>
                </a>
            </div>
        </div>
    </footer>

    <!-- Send Money Modal -->
    <div class="modal fade modal-mpesa" id="sendMoneyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Money</h5>
                    <button type="button" class="btn-close btn-close-mpesa" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo base_url() ?>Main/send_money" onsubmit="return disableSendButton()">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="sendPhone" class="form-label-mpesa">Phone Number</label>
                            <input type="tel" class="form-control-mpesa" id="sendPhone" name="phone" placeholder="Enter recipient's phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="sendAmount" class="form-label-mpesa">Amount (KES)</label>
                            <input type="number" class="form-control-mpesa" id="sendAmount" name="amount" min="1" step="1" placeholder="Enter amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="sendReason" class="form-label-mpesa">Reason (Optional)</label>
                            <input type="text" class="form-control-mpesa" id="sendReason" name="reason" placeholder="e.g. Lunch money">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-mpesa-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="sendButton" class="btn btn-mpesa">Send Money</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Withdraw Modal -->
    <div class="modal fade modal-mpesa" id="withdrawModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Withdraw Cash</h5>
                    <button type="button" class="btn-close btn-close-mpesa" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo base_url() ?>Main/withdraw" onsubmit="return disableWithdrawButton()">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="withdrawAgent" class="form-label-mpesa">Agent Number</label>
                            <input type="tel" class="form-control-mpesa" id="withdrawAgent" name="agent" placeholder="Enter agent number" required>
                        </div>
                        <div class="mb-3">
                            <label for="withdrawAmount" class="form-label-mpesa">Amount (KES)</label>
                            <input type="number" class="form-control-mpesa" id="withdrawAmount" name="amount" min="10" step="1" placeholder="Enter amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="withdrawPin" class="form-label-mpesa">M-Pesa PIN</label>
                            <input type="password" class="form-control-mpesa" id="withdrawPin" name="pin" placeholder="Enter your M-Pesa PIN" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-mpesa-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="withdrawButton" class="btn btn-mpesa">Withdraw</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Buy Airtime Modal -->
    <div class="modal fade modal-mpesa" id="buyAirtimeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buy Airtime</h5>
                    <button type="button" class="btn-close btn-close-mpesa" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo base_url() ?>Main/buy_airtime" onsubmit="return disableAirtimeButton()">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="airtimePhone" class="form-label-mpesa">Phone Number</label>
                            <input type="tel" class="form-control-mpesa" id="airtimePhone" name="phone" value="<?php echo $this->session->userdata('phone'); ?>" required>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="airtimeSelf" checked>
                                <label class="form-check-label" for="airtimeSelf">Buy for myself</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="airtimeAmount" class="form-label-mpesa">Amount (KES)</label>
                            <div class="btn-group d-flex mb-2" role="group">
                                <button type="button" class="btn btn-mpesa-outline amount-btn" data-amount="10">10</button>
                                <button type="button" class="btn btn-mpesa-outline amount-btn" data-amount="50">50</button>
                                <button type="button" class="btn btn-mpesa-outline amount-btn" data-amount="100">100</button>
                                <button type="button" class="btn btn-mpesa-outline amount-btn" data-amount="200">200</button>
                            </div>
                            <input type="number" class="form-control-mpesa" id="airtimeAmount" name="amount" min="5" step="1" placeholder="Or enter custom amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-mpesa-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="airtimeButton" class="btn btn-mpesa">Buy Airtime</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pay Bill Modal -->
    <div class="modal fade modal-mpesa" id="payBillModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pay Bill</h5>
                    <button type="button" class="btn-close btn-close-mpesa" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo base_url() ?>Main/pay_bill" onsubmit="return disablePayBillButton()">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="billCode" class="form-label-mpesa">Business Number</label>
                            <select class="form-control-mpesa" id="billCode" name="code" required>
                                <option value="">Select business</option>
                                <option value="888888">KPLC - Electricity</option>
                                <option value="888888">Nairobi Water</option>
                                <option value="888888">DStv</option>
                                <option value="888888">Gotv</option>
                                <option value="888888">Zuku</option>
                                <option value="888888">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="billAccount" class="form-label-mpesa">Account Number</label>
                            <input type="text" class="form-control-mpesa" id="billAccount" name="account" placeholder="Enter account number" required>
                        </div>
                        <div class="mb-3">
                            <label for="billAmount" class="form-label-mpesa">Amount (KES)</label>
                            <input type="number" class="form-control-mpesa" id="billAmount" name="amount" min="1" step="1" placeholder="Enter amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-mpesa-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="payBillButton" class="btn btn-mpesa">Pay Bill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scan QR Modal -->
    <div class="modal fade modal-mpesa" id="scanQRModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan QR Code</h5>
                    <button type="button" class="btn-close btn-close-mpesa" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-4" style="width: 250px; height: 250px; background-color: #f5f5f5; margin: 0 auto; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                        <i class="fas fa-qrcode fa-5x text-muted"></i>
                    </div>
                    <p class="text-muted">Point your camera at a QR code to scan</p>
                    <button class="btn btn-mpesa-outline">
                        <i class="fas fa-camera me-2"></i> Open Camera
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-mpesa-outline" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div class="modal fade modal-mpesa" id="transactionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaction Details</h5>
                    <button type="button" class="btn-close btn-close-mpesa" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="transactionDetails">
                    <div class="text-center py-4">
                        <div class="mpesa-spinner"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-mpesa-outline" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-mpesa" onclick="printReceipt()">
                        <i class="fas fa-print me-2"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden div for printing -->
    <div id="printableReceipt" style="display: none;"></div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- html2canvas for printing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <!-- jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>


</body>
</html>