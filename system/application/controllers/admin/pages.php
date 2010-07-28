<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends Controller {

	function Pages() {
		parent::Controller();
        $this->load->model("setting_model");
        $this->load->model("user_model");
        $this->load->model("page_model");
	}

    /*
     *          1       2       3       4
     * Default  /admin/pages/
     * Page X   /admin/pages/page/X
     * View     /admin/pages/id
     * Add      /admin/pages/add/
     * Edit     /admin/pages/edit/id
     * Delete   /admin/posts/delete/id
     *
     */

    function index() {
        // _remap stuff
    }

    function _remap() {
        if ( $this->user_model->has_permission(1) ) {
            $s3 = $this->uri->segment(3);
            $s4 = $this->uri->segment(4);

            if ($s3) {
                switch ($s3) {
                    case "add":
                            $this->add();
                        break;
                    case "edit":
                            if ($s4) {
                                //show edit form for page_id = s4
                                if (is_numeric($s4)) {
                                    $this->edit($s4);
                                }
                                else {
                                    //edit/s4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/pages/");
                                }
                            } else {
                                    //calling admin/pages/edit with no parameters, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Calling /edit with no params.</p></div>");
                                    redirect("admin/pages/");
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
                                //delete page_id = s4
                                if (is_numeric($s4)) {
                                    $this->delete($s4);
                                }
                                else {
                                    //delete/s4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/pages/");
                                }
                            } else {
                                    //calling admin/pages/delete with no parameters, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Calling /delete with no params.</p></div>");
                                    redirect("admin/pages/");
                            }
                        break;
                    case "page":
                             //show page "S4" of pages
                             if ($s4) {
                                if (is_numeric($s4)) {
                                    $this->page($s4);
                                }
                                else {
                                    //page/S4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/pages/");
                                }
                            } else {
                                    //s4 not specified, called on page/ (it should be page/1)
                                    redirect("admin/pages/");
                            }
                        break;
                    default:
                            //details of page_id = s3
                            if (is_numeric($s3)) {
                                $this->details($s3);
                            }
                            else {
                                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S3 not numeric.</p></div>");
                                redirect("admin/pages/");
                            }
                        break;
                }
            } else {
                //Default controller view
                //show table of all pages
                $this->table();
            }
        }
    }

    function add() {
        $data["head_queue"][] = add_js_script("js/jquery.min.js");
        $data["head_queue"][] = add_js_script("js/jquery.stringToSlug.js");
        $data["head_queue"][] = add_jquery_script('$("div.collapsed").hide();
                                $("legend").click(function() {
                                    $(this).next("div.collapsible").slideToggle(500);
                                });');
        $data["head_queue"][] = add_jquery_script('$("#page-title").stringToSlug({
                                setEvents: "keyup keydown blur",
                                getPut: "#page-alias",
                                space: "-"
                                });');

        $data["users"] = $this->user_model->get_users();
        $data["pages"] = $this->page_model->get_pages();

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Add a page";
        $data["content"] = "pages_add";
        $this->load->view("admin/include/template", $data);
    }

    function edit($edit_page_id) {
        $data["head_queue"][] = add_js_script("js/jquery.min.js");
        $data["head_queue"][] = add_js_script("js/jquery.stringToSlug.js");
        $data["head_queue"][] = add_jquery_script('$("div.collapsed").hide();
                                $("legend").click(function() {
                                    $(this).next("div.collapsible").slideToggle(500);
                                });');
        $data["head_queue"][] = add_jquery_script('$("#page-title").stringToSlug({
                                setEvents: "keyup keydown blur",
                                getPut: "#page-alias",
                                space: "-"
                                });');

        $data["edit_page"] = $this->page_model->get_page($edit_page_id);
        $data["pages"] = $this->page_model->get_pages();
        $data["users"] = $this->user_model->get_users();

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Editing page: \"".$data["edit_page"]->page_title."\"";
        $data["content"] = "pages_edit";
        $this->load->view("admin/include/template", $data);
    }

    function page($offset) {
        $max_length = 40;

        $pages_total_rows = $this->page_model->get_num_rows();
        if ($offset >= $pages_total_rows) {
            redirect("admin/pages/");
        }

        $this->load->library('pagination');
        $config = array(
            "base_url" => base_url()."admin/pages/page/",
            "total_rows" => $pages_total_rows,
            "per_page" => $this->setting_model->get_site_config("rows_per_page"),
            "uri_segment" => 4,
            "num_links" => 5
        );
        $this->pagination->initialize($config);

        $pages = $this->page_model->get_pages("elem_id", "ASC", $config["per_page"], $offset);

        foreach ($pages as $p) {    //cut the title if it's too long for the table
            $p->page_title = cut_text($p->page_title, $max_length);
            $p->page_parent_title = cut_text($p->page_parent_title, $max_length);
        }

        $data["pagination"] = $this->pagination->create_links();
        $data["pages"] = $pages;
        $data["date_format"] = $this->setting_model->get_site_config("date_format");
        $data["pages_total_rows"] = $pages_total_rows;

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Pages (Page ".(($config["per_page"]+$offset)/$config["per_page"]).")";
        $data["content"] = "pages";
        $this->load->view("admin/include/template", $data);
    }

    function table() {
        $max_length = 40;

        $pages_total_rows = $this->page_model->get_num_rows();

        $this->load->library('pagination');
        $config = array(
            "base_url" => base_url()."admin/pages/page/",
            "total_rows" => $pages_total_rows,
            "per_page" => $this->setting_model->get_site_config("rows_per_page"),
            "uri_segment" => 4,
            "num_links" => 5
        );
        $this->pagination->initialize($config);

        $pages = $this->page_model->get_pages("elem_id", "ASC", $config["per_page"], 0);

        if ($pages) {
            foreach ($pages as $p) {    //cut the title if it's too long for the table
                $p->page_title = cut_text($p->page_title, $max_length);
                $p->page_parent_title = cut_text($p->page_parent_title, $max_length);
            }
        }

        $data["pagination"] = $this->pagination->create_links();
        $data["pages"] = $pages;
        $data["date_format"] = $this->setting_model->get_site_config("date_format");
        $data["pages_total_rows"] = $pages_total_rows;

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Pages";
        $data["content"] = "pages";
        $this->load->view("admin/include/template", $data);
    }

    function details($page_id) {
        $page = $this->page_model->get_page($page_id);

        $data["page"] = $page;
        $data["date_format"] = $this->setting_model->get_site_config("date_format");

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Details of page: \"".$page->page_title."\"";
        $data["content"] = "pages_detail";
        $this->load->view("admin/include/template", $data);
    }

    function create() { 
        $this->load->library("form_validation");

        //field name, error message, validation rules
        $this->form_validation->set_rules("page-title", "Page title", "trim|required|max_length[200]");
        $this->form_validation->set_rules("page-body", "Page body", "trim|required");
        $this->form_validation->set_rules("page-alias", "Page alias", "trim|max_length[200]|unique[elements.elem_alias]");
        $this->form_validation->set_rules("page-meta-description", "Page meta description", "trim|max_length[255]");
        $this->form_validation->set_rules("page-meta-keywords", "Page meta keywords", "trim|max_length[255]");

		if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Make sure that all the required fields are filled in correctly.</p></div>");
            $this->add();
            //redirect("admin/pages/add/");
		} else {
            $elem_date = date("Y-m-d H:i:s");

            $new_page = array(
                "elem_title" => $this->input->post("page-title"),
                "elem_body" => $this->input->post("page-body"),
                "elem_date" => $elem_date,
                "elem_date" => $elem_date,
                "elem_status" => $this->input->post("page-status"),
                "elem_alias" => $this->input->post("page-alias"),
                "elem_meta_description" => $this->input->post("page-meta-description"),
                "elem_meta_keywords" => $this->input->post("page-meta-keywords"),
                "elem_charset" => $this->input->post("page-charset"),
                "elem_lang" => $this->input->post("page-lang"),
                "elem_type" => "page",
                "elem_views" => 0,
                "elem_parent_id" => $this->input->post("page-parent-id"),
                "elem_author_id" => $this->input->post("page-author-id")
            );

            if (empty($new_page["elem_alias"])) {
                $new_page["elem_alias"] = url_title(replace_accents($new_page["elem_title"]), "dash", TRUE);
            }

            $query = $this->page_model->add_page($new_page);

            if ($query) {
                $this->session->set_flashdata("message", "<div class=\"message ok\"><p><strong>Confirmation:</strong> New page added successfully!</p></div>");
                redirect("admin/pages/");
            } else {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Error while creating new page.</p></div>");
                redirect("admin/pages/");
            }
        }
    }
    
    //@todo: fix unique alias
    function update() {
        $this->load->library("form_validation");

        //field name, error message, validation rules
        $this->form_validation->set_rules("page-title", "Page title", "trim|required|max_length[200]");
        $this->form_validation->set_rules("page-body", "Page body", "trim|required");
        $this->form_validation->set_rules("page-summary", "Page summary", "trim|max_length[200]");
        $this->form_validation->set_rules("page-alias", "Page alias", "trim|max_length[200]");
        $this->form_validation->set_rules("page-meta-description", "Page meta description", "trim|max_length[255]");
        $this->form_validation->set_rules("page-meta-keywords", "Page meta keywords", "trim|max_length[255]");

		if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Make sure that all the required fields are filled in correctly.</p></div>");
			$this->edit($this->input->post("page-id"));
            //redirect("admin/pages/edit/".$this->input->post("page-id"));
		} else {
            $page_id = $this->input->post("page-id");

            $edited_page = array(
                "elem_title" => $this->input->post("page-title"),
                "elem_body" => $this->input->post("page-body"),
                "elem_date" => $this->input->post("page-date"),
                "elem_date_modified" => date("Y-m-d H:i:s"),
                "elem_status" => $this->input->post("page-status"),
                "elem_alias" => $this->input->post("page-alias"),
                "elem_meta_description" => $this->input->post("page-meta-description"),
                "elem_meta_keywords" => $this->input->post("page-meta-keywords"),
                "elem_charset" => $this->input->post("page-charset"),
                "elem_lang" => $this->input->post("page-lang"),
                "elem_summary" => $this->input->post("page-summary"),
                /*"elem_parent_id" => $this->input->post("page-parent-id"),*/
                "elem_author_id" => $this->input->post("page-author-id")
            );

            if (empty($edited_page["elem_alias"])) {
                $edited_page["elem_alias"] = url_title(replace_accents($edited_page["elem_title"]), "dash", TRUE);
            }

            $query = $this->page_model->update_page($page_id, $edited_page);

            if ($query) {
                $this->session->set_flashdata("message", "<div class=\"message ok\"><p><strong>Confirmation:</strong> Page #".$page_id." edited successfully!</p></div>");
                redirect("admin/pages/");
            } else {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Unexpected error while editing page #".$page_id.".</p></div>");
                redirect("admin/pages/");
            }
        }
    }

    function delete($page_id){
        $query = $this->page_model->remove_page($page_id);

        if ($query) {
            $this->session->set_flashdata("message", "<div class=\"message ok\"><p><strong>Confirmation:</strong> Page deleted successfully!</p></div>");
            redirect("admin/pages/");
        } else {
            $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Error while deleting page #".$page_id.".</p></div>");
            redirect("admin/pages/");
        }
    }

}
