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
            <h3>Transaction Data </h3>
            <div class="col-md-12">
                <div class="row">
                    <div class="filtraion">
                        <form id="filteform" action="" method="POST">
                            <?php if ($this->current_user == "1") { ?>
                                <div class="large-6 columns">
                                    <label for="users">Users &nbsp;&nbsp;  </label>
                                    <select id="users">
                                        <option value="">Select User</option>
                                        <?php
                                        if (!empty($all_customers)) {
                                            foreach ($all_customers as $customers) {
                                                ?>
                                                <option value="<?php echo $customers->username; ?>" data-id="<?php echo $customers->id; ?>"><?php echo $customers->username; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <br/>
                                </div>
                            <?php } ?>
                            <div class="large-6 columns">
                                <label for="trans_id">Merchant Type: &nbsp;&nbsp;  </label>
                                <select id="merch_type">
                                    <option value="">Select Merchant Gateway</option>
                                    <?php if (!$this->current_user == "1") { ?>
                                        <option value="nmi">NMI</option>
                                        <option value="authorize">Authorize</option>
                                    <?php } ?>
                                </select>
                                <br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="processor">Processor &nbsp;&nbsp;  </label>
                                <select id="processor">
                                    <option value="">Select Processor</option>
                                    <?php if (!$this->current_user == "1") { ?>
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
                                    <?php } ?>
                                </select>
                                <br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="trans_id">Transaction ID: &nbsp;&nbsp;  </label>
                                <input id="trans_id" name="trans_id" type="text" /><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="card_no">Card Number: &nbsp;&nbsp;  </label>
                                <input id="card_no" name="card_num" type="text" maxlength="16"/><br/>
                            </div>

                            <div class="large-6 columns">
                                <label for="from">Date (From): &nbsp;&nbsp;  </label>
                                <input id="from" name="from" type="text"/><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="to">Date (To): &nbsp;&nbsp;  </label>
                                <input id="to" name="to" type="text" /><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="to">CB Status: &nbsp;&nbsp;  </label>
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
                                    <option value="Matched:">Matched</option>
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
                    </div>
                </div>
                <?php
                ?>
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
                                <th>Alert Status</th>
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
                                <th>Alert Status</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            if (!empty($transALL)) {
                                foreach ($transALL as $data) {
                                    if (!empty($data['transaction_id'])) {
                                        ?>
                                        <tr>
                                            <td><a href="<?php echo site_url("admin/view_data/" . $data['mer_type'] . "/" . $data['transaction_id'] . "/" . $data['user_id']); ?>"><?php echo (!empty($data['transaction_id'])) ? $data['transaction_id'] : "--"; ?></a></td>
                                            <td><?php echo (!empty($data['first_name'])) ? $data['first_name'] : "--"; ?></td>
                                            <td><?php echo (!empty($data['last_name'])) ? $data['last_name'] : "--"; ?></td>
                                            <td><?php echo (!empty($data['processor_id'])) ? $data['processor_id'] : "--"; ?></td>
                                            <td><?php echo (!empty($data['mer_type'])) ? $data['mer_type'] : "--"; ?></td>
                                            <td><?php echo (!empty($data['email'])) ? $data['email'] : "--"; ?></td>
                                            <td><?php echo (!empty($data['date_of_tran'])) ? date("m/d/Y", strtotime($data['date_of_tran'])) : "--"; ?></td>
                                            <td><?php echo (!empty($data['card_num'])) ? $data['card_num'] : "--"; ?></td>
                                            <td><?php echo (!empty($data['matched'])) ? $data['matched'] : "--"; ?></td>
                                            <?php if ($this->current_user == "1") { ?>
                                                <td><?php echo (!empty($data['username'])) ? $data['username'] : "--"; ?></td>
                                            <?php } ?>
                                            <td><?php echo (!empty($data['a_match'])) ? $data['a_match'] : "--"; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->