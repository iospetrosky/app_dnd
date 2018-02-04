<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('welcome_model');
    }
    
	public function index()
	{
	    // load all the data needed in the views in variables to be passed as second parameter
	    $data['tile_sets'] = $this->welcome_model->get_tile_sets(); 
	    
	    // volendo si puo' passare a top_menu l'elenco dei CSS da caricare
		$this->load->view('top_menu');
		$this->load->view('init_form',$data);
	}
}
