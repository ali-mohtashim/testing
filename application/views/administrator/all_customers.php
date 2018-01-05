<div class="main-content">
    <div class="preloader"><img src="<?php echo ADMIN_IMAGE_URL; ?>loader3.gif"/></div>
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">All Customer</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <hr/>
        <div class="page-content">
            <h2>All Customer</h2>
            <div class="col-md-12 backround_merchant">
                <table id="example3" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (!empty($all_customers)) {
                            foreach ($all_customers as $key => $all_customer) {
                                $checkURL = ($this->current_user == "1") ? site_url('admin/view_transaction/' . $all_customer->id) : "#";
                                ?>
                                <tr>
                                    <td><?php echo $all_customer->id; ?></td>
                                    <td><a href="<?php echo $checkURL; ?>"><?php echo $all_customer->username; ?></a></td>
                                    <td><?php echo $all_customer->email; ?></td>
                                    <?php if ($key > 0) { ?>
                                        <td><a href="<?php echo site_url('/admin/del_user/' . $all_customer->id); ?>">Delete</a> | <?php if ($all_customer->status == "0") { ?><a href="<?php echo site_url('/admin/enable_user/' . $all_customer->id); ?>"><i class="fa fa-unlock" aria-hidden="true"></i> Enable</a><?php } else { ?><a href="<?php echo site_url('/admin/suspend_user/' . $all_customer->id); ?>"><i class="fa fa-lock" aria-hidden="true"></i> Disable</a><?php } ?></td>
                                    <?php } else { ?>
                                        <td><a>Delete</a></td>
                                    <?php } ?>
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