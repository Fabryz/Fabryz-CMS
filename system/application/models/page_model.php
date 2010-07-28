<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_model extends Model {

    //Returns all pages stored in database 
    function get_pages($orderby = "elem_id", $order = "ASC", $limit = 0, $offset = 0) {
        $this->db->select("elements.elem_id AS page_id, elements.elem_title AS page_title, elements.elem_body AS page_body, elements.elem_date AS page_date, elements.elem_date_modified AS page_date_modified, elements.elem_status AS page_status, elements.elem_alias AS page_alias, elements.elem_meta_description AS page_meta_description, elements.elem_meta_keywords AS page_meta_keywords, elements.elem_charset AS page_charset, elements.elem_lang AS page_lang, elements.elem_summary AS page_summary, elements.elem_views AS page_views, elements.elem_parent_id AS page_parent_id, parent.elem_title AS page_parent_title, elements.elem_author_id AS page_author_id, user_username, user_firstname, user_lastname, user_email, user_avatar, user_about, user_signup_date, user_role, user_logged_in")
                 ->join("users", "users.user_id = elements.elem_author_id")
                 ->join("elements parent", "parent.elem_id = elements.elem_parent_id", "left")
                 ->order_by("elements.".$orderby, $order);
        if ($limit>0) {
            $this->db->limit($limit, $offset);
        }
        $q = $this->db->get_where("elements", array("elements.elem_type" => "page"));

        if ($q->num_rows()>0) {
            return $q->result();
        } else
            return FALSE;
    }

    //Returns full infos of a given page
    function get_page($page_id) {
        $this->db->select("elements.elem_id AS page_id, elements.elem_title AS page_title, elements.elem_body AS page_body, elements.elem_date AS page_date, elements.elem_date_modified AS page_date_modified, elements.elem_status AS page_status, elements.elem_alias AS page_alias, elements.elem_meta_description AS page_meta_description, elements.elem_meta_keywords AS page_meta_keywords, elements.elem_charset AS page_charset, elements.elem_lang AS page_lang, elements.elem_summary AS page_summary, elements.elem_views AS page_views, elements.elem_parent_id AS page_parent_id, parent.elem_title AS page_parent_title, elements.elem_author_id AS page_author_id, user_username, user_firstname, user_lastname, user_email, user_avatar, user_about, user_signup_date, user_role, user_logged_in")
                 ->join("users", "users.user_id = elements.elem_author_id")
                 ->join("elements parent", "parent.elem_id = elements.elem_parent_id", "left")
                 ->where("elements.elem_id", $page_id);
        $q = $this->db->get_where("elements", array("elements.elem_type" => "page"));

        if ($q->num_rows()>0) {
            return $q->row();
        } else
            return FALSE;
    }

    //Returns a specified column of a given page
    function get_page_col($page_id, $page_col) {
        $this->db->where("elem_id", $page_id)
                 ->select($page_col);
        $q = $this->db->get_where("elements", array("elem_type" => "page"));

        if ($q->num_rows()>0) {
            $row = $q->row_array();
            return $row[$page_col];
        } else
            return FALSE;
    }

    //Store a new page on db
    function add_page($new_page) {
        $query = $this->db->insert("elements", $new_page);
		return $query;
    }

    //Delete a certain page from db
    function remove_page($page_id) {
        $query = $this->db->delete("elements", array('elem_id' => $page_id));
        return $query;
    }

    //Update infos of a page on db
    function update_page($page_id, $page_update) {
        $this->db->where("elem_id", $page_id);
        $query = $this->db->update("elements", $page_update);
        return $query;
    }

    //Returns the number of all rows stored in database, useful in pagination
    function get_num_rows() {
        $q = $this->db->get_where("elements", array("elem_type" => "page"));
        return $q->num_rows();
    }

}

?>