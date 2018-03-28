<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HeaderData {
    public function __construct() {
        if (isset($_GET)) {
            foreach($_GET as $k => $v) {
                $this->$k = $v;
            }
        }
        if (isset($_POST)) {
            foreach($_POST as $k => $v) {
                $this->$k = $v;
            }
        }
        if (isset($_COOKIE)) {
            foreach($_COOKIE as $k => $v) {
                $this->$k = $v;
            }
        }
    }
}


class MY_Controller extends CI_Controller {
    protected $hdata;
    
    public function __construct() {
        parent::__construct();
        $this->hdata = new HeaderData();
        $this->load->model('my_model');

        if (!isset($this->hdata->iam_id)) {
            setcookie('iam_callback',config_item('base_url') . '/dnd.php',time()+60*5,"/"); // expire when browser closes
            header("Location: " . config_item('base_url') . "/iam.php");
        } 
    }
}

        