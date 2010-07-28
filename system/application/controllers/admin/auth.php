<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Controller {

	function Auth() {
		parent::Controller();
        $this->load->model("setting_model");
        $this->load->model("user_model");
        $this->load->helper("security");
	}

    function index() {
        if ( !$this->user_model->is_logged_in() ) {
            $data["allow_user_signup"] = $this->setting_model->get_site_config("allow_user_signup");

            $data["configs"] = $this->setting_model->get_site_settings_assoc();
            $data["page_title"] = "Login";
            $data["content"] = "auth";
            $this->load->view("admin/include/template", $data);
        } else {
            redirect("admin/dashboard/");
        }
    }

    function login() {
		$validated = $this->user_model->validate_user($this->input->post("username"), dohash($this->input->post("password"), "md5"));

		if ($validated) { //if user credentials are validated
            $user_id = $this->user_model->get_user_id($this->input->post("username"));

			$data = array(
				"username" => $this->input->post("username"),
                "user_id" => $user_id,
                "user_role" => $this->user_model->get_user_col($user_id, "user_role"),
				"is_logged_in" => TRUE
			);

			$this->session->set_userdata($data);
			redirect("admin/dashboard/");
		} else {
            $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Username and/or password are not correct: check your credentials and try again.</p></div>");
			redirect("admin/auth/");
		}
	}

    function logout(){
		$this->session->sess_destroy();
        //$this->session->set_flashdata("message", "<div class=\"message ok\"><p>You&#39;ve logged out successfully!</p></div>");
		redirect("/");
	}
}
