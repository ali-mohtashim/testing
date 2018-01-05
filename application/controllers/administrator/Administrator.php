<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator extends CI_Controller {
    /*
     * @var Table $table declare it as an array in construct method for passing tables name 
     */

    protected $table;
    public $current_user;
    public $get_fnc;
    public $current_url;
    protected $APILOGIN;
    protected $TransactionKey;
    protected $Endpoint;
    public $processor;

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'cookie', 'custom'));
        $this->load->model(array('admin_manager'));
        $this->table = array('admin', 'users', 'api', 'disputes', 'transaction', 'alert', 'merchant', 'charge_back', 'csv_template', 'sftp', 'template', 'sage');
        $this->load->library(array('form_validation', 'email', 'ftp'));
        $this->current_user = (!empty($this->session->userdata('admin_Id'))) ? $this->session->userdata('admin_Id') : "";
        $this->get_fnc = get_instance();
        $this->current_url = $this->uri->segment(2);
        $this->APILOGIN = '5W3Wd9kL';
        $this->TransactionKey = '7w4L56qarV65RpYW';
        $this->Endpoint = 'https://apitest.authorize.net/xml/v1/request.api';
        $this->processor = $this->admin_manager->getProcessors();
    }

    public function index() {
        /*
         * Redirect dashboard view when login.Otherwise redirect to login Page
         */
        if ($this->session->userdata('admin_Id')) {
            redirect(site_url('/admin/dashboard'));
        } else {
            $this->load->view('administrator/login');
        }
    }

    public function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';
// hash
        $key = hash('sha256', $secret_key);

// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    public function Dashboard() {
        /* Create Dashboard view
         * @var Admin User ID $adminID This is for getting admin_id from session variable
         * @var data $data This is for passing data from controller to view
         */
        $adminID = $this->session->userdata('admin_Id');
        $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
        $data['transactions'] = '';
        $data['chargeback'] = '';
        $data['merchants'] = '';
        $data['users'] = '';
        if ($adminID == "1") {
            $data['merchants'] = $this->admin_manager->CheckAlreadyExistByOne($this->table[6]);
            $data['chargeback'] = $this->admin_manager->CheckAlreadyExistByOne($this->table[7]);
            $data['transactions'] = $this->admin_manager->CheckAlreadyExistByOne($this->table[4]);
            $data['users'] = $this->admin_manager->CheckAlreadyExistByOne($this->table[0]);
        }
        $this->load->view('administrator/header', $data);
        $this->load->view('administrator/sidebar');
        $this->load->view('administrator/dashboard', $data);
        $this->load->view('administrator/footer');
    }

    public function login() {
        /* Getting information from login form. Set cookies for always set username and password
         * @var username $username This is for getting username from form input field 
         * @var password $password This is for getting password from form input field 
         * @var remember $remember This is for getting remember from form input field 
         * @var query $query This is for getting by compare of two columns username and passwords
         * @var user cookie $user_cookies Set username to cookie
         * @var password cookie $pass_cookies Set password to cookie
         */
        $username = $this->input->post('uname');
        $password = $this->input->post('pass');
        $remember = $this->input->post('remember');
        $query = $this->admin_manager->SelectByCompare($this->table[0], 'username', $username, 'password', md5($password), 'password', md5($password));
        if (!empty($query)) {
            if ($query[0]->status == "0") {
                $this->session->set_flashdata('login_failed_status', 'You can\'t access your account. Please contact to Administrator');
                redirect(site_url('/admin/'));
            } else {
                $this->session->set_userdata('admin_Id', $query[0]->id);
                if ($remember != "") {
                    $time = (int) (86400 * 30 * 2);
                    $user_cookies = array(
                        'name' => 'username',
                        'value' => $username,
                        'expire' => $time,
                    );
                    $pass_cookies = array(
                        'name' => 'password',
                        'value' => $password,
                        'expire' => $time,
                    );
                    $this->input->set_cookie($user_cookies);
                    $this->input->set_cookie($pass_cookies);
                } else {
                    delete_cookie('username');
                    delete_cookie('password');
                }
                redirect(site_url('/admin/dashboard'), 'refresh');
            }
        } else {
            $this->session->set_flashdata('login_failed', 'Invalid username or password');
            redirect(site_url('/admin/'));
        }
    }

    public function registration() {
        $email = $this->input->post('email');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $query = $this->admin_manager->CheckAlreadyExist($this->table[0], 'username', $username, 'email', $email);
        if (empty($query)) {
            $data = array(
                'username' => $username,
                'email' => $email,
                'password' => md5($password),
            );
            $this->admin_manager->Insert($this->table[0], $data);
            print_r(json_encode(array("mess" => '<div class="alert alert-success"><strong>Success!</strong> You are successfully registered.</div>')));
        } else {
            print_r(json_encode(array("mess" => '<div class="alert alert-danger"><strong>Error!</strong> Username or Email already Exist.Please try another.</div>')));
        }
    }

    public function Settings() {
        /* Create Setting view
         * @var data $data For passing data to setting view
         */
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $data['settings'] = $this->admin_manager->SelectByID($this->table[2], 'id', '1');

            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/settings', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function Save_Credentials() {
        /* This method is used for updating admin credentials
         * @var user $user For getting user from input
         * @var password $pass For getting password from input
         * @var message $message sending reponse to ajax
         */
        $user = $this->input->post('user');
        $pass = $this->input->post('pass');
        $check = $this->admin_manager->CheckAlreadyExistByOne($this->table[2]);
        if ($check[0]->count < 1) {
            $data = array(
                'user' => $user,
                'pass' => $pass,
            );
            $this->admin_manager->Insert($this->table[2], $data);
            $auth = base64_encode($user . ":" . $pass);
            $message = array('message' => 'API credentials has been successfully added', 'authorization' => $auth);
            print_r(json_encode($message));
        } else {
            $where = array(
                'id' => '1'
            );
            $data = array(
                'user' => $user,
                'pass' => $pass,
            );
            $this->admin_manager->Update($this->table[2], $data, $where);
            $auth = base64_encode($user . ":" . $pass);
            $message = array('message' => 'API credentials has been successfully updated', 'authorization' => $auth);
            print_r(json_encode($message));
        }
    }

    public function dispute_data() {
        /* This method is used for Fetch data from API by CURL
         * @var user $user For getting user from input
         * @var password $pass For getting password from input
         * @var message $message sending reponse to ajax
         */
        $cbdisputes = true;
        $cbtransaction = true;
        $cbmerchants = true;
        $cbalerts = true;
        if ($cbdisputes == true) {
            $curl = curl_init();
            $data['settings'] = $this->admin_manager->SelectByID($this->table[2], 'id', '1');
            $authorization = base64_encode($data['settings'][0]->user . ":" . $data['settings'][0]->pass);
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://cbd.warplite.com/apis/cbdisputes?limit=100",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 30,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "authorization: Basic $authorization",
                    "cache-control: no-cache",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $data = json_decode($response);
                $array = array();
                $commonData = array();
                foreach ($data as $d) {
                    $commonData[] = array(
                        'merchantprocessor' => $d->merchantprocessor,
                        'merchantnumber' => $d->merchantnumber,
                        'statusname' => $d->statusname,
                        'cbid' => $d->cbid,
                        'statusid' => $d->statusid,
                        'statusid2' => $d->statusid2,
                        'cbpopulated' => $d->cbpopulated,
                        'infogw' => $d->infogw,
                        'infocrm' => $d->infocrm,
                        'infoshipping' => $d->infoshipping,
                        'cbcodeid' => $d->cbcodeid,
                        'batterylife' => $d->batterylife,
                        'ccbin6' => $d->ccbin6,
                        'adminid' => $d->adminid,
                        'adminid' => $d->adminid,
                        'orderid' => $d->orderid,
                        'itemid' => $d->itemid,
                        'datesubmitted' => $d->datesubmitted,
                        'csvfilename' => $d->csvfilename,
                        'cbonoff' => $d->cbonoff,
                        'cbflag' => $d->cbflag,
                        'cbtype' => $d->cbtype,
                        'cbadminid' => $d->cbadminid,
                        'cbcasenumber' => $d->cbcasenumber,
                        'cbcomments' => $d->cbcomments,
                        'cbdateorder' => $d->cbdateorder,
                        'cbdatedisputed' => $d->cbdatedisputed,
                        'cbdateexpire' => $d->cbdateexpire,
                        'cbdatemerchantdisputed' => $d->cbdatemerchantdisputed,
                        'cbdatewonlost' => $d->cbdatewonlost,
                        'cbdaterefunded' => $d->cbdaterefunded,
                        'cbcurrency' => $d->cbcurrency,
                        'cbamount' => $d->cbamount,
                        'cbtransamount' => $d->cbtransamount,
                        'cbrefundamount' => $d->cbrefundamount,
                        'cbrefundtransid' => $d->cbrefundtransid,
                        'cbreasoncode' => $d->cbreasoncode,
                        'cbreasonmsg' => $d->cbreasonmsg,
                        'cbmerchantreference' => $d->cbmerchantreference,
                        'cborderid' => $d->cborderid,
                        'cboffertype' => $d->cboffertype,
                        'cbproduct' => $d->cbproduct,
                        'cbmerchantnumber' => $d->cbmerchantnumber,
                        'cbmerchantname' => $d->cbmerchantname,
                        'cbcardtype' => $d->cbcardtype,
                        'cbavsstatus' => $d->cbavsstatus,
                        'cbcvvstatus' => $d->cbcvvstatus,
                        'cbclientname' => $d->cbclientname,
                        'cbclientnamefirst' => $d->cbclientnamefirst,
                        'cbclientnamelast' => $d->cbclientnamelast,
                        'cbclientipaddress' => $d->cbclientipaddress,
                        'cbclientaffiliateid' => $d->cbclientaffiliateid,
                        'cbclientsubaffiliateid' => $d->cbclientsubaffiliateid,
                        'cbclientaddress' => $d->cbclientaddress,
                        'cbshippingconfirmation' => $d->cbshippingconfirmation,
                        'cbrma' => $d->cbrma,
                        'cbclientshippingaddress' => $d->cbclientshippingaddress,
                        'cbdateshipped' => $d->cbdateshipped,
                        'cbdatereceived' => $d->cbdatereceived,
                        'cbdatereceived2' => $d->cbdatereceived2,
                        'cbdatereceived3' => $d->cbdatereceived3,
                        'cbshippingreceiver' => $d->cbshippingreceiver,
                        'cdbshippingservice' => $d->cdbshippingservice,
                        'cbtransid' => $d->cbtransid,
                        'cbauthcode' => $d->cbauthcode,
                        'cbrefnumber' => $d->cbrefnumber,
                        'cbbankarn' => $d->cbbankarn,
                        'cbbankcomments' => $d->cbbankcomments,
//                    'cbdisputeletter' => $d->cbdisputeletter,
                    );
                    $array [] = array(
                        'merchantid' => $d->merchantid,
                        'cb_cardnumber' => $d->cbcardnumber,
                        'cbc_namefirst' => $d->cbclientnamefirst,
                        'cbc_namelast' => $d->cbclientnamelast,
                        'cbc_phone' => $d->cbclientphone,
                        'cbc_email' => $d->cbclientemail,
                        'cbc_city' => $d->cbclientcity,
                        'cbc_state' => $d->cbclientstate,
                        'bill_address' => $d->cbclientfullbillingaddress,
                        'cbc_zip' => $d->cbclientzip,
                        'common_data' => serialize($commonData)
                    );
                }
                print_r(json_encode($data, JSON_PRETTY_PRINT));
                $check = $this->admin_manager->CheckAlreadyExistByOne($this->table[3]);
                if ($check[0]->count < 1) {
                    foreach ($array as $arr) {
                        $this->admin_manager->Insert($this->table[3], $arr);
                    }
                } else {
                    foreach ($array as $arr) {
                        $query = $this->admin_manager->SelectByID($this->table[3], 'cb_cardnumber', $arr['cb_cardnumber']);
                        if ($query[0]->cbcardnumber == $arr['cb_cardnumber']) {
                            $where = array(
                                'cb_cardnumber' => $arr['cb_cardnumber']
                            );
                            $this->admin_manager->Update($this->table[3], $arr, $where);
                        } else {
                            $this->admin_manager->Insert($this->table[3], $arr);
                        }
                    }
                }
            }
        }

        if ($cbtransaction == true) {
            $curl = curl_init();
            $data['settings'] = $this->admin_manager->SelectByID($this->table[2], 'id', '1');
            $authorization = base64_encode($data['settings'][0]->user . ":" . $data['settings'][0]->pass);
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://cbd.warplite.com/apis/gwtrans?limit=100",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 30,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "authorization: Basic $authorization",
                    "cache-control: no-cache",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $data = json_decode($response);
                $array = array();
                $commonData = array();
                foreach ($data as $d) {
                    $array [] = array(
                        'recid' => $d->recid,
                        'gwid' => $d->gwid,
                        'merchantid' => $d->merchantid,
                        'merch_processor' => $d->merchantprocessor,
                        'merchant_name' => $d->merchantname,
                        'gw_transdate' => $d->gwtransdate,
                        'gw_transamount' => $d->gwtransamount,
                        'gw_transtypeid' => $d->gwtranstypeid,
                        'gw_transtype' => $d->gwtranstypeid,
                        'gw_transid' => $d->gwtransid,
                        'ccnumber' => $d->ccnumber,
                    );
                }
                print_r(json_encode($data, JSON_PRETTY_PRINT));
                $check = $this->admin_manager->CheckAlreadyExistByOne($this->table[4]);
                if ($check[0]->count < 1) {
                    foreach ($array as $arr) {
                        $this->admin_manager->Insert($this->table[4], $arr);
                    }
                } else {
                    foreach ($array as $arr) {
                        $query = $this->admin_manager->SelectByID($this->table[4], 'ccnumber', $arr['ccnumber']);
                        if ($query[0]->cbcardnumber == $arr['cb_cardnumber']) {
                            $where = array(
                                'ccnumber' => $arr['ccnumber']
                            );
                            $this->admin_manager->Update($this->table[4], $arr, $where);
                        } else {
                            $this->admin_manager->Insert($this->table[4], $arr);
                        }
                    }
                }
            }
        }

        if ($cbalerts == true) {
            $curl = curl_init();
            $data['settings'] = $this->admin_manager->SelectByID($this->table[2], 'id', '1');
            $authorization = base64_encode($data['settings'][0]->user . ":" . $data['settings'][0]->pass);
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://cbd.warplite.com/apis/cbalerts?limit=100",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 30,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "authorization: Basic $authorization",
                    "cache-control: no-cache",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $data = json_decode($response);
                $array = array();
                $commonData = array();
                foreach ($data as $d) {
                    $commonData[] = array(
                        'providername' => $d->providername,
                        'merchantdescriptor' => $d->merchantdescriptor,
                        'merchantnumber' => $d->merchantnumber,
                        'descriptorid' => $d->descriptorid,
                        'descriptor' => $d->descriptor,
                        'desctext' => $d->desctext,
                        'sys5mid' => $d->sys5mid,
                        'outcome' => $d->outcome,
                        'apiID' => $d->apiID,
                        'providerid' => $d->providerid,
                        'statusid' => $d->statusid,
                        'statusid2' => $d->statusid2,
                        'productid' => $d->productid,
                        'itemid' => $d->itemid,
                        'apidate' => $d->apidate,
                        'apidateupdated' => $d->apidateupdated,
                        'apionoff' => $d->apionoff,
                        'apionoff' => $d->apionoff,
                        'apiflagged' => $d->apiflagged,
                        'apialerterid' => $d->apialerterid,
                        'apitransactiondate' => $d->apitransactiondate,
                        'apiamount' => $d->apiamount,
                        'apicurrency' => $d->apicurrency,
                        'apicard' => $d->apicard,
                        'ccbin6' => $d->ccbin6,
                        'apiarn' => $d->apiarn,
                        'apireasoncode' => $d->apireasoncode,
                        'ischargeback' => $d->ischargeback,
                        'requestrefundid' => $d->requestrefundid,
                        'creditgiven' => $d->creditgiven,
                        'creditreqid' => $d->creditreqid,
                        'creditreqnotes' => $d->creditreqnotes,
                        'uploadedsnaps' => $d->uploadedsnaps,
                    );
                    $array [] = array(
                        'merchantid' => $d->merchantid,
                        'merchantname' => $d->merchantname,
                        'customerid' => $d->customerid,
                        'apiID' => $d->apiID,
                        'apiamount' => $d->apiamount,
                        'apicurrency' => $d->apicurrency,
                        'apicard' => str_replace("*", "x", $d->apicard),
                        'apitransactiondate' => $d->apitransactiondate,
                        'apialerterid' => $d->apialerterid,
                        'common_data' => serialize($commonData)
                    );
                }
                print_r(json_encode($data, JSON_PRETTY_PRINT));
                $check = $this->admin_manager->CheckAlreadyExistByOne($this->table[5]);
                if ($check[0]->count < 1) {
                    foreach ($array as $arr) {
                        $this->admin_manager->Insert($this->table[5], $arr);
                    }
                } else {
                    foreach ($array as $arr) {
                        $query = $this->admin_manager->SelectByID($this->table[5], 'apicard', $arr['apicard']);
                        if ($query[0]->cbcardnumber == $arr['apicard']) {
                            $where = array(
                                'apicard' => $arr['apicard']
                            );
                            $this->admin_manager->Update($this->table[5], $arr, $where);
                        } else {
                            $this->admin_manager->Insert($this->table[5], $arr);
                        }
                    }
                }
            }
        }
        if ($cbmerchants == true) {
            $curl = curl_init();
            $data['settings'] = $this->admin_manager->SelectByID($this->table[2], 'id', '1');
            $authorization = base64_encode($data['settings'][0]->user . ":" . $data['settings'][0]->pass);
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://cbd.warplite.com/apis/cbmerchants?limit=100",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 30,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "authorization: Basic $authorization",
                    "cache-control: no-cache",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $data = json_decode($response);
                $array = array();
                $commonData = array();
                foreach ($data as $d) {
                    $commonData[] = array(
                        'usergroupid' => $d->usergroupid,
                        'merchantclientid' => $d->merchantclientid,
                        'merchantownersid' => $d->merchantownersid,
                        'merchantportalscnt' => $d->merchantportalscnt,
                        'merchantapiscnt' => $d->merchantapiscnt,
                        'merchantonoff' => $d->merchantonoff,
                        'merchantflag' => $d->merchantflag,
                        'merchantname' => $d->merchantname,
                        'merchantdba' => $d->merchantdba,
                        'merchantnumber' => $d->merchantnumber,
                        'merchantdescriptor' => $d->merchantdescriptor,
                        'merchantmonthlycap' => $d->merchantmonthlycap,
                        'merchantlastcbdate' => $d->merchantlastcbdate,
                        'merchantcolor' => $d->merchantcolor,
                        'merchantbold' => $d->merchantbold,
                        'merchantdesc' => $d->merchantdesc,
                        'merchanturl' => $d->merchanturl,
                        'merchantphone' => $d->merchantphone,
                        'merchantemail' => $d->merchantemail,
                        'merchantaddr' => $d->merchantaddr,
                        'merchantugroupids' => $d->merchantugroupids,
                        'merchantprocessorpoc' => $d->merchantprocessorpoc,
                        'merchantprocessorpoc' => $d->merchantprocessorpoc,
                        'merchantprocessorpocemail' => $d->merchantprocessorpocemail,
                        'merchantprocessorfax' => $d->merchantprocessorfax,
                        'statusid3' => $d->statusid3,
                        'merchantprocessorurl' => $d->merchantprocessorurl,
                        'merchantprocessoruser' => $d->merchantprocessoruser,
                        'merchantprocessorpass' => $d->merchantprocessorpass,
                        'merchantnmionoff' => $d->merchantnmionoff,
                        'merchantnmidownload' => $d->merchantnmidownload,
                        'merchantnmithrottle' => $d->merchantnmithrottle,
                        'merchantnmiavgtrans' => $d->merchantnmiavgtrans,
                        'merchantnmiuser' => $d->merchantnmiuser,
                        'merchantnmipass' => $d->merchantnmipass,
                        'merchantnmidelimiter' => $d->merchantnmidelimiter,
                        'merchantprocessornotes' => $d->merchantprocessornotes,
                        'merchantcsvselections' => $d->merchantcsvselections,
                        'merchantsort' => $d->merchantsort,
                        'alerteronoff' => $d->alerteronoff,
                        'senderid' => $d->senderid,
                        'alerteremails' => $d->alerteremails,
                        'alerteremails' => $d->alerteremails,
                        'alertermessage' => $d->alertermessage,
                        'tmpflagger' => $d->tmpflagger,
                        'tmpflaggerdate' => $d->tmpflaggerdate,
                        'processorname' => $d->processorname,
                        'companygroupname' => $d->companygroupname,
                        'companyname' => $d->companyname,
                        'notespopped' => $d->notespopped,
                        'portalsexist' => $d->portalsexist,
                        'apisexist' => $d->apisexist,
                    );
                    $array [] = array(
                        'merchantid' => $d->merchantid,
                        'customerid' => $d->customerid,
                        'processorid' => $d->processorid,
                        'merchantname' => $d->merchantname,
                        'merchantprocessor' => $d->merchantprocessor,
                        'merchantlastcbdate' => $d->merchantlastcbdate,
                        'merchantphone' => $d->merchantphone,
                        'merchantemail' => $d->merchantemail,
                        'merchantaddr' => $d->merchantaddr,
                        'common_data' => serialize($commonData)
                    );
                }
                print_r(json_encode($data, JSON_PRETTY_PRINT));
                $check = $this->admin_manager->CheckAlreadyExistByOne($this->table[6]);
                if ($check[0]->count < 1) {
                    foreach ($array as $arr) {
                        $this->admin_manager->Insert($this->table[6], $arr);
                    }
                } else {
                    foreach ($array as $arr) {
                        $query = $this->admin_manager->SelectByID($this->table[6], 'apicard', $arr['apicard']);
                        if ($query[0]->cbcardnumber == $arr['apicard']) {
                            $where = array(
                                'apicard' => $arr['apicard']
                            );
                            $this->admin_manager->Update($this->table[6], $arr, $where);
                        } else {
                            $this->admin_manager->Insert($this->table[6], $arr);
                        }
                    }
                }
            }
        }
    }

    public function Logout() {
        /* This method is used for logout
         */
        $this->session->unset_userdata('admin_Id');
        $this->session->unset_userdata('credentials');
        redirect(site_url('/admin/'));
    }

    public function Disputed_APIData_View() {
        /* Create APIData view
         * @var data $data For passing data to api_data view
         */
        $adminID = $this->session->userdata('admin_Id');
        $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
        $data['Apidata'] = $this->admin_manager->SelectAll($this->table[3]);
        $this->load->view('administrator/header', $data);
        $this->load->view('administrator/sidebar');
        $this->load->view('administrator/dispute', $data);
        $this->load->view('administrator/footer');
    }

    public function Transaction_APIData_View() {
        /* Create APIData view
         * @var data $data For passing data to api_data view
         */
        $adminID = $this->session->userdata('admin_Id');
        $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $this->current_user);
        $data['Apidata'] = array();
        $data['Testdata'] = $this->admin_manager->SelectWithJoinTrans($this->current_user);
        $data['all_customers'] = $this->admin_manager->SelectAll($this->table[0]);
        foreach ($data['Testdata'] as $Apidata) {
            $ccbin = $Apidata->cc_bin;
            $card_num = $Apidata->card_num;
            $dataMatch = date('Y-m-d', strtotime($Apidata->date_of_tran));
            $cardMatchEnd = substr($card_num, 11, 15);
            $cardMatchStart = substr($card_num, 0, 6);
            $alerts = $this->admin_manager->SelectForMatchOnlyAlert($this->table[5], $cardMatchEnd);
            if ($ccbin !== 0 && !empty($ccbin)) {
                $result = $this->admin_manager->SelectForMatchAlertCB($this->table[7], $dataMatch, $cardMatchEnd);
//                e cho "SELECT * FROM". $this->table[7]. "WHERE `received_date`='$dataMatch' AND `cardholder_number` LIKE '%$cardMatchEnd'";
                if (!empty($result)) {
                    $data['Apidata'][] = array(
                        'mer_type' => $Apidata->mer_type,
                        'username' => $Apidata->username,
                        'transaction_id' => $Apidata->transaction_id,
                        'processor_id' => $Apidata->processor_id,
                        'user_id' => $Apidata->user_id,
                        'first_name' => $Apidata->first_name,
                        'last_name' => $Apidata->last_name,
                        'email' => $Apidata->email,
                        'ccbin6' => $Apidata->cc_bin,
                        'date_of_tran' => $Apidata->date_of_tran,
                        'card_num' => $Apidata->card_num,
                        'matched' => 'Matched:',
                        'a_match' => (!empty($alerts)) ? "Matched:" : "Not Matched"
                    );
                } else {
                    $data['Apidata'][] = array(
                        'mer_type' => $Apidata->mer_type,
                        'username' => $Apidata->username,
                        'transaction_id' => $Apidata->transaction_id,
                        'processor_id' => $Apidata->processor_id,
                        'user_id' => $Apidata->user_id,
                        'first_name' => $Apidata->first_name,
                        'last_name' => $Apidata->last_name,
                        'email' => $Apidata->email,
                        'ccbin6' => $Apidata->cc_bin,
                        'date_of_tran' => $Apidata->date_of_tran,
                        'card_num' => $Apidata->card_num,
                        'matched' => 'Not Matched',
                        'a_match' => (!empty($alerts)) ? "Matched:" : "Not Matched"
                    );
                }
            } else {
                if (!empty($result)) {
                    $data['Apidata'][] = array(
                        'mer_type' => $Apidata->mer_type,
                        'username' => $Apidata->username,
                        'transaction_id' => $Apidata->transaction_id,
                        'processor_id' => $Apidata->processor_id,
                        'user_id' => $Apidata->user_id,
                        'first_name' => $Apidata->first_name,
                        'last_name' => $Apidata->last_name,
                        'email' => $Apidata->email,
                        'ccbin6' => $Apidata->cc_bin,
                        'date_of_tran' => $Apidata->date_of_tran,
                        'card_num' => $Apidata->card_num,
                        'matched' => 'Matched:',
                        'a_match' => (!empty($alerts)) ? "Matched:" : "Not Matched"
                    );
                } else {
                    $data['Apidata'][] = array(
                        'mer_type' => $Apidata->mer_type,
                        'username' => $Apidata->username,
                        'transaction_id' => $Apidata->transaction_id,
                        'processor_id' => $Apidata->processor_id,
                        'user_id' => $Apidata->user_id,
                        'first_name' => $Apidata->first_name,
                        'last_name' => $Apidata->last_name,
                        'email' => $Apidata->email,
                        'ccbin6' => $Apidata->cc_bin,
                        'date_of_tran' => $Apidata->date_of_tran,
                        'card_num' => $Apidata->card_num,
                        'matched' => 'Not Matched',
                        'a_match' => (!empty($alerts)) ? "Matched:" : "Not Matched"
                    );
                }
            }
        }
        $data['transALL'] = $data['Apidata'];
        $this->load->view('administrator/header', $data);
        $this->load->view('administrator/sidebar');
        $this->load->view('administrator/transaction', $data);
        $this->load->view('administrator/footer');
    }

    public function Alert_APIData_View() {
        /* Create APIData view
         * @var data $data For passing data to api_data view
         */
        $adminID = $this->session->userdata('admin_Id');
        $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
        $data['Apidata'] = $this->admin_manager->SelectAll($this->table[5]);
        $this->load->view('administrator/header', $data);
        $this->load->view('administrator/sidebar');
        $this->load->view('administrator/alert', $data);
        $this->load->view('administrator/footer');
    }

    public function Merchant_APIData_View() {
        /* Create APIData view
         * @var data $data For passing data to api_data view
         */
        $adminID = $this->session->userdata('admin_Id');
        $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
        $data['Apidata'] = $this->admin_manager->SelectAll($this->table[6]);
        $this->load->view('administrator/header', $data);
        $this->load->view('administrator/sidebar');
        $this->load->view('administrator/merchant', $data);
        $this->load->view('administrator/footer');
    }

    public function admin_settings() {
        /* Create Admin Settings view
         * @var data $data For passing data to admin_setting view
         */
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/admin_setting', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function save_setting() {
        /* This method is used for Saving setting of admin
         * @var user $username For getting user from input
         * @var password $password For getting password from input
         * @var message $message sending reponse to ajax
         */
        $username = $this->input->post('user');
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $check = $this->admin_manager->CheckAlreadyExistByOne($this->table[0]);
        if ($check[0]->count < 1) {
            $data = array();
            if (!empty($password)) {
                $data = array(
                    'username' => $username,
                    'email' => $email,
                    'password' => md5($password),
                );
            } else {
                $data = array(
                    'username' => $username,
                    'email' => $email,
                );
            }
            $this->admin_manager->Insert($this->table[0], $data);
            $message = array('message' => 'Admin user has been successfully inserted.');
            print_r(json_encode($message));
        } else {
            $data = array();
            if (!empty($password)) {
                $data = array(
                    'username' => $username,
                    'email' => $email,
                    'password' => md5($password),
                );
            } else {
                $data = array(
                    'username' => $username,
                    'email' => $email,
                );
            }
            $where = array('id' => '1');
            $this->admin_manager->Update($this->table[0], $data, $where);
            $message = array('message' => 'Admin user has been successfully updated.');
            print_r(json_encode($message));
        }
    }

    public function importCSV() {
        /* Create importCSV view
         * @var data $data For passing data to admin_setting view
         */
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/import_csv', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function uploadCSV() {
        $this->load->view('administrator/csv_mapping_style');
        $importNow = site_url('/admin/save_now');
        $adminID = $this->session->userdata('admin_Id');
        $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
//        $this->load->view('administrator/header', $data);
        $path = $_FILES['file']['name'];
        $filename = $_FILES["file"]["tmp_name"];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $array = explode('.', $ext);
        $extension = end($array);
        $dbcolunm = array();
        $data['dbcolumns'] = array();
        $querys = $this->admin_manager->ShowColumns($this->table[7]);
        foreach ($querys as $query) {
            $dbcolunm[] = $query->Field;
        }
        $this->session->set_userdata('dbcolumns', $dbcolunm);
        $varArray = array();
        if ($extension == "csv") {
            if ($_FILES["file"]["size"] > 0) {
                $row = 1;
                if (($handle = fopen($filename, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $num = count($data);
                        if ($row == 1) {
                            echo "<div class='mappingarea'><form action='" . $importNow . "' method='POST'>";
                            echo "<h2>CSV Mapping</h2>";
                            $cc = 0;

                            foreach ($dbcolunm as $d) {
                                echo "<span class='csv_handle'>" . $d . " <address style='font-size:11px;float:right;'>(db column)</address></span>";
                                if ($cc < 2) {
                                    echo "<select class='csv_header_sec' name='csv_header_keys[]' disabled>";
                                    foreach ($data as $key => $d) {
                                        echo "<option value=''>id</option>";
                                    }
                                    echo "</select>";
                                } else {
                                    echo "<select class='csv_header_sec' name='csv_header_keys[]'>";
                                    foreach ($data as $key => $d) {
                                        echo "<option value='" . $key . "'>" . $d . "</option>";
                                    }
                                    echo "</select>";
                                }
                                $cc++;
                            }
                            echo "<input type='submit' class='subcsv' value='Save & Compile CSV'/> <input type='submit' class='subcsv' value='Proceed'/></form>";
                            echo "</div>";
                            $this->session->set_userdata('csv_header', $data);
                        } else {
                            $varArray[] = $data;
                        }
                        $row++;
                    }
                    $this->session->set_userdata('csv_body', $varArray);
                    fclose($handle);
                }
            }
        } else {
            echo "The MIMETYPE ." . $extension . " is not allowed";
        }
    }

    public function testXmlQuery($username, $password, $constraints) {
// transactionFields has all of the fields we want to validate
// in the transaction tag in the XML output
        $transactionFields = array(
            'transaction_id',
            'transaction_type',
            'condition',
            'order_id',
            'authorization_code',
            'ponumber',
            'order_description',
            'avs_response',
            'csc_response',
            'first_name',
            'last_name',
            'address_1',
            'address_2',
            'company',
            'city',
            'state',
            'postal_code',
            'country',
            'email',
            'phone',
            'fax',
            'cell_phone',
            'customertaxid',
            'customerid',
            'website',
            'shipping_last_name',
            'shipping_address_1',
            'shipping_address_2',
            'shipping_company',
            'shipping_city',
            'shipping_state',
            'shipping_postal_code',
            'shipping_country',
            'shipping_email',
            'shipping_carrier',
            'tracking_number',
            'cc_number',
            'cc_hash',
            'cc_exp',
            'cc_bin',
            'avs_response',
            'csc_response',
            'cardholder_auth',
            'processor_id',
            'tax');
// actionFields is used to validate the XML tags in the
// action element
        $actionFields = array(
            'amount',
            'action_type',
            'date',
            'success',
            'ip_address',
            'source',
            'response_text'
        );

        $mycurl = curl_init();
        $postStr = 'username=' . $username . '&password=' . $password . $constraints;
        $url = "https://secure.nmi.com/api/query.php?" . $postStr;
        curl_setopt($mycurl, CURLOPT_URL, $url);
        curl_setopt($mycurl, CURLOPT_RETURNTRANSFER, 1);
        $responseXML = curl_exec($mycurl);
        curl_close($mycurl);

        $testXmlSimple = new SimpleXMLElement($responseXML);

        if (!isset($testXmlSimple->transaction)) {
            throw new NmExUser('No transactions returned');
        }

        $transNum = 1;
        foreach ($testXmlSimple->transaction as $transaction) {
            foreach ($transactionFields as $xmlField) {
                if (!isset($transaction->{$xmlField}[0])) {
                    throw new NmExUser('Error in transaction_id:' . $transaction->transaction_id[0] . ' id  Transaction tag is missing  field ' . $xmlField);
                }
            }
            if (!isset($transaction->action)) {
                throw new nmExUser('Error, Action tag is missing from transaction_id ' . $transaction->transaction_id[0]);
            }

            $actionNum = 1;
            foreach ($transaction->action as $action) {
                foreach ($actionFields as $xmlField) {
                    if (!isset($action->{$xmlField}[0])) {
                        throw new NmExUser('Error with transaction_id' . $transaction->transaction_id[0] . '
                                        Action number ' . $actionNum . ' Action tag is missing field ' . $xmlField);
                    }
                }
                $actionNum++;
            }
            $transNum++;
        }
        return;
    }

    public function SecureNMI($username, $password, $filters) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://secure.nmi.com/api/query.php?username=" . $username . "&password=" . $password . $filters,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 1000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function add_merchant() {
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $data['settings'] = $this->admin_manager->SelectByID($this->table[2], 'id', '1');

            $data['allcustom'] = $this->admin_manager->SelectAll($this->table[0]);

            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/merchant', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function all_merchant() {
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['allmerchants_nmi'] = '';
            $data['allmerchants_auth'] = '';
            $data['allmerchants_sage'] = '';
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $data['settings'] = $this->admin_manager->SelectByID($this->table[2], 'id', '1');
            if ($adminID == "1") {
                $data['allmerchants_nmi'] = $this->admin_manager->SelectWithJoin('nmi');
                $data['allmerchants_auth'] = $this->admin_manager->SelectWithJoin('authorize');
                $data['allmerchants_sage'] = $this->admin_manager->SelectWithJoin('sage');
            } else {
                $data['allmerchants_nmi'] = $this->admin_manager->SelectWithJoinWHERE('nmi', $this->current_user);
                $data['allmerchants_auth'] = $this->admin_manager->SelectWithJoinWHERE('authorize', $this->current_user);
                $data['allmerchants_sage'] = $this->admin_manager->SelectWithJoinWHERE('sage', $this->current_user);
            }
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/all_merchant', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function save_merchant() {
        $user = $this->input->post('user');
        $password = $this->input->post('password');
        $endpoint = $this->input->post('endpoint');
        $merchant_type = $this->input->post('merchant_type');
        $assign_user = $this->input->post('assign_user');
        $env_pro = $this->input->post('env_pro');
        $assign_user = $this->input->post('assign_user');

        $apiLogin = (!empty($this->input->post('apiLogin'))) ? $this->input->post('apiLogin') : "";
        $trans_key = (!empty($this->input->post('trans_key'))) ? $this->input->post('trans_key') : "";

        $client_id = (!empty($this->input->post('client_id'))) ? $this->input->post('client_id') : "";
        $client_secret = (!empty($this->input->post('client_secret'))) ? $this->input->post('client_secret') : "";
        $sign_secret = (!empty($this->input->post('sign_secret'))) ? $this->input->post('sign_secret') : "";
        $merchant_type = $this->input->post('merchant_type');
        if ($merchant_type !== "nmi") {
            $result = $this->validateBatchList($apiLogin, $trans_key, $endpoint);
            if ($result['messages']['resultCode'] == "Error") {
                $arrayData = array(
                    'loginId' => $apiLogin,
                    'transKey' => $trans_key,
                    'endpoint' => $endpoint,
                    'assing_user' => $assign_user,
                    'env' => $env_pro,
                    'mer_type' => $merchant_type
                );
                $this->session->set_userdata('credentials', $arrayData);
                $this->session->set_flashdata("transError", $result['messages']['message']['text']);
                redirect(site_url('admin/add_merchant'));
            }
        }
        if ($merchant_type == "nmi") {
            $arrayData = array(
                'username' => $user,
                'password' => $password,
                'endpoint' => $endpoint,
                'assing_user' => $assign_user,
                'env' => $env_pro,
                'mer_type' => $merchant_type
            );
            $this->session->set_userdata('credentials', $arrayData);
            $query = $this->admin_manager->CheckAlreadyExistAND($this->table[6], 'm_user', $user, 'm_pass', $password);
            $data = array(
                'user_id' => (!empty($assign_user)) ? $assign_user : $this->current_user,
                'mer_type' => $merchant_type,
                'm_user' => $user,
                'm_pass' => $password,
                'm_end_point' => $endpoint,
            );
            $this->admin_manager->Insert($this->table[6], $data);
            $this->session->set_flashdata('msg', '<div class="alert clearfix alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Merchant has been registered</div>');
        }
        if ($merchant_type == "authorize") {
            $arrayData = array(
                'loginId' => $apiLogin,
                'transKey' => $trans_key,
                'endpoint' => $endpoint,
                'assing_user' => $assign_user,
                'env' => $env_pro,
                'mer_type' => $merchant_type
            );
            $this->session->set_userdata('credentials', $arrayData);
            $data = array(
                'user_id' => (!empty($assign_user)) ? $assign_user : $this->current_user,
                'mer_type' => $merchant_type,
                'api_login_id' => $apiLogin,
                'api_tran_key' => $trans_key,
                'm_end_point' => $endpoint,
            );
            $this->admin_manager->Insert($this->table[6], $data);
            $this->session->set_flashdata('msg', '<div class="alert clearfix alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Merchant has been registered</div>');
        }
        if ($merchant_type == "sage") {
            $arrayData = array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'sign_secret' => $sign_secret,
                'endpoint' => $endpoint,
                'assing_user' => $assign_user,
                'env' => $env_pro,
                'mer_type' => $merchant_type
            );
            $this->session->set_userdata('credentials', $arrayData);
            $query = $this->admin_manager->CheckAlreadyExistANDWhere($this->table[6], 'api_login_id', $apiLogin, 'api_tran_key', $trans_key, 'api_batch_id', $batch_id);
            if (!empty($query)) {
                $this->session->set_flashdata('msg', '<div class="alert clearfix alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Merchant already exist</div>');
            } else {
                $data = array(
                    'user_id' => (!empty($assign_user)) ? $assign_user : $this->current_user,
                    'mer_type' => $merchant_type,
                    'client_id' => $client_id,
                    'client_secret' => $trans_key,
                    'sign_secret' => $sign_secret,
                    'm_end_point' => $endpoint,
                );
                $this->admin_manager->Insert($this->table[6], $data);
                $this->session->set_flashdata('msg', '<div class="alert clearfix alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Merchant has been registered</div>');
            }
        }
        redirect(site_url('admin/add_merchant'));
    }

    public function view_merchant($id) {
        echo "<h1 class='load'>Loading...</h1>";
//        sleep(2);
        $query = $this->admin_manager->SelectByID($this->table[6], 'id', $id);
        $dates = date('Ymd', strtotime('-1 day')) . "000000";
        $constraints = "&start_date=" . $dates;
        $result = $this->SecureNMI($query[0]->m_user, $query[0]->m_pass, $constraints);
        $xml = simplexml_load_string($result) or die("Error: Cannot create object");
        $count = 1;
        foreach ($xml->transaction as $transaction) {
            $transaction_id = $transaction->transaction_id;
            $first_name = $transaction->first_name;
            $last_name = $transaction->last_name;
            $address_1 = $transaction->address_1;
            $address_2 = $transaction->address_2;
            $company = $transaction->company;
            $city = $transaction->city;
            $state = $transaction->state;
            $postal_code = $transaction->postal_code;
            $country = $transaction->country;
            $email = $transaction->email;
            $phone = $transaction->phone;
            $fax = $transaction->fax;
            $ship_address1 = $transaction->shipping_address_1;
            $ship_address2 = $transaction->shipping_address_2;
            $shipping_city = $transaction->shipping_city;
            $shipping_state = $transaction->shipping_state;
            $shipping_postal_code = $transaction->shipping_postal_code;
            $shipping_country = $transaction->shipping_country;
            $processorID = $transaction->processor_id;
            $cc_num = $transaction->cc_number;
            $tax = $transaction->tax;
            $currency = $transaction->currency;
            $merchant_field = $transaction->merchant_defined_field;
            $cc_type = $transaction->cc_type;
            $cc_bin = $transaction->cc_bin;
            $amount = $transaction->action->amount;
            $dateOfTran = $transaction->action->date;
            $querxy = $this->admin_manager->SelectByID($this->table[4], 'transaction_id', $transaction->transaction_id);
            if (empty($querxy)) {
                $data = array(
                    'user_id' => $this->current_user,
                    'mer_id' => $id,
                    'mer_type' => 'nmi',
                    'transaction_id' => $transaction_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'address1' => $address_1,
                    'address2' => $address_2,
                    'company' => $company,
                    'city' => $city,
                    'state' => $state,
                    'postalcode' => $postal_code,
                    'country' => $country,
                    'email' => $email,
                    'phone' => $phone,
                    'fax' => $fax,
                    'ship_address1' => $ship_address1,
                    'ship_address2' => $ship_address2,
                    'ship_city' => $shipping_city,
                    'ship_state' => $shipping_state,
                    'ship_postal_code' => $shipping_postal_code,
                    'ship_country' => $shipping_country,
                    'processor_id' => $processorID,
                    'card_num' => $cc_num,
                    'tax' => $tax,
                    'currency' => $currency,
                    'merchant_field' => $merchant_field,
                    'cc_bin' => $cc_bin,
                    'cc_type' => $cc_type,
                    'amount' => $amount,
                    'date_of_tran' => $dateOfTran,
                    'date_of_tran2' => date('Y-m-d', strtotime($dateOfTran)),
                );
                $this->admin_manager->Insert($this->table[4], $data);
            }
            $len = count($xml->transaction);
            if ($len == $count) {
                redirect(site_url('admin/all_merchant'));
            }
            $count++;
        }
    }

    public function view_data($merType) {
        $mer_type = $merType;
        $transactionId = $this->uri->segment(4);
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['xml'] = '';
            $data['transactionID'] = '';
            $data['transdata'] = array();
            $data['mer_type'] = $mer_type;
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $data['settings'] = $this->admin_manager->SelectByID($this->table[2], 'id', '1');
            $data['allmerchants'] = $this->admin_manager->SelectByID($this->table[6], 'user_id', $this->current_user);
            $data['db_data'] = $this->admin_manager->SelectByID($this->table[4], 'transaction_id', $transactionId);
            $data['cb_data'] = array();
            $data['alert_data'] = array();
            if ($mer_type == "nmi") {
                $data['transdata'] = $this->admin_manager->SelectByID($this->table[4], 'transaction_id', $transactionId);
                $cardnum = $data['transdata'][0]->card_num;
                $cardMatchEnd = substr($cardnum, 13, 15);
                $cardMatchStart = substr($cardnum, 0, 6);
                $ccbin6 = $data['transdata'][0]->cc_bin;
                $dataMatch = date('Y-m-d', strtotime($data['transdata'][0]->date_of_tran));
                $data['cb_data'] = array();
                $data['alert_data'] = array();
                if ($ccbin6 !== 0 && !empty($ccbin6)) {
                    $data['cb_data'] = $this->admin_manager->SelectForMatchAlertCB($this->table[7], $dataMatch, $cardMatchEnd);
                    $data['alert_data'] = $this->admin_manager->SelectForAlertMatch($transactionId);
//                    $data['alert_data'] = $this->admin_manager->SelectForAlertMatch($this->table[5], $dataMatch, $cardMatchEnd);
                } else {
                    $data['cb_data'] = $this->admin_manager->SelectForMatchAlertCB($this->table[7], $dataMatch, $cardMatchEnd);
                    $data['alert_data'] = $this->admin_manager->SelectForAlertMatch($transactionId);
                }
                $this->load->view('administrator/header', $data);
                $this->load->view('administrator/sidebar');
                $this->load->view('administrator/view_data', $data);
                $this->load->view('administrator/footer');
            }

            if ($mer_type == "authorize") {
                $data['transdata'] = $this->admin_manager->SelectByID($this->table[4], 'transaction_id', $transactionId);
                $cardnum = $data['transdata'][0]->card_num;
                $cardMatchEnd = substr($cardnum, 12, 15);
                $cardMatchStart = substr($cardnum, 0, 6);
                $data['cb_data'] = $this->admin_manager->SelectForMatchAlertCB($this->table[7], $cardMatchStart, $cardMatchEnd);
                $this->load->view('administrator/header', $data);
                $this->load->view('administrator/sidebar');
                $this->load->view('administrator/view_data_auth', $data);
                $this->load->view('administrator/footer');
            }
        } else {
            redirect(site_url('/admin/'));
        }
    }

