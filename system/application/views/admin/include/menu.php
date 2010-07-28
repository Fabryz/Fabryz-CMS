<?php
    function getCurrentPage() {
        $CI = &get_instance();
        return $CI->uri->segment(2);
    }
    //add CSS class to current page's list markup
    function isCurrent($page) {
        if (getCurrentPage() == $page) {
            return " class=\"current\"";
        }
    }

    $admin_menu = array(
        array("requested_role" => 2, "URL" => "dashboard", "title" => "Dashboard"),
        array("requested_role" => 0, "URL" => "settings", "title" => "Settings"),
        array("requested_role" => 1, "URL" => "categories", "title" => "Categories"),
        array("requested_role" => 2, "URL" => "posts", "title" => "Posts"),
        array("requested_role" => 1, "URL" => "pages", "title" => "Pages"),
        array("requested_role" => 0, "URL" => "users", "title" => "Users"),

    );

    if ($this->session->userdata("is_logged_in")) {  ?>
        <div id="admin-menu" class="<?php echo getCurrentPage(); ?>">
            <ul>
<?php
                //show the admin menu according to user privileges
                foreach ($admin_menu as $item) {
                    if ( $this->user_model->has_permission($item["requested_role"], FALSE) ) {
                        echo "<li".isCurrent($item["URL"]).">".anchor("admin/".$item["URL"], $item["title"])."</li>\n";
                    }
                }

              ?><li id="view-site"><?php echo anchor("site/", "View site &raquo;"); ?></li>
                <li id="logout"><?php echo anchor("admin/auth/logout/", "Logout (".$this->session->userdata("username").")"); ?></li>
            </ul>
            <div class="clearer"></div>
        </div>

<?php

        //showing sub menu if exists
        if (!(empty($submenu))) {
            echo "\t<div id=\"admin-submenu\">\n";
                echo "\t<ul>\n";
                foreach ($submenu as $elem) {
                    echo "\t<li>".anchor($elem[0], $elem[1], $elem[2])."</li>\n";
                }
                echo "\t</ul>\n";
                echo "<div class=\"clearer\"></div>";
            echo "\t</div>\n";
        }
    }