        <div id="admin-content">
            <h1><?php echo $page_title; ?></h1>
<?php
            echo $this->session->flashdata("message")."\n";
            echo form_open("admin/settings/update", 'id="form_configs"')."\n";
            echo form_fieldset("Edit settings")."\n";
                foreach ($configs_form as $c) {
                    switch ($c->key) {
                        case "site_doctype":    //dedicated markup for doctype
                                $options = array(
                                  'xhtml11' => 'XHTML 1.1',
                                  'xhtml1-strict' => 'XHTML 1.0 Strict',
                                  'xhtml1-trans' => 'XHTML 1.0 Transitional',
                                  'xhtml1-frame' => 'XHTML 1.0 Frameset',
                                  'xhtml5' => 'HTML 5',
                                  'html4-strict' => 'HTML 4 Strict',
                                  'html4-trans' => 'HTML 4 Transitional',
                                  'html4-frame' => 'HTML 4 Frameset'
                                );
                                echo lang($c->key, $c->key)."\n";
                                echo form_dropdown($c->key, $options, $c->value)."<br/>\n";
                            break;
                        case "posts_default_category":
                                $options = array();
                                
                                foreach ($categories as $cat) {
                                    $options[$cat->cat_id] = $cat->cat_title;
                                }
                                echo lang($c->key, $c->key)."\n";
                                echo form_dropdown( $c->key, $options, $c->value)."<br/>\n";
                            break;
                        case "allow_user_signup":
                                $options = array();
                                $options[0] = "No";
                                $options[1] = "Yes";
                                echo lang($c->key, $c->key)."\n";
                                echo form_dropdown( $c->key, $options, $c->value)."<br/>\n";
                            break;
                        case "rows_per_page":
                                $options = array();
                                $options = array(
                                    "5" => "5",
                                    "10" => "10",
                                    "15" => "15",
                                    "20" => "20",
                                    "25" => "25"
                                );

                                echo lang($c->key, $c->key)."\n";
                                echo form_dropdown( $c->key, $options, $c->value)."<br/>\n";
                            break;
                        case "date_format":
                                $options = array();
                                $options = array(
                                    "Y/m/d H:i:s" => date("Y/m/d H:i:s"),
                                    "Y/m/d" =>       date("Y/m/d"),
                                    "d/m/Y H:i:s" => date("d/m/Y H:i:s"),
                                    "d/m/Y" =>       date("d/m/Y"),
                                    "H:i:s d/m/Y" => date("H:i:s d/m/Y"),
                                    "d/m/Y" =>       date("d/m/Y"),
                                    "F j Y, g:ia" => date("F j Y, g:ia")
                                );

                                echo lang($c->key, $c->key)."\n";
                                echo form_dropdown( $c->key, $options, $c->value)."<br/>\n";
                            break;
                        case "debug":
                                $options = array();
                                $options[0] = "No";
                                $options[1] = "Yes";
                                echo lang($c->key, $c->key)."\n";
                                echo form_dropdown( $c->key, $options, $c->value)."<br/>\n";
                            break;

                        default: // any other config
                                echo lang($c->key, $c->key)."\n";
                                echo form_input($c->key, $c->value, 'id="'.$c->key.'" size="50"')."<br/>\n";
                            break;
                    }
                }
            echo form_fieldset_close()."\n";
            
            echo form_reset("reset", "Reset")."\n";
            echo form_submit("update", "Update >")."\n";
            echo form_close()."\n";
            echo "<p>".anchor("admin/dashboard/", "< Go back to admin area")."</p>\n";
?>
        </div> <!-- /admin-content -->
