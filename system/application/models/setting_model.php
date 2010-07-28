<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends Model {

    /*
     * Array
            (
            [0] => stdClass Object
                (
                    [id] => 1
                    [key] => site_title
                    [value] => Default site title
                )
     *      ...
     */
    //useful to create the settings forms
    function get_site_settings() {
        $q = $this->db->get("settings");

        if ($q->num_rows()>0) {
            return $q->result();
        } else
            return FALSE;
    }

    /*
     * Array
            (
                [site_title] => Default site title
     *          ...
     * Useful to retrieve site configs for site template
     */
    function get_site_settings_assoc() {
        $q = $this->db->get("settings");

        if ($q->num_rows()>0) {
            foreach ($q->result() as $row) {
                $settings[$row->key] = $row->value;
            }
            return $settings;
        } else
            return FALSE;
    }

    function get_site_config($key) {
        $this->db->where("key", $key);
        $q = $this->db->get("settings");

        if ($q->num_rows()>0) {
            $config = $q->row();
            return $config->value;
        } else
            return FALSE;
    }

    function settings_update() {
        $current_settings = $this->get_site_settings();

        foreach ($current_settings as $c) {
            $new_value = $this->input->post($c->key);
            $data = array("value" => $new_value);
            $this->db->where("key", $c->key);
            $query = $this->db->update("settings", $data);
        }
        return $query;
    }
}
