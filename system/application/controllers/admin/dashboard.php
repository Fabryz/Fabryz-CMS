<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Controller {

	function Dashboard() {
		parent::Controller();
        $this->load->model("setting_model");
        $this->load->model("user_model");

        $this->load->library("user_agent");
	}

    function index() {
		if ( $this->user_model->has_permission(2) ) {
            $data["configs"] = $this->setting_model->get_site_settings_assoc();
            $data["page_title"] = "Dashboard";
            $data["content"] = "dashboard";
            $this->load->view("admin/include/template", $data);
        }
    }

}
