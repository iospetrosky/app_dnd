<div id="unmapped_tiles">
<?php
if ($unmapped) {
    foreach($unmapped as $tile) {
        echo img(array(
            'src' => 'app_dnd/graphics/tiles/map_' .  str_replace('.png',"_{$tile->rotation}.png", $tile->png),
            'width' => 60, 'height' => 60,
            'class' => 'clickable tile_unselected',
            'ID' => "unmap_{$tile->id_dngtile}",
            'onclick' => "select_me(this, {$tile->id_dngtile})")
            );
    }
}
?>
</div>


<div id="dMap">
<?php
if ($map) {
    /*
    print_r($map);
    echo hr();
    print_r($limits);
    echo hr();
    */
    
    //creo la griglia vuota
    $y = $limits->min_y;
    $lines = array();
    while ($y <= $limits->max_y) {
        $lines[$y] = array();
        $x = $limits->min_x;
        while ($x <= $limits->max_x) {
            $lines[$y][$x] = "{$y}_{$x}";
            $x++;
        }
        $y++;
    }
    //adesso metto i valori nelle celle della mia matrice
    //creo un oggetto per ogni cella, carico i valori che mi servono e lo metto nella posizione
    //indicata nella matrice
    foreach($map as $tile) {
        $lines[$tile->y][$tile->x] = $tile;
    }
    //disegno la mappa
    $o = "<table cellpadding=0 cellspacing=0 border=0>";
    $r = $limits->min_y;
    while ($r <= $limits->max_y):
        $o .= "<tr>";
        $c = $limits->min_x;
        while ($c <= $limits->max_x):    
            if ($lines[$r][$c] instanceof stdClass):
                $tile = $lines[$r][$c];
                $o .= "<td>";
                $o .= img(array(
                        'src' => 'app_dnd/graphics/tiles/map_' .  str_replace('.png',"_{$tile->rotation}.png", $tile->png),
                        'width' => 60, 'height' => 60,
                        'class' => 'clickable full_tile',
                        'ID' => "tile_{$tile->id_dngtile}",
                        'onclick' => "select_me(this, {$tile->id_dngtile})")
                        );
                $o .= "</td>";
            else:
                #it's not a Tile so it's a string already formatted with the coords of the cell on the map
                $o .= "<td width=60 height=60 align=center><span class='clickable empty_tile' id='put_{$lines[$r][$c]}'>put<br/>here</span></td>";
            endif;
            $c++;
        endwhile;
        $o .= "</tr>";
        $r++;
    endwhile;
    
    $o .= "</table>";
    echo $o;
}
?>
</div>    