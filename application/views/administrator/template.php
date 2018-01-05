<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Email Template</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>Email Template</h3>
            <div class="colums_csv col-md-8 pull-left">

                <form action="" method="POST">
                    <textarea id="template_editor"><?php echo (!empty($template)) ? $template[0]->etemplate : ""; ?></textarea>
                    <input type="submit" class="saveTemplate" id="saveTemplate" value="Save Template"/>
                </form>
                <br/>
                <div class="messageShow"></div>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->