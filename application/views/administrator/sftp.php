<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">FTP Credentials</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>FTP Credentials</h3>
            <div class="col-md-8">
                <div class="form-group col-md-12">
                    <form action="<?php echo site_url('/admin/save_sftp'); ?>" method="POST">
                        <div class="col-sm-8 clearfix">
                            <label>Select Protocol</label>
                            <select name="protocol" class="col-xs-10 col-sm-12 validate[required]">
                                <option value="" <?php echo CheckEquality($ftp[0]->protocol, "", "selected"); ?>>Select Protocol</option>
                                <option value="ftp" <?php echo CheckEquality($ftp[0]->protocol, "ftp", "selected"); ?>>FTP</option>
                                <option value="sftp" <?php echo CheckEquality($ftp[0]->protocol, "sftp", "selected"); ?>>SFTP</option>
                            </select>
                        </div>
                        <div class="spacer"></div>
                        <div class="col-sm-7 clearfix">
                            <label>FTP Host</label>
                            <input type="text" name="host" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo CheckEmpty($ftp[0]->ftp_host); ?>" placeholder="FTP HOST: example.com"/>
                        </div>
                        <div class="col-sm-1 clearfix">
                            <label>Port</label>
                            <input type="text" name="port" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo CheckEmpty($ftp[0]->port); ?>" placeholder="Port"/>
                        </div>
                        <div class="spacer"></div>
                        <div class="col-sm-8 clearfix">
                            <label>Username</label>
                            <input type="text" name="username" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo CheckEmpty($ftp[0]->username); ?>" placeholder="User name"/>
                        </div>
                        <div class="spacer"></div>
                        <div class="col-sm-8 clearfix">
                            <label>Password</label>
                            <input type="password" name="password" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo CheckEmpty($ftp[0]->password); ?>" placeholder="Password"/>
                        </div>
                        <div class="spacer"></div>
                        <div class="col-sm-8 clearfix">
                            <label>Directory</label>
                            <input type="text" name="directory" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo CheckEmpty($ftp[0]->dir); ?>" placeholder="/public_html/"/>
                        </div>
                        <div class="spacer"></div>
                        <div class="col-sm-8 clearfix">
                            <label>Filename</label>
                            <input type="text" name="filename" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo CheckEmpty($ftp[0]->filename); ?>" placeholder="e.g abc.csv"/>
                        </div>
                        <div class="spacer"></div>
                        <div class="col-sm-8 clearfix">
                            <input type="submit" name="submit" class="btn btn-success" value="Save Credentials"/>
                        </div>
                    </form> 
                </div>

            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->