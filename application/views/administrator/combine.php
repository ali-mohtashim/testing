<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">API Data</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>API Data</h3>
            <div class="col-md-12">
                <div class="overflowdata">
                    <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Rec ID</th>
                                <th>GW ID</th>
                                <th>Merchant ID</th>
                                <th>Merchant Processor</th>
                                <th>Merchant Name</th>
                                <th>GW Transaction Date</th>
                                <th>GW Amount ID</th>
                                <th>GW Transaction Type</th>
                                <th>GW Transaction ID</th>
                                <th>Credit Card Number</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Rec ID</th>
                                <th>GW ID</th>
                                <th>Merchant ID</th>
                                <th>Merchant Processor</th>
                                <th>Merchant Name</th>
                                <th>GW Transaction Date</th>
                                <th>GW Amount ID</th>
                                <th>GW Transaction Type</th>
                                <th>GW Transaction ID</th>
                                <th>Credit Card Number</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            if (!empty($Apidata)) {
                                foreach ($Apidata as $data) {
                                    ?>
                                    <tr>
                                        <td><?php echo (!empty($data->recid)) ? $data->recid : "--"; ?></td>
                                        <td><?php echo (!empty($data->gwid)) ? $data->gwid : "--"; ?></td>
                                        <td><?php echo (!empty($data->merchantid)) ? $data->merchantid : "--"; ?></td>
                                        <td><?php echo (!empty($data->merch_processor)) ? $data->merch_processor : "--"; ?></td>
                                        <td><?php echo (!empty($data->merchant_name)) ? $data->merchant_name : "--"; ?></td>
                                        <td><?php echo (!empty($data->gw_transdate)) ? $data->gw_transdate : "--"; ?></td>
                                        <td><?php echo (!empty($data->gw_transamount)) ? $data->gw_transamount : "--"; ?></td>
                                        <td><?php echo (!empty($data->gw_transtypeid)) ? $data->gw_transtypeid : "--"; ?></td>
                                        <td><?php echo (!empty($data->gw_transid)) ? $data->gw_transid : "--"; ?></td>
                                        <td><?php echo (!empty($data->ccnumber)) ? $data->ccnumber : "--"; ?></td>
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