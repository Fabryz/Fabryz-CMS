        <div id="admin-content">
  <?php     echo "<h1>Editing post \"".$edit_post->post_title."\"</h1>\n";

            echo $this->session->flashdata("message")."\n";
            $validation_errors = validation_errors();
            if (!empty($validation_errors))
                echo "<div class=\"message error validation\">".validation_errors()."</div>\n";
                
            echo form_open("admin/posts/update/", 'id="form_edit_post"')."\n";
            echo form_fieldset("Post info")."\n";
                echo form_label("Title", "post-title")."\n";
                echo form_input("post-title", set_value("post-title", $edit_post->post_title), 'size="50" maxlength="200" id="post-title"')."\n";
                echo "<span class=\"field_tips\">required</span>\n";
                echo form_label("Body", "post-body")."\n";
                $size = array(
                  'rows'        => '10',
                  'cols'          => '50',
                );
                echo form_textarea("post-body", set_value("post-body", $edit_post->post_body), $size)."\n";
                echo "<span class=\"field_tips\">required</span>\n";
                echo form_label("Summary", "post-summary")."\n";
                $size = array(
                  'rows'        => '3',
                  'cols'          => '50',
                );
                echo form_textarea("post-summary", set_value("post-summary", $edit_post->post_summary), $size)."\n";
                echo "<span class=\"field_tips\">max 200 characters</span>\n";
                echo form_label("Category", "post-category-id")."\n";
                if ($categories != FALSE) {
                    foreach ($categories as $c) {
                        $options[$c->cat_id] = $c->cat_title;
                    }
                }
                echo form_dropdown("post-category-id", $options, set_value("post-category-id", $edit_post->category_id))."\n";
                echo form_label("Status", "post-status")."\n";
                $options = array(
                          'published' => 'Published',
                          'not-published' => 'Not published',
                          'private' => 'Private'
                        );
                echo form_dropdown("post-status", $options, set_value("post-status", $edit_post->post_status))."\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Search Engine Optimization (SEO)")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                echo form_label("Friendly URL", "post-alias")."\n";
                echo form_input("post-alias", set_value("post-alias", $edit_post->post_alias), 'size="50" maxlength="200" id="post-alias"')."\n";
                echo form_label("Meta Description", "post-meta-description")."\n";
                echo form_input("post-meta-description", set_value("post-meta-description", $edit_post->post_meta_description), 'size="50" maxlength="255"')."<br/>\n";
                echo form_label("Meta keywords", "post-meta-keywords")."\n";
                echo form_input("post-meta-keywords", set_value("post-meta-keywords", $edit_post->post_meta_keywords), 'size="50" maxlength="255"')."<br/>\n";
            echo "</div>\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Authoring info")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                $options = array();
                foreach ($users as $author) {
                    $options[$author->user_id] = $author->user_username;
                }
                echo form_label("Author", "post-author-id")."\n";
                echo form_dropdown("post-author-id", $options, set_value("post-author-id", $edit_post->post_author_id))."\n";

                echo form_label("Post date", "post-date")."\n";
                echo form_input("post-date", set_value("post-date", $edit_post->post_date), 'size="19" maxlength="19"')."\n";
                echo "<span class=\"field_tips\">Format: Y-m-d H:i:s</span>\n";
                echo form_label("Post modified date", "post-date-modified")."\n";
                echo form_input("post-date-modified", set_value("post-date-modified", $edit_post->post_date_modified), 'size="19" maxlength="19"')."\n";
                echo "<span class=\"field_tips\">Format: Y-m-d H:i:s</span>\n";
            echo "</div>\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Custom HTML tags")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                echo form_label("Custom charset", "post-charset")."\n";
                echo form_input("post-charset", set_value("post-charset", $edit_post->post_charset), 'size="10" maxlength="10"')."\n";
                echo form_label("Custom language", "post-lang")."\n";
                echo form_input("post-lang", set_value("post-lang", $edit_post->post_lang), 'size="10" maxlength="10"')."\n";
            echo "</div>\n";
            echo form_hidden("post-id", $edit_post->post_id)."\n";
            echo form_fieldset_close()."\n";

            echo form_reset("reset", "Reset")."\n";
            echo form_submit("add", "Update >")."\n";
            echo form_close()."\n";
            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/posts/", "< View all posts")."</p>\n";
?>
        </div> <!-- /admin-content -->