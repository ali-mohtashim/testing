<div class="main-content">
    <div class="preloader"><img src="<?php echo ADMIN_IMAGE_URL; ?>loader3.gif"/></div>
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">All Merchants</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <hr/>
        <div class="page-content">
            <h2>All Merchants</h2>
            <?php
            $error = $this->session->flashdata('checkError');
            if (!empty($error)) {
                ?>
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Error: </strong> <?php echo $error; ?>
                </div>
                <?php
            }
            ?>
            <hr/>
            <div class="col-md-12 backround_merchant">
                <h2>SECURE.NMI</h2>
                <hr/>
                <table id="example" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Login</th>
                            <th>password</th>
                            <th>Endpoint</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Login</th>
                            <th>password</th>
                            <th>Endpoint</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (!empty($allmerchants_nmi)) {
                            foreach ($allmerchants_nmi as $data) {
                                ?>
                                <tr>
                                    <td><?php echo (!empty($data->id)) ? $data->id : "--"; ?></td>
                                    <td><?php echo (!empty($data->username)) ? $data->username : "--"; ?></td>
                                    <td><?php echo (!empty($data->m_user)) ? $data->m_user : "--"; ?></td>
                                    <td><?php echo (!empty($data->m_pass)) ? $data->m_pass : "--"; ?></td>
                                    <td><?php echo (!empty($data->m_end_point)) ? $data->m_end_point : "--"; ?></td>
                                    <td><a class="sync" href="<?php echo site_url('admin/view_merchant/' . $data->id) ?>"> Sync</a> | <a href="<?php echo site_url('admin/view_transaction/' . $data->id) ?>">View Transaction</a></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <hr/>
            <div class="col-md-12 backround_merchant">
                <h2>Authorize.net</h2>
                <hr/>
                <table id="example2" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>API Login ID</th>
                            <th>Transaction Key</th>
                            <th>Endpoint</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>API Login ID</th>
                            <th>Transaction Key</th>
                            <th>Endpoint</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (!empty($allmerchants_auth)) {
                            foreach ($allmerchants_auth as $data) {
                                ?>
                                <tr>
                                    <td><?php echo (!empty($data->id)) ? $data->id : "--"; ?></td>
                                    <td><?php echo (!empty($data->username)) ? $data->username : "--"; ?></td>
                                    <td><?php echo (!empty($data->api_login_id)) ? $data->api_login_id : "--"; ?></td>
                                    <td><?php echo (!empty($data->api_tran_key)) ? $data->api_tran_key : "--"; ?></td>
                                    <td><?php echo (!empty($data->m_end_point)) ? $data->m_end_point : "--"; ?></td>
                                    <td><a class="sync" href="<?php echo site_url('admin/get_all_transactions/' . $data->id) ?>"> Sync</a> | <a href="<?php echo site_url('admin/view_transaction_auth/' . $data->id) ?>">View Transaction</a></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <hr/>
            <div class="col-md-12 backround_merchant">
                <h2>Sage One</h2>
                <hr/>
                <table id="example3" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Transaction Key</th>
                            <th>Batch ID</th>
                            <th>Endpoint</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Transaction Key</th>
                            <th>Batch ID</th>
                            <th>Endpoint</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (!empty($allmerchants_sage)) {
                            foreach ($allmerchants_sage as $data) {
                                ?>
                                <tr>
                                    <td><?php echo (!empty($data->id)) ? $data->id : "--"; ?></td>
                                    <td><?php echo (!empty($data->client_id)) ? $data->client_id : "--"; ?></td>
                                    <td><?php echo (!empty($data->client_secret)) ? $data->client_secret : "--"; ?></td>
                                    <td><?php echo (!empty($data->sign_secret)) ? $data->sign_secret : "--"; ?></td>
                                    <td><?php echo (!empty($data->m_end_point)) ? $data->m_end_point : "--"; ?></td>
                                    <td><a class="sync" href="<?php echo site_url('admin/view_merchant/' . $data->id) ?>"> Sync</a> | <a href="<?php echo site_url('admin/makeAlert/') ?>">Match</a>| <a href="<?php echo site_url('admin/view_transaction/' . $data->id) ?>">View Transaction</a></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->