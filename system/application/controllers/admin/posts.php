<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends Controller {

	function Posts() {
		parent::Controller();
        $this->load->model("setting_model");
        $this->load->model("user_model");
        $this->load->model("category_model");
        $this->load->model("post_model");
	}

    /*
     *          1       2       3       4
     * Default  /admin/posts/
     * Page X   /admin/posts/page/X
     * View     /admin/posts/id
     * Add      /admin/posts/add/
     * Edit     /admin/posts/edit/id
     * Delete   /admin/posts/delete/id
     *
     */

    function index() {
        // _remap stuff
    }

    function _remap() {
        if ( $this->user_model->has_permission(2) ) {
            $s3 = $this->uri->segment(3);
            $s4 = $this->uri->segment(4);
            
            if ($s3) {
                switch ($s3) {
                    case "add":
                            $this->add();
                        break;
                    case "edit":
                            if ($s4) {
                                //show edit form for post_id = s4
                                if (is_numeric($s4)) {
                                    $this->edit($s4);
                                }
                                else {
                                    //edit/s4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/posts/");
                                }
                            } else {
                                    //calling admin/posts/edit with no parameters, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Calling /edit with no params.</p></div>");
                                    redirect("admin/posts/");
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
                                //delete post_id = s4
                                if (is_numeric($s4)) {
                                    $this->delete($s4);
                                }
                                else {
                                    //delete/s4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/posts/");
                                }
                            } else {
                                    //calling admin/posts/delete with no parameters, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Calling /delete with no params.</p></div>");
                                    redirect("admin/posts/");
                            }
                        break;
                    case "page":
                             //show page "S4" of posts
                             if ($s4) {
                                if (is_numeric($s4)) {
                                    $this->page($s4);
                                }
                                else {
                                    //page/S4 not numeric, redirect
                                    $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S4 not numeric.</p></div>");
                                    redirect("admin/posts/");
                                }
                            } else {
                                    //s4 not specified, called on page/ (it should be page/1)
                                    redirect("admin/posts/");
                            }
                        break;
                    default:
                            //details of post_id = s3
                            if (is_numeric($s3)) {
                                $this->details($s3);
                            }
                            else {
                                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S3 not numeric.</p></div>");
                                redirect("admin/posts/");
                            }
                        break;
                }
            } else {
                //Default controller view
                //show table of all post
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
        $data["head_queue"][] = add_jquery_script('$("#post-title").stringToSlug({
                                setEvents: "keyup keydown blur",
                                getPut: "#post-alias",
                                space: "-"
                                });');

        $data["categories"] = $this->category_model->get_categories();
        $data["default_category"] = $this->setting_model->get_site_config("posts_default_category");
        $data["users"] = $this->user_model->get_users();

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Add a post";
        $data["content"] = "posts_add";
        $this->load->view("admin/include/template", $data);
    }

    function edit($edit_post_id) {
        $data["head_queue"][] = add_js_script("js/jquery.min.js");
        $data["head_queue"][] = add_js_script("js/jquery.stringToSlug.js");
        $data["head_queue"][] = add_jquery_script('$("div.collapsed").hide();
                                $("legend").click(function() {
                                    $(this).next("div.collapsible").slideToggle(500);
                                });');
        $data["head_queue"][] = add_jquery_script('$("#post-title").stringToSlug({
                                setEvents: "keyup keydown blur",
                                getPut: "#post-alias",
                                space: "-"
                                });');

        $data["edit_post"] = $this->post_model->get_post($edit_post_id);
        $data["categories"] = $this->category_model->get_categories();
        $data["users"] = $this->user_model->get_users();

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Editing post: \"".$data["edit_post"]->post_title."\"";
        $data["content"] = "posts_edit";
        $this->load->view("admin/include/template", $data);
    }

    function page($offset) {
        $max_length = 40;
        
        $posts_total_rows = $this->post_model->get_num_rows();
        if ($offset >= $posts_total_rows) {
            redirect("admin/posts/");
        }

        $this->load->library('pagination');
        $config = array(
            "base_url" => base_url()."admin/posts/page/",
            "total_rows" => $posts_total_rows,
            "per_page" => $this->setting_model->get_site_config("rows_per_page"),
            "uri_segment" => 4,
            "num_links" => 5
        );
        $this->pagination->initialize($config);

        $posts = $this->post_model->get_posts("elem_id", "DESC", $config["per_page"], $offset);

        foreach ($posts as $p) {    //cut the title if it's too long for the table
            $p->post_title = cut_text($p->post_title, $max_length);
        }
        
        $data["pagination"] = $this->pagination->create_links();
        $data["posts"] = $posts;
        $data["date_format"] = $this->setting_model->get_site_config("date_format");
        $data["posts_total_rows"] = $posts_total_rows;

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Posts (Page ".(($config["per_page"]+$offset)/$config["per_page"]).")";
        $data["content"] = "posts";
        $this->load->view("admin/include/template", $data);
    }

    function table() {
        $max_length = 40;

        $posts_total_rows = $this->post_model->get_num_rows();
        
        $this->load->library('pagination');
        $config = array(
            "base_url" => base_url()."admin/posts/page/",
            "total_rows" => $posts_total_rows,
            "per_page" => $this->setting_model->get_site_config("rows_per_page"),
            "uri_segment" => 4,
            "num_links" => 5
        );
        $this->pagination->initialize($config);

        $posts = $this->post_model->get_posts("elem_id", "DESC", $config["per_page"], 0);

        if ($posts) {
            foreach ($posts as $p) {    //cut the title if it's too long for the table
                $p->post_title = cut_text($p->post_title, $max_length);
            }
        }

        $data["pagination"] = $this->pagination->create_links();
        $data["posts"] = $posts;
        $data["date_format"] = $this->setting_model->get_site_config("date_format");
        $data["posts_total_rows"] = $posts_total_rows;

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Posts";
        $data["content"] = "posts";
        $this->load->view("admin/include/template", $data);
    }

    function details($post_id) {
        $post = $this->post_model->get_post($post_id);

        $data["post"] = $post;
        $data["date_format"] = $this->setting_model->get_site_config("date_format");

        $data["configs"] = $this->setting_model->get_site_settings_assoc();
        $data["page_title"] = "Details of post: \"".$post->post_title."\"";
        $data["content"] = "posts_detail";
        $this->load->view("admin/include/template", $data);
    }

    function create() { //@todo: sistemare regole
        $this->load->library("form_validation");

        //field name, error message, validation rules
        $this->form_validation->set_rules("post-title", "Post title", "trim|required|max_length[200]");
        $this->form_validation->set_rules("post-body", "Post body", "trim|required");
        $this->form_validation->set_rules("post-summary", "Post summary", "trim|max_length[200]");
        $this->form_validation->set_rules("post-alias", "Post alias", "trim|max_length[200]|unique[elements.elem_alias]");
        $this->form_validation->set_rules("post-meta-description", "Post meta description", "trim|max_length[255]");
        $this->form_validation->set_rules("post-meta-keywords", "Post meta keywords", "trim|max_length[255]");

		if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Make sure that all the required fields are filled in correctly.</p></div>");
			$this->add();
            //redirect("admin/posts/add/");
		} else {
            $elem_date = date("Y-m-d H:i:s");

            $new_post = array(
                "elem_title" => $this->input->post("post-title"),
                "elem_body" => $this->input->post("post-body"),
                "elem_date" => $elem_date,
                "elem_date" => $elem_date,
                "elem_status" => $this->input->post("post-status"),
                "elem_alias" => $this->input->post("post-alias"),
                "elem_meta_description" => $this->input->post("post-meta-description"),
                "elem_meta_keywords" => $this->input->post("post-meta-keywords"),
                "elem_charset" => $this->input->post("post-charset"),
                "elem_lang" => $this->input->post("post-lang"),
                "elem_type" => "post",
                "elem_summary" => $this->input->post("post-summary"),
                "elem_views" => 0,
                /*"elem_parent_id" => $this->input->post("post-parent-id"),*/
                "elem_author_id" => $this->input->post("post-author-id")
            );

            if (empty($new_post["elem_alias"])) {
                $new_post["elem_alias"] = url_title(replace_accents($new_post["elem_title"]), "dash", TRUE);
            }

            $category_id = $this->input->post("post-category");

            $query = $this->post_model->add_post($new_post, $category_id);

            if ($query) {
                $this->session->set_flashdata("message", "<div class=\"message ok\"><p><strong>Confirmation:</strong> New post added successfully!</p></div>");
                redirect("admin/posts/");
            } else {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Error while creating new post.</p></div>");
                redirect("admin/posts/");
            }
        }
    }

    //@todo: fix unique alias
    function update() {   
        $this->load->library("form_validation");

        //field name, error message, validation rules
        $this->form_validation->set_rules("post-title", "Post title", "trim|required|max_length[200]");
        $this->form_validation->set_rules("post-body", "Post body", "trim|required");
        $this->form_validation->set_rules("post-summary", "Post summary", "trim|max_length[200]");
        $this->form_validation->set_rules("post-alias", "Post alias", "trim|max_length[200]");
        $this->form_validation->set_rules("post-meta-description", "Post meta description", "trim|max_length[255]");
        $this->form_validation->set_rules("post-meta-keywords", "Post meta keywords", "trim|max_length[255]");

		if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Make sure that all the required fields are filled in correctly.</p></div>");
			$this->edit($this->input->post("post-id"));
            //redirect("admin/posts/edit/".$this->input->post("post-id"));
		} else {
            $post_id = $this->input->post("post-id");
            $cat_id = $this->input->post("post-category-id");

            $edited_post = array(
                "elem_title" => $this->input->post("post-title"),
                "elem_body" => $this->input->post("post-body"),
                "elem_date" => $this->input->post("post-date"),
                "elem_date_modified" => date("Y-m-d H:i:s"),
                "elem_status" => $this->input->post("post-status"),
                "elem_alias" => $this->input->post("post-alias"),
                "elem_meta_description" => $this->input->post("post-meta-description"),
                "elem_meta_keywords" => $this->input->post("post-meta-keywords"),
                "elem_charset" => $this->input->post("post-charset"),
                "elem_lang" => $this->input->post("post-lang"),
                "elem_summary" => $this->input->post("post-summary"),
                /*"elem_parent_id" => $this->input->post("post-parent-id"),*/
                "elem_author_id" => $this->input->post("post-author-id")
            );

            if (empty($edited_post["elem_alias"])) {
                $edited_post["elem_alias"] = url_title(replace_accents($edited_post["elem_title"]), "dash", TRUE);
            }

            $query = $this->post_model->update_post($post_id, $edited_post, $cat_id);

            if ($query) {
                $this->session->set_flashdata("message", "<div class=\"message ok\"><p><strong>Confirmation:</strong> Post #".$post_id." edited successfully!</p></div>");
                redirect("admin/posts/");
            } else {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Unexpected error while editing post #".$post_id.".</p></div>");
                redirect("admin/posts/");
            }
        }
    }

    function delete($post_id){
        $query = $this->post_model->remove_post($post_id);

        if ($query) {
            $this->session->set_flashdata("message", "<div class=\"message ok\"><p><strong>Confirmation:</strong> Post deleted successfully!</p></div>");
            redirect("admin/posts/");
        } else {
            $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> Error while deleting post #".$post_id.".</p></div>");
            redirect("admin/posts/");
        }
    }

}
