<?php

class AuthorizeNet {

    protected $APILOGIN;
    protected $TransactionKey;
    protected $Endpoint;

    public function __construct($APILogin = "", $TransactionKey = "", $Endpoint = "") {
        $this->APILOGIN = $APILogin;
        $this->TransactionKey = $TransactionKey;
        $this->Endpoint = $Endpoint;
        $this->getUnsettledTransactionListRequest();
    }

    public function curl_request($xmlContent, $contentType) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->Endpoint);
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

    public function getUnsettledTransactionListRequest() {
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

        $result = $this->curl_request($xmlContent, 'xml');
        foreach ($result['transactions'] as $transactions) {
            $this->getTransactionDetailsRequest($transactions['transId']);
        }
    }

    public function getSettledBatchListRequest() {
        $CalCurrentDate = date('Y-m-d h:m:s');
        $StartDate = str_replace('+00:00', 'Z', gmdate('c', strtotime($CalCurrentDate)));
        $CalMonth = date('Y-m-d h:m:s', strtotime('-1 day'));
        $MonthLastDate = str_replace('+00:00', 'Z', gmdate('c', strtotime($CalMonth)));

        $xmlContent = '<getSettledBatchListRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                <name>' . $this->APILOGIN . '</name>
                                <transactionKey>' . $this->TransactionKey . '</transactionKey>
                            </merchantAuthentication>
                            <includeStatistics>true</includeStatistics>
                            <firstSettlementDate>' . $MonthLastDate . '</firstSettlementDate>
                            <lastSettlementDate>' . $StartDate . '</lastSettlementDate>
                        </getSettledBatchListRequest>';

        $result = $this->curl_request($xmlContent, 'xml');
        if ($result['messages']['resultCode'] == "Ok") {
            foreach ($result['batchList']['batch'] as $batch) {
                $this->getTransactionListRequest($batch['batchId']);
            }
        }
    }

    public function getTransactionListRequest($batchID) {
        $xmlContent = '<getTransactionListRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                 <name>' . $this->APILOGIN . '</name>
                                 <transactionKey>' . $this->TransactionKey . '</transactionKey>
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

        $result = $this->curl_request($xmlContent, 'xml');
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
                $this->getTransactionDetailsRequest($transactionID);
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
            $this->getTransactionDetailsRequest($transactionID);
        }
    }

    public function getTransactionDetailsRequest($transactionID) {
        $xmlContent = '<getTransactionDetailsRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
                            <merchantAuthentication>
                                 <name>' . $this->APILOGIN . '</name>
                                 <transactionKey>' . $this->TransactionKey . '</transactionKey>
                            </merchantAuthentication>
                            <transId>' . $transactionID . '</transId>
                        </getTransactionDetailsRequest>';
        $result = $this->curl_request($xmlContent, 'xml');
        echo "<pre>";
        print_r($result);
        echo "<pre>";
    }

}

