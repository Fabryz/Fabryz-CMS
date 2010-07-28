        <div id="admin-content">
<?php       echo "<h1>Add a category</h1>\n";

            echo $this->session->flashdata("message")."\n";
            $validation_errors = validation_errors();
            if (!empty($validation_errors))
                echo "<div class=\"message error validation\">".validation_errors()."</div>\n";

            echo form_open("admin/categories/create/", 'id="form_manage_categories"')."\n";
            echo form_fieldset("Category info")."\n";
                echo form_label("Title", "cat-title")."\n";
                echo form_input("cat-title", set_value("cat-title", ""), 'size="50" maxlength="200" id="cat-title"')."\n";
                echo "<span class=\"field_tips\">required</span>\n";
                echo form_label("Friendly URL", "cat-alias")."\n";
                echo form_input("cat-alias", set_value("cat-alias", ""), 'size="50" maxlength="200" id="cat-alias"')."\n";
                echo "<span class=\"field_tips\">unique (if empty the title field will be used)</span>\n";
                echo form_label("Description", "cat-description")."\n";
                $size = array(
                  'rows'        => '3',
                  'cols'          => '50',
                );
                echo form_textarea("cat-description", set_value("cat-description", ""), $size)."\n";
                echo form_label("Parent category", "cat-parent-id")."\n";

                if ($categories) {
                    $options[0] = "(No parent)";
                    foreach ($categories as $c) {
                        $options[$c->cat_id] = $c->cat_title;
                    }
                }
                echo form_dropdown("cat-parent-id", $options, set_value("cat-parent-id", ""))."\n";
                echo "<span class=\"field_tips\">required</span>\n";
            echo form_fieldset_close()."\n";
            echo form_reset("reset", "Reset")."\n";
            echo form_submit("add", "Add category>")."\n";
            echo form_close()."\n";
            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/categories/", "< View all categories")."</p>\n";
?>
        </div> <!-- /admin-content -->