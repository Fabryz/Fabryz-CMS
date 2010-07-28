        <div id="content">
<?php
        echo "<h2>Home</h2>";
        echo $this->session->flashdata("message")."\n"; ?>

        <img src="<?php echo base_url() ?>img/dummy-header.png" alt="Dummy header" />
        <hr class="separator"/>
        <div id="home-left">
            <h2>About us</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Aenean vestibulum fringilla semper. Aliquam erat volutpat.
            Etiam quis tortor ut odio laoreet facilisis a ut nisl.
            Aliquam rutrum ornare magna porttitor molestie. Phasellus feugiat,
            lectus eget varius vulputate, neque felis elementum quam, nec vulputate
            quam ante eget ante. Nulla vestibulum semper sapien vitae cursus.
            Ut dignissim neque ac ipsum pharetra dictum. Phasellus eros orci,
            placerat et fermentum vitae, varius sit amet neque. Donec suscipit,
            ligula sodales tristique suscipit, orci felis tempor leo, eget pretium
            lectus augue ut justo. Sed ut diam sem, vitae adipiscing nulla. Suspendisse
            id purus vel massa tempor egestas. <?php echo anchor("site/26", "Learn more &raquo;"); ?></p>
            <h2>Our services</h2>
            <p>Proin libero mi, dictum eget interdum bibendum, ultricies non nisi.
            Cras sodales magna nec tortor placerat vitae gravida nibh dapibus.
            Duis in purus nec velit venenatis luctus id quis lacus. Morbi hendrerit tristique metus,
            ullamcorper laoreet purus euismod non. Pellentesque sed lacus ultrices
            mauris vestibulum lacinia. Aenean placerat hendrerit dignissim. Vivamus
            eget felis mauris. Cras commodo mollis elit ut aliquam. Donec et velit
            at lectus vehicula consectetur ut id erat. Integer vehicula nisi vitae neque
            consequat sodales. Sed a scelerisque libero. Aenean pulvinar tincidunt nisl a accumsan.
            Sed quis dapibus nisl. Suspendisse at mi neque, et tempor leo. <?php echo anchor("site/27", "Learn more &raquo;"); ?></p>
        </div>
        <div id="home-right">
            <h2><?php echo anchor("site/categories/34", "Latest news"); ?></h2>
            <ul>
                <li><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Aenean vestibulum fringilla semper. <br/><?php echo anchor("site/posts/33", "Read more &raquo;"); ?></p></li>
                <li><p>Aliquam erat volutpat.
                    Etiam quis tortor ut odio laoreet facilisis a ut nisl. <br/><?php echo anchor("site/posts/38", "Read more &raquo;"); ?></p></li>
                <li><p>Phasellus feugiat, lectus eget varius vulputate,
                    neque felis elementum quam, nec vulputate
                    quam ante eget ante. <br/><?php echo anchor("site/posts/41", "Read more &raquo;"); ?></p></li>
                <li><p>Ut dignissim neque ac ipsum pharetra dictum. Phasellus eros orci,
                    placerat et fermentum vitae, varius sit amet neque. <br/><?php echo anchor("site/posts/43", "Read more &raquo;"); ?></p></li>
                <li><p>Sed ut diam sem, vitae adipiscing nulla. Suspendisse
                    id purus vel massa tempor egestas. <br/><?php echo anchor("site/posts/37", "Read more &raquo;"); ?></p></li>
            </ul>
        </div>
        <div class="clearer"></div>
        <hr class="separator"/>
<?php   if ($is_logged_in == 0) {
            echo "\t\t<p>".anchor('admin/auth/', 'Login', 'title="Login to the admin area"')."</p>\n";
        } else {
            echo "\t\t<p>".anchor('admin/', 'Admin', 'title="Go to the administration area"')."</p>\n";
            echo "\t\t<p>".anchor('admin/auth/logout/', 'Logout', 'title="Logout from the admin area"')."</p>\n";
        }
        //echo "\t\t<p>".anchor('site/signup/', 'Signup', 'title="Create a new account"')."</p>\n";
?>
        </div> <!-- /content -->
