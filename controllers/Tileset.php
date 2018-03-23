
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tileset extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('my_model');
        $this->load->model('tileset_model');
        $this->load->helper('html_gen');

        if (!$this->my_model->test_dungeon_owner($this->hdata->iam_id, $this->hdata->last_dungeon)) {
            setcookie('traceinfo',"Redirecting...",time()+60,"/");
            header('Location: /dnd.php', true);
        }
    }
    
	public function index()
	{
	    // load all the data needed in the views in variables to be passed as second parameter
	    $data['images'] = $this->tileset_model->get_tile_set($this->hdata->last_tileset); 
	    
		$this->load->view('top_menu');
		$this->load->view('tileset_form',$data);
	}
}
    