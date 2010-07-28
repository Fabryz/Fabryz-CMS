        <div id="content" class="site-post">
<?php       echo "<h2>".anchor("site/posts/".$post->post_id, $page_title, 'title="Post permalink"')."</h2>\n";
            echo "<div class=\"post-attributes\">\n";
                echo "<span class=\"post-date\">".date($date_format, strtotime($post->post_date))."</span>\n";
                echo "<span class=\"post-category\">posted under ".anchor("site/categories/".$post->category_id, $post->category_title)."</span>\n";
                echo "<span class=\"post-author\">by ".anchor("site/users/".$post->post_author_id, $post->user_username)."</span>\n";
            echo "</div>\n";

            if (!empty($post->post_summary))
                echo "<div class=\"post-summary\">\n<p>".$post->post_summary."</p>\n</div>\n";
            echo "<div class=\"post-body\">\n<p>".$post->post_body."</p>\n</div>\n";
            if (!empty($post->post_date_modified))
                echo "<div class=\"post-date-modified\">Last modified on ".date($date_format, strtotime($post->post_date_modified))."</div>\n";

?>
        </div> <!-- /content -->