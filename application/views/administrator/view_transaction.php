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
                <div class="row">
                    <div class="filtraion">
                        <form id="filteform" action="" method="POST">  
                            <?php if ($this->current_user == "1") { ?>
                                <!--                                <div class="large-6 columns">
                                                                    <label for="users">Users &nbsp;&nbsp;  </label>
                                                                    <select id="users">
                                                                        <option value="">Select User</option>
                                <?php
                                if (!empty($all_customers)) {
                                    foreach ($all_customers as $customers) {
                                        ?>
                                                                                        <option value="<?php echo $customers->username; ?>"><?php echo $customers->username; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                                
                                                                    </select>
                                                                    <br/>
                                                                </div>-->
                            <?php } ?>
                            <div class="large-6 columns">
                                <label for="trans_id">Merchant Type: &nbsp;&nbsp;  </label>
                                <select id="merch_type">
                                    <option value="">Select Merchant Gateway</option>
                                    <option value="nmi">NMI</option>
                                    <option value="authorize">Authorize</option>
                                </select>
                                <br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="processor">Processor &nbsp;&nbsp;  </label>
                                <select id="processor">
                                    <option value="">Select Processor</option>
                                    <?php
                                    if (!empty($this->processor)) {
                                        $processors = $this->processor;
                                        foreach ($processors as $processor) {
                                            ?>
                                            <option value="<?php echo $processor->processor_id; ?>"><?php echo $processor->processor_id; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>

                                </select>
                                <br/>
                            </div>

                            <div class="large-6 columns">
                                <label for="trans_id">Transaction ID: &nbsp;
                                    &nbsp;
                                </label>
                                <input id="trans_id" name="trans_id" type="text" /><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="card_no">Card Number: &nbsp;
                                    &nbsp;
                                </label>
                                <input id="card_no" name="card_num" type="text" maxlength="16"/><br/>
                            </div>

                            <div class="large-6 columns">
                                <label for="from">Date (From): &nbsp;
                                    &nbsp;
                                </label>
                                <input id="from" name="from" type="text"/><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="to">Date (To): &nbsp;
                                    &nbsp;
                                </label>
                                <input id="to" name="to" type="text" /><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="to">Status: &nbsp;
                                    &nbsp;
                                </label>
                                <select id="status_matched">
                                    <option value="">Status</option>
                                    <option value="Matched:">Matched</option>
                                    <option value="Not Matched">Not Matched</option>
                                </select>
                                <br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="to">Alert Status: &nbsp;&nbsp;  </label>
                                <select id="status_alert">
                                    <option value="">Status</option>
                                    <option value="Matched">Matched</option>
                                    <option value="Not Matched">Not Matched</option>
                                </select>
                                <br/>
                            </div>
                            <div class="clear"></div>
                            <?php if ($this->current_user == "1") { ?>
                                <div class="large-12 columns middle">
                                    <button class="button radius" id="filterz">Filter</button><button class="button radius" id="resetFormz">Reset</button>
                                </div>
                            <?php } else { ?>
                                <div class="large-12 columns middle">
                                    <button class="button radius" id="filter">Filter</button><button class="button radius" id="resetForm">Reset</button>
                                </div>
                            <?php } ?>
                            <div class="clear"></div>
                        </form>
                    </div><!--Filter-->
                </div>

                <div class="overflowdata">
                    <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Processor ID</th>
                                <th>Merchant Type</th>
                                <th>Email</th>
                                <th>Transaction Date</th>
                                <th>card Number</th>
                                <th>CB Status</th>
                                <?php if ($this->current_user == "1") { ?>
                                    <th>Customer</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Transaction ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Processor ID</th>
                                <th>Merchant Type</th>
                                <th>Email</th>
                                <th>Transaction Date</th>
                                <th>card Number</th>
                                <th>CB Status</th>
                                <?php if ($this->current_user == "1") { ?>
                                    <th>Customer</th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            if (!empty($transALL)) {
                                foreach ($transALL as $transact) {
                                    ?>
                                    <tr>
                                        <td><a href="<?php echo site_url("admin/view_data/" . $transact['mer_type'] . "/" . $transact['transaction_id'] . "/" . $transact['user_id']); ?>"><?php echo (!empty($transact['transaction_id'])) ? $transact['transaction_id'] : "--"; ?></a></td>
                                        <td><?php echo (!empty($transact['first_name'])) ? $transact['first_name'] : "--"; ?></td>
                                        <td><?php echo (!empty($transact['last_name'])) ? $transact['last_name'] : "--"; ?></td>
                                        <td><?php echo (!empty($transact['processor_id'])) ? $transact['processor_id'] : "--"; ?></td>
                                        <td><?php echo (!empty($transact['mer_type'])) ? $transact['mer_type'] : "--"; ?></td>
                                        <td><?php echo (!empty($transact['email'])) ? $transact['email'] : "--"; ?></td>
                                        <td><?php echo (!empty($transact['date_of_tran2'])) ? $transact['date_of_tran2'] : "--"; ?></td>
                                        <td><?php echo (!empty($transact['card_num'])) ? $transact['card_num'] : "--"; ?></td>
                                        <td><?php echo (!empty($transact['matched'])) ? $transact['matched'] : "--"; ?></td>
                                        <?php if ($this->current_user == "1") { ?>
                                            <td><?php echo (!empty($transact['username'])) ? $transact['username'] : "--"; ?></td>
                                        <?php } ?>


                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- /.page-content -->
</div>
</div><!-- /.main-content -->