
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Map extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('map_model');
        $this->load->helper('html_gen');
    }
    
    public function getmap() {
        $data = $this->input->cookie(array('last_dungeon','last_level'));
        $data['unmapped'] = $this->map_model->get_unmapped($data['last_dungeon'], $data['last_level']);
        $data['map'] = $this->map_model->get_map($data['last_dungeon'], $data['last_level']);
        if ($data['map']) {
            $data['limits'] = $this->map_model->get_map_limits($data['last_dungeon'], $data['last_level']);
        }
        $this->load->view('maps_content',$data);
    }
    
    public function putonmap($y, $x, $id_dngtile) {
        $this->map_model->put_on_map($y, $x, $id_dngtile, 
                    $this->input->cookie('last_dungeon'), 
                    $this->input->cookie('last_level'));
    }
    
    
	public function index()
	{
	    $data['dng_code'] = $this->input->cookie('last_dungeon');
	    $data['dng_description'] = $this->map_model->get_dng_descr($data['dng_code']); 
	    $data['dng_level'] = $this->input->cookie('last_level');

	    //$data['tile_sets'] = $this->map_model->some_method(); 
	    
	    $spec['css']=array('map.css');
	    
		$this->load->view('top_menu',$spec);
		$this->load->view('map_form',$data);
	}
}
    