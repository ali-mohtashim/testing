<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">FTP Connect</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>FTP Connect</h3>
            <div class="col-md-8">
                <?php
                if (empty($error)) {
                    ?>
                    <div class="loading"><h2>Importing CSV...</h2></div>
                <?php } else { ?>
                   <h4><?php echo $error; ?></h4>
                        <?php } ?>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->