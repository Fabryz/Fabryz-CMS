        <div id="admin-content">
            <script language="javascript" type="text/javascript">
            <!--
                function deletePage(url){
                    message = "Attention:\nAre you sure you want to delete this page?\nIt cannot be undone"
                    if (confirm(message)) {
                        document.location = url;
                    }
                }
            // -->
            </script>
<?php       echo "<h1>Page \"".$page->page_title."\"</h1>\n";

            echo $this->session->flashdata("message")."\n";
            echo "<div id=\"details\">";
            echo "<dl>\n";
            echo "\t<dt>ID</dt>\n";
            echo "\t<dd>".$page->page_id."</dd>\n";
            echo "\t<dt>Title</dt>\n";
            echo "\t<dd>".$page->page_title."</dd>\n";
            echo "\t<dt>Body</dt>\n";
            echo "\t<dd>".htmlspecialchars($page->page_body)."</dd>\n";
            echo "\t<dt>Parent page</dt>\n";
            echo "\t<dd>".($page->page_parent_id==0?"<em>(No Parent)</em>":anchor("admin/pages/".$page->page_parent_id, $page->page_parent_title))."</dd>\n";
            $options = array(
                          'published' => 'Published',
                          'not-published' => 'Not published',
                          'private' => 'Private'
                        );
            echo "\t<dt>Status</dt>\n";
            echo "\t<dd class=\"".$page->page_status."\">".$options[$page->page_status]."</dd>\n";
            echo "\t<dt>Author</dt>\n";
            echo "\t<dd>".anchor("admin/users/".$page->page_author_id, $page->user_username)."</dd>\n";
            echo "\t<dt>Date</dt>\n";
            echo "\t<dd>".date($date_format, strtotime($page->page_date))."</dd>\n";
            echo "\t<dt>Modified date</dt>\n";
            echo "\t<dd>".(empty($page->page_date_modified)?"<em>None</em>":date($date_format, strtotime($page->page_date_modified)))."</dd>\n";
            echo "<hr class=\"separator\"/>\n";
            echo "\t<dt>Alias</dt>\n";
            echo "\t<dd>".$page->page_alias."</dd>\n";
            echo "\t<dt>Meta description</dt>\n";
            echo "\t<dd>".(empty($page->page_meta_description)?"<em>None</em>":$page->page_meta_description)."</dd>\n";
            echo "\t<dt>Meta keywords</dt>\n";
            echo "\t<dd>".(empty($page->page_meta_keywords)?"<em>None</em>":$page->page_meta_keywords)."</dd>\n";
            echo "<hr class=\"separator\"/>\n";
            echo "\t<dt>Custom charset</dt>\n";
            echo "\t<dd>".(empty($page->page_charset)?"<em>None</em>":$page->page_charset)."</dd>\n";
            echo "\t<dt>Custom language</dt>\n";
            echo "\t<dd>".(empty($page->page_lang)?"<em>None</em>":$page->page_lang)."</dd>\n";
            echo "\t<dt>Viewed</dt>\n";
            echo "\t<dd>".$page->page_views." times</dd>\n";
            echo "</dl>\n";
            echo "</div>\n";

            //Debug
            echo debug($page);
            
            echo "<hr class=\"separator\"/>\n";
            //Allow edit/delete only
            //if logged user has "Editor" role or is the post owner
            $has_permission = $this->user_model->has_permission(1, FALSE);
            $is_owner = ($this->session->userdata("user_id") == $page->page_author_id);

            if (($has_permission) || $is_owner) {
                echo "<p>".anchor("admin/pages/edit/".$page->page_id, "* Edit this Page")."</p>\n";
                echo "<p><a href=\"javascript:void(0);\" title=\"Delete this page\" onclick=\"deletePage('".site_url("admin/pages/delete")."/".$page->page_id."');\">- Delete this page</a></p>\n";
            }
            echo "<p>".anchor("admin/pages/add/", "+ Add a new page")."</p>\n";
            echo "<p>".anchor("site/".$page->page_id, "View on live site &raquo;")."</p>\n";
            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/pages/", "< View all pages")."</p>\n";
?>
        </div> <!-- /admin-content -->