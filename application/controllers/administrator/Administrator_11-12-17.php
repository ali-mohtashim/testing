<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator extends CI_Controller {
    /*
     * @var Table $table declare it as an array in construct method for passing tables name 
     */

    protected $table;
    public $current_user;

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'cookie'));
        $this->load->model(array('admin_manager'));
        $this->table = array('admin', 'users', 'api', 'disputes', 'transaction', 'alert', 'merchant');
        $this->load->library(array('form_validation'));
        $this->current_user = (!empty($this->session->userdata('admin_Id'))) ? $this->session->userdata('admin_Id') : "";
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

    public function Dashboard() {
        /* Create Dashboard view
         * @var Admin User ID $adminID This is for getting admin_id from session variable
         * @var data $data This is for passing data from controller to view
         */
        $adminID = $this->session->userdata('admin_Id');
        $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
        $this->load->view('administrator/header', $data);
        $this->load->view('administrator/sidebar');
        $this->load->view('administrator/dashboard');
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
        $query = $this->admin_manager->SelectByCompare($this->table[0], 'username', $username, 'password', md5($password));
        if (!empty($query)) {
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
                        'merchantname' => $d->merchantname,
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
        $data['admin_info'] = $this->admin_manager->SelectByID($this->table[0], 'id', $adminID);
        $data['Apidata'] = $this->admin_manager->SelectAll($this->table[4]);
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
        $path = $_FILES['file']['name'];
        $filename = $_FILES["file"]["tmp_name"];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $array = explode('.', $ext);
        $extension = end($array);
        if ($extension == "csv") {
            if ($_FILES["file"]["size"] > 0) {
                $row = 1;
                if (($handle = fopen($filename, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $num = count($data);
                        if ($row == 1) {
                            
                        } else {
                            for ($c = 0; $c < $num; $c++) {
                                echo $c . "<br />\n";
                                if ($row == $c) {
                                    echo $row . "-CC- " . $data[4] . "<br />\n";
                                }
                            }
                        }
                        $row++;
                    }
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

    public function testArray() {
        try {
            $constraints = "&start_date=20171208000000";
            $result = $this->testXmlQuery('amychargeback22', 'Chargeback17!', $constraints);
            print_r($result);
        } catch (Exception $e) {

            $e->outputText();
        }
    }

    public function add_merchant() {
        $this->load->view('administrator/header');
        $this->load->view('administrator/sidebar');
        $this->load->view('administrator/merchant');
        $this->load->view('administrator/footer');
    }

    public function all_merchant() {
        $data['allmerchants'] = $this->admin_manager->SelectByID($this->table[6], 'user_id', $this->current_user);
        $this->load->view('administrator/header');
        $this->load->view('administrator/sidebar');
        $this->load->view('administrator/all_merchant', $data);
        $this->load->view('administrator/footer');
    }

    public function save_merchant() {
        $user = $this->input->post('user');
        $password = $this->input->post('password');
        $endpoint = $this->input->post('endpoint');
        $data = array(
            'user_id' => $this->current_user,
            'm_user' => $user,
            'm_pass' => $password,
            'm_end_point' => $endpoint,
        );
        $this->admin_manager->Insert($this->table[6], $data);
        redirect(site_url('admin/add_merchant'));
    }

    public function view_merchant($id) {
        echo "<h1>Loading...</h1>";
        $query = $this->admin_manager->SelectByID($this->table[6], 'id', $id);
        
        
        try {
            $constraints = "&start_date=20171208000000";
            $result = $this->testXmlQuery($query[0]->m_user, $query[0]->m_pass, $constraints);
            print_r($result);
        } catch (Exception $e) {
            $e->outputText();
        }
    }

}
