        <div id="admin-content">
            <h1><?php echo $page_title; ?></h1>
<?php       echo $this->session->flashdata("message")."\n";

            /*echo "<div id=\"dashboard-debug\">\n";
                echo "<h2>Debug</h2>";
                echo "<p>is_logged_in: ".$this->session->userdata("is_logged_in")."</p>\n";
                echo "<p>username: ".$this->session->userdata("username")."</p>\n";
                echo "<p>user_id: ".$this->session->userdata("user_id")."</p>\n";
                echo "<p>user_role: ".$this->session->userdata("user_role")."</p>\n";
            echo "</div>\n";*/
?>
            <div id="dashboard-about">
                <h2>About</h2>
                <p>This CMS application has been developed by:</p>
                <ul>
                    <li><strong>Codello Fabrizio</strong> - <?php echo safe_mailto("fabryz@gmail.com?subject=LatoServer","fabryz@gmail.com"); ?></li>
                </ul>
                <p>for the &ldquo;Advanced Laboratory on Server-side Technologies&rdquo; class.</p>
                <p>&nbsp;</p>
                <p>Technologies involved in this project:</p>
                <ul id="dashboard-techs">
                    <li>xHTML 1.0 Transitional, CSS 2/3, <a href="http://jquery.com/" title="JQuery official site">JQuery</a> 1.4.2</li>
                    <li><a href="http://codeigniter.com/" title="Codeigniter official site">Codeigniter Framework</a> 1.7.2</li>
                    <li><a href="http://www.apachefriends.org/" title="XAMPP official site">XAMPP platform</a> 1.7.2
                        <ul>
                            <li>Apache 2.2.12</li>
                            <li>PHP 5.3.0</li>
                            <li>MySQL 5.1.37</li>
                        </ul>
                    </li>
                    <li><a href="http://netbeans.org/" title="Netbeans official site">Netbeans editor</a> 6.5</li>
                </ul>
            </div>
            <div id="dashboard-stats">
                <h2>Stats</h2>
                <p>Some useless statistics to entertain you:</p>
                <dl>
                    <dt>Browser / version</dt>
                    <dd><?php echo $this->agent->browser()." / ".$this->agent->version(); ?></dd>
                    <dt>Operative System</dt>
                    <dd><?php echo $this->agent->platform(); ?></dd>
                    <dt>Database platform / version</dt>
                    <dd><?php echo $this->db->platform()." / ".$this->db->version();?></dd>
                    <dt>Tables row count</dt>
                    <dd><?php echo "There are: ".$this->db->count_all("users")." users that made ";
                              echo $this->db->count_all("elements")." total posts/pages, stored in ";
                              echo $this->db->count_all("categories")." total categories.";?></dd>
                </dl>
            </div>
            <div class="clearer"></div>
        </div> <!-- /admin-content -->