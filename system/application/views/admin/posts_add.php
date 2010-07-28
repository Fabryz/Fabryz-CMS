        <div id="admin-content">
  <?php     echo "<h1>Create a new post</h1>\n";
  
            echo $this->session->flashdata("message")."\n";
            $validation_errors = validation_errors();
            if (!empty($validation_errors))
                echo "<div class=\"message error validation\">".validation_errors()."</div>\n";

            echo form_open("admin/posts/create", 'id="form_create_post"')."\n";
            echo form_fieldset("Post info")."\n";
                echo form_label("Title", "post-title")."\n";
                echo form_input("post-title", set_value("post-title", ""), 'size="50" maxlength="200" id="post-title"')."\n";
                echo "<span class=\"field_tips\">required</span>\n";
                echo form_label("Body", "post-body")."\n";
                $size = array(
                  'rows'        => '10',
                  'cols'          => '50',
                );
                echo form_textarea("post-body", set_value("post-body", ""), $size)."\n";
                echo "<span class=\"field_tips\">required</span>\n";
                echo form_label("Summary", "post-summary")."\n";
                $size = array(
                  'rows'        => '3',
                  'cols'          => '50',
                );
                echo form_textarea("post-summary", set_value("post-summary", ""), $size)."\n";
                echo "<span class=\"field_tips\">max 200 characters</span>\n";
                echo form_label("Category", "post-category")."\n";
                if ($categories != FALSE) {
                    foreach ($categories as $c) {
                        $options[$c->cat_id] = $c->cat_title;
                    }
                }
                echo form_dropdown("post-category", $options, set_value("post-category", $default_category))."\n";
                echo form_label("Status", "post-status")."\n";
                $options = array(
                          'published' => 'Publish',
                          'not-published' => 'Do not publish',
                          'private' => 'Make private'
                        );
                echo form_dropdown("post-status", $options, set_value("post-status", "published"))."\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Search Engine Optimization (SEO)")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                echo form_label("Friendly URL", "post-alias")."\n";
                echo form_input("post-alias", set_value("post-alias", ""), 'size="50" maxlength="200" id="post-alias"')."\n";
                echo form_label("Meta Description", "post-meta-description")."\n";
                echo form_input("post-meta-description", set_value("post-meta-description", ""), 'size="50" maxlength="255"')."<br/>\n";
                echo form_label("Meta keywords", "post-meta-keywords")."\n";
                echo form_input("post-meta-keywords", set_value("post-meta-keywords", ""), 'size="50" maxlength="255"')."<br/>\n";
            echo "</div>\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Authoring info")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                $options = array();
                foreach ($users as $author) {
                    $options[$author->user_id] = $author->user_username;
                }
                echo form_label("Author", "post-author-id")."\n";
                echo form_dropdown("post-author-id", $options, set_value("post-author-id", $this->session->userdata("user_id")))."\n";
            echo "</div>\n";
            echo form_fieldset_close()."\n";
            echo form_fieldset("Custom HTML tags")."\n";
            echo "<div class=\"collapsible collapsed\">\n";
                echo form_label("Custom charset", "post-charset")."\n";
                echo form_input("post-charset", set_value("post-charset", ""), 'size="10" maxlength="10"')."\n";
                echo form_label("Custom language", "post-lang")."\n";
                echo form_input("post-lang", set_value("post-lang", ""), 'size="10" maxlength="10"')."\n";
            echo "</div>\n";
            echo form_fieldset_close()."\n";

            echo form_reset("reset", "Reset")."\n";
            echo form_submit("add", "Add post >")."\n";
            echo form_close()."\n";
            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/posts/", "< View all posts")."</p>\n";
?>
        </div> <!-- /admin-content -->