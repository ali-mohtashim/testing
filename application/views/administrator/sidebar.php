<div id="sidebar" class="sidebar responsive ace-save-state">
    <script type="text/javascript">
        try {
            ace.settings.loadState('sidebar')
        } catch (e) {

        }
    </script>
    <ul class="nav nav-list">
        <li class="<?php echo ($this->current_url == "dashboard") ? 'active' : ''; ?>">
            <a href="<?php echo site_url('/admin/dashboard/'); ?>">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text"> Dashboard </span>
            </a>
            <b class="arrow"></b>
        </li>
        <?php
        if ($this->current_user == "1") {
            ?>
            <li class="<?php echo ($this->current_url == "all_customer") ? 'active' : ''; ?>">
                <a href="<?php echo site_url('/admin/all_customer/'); ?>">
                    <i class="menu-icon fa fa-users"></i>
                    <span class="menu-text"> All Customers </span>
                </a>
                <b class="arrow"></b>
            </li>
            <?php
        }
        ?>
        <li class="<?php echo ($this->current_url == "add_merchant") ? 'active' : ''; ?>">
            <a href="<?php echo site_url('/admin/add_merchant/'); ?>">
                <i class="menu-icon fa fa-plus"></i>
                <span class="menu-text"> Add Merchants </span>
            </a>
            <b class="arrow"></b>
        </li>
        <li class="<?php echo ($this->current_url == "all_merchant") ? 'active' : ''; ?>">
            <a href="<?php echo site_url('/admin/all_merchant'); ?>">
                <i class="menu-icon fa fa-shopping-bag"></i>
                <span class="menu-text"> All Merchants </span>
            </a>
            <b class="arrow"></b>
        </li>
        <li class="<?php echo ($this->current_url == "transaction_data") ? 'active' : ''; ?>">
            <a href="<?php echo site_url('/admin/transaction_data/'); ?>">
                <i class="menu-icon fa fa-database"></i>
                <span class="menu-text"> Transaction </span>
            </a>
            <b class="arrow"></b>
        </li>
        <li class="<?php echo ($this->current_url == "sage_view") ? 'active' : ''; ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-money"></i>
                <span class="menu-text"> Sage </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu nav-show">
                <li class="">
                    <a href="<?php echo site_url('/admin/sage_view/'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Sage Transaction
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?php echo site_url('/admin/ftp_connect_sage/'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        FTP Setting
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?php echo site_url('/admin/connect_sage_ftp'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Import CSV From FTP
                    </a>
                    <b class="arrow"></b>
                </li>
            </ul>
        </li>
        <li class="<?php echo ($this->current_url == "charge_back") ? 'active' : ''; ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-undo" aria-hidden="true"></i>
                <span class="menu-text"> Charge Back </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu nav-show">
                <li class="">
                    <a href="<?php echo site_url('/admin/charge_back/'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Charge Back

                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?php echo site_url('/admin/sftp/'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        FTP Setting
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?php echo site_url('/admin/connect_ftp/'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Import CSV From FTP
                    </a>
                    <b class="arrow"></b>
                </li>
            </ul>
        </li>
        <li class="<?php echo ($this->current_url == "import_csv") ? 'active' : ''; ?>">
            <a href="<?php echo site_url('/admin/import_csv/'); ?>">
                <i class="menu-icon fa fa-file-excel-o"></i>
                <span class="menu-text"> Import CSV </span>
            </a>
            <b class="arrow"></b>
        </li>
        <li class="<?php echo ($this->current_url == "alert") ? 'active' : ''; ?>">
            <a href="<?php echo site_url('/admin/alert_data/'); ?>">
                <i class="menu-icon fa fa-bell" aria-hidden="true"></i>
                <span class="menu-text"> Alerts </span>
            </a>
            <b class="arrow"></b>
        </li>


        <li class="<?php echo ($this->current_url == "template") ? 'active' : ''; ?>">
            <a href="<?php echo site_url('/admin/template/'); ?>">
                <i class="menu-icon fa fa-cubes" aria-hidden="true"></i>
                <span class="menu-text"> Email Template</span>
            </a>
            <b class="arrow"></b>
        </li>


    </ul><!-- /.nav-list -->
    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>