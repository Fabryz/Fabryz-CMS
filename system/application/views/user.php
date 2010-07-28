        <div id="content" class="site-user">
<?php       echo "<h2>".$page_title."</h2>\n";

            echo "<dl>\n";
            echo "\t<dt>First name</dt>\n";
            echo "\t<dd>".(empty($user->user_firstname)?"<em>None</em>":$user->user_firstname)."</dd>\n";
            echo "\t<dt>Last name</dt>\n";
            echo "\t<dd>".(empty($user->user_lastname)?"<em>None</em>":$user->user_lastname)."</dd>\n";
            echo "\t<dt>Email</dt>\n";
            echo "\t<dd>".safe_mailto($user->user_email, $user->user_email)."</dd>\n";
            echo "\t<dt>Avatar</dt>\n";
            echo "\t<dd>".(empty($user->user_avatar)?"<em>None</em>":$user->user_avatar)."</dd>\n";
            echo "\t<dt>About</dt>\n";
            echo "\t<dd>".(empty($user->user_about)?"<em>None</em>":$user->user_about)."</dd>\n";
            echo "\t<dt>Signup date</dt>\n";
            echo "\t<dd>".date($date_format, strtotime($user->user_signup_date))."</dd>\n";
            echo "\t<dt>Role</dt>\n";
            $roles = array(
                        0 => "Admin",
                        1 => "Editor",
                        2 => "Writer"
                    );
            echo "\t<dd>".$roles[$user->user_role]."</dd>\n";
            echo "</dl>\n";

            if ($posts) {
                echo "<hr class=\"separator\"/>\n";
                echo "<h3>Posts made by this user</h3>\n";
                echo "<ul class=\"posts\">\n";
                foreach ($posts as $p) {
                    echo "\t<li>".date($date_format, strtotime($p->post_date))." - ".anchor("site/posts/".$p->post_id, $p->post_title)." under ".anchor("site/categories/".$p->category_id, $p->category_title)."</li>\n";
                }
                echo "</ul>\n";
            }
?>
        </div> <!-- /content -->