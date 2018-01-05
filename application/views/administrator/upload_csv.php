<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Import CSV</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="page-content">
            <h3>Mapping Section</h3>
            <div class="colums_csv col-md-5 pull-left">
                <?php
                foreach ($columns as $column) {
                    echo "<span class='col-md-8 csv_handle'>" . $column . "</span>";
                    ?>
                    <div class="colums_csv col-md-2 pull-left">
                        <select>
                            <?php
                            foreach ($dbcolumns as $column) {
                                echo "<option value='" . $column . "'>" . $column . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div style='clear:both;'></div>
                    <?php
                }
                ?>

            </div>

        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->