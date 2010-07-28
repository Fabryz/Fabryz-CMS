<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends Controller {

	function Categories() {
		parent::Controller();
        $this->load->model("setting_model");
        $this->load->model("user_model");
        $this->load->model("category_model");
	}

    /*
     *          1       2       3       4
     * Default  /admin/categories/
     * Page X   /admin/categories/page/X
     * View     /admin/categories/id
     * Add      /admin/categories/add/
     * Edit     /admin/categories/edit/id
     * Delete   /admin/categories/delete/id
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
                                //show edit form for category_id = s4
                                if (is_numeric($s4)) {
                                    $this->edit($s4);
                                }
                                else {
                                    //edit/s4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/categories/");
                                }
                            } else {
                                    //calling admin/categories/edit with no parameters, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Calling /edit with no params.</p></div>");
                                    redirect("admin/categories/");
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
                                //delete category_id = s4
                                if (is_numeric($s4)) {
                                    $this->delete($s4);
                                }
                                else {
                                    //delete/s4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/categories/");
                                }
                            } else {
                                    //calling admin/categories/delete with no parameters, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Calling /delete with no params.</p></div>");
                                    redirect("admin/categories/");
                            }
                        break;
                    case "page":    
                             //show page "S4" of categories
                             if ($s4) {
                                if (is_numeric($s4)) {
                                    $this->page($s4);
                                }
                                else {
                                    //page/S4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/categories/");
                                }
                            } else {    
                                    //s4 not specified, called on page/ (it should be page/1)
                                    redirect("admin/categories/");
                            }
                        break;
                    default:
                            //details of category_id = s3
                            if (is_numeric($s3)) {
                                $this->details($s3);
                            }
                            else {
                                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S3 not numeric.</p></div>");
                                redirect("admin/categories/");
                            }
                        break;
                }
            } else {
                //Default controller view
                //show table of all categories
                $this->table();
            }
        }
    }

    function add() {
        $data["head_queue"][] = add_js_script("js/jquery.min.js");
        $data["head_queue"][] = add_js_script("js/jquery.stringToSlug.js");
        $data["head_queue"][] = add_jquery_script('$("#cat-title").stringToSlug({
                                setEvents: "keyup keydown blur",
                                getPut: "#cat-alias",
                                space: "-"
                                });');

        $data["categories"] = $this->category_model->get_categories();
        
        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Add a category";
        $data["content"] = "categories_add";
        $this->load->view("admin/include/template", $data);
    }

    function edit($edit_cat_id) {
        $data["head_queue"][] = add_js_script("js/jquery.min.js");
        $data["head_queue"][] = add_js_script("js/jquery.stringToSlug.js");
        $data["head_queue"][] = add_jquery_script('$("#cat-title").stringToSlug({
                                setEvents: "keyup keydown blur",
                                getPut: "#cat-alias",
                                space: "-"
                                });');

        $data["categories"] = $this->category_model->get_categories();
        $data["edit_category"] = $this->category_model->get_category($edit_cat_id);

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Editing category: \"".$data["edit_category"]->cat_title."\"";
        $data["content"] = "categories_edit";
        $this->load->view("admin/include/template", $data);
    }

    function page($offset) {
        $max_length = 40;
        $cat_total_rows = $this->category_model->get_num_rows();
        if ($offset >= $cat_total_rows) {
            redirect("admin/categories/");
        }

        $this->load->library('pagination');
        $config = array(
            "base_url" => base_url()."admin/categories/page/",
            "total_rows" => $cat_total_rows,
            "per_page" => $this->setting_model->get_site_config("rows_per_page"),
            "uri_segment" => 4,
            "num_links" => 5
        );
        $this->pagination->initialize($config);

        $data["cat_total_rows"] = $cat_total_rows;

        $categories = $this->category_model->get_categories("cat_id", "ASC", $config["per_page"], $offset);
        foreach ($categories as $c) {
            $c->cat_title = cut_text($c->cat_title, $max_length);
            $c->cat_alias = cut_text($c->cat_alias, $max_length);
            $c->cat_description = cut_text($c->cat_description, $max_length);
        }

        $data["pagination"] = $this->pagination->create_links();
        $data["categories"] = $categories;

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Categories (Page ".(($config["per_page"]+$offset)/$config["per_page"]).")";
        $data["content"] = "categories";
        $this->load->view("admin/include/template", $data);
    }

    function table() {
        $max_length = 4;   //should be the same of page() function
        $cat_total_rows = $this->category_model->get_num_rows();

        $this->load->library('pagination');
        $config = array(
            "base_url" => base_url()."admin/categories/page/",
            "total_rows" => $cat_total_rows,
            "per_page" => $this->setting_model->get_site_config("rows_per_page"),
            "uri_segment" => 4,
            "num_links" => 5
        );
        $this->pagination->initialize($config);

        $data["cat_total_rows"] = $cat_total_rows;

        $categories = $this->category_model->get_categories("cat_id", "ASC", $config["per_page"], 0);
        foreach ($categories as $c) {
            cut_text($c->cat_title, $max_length);
            cut_text($c->cat_alias, $max_length);
            cut_text($c->cat_description, $max_length);
        }

        $data["pagination"] = $this->pagination->create_links();
        $data["categories"] = $categories;

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Categories";
        $data["content"] = "categories";
        $this->load->view("admin/include/template", $data);
    }

    function details($cat_id) {
        $category = $this->category_model->get_category($cat_id);

        $data["category"] = $category;

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Details of category: \"".$category->cat_title."\"";
        $data["content"] = "categories_detail";
        $this->load->view("admin/include/template", $data);
    }

    function create() {
        $this->load->library("form_validation");

        //field name, error message, validation rules
        $this->form_validation->set_rules("cat-title", "Category title", "trim|required|max_length[200]");
        $this->form_validation->set_rules("cat-alias", "Category alias", "trim|max_length[200]|unique[categories.cat_alias]");
        $this->form_validation->set_rules("cat-description", "Category description", "trim|max_length[255]");
		$this->form_validation->set_rules("cat-parent-id", "Category parent id", "required");

		if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Make sure that all the required fields are filled in correctly.</p></div>");
			$this->add();
            //redirect("admin/categories/add/");
		} else {
            $new_category = array(
                "cat_title" => $this->input->post("cat-title"),
                "cat_alias" => $this->input->post("cat-alias"),
                "cat_description" => $this->input->post("cat-description"),
                "cat_parent_id" => $this->input->post("cat-parent-id"),
            );

            if (empty($new_category["cat_alias"])) {
                $new_category["cat_alias"] = url_title(replace_accents($new_category["cat_title"]), "dash", TRUE);
            }

            $query = $this->category_model->add_category($new_category);

            if ($query) {
                $this->session->set_flashdata("message", "<div class=\"message ok\"><p><strong>Confirmation:</strong> New category added successfully!</p></div>");
                redirect("admin/categories/");
            } else {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Error while creating new category.</p></div>");
                redirect("admin/categories/");
            }
        }
    }

    function update() {
        $this->load->library("form_validation");

        //field name, error message, validation rules
        $this->form_validation->set_rules("cat-title", "Category title", "trim|required|max_length[200]");
        $this->form_validation->set_rules("cat-alias", "Category alias", "trim|max_length[200]");
        $this->form_validation->set_rules("cat-description", "Category description", "trim|max_legth[255]");
		$this->form_validation->set_rules("cat-parent-id", "Category parent id", "required");

		if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Make sure that all the required fields are filled in correctly.</p></div>");
			$this->edit($this->input->post("cat-id"));
            //redirect("admin/categories/edit/".$this->input->post("cat-id"));
		} else {
            $edited_category = array(
                "cat_id" => $this->input->post("cat-id"),
                "cat_title" => $this->input->post("cat-title"),
                "cat_alias" => $this->input->post("cat-alias"),
                "cat_description" => $this->input->post("cat-description"),
                "cat_parent_id" => $this->input->post("cat-parent-id"),
            );

            if (empty($edited_category["cat_alias"])) {
                $edited_category["cat_alias"] = url_title(replace_accents($edited_category["cat_title"]), "dash", TRUE);
            }

            $query = $this->category_model->update_category($edited_category);

            if ($query) {
                $this->session->set_flashdata("message", "<div class=\"message ok\"><strong><p>Confirmation:</strong> Category #".$edited_category["cat_id"]." edited successfully!</p></div>");
                redirect("admin/categories/");
            } else {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Unexpected error while editing category #".$edited_category["cat_id"].".</p></div>");
                redirect("admin/categories/");
            }
        }
    }

    function delete($cat_id){
        if ($cat_id != 1) { //make sure that Uncategorized category is undeletable
            $query = $this->category_model->remove_category($cat_id);

            if ($query) {
                $this->session->set_flashdata("message", "<div class=\"message ok\"><p><strong>Confirmation:</strong> Category deleted successfully!</p></div>");
                redirect("admin/categories/");
            } else {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Error while deleting category #".$cat_id.".</p></div>");
                redirect("admin/categories/");
            }
        } else {
            redirect("admin/categories/");
        }
    }

}
