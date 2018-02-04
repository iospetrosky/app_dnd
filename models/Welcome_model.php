<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome_model extends CI_Model {

    public function __construct()    {
        $this->load->database();
    }


    public function get_tile_sets() {
        $query = $this->db->get('tile_sets');
        return $query->result();
    }


}