<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">View Sage Transactions</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>View Sage Transactions</h3>
            <div class="col-md-12">
                <div class="row">
                    <div class="filtraion">
                        <form id="filteform" action="" method="POST">
                            <div class="large-6 columns">
                                <label for="mer_namex">Merchant Name: &nbsp;&nbsp;  </label>
                                <select id="mer_namex">
                                    <option value="">Select Merchant</option>
                                    <?php
                                    if ($merchants) {
                                        foreach ($merchants as $merchant) {
                                            ?>
                                            <option value="<?php echo $merchant->mer_name; ?>"><?php echo $merchant->mer_name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="location">Location &nbsp;&nbsp;  </label>
                                <select id="location">
                                    <option value="">Select Location</option>
                                    <?php
                                    if ($location) {
                                        foreach ($location as $locat) {
                                            ?>
                                            <option value="<?php echo $locat->location; ?>"><?php echo $locat->location; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="ter_id">Terminal ID: &nbsp;&nbsp;  </label>
                                <input id="ter_id" name="ter_id" type="text" /><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="rout_no">Routing Number: &nbsp;&nbsp;  </label>
                                <input id="rout_no" name="rout_no" type="text" /><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="fromx">Date (From): &nbsp;&nbsp;  </label>
                                <input id="fromx" name="fromx" type="text"/><br/>
                            </div>
                            <div class="large-6 columns">
                                <label for="tox">Date (To): &nbsp;&nbsp; </label>
                                <input id="tox" name="tox" type="text" /><br/>
                            </div>
                            <div class="clear"></div>
                            <div class="large-12 columns middle">
                                <button class="button radius" id="filterv">Filter</button>
                                <button class="button radius" id="resetFormv">Reset</button>
                            </div>

                            <div class="clear"></div>
                        </form>
                    </div>
                </div>
                <div class="overflowdata">
                    <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Created Date</th>
                                <th>Batch Date</th>
                                <th>Location</th>
                                <th>Terminal ID</th>
                                <th>Check Status</th>
                                <th>Funding Status</th>
                                <th>Auth No#</th>
                                <th>Routing No#</th>
                                <th>Check No#</th>
                                <th>Check Amount</th>
                                <th>Deposit Date</th>
                                <th>Transaction ID</th>
                                <th>Merchant</th>
                                <th>Check Writer</th>
                                <th>Transation Type</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Created Date</th>
                                <th>Batch Date</th>
                                <th>Location</th>
                                <th>Terminal ID</th>
                                <th>Check Status</th>
                                <th>Funding Status</th>
                                <th>Auth No#</th>
                                <th>Routing No#</th>
                                <th>Check No#</th>
                                <th>Check Amount</th>
                                <th>Deposit Date</th>
                                <th>Transaction ID</th>
                                <th>Merchant</th>
                                <th>Check Writer</th>
                                <th>Transation Type</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            if (!empty($sages)) {
                                foreach ($sages as $data) {
                                    ?>
                                    <tr>
                                        <td><?php echo (!empty($data->create_date)) ? $data->create_date : "--"; ?></td>
                                        <td><?php echo (!empty($data->batch_date)) ? $data->batch_date : "--"; ?></td>
                                        <td><?php echo (!empty($data->location)) ? $data->location : "--"; ?></td>
                                        <td><?php echo (!empty($data->terminal_id)) ? $data->terminal_id : "--"; ?></td>
                                        <td><?php echo (!empty($data->check_status)) ? $data->check_status : "--"; ?></td>
                                        <td><?php echo (!empty($data->funding_status)) ? $data->funding_status : "--"; ?></td>
                                        <td><?php echo (!empty($data->auth_no)) ? $data->auth_no : "--"; ?></td>
                                        <td><?php echo (!empty($data->routing_no)) ? $data->routing_no : "--"; ?></td>
                                        <td><?php echo (!empty($data->check_no)) ? $data->check_no : "--"; ?></td>
                                        <td><?php echo (!empty($data->check_amount)) ? $data->check_amount : "--"; ?></td>
                                        <td><?php echo (!empty($data->desposite_date)) ? date("m/d/Y", strtotime($data->desposite_date)) : "--"; ?></td>
                                        <td><?php echo (!empty($data->transacion_id)) ? $data->transacion_id : "--"; ?></td>
                                        <td><?php echo (!empty($data->mer_name)) ? $data->mer_name : "--"; ?></td>
                                        <td><?php echo (!empty($data->check_writer)) ? $data->check_writer : "--"; ?></td>
                                        <td><?php echo (!empty($data->tran_type)) ? $data->tran_type : "--"; ?></td>
                                    </tr>
                                    <?php
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
<style>
    .clearfix {
        clear: both;
    }
</style>