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

    public function create_new_dungeon($dcode, $dtext, $uid) {
        $ret = new stdClass();
        $this->db->set('dcode',$dcode)
                 ->set('dname',$dtext)
                 ->set('uid',$uid)
                 ->insert('dungeons');
        if ($this->db->affected_rows() == 1) {
            $ret->id = $this->db->insert_id();
            $ret->result = 'OK';
        } else {
            $ret->result = 'ERR';
        }
        
        return $ret;
    }

}