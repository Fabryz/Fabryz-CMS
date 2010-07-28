<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post_model extends Model {

    //Returns all posts stored in database
    function get_posts($orderby = "elem_id", $order = "DESC", $limit = 0, $offset = 0) {
        $this->db->select("elem_id AS post_id, elem_title AS post_title, elem_body AS post_body, elem_date AS post_date, elem_date_modified AS post_date_modified, elem_status AS post_status, elem_alias AS post_alias, elem_meta_description AS post_meta_description, elem_meta_keywords AS post_meta_keywords, elem_charset AS post_charset, elem_lang AS post_lang, elem_summary AS post_summary, elem_views AS post_views, elem_parent_id AS post_parent_id, elem_author_id AS post_author_id, user_username, user_firstname, user_lastname, user_email, user_avatar, user_about, user_signup_date, user_role, user_logged_in, cat_id AS category_id, cat_title AS category_title, cat_description AS category_description, cat_parent_id AS category_parent_id")
                 ->join("relationships", "relationships.element_id = elements.elem_id")
                 ->join("categories", "categories.cat_id = relationships.category_id")
                 ->join("users", "users.user_id = elements.elem_author_id")
                 ->order_by("elements.".$orderby, $order);
        if ($limit>0) {
            $this->db->limit($limit, $offset);
        }
        $q = $this->db->get_where("elements", array("elements.elem_type" => "post"));

        if ($q->num_rows()>0) {
            return $q->result();
        } else
            return FALSE;
    }

    //Returns all posts stored under a certain category
    function get_posts_of($table, $table_id, $orderby = "elem_id", $order = "DESC", $limit = 0, $offset = 0) {
        $this->db->select("elem_id AS post_id, elem_title AS post_title, elem_body AS post_body, elem_date AS post_date, elem_date_modified AS post_date_modified, elem_status AS post_status, elem_alias AS post_alias, elem_meta_description AS post_meta_description, elem_meta_keywords AS post_meta_keywords, elem_charset AS post_charset, elem_lang AS post_lang, elem_summary AS post_summary, elem_views AS post_views, elem_parent_id AS post_parent_id, elem_author_id AS post_author_id, user_username, user_firstname, user_lastname, user_email, user_avatar, user_about, user_signup_date, user_role, user_logged_in, cat_id AS category_id, cat_title AS category_title, cat_description AS category_description, cat_parent_id AS category_parent_id")
                 ->join("relationships", "relationships.element_id = elements.elem_id")
                 ->join("categories", "categories.cat_id = relationships.category_id")
                 ->join("users", "users.user_id = elements.elem_author_id")
                 ->order_by("elements.".$orderby, $order);
        if ($limit>0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->where($table, $table_id);
        $q = $this->db->get_where("elements", array("elements.elem_type" => "post"));

        if ($q->num_rows()>0) {
            return $q->result();
        } else
            return FALSE;
    }

    //Returns full infos of a given post
    function get_post($post_id) {
        $this->db->select("elem_id AS post_id, elem_title AS post_title, elem_body AS post_body, elem_date AS post_date, elem_date_modified AS post_date_modified, elem_status AS post_status, elem_alias AS post_alias, elem_meta_description AS post_meta_description, elem_meta_keywords AS post_meta_keywords, elem_charset AS post_charset, elem_lang AS post_lang, elem_summary AS post_summary, elem_views AS post_views, elem_parent_id AS post_parent_id, elem_author_id AS post_author_id, user_username, user_firstname, user_lastname, user_email, user_avatar, user_about, user_signup_date, user_role, user_logged_in, cat_id AS category_id, cat_title AS category_title, cat_description AS category_description, cat_parent_id AS category_parent_id")
                 ->join("relationships", "relationships.element_id = elements.elem_id")
                 ->join("categories", "categories.cat_id = relationships.category_id")
                 ->join("users", "users.user_id = elements.elem_author_id")
                 ->where("elem_id", $post_id);
        $q = $this->db->get_where("elements", array("elem_type" => "post"));

        if ($q->num_rows()>0) {
            return $q->row();
        } else
            return FALSE;
    }

    //Returns a specified column of a given post
    function get_post_col($post_id, $post_col) {
        $this->db->where("elem_id", $post_id)
                 ->select($post_col);
        $q = $this->db->get_where("elements", array("elem_type" => "post"));

        if ($q->num_rows()>0) {
            $row = $q->row_array();
            return $row[$post_col];
        } else
            return FALSE;
    }

    //Store a new post on db
    function add_post($new_post, $category_id) {
        $query1 = $this->db->insert("elements", $new_post);

        $new_relationship = array(
            "category_id" => $category_id,
            "element_id" => $this->db->insert_id()
        );
        $query2 = $this->db->insert("relationships", $new_relationship);

		return $query1 && $query2;
    }

    //Delete a certain post from db
    function remove_post($post_id) {
        $query1 = $this->db->delete("elements", array('elem_id' => $post_id));
        $query2 = $this->db->delete("relationships", array('element_id' => $post_id));
        return $query1 && $query2;
    }

    //Update infos of a post on db
    function update_post($post_id, $post_update, $cat_id) {
        $this->db->where("elem_id", $post_id);
        $query1 = $this->db->update("elements", $post_update);

        $this->db->where("element_id", $post_id);
        $query2 = $this->db->update("relationships", array("category_id" => $cat_id));
        return $query1 && $query2;
    }

    //Returns the number of all rows stored in database, useful in pagination
    function get_num_rows() {
        $q = $this->db->get_where("elements", array("elem_type" => "post"));
        return $q->num_rows();
    }

}
