        <div id="admin-content">
  <?php     echo "<h1>Editing page \"".$edit_page->page_title."\"</h1>\n";

            echo $this->session->flashdata("message")."\n";
            $validation_errors = validation_errors();
            if (!empty($validation_errors))
                echo "<div class=\"message error validation\">".validation_errors()."</div>\n";
                
            echo form_open("admin/pages/update/", 'id="form_edit_page"')."\n";
            echo form_fieldset("Page info")."\n";
                echo form_label("Title", "page-title")."\n";
                echo form_input("page-title", set_value("page-title", $edit_page->page_title), 'size="50" maxlength="200" id="page-title"')."\n";
                echo "<span class=\"field_tips\">required</span>\n";
                echo form_label("Body", "page-body")."\n";
                $size = array(
                  'rows'        => '10',
                  'cols'          => '50',
                );
                echo form_textarea("page-body", set_value("page-body", $edit_page->page_body), $size)."\n";
                echo "<span class=\"field_tips\">required</span>\n";
                echo form_label("Parent page", "page-parent-id")."\n";
                $options = array(
                    "0" => "(No parent)"
                );
                if ($pages != FALSE) {
                    foreach ($pages as $p) {
                        if ($p->page_id != $edit_page->page_id ) { //don't show this page on parent pages menu
                            $options[$p->page_id] = $p->page_title;
                        }
                    }
                }
                echo form_dropdown("page-parent-id", $options, set_value("page-parent-id", $edit_page->page_parent_id))."\n";
                echo form_label("Status", "page-status")."\n";
                $options = array(
                          'published' => 'Published',
                          'not-published' => 'Not published',
                          'private' => 'Private'
                        );
                echo form_dropdown("page-status", $options, set_value("page-status", $edit_page->page_status))."\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Search Engine Optimization (SEO)")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                echo form_label("Friendly URL", "page-alias")."\n";
                echo form_input("page-alias", set_value("page-alias", $edit_page->page_alias), 'size="50" maxlength="200" id="page-alias"')."\n";
                echo form_label("Meta Description", "page-meta-description")."\n";
                echo form_input("page-meta-description", set_value("page-meta-description", $edit_page->page_meta_description), 'size="50" maxlength="255"')."<br/>\n";
                echo form_label("Meta keywords", "page-meta-keywords")."\n";
                echo form_input("page-meta-keywords", set_value("page-meta-keywords", $edit_page->page_meta_keywords), 'size="50" maxlength="255"')."<br/>\n";
            echo "</div>\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Authoring info")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                $options = array();
                foreach ($users as $author) {
                    $options[$author->user_id] = $author->user_username;
                }
                echo form_label("Author", "page-author-id")."\n";
                echo form_dropdown("page-author-id", $options, set_value("page-author-id", $edit_page->page_author_id))."\n";

                echo form_label("Page date", "page-date")."\n";
                echo form_input("page-date", set_value("page-date", $edit_page->page_date), 'size="19" maxlength="19"')."\n";
                echo "<span class=\"field_tips\">Format: Y-m-d H:i:s</span>\n";
                echo form_label("Page modified date", "page-date-modified")."\n";
                echo form_input("page-date-modified", set_value("page-date-modified", $edit_page->page_date_modified), 'size="19" maxlength="19"')."\n";
                echo "<span class=\"field_tips\">Format: Y-m-d H:i:s</span>\n";
            echo "</div>\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Custom HTML tags")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                echo form_label("Custom charset", "page-charset")."\n";
                echo form_input("page-charset", set_value("page-charset", $edit_page->page_charset), 'size="10" maxlength="10"')."\n";
                echo form_label("Custom language", "page-lang")."\n";
                echo form_input("page-lang", set_value("page-lang", $edit_page->page_lang), 'size="10" maxlength="10"')."\n";
            echo "</div>\n";
            echo form_hidden("page-id", $edit_page->page_id)."\n";
            echo form_fieldset_close()."\n";

            echo form_reset("reset", "Reset")."\n";
            echo form_submit("add", "Update >")."\n";
            echo form_close()."\n";
            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/pages/", "< View all pages")."</p>\n";
?>
        </div> <!-- /admin-content -->