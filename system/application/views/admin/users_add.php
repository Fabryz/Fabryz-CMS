        <div id="admin-content">
  <?php     echo "<h1>Create a new user</h1>\n";
  
            echo $this->session->flashdata("message")."\n";
            $validation_errors = validation_errors();
            if (!empty($validation_errors))
                echo "<div class=\"message error validation\">".validation_errors()."</div>\n";

            echo form_open("admin/users/create", 'id="form_create_user"')."\n";
            echo form_fieldset("User login info")."\n";
                echo form_label("Username", "user-username")."\n";
                echo form_input("user-username", set_value("user-username", ""))."\n";
                echo "<span class=\"field_tips\">required, minimum length: 4</span>\n";
                echo form_label("Email", "user-email")."\n";
                echo form_input("user-email", set_value("user-email", ""))."\n";
                echo "<span class=\"field_tips\">required</span>\n";
                echo form_label("Confirm Email", "user-email2")."\n";
                echo form_input("user-email2", set_value("user-email", ""))."\n";
                echo form_label("Password", "user-password")."\n";
                echo form_password("user-password", set_value("user-password", ""))."\n";
                echo "<span class=\"field_tips\">required, minimum length: 8</span>\n";
                echo form_label("Verify password", "user-password2")."\n";
                echo form_password("user-password2", set_value("user-password2", ""))."\n";
                echo form_label("Role", "user-role")."\n";
                $options = array(
                        0 => "Admin",
                        1 => "Editor",
                        2 => "Writer"
                );
                echo form_dropdown("user-role", $options, set_value("user-role", 2))."\n";
            echo form_fieldset_close()."\n";

            echo form_fieldset("Personal info");
            echo "<div class=\"collapsible collapsed\">\n";
                echo form_label("First Name", "user-firstname")."\n";
                echo form_input("user-firstname", set_value("user-firstname", ""))."\n";
                echo form_label("Last Name", "user-lastname")."\n";
                echo form_input("user-lastname", set_value("user-lastname", ""))."\n";
                echo form_label("Avatar", "user_avatar")."\n";
                echo form_input("user-avatar", set_value("user-avatar", ""), 'size="50"')."\n";
                echo form_label("About", "user_about")."\n";
                $size = array(
                  'rows'        => '3',
                  'cols'          => '50',
                );
                echo form_textarea("user-about", set_value("user-about", ""), $size)."\n";
            echo "</div>\n";
            echo form_fieldset_close()."\n";

            echo form_reset("reset", "Reset")."\n";
            echo form_submit("add", "Add user >")."\n";
            echo form_close()."\n";
            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/users/", "< View all users")."</p>\n";
?>
        </div> <!-- /admin-content -->