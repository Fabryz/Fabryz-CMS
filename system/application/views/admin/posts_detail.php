        <div id="admin-content">
            <script language="javascript" type="text/javascript">
            <!--
                function deletePost(url){
                    message = "Attention:\nAre you sure you want to delete this post?\nIt cannot be undone"
                    if (confirm(message)) {
                        document.location = url;
                    }
                }
            // -->
            </script>
<?php       echo "<h1>Post \"".$post->post_title."\"</h1>\n";

            echo $this->session->flashdata("message")."\n";
            echo "<div id=\"details\">";
            echo "<dl>\n";
            echo "\t<dt>ID</dt>\n";
            echo "\t<dd>".$post->post_id."</dd>\n";
            echo "\t<dt>Title</dt>\n";
            echo "\t<dd>".$post->post_title."</dd>\n";
            echo "\t<dt>Body</dt>\n";
            echo "\t<dd>".htmlspecialchars($post->post_body)."</dd>\n";
            echo "\t<dt>Summary</dt>\n";
            echo "\t<dd>".(empty($post->post_summary)?"<em>None</em>":$post->post_summary)."</dd>\n";
            echo "\t<dt>Category</dt>\n";
            echo "\t<dd>".anchor("admin/categories/".$post->category_id, $post->category_title)."</dd>\n";
            $options = array(
                          'published' => 'Published',
                          'not-published' => 'Not published',
                          'private' => 'Private'
                        );
            echo "\t<dt>Status</dt>\n";
            echo "\t<dd class=\"".$post->post_status."\">".$options[$post->post_status]."</dd>\n";
            echo "\t<dt>Author</dt>\n";
            echo "\t<dd>".anchor("admin/users/".$post->post_author_id, $post->user_username)."</dd>\n";
            echo "\t<dt>Date</dt>\n";
            echo "\t<dd>".date($date_format, strtotime($post->post_date))."</dd>\n";
            echo "\t<dt>Modified date</dt>\n";
            echo "\t<dd>".(empty($post->post_date_modified)?"<em>None</em>":date($date_format, strtotime($post->post_date_modified)))."</dd>\n";
            echo "<hr class=\"separator\"/>\n";
            echo "\t<dt>Alias</dt>\n";
            echo "\t<dd>".$post->post_alias."</dd>\n";
            echo "\t<dt>Meta description</dt>\n";
            echo "\t<dd>".(empty($post->post_meta_description)?"<em>None</em>":$post->post_meta_description)."</dd>\n";
            echo "\t<dt>Meta keywords</dt>\n";
            echo "\t<dd>".(empty($post->post_meta_keywords)?"<em>None</em>":$post->post_meta_keywords)."</dd>\n";
            echo "<hr class=\"separator\"/>\n";
            echo "\t<dt>Custom charset</dt>\n";
            echo "\t<dd>".(empty($post->post_charset)?"<em>None</em>":$post->post_charset)."</dd>\n";
            echo "\t<dt>Custom language</dt>\n";
            echo "\t<dd>".(empty($post->post_lang)?"<em>None</em>":$post->post_lang)."</dd>\n";
            echo "\t<dt>Viewed</dt>\n";
            echo "\t<dd>".$post->post_views." times</dd>\n";
            echo "</dl>\n";
            echo "</div>\n";

            //Debug
            echo debug($post);
            
            echo "<hr class=\"separator\"/>\n";
            //Allow edit/delete only
            //if logged user has "Editor" role or is the post owner
            $has_permission = $this->user_model->has_permission(1, FALSE);
            $is_owner = ($this->session->userdata("user_id") == $post->post_author_id);

            if (($has_permission == TRUE) || $is_owner) {
                echo "<p>".anchor("admin/posts/edit/".$post->post_id, "* Edit this Post")."</p>\n";
                echo "<p><a href=\"javascript:void(0);\" title=\"Delete this post\" onclick=\"deletePost('".site_url("admin/posts/delete")."/".$post->post_id."');\">- Delete this post</a></p>\n";
            }
            echo "<p>".anchor("admin/posts/add/", "+ Add a new post")."</p>\n";
            echo "<p>".anchor("site/posts/".$post->post_id, "View on live site &raquo;")."</p>\n";
            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/posts/", "< View all posts")."</p>\n";
?>
        </div> <!-- /admin-content -->