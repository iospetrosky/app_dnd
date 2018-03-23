<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_model extends CI_Model {

    public function __construct()    {
        $this->load->database();
    }

    public function sync_user($uid) {
        $external_db_table = "iam.users"; // lenovo and PI
        //$external_db_table = "Sql1185872_1.users"; // Aruba
        $remote_user = $this->db->select("ID,fullname,email")
                        ->from($external_db_table)
                        ->where("ID",$uid)
                        ->get()->result()[0]; // it MUST exist!!!

        $this->db->where('ID',$uid)->delete('users');
        $this->db->insert('users',(array)$remote_user);
    }

    public function test_dungeon_owner($uid, $dung) {
        $test_id = $this->db->select('uid')
                ->from('dungeons')->where('dcode',$dung)
                ->get()->result()[0]; // it must exist!!
        //setcookie('traceinfo',"Test dungeon $uid - $dung - {$test_id->uid}",time()+60,"/");
        if ($test_id->uid == $uid) {
            return true;
        }
        return false;
    }

}
    