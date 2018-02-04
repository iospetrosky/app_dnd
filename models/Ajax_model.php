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


    
    public function make_new_room($tile_id, $tile_set, $dungeon, $level, $max_monsters, $max_level, $max_items) {
        $monsters = $this->get_monsters($max_level);
        
        $this->db->trans_start();
        $data = array('dcode' => $dungeon, 'tcode' => $tile_id);
        $this->db->insert('dng_tiles',$data);
        $last_tile_id = $this->db->insert_id();
        
        for ($n=0; $n<rand(0,$max_monsters);$n++) {
            log_message('debug','### Adding a monsters to this room');
            $data = array('id_dngtile' => $last_tile_id);
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
                $data['carried_items'][]= $wep->description;
                // va inserito il concetto di arma magica che cambia il livello di bonus totale
                $data['bonus_attack'] += rand(-($wep->max_bonus/2),$wep->max_bonus);
                // assign an armor
                $armors = $this->get_armors($monster->mlevel);
                $arm = $armors[rand(0,count($armors)-1)];
                
                // stesso discorso del magico per l'armatura
                if ($arm->description != 'no_armor') {
                    $data['carried_items'][] = $arm->description;
                    $data['bonus_defense'] += rand(-($arm->max_bonus/2),$arm->max_bonus);
                }
                
                // maybe the monster is carrying some items?
                if (rand(1,3)==1) {
                    log_message('debug','### Adding items to this monster');
                    $items = $this->get_items($monster->mlevel);
                    $data['carried_items'][] = $items[rand(0,count($items)-1)]->description;
                }
            }


            if (count($data['carried_items']) > 0) {
                $data['carried_items'] = implode(", ",$data['carried_items']);
            } else {
                unset($data['carried_items']); // get the default from the DB settings
            }
            $this->db->insert('dngtile_monsters',$data);
            unset($data);
        }
        
        $items = $this->get_inventory(99);
        $data = array('id_dngtile' => $last_tile_id, 'item' => array());
        for ($n=0; $n<rand(0,$max_items);$n++) {
            log_message('debug','### Adding an item to this room');
            $data['item'][] = $items[rand(0,count($items)-1)]->description;
        } // adding items
        if (count($data['item']) > 0) {
            $data['item'] = 'Box with: ' . implode(", ",$data['item']);
            $this->db->insert('dngtile_items',$data);
        }
        
        $this->db->trans_complete();
        echo json_encode(array('tile_id' => $last_tile_id));    
    
    } // make_new_room
    
    public function rotate_tile($tile_id, $rotation) {
        $query = $this->db->select('id,rotation')
                          ->from('dng_tiles')
                          ->where('id',$tile_id)
                          ->get();
        //log_message('debug',$this->db->last_query());
        if ($query->num_rows() > 0) {
            $tile = $query->result()[0];
            $tile->rotation += $rotation;
            if ($tile->rotation < 0) {
                $tile->rotation += 360;
            } elseif ($tile->rotation >= 360) {
                $tile->rotation -= 360;
            }
            $this->db->where('id',$tile->id)->update('dng_tiles',$tile);
            //log_message('debug',$this->db->last_query());
        }
    }
}
