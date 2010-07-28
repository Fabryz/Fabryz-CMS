        <div id="admin-content">
            <script language="javascript" type="text/javascript">
            <!--
                function deleteCategory(url){
                    message = "Attention:\nAre you sure you want to delete this category?\nIt cannot be undone"
                    if (confirm(message)) {
                        document.location = url;
                    }
                }
            // -->
            </script>
<?php       echo "<h1>Category \"".$category->cat_title."\"</h1>\n";

            echo $this->session->flashdata("message")."\n";
            echo "<div id=\"details\">\n";
            echo "<dl>\n";
            echo "\t<dt>ID</dt>\n";
            echo "\t<dd>".$category->cat_id."</dd>\n";
            echo "\t<dt>Title</dt>\n";
            echo "\t<dd>".$category->cat_title."</dd>\n";
            echo "\t<dt>Alias</dt>\n";
            echo "\t<dd>".$category->cat_alias."</dd>\n";
            echo "\t<dt>Description</dt>\n";
            if (empty($category->cat_description))
                 echo "\t<dd><em>None</em></dd>\n";
            else
                 echo "\t<dd>".$category->cat_description."</dd>\n";
            echo "\t<dt>Parent category</dt>\n";
            if ($category->cat_parent_id == 0)
                echo "\t<dd><em>None</em></dd>\n";
            else
                echo "\t<dd>".anchor("admin/categories/".$category->cat_parent_id, $category->cat_parent_title)." (ID ".$category->cat_parent_id.")</dd>\n";
            echo "\t<dt>Size</dt>\n";
            echo "\t<dd>".$category->cat_size."</dd>\n";
            
            echo "</dl>\n";
            echo "</div>\n";

            //Debug
            echo debug($category);

            //Allow edit/delete only
            //if logged user has "Editor" role
            $has_permission = $this->user_model->has_permission(1, FALSE);

            if ($has_permission) {
                echo "<p>".anchor("admin/categories/edit/".$category->cat_id, "* Edit this category")."</p>\n";
                if ($category->cat_id != 1) //makes sure Uncategorized is undeletable
                    echo "<p><a href=\"javascript:void(0);\" title=\"Delete this category\" onclick=\"deleteCategory('".site_url("admin/categories/delete")."/".$category->cat_id."');\">- Delete this category</a></p>\n";
            }
            echo "<p>".anchor("admin/categories/add/", "+ Add a new category")."</p>\n";
            echo "<p>".anchor("site/categories/".$category->cat_id, "View on live site &raquo;")."</p>\n";
            echo "<hr class=\"separator\"/>\n";
            echo "<p>".anchor("admin/categories/", "< View all categories")."</p>\n";
?>
        </div> <!-- /admin-content -->