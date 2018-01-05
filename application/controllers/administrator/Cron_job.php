<?php

class Cron_job extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'cookie'));
        $this->load->model(array('admin_manager'));
        $this->table = array('admin', 'users', 'api', 'disputes', 'transaction', 'alert', 'merchant', 'charge_back', 'csv_template', 'sftp', 'template');
        $this->load->library(array('form_validation', 'email'));
        $this->current_user = (!empty($this->session->userdata('admin_Id'))) ? $this->session->userdata('admin_Id') : "";
        $this->get_fnc = get_instance();
        $this->current_url = $this->uri->segment(2);
        $this->Endpoint = 'https://apitest.authorize.net/xml/v1/request.api';
    }

    public function cron_match_alert() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cbd.warplite.com/apis/cbalerts?limit=1000",
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
                        $this->admin_manager->UpdateQuery($this->table[4], $result->ccbin6, $cardMatchEnd, '0', $commondata);
                        $this->cronMail_fnc('ali@kingdom-vision.com', 'saad@kingdom-vision.co.uk', 'testing', $data);
                    }
                } else {

                    $query = $this->admin_manager->SelectForMatchAlert($this->table[4], $cardMatchStart, $cardMatchEnd, '0');
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
                        $this->admin_manager->UpdateQuery($this->table[4], $cardMatchStart, $cardMatchEnd, '0', $commondata);
                        $this->cronMail_fnc('ali@kingdom-vision.com', 'saad@kingdom-vision.co.uk', 'testing', $data);
                    }
                }
                $count++;
            }
        }
    }

    public function cronMail_fnc($to, $from, $subject, $data) {

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
    }

    public function cron_curl_request($xmlContent, $contentType, $endpoint) {
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

    public function CronGetSettledBatchListRequest($id, $apilogin, $transactionkey, $endpoint = "") {
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


        $results = $this->cron_curl_request($xmlContent, 'xml', $endpoint);
        foreach ($results['batchList']['batch'] as $batch) {
            $this->CronGetTransactionListRequest($batch['batchId'], $id, $apilogin, $transactionkey, $endpoint);
        }
    }

    public function CronGetTransactionListRequest($batchID, $id, $apilogin, $transactionkey, $endpoint = "") {

        $xmlContent = '<getTransactionListRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                 <name>' . $apilogin . '</name>
                                 <transactionKey>' . $transactionkey . '</transactionKey>
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

        $result = $this->curl_request($xmlContent, 'xml', $endpoint);

        $test = array();
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
                $this->CronGetTransactionDetailsRequest($transactionID, $id, $apilogin, $transactionkey, $endpoint);
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
                'product' => $test[11],
                'batchId' => (string) $batchID
            );
            $transactionID = $array['transId'];
            $this->CronGetTransactionDetailsRequest($transactionID, $id, $apilogin, $transactionkey, $endpoint);
        }
    }

    public function CronGetTransactionDetailsRequest($transactionID, $id, $apilogin, $transactionkey, $endpoint = "") {
        $xmlContent = '<getTransactionDetailsRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                 <name>' . $apilogin . '</name>
                                 <transactionKey>' . $transactionkey . '</transactionKey>
                            </merchantAuthentication>
                            <transId>' . $transactionID . '</transId>
                        </getTransactionDetailsRequest>';
        $response = $this->cron_curl_request($xmlContent, 'xml', $endpoint);

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
            'date_of_tran2' => $submitTimeLocal,
        );
        $this->admin_manager->Insert($this->table[4], $data);
    }

    public function CronGetUnsettledTransactionListRequest($id, $apilogin, $transactionkey, $endpoint = "") {
        $xmlContent = '<getUnsettledTransactionListRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                <name>' . $apilogin . '</name>
                                <transactionKey>' . $transactionkey . '</transactionKey>
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

        $result = $this->cron_curl_request($xmlContent, 'xml', $endpoint);
        if (!empty($result)) {
            foreach ($result['transactions'] as $transactions) {
                $this->CronGetTransactionDetailsRequest($transactions['transId'], $id, $apilogin, $transactionkey, $endpoint);
            }
        }
    }

    public function cron_get_transaction() {
        $query = $this->admin_manager->selectAll($this->table[6]);
        if (!empty($query)) {
            foreach ($query as $q) {
                if ($q->mer_type == "nmi") {
                    $username = $q->m_user;
                    $password = $q->m_pass;
                    $userID = $q->user_id;
                    $merid = $q->id;
                    $this->NMI_Transaction($userID, $merid, $username, $password);
                }
                if ($q->mer_type == "authorize") {
                    $endpoint = $q->m_end_point;
                    $loginId = $q->api_login_id;
                    $transkey = $q->api_tran_key;
                    $userID = $q->user_id;
                    $this->CronGetSettledBatchListRequest($userID, $loginId, $transkey, $endpoint);
                    $this->CronGetUnsettledTransactionListRequest($userID, $loginId, $transkey, $endpoint);
                }
            }
        }
    }

    public function CronSecureNMI($username, $password, $filters, $endpoint = "") {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint . "?username=" . $username . "&password=" . $password . $filters,
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

    public function NMI_Transaction($userID = "", $merid = "", $username = "", $password = "") {
        $dates = date('Ymd') . "000000";
        $constraints = "&start_date=" . $dates;
        $result = $this->CronSecureNMI($username, $password, $constraints);
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
                    'user_id' => $userID,
                    'mer_id' => $merid,
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

}
