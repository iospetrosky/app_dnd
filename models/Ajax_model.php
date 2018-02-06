<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_model extends CI_Model {

    public function __construct()    {
        $this->load->database();
    }

    public function get_single_tile($tile_code, $tile_set) {
        $tt = $this->db->select('png, max_monsters, max_items')
                        ->from('tiles')
                        ->where('tcode',$tile_code)->where('tile_set',$tile_set)
                        ->get();
        if ($tt->num_rows() > 0) {
            // devo restituire quello ruotato di 0 gradi
            $retval = $tt->result()[0];
            $retval->png = img(array(
                'src' => 'app_dnd/graphics/tiles/map_' .  str_replace('.png','_0.png', $tt->result()[0]->png),
                'width' => 224, 'height' => 224));
            echo json_encode($retval);
        } else {
            $retval = new stdClass();
            $retval->png = img(array(
                'src' => 'app_dnd/graphics/goblin_no_tiles.png',
                'width' => 224, 'height' => 224));
            $retval->max_monsters = 0;
            $retval->max_items = 0;
            echo json_encode($retval);
        }
    }
    
    public function get_room_monsters_list($tile_id) {
        $query = $this->db->select('id, monster, bonus_attack, bonus_defense, hit_points, carried_items, dice_rolls')
                            ->from('dngtile_monsters')
                            ->where('id_dngtile',$tile_id)
                            ->get();
        //log_message('debug',$this->db->last_query());
        
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    } // get_monster_data
    
    public function get_room_items_list($tile_id) {
        $query = $this->db->select('id, item')
                          ->from('dngtile_items')
                          ->where('id_dngtile',$tile_id)
                           ->get();
        //log_message('debug',$this->db->last_query());
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }        
    
    public function get_tile_pic($tile_id) {
        $query = $this->db->select('png, rotation')
                          ->from('tiles T')
                          ->join('dng_tiles D','T.tcode = D.tcode')
                          ->where('D.id',$tile_id)
                          ->get();    
        //log_message('debug',$this->db->last_query());  
                  
        $png = $query->result()[0]->png;
        $png = 'map_' . str_replace('.png', '_' . $query->result()[0]->rotation . '.png', $png); 
        
        $png = img(array(
                'src' => 'app_dnd/graphics/tiles/' . $png,
                'width' => 224, 'height' => 224)); 
        echo json_encode(array('png' => $png));
    }
    
    private function get_monsters($max_level) {
        $data = $this->db->select('mname, mlevel, kattack')
                    ->from('monsters')
                    ->where('mlevel <=',$max_level)
                    ->get();
        return $data->result();
    }
    
    private function get_weapons($level) {
        $data = $this->db->select('description, max_bonus')
                            ->from('inventory')
                            ->where('item_type','W')
                            ->where('mnst_level <=', $level)
                            ->get();
        return $data->result();
    }
    
    private function get_armors($level) {
        $data = $this->db->select('description, max_bonus')
                            ->from('inventory')
                            ->where('item_type','A')
                            ->where('mnst_level <=', $level)
                            ->get();
        return $data->result();
    }
    
    private function get_items($level) {
        $data = $this->db->select('description')
                        ->from('inventory')
                        ->where_in('item_type',array('P','S','X'))
                        ->where('mnst_level <=', $level)
                        ->get();
        return $data->result();
    }

    private function get_inventory($level) {
        $data = $this->db->select('description')
                        ->from('inventory')
                        ->where('mnst_level <=', $level)
                        ->get();
        return $data->result();
    }

    private function generate_new_monster_data($max_level) {
        $monsters = $this->get_monsters($max_level);
        $data = array();

        $monster = $monsters[rand(0,count($monsters)-1)];
        $data['monster'] = $monster->mname;
        $data['bonus_attack'] = $monster->mlevel - 1;
        $data['bonus_defense'] = $monster->mlevel - 1;
        $data['hit_points'] = (rand(1,3)+rand(1,3)) * $monster->mlevel;
        
        $data['carried_items'] = array ();
        if ($monster->kattack == 'W') {
            // assign a weapon and adjust the attack bomus
            $weapons = $this->get_weapons($monster->mlevel);
            $wep = $weapons[rand(0,count($weapons)-1)];
            $bc = 0;
            if (rand(1,5) == 1) { // chances the weapon is blessed/cursed
                if (rand(1,3) == 1) {// cursed
                    $bc = rand(-3,-1);
                    $data['carried_items'][]= sprintf("%s %+d",$wep->description, $bc);
                } else { // blessed
                    $bc = rand(1,3);
                    $data['carried_items'][]= sprintf("%s %+d",$wep->description, $bc);
                }
            } else {
                $data['carried_items'][]= $wep->description;
            }
            $data['bonus_attack'] += rand(-($wep->max_bonus/2),$wep->max_bonus) + $bc;
            // assign an armor
            $armors = $this->get_armors($monster->mlevel);
            $arm = $armors[rand(0,count($armors)-1)];
            if ($arm->description != 'no_armor') {
                $bc = 0;
                if (rand(1,5) == 1) { // chances the armour is blessed/cursed
                    if (rand(1,3) == 1) {// cursed
                        $bc = rand(-3,-1);
                        $data['carried_items'][]= sprintf("%s %+d",$arm->description, $bc);
                    } else { // blessed
                        $bc = rand(1,3);
                        $data['carried_items'][]= sprintf("%s %+d",$arm->description, $bc);
                    }
                } else {
                    $data['carried_items'][]= $arm->description;
                }
                $data['bonus_defense'] += rand(-($arm->max_bonus/2),$arm->max_bonus) + $bc;
            }
            
            // maybe the monster is carrying some items?
            if (rand(1,3)==1) {
                //log_message('debug','### Adding items to this monster');
                $items = $this->get_items($monster->mlevel);
                $data['carried_items'][] = $items[rand(0,count($items)-1)]->description;
            }
        }


        if (count($data['carried_items']) > 0) {
            $data['carried_items'] = implode(", ",$data['carried_items']);
        } else {
            unset($data['carried_items']); // get the default from the DB settings
        }
        return $data;
    }

    public function add_monsters_to_room($tile_id, $max_monsters, $max_level) {
        $this->db->trans_start();
        for ($n=0; $n<rand(0,$max_monsters);$n++) {
            //log_message('debug','### Adding a monsters to this room');
            $data = $this->generate_new_monster_data($max_level);
            $data['id_dngtile'] = $tile_id;
            $this->db->insert('dngtile_monsters',$data);
        }
        $this->db->trans_complete();
        echo json_encode(array('tile_id' => $tile_id, 'monsters' => $n));
    }
    
    public function make_new_room($tile_id, $tile_set, $dungeon, $level, $max_monsters, $max_level, $max_items) {
        
        $this->db->trans_start();
        $data = array('dcode' => $dungeon, 'tcode' => $tile_id);
        $this->db->insert('dng_tiles',$data);
        $last_tile_id = $this->db->insert_id();
        
        for ($n=0; $n<rand(0,$max_monsters);$n++) {
            //log_message('debug','### Adding a monsters to this room');
            $data = $this->generate_new_monster_data($max_level);
            $data['id_dngtile'] = $last_tile_id;
            $this->db->insert('dngtile_monsters',$data);
        }
                
        $items = $this->get_inventory(99);
        $data = array('id_dngtile' => $last_tile_id, 'item' => array());
        for ($n=0; $n<rand(0,$max_items);$n++) {
            //log_message('debug','### Adding an item to this room');
            $data['item'][] = $items[rand(0,count($items)-1)]->description;
        } // adding items
        if (count($data['item']) > 0) {
            // aggiungere concetto di (magically) locked
            $data['item'] = 'Box with: ' . implode(", ",$data['item']);
            $this->db->insert('dngtile_items',$data);
        }
        
        $this->db->trans_complete();
        echo json_encode(array('tile_id' => $last_tile_id));    
    
    } // make_new_room
    
    public function rotate_tile($tile_id, $rotation) {
        $query = $this->db->select('id_dngtile as id,rotation')
                          ->from('v_unmapped_tiles')
                          ->where('id_dngtile',$tile_id)
                          ->get();
        //log_message('debug',$this->db->last_query());
        if ($query->num_rows() > 0) {
            // if the tile is already on a map it can't be rotated
            $tile = $query->result()[0];
            $tile->rotation += $rotation;
            if ($tile->rotation < 0) {
                $tile->rotation += 360;
            } elseif ($tile->rotation >= 360) {
                $tile->rotation -= 360;
            }
            $this->db->where('id',$tile->id)->update('dng_tiles',$tile);
            //log_message('debug',$this->db->last_query());
            return true;
        } else {
            return false;
        }
    }
    
    public function monster_suffer_damage($id,$hp) {
        $monster = $this->db->select('hit_points, carried_items, id_dngtile, monster')
                            ->from('dngtile_monsters')
                            ->where('id',$id)
                            ->get()->result()[0];
        
        $monster->hit_points -= $hp;
        $this->db->trans_start();
        if ($monster->hit_points < 1) {
            log_message('debug','#### monster dead');
            $this->db->where('id',$id)->delete('dngtile_monsters');
            if ($monster->carried_items != 'no items carried') {
                $items = explode(',',$monster->carried_items);
                foreach($items as $it) {
                    $data = array('id_dngtile' => $monster->id_dngtile, 'item' => $it);
                    $this->db->insert('dngtile_items',$data);
                    //log_message('debug',$this->db->last_query());
                }
            }
            $data = array('id_dngtile' => $monster->id_dngtile, 'item' => "A {$monster->monster} corpse");
            $this->db->insert('dngtile_items',$data);
        } else {
            $this->db->where('id',$id)->update('dngtile_monsters',$monster);
        }
        $this->db->trans_complete();            
    }

    public function delete_tile_item($id) {
        $this->db->where('id',$id)->delete('dngtile_items');
    }
    
    public function roll_all_dice($tile_id) {
        log_message('debug','### Rolling all dice');
        $monsters = $this->db->select('id')
                             ->from('dngtile_monsters')
                             ->where('id_dngtile',$tile_id)
                             ->get()->result();
        $this->db->trans_start();
        foreach($monsters as $mon) {
            $this->roll_monster_dice($mon->id);
        }
        $this->db->trans_complete();
    }

    public function roll_monster_dice($id) {
        $monster = $this->db->select("D.monster, D.bonus_attack, D.bonus_defense, M.kattack, M.num_attacks") 
                            ->from('dngtile_monsters D')
                            ->join('monsters M', 'D.monster = M.mname')
                            ->where('D.id',$id)
                            ->get()->result()[0];
        $result = new stdClass();
        $result->element = "DR_$id";
        $atts = array();
        $result->content = "I: " . rand(1,10); // initiative
        for ($a=0; $a< $monster->num_attacks; $a++) {
            $atts[] = rand(1,10) + $monster->bonus_attack;
        }
        $result->content .= " A: " . implode(', ',$atts); // attacks
        $result->content .= " D: " . (string)(rand(1,10) + $monster->bonus_defense); // defense
        
        $this->db->set('dice_rolls',$result->content)
                 ->where('id',$id)
                 ->update('dngtile_monsters');
        
        return $result;
    }
}

