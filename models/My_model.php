<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_model extends CI_Model {

    public function __construct()    {
        $this->load->database();
    }

    public function sync_user($uid) {
        $remote_user = $this->db->select("ID,fullname,email")
                        ->from("iam.users")
                        ->where("ID",$uid)
                        ->get()->result()[0]; // it MUST exist!!!

        $this->db->where('ID',$uid)->delete('users');
        $this->db->insert('users',(array)$remote_user);

    }
}
    