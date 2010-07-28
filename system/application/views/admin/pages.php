        <div id="admin-content">
            <script language="javascript" type="text/javascript">
            <!--
                function deletePage(id, name, url){
                    message = "Attention:\n\nAre you sure you want to delete the page #"+id+": \""+name+"\"?\nIt cannot be undone."
                    if (confirm(message)) {
                        document.location = url;
                    }
                }
            // -->
            </script>

<?php       echo "<h1>Pages</h1>\n";

            echo $this->session->flashdata("message")."\n";
            echo "<table id=\"table_pages\" class=\"table_view\">\n";
            echo "<tr>\n";
            echo "\t<th>id</th>\n";
            echo "\t<th>Title</th>\n";
            echo "\t<th>Parent page</th>\n";
            echo "\t<th>Author</th>\n";
            echo "\t<th>Date</th>\n";
            echo "\t<th>Views</th>\n";
            echo "\t<th>Status</th>\n";
            echo "\t<th>Actions</th>\n";
            echo "</tr>\n";

            if ($pages) {
                $i = 0;
                foreach ($pages as $p) {
                    $statuses = array(
                        "published" => "Published",
                        "private" => "Private",
                        "not-published" => "Not Published"
                    );

                    if (($i%2)==0)
                         echo "<tr class=\"even\">\n";
                    else
                        echo "<tr class=\"odd\">\n";
                    echo "\t<td class=\"center\">".$p->page_id."</td>\n";
                    echo "\t<td>".$p->page_title."</td>\n";
                    echo "\t<td class=\"center\">".(empty($p->page_parent_title)?"-":anchor("/admin/pages/".$p->page_parent_id, $p->page_parent_title, 'title="View the details of the parent page"'))."</td>\n";
                    echo "\t<td class=\"center\">".anchor("/admin/users/".$p->page_author_id, $p->user_username)."</td>\n";
                    echo "\t<td class=\"center\">".date($date_format, strtotime($p->page_date))."</td>\n";
                    echo "\t<td class=\"center\">".$p->page_views."</td>\n";
                    echo "\t<td class=\"center ".$p->page_status."\">".$statuses[$p->page_status]."</td>\n";

                    echo "\t<td>";
                        //Allow edit/delete only
                        //if logged user has "Editor" role or is the post owner
                        $has_permission = $this->user_model->has_permission(1, FALSE);
                        $is_owner = ($this->session->userdata("user_id") == $p->page_author_id);

                        if (($has_permission == TRUE) || $is_owner) {
                            echo anchor("/admin/pages/edit/$p->page_id", "Edit", 'title="Edit this page"');
                            echo nbs(4)."<a href=\"javascript:void(0);\" title=\"Delete this page\" onclick=\"deletePage('".$p->page_id."', '".addslashes($p->page_title)."', '".site_url("admin/pages/delete")."/".$p->page_id."');\">Delete</a>".nbs(4);
                        }
                        echo anchor("/admin/pages/".$p->page_id, "Details", 'title="View this page in detail"')."</td>\n";
                    echo "</tr>\n";
                    $i++;
                }
                echo "</table>\n";
                echo "<div id=\"pagination\">".$pagination."</div>\n";
                echo "<div id=\"total_rows\"><p>Total pages: ".$pages_total_rows."</p></div>\n";
                echo "<p>".anchor("admin/pages/add/", "+ Add a new page")."</p>\n";
                echo "<div class=\"clearer\"></div>\n";
            } else { //if no pages on db
                echo "<tr>\n";
                echo "<td colspan=\"8\">Still no pages on database, ".anchor("admin/pages/add", "create one")." now!</td>\n";
                echo "</tr>\n";
                echo "</table>\n";
            }

            //Debug
            echo debug($pages);

            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/dashboard/", "< Go back to Dashboard")."</p>\n";
?>
        </div> <!-- /admin-content -->