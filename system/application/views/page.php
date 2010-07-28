        <div id="content" class="site-page">
<?php       echo "<h2>".anchor("site/".$page->page_id, $page_title)."</h2>\n";
            /*echo "<div class=\"page-attributes\">\n";
                echo "<span class=\"page-date\">".date($date_format, strtotime($page->page_date))."</span>\n";
                echo "<span class=\"page-author\">by ".anchor("site/users/".$page->page_author_id, $page->user_username)."</span>\n";
            echo "</div>\n";*/

            echo "<div class=\"page-body\">\n<p>".$page->page_body."</p>\n</div>\n";
?>
        </div> <!-- /content -->