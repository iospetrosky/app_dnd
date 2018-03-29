<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DMaster extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dmaster_model');
    }
    
	public function index()
	{
	    // load all the data needed in the views in variables to be passed as second parameter
	    $data['user_id'] = $this->hdata->iam_id; 
	    
		$this->load->view('top_menu');
        $this->load->view('dmaster_form',$data);
    }
    
    public function get_dm($uid) {
        $n = $this->dmaster_model->get_dungeon_master($uid);
        if ($n) {
            echo json_encode($n);
        } else {
            echo 'ERR';
        }
    }



}
    