<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_manager extends CI_Model {

    public function __construct() {
        parent::__construct();
// Your own constructor code
    }

    public function SelectAll($table) {
        $query = $this->db->get($table);
        if ($query->result() > 0) {
            return $query->result();
        }
    }

    public function SelectByID($table, $key, $value) {
        $query = $this->db->get_where($table, array($key => $value));
        if ($query->result() > 0) {
            return $query->result();
        }
    }

    public function SelectByMultipleCol($table, $data) {
        $query = $this->db->get_where($table, $data);
        if ($query->result() > 0) {
            return $query->result();
        }
    }

    public function SelectWithJoin($mertype) {
        $query = $this->db->query("SELECT m.*,a.`username` FROM `merchant` m INNER JOIN `admin` a ON a.`id` = m.`user_id` WHERE m.`mer_type`='$mertype'");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function SelectWithJoinSage($mertype) {
        $query = $this->db->query("SELECT s.*,sml.`mer_name` FROM `sage` s
                                    INNER JOIN `sage_merchant_list` sml
                                    ON s.`merchant`=sml.`mer_id`
                                    WHERE s.`user_id`='$mertype'");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function SelectWithSageMerchants() {
        $query = $this->db->query("SELECT sml.`mer_id`,sml.`mer_name` FROM `sage` s
                                    INNER JOIN `sage_merchant_list` sml
                                    ON s.`merchant`=sml.`mer_id`
                                    GROUP BY sml.`mer_name`");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function GroupByCol($table, $column) {
        $query = $this->db->query("SELECT $column FROM $table GROUP BY $column");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function SelectWithJoinTrans($userid) {
        $query = $this->db->query("SELECT a.`username`,t.* FROM `transaction` t
                                    INNER JOIN `admin` a
                                    ON t.`user_id` = a.`id` 
                                    WHERE t.`user_id`='$userid'
                                    LIMIT 2000");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

//    public function SelectWithJoinTrans() {
//        $query = $this->db->query("SELECT a.`username`,t.*, SUBSTR(al.`apicard`,13,15) AS matched FROM `transaction` t
//INNER JOIN `admin` a
//ON t.`user_id` = a.`id`
//INNER JOIN `alert` al
//ON t.`card_num` LIKE CONCAT('%',SUBSTR(t.`card_num`,13,15)) = al.`apicard` LIKE CONCAT('%',SUBSTR(al.`apicard`,13,15)) LIMIT 4000");
//        foreach ($query->result() as $row) {
//            return $query->result();
//        }
//    }

    public function SelectWithJoinTransWhere($mer_id) {
        $query = $this->db->query("SELECT a.`username`,t.* FROM `transaction` t
                                    INNER JOIN `admin` a
                                    ON t.`user_id` = a.`id`
                                    WHERE t.`mer_id`='$mer_id'");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function SelectDistinct($table, $key) {
        $query = $this->db->query("SELECT DISTINCT $key FROM $table");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function SelectWithJoinWHERE($mertype, $userid) {
        $query = $this->db->query("SELECT m.*,a.`username` FROM `merchant` m INNER JOIN `admin` a ON a.`id` = m.`user_id` WHERE m.`mer_type`='$mertype' AND m.`user_id`='$userid'");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function SelectByArray($table, $data) {
        $query = $this->db->get_where($table, $data);
        if ($query->result() > 0) {
            return $query->result();
        }
    }

    public function SelectForMatchAlert($table, $value1, $value2, $value3 = "") {
        $query = $this->db->query("SELECT * FROM $table WHERE `cc_bin`='$value1' AND `card_num` LIKE '%$value2' AND view_status='$value3'");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function SelectForMatchAlertCB($table, $value1, $value2) {
        $query = $this->db->query("SELECT * FROM $table WHERE `received_date`='$value1' AND `cardholder_number` LIKE '%$value2'");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function SelectForMatchOnlyAlert($table, $value2) {
        $query = $this->db->query("SELECT apicard FROM $table WHERE `apicard` LIKE '%$value2'");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

//     public function SelectForMatchAlertCB($table,$table2, $value1, $value2) {
//        $query = $this->db->query("SELECT * FROM $table,$table2 WHERE `received_date`='$value1' AND `cardholder_number` LIKE '%$value2' AND `apicard` LIKE '%$value2' ");
//        foreach ($query->result() as $row) {
//            return $query->result();
//        }
//    }
//    public function SelectForMatchAlertCB($table, $value1, $value2) {
//        $query = $this->db->query("SELECT 
//                                        a.*,cb.*,t.*,
//                                        (
//                                          CASE
//                                            WHEN (a.`apicard` IS NULL) 
//                                            THEN 'Not Matched' 
//                                            ELSE 'Matched' 
//                                          END
//                                        ) AS alert_status,
//                                        (
//                                          CASE
//                                            WHEN (cb.`cardholder_number` IS NULL) 
//                                            THEN 'Not Matched'
//                                            ELSE 'Matched'
//                                          END
//                                        ) AS cb_status 
//                                      FROM
//                                        `transaction` t 
//                                        LEFT JOIN `charge_back` cb 
//                                          ON cb.`cardholder_number` = CONCAT(
//                                            t.`cc_bin`,
//                                            SUBSTR(t.`card_num`, 7, 15)
//                                          ) 
//                                        LEFT JOIN `alert` a 
//                                          ON a.`apicard` = CONCAT(
//                                            t.`cc_bin`,
//                                            SUBSTR(t.`card_num`, 7, 15)
//                                          ) 
//                                      WHERE t.`cc_bin` = '$value1' 
//                                        AND t.`card_num` LIKE '%$value2' LIMIT 1");
//        foreach ($query->result() as $row) {
//            return $query->result();
//        }
//    }
//    public function SelectForAlertMatch($table, $value1, $value2) {
//        $query = $this->db->query("SELECT * FROM $table WHERE `apicard` LIKE '$value1%' AND `apicard` LIKE '%$value2'");
//        foreach ($query->result() as $row) {
//            return $query->result();
//        }
//    }
    public function SelectForAlertMatch($transactionID) {
        $query = $this->db->query("SELECT *   FROM `alert` a
                                    INNER JOIN `transaction` t
                                    ON a.`apicard` LIKE CONCAT('%',SUBSTR(a.`apicard`,13,15)) = t.`card_num` LIKE CONCAT('%',SUBSTR(a.`apicard`,13,15))
                                    WHERE t.`transaction_id`='$transactionID'");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function SelectForMatchAlertMcol($table, $value1, $value2, $value3 = "", $value4) {
        $query = $this->db->query("SELECT * FROM $table WHERE `cc_bin`='$value1' AND `card_num` LIKE '%$value2' AND `date_of_tran2`='$value4' AND view_status='$value3'");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function GetTransactions() {
        $query = $this->db->query("SELECT t.`id`,t.`card_num` FROM `transaction` t WHERE t.`card_num` !=''");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function GetCB() {
        $query = $this->db->query("SELECT cb.`id`,cb.`cardholder_number` FROM `charge_back` cb WHERE cb.`cardholder_number` !=''");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function SelectForMatchAlertCh($table, $value1) {
        $query = $this->db->query("SELECT * FROM $table WHERE `cardholder_number` LIKE '%$value1%'");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function UpdateQuery($table, $cardMatchStart, $cardMatchEnd, $view_status, $data) {
        $query = $this->db->query("UPDATE $table SET view_status = '1', common_data = '$data' WHERE `cc_bin` = '$cardMatchStart' AND `card_num` LIKE '%$cardMatchEnd' AND view_status = '$view_status'");
    }

    public function ShowColumns($table) {
        $query = $this->db->query("SHOW FIELDS FROM $table");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function SelectByCompare($table, $key1, $value1, $key2, $value2) {
        $query = $this->db->get_where($table, array($key1 => $value1, $key2 => $value2));
        if ($query->result() > 0) {
            return $query->result();
        }
    }

    public function SelectByCompareBythree($table, $key1, $value1, $key2, $value2, $key3, $value3) {
        $query = $this->db->get_where($table, array($key1 => $value1, $key2 => $value2, $key3 => $value3));
        if ($query->result() > 0) {
            return $query->result();
        }
    }

    public function DeleteAll() {
        
    }

    public function DeleteByID($table, $key, $value) {
        $this->db->delete($table, array($key => $value));
    }

    public function CheckAlreadyExistByOne($table) {
        $query = $this->db->select('*, COUNT(*) as count');
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result();
    }

    public function CheckAlreadyExist($table, $key1, $value1, $key2, $value2) {
        $query = $this->db->where($key1, $value1);
        $this->db->or_where($key2, $value2);
        $query = $this->db->get($table);
        if ($query->result() > 0) {
            return $query->result();
        }
    }

    public function CheckAlreadyExistAND($table, $key1, $value1, $key2, $value2) {
        $query = $this->db->where($key1, $value1);
        $this->db->where($key2, $value2);
        $query = $this->db->get($table);
        if ($query->result() > 0) {
            return $query->result();
        }
    }

    public function CheckAlreadyExistANDWhere($table, $key1, $value1, $key2, $value2, $key3, $value3) {
        $query = $this->db->where($key1, $value1);
        $this->db->where($key2, $value2);
        $this->db->where($key3, $value3);
        $query = $this->db->get($table);
        if ($query->result() > 0) {
            return $query->result();
        }
    }

    public function Insert($table, $data) {
        $this->db->insert($table, $data);
    }

    public function Update($table, $data, $where) {
        $this->db->update($table, $data, $where);
    }

    public function UpdateByID($table, $data, $key, $value) {
        $this->db->update($table, $data, array($key => $value));
    }

    public function getAlerts() {
        $query = $this->db->query("SELECT 
                                    t.`card_num` IS NULL,
                                    'empty',
                                    CONCAT(
                                      t.`cc_bin`,
                                      SUBSTR(t.`card_num`, 7, 15)
                                    )
                                  ) AS lastcardno,
                                  t.`mer_type`,
                                  a.`apialerterid`,
                                  a.`apiamount`,
                                  a.`apicard`,
                                  a.`apicurrency`,
                                  a.`apicurrency`,
                                  a.`apiID`,
                                  a.`apitransactiondate`,
                                  a.`customerid`,
                                  a.`merchantid`,
                                  a.`merchantname` 
                                FROM
                                  `transaction` t 
                                  INNER JOIN `alert` a 
                                    ON a.`apicard` = CONCAT(
                                      t.`cc_bin`,
                                      SUBSTR(t.`card_num`, 7, 15)
                                    )");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function getProcessors() {
        $query = $this->db->query("SELECT `processor_id` FROM `transaction`
                                   WHERE `processor_id` IS NOT NULL
                                   GROUP BY `processor_id`");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

    public function getTransactionByUser($column, $id) {
        $query = $this->db->query("SELECT $column FROM `admin` a
                                    INNER JOIN `transaction` t
                                    ON a.`id` = t.`user_id`
                                    WHERE a.`id`='$id' AND $column IS NOT NULL
                                    GROUP BY $column");
        foreach ($query->result() as $row) {
            return $query->result();
        }
    }

}
