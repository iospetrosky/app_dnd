<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ajax_model');
        $this->load->library('table');
    }

    public function room() {
        switch($this->input->post('aktion')) {
            case 'GET_SINGLE_TILE':
                $this->ajax_model->get_single_tile($this->input->post('tile_id'), get_cookie('last_tileset'));
                return;
            case 'GET_TILE_PIC':
                $this->ajax_model->get_tile_pic($this->input->post('tile_id'));
                return;
            case 'MAKE_NEW_ROOM':
                $this->ajax_model->make_new_room($this->input->post('tile_id'), 
                                                 get_cookie('last_tileset'),
                                                 get_cookie('last_dungeon'),
                                                 get_cookie('last_level'),
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
                        $this->table->add_row($it->item,'buttons');
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
                    $this->table->set_heading('Monster','Hit points','Att. bonus','Def. bonus','Items','Dice rolls','&nbsp;');
                    foreach($monsters as $mon) {
                        $this->table->add_row($mon->monster,
                                              "<div style='text-align:center'>{$mon->hit_points}</div>",
                                              "<div style='text-align:center'>{$mon->bonus_attack}</div>",
                                              "<div style='text-align:center'>{$mon->bonus_defense}</div>",
                                              $mon->carried_items,
                                              $mon->dice_rolls,
                                              'buttons'
                                              );
                    }
                    echo $this->table->generate();
                } else {
                    echo 'There are no monsters on this tile';
                }
                return;
            case 'ROTATE_TILE':
                $this->ajax_model->rotate_tile($this->input->post('tile_id'), $this->input->post('rotation'));
                echo $this->input->post('tile_id');
                return;
        } // switch
        
        echo "Call to unsupported function";
    }

}
    