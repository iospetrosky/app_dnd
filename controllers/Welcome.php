<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('welcome_model');
        $this->load->model('my_model');
    }
    
	public function index()
	{
	    // load all the data needed in the views in variables to be passed as second parameter
	    $data['tile_sets'] = $this->welcome_model->get_tile_sets(); 
	    $spec['css'] = array('init_form.css');
	    // volendo si puo' passare a top_menu l'elenco dei CSS da caricare
		$this->load->view('top_menu',$spec);
		$this->load->view('init_form',$data);
	}
	
	public function newdungeon($dname,$text) {
	    $j = $this->welcome_model->create_new_dungeon($dname,urldecode($text),$this->hdata->iam_id);
	    echo json_encode($j);
    }

    public function tdo($dname) {
        // placeholder for test dungeon owner
        $t = $this->my_model->test_dungeon_owner($this->hdata->iam_id, $dname);
        if ($t) { 
            echo 'OK';
        } else {
            echo 'NO';
        }
    }
    
    
}
