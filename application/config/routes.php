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
  |	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'chargeback';
$route['test'] = 'chargeback/test';
//BackEnd Routes
$route['admin'] = 'administrator/administrator';
$route['admin/login'] = 'administrator/administrator/login';
$route['admin/registration'] = 'administrator/administrator/registration';
$route['admin/dashboard'] = 'administrator/administrator/Dashboard';
$route['admin/settings'] = 'administrator/administrator/Settings';
$route['admin/save_credentials'] = 'administrator/administrator/Save_Credentials';
$route['admin/dispute_data'] = 'administrator/administrator/dispute_data';
$route['admin/logout'] = 'administrator/administrator/Logout';
$route['admin/api_data'] = 'administrator/administrator/Disputed_APIData_View';
$route['admin/merchant_data'] = 'administrator/administrator/Merchant_APIData_View';
$route['admin/alert_data'] = 'administrator/administrator/Alert_APIData_View';
$route['admin/admin_settings'] = 'administrator/administrator/admin_settings';
$route['admin/save_setting'] = 'administrator/administrator/save_setting';
$route['admin/testresult'] = 'administrator/administrator/testResult';
$route['admin/import_csv'] = 'administrator/administrator/importCSV';
$route['admin/upload_csv'] = 'administrator/administrator/uploadCSV';
$route['admin/test_array'] = 'administrator/administrator/test_array';
$route['admin/transaction_data'] = 'administrator/administrator/Transaction_APIData_View';
$route['admin/testarray'] = 'administrator/administrator/testArray';
$route['admin/add_merchant'] = 'administrator/administrator/add_merchant';
$route['admin/save_merchant'] = 'administrator/administrator/save_merchant';
$route['admin/all_merchant'] = 'administrator/administrator/all_merchant';
$route['admin/make_alert'] = 'administrator/administrator/makeAlert';
$route['admin/sftp'] = 'administrator/administrator/SFTPConnect';
$route['admin/save_sftp'] = 'administrator/administrator/save_sftp';
$route['admin/save_template'] = 'administrator/administrator/save_template';
$route['admin/connect_ftp'] = 'administrator/administrator/connect_ftp';
$route['admin/sage_view'] = 'administrator/administrator/sage_view';
$route['admin/connect_sage_ftp'] = 'administrator/administrator/connect_sage_ftp';
$route['admin/ftp_connect_sage'] = 'administrator/administrator/SFTPConnectSage';

$route['admin/get_all_transactions/(:any)'] = 'administrator/administrator/getAllTransactions/$1';
$route['admin/view_merchant/(:any)'] = 'administrator/administrator/view_merchant/$1';
$route['admin/view_data/(:any)/(:any)/(:any)'] = 'administrator/administrator/view_data/$1/$1/$1';
$route['admin/view_transaction/(:any)'] = 'administrator/administrator/view_transaction/$1';
$route['admin/view_transaction_auth/(:any)'] = 'administrator/administrator/view_transaction_auth/$1';
$route['admin/save_now'] = 'administrator/administrator/save_now/';
$route['admin/import_now'] = 'administrator/administrator/import_now/';
$route['admin/charge_back'] = 'administrator/administrator/charge_back/';
$route['admin/all_customer'] = 'administrator/administrator/all_customer/';
$route['admin/view_cb_data/(:any)'] = 'administrator/administrator/view_cb_data/$1';
$route['admin/del_user/(:any)'] = 'administrator/administrator/del_user/$1';
$route['admin/suspend_user/(:any)'] = 'administrator/administrator/suspend_user/$1';
$route['admin/enable_user/(:any)'] = 'administrator/administrator/enable_user/$1';
$route['admin/get_parser_mail'] = 'administrator/administrator/GetParserMail/';
$route['admin/cb_dispute_match'] = 'administrator/administrator/cb_dispute_match/';
$route['admin/cb_dispute'] = 'administrator/administrator/cb_dispute/';
$route['admin/match_filters'] = 'administrator/administrator/MatchFilters/';
$route['admin/alert'] = 'administrator/administrator/Alert/';
$route['admin/template'] = 'administrator/administrator/template/';
$route['admin/get_user_mertype'] = 'administrator/administrator/get_user_mertype/';
$route['admin/get_user_processors'] = 'administrator/administrator/get_user_processors/';
$route['admin/save_sage_sftp'] = 'administrator/administrator/save_sage_sftp/';
$route['admin/insert_test'] = 'administrator/administrator/InsertTest/';



$route['admin/cron_match_alert'] = 'administrator/cron_job/cron_match_alert';
$route['admin/cron_get_transaction'] = 'administrator/cron_job/cron_get_transaction/';
$route['admin/cron_curl_request'] = 'administrator/cron_job/cron_curl_request/';
$route['admin/CronGetTransactionDetailsRequest'] = 'administrator/cron_job/CronGetTransactionDetailsRequest/';
$route['admin/CronGetSettledBatchListRequest'] = 'administrator/cron_job/CronGetSettledBatchListRequest/';
$route['admin/CronGetUnsettledTransactionListRequest'] = 'administrator/cron_job/CronGetUnsettledTransactionListRequest/';
$route['admin/CronGetTransactionListRequest'] = 'administrator/cron_job/CronGetTransactionListRequest/';
$route['admin/CronSecureNMI'] = 'administrator/cron_job/CronSecureNMI/';
$route['admin/nmi_Transaction'] = 'administrator/cron_job/NMI_Transaction/';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
