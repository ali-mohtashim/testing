<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Charge Back</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>Charge Back</h3>
            <div class="col-md-12 cb">
                <div class="col-md-9 topm">
                    <ul id="tabbs">
                        <li><a href="#dispute_data" class="selected">Dispute Variables</a></li>
                        <li><a href="#nmi_data">NMI Data</a></li>
                        <li><a href="#csv_data">CSV Data / CB data</a></li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="tabs" id="dispute_data">
                        <h5>Client Info</h5>
                        <div class="blocks">
                            <span><strong>Client First Name:</strong></span><span><?php //echo CheckEmpty(); ?></span>
                        </div>
                        <div class="blocks">
                            <span><strong> Client Last Name:</strong></span><span>---</span>
                        </div>
                        <div class="blocks">
                            <span><strong>Client Phone:</strong></span>	<span>---</span>
                        </div>
                        <div class="blocks">
                            <span> <strong>Client Email:</strong></span><span><span>----</span>
                        </div>
                        <div class="blocks">
                            <span><strong> Client IP Address:</strong></span>---</span>
                        </div>
                        <hr/>
                        <h5>Address Info</h5>
                        <div class="blocks">
                            <span><strong>Address 1:</strong></span><span>---</span>
                        </div>
                        <div class="blocks">
                            <span><strong>City:</strong></span><span>---</span>
                        </div>
                        <div class="blocks">
                            <span><strong>State:</strong></span><span>---</span>
                        </div>
                        <div class="blocks">
                            <span><strong>Zip Code:</strong></span><span>---</span>
                        </div>
                        <hr/>
                        <h5>Card Info</h5>
                        <div class="blocks">
                            <span><strong>Acct Number:</strong></span><span>---</span>
                        </div>
                        <div class="blocks">
                            <span><strong>AVS Status:</strong></span><span>---</span>
                        </div>
                        <div class="blocks">
                            <span><strong>CVV Status:</strong></span><span>---</span>
                        </div>
                        <hr/>
                        <h5>All Date Related Info</h5>
                        <div class="blocks">
                            <span><strong>Transaction Date:</strong></span><span>---</span>
                        </div>
                        <h5>Transaction/Sale Info</h5>
                        <div class="blocks">
                            <span><strong>Transaction Amount:</strong></span><span>---</span>
                        </div>
                        <div class="blocks">
                            <span><strong>Chargeback Amount:</strong></span><span>---</span>
                        </div>
                        <div class="blocks">
                            <span><strong>Order ID:</strong></span><span>---</span>
                        </div>
                        <div class="blocks">
                            <span><strong>Offer Type:</strong></span><span>---</span>
                        </div>
                        <div class="blocks">
                            <span><strong>Reason Code:</strong></span><span>---</span>
                        </div>
                        <div class="blocks">
                            <span><strong>Reason Msg:</strong></span><span>---</span>
                        </div><div class="blocks">
                            <span><strong>Bank Comments:</strong></span><span>---</span>
                        </div>  

                    </div>
                    <div class="clearfix"></div>
                    <div class="tabs" id="nmi_data" style="display:none;">
                        Data not found
                    </div>
                    <div class="clearfix"></div>
                    <div class="tabs" id="csv_data" style="display:none;">
                        <?php
                        if (!empty($chargBack_db_col)) {
                            foreach ($chargBack_db_col as $key => $chargBack_db) {
                                $change = (array) $chargBack[0];
                                ?>
                                <div class="col-md-12">
                                    <div class="col-md-5 colheading"><?php echo ucwords(str_replace("_", " ", $chargBack_db->Field)); ?></div>
                                    <div class="col-md-7 colvalue"><?php echo (!empty($change[$chargBack_db->Field])) ? $change[$chargBack_db->Field] : "-"; ?></div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->