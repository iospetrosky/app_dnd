
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model {

    public function __construct()    {
        $this->load->database();
    }

    public function get_dng_descr($dng) {
        $dd = $this->db->select('dname')
                        ->from('dungeons')
                        ->where('dcode',$dng)
                        ->get();
        if($dd) {
            return $dd->result()[0]->dname;
        } else {
            return 'Unknown dungeon';
        }
    }
}
