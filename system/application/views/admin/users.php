        <div id="admin-content">
            <script language="javascript" type="text/javascript">
            <!--
                function deleteUser(id, name, url){
                    message = "Attention:\n\nAre you sure you want to delete the user #"+id+": \""+name+"\"?\nIt cannot be undone."
                    if (confirm(message)) {
                        document.location = url;
                    }
                }
            // -->
            </script>

<?php       echo "<h1>Users</h1>\n";

            echo $this->session->flashdata("message")."\n";
            echo "<table id=\"table_users\" class=\"table_view\">\n";
            echo "<tr>\n";
            echo "\t<th>id</th>\n";
            echo "\t<th>Username</th>\n";
            echo "\t<th>Name</th>\n";
            echo "\t<th>Email</th>\n";
            echo "\t<th>Signup date</th>\n";
            echo "\t<th>Role</th>\n";
            echo "\t<th>Actions</th>\n";
            echo "</tr>\n";

            if ($users != FALSE) {
                $i = 0;
                foreach ($users as $u) {
                    $roles = array(
                        0 => "Admin",
                        1 => "Editor",
                        2 => "Writer"
                    );

                    if (($i%2)==0)
                         echo "<tr class=\"even\">\n";
                    else
                        echo "<tr class=\"odd\">\n";
                    echo "\t<td class=\"center\">".$u->user_id."</td>\n";
                    echo "\t<td>".$u->user_username."</td>\n";
                    echo "\t<td class=\"center\">".$u->user_firstname." ".$u->user_lastname."</td>\n";
                    echo "\t<td class=\"center\">".safe_mailto($u->user_email, $u->user_email)."</td>\n";
                    echo "\t<td class=\"center\">".date($date_format, strtotime($u->user_signup_date))."</td>\n";
                    echo "\t<td class=\"center\">".$roles[$u->user_role]."</td>\n";

                    echo "\t<td>";
                        //Allow edit/delete only if logged user has "admin" role
                        $has_permission = $this->user_model->has_permission(0, FALSE);

                        if ($has_permission) {
                            echo anchor("/admin/users/edit/$u->user_id", "Edit", 'title="Edit this user"').nbs(4);
                            //make admin account undeletable, also don't allow the user to delete his account
                            if (($u->user_id != 1) && ($u->user_id != $this->session->userdata("user_id")))
                                echo "<a href=\"javascript:void(0);\" title=\"Delete this user\" onclick=\"deleteUser('".$u->user_id."', '".$u->user_username."', '".site_url("admin/users/delete")."/".$u->user_id."');\">Delete</a>".nbs(4);
                        }
                        echo anchor("/admin/users/".$u->user_id, "Details", 'title="View this user in detail"')."</td>\n";
                    echo "</tr>\n";
                    $i++;
                }
                echo "</table>\n";
                echo "<div id=\"pagination\">".$pagination."</div>\n";
                echo "<div id=\"total_rows\"><p>Total users: ".$users_total_rows."</p></div>\n";
                echo "<p>".anchor("admin/users/add/", "+ Add a new user")."</p>\n";
                echo "<div class=\"clearer\"></div>\n";
            } else { //if no users on db
                echo "<tr>\n";
                echo "<td colspan=\"7\">Still no users on database, ".anchor("admin/users/add", "create one")." now!</td>\n";
                echo "</tr>\n";
                echo "</table>\n";
            }

            //Debug
            echo debug($users);

            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/dashboard/", "< Go back to Dashboard")."</p>\n";
?>
        </div> <!-- /admin-content -->