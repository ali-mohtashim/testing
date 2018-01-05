<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Charge Back Data</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>Charge Back Data</h3>
            <div class="col-md-12">
                <div class="row">
                    <div class="filtraion">
                        <form id="filteform" action="" method="POST">
                            <div class="large-6 columns">
                                <label for="trans_id">Merchant Processor: &nbsp;&nbsp;  </label>
                                <select id="merch_processor">
                                    <option value="">Select Merchant Processor</option>
                                    <?php
                                    $arr = array();
                                    if (!empty($chargBack)) {
                                        foreach ($chargBack as $charge) {
                                            $commonData = unserialize($charge->csv_data);
                                            $arr[] = $commonData->merchantprocessor;
                                        }
                                        $unique_data = array_unique($arr);
                                        foreach ($unique_data as $val) {
                                            ?>
                                            <option value="<?php echo $val; ?>"><?php echo $val; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="case_id">Case Number: &nbsp;&nbsp;  </label>
                                <input id="case_id" name="case_id" type="text" /><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="case_id">Merchant Number: &nbsp;&nbsp;  </label>
                                <input id="merch_no" name="merch_no" type="text" /><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="card_nos">Card Number: &nbsp;&nbsp;  </label>
                                <input id="card_nos" name="card_nos" type="text" maxlength="16"/><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="mer_name">Merchant Name: &nbsp;&nbsp;  </label>
                                <input id="mer_name" name="mer_name" type="text" maxlength="16"/><br/>
                            </div>

                            <div class="large-6 columns">
                                <label for="froms">Date (From): &nbsp;&nbsp;  </label>
                                <input id="froms" name="froms" type="text"/><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="tos">Date (To): &nbsp;&nbsp;  </label>
                                <input id="tos" name="tos" type="text" /><br/>
                            </div>
                            <div class="clear"></div>
                            <div class="large-12 columns middle">
                                <button class="button radius" id="filters">Filter</button><button class="button radius" id="resetForms">Reset</button>
                            </div>
                            <div class="clear"></div>
                        </form>
                    </div>
                </div>
                <div class="overflowdata">
                    <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Merchant Number</th>
                                <th>Merchant Processor</th>
                                <th>Merchant DBA</th>
                                <th>Merchant Name</th>
                                <th>Case Number</th>
                                <th>Received Date</th>
                                <th>Card Holder No</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Merchant Number</th>
                                <th>Merchant Processor</th>
                                <th>Merchant DBA</th>
                                <th>Merchant Name</th>
                                <th>Case Number</th>
                                <th>Received Date</th>
                                <th>Card Holder No</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            if (!empty($chargBack)) {
                                foreach ($chargBack as $data) {
                                    $commonData = unserialize($data->csv_data);
                                    ?>
                                    <tr>
                                        <td><?php echo (!empty($data->id)) ? $data->id : "--"; ?></td>
                                        <td><?php echo (!empty($data->merchant_number)) ? $data->merchant_number : "--"; ?></td>
                                        <td><?php echo (!empty($commonData->merchantprocessor)) ? $commonData->merchantprocessor : "--"; ?></td>
                                        <td><?php echo (!empty($data->merchant_dba)) ? $data->merchant_dba : "--"; ?></td>
                                        <td><?php echo (!empty($data->merchant_name)) ? $data->merchant_name : "--"; ?></td>
                                        <td><?php echo (!empty($data->case_number)) ? $data->case_number : "--"; ?></td>
                                        <td><?php echo (!empty($data->received_date)) ? $data->received_date : "--"; ?></td>
                                        <td><?php echo (!empty($data->cardholder_number)) ? $data->cardholder_number : "--"; ?></td>
                                        <td><a href="<?php echo site_url('/admin/view_cb_data/' . $data->id); ?>">View Details</a></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->