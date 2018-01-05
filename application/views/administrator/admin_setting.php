<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Admin Setting</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>Admin Setting</h3>
            <div class="col-md-12">
                <form class="form-horizontal" id="saveadminsetting" action="<?php echo site_url('/admin/save_setting/'); ?>" method="POST">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" name="user" class="col-xs-10 col-sm-8 validate[required]" value="<?php echo (!empty($admin_info[0]->username)) ? $admin_info[0]->username : ""; ?>" placeholder="USERNAME"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" name="email" value="<?php echo (!empty($admin_info[0]->email)) ? $admin_info[0]->email : ""; ?>" class="col-xs-10 col-sm-8 validate[required,custom[email]]" placeholder="EMAIL"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="password" id="password" name="password" class="col-xs-10 col-sm-8" value="" placeholder="ENTER PASSWORD"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="password" name="pass2" class="col-xs-10 col-sm-8 validate[equals[password]]" value="" placeholder="CONFIRM ENTER PASSWORD"/>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success" id="SaveAdminSettings">
                        Save
                        <i class="ace-icon fa fa-check icon-on-right bigger-110"></i>
                    </button>
                    <div class="clear"></div>
                    <div class="space"></div>
                    <div class="alert alert-success col-xs-10 col-sm-8 displayMessage" style="display: none;">
                        <p>Success!</p>
                    </div>
                </form>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->