//    public function view_data($merType) {
//        $mer_type = $merType;
//        $transactionId = $this->uri->segment(4);
//        if ($this->session->userdata('admin_Id')) {
//            $adminID = $this->session->userdata('admin_Id');
//            $data['xml'] = '';
//            $data['transactionID'] = '';
//            $data['mer_type'] = $mer_type;
//            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
//            $data['settings'] = $this->admin_manager->SelectByID($this->table[2], 'id', '1');
//            $data['allmerchants'] = $this->admin_manager->SelectByID($this->table[6], 'user_id', $this->current_user);
//            $data['db_data'] = $this->admin_manager->SelectByID($this->table[4], 'transaction_id', $transactionId);
//            if ($mer_type == "nmi") {
//                $userID = $this->uri->segment(5);
//
//                $transactionID = $transactionId;
//                $constraints = "&transaction_id=" . $transactionID;
//                $query = '';
//                if ($this->current_user == "1") {
//                    $query = $this->admin_manager->CheckAlreadyExistAND($this->table[6], 'user_id', $userID, 'mer_type', $mer_type);
//                } else {
//                    $query = $this->admin_manager->CheckAlreadyExistAND($this->table[6], 'user_id', $userID, 'mer_type', $mer_type);
//                }
//
//                $result = $this->SecureNMI($query[0]->m_user, $query[0]->m_pass, $constraints);
//                $data['xml'] = simplexml_load_string($result) or die("Error: Cannot create object");
//                $data['transactionID'] = $transactionID;
//                $this->load->view('administrator/header', $data);
//                $this->load->view('administrator/sidebar');
//                $this->load->view('administrator/view_data', $data);
//                $this->load->view('administrator/footer');
//            }
//            if ($mer_type == "authorize") {
//                $userID = $this->uri->segment(5);
//                $transactionID = $transactionId;
//                $constraints = "&transaction_id=" . $transactionID;
//                $query = $this->admin_manager->CheckAlreadyExistAND($this->table[6], 'user_id', $userID, 'mer_type', $mer_type);
//                $data['transactionID'] = $transactionID;
//                $APILOGIN = $query[0]->api_login_id;
//                $APITRANSKEY = $query[0]->api_tran_key;
//                $xmlContent = '<getTransactionDetailsRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
//                            <merchantAuthentication>
//                                 <name>' . $APILOGIN . '</name>
//                                 <transactionKey>' . $APITRANSKEY . '</transactionKey>
//                            </merchantAuthentication>
//                            <transId>' . $transactionID . '</transId>
//                        </getTransactionDetailsRequest>';
//                $data['response'] = $this->curl_request($xmlContent, 'xml', $this->Endpoint);
//
//                $this->load->view('administrator/header', $data);
//                $this->load->view('administrator/sidebar');
//                $this->load->view('administrator/view_data_auth', $data['response']);
//                $this->load->view('administrator/footer');
//            }
//        } else {
//            redirect(site_url('/admin/'));
//        }
//    }

    public function makeAlert() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cbd.warplite.com/apis/cbalerts?limit=500",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic TEg5eWQ4bnNQYmFVS0hwOmIyZEtuZUVBTndnN3RiS1o0MUZFdE5VRGE=",
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $results = json_decode($response);
            $count = 0;
            foreach ($results as $result) {
                $cardMatchEnd = substr($result->apicard, 12, 15);
                $cardMatchStart = substr($result->apicard, 0, 6);
                $data = array(
                    'merchantid' => $result->merchantid,
                    'merchantname' => $result->merchantname,
                    'customerid' => $result->customerid,
                    'apiID' => $result->apiID,
                    'apiamount' => $result->apiamount,
                    'apicurrency' => $result->apicurrency,
                    'apicard' => $result->apicard,
                    'apitransactiondate' => $result->apitransactiondate,
                    'apialerterid' => $result->apialerterid,
                    'common_data' => serialize($results),
                );
                if ($result->ccbin6 !== 0) {
                    $query = $this->admin_manager->SelectForMatchAlert($this->table[4], $result->ccbin6, $cardMatchEnd, '0');
                    if (!empty($query)) {

                        $data = array(
                            'transaction_ID' => $query[0]->transaction_id,
                            'first_name' => $query[0]->first_name,
                            'last_name' => $query[0]->first_name,
                            'address1' => $query[0]->address1,
                            'address2' => $query[0]->address2,
                            'email' => $query[0]->email,
                            'city' => $query[0]->city,
                            'state' => $query[0]->state,
                            'common_data' => serialize($result)
                        );
                        $commondata = serialize($result);
                        $this->Mail_fnc('ali@kingdom-vision.com', 'saad@kingdom-vision.co.uk', 'testing', $data);
                    }
                } else {
                    $query = $this->admin_manager->SelectForMatchAlert($this->table[4], $cardMatchStart, $cardMatchEnd, '0');
                    if (!empty($query[0])) {
                        $data = array(
                            'transaction_ID' => $query[0]->transaction_id,
                            'first_name' => $query[0]->first_name,
                            'last_name' => $query[0]->last_name,
                            'address1' => $query[0]->address1,
                            'address2' => $query[0]->address2,
                            'email' => $query[0]->email,
                            'city' => $query[0]->city,
                            'state' => $query[0]->state,
                            'common_data' => serialize($result)
                        );
                        $commondata = serialize($result);
                        $this->Mail_fnc('ali@kingdom-vision.com', 'saad@kingdom-vision.co.uk', 'testing', $data);
                    }
                }
                $count++;
            }
        }
    }

    public function Mail_fnc($to, $from, $subject, $data) {

        $htmlContent = $this->admin_manager->selectAll($this->table[10]);
        $Content = str_replace("{{Name}}", $data['first_name'], $htmlContent);
        $Content .= str_replace("{{TransactionID}}", $data['transaction_ID'], $htmlContent);
        $Content .= str_replace("{{FirstName}}", $data['first_name'], $htmlContent);
        $Content .= str_replace("{{LastName}}", $data['last_name'], $htmlContent);
        $Content .= str_replace("{{Address1}}", $data['address1'], $htmlContent);
        $Content .= str_replace("{{Address2}}", $data['address2'], $htmlContent);
        $Content .= str_replace("{{Email}}", $data['email'], $htmlContent);
        $Content .= str_replace("{{City}}", $data['city'], $htmlContent);
        $Content .= str_replace("{{State}}", $data['state'], $htmlContent);

        $html = '<!DOCTYPE html>';
        $html .= '<html>';
        $html .= $Content;
        $html .= '</html>';
        $this->email
                ->from($from, 'Example Inc.')
                ->to($to)
                ->subject($subject)
                ->message($html)
                ->set_mailtype('html');
        $this->email->send();
        redirect(site_url('/admin/all_merchant'));
    }

    public function view_transaction($id) {
        if ($this->session->userdata('admin_Id')) {
            $mer_id = $id;
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $data['settings'] = $this->admin_manager->SelectByID($this->table[2], 'id', '1');
            $MultiData = array(
                'mer_id' => $mer_id,
            );
            $data['all_customers'] = $this->admin_manager->SelectAll($this->table[0]);
            $data['Apidata'] = array();
            $data['Testdata'] = $this->admin_manager->SelectWithJoinTransWhere($mer_id);

            if (!empty($data['Testdata'])) {
                foreach ($data['Testdata'] as $Apidata) {
                    $ccbin = $Apidata->cc_bin;
                    $card_num = $Apidata->card_num;
                    $dataMatch = date('Y-m-d', strtotime($Apidata->date_of_tran));
                    $cardMatchEnd = substr($card_num, 11, 15);
                    $cardMatchStart = substr($card_num, 0, 6);
                    $alerts = $this->admin_manager->SelectForMatchOnlyAlert($this->table[5], $cardMatchEnd);
                    if ($ccbin !== 0 && !empty($ccbin)) {
                        $result = $this->admin_manager->SelectForMatchAlertCB($this->table[7], $dataMatch, $cardMatchEnd);
                        if (!empty($result)) {
                            $data['Apidata'][] = array(
                                'mer_type' => $Apidata->mer_type,
                                'username' => $Apidata->username,
                                'transaction_id' => $Apidata->transaction_id,
                                'processor_id' => $Apidata->processor_id,
                                'user_id' => $Apidata->user_id,
                                'first_name' => $Apidata->first_name,
                                'last_name' => $Apidata->last_name,
                                'email' => $Apidata->email,
                                'ccbin6' => $Apidata->cc_bin,
                                'date_of_tran' => $Apidata->date_of_tran,
                                'card_num' => $Apidata->card_num,
                                'matched' => 'Matched',
                                'a_match' => (!empty($alerts)) ? "Matched:" : "Not Matched"
                            );
                        } else {
                            $data['Apidata'][] = array(
                                'mer_type' => $Apidata->mer_type,
                                'username' => $Apidata->username,
                                'transaction_id' => $Apidata->transaction_id,
                                'processor_id' => $Apidata->processor_id,
                                'user_id' => $Apidata->user_id,
                                'first_name' => $Apidata->first_name,
                                'last_name' => $Apidata->last_name,
                                'email' => $Apidata->email,
                                'ccbin6' => $Apidata->cc_bin,
                                'date_of_tran' => $Apidata->date_of_tran,
                                'card_num' => $Apidata->card_num,
                                'matched' => 'Not Matched',
                               'a_match' => (!empty($alerts)) ? "Matched:" : "Not Matched"
                            );
                        }
                    } else {
                        if (!empty($result)) {
                            $data['Apidata'][] = array(
                                'mer_type' => $Apidata->mer_type,
                                'username' => $Apidata->username,
                                'transaction_id' => $Apidata->transaction_id,
                                'processor_id' => $Apidata->processor_id,
                                'user_id' => $Apidata->user_id,
                                'first_name' => $Apidata->first_name,
                                'last_name' => $Apidata->last_name,
                                'email' => $Apidata->email,
                                'ccbin6' => $Apidata->cc_bin,
                                'date_of_tran' => $Apidata->date_of_tran,
                                'card_num' => $Apidata->card_num,
                                'matched' => 'Matched:',
                                'a_match' => (!empty($alerts)) ? "Matched:" : "Not Matched"
                            );
                        } else {
                            $data['Apidata'][] = array(
                                'mer_type' => $Apidata->mer_type,
                                'username' => $Apidata->username,
                                'transaction_id' => $Apidata->transaction_id,
                                'processor_id' => $Apidata->processor_id,
                                'user_id' => $Apidata->user_id,
                                'first_name' => $Apidata->first_name,
                                'last_name' => $Apidata->last_name,
                                'email' => $Apidata->email,
                                'ccbin6' => $Apidata->cc_bin,
                                'date_of_tran' => $Apidata->date_of_tran,
                                'card_num' => $Apidata->card_num,
                                'matched' => 'Not Matched',
                                'a_match' => (!empty($alerts)) ? "Matched:" : "Not Matched"
                            );
                        }
                    }
                }
            }
            $data['transALL'] = $data['Apidata'];
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/view_transaction', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function view_transaction_auth($id) {
        if ($this->session->userdata('admin_Id')) {
            $mer_id = $id;
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $data['settings'] = $this->admin_manager->SelectByID($this->table[2], 'id', '1');
            $data['transaction'] = $this->admin_manager->SelectByCompare($this->table[4], 'mer_id', $mer_id, 'mer_type', 'authorize');
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/view_transaction_auth', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function curl_request($xmlContent, $contentType, $endpoint) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/' . $contentType));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlContent);
        $result = curl_exec($ch);
        $xmls = @simplexml_load_string($result);
        $my_std_class = json_decode(json_encode($xmls));
        return json_decode(json_encode($xmls), true);
    }

    public function getUnsettledTransactionListRequest($id) {
        $xmlContent = '<getUnsettledTransactionListRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                <name>' . $this->APILOGIN . '</name>
                                <transactionKey>' . $this->TransactionKey . '</transactionKey>
                            </merchantAuthentication>
                            <sorting>
                                <orderBy>submitTimeUTC</orderBy>
                                <orderDescending>true</orderDescending>
                            </sorting>
                            <paging>
                                <limit>100</limit>
                                <offset>1</offset>
                            </paging>
                        </getUnsettledTransactionListRequest>';

        $result = $this->curl_request($xmlContent, 'xml', $this->Endpoint);
        if (!empty($result)) {
            foreach ($result['transactions'] as $transactions) {
                $this->getTransactionDetailsRequest($transactions['transId'], $id);
            }
        }
    }

    public function getSettledBatchListRequest($id) {
        $query = $this->admin_manager->SelectByID($this->table[6], 'id', $id);
        $login = $query[0]->api_login_id;
        $transactionKey = $query[0]->api_tran_key;
        $end_point = $query[0]->m_end_point;
        $CalCurrentDate = date('Y-m-d h:m:s');
        $StartDate = str_replace('+00:00', 'Z', gmdate('c', strtotime($CalCurrentDate)));
        $CalMonth = date('Y-m-d h:m:s', strtotime('-1 month'));
        $MonthLastDate = str_replace('+00:00', 'Z', gmdate('c', strtotime($CalMonth)));

        $xmlContent = '<getSettledBatchListRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                <name>' . $login . '</name>
                                <transactionKey>' . $transactionKey . '</transactionKey>
                            </merchantAuthentication>
                            <includeStatistics>true</includeStatistics>
                            <firstSettlementDate>' . $MonthLastDate . '</firstSettlementDate>
                            <lastSettlementDate>' . $StartDate . '</lastSettlementDate>
                        </getSettledBatchListRequest>';


        $results = $this->curl_request($xmlContent, 'xml', $end_point);
        if ($results['messages']['resultCode'] == "Error") {
            $this->session->set_flashdata("checkError", $results['messages']['message']['text']);
            redirect('admin/all_merchant/');
        } else {
            foreach ($results['batchList']['batch'] as $batch) {
                $this->getTransactionListRequest($batch['batchId'], $id);
            }
        }
    }

    public function getTransactionListRequest($batchID, $id = "") {
        $query = $this->admin_manager->SelectByID($this->table[6], 'id', $id);
        $login = $query[0]->api_login_id;
        $transactionKey = $query[0]->api_tran_key;
        $end_point = $query[0]->m_end_point;
        $xmlContent = '<getTransactionListRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                 <name>' . $login . '</name>
                                 <transactionKey>' . $transactionKey . '</transactionKey>
                            </merchantAuthentication>
                            <batchId>' . $batchID . '</batchId>
                            <sorting>
                              <orderBy>submitTimeUTC</orderBy>
                              <orderDescending>true</orderDescending>
                            </sorting>
                            <paging>
                              <limit>100</limit>
                              <offset>1</offset>
                            </paging>
                        </getTransactionListRequest>';

        $result = $this->curl_request($xmlContent, 'xml', $end_point);
        $test = array();
        $transactions = $result['transactions']['transaction'];
        foreach ($transactions as $transaction) {
            if (is_array($transaction)) {
                $array = array(
                    'transId' => (!empty($transaction['transId'])) ? $transaction['transId'] : "",
                    'submitTimeUTC' => (!empty($transaction['submitTimeUTC'])) ? $transaction['submitTimeUTC'] : "",
                    'submitTimeLocal' => (!empty($transaction['submitTimeLocal'])) ? $transaction['submitTimeLocal'] : "",
                    'transactionStatus' => (!empty($transaction['transactionStatus'])) ? $transaction['transactionStatus'] : "",
                    'invoiceNumber' => (!empty($transaction['invoiceNumber'])) ? $transaction['invoiceNumber'] : "",
                    'accountType' => (!empty($transaction['accountType'])) ? $transaction['accountType'] : "",
                    'accountNumber' => (!empty($transaction['accountNumber'])) ? $transaction['accountNumber'] : "",
                    'settleAmount' => (!empty($transaction['settleAmount'])) ? $transaction['settleAmount'] : "",
                    'marketType' => (!empty($transaction['marketType'])) ? $transaction['marketType'] : "",
                    'product' => (!empty($transaction['product'])) ? $transaction['product'] : "",
                    'batchId' => (string) $batchID
                );
                $transactionID = $array['transId'];
                $this->getTransactionDetailsRequest($transactionID, $id);
            } else {
                $test[] = $transaction;
            }
        }
        if (!empty($test)) {
            $array = array(
                'transId' => $test[0],
                'submitTimeUTC' => $test[1],
                'submitTimeLocal' => $test[2],
                'transactionStatus' => $test[3],
                'invoiceNumber' => $test[4],
                'accountType' => $test[7],
                'accountNumber' => $test[8],
                'settleAmount' => $test[9],
                'marketType' => $test[10],
//                'product' => $test[11],
                'batchId' => (string) $batchID
            );
            $transactionID = $array['transId'];
            $this->getTransactionDetailsRequest($transactionID, $id);
        }
    }

    public function getTransactionDetailsRequest($transactionID, $id) {
        error_reporting(E_ALL);
        $query = $this->admin_manager->SelectByID($this->table[6], 'id', $id);
        $login = $query[0]->api_login_id;
        $transactionKey = $query[0]->api_tran_key;
        $end_point = $query[0]->m_end_point;
        $xmlContent = '<getTransactionDetailsRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                 <name>' . $login . '</name>
                                 <transactionKey>' . $transactionKey . '</transactionKey>
                            </merchantAuthentication>
                            <transId>' . $transactionID . '</transId>
                        </getTransactionDetailsRequest>';
        $response = $this->curl_request($xmlContent, 'xml', $end_point);
//        print_r($response);
//        die();
        $transId = (!empty($response['transaction']['transId'])) ? $response['transaction']['transId'] : "";
        $submitTimeUTC = (!empty($response['transaction']['submitTimeUTC'])) ? $response['transaction']['submitTimeUTC'] : "";
        $submitTimeLocal = (!empty($response['transaction']['submitTimeLocal'])) ? $response['transaction']['submitTimeLocal'] : "";
        $transactionType = (!empty($response['transaction']['transactionType'])) ? $response['transaction']['transactionType'] : "";
        $transactionStatus = (!empty($response['transaction']['transactionStatus'])) ? $response['transaction']['transactionStatus'] : "";
        $responseCode = (!empty($response['transaction']['responseCode'])) ? $response['transaction']['responseCode'] : "";
        $responseReasonCode = (!empty($response['transaction']['responseReasonCode'])) ? $response['transaction']['responseReasonCode'] : "";
        $responseReasonDescription = (!empty($response['transaction']['responseReasonDescription'])) ? $response['transaction']['responseReasonDescription'] : "";
        $authCode = (!empty($response['transaction']['authCode'])) ? $response['transaction']['authCode'] : "";
        $AVSResponse = (!empty($response['transaction']['AVSResponse'])) ? $response['transaction']['AVSResponse'] : "";
        $cardCodeResponse = (!empty($response['transaction']['cardCodeResponse'])) ? $response['transaction']['cardCodeResponse'] : "";
        $batchId = (!empty($response['transaction']['batch']['batchId'])) ? $response['transaction']['batch']['batchId'] : "";
        $settlementTimeUTC = (!empty($response['transaction']['batch']['settlementTimeUTC'])) ? $response['transaction']['batch']['settlementTimeUTC'] : "";
        $settlementTimeLocal = (!empty($response['transaction']['batch']['settlementTimeLocal'])) ? $response['transaction']['batch']['settlementTimeLocal'] : "";
        $settlementState = (!empty($response['transaction']['batch']['settlementState'])) ? $response['transaction']['batch']['settlementState'] : "";
        $invoiceNumber = (!empty($response['transaction']['order']['invoiceNumber'])) ? $response['transaction']['order']['invoiceNumber'] : "";
        $description = (!empty($response['transaction']['order']['description'])) ? $response['transaction']['order']['description'] : "";
        $tax = (!empty($response['transaction']['tax']['amount'])) ? $response['transaction']['tax']['amount'] : "";
        $settleAmount = (!empty($response['transaction']['settleAmount'])) ? $response['transaction']['settleAmount'] : "";
        $cardNumber = (!empty($response['transaction']['payment']['creditCard']['cardNumber'])) ? $response['transaction']['payment']['creditCard']['cardNumber'] : "";
        $expirationDate = (!empty($response['transaction']['payment']['creditCard']['expirationDate'])) ? $response['transaction']['payment']['creditCard']['expirationDate'] : "";
        $cardType = (!empty($response['transaction']['payment']['creditCard']['cardType'])) ? $response['transaction']['payment']['creditCard']['cardType'] : "";
        $email = (!empty($response['transaction']['customer']['email'])) ? $response['transaction']['customer']['email'] : "";
        $firstName = (!empty($response['transaction']['billTo']['firstName'])) ? $response['transaction']['billTo']['firstName'] : "";
        $lastName = (!empty($response['transaction']['billTo']['lastName'])) ? $response['transaction']['billTo']['lastName'] : "";
        $country = (!empty($response['transaction']['billTo']['country'])) ? $response['transaction']['billTo']['country'] : "";
        $address = (!empty($response['transaction']['billTo']['address'])) ? $response['transaction']['billTo']['address'] : "";
        $city = (!empty($response['transaction']['billTo']['city'])) ? $response['transaction']['billTo']['city'] : "";
        $state = (!empty($response['transaction']['billTo']['state'])) ? $response['transaction']['billTo']['state'] : "";
        $zip = (!empty($response['transaction']['billTo']['zip'])) ? $response['transaction']['billTo']['zip'] : "";
        $phoneNumber = (!empty($response['transaction']['billTo']['phoneNumber'])) ? $response['transaction']['billTo']['phoneNumber'] : "";

        $data = array(
            'user_id' => $this->current_user,
            'mer_id' => $id,
            'mer_type' => 'authorize',
            'transaction_id' => $transId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'address1' => $address,
            'city' => $city,
            'state' => $state,
            'postalcode' => $zip,
            'tax' => $tax,
            'country' => $country,
            'email' => $email,
            'phone' => $phoneNumber,
            'cc_type' => $cardType,
            'card_num' => $cardNumber,
            'amount' => $settleAmount,
            'date_of_tran' => $submitTimeLocal,
            'date_of_tran2' => date('Y-m-d', strtotime($submitTimeLocal)),
        );
        $this->admin_manager->Insert($this->table[4], $data);
//        redirect(site_url('/admin/all_merchant'));
    }

    public function getAllTransactions($id) {
        $this->getSettledBatchListRequest($id);
        $this->getUnsettledTransactionListRequest($id);
    }

    public function save_now() {
        $this->load->view('administrator/csv_mapping_style');
        $header = $_POST;
        $this->session->set_userdata('header', $header);
        $data['template'] = $this->admin_manager->SelectAll($this->table[8]);
        $this->load->view('administrator/save_template', $data);
    }

    public function import_now() {
        $csvHead = $this->session->userdata('csv_header');
        $dbcolumns = $this->session->userdata('dbcolumns');
        $csvData = $this->session->userdata('csv_body');
        $importcsv = $this->input->post('importcsv');
        $map = $this->input->post('map');
        $headers = $this->session->userdata('header');
        $header = $headers['csv_header_keys'];
        $saveHeader = json_encode($headers);
        if ($importcsv == "save") {
            $insertData = array(
                'temp_title' => $map,
                'template' => $saveHeader,
            );
            $this->admin_manager->insert($this->table[8], $insertData);
            foreach ($csvData as $key => $csv) {
                $data = array(
                    'corp' => $csv[$header[0]],
                    'region' => $csv[$header[1]],
                    'principal' => $csv[$header[2]],
                    'associate' => $csv[$header[3]],
                    'chain' => $csv[$header[4]],
                    'merchant_number' => $csv[$header[5]],
                    'merchant_dba' => $csv[$header[6]],
                    'merchant_name' => $csv[$header[7]],
                    'mcc' => $csv[$header[8]],
                    'case_id' => $csv[$header[9]],
                    'case_number' => $csv[$header[10]],
                    'dispute_type' => $csv[$header[11]],
                    'transaction_type' => $csv[$header[12]],
                    'Dispute_sub_type' => $csv[$header[13]],
                    'received_date' => $csv[$header[14]],
                    'case_amount' => $csv[$header[15]],
                    'case_currency' => $csv[$header[16]],
                    'cardholder_number' => $csv[$header[17]],
                    'card_scheme' => $csv[$header[18]],
                    'adjustment_amount' => $csv[$header[19]],
                    'adjustment_currency' => $csv[$header[20]],
                    'original_transaction_amount' => $csv[$header[21]],
                    'original_transaction_currency' => $csv[$header[22]],
                    'original_transaction_date' => $csv[$header[23]],
                    'reason_code' => $csv[$header[24]],
                    'reason_code_description' => $csv[$header[25]],
                    'issuing_bank_comment' => $csv[$header[26]],
                    'original_reference_number' => $csv[$header[27]],
                    'remarks' => $csv[$header[28]],
                    'invoice_ticket_number' => $csv[$header[29]],
                    'acquirer_reference_number' => $csv[$header[30]],
                    'deposit_control_number' => $csv[$header[31]],
                    'cash_back_amount' => $csv[$header[32]],
                    'case_due_date' => $csv[$header[33]],
                    'investigator_comments' => $csv[$header[34]],
                    'case_resolved_date' => $csv[$header[35]],
                    'case_status' => $csv[$header[36]],
                    'transaction_ID' => $csv[$header[37]],
                );
                $this->admin_manager->Insert($this->table[7], $data);
            }
        }

        if ($importcsv == "existing") {
            $usemap = $this->input->post('usemap');
            $header = $this->admin_manager->SelectByID($this->table[8], 'id', $usemap);
            $changeType = json_decode($header[0]->template);
            $header = $changeType->csv_header_keys;
            foreach ($csvData as $key => $csv) {
                $data = array(
                    'user_id' => $this->current_user,
                    'corp' => $csv[$header[0]],
                    'region' => $csv[$header[1]],
                    'principal' => $csv[$header[2]],
                    'associate' => $csv[$header[3]],
                    'chain' => $csv[$header[4]],
                    'merchant_number' => $csv[$header[5]],
                    'merchant_dba' => $csv[$header[6]],
                    'merchant_name' => $csv[$header[7]],
                    'mcc' => $csv[$header[8]],
                    'case_id' => $csv[$header[9]],
                    'case_number' => $csv[$header[10]],
                    'dispute_type' => $csv[$header[11]],
                    'transaction_type' => $csv[$header[12]],
                    'Dispute_sub_type' => $csv[$header[13]],
                    'received_date' => $csv[$header[14]],
                    'case_amount' => $csv[$header[15]],
                    'case_currency' => $csv[$header[16]],
                    'cardholder_number' => $csv[$header[17]],
                    'card_scheme' => $csv[$header[18]],
                    'adjustment_amount' => $csv[$header[19]],
                    'adjustment_currency' => $csv[$header[20]],
                    'original_transaction_amount' => $csv[$header[21]],
                    'original_transaction_currency' => $csv[$header[22]],
                    'original_transaction_date' => $csv[$header[23]],
                    'reason_code' => $csv[$header[24]],
                    'reason_code_description' => $csv[$header[25]],
                    'issuing_bank_comment' => $csv[$header[26]],
                    'original_reference_number' => $csv[$header[27]],
                    'remarks' => $csv[$header[28]],
                    'invoice_ticket_number' => $csv[$header[29]],
                    'acquirer_reference_number' => $csv[$header[30]],
                    'deposit_control_number' => $csv[$header[31]],
                    'cash_back_amount' => $csv[$header[32]],
                    'case_due_date' => $csv[$header[33]],
                    'investigator_comments' => $csv[$header[34]],
                    'case_resolved_date' => $csv[$header[35]],
                    'case_status' => $csv[$header[36]],
                    'transaction_ID' => $csv[$header[37]],
                );
                $this->admin_manager->Insert($this->table[7], $data);
            }
        }

        redirect(site_url('/admin/import_csv'));
    }

    public function charge_back() {
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['chargBack'] = '';
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            if ($adminID == "1") {
                $data['chargBack'] = $this->admin_manager->SelectAll($this->table[7]);
            } else {
                $data['chargBack'] = $this->admin_manager->SelectByID($this->table[7], 'user_id', $adminID);
            }
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/charge_back', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function view_cb_data($id) {
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $data['chargBack'] = $this->admin_manager->SelectByID($this->table[7], 'id', $id);
            $data['chargBack_db_col'] = $this->admin_manager->ShowColumns($this->table[7]);
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/view_cb_data', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function all_customer() {
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $data['all_customers'] = $this->admin_manager->SelectAll($this->table[0]);
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/all_customers', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function del_user($id) {
        $this->admin_manager->DeleteByID($this->table[0], 'id', $id);
        redirect(site_url('admin/all_customer'));
    }

    public function suspend_user($id) {
        $data = array('status' => '0');
        $this->admin_manager->UpdateByID($this->table[0], $data, 'id', $id);
        redirect(site_url('admin/all_customer'));
    }

    public function enable_user($id) {
        $data = array('status' => '1');
        $this->admin_manager->UpdateByID($this->table[0], $data, 'id', $id);
        redirect(site_url('admin/all_customer'));
    }

    public function GetParserMail() {
        if (!empty($_POST)) {
            $myrecords = $_POST['my_records'];
            $count = 0;
            foreach ($myrecords as $myrecord) {
                if ($count !== 0) {
                    $currentRecords = array_filter($myrecord);
                    if (!empty($currentRecords)) {
                        $cardMatchEnd = substr($currentRecords[10], 12, 15);
                        $cardMatchStart = substr($currentRecords[10], 0, 6);
                        $cardnumber = $cardMatchStart . "XXXXXX" . $cardMatchEnd;
                        $transaction = $this->admin_manager->SelectForMatchAlert($this->table[4], $cardMatchStart, $cardMatchEnd, "0");
                        $chargeback = $this->admin_manager->SelectForMatchAlertCh($this->table[7], $cardnumber);
                        if (!empty($chargeback)) {
                            $data = array(
                                'csv_data' => serialize($currentRecords)
                            );
                            $where = array(
                                'cardholder_number' => $cardnumber
                            );
                            $this->admin_manager->Update($this->table[7], $data, $where);
                        }
                        if (!empty($transaction)) {
                            $data = array(
                                'csv_data' => serialize($currentRecords)
                            );
                            $where = array(
                                'card_num' => $cardnumber
                            );
                            $this->admin_manager->Update($this->table[4], $data, $where);
                        }
                    }
                }
                $count++;
            }
        }
    }

    public function validateBatchList($apiLogin, $trans_key, $endpoint) {
        $CalCurrentDate = date('Y-m-d h:m:s');
        $StartDate = str_replace('+00:00', 'Z', gmdate('c', strtotime($CalCurrentDate)));
        $CalMonth = date('Y-m-d h:m:s', strtotime('-1 month'));
        $MonthLastDate = str_replace('+00:00', 'Z', gmdate('c', strtotime($CalMonth)));
        $xmlContent = '<getSettledBatchListRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                <name>' . $apiLogin . '</name>
                                <transactionKey>' . $trans_key . '</transactionKey>
                            </merchantAuthentication>
                            <includeStatistics>true</includeStatistics>
                            <firstSettlementDate>' . $MonthLastDate . '</firstSettlementDate>
                            <lastSettlementDate>' . $StartDate . '</lastSettlementDate>
                        </getSettledBatchListRequest>';


        $results = $this->curl_request($xmlContent, 'xml', $endpoint);
        return $results;
    }

    public function validateTransactionList($batchID, $apiLogin, $trans_key, $endpoint) {
        $xmlContent = '<getTransactionListRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                 <name>' . $apiLogin . '</name>
                                 <transactionKey>' . $trans_key . '</transactionKey>
                            </merchantAuthentication>
                            <batchId>' . $batchID . '</batchId>
                            <sorting>
                              <orderBy>submitTimeUTC</orderBy>
                              <orderDescending>true</orderDescending>
                            </sorting>
                            <paging>
                              <limit>1000</limit>
                              <offset>1</offset>
                            </paging>
                        </getTransactionListRequest>';

        $result = $this->curl_request($xmlContent, 'xml', $endpoint);
        $transactions = $result['transactions']['transaction'];
        foreach ($transactions as $transaction) {
            if (is_array($transaction)) {
                $array = array(
                    'transId' => $transaction['transId'],
                    'submitTimeUTC' => $transaction['submitTimeUTC'],
                    'submitTimeLocal' => $transaction['submitTimeLocal'],
                    'transactionStatus' => $transaction['transactionStatus'],
                    'invoiceNumber' => $transaction['invoiceNumber'],
                    'accountType' => $transaction['accountType'],
                    'accountNumber' => $transaction['accountNumber'],
                    'settleAmount' => $transaction['settleAmount'],
                    'marketType' => $transaction['marketType'],
                    'product' => $transaction['product'],
                    'batchId' => (string) $batchID
                );
                $transactionID = $array['transId'];
                $this->ValidateTransactionDetails($transactionID, $apiLogin, $trans_key, $endpoint);
            } else {
                $test[] = $transaction;
            }
        }
    }

    public function ValidateTransactionDetails($transactionID, $apiLogin, $trans_key, $endpoint) {
        $xmlContent = '<getTransactionDetailsRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                 <name>' . $login . '</name>
                                 <transactionKey>' . $transactionKey . '</transactionKey>
                            </merchantAuthentication>
                            <transId>' . $transactionID . '</transId>
                        </getTransactionDetailsRequest>';
        $response = $this->curl_request($xmlContent, 'xml', $end_point);
        return $response;
    }

    public function cb_dispute_match() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cbd.warplite.com/apis/cbdisputes?limit=100",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic TEg5eWQ4bnNQYmFVS0hwOmIyZEtuZUVBTndnN3RiS1o0MUZFdE5VRGE=",
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $results = json_decode($response);
            $count = 0;
            foreach ($results as $result) {
                $cardMatchEnd = substr($result->cbcardnumber, 12, 15);
                $cardMatchStart = substr($result->cbcardnumber, 0, 6);
                if ($result->ccbin6 !== 0) {
                    $query = $this->admin_manager->SelectForMatchAlertMcol($this->table[4], $result->ccbin6, $cardMatchEnd, '0', $result->cbdatedisputed);
                    if (!empty($query)) {
                        $data = array(
                            'first_name' => $result->cbclientnamefirst,
                            'last_name' => $result->cbclientnamelast,
                            'address1' => $result->cbclientfullbillingaddress,
                            'address2' => $result->cbclientaddress,
                            'email' => $result->cbclientemail,
                            'city' => $result->cbclientcity,
                            'state' => $result->cbclientstate,
                            'common_data' => serialize($result)
                        );
                        $commondata = serialize($result);
                        $this->admin_manager->UpdateQuery($this->table[4], $result->ccbin6, $cardMatchEnd, '0', $commondata);
                    }
                } else {
                    $query = $this->admin_manager->SelectForMatchAlertMcol($this->table[4], $result->ccbin6, $cardMatchEnd, '0', $result->cbdatedisputed);
                    if (!empty($query[0])) {
                        $data = array(
                            'first_name' => $result->cbclientnamefirst,
                            'last_name' => $result->cbclientnamelast,
                            'address1' => $result->cbclientfullbillingaddress,
                            'address2' => $result->cbclientaddress,
                            'email' => $result->cbclientemail,
                            'city' => $result->cbclientcity,
                            'state' => $result->cbclientstate,
                            'common_data' => serialize($result)
                        );
                        $commondata = serialize($result);
                        $this->admin_manager->UpdateQuery($this->table[4], $cardMatchStart, $cardMatchEnd, '0', $commondata);
                    }
                }
                $count++;
            }
        }
