<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('add_js_script')) {
    function add_js_script($str_in) {
        $str_out = "<script type=\"text/javascript\" src=\"".base_url().$str_in."\"></script>";
        return $str_out;
    }
}

if ( ! function_exists('add_jquery_script')) {
    function add_jquery_script($str_in) {
        $str_out = "<script type=\"text/javascript\">\n$(document).ready(function() {\n".$str_in."\n})</script>";
        return $str_out;
    }
}

//transforms accents into letters, used in slug creation
if ( ! function_exists('replace_accents')) {
    function replace_accents($str) {
        $str = htmlentities($str, ENT_COMPAT, "UTF-8");
        $str = preg_replace("/&([a-zA-Z])(uml|acute|grave|circ|tilde);/", "$1", $str);
        return html_entity_decode($str);
    }
}

//transforms accents into letters, used in slug creation
if ( ! function_exists('cut_text')) {
    function cut_text($str, $max_length, $tail_str = "...") {
        if (strlen($str) > $max_length) {
            $str = substr($str, 0, $max_length).$tail_str;
        }
        return $str;
    }
}

//print info of a certain variable, useful to debug
if ( ! function_exists('debug')) {
    function debug($var) {
        $CI = &get_instance();
        //$CI->load->model("setting_model");
        //$CI->load->model("user_model");
        $debug = $CI->setting_model->get_site_config("debug");

        //is debug active? Is the user admin?
        if (($debug == 1) && ($CI->user_model->has_permission(0, FALSE) )) {
            $out = "<div class=\"debug\">\n";
            $out .= "<h3>Debug</h3>\n";
            $out .= "<pre>\n";
            $out .= htmlspecialchars(print_r($var, TRUE))."\n";
            $out .= "</pre>\n";
            $out .= "</div>\n";
            return $out;
        } else
            return NULL;
    }
}
