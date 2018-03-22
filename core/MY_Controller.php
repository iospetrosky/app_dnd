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
            setcookie('iam_callback','/dnd.php',time()+60*5,"/"); // expire when browser closes
            header("Location: /iam.php");
        } else {
            if (isset($this->hdata->iam_callback)) {
                // it's coming from the logon procedure
                setcookie('iam_callback','adios',1,'/'); // so we avoid to call at every loading
                $this->my_model->sync_user($this->hdata->iam_id);
            }
        }



    }
}

        