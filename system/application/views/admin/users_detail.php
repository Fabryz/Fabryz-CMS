        <div id="admin-content">
            <script language="javascript" type="text/javascript">
            <!--
                function deleteUser(url){
                    message = "Attention:\nAre you sure you want to delete this user?\nIt cannot be undone"
                    if (confirm(message)) {
                        document.location = url;
                    }
                }
            // -->
            </script>
<?php       echo "<h1>User \"".$user->user_username."\"</h1>\n";

            echo $this->session->flashdata("message")."\n";
            echo "<div id=\"details\">";
            echo "<dl>\n";
            echo "\t<dt>ID</dt>\n";
            echo "\t<dd>".$user->user_id."</dd>\n";
            echo "\t<dt>Username</dt>\n";
            echo "\t<dd>".$user->user_username."</dd>\n";
            echo "\t<dt>First name</dt>\n";
            echo "\t<dd>".(empty($user->user_firstname)?"<em>None</em>":$user->user_firstname)."</dd>\n";
            echo "\t<dt>Last name</dt>\n";
            echo "\t<dd>".(empty($user->user_lastname)?"<em>None</em>":$user->user_lastname)."</dd>\n";
            echo "\t<dt>Email</dt>\n";
            echo "\t<dd>".safe_mailto($user->user_email, $user->user_email)."</dd>\n";
            echo "\t<dt>Avatar</dt>\n";
            echo "\t<dd>".(empty($user->user_avatar)?"<em>None</em>":$user->user_avatar)."</dd>\n";
            echo "\t<dt>About</dt>\n";
            echo "\t<dd>".(empty($user->user_about)?"<em>None</em>":$user->user_about)."</dd>\n";
            echo "\t<dt>Signup date</dt>\n";
            echo "\t<dd>".date($date_format, strtotime($user->user_signup_date))."</dd>\n";
            echo "\t<dt>Role</dt>\n";
            $roles = array(
                        0 => "Admin",
                        1 => "Editor",
                        2 => "Writer"
                    );
            echo "\t<dd>".$roles[$user->user_role]."</dd>\n";
            echo "</dl>\n";
            echo "</div>\n";

            //Debug
            echo debug($user);
            
            echo "<hr class=\"separator\"/>\n";
            //make admin account undeletable, also don't allow the user to delete his account
            $has_permission = $this->user_model->has_permission(0, FALSE);

            if ($has_permission) {
                echo "<p>".anchor("admin/users/edit/".$user->user_id, "* Edit this User")."</p>\n";
                //make admin account undeletable, also don't allow the user to delete his account
                if (($user->user_id != 1) && ($user->user_id != $this->session->userdata("user_id")))
                    echo "<p><a href=\"javascript:void(0);\" title=\"Delete this user\" onclick=\"deleteUser('".site_url("admin/users/delete")."/".$user->user_id."');\">- Delete this user</a></p>\n";
            }
            echo "<p>".anchor("admin/users/add/", "+ Add a new user")."</p>\n";
            echo "<p>".anchor("site/users/".$user->user_id, "View on live site &raquo;")."</p>\n";
            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/users/", "< View all users")."</p>\n";
?>
        </div> <!-- /admin-content -->