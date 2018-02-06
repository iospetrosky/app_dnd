
    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tileset_model extends CI_Model {

    public function __construct()    {
        $this->load->database();
    }

    public function get_tile_set($set) {
        $images = $this->db->select('tcode,png')
                       ->from('tiles')
                       ->where('tile_set',$set)
                       ->get()->result();
        return $images;
    }
}
    