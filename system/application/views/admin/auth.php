        <div id="admin-content">
            <div id="login_form">
                <h1><?php echo $page_title; ?></h1>
<?php
                echo $this->session->flashdata("message")."\n";

                echo form_open("admin/auth/login/", 'id="form_login"')."\n";
                    echo form_fieldset("Enter your credentials")."\n";
                        echo form_label("Username", "username")."\n";
                        echo form_input("username", "Username")."<br/>\n";
                        echo form_label("Password", "password")."\n";
                        echo form_password("password", "Password")."<br/>\n";
                    echo form_fieldset_close()."\n";
                    echo form_reset("reset", "Reset", "class=\"button\"")."\n";
                    echo form_submit("submit", "Login", "class=\"button\"")."<br/>\n";
                echo form_close()."\n";
                echo "<p><img class=\"icon\" src=\"".base_url()."img/help.png\" alt=\"Icon forgot password\" /> ".anchor("#", "Forgot password?")."</p>\n";
                if ($allow_user_signup)
                    echo "<p><img class=\"icon\" src=\"".base_url()."img/user_add.png\" alt=\"Icon signup\" /> ".anchor("#", "Create a new account")."</p>\n";
                echo "<p><img class=\"icon\" src=\"".base_url()."img/view_site_alt.png\" alt=\"Icon view site\" /> ".anchor("/site/", "View site &raquo;")."</p>\n";
?>
            </div> <!-- /login_form -->
        </div> <!-- /admin-content -->
