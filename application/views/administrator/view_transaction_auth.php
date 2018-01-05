<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Transaction Data</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>Transaction Data</h3>
            <div class="col-md-12">
                <div class="overflowdata">
                    <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Merchant Type</th>
                                <th>Email</th>
                                <th>Transaction Date</th>
                                <th>card Number</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Transaction ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Merchant Type</th>
                                <th>Email</th>
                                <th>Transaction Date</th>
                                <th>card Number</th>
                                <th>Status</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            if (!empty($transaction)) {
                                foreach ($transaction as $transact) {
                                    ?>
                                    <tr>
                                        <td><a href="<?php echo site_url("admin/view_data/" . $transact->mer_type . "/" . $transact->transaction_id . "/" . $transact->user_id); ?>"><?php echo (!empty($transact->transaction_id)) ? $transact->transaction_id : "--"; ?></a></td>
                                        <td><?php echo (!empty($transact->first_name)) ? $transact->first_name : "--"; ?></td>
                                        <td><?php echo (!empty($transact->last_name)) ? $transact->last_name : "--"; ?></td>
                                        <td><?php echo (!empty($transact->mer_type)) ? $transact->mer_type : "--"; ?></td>
                                        <td><?php echo (!empty($transact->email)) ? $transact->email : "--"; ?></td>
                                        <td><?php echo (!empty($transact->card_num)) ? $transact->date_of_tran2 : "--"; ?></td>
                                        <td><?php echo (!empty($transact->card_num)) ? $transact->card_num : "--"; ?></td>
                                        <td><?php echo (!empty($transact->matched)) ? $transact->matched : "--"; ?></td>
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