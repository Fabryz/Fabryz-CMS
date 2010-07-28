<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Controller {

	function Users() {
		parent::Controller();
        $this->load->model("setting_model");
        $this->load->model("user_model");
        $this->load->helper("security");
	}

    /*
     *          1       2       3       4
     * Default  /admin/users/
     * User X   /admin/users/user/X
     * View     /admin/users/id
     * Add      /admin/users/add/
     * Edit     /admin/users/edit/id
     * Delete   /admin/posts/delete/id
     *
     */

    function index() {
        // _remap stuff
    }

    function _remap() {
        if ( $this->user_model->has_permission(0) ) {
            $s3 = $this->uri->segment(3);
            $s4 = $this->uri->segment(4);

            if ($s3) {
                switch ($s3) {
                    case "add":
                            $this->add();
                        break;
                    case "edit":
                            if ($s4) {
                                //show edit form for user_id = s4
                                if (is_numeric($s4)) {
                                    $this->edit($s4);
                                }
                                else {
                                    //edit/s4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/users/");
                                }
                            } else {
                                    //calling admin/users/edit with no parameters, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Calling /edit with no params.</p></div>");
                                    redirect("admin/users/");
                            }
                        break;
                    case "create":
                            $this->create();
                        break;
                    case "update":
                            $this->update();
                        break;
                    case "delete":
                            if ($s4) {
                                //delete user_id = s4
                                if (is_numeric($s4)) {
                                    $this->delete($s4);
                                }
                                else {
                                    //delete/s4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/users/");
                                }
                            } else {
                                    //calling admin/users/delete with no parameters, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Calling /delete with no params.</p></div>");
                                    redirect("admin/users/");
                            }
                        break;
                    case "page":
                             //show page "S4" of users
                             if ($s4) {
                                if (is_numeric($s4)) {
                                    $this->page($s4);
                                }
                                else {
                                    //page/S4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/users/");
                                }
                            } else {
                                    //s4 not specified, called on page/ (it should be page/1)
                                    redirect("admin/users/");
                            }
                        break;
                    default:
                            //details of user_id = s3
                            if (is_numeric($s3)) {
                                $this->details($s3);
                            }
                            else {
                                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S3 not numeric.</p></div>");
                                redirect("admin/users/");
                            }
                        break;
                }
            } else {
                //Default controller view
                //show table of all users
                $this->table();
            }
        }
    }

    function add() {
        $data["head_queue"][] = add_js_script("js/jquery.min.js");
        $data["head_queue"][] = add_jquery_script('$("div.collapsed").hide();
                                $("legend").click(function() {
                                    $(this).next("div.collapsible").slideToggle(500);
                                });');

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Add a user";
        $data["content"] = "users_add";
        $this->load->view("admin/include/template", $data);
    }

    function edit($edit_user_id) {
        $data["head_queue"][] = add_js_script("js/jquery.min.js");
        $data["head_queue"][] = add_jquery_script('$("div.collapsed").hide();
                                $("legend").click(function() {
                                    $(this).next("div.collapsible").slideToggle(500);
                                });');

        $data["edit_user"] = $this->user_model->get_user($edit_user_id);

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Editing user: \"".$data["edit_user"]->user_username."\"";
        $data["content"] = "users_edit";
        $this->load->view("admin/include/template", $data);
    }

    function page($offset) {
        $max_length = 40;

        $users_total_rows = $this->user_model->get_num_rows();
        if ($offset >= $users_total_rows) {
            redirect("admin/users/");
        }

        $this->load->library('pagination');
        $config = array(
            "base_url" => base_url()."admin/users/page/",
            "total_rows" => $users_total_rows,
            "per_page" => $this->setting_model->get_site_config("rows_per_page"),
            "uri_segment" => 4,
            "num_links" => 5
        );
        $this->pagination->initialize($config);

        $users = $this->user_model->get_users("user_id", "ASC", $config["per_page"], $offset);

        $data["pagination"] = $this->pagination->create_links();
        $data["users"] = $users;
        $data["date_format"] = $this->setting_model->get_site_config("date_format");
        $data["users_total_rows"] = $users_total_rows;

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Users (Page ".(($config["per_page"]+$offset)/$config["per_page"]).")";
        $data["content"] = "users";
        $this->load->view("admin/include/template", $data);
    }

    function table() {
        $max_length = 40;

        $users_total_rows = $this->user_model->get_num_rows();

        $this->load->library('pagination');
        $config = array(
            "base_url" => base_url()."admin/users/page/",
            "total_rows" => $users_total_rows,
            "per_page" => $this->setting_model->get_site_config("rows_per_page"),
            "uri_segment" => 4,
            "num_links" => 5
        );
        $this->pagination->initialize($config);

        $users = $this->user_model->get_users("user_id", "ASC", $config["per_page"], 0);

        $data["pagination"] = $this->pagination->create_links();
        $data["users"] = $users;
        $data["date_format"] = $this->setting_model->get_site_config("date_format");
        $data["users_total_rows"] = $users_total_rows;

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Users";
        $data["content"] = "users";
        $this->load->view("admin/include/template", $data);
    }

    function details($user_id) {
        $user = $this->user_model->get_user($user_id);

        $data["user"] = $user;
        $data["date_format"] = $this->setting_model->get_site_config("date_format");

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Details of user: \"".$user->user_username."\"";
        $data["content"] = "users_detail";
        $this->load->view("admin/include/template", $data);
    }

    function create() {
        $this->load->library("form_validation");

        //field name, error message, validation rules
        $this->form_validation->set_rules("user-username", "Username", "trim|required|min_length[4]|max_length[25]");
        $this->form_validation->set_rules("user-email", "Email address", "trim|required|valid_email|max_length[50]");
        $this->form_validation->set_rules("user-email2", "Email address confirmation", "trim|required|valid_email|matches[user-email]");
		$this->form_validation->set_rules("user-password", "Password", "trim|required|min_length[8]|max_length[32]");
		$this->form_validation->set_rules("user-password2", "Password confirmation", "trim|required|matches[user-password]");

		$this->form_validation->set_rules("user-firstname", "First name", "trim|max_length[25]");
		$this->form_validation->set_rules("user-lastname", "Last name", "trim|max_length[25]");
        $this->form_validation->set_rules("user-avatar", "Avatar", "trim|max_length[255]");
        $this->form_validation->set_rules("user-about", "About", "trim|max_length[100]");

		if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Make sure that all the required fields are filled in correctly.</p></div>");
            $this->add();
            //redirect("admin/users/add/");
		} else {
            if (! $this->user_model->check_availability("user_username", $this->input->post("user-username"))) {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> That username is not available, choose another one.</p></div>");
                redirect("admin/users/add/");
            } elseif (! $this->user_model->check_availability("user_email", $this->input->post("user-email"))) {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> That email has already been used in another account, choose another one.</p></div>");
                redirect("admin/users/add/");
            } else {
                $new_user = array(
                    "user_username" => $this->input->post("user-username"),
                    "user_password" => dohash($this->input->post("user-password"), "md5"),
                    "user_firstname" => $this->input->post("user-firstname"),
                    "user_lastname" => $this->input->post("user-lastname"),
                    "user_email" => $this->input->post("user-email"),
                    "user_avatar" => $this->input->post("user-avatar"),
                    "user_about" => $this->input->post("user-about"),
                    "user_signup_date" => date("Y-m-d H:i:s"),
                    "user_activation_key" => "test",
                    "user_role" => $this->input->post("user-role"),
                    "user_logged_in" => 0,  // 0 == false
                );

                $query = $this->user_model->add_user($new_user);

                if ($query) {
                    $this->session->set_flashdata("message", "<div class=\"message ok\"><p><strong>Confirmation:</strong> New user added successfully!</p></div>");
                    redirect("admin/users/");
                } else {
                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Error while creating new user.</p></div>");
                    redirect("admin/users/");
                }
            }
        }
    }

    function update() {
        $this->load->library("form_validation");

        //field name, error message, validation rules
        $this->form_validation->set_rules("user-email", "Email address", "trim|valid_email|max_length[50]");

        $this->form_validation->set_rules("user-old-password", "Old Password", "trim|min_length[8]|max_length[32]");
        $this->form_validation->set_rules("user-password", "New password", "trim|min_length[8]|max_length[32]");
		$this->form_validation->set_rules("user-password2", "New password confirmation", "trim|matches[user-password]");

		$this->form_validation->set_rules("user-firstname", "First name", "trim|max_length[25]");
		$this->form_validation->set_rules("user-lastname", "Last name", "trim|max_length[25]");
        $this->form_validation->set_rules("user-avatar", "Avatar", "trim|max_length[255]");
        $this->form_validation->set_rules("user-about", "About", "trim|max_length[100]");

		if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Make sure that all the required fields are filled in correctly.</p></div>");
			$this->edit($this->input->post("user-id"));
            //redirect("admin/users/edit/".$this->input->post("user-id"));
		} else {
            $user_id = $this->input->post("user-id");

            $edited_user = array(
                "user_firstname" => $this->input->post("user-firstname"),
                "user_lastname" => $this->input->post("user-lastname"),
                "user_email" => $this->input->post("user-email"),
                "user_avatar" => $this->input->post("user-avatar"),
                "user_about" => $this->input->post("user-about"),
                "user_role" => $this->input->post("user-role"),
            );

            $current_pass = $this->user_model->get_user_col($user_id, "user_password");
            $old_pass = dohash($this->input->post("user-old-password"), "md5");
            if (($this->input->post("user-old-password") != "")) {  //does he want to change the password?
                if ($current_pass == $old_pass) {
                    //if the 2 password fields has been filled, update the new pass
                    if (($this->input->post("user-password") != "") && ($this->input->post("user-password2") != "")) {
                        $edited_user["user_password"] = dohash($this->input->post("user-password"), "md5");
                    } else {
                        $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> The new password and new password verification field don't match.</p></div>");
                        redirect("admin/users/edit/".$this->input->post("user-id"));
                    }
                } else {
                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Your old password is incorrect.</p></div>");
                    redirect("admin/users/edit/".$this->input->post("user-id"));
                }
            } 

            $query = $this->user_model->update_user($user_id, $edited_user);

            if ($query) {
                $this->session->set_flashdata("message", "<div class=\"message ok\"><p><strong>Confirmation:</strong> User #".$user_id." edited successfully!</p></div>");
                redirect("admin/users/");
            } else {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Unexpected error while editing user #".$user_id.".</p></div>");
                redirect("admin/users/");
            }
        }
    }

    function delete($user_id){
        //make sure that the admin account is undeletable, also don't allow the user to delete his own account
        if (($user_id != 1) && ($user_id != $this->session->userdata("user_id"))) {
            $query = $this->user_model->remove_user($user_id);

            if ($query) {
                $this->session->set_flashdata("message", "<div class=\"message ok\"><p><strong>Confirmation:</strong> User deleted successfully!</p></div>");
                redirect("admin/users/");
            } else {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Error while deleting user #".$user_id.".</p></div>");
                redirect("admin/users/");
            }
        } else {
            redirect("admin/users/");
        }
    }

}