//        redirect(site_url('/admin/charge_back'));
    }

    public function cb_dispute() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cbd.warplite.com/apis/cbdisputes?limit=1000",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic TEg5eWQ4bnNQYmFVS0hwOmIyZEtuZUVBTndnN3RiS1o0MUZFdE5VRGE=",
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $results = json_decode($response);
            foreach ($results as $result) {
                $data = array(
                    'user_id' => $this->current_user,
                    'merchant_number' => $result->merchantnumber,
                    'merchant_name' => $result->merchantname,
                    'case_number' => $result->cbcasenumber,
                    'case_amount' => $result->cbtransamount,
                    'cash_back_amount' => $result->cbtransamount,
                    'original_transaction_currency' => $result->cbcurrency,
                    'reason_code_description' => $result->cbreasonmsg,
                    'cardholder_number' => $result->cbcardnumber,
                    'card_scheme' => $result->cbcomments,
                    'received_date' => $result->cbdatedisputed,
                    'reason_code' => $result->cbreasoncode,
                    'investigator_comments' => $result->statusname,
                    'csv_data' => serialize($result),
                    'transaction_ID' => $result->cbtransid
                );
                $this->admin_manager->Insert($this->table[7], $data);
            }
        }
        redirect(site_url('admin/charge_back'));
    }

    public function SFTPConnect() {
        $adminID = $this->session->userdata('admin_Id');
        $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
        $data['ftp'] = $this->admin_manager->SelectAll($this->table[9]);
        $this->load->view('administrator/header', $data);
        $this->load->view('administrator/sidebar');
        $this->load->view('administrator/sftp', $data);
        $this->load->view('administrator/footer');
    }

    public function save_sftp() {
        $protocol = $this->input->post('protocol');
        $host = $this->input->post('host');
        $port = $this->input->post('port');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $directory = $this->input->post('directory');
        $filename = $this->input->post('filename');
//        $query = $this->admin_manager->CheckAlreadyExistByOne($this->table[9]);
        $query = $this->admin_manager->SelectByID($this->table[9], 'id', '1');
        if (empty($query)) {
            $ftpdata = array(
                'protocol' => $protocol,
                'ftp_host' => $host,
                'username' => $username,
                'password' => $password,
                'port' => $port,
                'dir' => $directory,
                'filename' => $filename
            );
            $this->admin_manager->Insert($this->table[9], $ftpdata);
        } else {
            $ftpdata = array(
                'protocol' => $protocol,
                'ftp_host' => $host,
                'username' => $username,
                'password' => $password,
                'port' => $port,
                'dir' => $directory,
                'filename' => $filename
            );
            $where = array('id' => '1');
            $this->admin_manager->Update($this->table[9], $ftpdata, $where);
        }
        redirect(site_url('/admin/sftp'));
    }

    public function MatchFilters() {
        $trans_id = $this->input->post("trans_id");
        $trans_date = $this->input->post("trans_date");
        $card_num = $this->input->post("card_num");
        $data = array();
        if (!empty($trans_id)) {
            $data = array('transaction_id' => $trans_id);
        }
        if (!empty($trans_date)) {
            $data = array(
                'date_of_tran2' => $trans_date,
            );
        }
        if (!empty($card_num)) {
            $data = array(
                'card_num' => $card_num,
            );
        }
        if (!empty($trans_id) && !empty($trans_date)) {
            $data = array(
                'transaction_id' => $trans_id,
                'date_of_tran2' => $trans_date,
            );
        }
        if (!empty($trans_id) && !empty($card_num)) {
            $data = array(
                'transaction_id' => $trans_id,
                'card_num' => $card_num,
            );
        }
        if (!empty($trans_date) && !empty($card_num)) {
            $data = array(
                'date_of_tran2' => $trans_date,
                'card_num' => $card_num,
            );
        }
        if (!empty($trans_id) && !empty($trans_date) && !empty($card_num)) {
            $data = array(
                'date_of_tran2' => $trans_date,
                'card_num' => $card_num,
                'transaction_id' => $trans_id,
            );
        }
        $getTransaction = $this->admin_manager->SelectByArray($this->table[4], $data);
        if (!empty($getTransaction)) {
            $ccbin = $getTransaction[0]->cc_bin;
            $card_num = $getTransaction[0]->card_num;
            $cardMatchEnd = substr($card_num, 12, 15);
            $cardMatchStart = substr($card_num, 0, 6);

            if ($ccbin !== "0") {
                $result = $this->admin_manager->SelectForMatchAlertCB($this->table[7], $ccbin, $cardMatchEnd);
                if (empty($result)) {
                    foreach ($getTransaction as $transaction) {
                        ?>
                        <tr>
                            <td><a href="<?php echo site_url("admin/view_data/" . $transaction->mer_type . "/" . $transaction->transaction_id . "/" . $transaction->user_id); ?>"><?php echo (!empty($transaction->transaction_id)) ? $transaction->transaction_id : "--"; ?></a></td>
                            <td><?php echo (!empty($transaction->first_name)) ? $transaction->first_name : "--"; ?></td>
                            <td><?php echo (!empty($transaction->last_name)) ? $transaction->last_name : "--"; ?></td>
                            <td><?php echo (!empty($transaction->mer_type)) ? $transaction->mer_type : "--"; ?></td>
                            <td><?php echo (!empty($transaction->email)) ? $transaction->email : "--"; ?></td>
                            <td><?php echo (!empty($transaction->date_of_tran)) ? date("Y-m-d", strtotime($transaction->date_of_tran)) : "--"; ?></td>
                            <td><?php echo (!empty($transaction->card_num)) ? $transaction->card_num : "--"; ?></td>
                            <td>Not Available</td>
                        </tr>
                        <?php
                    }
                }
            }
        } else {
            echo "<tr><td colspan='8'>No Record found</td></tr>";
        }
    }

    public function Alert() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cbd.warplite.com/apis/cbalerts?limit=500",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic TEg5eWQ4bnNQYmFVS0hwOmIyZEtuZUVBTndnN3RiS1o0MUZFdE5VRGE=",
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $results = json_decode($response);
            $count = 0;
            foreach ($results as $result) {
                $query = $this->admin_manager->SelectByCompare($this->table[5], 'apitransactiondate', $result->apitransactiondate, 'apicard', $result->apicard);
                if (empty($query)) {
                    $data = array(
                        'merchantid' => $result->merchantid,
                        'merchantname' => $result->merchantname,
                        'customerid' => $result->customerid,
                        'apiID' => $result->apiID,
                        'apiamount' => $result->apiamount,
                        'apicurrency' => $result->apicurrency,
                        'apicard' => $result->apicard,
                        'apitransactiondate' => $result->apitransactiondate,
                        'apialerterid' => $result->apialerterid,
                        'common_data' => serialize($results),
                    );
                    $this->admin_manager->Insert($this->table[5], $data);
                }
            }
        }
        redirect(site_url('/admin/alert_data'));
    }

    public function template() {
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $data['template'] = $this->admin_manager->SelectAll($this->table[10]);
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/template', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function save_template() {
        $template = $this->input->post('content');
        $data = array(
            'etemplate' => $template,
        );
        $vx = $this->admin_manager->CheckAlreadyExistByOne($this->table[10]);
        if ($vx[0]->count < 1) {
            $this->admin_manager->Insert($this->table[10], $data);
            print_r(json_encode(array('status' => 'insert', 'response' => '<div class="alert clearfix alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Template has been added</div>')));
        } else {
            $where = array(
                'id' => '1'
            );
            $this->admin_manager->Update($this->table[10], $data, $where);
            print_r(json_encode(array('status' => 'update', 'response' => '<div class="alert clearfix alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Template has been updated</div>')));
        }
    }

    public function connect_ftp() {
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $select = array(
                'id' => '1'
            );
            $ers = $this->admin_manager->SelectByMultipleCol($this->table[9], $select);
            $data['checkLogin'] = '';
            $data['jms'] = array();
            $data['error'] = '';
            if (!empty($ers)) {
                $ftp_host = $ers[0]->ftp_host;
                $username = $ers[0]->username;
                $password = $ers[0]->password;
                $directory = ($ers[0]->directory == "/") ? "" : $ers[0]->directory;
                $filename = $ers[0]->filename;
                $config['hostname'] = $ftp_host;
                $config['username'] = $username;
                $config['password'] = $password;
                $config['debug'] = TRUE;
                $this->ftp->connect($config);
                $list = $this->ftp->list_files($directory . $filename);
                if (!empty($list)) {
                    $csvfile = $list[0];
                    $filename = "ftp://" . $username . ":" . $password . "@" . $ftp_host . "/" . $csvfile;
                    $handle = fopen($filename, "r");
                    $row = 1;
                    $data['h'] = $handle;
                    if (($handlex = fopen($filename, "r")) !== FALSE) {
                        while (($data = fgetcsv($handlex, 1000, ",")) !== FALSE) {
                            if ($row == 1) {
                                $row++;
                                continue;
                            }
                            $data['jms'] = $data;
                            $insert = array(
                                'user_id' => $this->current_user,
                                'corp' => $data[0],
                                'region' => $data[1],
                                'principal' => $data[2],
                                'associate' => $data[3],
                                'chain' => $data[4],
                                'merchant_number' => $data[5],
                                'merchant_dba' => $data[6],
                                'merchant_name' => $data[7],
                                'mcc' => $data[8],
                                'case_id' => $data[9],
                                'case_number' => $data[10],
                                'dispute_type' => $data[11],
                                'transaction_type' => $data[12],
                                'Dispute_sub_type' => $data[13],
                                'received_date' => $data[14],
                                'case_amount' => $data[15],
                                'case_currency' => $data[16],
                                'cardholder_number' => $data[17],
                                'card_scheme' => $data[18],
                                'adjustment_amount' => $data[19],
                                'adjustment_currency' => $data[20],
                                'original_transaction_amount' => $data[21],
                                'original_transaction_currency' => $data[22],
                                'original_transaction_date' => $data[23],
                                'reason_code' => $data[24],
                                'reason_code_description' => $data[25],
                                'issuing_bank_comment' => $data[26],
                                'original_reference_number' => $data[27],
                                'remarks' => $data[28],
                                'invoice_ticket_number' => $data[29],
                                'acquirer_reference_number' => $data[30],
                                'deposit_control_number' => $data[31],
                                'cash_back_amount' => $data[32],
                                'case_due_date' => $data[33],
                                'investigator_comments' => $data[34],
                                'case_resolved_date' => $data[35],
                                'case_status' => $data[36],
                                'transaction_ID' => $data[37],
                            );
                            $this->admin_manager->Insert($this->table[7], $insert);
                        }
                    }
                    redirect(site_url('/admin/charge_back'));
                } else {
                    $data['error'] = "File not found";
                }
            }
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/cftp', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function sage_view() {
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $data['sages'] = $this->admin_manager->SelectWithJoinSage($adminID);
            $data['merchants'] = $this->admin_manager->SelectWithSageMerchants();
            $data['location'] = $this->admin_manager->GroupByCol('sage', 'location');
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/sage_view', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function connect_sage_ftp() {
        if ($this->session->userdata('admin_Id')) {
            $adminID = $this->session->userdata('admin_Id');
            $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
            $select = array(
                'id' => '2'
            );
            $ers = $this->admin_manager->SelectByMultipleCol($this->table[9], $select);
            $data['checkLogin'] = '';
            $data['jms'] = array();
            if (!empty($ers)) {
                $ftp_host = $ers[0]->ftp_host;
                $username = $ers[0]->username;
                $password = $ers[0]->password;
                $directory = ($ers[0]->directory == "/") ? "" : $ers[0]->directory;
                $filename = $ers[0]->filename;
                $config['hostname'] = $ftp_host;
                $config['username'] = $username;
                $config['password'] = $password;
                $config['debug'] = TRUE;
                $this->ftp->connect($config);
                $list = $this->ftp->list_files($directory . $filename);
                if (!empty($list)) {
                    $csvfile = $list[0];
                    $filename = "ftp://" . $username . ":" . $password . "@" . $ftp_host . "/" . $csvfile;
                    $handle = fopen($filename, "r");
                    $row = 1;
                    $data['h'] = $handle;
                    if (($handlex = fopen($filename, "r")) !== FALSE) {
                        while (($data = fgetcsv($handlex, 1000, ",")) !== FALSE) {
                            if ($row == 1) {
                                $row++;
                                continue;
                            }
                            $data['jms'] = $data;
                            $insert = array(
                                'user_id' => $this->current_user,
                                'create_date' => $data[0],
                                'batch_date' => $data[1],
                                'location' => $data[2],
                                'terminal_id' => $data[3],
                                'check_status' => $data[4],
                                'funding_status' => $data[5],
                                'auth_no' => $data[6],
                                'routing_no' => $data[7],
                                'acc_no' => $data[8],
                                'check_no' => $data[9],
                                'check_amount' => $data[10],
                                'desposite_date' => $data[11],
                                'transacion_id' => $data[12],
                                'merchant' => $data[13],
                                'check_writer' => $data[14],
                                'tran_type' => $data[15],
                            );
                            $this->admin_manager->Insert($this->table[11], $insert);
                        }
                        redirect(site_url('/admin/sage_view'));
                    }
                } else {
                    $data['error'] = "File not found";
                }
            }
            $this->load->view('administrator/header', $data);
            $this->load->view('administrator/sidebar');
            $this->load->view('administrator/con_sftp_sage', $data);
            $this->load->view('administrator/footer');
        } else {
            redirect(site_url('/admin/'));
        }
    }

    public function get_user_mertype() {
        $userID = $this->input->post('user_id');
        $query = $this->admin_manager->getTransactionByUser('t.`mer_type`', $userID);
        $response = array('merchant_type' => array('records' => $query));
        print_r(json_encode($response));
    }

    public function get_user_processors() {
        $userID = $this->input->post('user_id');
        $query = $this->admin_manager->getTransactionByUser('t.`processor_id`', $userID);
        $response = array('processor' => array('records' => $query));
        print_r(json_encode($response));
    }

    public function SFTPConnectSage() {
        $adminID = $this->session->userdata('admin_Id');
        $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
        $data['ftp'] = $this->admin_manager->SelectByID($this->table[9], 'id', '2');
        $this->load->view('administrator/header', $data);
        $this->load->view('administrator/sidebar');
        $this->load->view('administrator/sftp_sage', $data);
        $this->load->view('administrator/footer');
    }

    public function save_sage_sftp() {
        $protocol = $this->input->post('protocol');
        $host = $this->input->post('host');
        $port = $this->input->post('port');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $directory = $this->input->post('directory');
        $filename = $this->input->post('filename');

        $query = $this->admin_manager->SelectByID($this->table[9], 'id', '2');
        if (empty($query)) {
            $ftpdata = array(
                'protocol' => $protocol,
                'ftp_host' => $host,
                'username' => $username,
                'password' => $password,
                'port' => $port,
                'dir' => $directory,
                'filename' => $filename
            );
            $this->admin_manager->Insert($this->table[9], $ftpdata);
        } else {
            $ftpdata = array(
                'protocol' => $protocol,
                'ftp_host' => $host,
                'username' => $username,
                'password' => $password,
                'port' => $port,
                'dir' => $directory,
                'filename' => $filename
            );
            $where = array('id' => '2');
            $this->admin_manager->Update($this->table[9], $ftpdata, $where);
        }
        redirect(site_url('/admin/ftp_connect_sage'));
    }

}
