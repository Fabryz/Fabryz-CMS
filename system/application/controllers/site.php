<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends Controller {

	function Site() {
		parent::Controller();
        $this->load->model("setting_model");
        $this->load->model("category_model");
        $this->load->model("post_model");
        $this->load->model("page_model");
        $this->load->model("user_model");
	}

     /*
     *              1       2       3
     * Home      /site/
     * Pages X   /site/X
     * Posts X   /site/posts/X
     * Cats X    /site/categories/X
     * Users X   /site/users/X
      *
      * @todo: add pagination
     *
     */

    function index() {
        // _remap stuff
    }

    function _remap() {
        $s2 = $this->uri->segment(2);
        $s3 = $this->uri->segment(3);
        $s4 = $this->uri->segment(4);

        if ($s2) {
            switch ($s2) {
                case "categories":
                         //show category S3
                         if ($s3) {
                            if (is_numeric($s3)) {
                                $this->category($s3);
                            }
                            else {
                                //category/S3 not numeric, @todo: lookup alias
                                //$this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> category/S3 not numeric.</p></div>");
                                redirect("site/");
                            }
                        } else {
                                //s3 not specified
                                //@todo: show list of categories
                                //$this->categories();
                                redirect("site/");
                        }
                    break;
                case "users":
                         //show user S3
                         if ($s3) {
                            if (is_numeric($s3)) {
                                $this->user($s3);
                            }
                            else {
                                //user/S3 not numeric, redirect @todo: lookup alias
                                //$this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> user/S3 not numeric.</p></div>");
                                redirect("site/");
                            }
                        } else {
                                //s3 not specified
                                //@todo: show list of users
                                redirect("site/");
                        }
                    break;
                case "posts":
                         //show post S3
                         if ($s3) {
                            if (is_numeric($s3)) {
                                $this->post($s3);
                            }
                            else {
                                //posts/S3 not numeric, redirect @todo: lookup alias
                                //$this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> user/S3 not numeric.</p></div>");
                                redirect("site/");
                            }
                        } else {
                                //s3 not specified
                                //@todo: show list of the latest posts
                                redirect("site/");
                        }
                    break;
                default:
                        //show page S2
                        if (is_numeric($s2)) {
                            $this->page($s2);
                        }
                        else {
                            //S3 not numeric, redirect @todo: lookup alias on pages
                            //$this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> S3 not numeric.</p></div>");
                            redirect("site/");
                        }
                    break;
            }
        } else {
            //no parameters, show homepage
            $this->homepage();
        }
    }

    function homepage() {
        $data["configs"] = $this->setting_model->get_site_settings_assoc();

        $data["meta_description"] = $data["configs"]["site_meta_description"];
        $data["meta_keywords"] = $data["configs"]["site_meta_keywords"];
        
        $data["page_title"] = "";
        $data["is_logged_in"] = $this->user_model->is_logged_in();
        $data["content"] = "index";
        $this->load->view("include/template", $data);
    }

    function category($cat_id) {
        $category = $this->category_model->get_category($cat_id);

        if (!$category)
            show_404();

        $data["category"] = $category;
        $data["posts"] = $this->post_model->get_posts_of("relationships.category_id", $cat_id);
        $data["date_format"] = $this->setting_model->get_site_config("date_format");

        $data["configs"] = $this->setting_model->get_site_settings_assoc();

        $data["meta_description"] = "";
        $data["meta_keywords"] = "";

        $data["page_title"] = "Category: ".$category->cat_title;
        $data["content"] = "category";
        $this->load->view("include/template", $data);
    }

    function post($post_id) {
        $post = $this->post_model->get_post($post_id);

        if ((!$post) || ($post->post_status != "published"))
            show_404();

        $data["post"] = $post;
        $data["date_format"] = $this->setting_model->get_site_config("date_format");

        $data["configs"] = $this->setting_model->get_site_settings_assoc();

        $data["meta_description"] = $post->post_meta_description;
        $data["meta_keywords"] = $post->post_meta_keywords;

        $data["page_title"] = $post->post_title;
        $data["content"] = "post";
        $this->load->view("include/template", $data);
    }

    function page($page_id) {
        $page = $this->page_model->get_page($page_id);

        if (!$page)
            show_404();

        $data["page"] = $page;
        $data["date_format"] = $this->setting_model->get_site_config("date_format");

        $data["configs"] = $this->setting_model->get_site_settings_assoc();

        $data["meta_description"] = $page->page_meta_description;
        $data["meta_keywords"] = $page->page_meta_keywords;

        $data["page_title"] = $page->page_title;
        $data["content"] = "page";
        $this->load->view("include/template", $data);
    }

    function user($user_id) {
        $user = $this->user_model->get_user($user_id);

        if (!$user)
            show_404();

        $data["user"] = $user;
        $data["posts"] = $this->post_model->get_posts_of("users.user_id", $user_id);
        $data["date_format"] = $this->setting_model->get_site_config("date_format");

        $data["configs"] = $this->setting_model->get_site_settings_assoc();

        $data["meta_description"] = "";
        $data["meta_keywords"] = "";

        $data["page_title"] = "User: \"".$user->user_username."\"";
        $data["content"] = "user";
        $this->load->view("include/template", $data);
    }
}
