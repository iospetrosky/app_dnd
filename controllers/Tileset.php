
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tileset extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tileset_model');
        $this->load->helper('html_gen');
    }
    
	public function index()
	{
	    // load all the data needed in the views in variables to be passed as second parameter
	    $data['images'] = $this->tileset_model->get_tile_set($this->hdata->last_tileset); 
	    
		$this->load->view('top_menu');
		$this->load->view('tileset_form',$data);
	}
}
    