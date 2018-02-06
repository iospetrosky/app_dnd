#!/usr/bin/python
import subprocess as sp
import os.path as path

xclass = "Tileset"

f = "controllers/{}.php".format(xclass)
if path.isfile(f):
    print ("{} already exist!!!".format(f))
else:
    file = open(f ,"w")
    testo = ("""
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class XXX extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('xxx_model');
    }
    
	public function index()
	{
	    // load all the data needed in the views in variables to be passed as second parameter
	    $data['tile_sets'] = $this->xxx_model->some_method(); 
	    
		$this->load->view('top_menu');
		$this->load->view('xxx_form',$data);
	}
}
    """)
    
    testo = testo.replace('XXX',xclass)
    testo = testo.replace('xxx',xclass.lower())

    file.write(testo)
    file.close()
    sp.call(['chmod','0666',"controllers/{}.php".format(xclass)])
    
    

f = "models/{}_model.php".format(xclass)
if path.isfile(f):
    print ("{} already exist!!!".format(f))
else:
    file = open(f,"w")
    testo = ("""
    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class XXX_model extends CI_Model {

    public function __construct()    {
        $this->load->database();
    }

    public function some_method() {
        //$query = $this->db->get('tile_sets');
        //return $query->result();
    }
}
    """)
    
    testo = testo.replace('XXX',xclass)
    file.write(testo)
    file.close()
    sp.call(['chmod','0666',"models/{}_model.php".format(xclass)])


f = "views/{}_form.php".format(xclass.lower())
if path.isfile(f):
    print ("{} already exist!!!".format(f))
else:
    file = open(f,"w")
    testo = ("""
<?php
$bu = config_item('base_url') . '/' . config_item('index_page');
$ajax = $bu . "/xxx/";
?>
<script type='text/javascript'>
var base_url = "<?php echo $bu; ?>"
var ajax_url = "<?php echo $ajax; ?>" 


function run_local() {


            
} // run_local    
    
</script>
    """)
    testo = testo.replace('XXX',xclass.lower())
    file.write(testo)
    file.close()
    sp.call(['chmod','0666',"views/{}_form.php".format(xclass.lower())])



