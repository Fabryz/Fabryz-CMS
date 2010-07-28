        <div id="admin-content">
            <script language="javascript" type="text/javascript">
            <!--
                function deleteCategory(id, name, url){
                    message = "Attention:\n\nAre you sure you want to delete the category #"+id+": \""+name+"\"?\nIt cannot be undone."
                    if (confirm(message)) {
                        document.location = url;
                    }
                }
            // -->
            </script>

<?php       echo "<h1>Categories</h1>\n";

            echo $this->session->flashdata("message")."\n";
            echo "<table id=\"table_categories\" class=\"table_view\">\n";
            echo "<tr>\n";
            echo "\t<th>id</th>\n";
            echo "\t<th>Title</th>\n";
            echo "\t<th>Alias</th>\n";
            echo "\t<th>Description</th>\n";
            echo "\t<th>Parent category</th>\n";
            echo "\t<th>Size</th>\n";
            echo "\t<th>Actions</th>\n";
            echo "</tr>\n";

            if ($categories != FALSE) {
                $i = 0;
                foreach ($categories as $c) {
                    if (($i%2)==0)
                         echo "<tr class=\"even\">\n";
                    else
                        echo "<tr class=\"odd\">\n";
                    echo "\t<td class=\"center\">".$c->cat_id."</td>\n";
                    echo "\t<td>".$c->cat_title."</td>\n";
                    echo "\t<td>".$c->cat_alias."</td>\n";
                    if (empty($c->cat_description))
                        echo "\t<td class=\"center\">-</td>\n";
                    else
                        echo "\t<td>".$c->cat_description."</td>\n";
                    if ($c->cat_parent_id == 0)
                        echo "\t<td class=\"center\">-</td>\n";
                    else
                        echo "\t<td class=\"center\">".anchor("admin/categories/".$c->cat_parent_id, $c->cat_parent_title)."</td>\n";
                    echo "\t<td class=\"center\">".$c->cat_size."</td>\n";
                    echo "\t<td>";
                        //Allow edit/delete only
                        //if logged user has "Editor" role
                        $has_permission = $this->user_model->has_permission(1, FALSE);

                        if ($has_permission) {
                            echo anchor("/admin/categories/edit/$c->cat_id", "Edit", 'title="Edit this category"').nbs(4);
                            if ($c->cat_id != 1)    //Make category id=1 "Uncategorized" undeletable
                                echo "<a href=\"javascript:void(0);\" title=\"Delete this category\" onclick=\"deleteCategory('".$c->cat_id."', '".$c->cat_title."', '".site_url("admin/categories/delete")."/".$c->cat_id."');\">Delete</a>".nbs(4);
                        }
                        echo anchor("/admin/categories/$c->cat_id", "Details", 'title="View this category in detail"')."</td>\n";
                    echo "</tr>\n";
                    $i++;
                }
                echo "</table>\n";
                echo "<div id=\"pagination\">".$pagination."</div>\n";
                echo "<div id=\"total_rows\"><p>Total categories: ".$cat_total_rows."</p></div>\n";
                echo "<p>".anchor("admin/categories/add/", "+ Add a new category")."</p>\n";
                echo "<div class=\"clearer\"></div>\n";
            } else { //if no categories on db
                echo "<tr>\n";
                echo "<td colspan=\"7\">Still no categories on database, ".anchor("admin/categories/add", "create one")." now!</td>\n";
                echo "</tr>\n";
                echo "</table>\n";
            }

            //Debug
            echo debug($categories);

            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/dashboard/", "< Go back to Dashboard")."</p>\n";
?>
        </div> <!-- /admin-content -->