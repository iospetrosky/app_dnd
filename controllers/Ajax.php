<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ajax_model');
        $this->load->library('table');
        $this->load->helper('html_gen');
    }

    public function suffer($id, $hp) {
        $this->ajax_model->monster_suffer_damage($id,$hp);
    }
    
    public function delete_item($id) {
        $this->ajax_model->delete_tile_item($id);
    }

    public function roll_dice($id) {
        $result = $this->ajax_model->roll_monster_dice($id);
        echo json_encode($result);
    }
    
    public function roll_all($tile_id) {
        $this->ajax_model->roll_all_dice($tile_id);
        echo $tile_id;
    }

    public function room() {
        switch($this->input->post('aktion')) {
            case 'GET_SINGLE_TILE':
                $this->ajax_model->get_single_tile($this->input->post('tile_id'), get_cookie('last_tileset'));
                return;
            case 'GET_TILE_PIC':
                $this->ajax_model->get_tile_pic($this->input->post('tile_id'));
                return;
            case 'ADD_MONSTERS':
                $this->ajax_model->add_monsters_to_room($this->input->post('tile_id'),
                                                        $this->input->post('min_monsters'),
                                                        $this->input->post('max_monsters'),
                                                        $this->input->post('max_level'));
                return;
            case 'MAKE_NEW_ROOM':
                $this->ajax_model->make_new_room($this->input->post('tile_id'), 
                                                 get_cookie('last_tileset'),
                                                 get_cookie('last_dungeon'),
                                                 get_cookie('last_level'),
                                                 $this->input->post('min_monsters'),
                                                 $this->input->post('max_monsters'),
                                                 $this->input->post('max_level'),
                                                 $this->input->post('max_items')
                                                 );
                return;
            case 'LOAD_ITEMS_DATA':
                $items = $this->ajax_model->get_room_items_list($this->input->post('tile_id'));
                if ($items) {
                    $this->table->set_template(array(
                        'table_open' => '<table border=0 cellpadding=2 cellspacing=0 width="100%">',
                        'heading_cell_start' => '<th class=s_table_cell_head>',
                        'cell_start' => '<td class=s_table_cell_odd>',
                        'cell_alt_start' => '<td class=s_table_cell_even>',
                    ));
                    $this->table->set_heading('Item','&nbsp');
                    foreach($items as $it) {
                        $this->table->add_row($it->item,
                                        span("[X]",array("class" => "clickable",
                                                         "onclick" => "delete_item({$it->id})"
                                        ))
                        );
                    }
                    echo $this->table->generate();
                } else {
                    echo 'There are no items on this tile';
                }
                return;   
            case 'LOAD_MONSTER_DATA':
                $monsters = $this->ajax_model->get_room_monsters_list($this->input->post('tile_id'));
                if ($monsters) {
                    $this->table->set_template(array(
                        'table_open' => '<table border=0 cellpadding=2 cellspacing=0 width="100%">',
                        'heading_cell_start' => '<th class=s_table_cell_head>',
                        'cell_start' => '<td class=s_table_cell_odd>',
                        'cell_alt_start' => '<td class=s_table_cell_even>',
                    ));
                    $x = span('[R]',array("onclick" => "roll_all()", "class" => "clickable", "style" => "margin-left:4px"));
                    
                    $this->table->set_heading('Monster','Hit<br/>points','Att.<br/>bonus','Def.<br/>bonus','Items',"Dice rolls $x",'&nbsp;');
                    foreach($monsters as $mon) {
                        $this->table->add_row($mon->monster,
                                                  div($mon->hit_points, array('style' => array('text-align:center'))),
                                                  div($mon->bonus_attack, array('style' => 'text-align:center')),
                                                  div($mon->bonus_defense, array('style' => 'text-align:center')),
                                                  div($mon->carried_items, array('style' => 'width:120px')),
                                                  div($mon->dice_rolls, array('id' => "DR_{$mon->id}",
                                                                              'style' => array("float:left","margin-right:4px"))) .
                                                  div('[R]', array("class" => "clickable","onclick" => "roll_dice({$mon->id})")) ,
                                                  span('[K]', array("class" => "clickable",
                                                                "onclick" => "suffer_damage({$mon->id})"
                                                  ))
                                              );
                    }
                    //log_message('debug',site_url('/ajax/suffer/12'));
                    
                    echo $this->table->generate();
                } else {
                    echo 'There are no monsters on this tile';
                }
                return;
            case 'ROTATE_TILE':
                $result = $this->ajax_model->rotate_tile($this->input->post('tile_id'), $this->input->post('rotation'));
                echo json_encode(array("tile_id" => $this->input->post('tile_id'), "result" => $result));
                return;
        } // switch
        
        echo "Call to unsupported function";
    }

}
    