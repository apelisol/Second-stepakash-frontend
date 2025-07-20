<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Main';
$route['login'] = 'Main/login';
$route['signup'] = 'Main/signup';
$route['derivauth'] = 'Main/derivauth';
$route['derivauth'] = 'auth/derivauth';
$route['home'] = 'Main/home';
$route['test'] = 'Main/test';
$route['testing'] = 'Main/testing';
$route['checkout'] = 'Main/checkout';
$route['pay'] = 'Main/process_checkout';
$route['payment_form'] = 'Main/payment_form';
$route['invoice_payment'] = 'Main/invoice_payment';
$route['payment_confirmation'] = 'Main/payment_confirmation';

// deriv
$route['DerivOAuth'] = 'Main/DerivOAuth';
$route['DerivCallback'] = 'Main/DerivCallback';
$route['GetDerivSessionData'] = 'Main/GetDerivSessionData';


$route['transactions'] = 'Main/transactions';
$route['thankyou'] = 'Main/thankyou';
$route['p2p_transfer'] = 'Main/p2p_transfer';
$route['deposit_binance'] = 'Main/deposit_binance';
$route['deposit_bitcoin'] = 'Main/deposit_bitcoin';
$route['deposit_ethereum'] = 'Main/deposit_ethereum';
$route['deriv_deposit'] = 'Main/DepositToDeriv'; 
$route['deposit_erc'] = 'Main/deposit_erc';
$route['deposit_trc'] = 'Main/deposit_trc';
$route['deposit_skrill'] = 'Main/deposit_skrill';
$route['deposit_neteller'] = 'Main/deposit_neteller';

//withdraw
$route['withdraw_binance'] = 'Main/withdraw_binance';
$route['withdraw_bitcoin'] = 'Main/withdraw_bitcoin';
$route['withdraw_ethereum'] = 'Main/withdraw_ethereum';
$route['withdraw_erc'] = 'Main/withdraw_erc';
$route['withdraw_trc'] = 'Main/withdraw_trc';
$route['withdraw_skrill'] = 'Main/withdraw_skrill';
$route['withdraw_neteller'] = 'Main/withdraw_neteller';




$route['forgotpassword'] = 'Auth/forgotpassword';
$route['verifyotp'] = 'Auth/verifyotp';

$route['resetpassword'] = 'Auth/resetpassword';
$route['updatepassword'] = 'Auth/updatepassword';
$route['changepassword'] = 'Main/changepassword';
$route['authorisation'] = 'Main/authorisation';
$route['verification'] = 'Main/verification';
$route['profile'] = 'Main/profile';
$route['stkresults'] = 'Money/stkresults';
$route['account'] = 'Main/account';
$route['balance'] = 'Main/balance';
$route['initiate'] = 'Main/initiate';
$route['terms'] = 'Main/terms';
$route['privacy'] = 'Main/privacy';
$route['logout'] = 'Auth/logout';

//SEND GIFT
$route['send_gift'] = 'Main/send_gift';
$route['withdraw_to_agent'] = 'Main/withdraw_to_agent';
//REGISTER AGENT
$route['agency'] = 'Main/agency';


$route['deriv_withdraw'] = 'Main/WithdrawFromDeriv';
$route['verify_deriv_token'] = 'Main/verifyDerivToken';
$route['get_deriv_rates'] = 'Main/getDerivRates';
$route['deriv_withdrawal_callback'] = 'Deriv/derivWithdrawalCallback';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
