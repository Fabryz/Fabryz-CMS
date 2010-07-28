<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Controller {

	function Settings() {
		parent::Controller();
        $this->load->model("setting_model");
        $this->load->model("user_model");
        $this->load->model("category_model");
	}

    function index() {
        if ( $this->user_model->has_permission(0) ) {
            $this->lang->load("site_settings", "english");

            $data["configs"] = $this->setting_model->get_site_settings_assoc();
            $data["configs_form"] = $this->setting_model->get_site_settings();
            $data["categories"] = $this->category_model->get_categories("cat_title");
            $data["page_title"] = "Manage settings";
            $data["content"] = "settings";
            $this->load->view("admin/include/template", $data);
        }
    }

    function update() {
        //skipping complex form validation for different settings
        $query = $this->setting_model->settings_update();

        if ($query) {
            $this->session->set_flashdata("message", "<div class=\"message ok\"><p>Configurations updated successfully!</p></div>");
            redirect("admin/settings/");
        } else {
               $this->session->set_flashdata("message", "<div class=\"message error\"><p>There was an error while updating configurations.</p></div>");
               redirect("admin/settings/");
        }
    }

}
