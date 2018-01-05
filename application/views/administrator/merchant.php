<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Add Merchants</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>Add Merchants</h3>
            <div class="col-md-12">
                <form id="add_merchants_form" action="<?php echo site_url('admin/save_merchant'); ?>" class="form-horizontal" method="POST">
                    <?php
                    $xError = $this->session->flashdata('transError');
                    if (!empty($xError)) {
                        ?>
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Error: </strong> <?php echo $xError; ?>. Please Enable your API from Authorize.ne Settings
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <div class="col-sm-5 clearfix">
                            <?php
                            $Cred = $this->session->userdata('credentials');
                            ?>
                            <div class="radio">
                                <label>
                                    <input name="env_pro" type="radio" class="ace credentials" id="sandbox" value="sandbox" <?php echo (!empty($Cred['env'] == "sandbox")) ? "checked" : (!empty($Cred)) ? "checked" : "checked" ?>  data-endpoint="https://apitest.authorize.net/xml/v1/request.api">
                                    <span class="lbl"> Sandbox</span>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input name="env_pro" type="radio" <?php echo (!empty($Cred['env'] == "live")) ? "checked" : "" ?>  class="ace credentials" value="live" id="live" data-endpoint="https://api.authorize.net/xml/v1/request.api">
                                    <span class="lbl"> Live</span>
                                </label>
                            </div>
                            <div class="spacer clearfix"></div>
                        </div>
                        <div class="spacer clearfix"></div>
                        <div class="col-sm-5 clearfix">
                            <select name="endpoint">
                                <option value="">Select end point</option>
                                <option value="https://secure.nmi.com/api/query.php" <?php echo (!empty($Cred['endpoint']) && $Cred['endpoint'] == "https://secure.nmi.com/api/query.php") ? "selected" : (!empty($Cred)) ? "selected" : "selected" ?> data-type="nmi">SECURE.NMI</option>
                                <option value="https://apitest.authorize.net/xml/v1/request.api" <?php echo (!empty($Cred['endpoint']) && $Cred['endpoint'] == "https://apitest.authorize.net/xml/v1/request.api" || $Cred['endpoint'] == "https://api.authorize.net/xml/v1/request.api") ? "selected" : "" ?> data-type="authorize">Authorize.net</option>
                                <option value="https://api.sageone.com/accounts/v1/transactions" <?php echo (!empty($Cred['endpoint']) && $Cred['endpoint'] == "https://api.sageone.com/accounts/v1/transactions") ? "selected" : "" ?> data-type="sage">Sage One</option>
                            </select>
                            <div class="spacer clearfix"></div>
                        </div>
                        <div class="spacer clearfix"></div>
                        <?php if ($this->current_user == "1") { ?>
                            <div class="col-sm-5 vclearfix">
                                <select name="assign_user" class="full validate[required]">
                                    <option value="">Assign Merchant with custom</option>
                                    <?php
                                    if (!empty($allcustom)) {
                                        foreach ($allcustom as $allc) {
                                            ?>
                                            <option value="<?php echo $allc->id; ?>" <?php echo (!empty($Cred['assing_user']) && $Cred['assing_user'] == $allc->id) ? "selected" : "" ?> data-type="nmi"><?php echo $allc->username ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="spacer clearfix"></div>
                            </div>
                        <?php } ?>
                        <div class="spacer clearfix"></div>
                        <div class="nmi_field" <?php echo (!empty($Cred['mer_type']) && $Cred['mer_type'] == "nmi") ? "style='display:block'" : (empty($Cred)) ? "style='display:block'" : "style='display:none'" ?>>
                            <div class="col-sm-5 clearfix">
                                <input type="text" name="user" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo (!empty($Cred['username'])) ? $Cred['username'] : "" ?>" placeholder="User name"/>
                            </div>
                            <div class="spacer"></div>
                            <div class="col-sm-5 clearfix">
                                <input type="text" name="password" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo (!empty($Cred['password'])) ? $Cred['password'] : "" ?>" placeholder="Password"/>
                            </div>
                        </div>
                        <div class="authorize_field" <?php echo (!empty($Cred['mer_type']) && $Cred['mer_type'] == "authorize") ? "style='display:block'" : "style='display:none'" ?>>
                            <div class="col-sm-5 clearfix">
                                <input type="text" name="apiLogin" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo (!empty($Cred['loginId'])) ? $Cred['loginId'] : "" ?>" placeholder="API Login ID"/>
                            </div>
                            <div class="spacer"></div>
                            <div class="col-sm-5 clearfix">
                                <input type="text" name="trans_key" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo (!empty($Cred['transKey'])) ? $Cred['transKey'] : "" ?>" placeholder="Transaction Key"/>
                            </div>
                            <div class="spacer"></div>
                        </div>
                        <div class="sage_field" <?php echo (!empty($Cred['mer_type']) && $Cred['mer_type'] == "sage") ? "style='display:block'" : "style='display:none'" ?>>
                            <div class="col-sm-5 clearfix">
                                <input type="text" name="client_id" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo (!empty($Cred['client_id'])) ? $Cred['client_id'] : "" ?>" placeholder="Client ID"/>
                            </div>
                            <div class="spacer"></div>
                            <div class="col-sm-5 clearfix">
                                <input type="text" name="client_secret" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo (!empty($Cred['client_secret'])) ? $Cred['client_secret'] : "" ?>" placeholder="Client Secret"/>
                            </div>
                            <div class="spacer"></div>
                            <div class="col-sm-5 clearfix">
                                <input type="text" name="signing_secret" class="col-xs-10 col-sm-12 validate[required]" value="<?php echo (!empty($Cred['sign_secret'])) ? $Cred['sign_secret'] : "" ?>" placeholder="Signing Secret"/>
                            </div>
                        </div>
                        <input type="hidden" name="merchant_type" id="merchant_type" value="<?php echo (!empty($Cred['mer_type'])) ? $Cred['mer_type'] : "nmi" ?>"/>
                        <div class="spacer"></div>
                        <div class="col-sm-5 clearfix">
                            <br/>

                            <input type="submit" name="" value="Add Merchant" class="btn btn-lighter"/>
                            <div class="spacer"></div>
                            <?php echo $msg = (!empty($this->session->flashdata('msg'))) ? $this->session->flashdata('msg') : ""; ?>
                        </div>

                    </div>
                </form>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
<style>
    .clearfix {
        clear: both;
    }
</style>