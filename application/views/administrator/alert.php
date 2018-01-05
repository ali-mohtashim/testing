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
                                <th>Merchant ID</th>
                                <th>Merchant Name</th>
                                <th>Customer ID</th>
                                <th>API ID</th>
                                <th>API Amount</th>
                                <th>API Currency</th>
                                <th>API Card</th>
                                <th>API Transaction Date</th>
                                <th>API Alert ID</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Merchant ID</th>
                                <th>Merchant Name</th>
                                <th>Customer ID</th>
                                <th>API ID</th>
                                <th>API Amount</th>
                                <th>API Currency</th>
                                <th>API Card</th>
                                <th>API Transaction Date</th>
                                <th>API Alert ID</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            if (!empty($Apidata)) {
                                foreach ($Apidata as $data) {
                                    ?>
                                    <tr>
                                        <td><?php echo (!empty($data->merchantid)) ? $data->merchantid : "--"; ?></td>
                                        <td><?php echo (!empty($data->merchantname)) ? $data->merchantname : "--"; ?></td>
                                        <td><?php echo (!empty($data->customerid)) ? $data->customerid : "--"; ?></td>
                                        <td><?php echo (!empty($data->apiID)) ? $data->apiID : "--"; ?></td>
                                        <td><?php echo (!empty($data->apiamount)) ? $data->apiamount : "--"; ?></td>
                                        <td><?php echo (!empty($data->apicurrency)) ? $data->apicurrency : "--"; ?></td>
                                        <td><?php echo (!empty($data->apicard)) ? $data->apicard : "--"; ?></td>
                                        <td><?php echo (!empty($data->apitransactiondate)) ? $data->apitransactiondate : "--"; ?></td>
                                        <td><?php echo (!empty($data->apialerterid)) ? $data->apialerterid : "--"; ?></td>
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