<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">API Settings</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <h3>API Credentials Settings</h3>
            <div class="col-lg-5">
                <form class="form-horizontal" id="savesetting" action="<?php echo site_url('/admin/save_credentials/'); ?>" method="POST">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" name="user" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo (!empty($settings[0]->user)) ? $settings[0]->user : ""; ?>" placeholder="ENTER API USER"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="password" name="pass" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo (!empty($settings[0]->pass)) ? $settings[0]->pass : ""; ?>" placeholder="ENTER API PASSWORD"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" name="auth" disabled="disabled" value="<?php echo (!empty($settings[0]->user) && !empty($settings[0]->pass)) ? base64_encode($settings[0]->user . ":" . $settings[0]->pass) : ""; ?>" class="col-xs-10 col-sm-12" placeholder="Authorization"/>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success" id="saveCredentials">
                        Save
                        <i class="ace-icon fa fa-check icon-on-right bigger-110"></i>
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger" id="testCredentials">
                        Fetch Data
                        <i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
                    </button>
                    <div class="clear"></div>
                    <div class="space"></div>
                    <div class="alert alert-success col-xs-10 col-sm-8 displayMessage" style="display: none;">
                        <p>Success!</p>
                    </div>
                </form>
            </div>
            <div class="col-lg-7">
                <div class="showdata"><pre><code id=planets></code></pre></div>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->