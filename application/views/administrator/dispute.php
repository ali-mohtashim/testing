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
                                <th>Card Number</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Address</th>
                                <th>Zip</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Merchant ID</th>
                                <th>Card Number</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Address</th>
                                <th>Zip</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            if (!empty($Apidata)) {
                                foreach ($Apidata as $data) {
                                    ?>
                                    <tr>
                                        <td><?php echo (!empty($data->merchantid)) ? $data->merchantid : "--"; ?></td>
                                        <td><?php echo (!empty($data->cb_cardnumber)) ? $data->cb_cardnumber : "--"; ?></td>
                                        <td><?php echo (!empty($data->cbc_namefirst)) ? $data->cbc_namefirst : "--"; ?></td>
                                        <td><?php echo (!empty($data->cbc_namelast)) ? $data->cbc_namelast : "--"; ?></td>
                                        <td><?php echo (!empty($data->cbc_phone)) ? $data->cbc_phone : "--"; ?></td>
                                        <td><?php echo (!empty($data->cbc_email)) ? $data->cbc_email : "--"; ?></td>
                                        <td><?php echo (!empty($data->cbc_city)) ? $data->cbc_city : "--"; ?></td>
                                        <td><?php echo (!empty($data->cbc_state)) ? $data->cbc_state : "--"; ?></td>
                                        <td><?php echo (!empty($data->bill_address)) ? $data->bill_address : "--"; ?></td>
                                        <td><?php echo (!empty($data->cbc_zip)) ? $data->cbc_zip : "--"; ?></td>
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