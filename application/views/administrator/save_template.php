<div class='mappingarea'>
    <div class="save_template">
        <h2>Import CSV</h2>
        <div class="left-side">
            <form id="form_save_template" action="<?php echo site_url('admin/import_now'); ?>" method="POST">
                <input type="hidden" name="importcsv" value="save"/>
                <input type="text" class="fieldset validate[required]" name="map" placeholder="Enter Mapping Tile"/>
                <input type="submit" value="Save Mapping & Import CSV" class="saveTem"/>
            </form>
        </div>
        <div class="left-mid-side">OR</div>
        <div class="left-side">
            <form id="form_import_template" action="<?php echo site_url('admin/import_now'); ?>" method="POST">
                <input type="hidden" name="importcsv" value="existing"/>
                <select class="fieldset validate[required]" name="usemap">
                    <option value="">Select Mapping</option>
                    <?php
                    if (!empty($template)) {
                        ?>
                        <option value="<?php echo $template[0]->id; ?>"><?php echo $template[0]->temp_title; ?></option>
                    <?php } ?>
                </select>
                <input type="submit" value="Import CSV From Existing Map" class="saveTem"/>
            </form>
        </div>
        <div class="clear"></div>
    </div>
</div>