<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('my_model');
        $this->load->model('room_model');

        if (!$this->my_model->test_dungeon_owner($this->hdata->iam_id, $this->hdata->last_dungeon)) {
            header('Location: ' . config_item('base_url') . '/' . config_item('index_page'), true);
        }
    }
    
	public function index()
	{

        // load all the data needed in the views in variables to be passed as second parameter
	    $data['dng_code'] = $this->hdata->last_dungeon;
	    $data['dng_description'] = $this->room_model->get_dng_descr($data['dng_code']); 
	    $data['dng_level'] = $this->hdata->last_level;
	    
	    $spec['css']=array('room.css');
	    
		$this->load->view('top_menu',$spec);
		$this->load->view('room_form',$data);
	}
    
    /*
	public function test() {
	    header('Access-Control-Allow-Origin: *');
	    echo "chao";
    }
    */
}

