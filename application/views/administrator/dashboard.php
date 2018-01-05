<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Dashboard</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <div class="page-header">
                <h1>
                    Dashboard
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        overview &amp; stats
                    </small>
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="space-6"></div>

                        <div class="col-sm-3 infobox-container">
                            <div class="infobox infobox-green">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-shopping-bag"></i>
                                </div>

                                <div class="infobox-data">
                                    <span class="infobox-data-number"><?php echo (!empty($merchants[0]->count)) ? $merchants[0]->count : "" ?></span>
                                    <div class="infobox-content">Total Merchants</div>
                                </div>
                            </div>

                            <div class="infobox infobox-blue">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-undo"></i>
                                </div>
                                <div class="infobox-data">
                                    <span class="infobox-data-number"><?php echo (!empty($chargeback[0]->count)) ? $chargeback[0]->count : "" ?></span>
                                    <div class="infobox-content">Charge Back</div>
                                </div>
                            </div>

                            <div class="infobox infobox-purple2">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-database"></i>
                                </div>
                                <div class="infobox-data">
                                    <span class="infobox-data-number"><?php echo (!empty($transactions[0]->count)) ? $transactions[0]->count : "" ?></span>
                                    <div class="infobox-content">Transactions</div>
                                </div>
                            </div>
                            <div class="infobox infobox-pink">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-users"></i>
                                </div>
                                <div class="infobox-data">
                                    <span class="infobox-data-number"><?php echo (!empty($users[0]->count)) ? $users[0]->count : "" ?></span>
                                    <div class="infobox-content">Customers</div>
                                </div>

                            </div>
                        </div>

                        <div class="vspace-12-sm"></div>

                        <!-- <div class="col-sm-5">
                            <div class="widget-box">
                                <div class="widget-header widget-header-flat widget-header-small">
                                    <h5 class="widget-title">
                                        <i class="ace-icon fa fa-signal"></i>
                                        Traffic Sources
                                    </h5>

                                    <div class="widget-toolbar no-border">
                                        <div class="inline dropdown-hover">
                                            <button class="btn btn-minier btn-primary">
                                                This Week
                                                <i class="ace-icon fa fa-angle-down icon-on-right bigger-110"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
                                                <li class="active">
                                                    <a href="#" class="blue">
                                                        <i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
                                                        This Week
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#">
                                                        <i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                        Last Week
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#">
                                                        <i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                        This Month
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#">
                                                        <i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                        Last Month
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div id="piechart-placeholder"></div>

                                        <div class="hr hr8 hr-double"></div>

                                        <div class="clearfix">
                                            <div class="grid3">
                                                <span class="grey">
                                                    <i class="ace-icon fa fa-facebook-square fa-2x blue"></i>
                                                    &nbsp; likes
                                                </span>
                                                <h4 class="bigger pull-right">1,255</h4>
                                            </div>

                                            <div class="grid3">
                                                <span class="grey">
                                                    <i class="ace-icon fa fa-twitter-square fa-2x purple"></i>
                                                    &nbsp; tweets
                                                </span>
                                                <h4 class="bigger pull-right">941</h4>
                                            </div>

                                            <div class="grid3">
                                                <span class="grey">
                                                    <i class="ace-icon fa fa-pinterest-square fa-2x red"></i>
                                                    &nbsp; pins
                                                </span>
                                                <h4 class="bigger pull-right">1,050</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>/.col -->
                    </div><!-- /.row -->
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->