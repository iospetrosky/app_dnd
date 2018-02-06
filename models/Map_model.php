<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Map_model extends CI_Model {

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

    public function get_unmapped($dungeon, $level) {
        $query = $this->db->select('*')
                      ->from('v_unmapped_tiles')
                      ->where('dcode',$dungeon)
                      ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    public function get_map($dungeon, $level) {
        $query = $this->db->select('*')
                      ->from('v_maps')
                      ->where('dcode',$dungeon)
                      ->where('dlevel',$level)
                      ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_map_limits($dungeon, $level) {
        $query = $this->db->select('min_x, min_y, max_x, max_y')
                      ->from('v_map_max_min')
                      ->where('dcode',$dungeon)
                      ->where('dlevel',$level)
                      ->get();
        if ($query->num_rows() > 0) {
            return $query->result()[0];
        } else {
            return false;
        }
    }
    
    public function put_on_map($y, $x, $id_dngtile, $dcode, $dlevel) {
        $this->db->set('y',$y);
        $this->db->set('x',$x);
        $this->db->set('id_dngtile',$id_dngtile);
        $this->db->set('dcode',$dcode);
        $this->db->set('dlevel',$dlevel);
        $this->db->insert('dng_maps');
    }
        
    public function remove_from_map ($tile) {
        $this->db->where('id_dngtile', $tile)->delete('dng_maps');
    }

}
    