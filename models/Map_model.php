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



/*
CREATE VIEW v_unmapped_tiles AS
select dt.id as id_dngtile, dt.dcode, dt.tcode, dt.rotation, t.png, dn.id_dngtile as test from dng_tiles dt 
inner join tiles t on dt.tcode = t.tcode
left join dng_maps dn on dt.id=dn.id_dngtile where dn.id_dngtile is null;

create view v_maps as 
select M.id_dngtile, T.png, D.rotation, D.tcode, M.y, M.x, M.dlevel, M.dcode  from dng_maps M 
        inner join dng_tiles D on M.id_dngtile = D.id 
        inner join tiles T on D.tcode = T.tcode ;

create view map_max_min as
select min(x)-1 as min_x, min(y)-1 as min_y, max(x)+1 as max_x, max(y)+1 as max_y , dlevel, dcode
from dng_maps
group by dcode, dlevel;


*/


}
    