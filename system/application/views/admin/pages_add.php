        <div id="admin-content">
  <?php     echo "<h1>Create a new page</h1>\n";
  
            echo $this->session->flashdata("message")."\n";
            $validation_errors = validation_errors();
            if (!empty($validation_errors))
                echo "<div class=\"message error validation\">".validation_errors()."</div>\n";

            echo form_open("admin/pages/create", 'id="form_create_page"')."\n";
            echo form_fieldset("Page info")."\n";
                echo form_label("Title", "page-title")."\n";
                echo form_input("page-title", set_value("page-title", ""), 'size="50" maxlength="200" id="page-title"')."\n";
                echo "<span class=\"field_tips\">required</span>\n";
                echo form_label("Body", "page-body")."\n";
                $size = array(
                  'rows'        => '10',
                  'cols'          => '50',
                );
                echo form_textarea("page-body", set_value("page-body", ""), $size)."\n";
                echo "<span class=\"field_tips\">required</span>\n";
                echo form_label("Parent page", "page-parent-id")."\n";
                $options = array(
                    "0" => "(No parent)"
                );
                if ($pages != FALSE) {
                    foreach ($pages as $p) {
                        $options[$p->page_id] = $p->page_title;
                    }
                }
                echo form_dropdown("page-parent-id", $options, set_value("page-parent-id", "0"))."\n";
                echo form_label("Status", "page-status")."\n";
                $options = array(
                          'published' => 'Publish',
                          'not-published' => 'Do not publish',
                          'private' => 'Make private'
                        );
                echo form_dropdown("page-status", $options, set_value("page-status", "published"))."\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Search Engine Optimization (SEO)")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                echo form_label("Friendly URL", "page-alias")."\n";
                echo form_input("page-alias", set_value("page-alias", ""), 'size="50" maxlength="200" id="page-alias"')."\n";
                echo form_label("Meta Description", "page-meta-description")."\n";
                echo form_input("page-meta-description", set_value("page-meta-description", ""), 'size="50" maxlength="255"')."<br/>\n";
                echo form_label("Meta keywords", "page-meta-keywords")."\n";
                echo form_input("page-meta-keywords", set_value("page-meta-keywords", ""), 'size="50" maxlength="255"')."<br/>\n";
            echo "</div>\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Authoring info")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                $options = array();
                foreach ($users as $author) {
                    $options[$author->user_id] = $author->user_username;
                }
                echo form_label("Author", "page-author-id")."\n";
                echo form_dropdown("page-author-id", $options, set_value("page-author-id", $this->session->userdata("user_id")))."\n";
            echo "</div>\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Custom HTML tags")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                echo form_label("Custom charset", "page-charset")."\n";
                echo form_input("page-charset", set_value("page-charset", ""), 'size="10" maxlength="10"')."\n";
                echo form_label("Custom language", "page-lang")."\n";
                echo form_input("page-lang", set_value("page-lang", ""), 'size="10" maxlength="10"')."\n";
            echo "</div>\n";
            echo form_fieldset_close()."\n";

            echo form_reset("reset", "Reset")."\n";
            echo form_submit("add", "Add page >")."\n";
            echo form_close()."\n";
            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/pages/", "< View all pages")."</p>\n";
?>
        </div> <!-- /admin-content -->