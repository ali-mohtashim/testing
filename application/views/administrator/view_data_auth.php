<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Transaction ID: <?php echo $transactionID; ?></li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>Transaction Data</h3>
            <h5>Transaction of: <?php echo strtoupper($mer_type); ?></h5>
            <div class="col-md-12 topm">
                <div class="col-md-12 topm">
                    <div class="col-md-9 topm">
                        <ul id="tabbs">
                            <li><a href="#dispute_data" class="selected">Transaction Information</a></li>
                            <li><a href="#cb_data">CB Data</a></li>
                        </ul>
                        <div class="tabs" id="dispute_data">
                            <h5>Client Info</h5>
                            <div class="blocks">
                                <span><strong>Client First Name:</strong></span><span><?php echo CheckEmpty($transdata[0]->first_name); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong> Client Last Name:</strong></span><span><?php echo CheckEmpty($transdata[0]->last_name); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>Client Phone:</strong></span>	<span><?php echo CheckEmpty($transdata[0]->phone); ?></span>
                            </div>
                            <div class="blocks">
                                <span> <strong>Client Email:</strong></span><span><span><?php echo CheckEmpty($transdata[0]->email); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong> Client IP Address:</strong></span><?php //echo CheckEmpty($transdata[0]->first_name);                                         ?>--</span>
                            </div>
                            <hr/>
                            <h5>Address Info</h5>
                            <div class="blocks">
                                <span><strong>Address 1:</strong></span><span><?php echo CheckEmpty($transdata[0]->address1); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>Address 2:</strong></span><span><?php echo CheckEmpty($transdata[0]->address2); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>City:</strong></span><span><?php echo CheckEmpty($transdata[0]->city); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>State:</strong></span><span><?php echo CheckEmpty($transdata[0]->state); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>Zip Code:</strong></span><span><?php echo CheckEmpty($transdata[0]->postalcode); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>Country:</strong></span><span><?php echo CheckEmpty($transdata[0]->country); ?></span>
                            </div>
                            <hr/>
                            <h5>Card Info</h5>
                            <div class="blocks">
                                <span><strong>Card Number:</strong></span><span><?php echo CheckEmpty($transdata[0]->card_num); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>Card Bin:</strong></span><span><?php echo CheckEmpty($transdata[0]->cc_bin); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>Card Type:</strong></span><span><?php echo CheckEmpty($transdata[0]->cc_type); ?></span>
                            </div>
                            <hr/>
                            <h5>Transaction Information</h5>
                            <div class="blocks">
                                <span><strong>Transaction ID:</strong></span><span><?php echo CheckEmpty($transdata[0]->transaction_id); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>Transaction Amount:</strong></span><span><?php echo CheckEmpty($transdata[0]->amount) . " " . CheckEmpty($transdata[0]->currency); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>Currency:</strong></span><span><?php echo CheckEmpty($transdata[0]->currency); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>Tax:</strong></span><span><?php echo CheckEmpty($transdata[0]->tax); ?></span>
                            </div>
                            <div class="blocks">
                                <span><strong>Transaction Date:</strong></span><span><?php echo CheckEmpty($transdata[0]->date_of_tran2); ?></span>
                            </div>
                        </div>
                        <div class="tabs" id="cb_data" style="display:none;"> 
                            <h4>Charge Back Information</h4>
                            <?php
                            if (!empty($cb_data)) {
                                $count = 1;
                                foreach ($cb_data as $cb) {
                                    $common_data = unserialize($cb->csv_data);
                                    ?>
                                    <h3>Charge Back Case # <?php echo $count; ?></h3>
                                    <div class="blocks">
                                        <span><strong>Merchant Number:</strong></span><span><?php echo CheckEmpty($cb->merchant_number); ?></span>
                                    </div>
                                    <div class="blocks">
                                        <span><strong>Merchant Name:</strong></span><span><?php echo CheckEmpty($cb->merchant_name); ?></span>
                                    </div>
                                    <div class="blocks">
                                        <span><strong>Card Number:</strong></span><span><?php echo CheckEmpty($cb->cardholder_number); ?></span>
                                    </div>
                                    <div class="blocks">
                                        <span><strong>Card Type:</strong></span><span><?php echo CheckEmpty($cb->card_scheme); ?></span>
                                    </div>
                                    <div class="blocks">
                                        <span><strong>Card Type:</strong></span><span><?php echo CheckEmpty($cb->card_scheme); ?></span>
                                    </div>
                                    <div class="blocks">
                                        <span><strong>Reason:</strong></span><span><?php echo CheckEmpty($cb->reason_code_description); ?></span>
                                    </div>
                                    <div class="blocks">
                                        <span><strong>Investigator Comments:</strong></span><span><?php echo CheckEmpty($cb->investigator_comments); ?></span>
                                    </div>
                                    <div class="db_letter">
                                        <span><strong>Dispute Letter:</strong></span><span><?php echo CheckEmpty($common_data->cbdisputeletter); ?></span>
                                    </div>
                                    <?php
                                    $count++;
                                }
                            } else {
                                echo "<h5>No Record Found</h5>";
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->