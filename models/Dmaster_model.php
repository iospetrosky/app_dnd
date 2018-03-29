<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DMaster_model extends CI_Model {

    public function __construct()    {
        $this->load->database();
    }

    public function some_method() {
        //$query = $this->db->get('tile_sets');
        //return $query->result();
    }

    public function get_dungeon_master($uid) {
        $data = $this->db->select('ID, notes')
                            ->from('dng_masters')
                            ->where('ID',$uid)
                            ->get()->result();
        if (is_array($data) && count($data) > 0) {
            return $data[0];
        } else {
            // create a default DM
            $data = (object) [
                'ID' => $uid,
                'notes' => "Nothing yet"
            ];
            $this->db->insert("dng_masters", $data);
            if ($this->db->affected_rows() != 1) {
                return false;
            } else {
                return $data;
            }
        }
        
    }
}
    
