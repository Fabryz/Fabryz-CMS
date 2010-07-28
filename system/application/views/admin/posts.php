        <div id="admin-content">
            <script language="javascript" type="text/javascript">
            <!--
                function deletePost(id, name, url){
                    message = "Attention:\n\nAre you sure you want to delete the post #"+id+": \""+name+"\"?\nIt cannot be undone."
                    if (confirm(message)) {
                        document.location = url;
                    }
                }
            // -->
            </script>

<?php       echo "<h1>Posts</h1>\n";

            echo $this->session->flashdata("message")."\n";
            echo "<table id=\"table_posts\" class=\"table_view\">\n";
            echo "<tr>\n";
            echo "\t<th>id</th>\n";
            echo "\t<th>Title</th>\n";
            echo "\t<th>Category</th>\n";
            echo "\t<th>Author</th>\n";
            echo "\t<th>Date</th>\n";
            echo "\t<th>Views</th>\n";
            echo "\t<th>Status</th>\n";
            echo "\t<th>Actions</th>\n";
            echo "</tr>\n";

            if ($posts != FALSE) {
                $i = 0;
                foreach ($posts as $p) {
                    $statuses = array(
                        "published" => "Published",
                        "private" => "Private",
                        "not-published" => "Not Published"
                    );

                    if (($i%2)==0)
                         echo "<tr class=\"even\">\n";
                    else
                        echo "<tr class=\"odd\">\n";
                    echo "\t<td class=\"center\">".$p->post_id."</td>\n";
                    echo "\t<td>".$p->post_title."</td>\n";
                    echo "\t<td class=\"center\">".anchor("/admin/categories/".$p->category_id, $p->category_title, 'title="View the details of this category"')."</td>\n";
                    echo "\t<td class=\"center\">".anchor("/admin/users/".$p->post_author_id, $p->user_username)."</td>\n";
                    echo "\t<td class=\"center\">".date($date_format, strtotime($p->post_date))."</td>\n";
                    echo "\t<td class=\"center\">".$p->post_views."</td>\n";
                    echo "\t<td class=\"center ".$p->post_status."\">".$statuses[$p->post_status]."</td>\n";

                    echo "\t<td>";
                        //Allow edit/delete only
                        //if logged user has "Editor" role or is the post owner
                        $has_permission = $this->user_model->has_permission(1, FALSE);
                        $is_owner = ($this->session->userdata("user_id") == $p->post_author_id);
                        
                        if (($has_permission) || $is_owner) {
                            echo anchor("/admin/posts/edit/$p->post_id", "Edit", 'title="Edit this post"');
                            echo nbs(4)."<a href=\"javascript:void(0);\" title=\"Delete this post\" onclick=\"deletePost('".$p->post_id."', '".addslashes($p->post_title)."', '".site_url("admin/posts/delete")."/".$p->post_id."');\">Delete</a>".nbs(4);
                        }
                        echo anchor("/admin/posts/".$p->post_id, "Details", 'title="View this post in detail"')."</td>\n";
                    echo "</tr>\n";
                    $i++;
                }
                echo "</table>\n";
                echo "<div id=\"pagination\">".$pagination."</div>\n";
                echo "<div id=\"total_rows\"><p>Total posts: ".$posts_total_rows."</p></div>\n";
                echo "<p>".anchor("admin/posts/add/", "+ Add a new post")."</p>\n";
                echo "<div class=\"clearer\"></div>\n";
            } else { //if no posts on db
                echo "<tr>\n";
                echo "<td colspan=\"8\">Still no posts on database, ".anchor("admin/posts/add", "create one")." now!</td>\n";
                echo "</tr>\n";
                echo "</table>\n";
            }

            //Debug
            echo debug($posts);

            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/dashboard/", "< Go back to Dashboard")."</p>\n";
?>
        </div> <!-- /admin-content -->