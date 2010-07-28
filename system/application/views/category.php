        <div id="content" class="site-category">
<?php       echo "<h2>".$page_title."</h2>\n";

            echo "<h3>Description</h3>\n";
            if (empty($category->cat_description))
                 echo "<p><em>No description</em></p>\n";
            else
                 echo "<p>".$category->cat_description."</p>\n";

            echo "<h3>Parent category</h3>\n";
            if ($category->cat_parent_id == 0)
                echo "\t<p><em>No parent</em></p>\n";
            else
                echo "\t<p>".anchor("site/categories/".$category->cat_parent_id, $category->cat_parent_title)."</p>\n";

            if ($posts) {
                echo "<hr class=\"separator\"/>\n";
                echo "<h3>Posts in this category: ".$category->cat_size."</h3>\n";
                echo "<ul class=\"posts\">\n";
                foreach ($posts as $p) {
                    echo "\t<li>".date($date_format, strtotime($p->post_date))." - ".anchor("site/posts/".$p->post_id, $p->post_title)." from ".anchor("site/users/".$p->post_author_id, $p->user_username)."</li>\n";
                }
                echo "</ul>\n";
            }

?>
        </div> <!-- /content -->