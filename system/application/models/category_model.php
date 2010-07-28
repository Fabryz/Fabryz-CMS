<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends Model {

    //Returns the number of posts contained in a category
    function get_category_size($category_id) {
        $this->db->from("relationships");
        $this->db->where("category_id", $category_id);
        $q = $this->db->count_all_results();

        return $q;
    }

    //Returns all categories stored in database
    //@todo: improve the query to avoid foreach
    function get_categories($orderby = "cat_id", $order = "ASC", $limit = 0, $offset = 0) {
        $this->db->order_by($orderby, $order);
        if ($limit>0) {
            $this->db->limit($limit, $offset);
        }
        $q = $this->db->get("categories"); 

        if ($q->num_rows()>0) {
            $categories = $q->result();
            foreach ($categories as $row) {
                $row->cat_size = $this->get_category_size($row->cat_id);
                $row->cat_parent_title = $this->get_category_col($row->cat_parent_id, "cat_title");
            }
            return $categories;
        } else
            return FALSE;
    }

    //Returns full infos of a given category
    //@todo: improve the query using join
    function get_category($category_id) {
        $this->db->where("cat_id", $category_id);
        $q = $this->db->get("categories");

        if ($q->num_rows()>0) {
            $row = $q->row();
            $row->cat_size = $this->get_category_size($row->cat_id);
            $row->cat_parent_title = $this->get_category_col($row->cat_parent_id, "cat_title");
            return $row;
        } else
            return FALSE;
    }

    //Returns a specified column of a given category
    function get_category_col($category_id, $category_col) {
        $this->db->where("cat_id", $category_id)
                 ->select($category_col);
        $q = $this->db->get("categories");

        if ($q->num_rows()>0) {
            $row = $q->row_array();
            return $row[$category_col];
        } else
            return FALSE;
    }

    //Returns parent infos of a given category
    function get_category_parent($category_id) {
        $parent_id = $this->get_category_col($category_id, "cat_parent_id");
        $parent_category = $this->get_category($category_id);
        if (($parent_id) && ($parent_category)) {
            return $parent_category;
        }
        else
            return FALSE;
    }

    //Store a new category on db
    function add_category($new_category) {
        $query = $this->db->insert("categories", $new_category);
		return $query;
    }

    //Delete a certain category from db
    function remove_category($category_id) {
        //move all this category's posts to the default category (Uncategorized)
        $this->db->where("category_id", $category_id);
        $this->db->update("relationships", array("category_id" => "1"));

        //if it has childrens, make them point to his father as new parent_cat
        $cat_parent_id = $this->get_category_col($category_id, "cat_parent_id");
        $this->db->where("cat_parent_id", $category_id);
        $this->db->update("categories", array("cat_parent_id" => $cat_parent_id));

        $query = $this->db->delete("categories", array('cat_id' => $category_id));
        return $query;
    }

    //Update infos of a category on db
    function update_category($category_update) {
        $this->db->where("cat_id", $category_update["cat_id"]);
        $query = $this->db->update("categories", $category_update);
        return $query;
    }

    //Returns the number of all rows stored in database, useful in pagination
    function get_num_rows() {
        return $this->db->count_all("categories");
    }

}
