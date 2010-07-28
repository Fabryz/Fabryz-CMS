<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends Model {

	function validate_user($user, $pass) {
		$this->db->where("user_username", $user);
		$this->db->where("user_password", $pass);
		$query = $this->db->get("users");

		if ($query->num_rows() == 1)
			return TRUE;
		else
            return FALSE;

	}

    function check_availability($col, $value) {
        $this->db->where($col, $value);
        $query = $this->db->get("users");

        if ($query->num_rows() > 0) {
            return FALSE;   //not available
        } else
            return TRUE;    //available
    }

    function is_logged_in() {
		$is_logged_in = $this->session->userdata("is_logged_in");

		if (!isset($is_logged_in) || $is_logged_in != TRUE)
			return FALSE;
        else
            return TRUE;
	}

   /*Check permission to access a certain page or to view something
    *bigger the number bigger the limitations
    *by default it is:
    *   Admin 0
    *   Editor 1
    *   Writer 2
    *
    * You need to have a <= role number to access:
    * logged_user_role <= $requested_user_role? access : deny
    *
    * $redirect if needed, used FALSE while building the admin menu
    */
   function has_permission($requested_user_role = 99, $redirect = TRUE) {
        if (!$this->is_logged_in()) {   //is he even logged in?
             $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> You are not authorized to view that page, do the login first!</p></div>");
             redirect("admin/auth/");
        } elseif ($this->session->userdata("user_role") <= $requested_user_role) {  //is he allowed to do this?
                return TRUE;
            }
            elseif ($redirect) {
                $this->session->set_flashdata("message", "<div class=\"message error\"><p><strong>Attention:</strong> You haven't the minimum privileges to access that function.</p></div>");
                redirect("admin/dashboard/");
            } else {
                return FALSE;
            }
    }

    function get_user_id($username) {
        $this->db->where("user_username", $username);
        $query = $this->db->get("users");
        
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $userid = $row["user_id"];
            return $userid;
        } else
            return FALSE;
    }

    //Returns all users stored in database
    function get_users($orderby = "user_username", $order = "ASC", $limit = 0, $offset = 0) {  
        $this->db->select("user_id, user_username, user_firstname, user_lastname, user_email, user_avatar, user_about, user_signup_date, user_role, user_logged_in")
                 ->order_by($orderby, $order);
        if ($limit>0) {
            $this->db->limit($limit, $offset);
        }
        $q = $this->db->get("users");

        if ($q->num_rows()>0) {
            return $q->result();
        } else
            return FALSE;
    }

    function get_user($userid) {
        $this->db->select("user_id, user_username, user_firstname, user_lastname, user_email, user_avatar, user_about, user_signup_date, user_role, user_logged_in");
        $q = $this->db->get_where("users", array("user_id" => $userid));

		if ($q->num_rows()>0) {
			return $q->row();
		} else
            return FALSE;
    }

    //Returns a specified column of a given user
    //@todo: make activation key and password unretrievable
    function get_user_col($user_id, $user_col) {
        $this->db->where("user_id", $user_id)
                 ->select($user_col);
        $q = $this->db->get("users");

        if ($q->num_rows()>0) {
            $row = $q->row_array();
            return $row[$user_col];
        } else
            return FALSE;
    }

    //Store a new user on db
    function add_user($new_user) {
        $query = $this->db->insert("users", $new_user);
		return $query;
    }

    //Delete a certain user from db, link all his posts/pages to the default admin
    function remove_user($user_id) {
        $this->db->where("elem_author_id", $user_id);
        $query1 = $this->db->update("elements", array("elem_author_id" => 1));

        $query2 = $this->db->delete("users", array("user_id" => $user_id));
        return $query1 && $query2;
    }

    //Update infos of a user on db
    function update_user($user_id, $user_update) {
        $this->db->where("user_id", $user_id);
        $query = $this->db->update("users", $user_update);
        return $query;
    }

    //Returns the number of all rows stored in database, useful in pagination
    function get_num_rows() {
        return $this->db->count_all("users");
    }

}

?>