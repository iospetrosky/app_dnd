<?php 
$bu = config_item('base_url'); 
$imp = 'app_dnd/graphics/';
?>

<script type="text/javascript">
var base_url = "<?php echo config_item('base_url') . '/' . config_item('index_page'); ?>"


function load_monster_data(tile_id) {
    if (isNaN(tile_id)) {
        return;
    }
    var params = new Object()
    params['tile_id'] = tile_id
    params['aktion'] = 'LOAD_MONSTER_DATA'
    $.ajax({
        url: base_url + '/ajax/room',
        type: 'POST',
        data: params,
        success: function(data) {
            $('#xMonsters').html(data)
        }
    }) // ajax 
}

function load_tile_picture(tile_id) {
    if (isNaN(tile_id)) {
        return;
    }
    var params = new Object()
    params['tile_id'] = tile_id
    params['aktion'] = 'GET_TILE_PIC'
    $.ajax({
        url: base_url + '/ajax/room',
        type: 'POST',
        data: params,
        success: function(data) {
            var o = JSON.parse(data)
            $("#tile_picture").html(o.png)
        }
    }) // ajax 
}



function load_item_data(tile_id) {
    if (isNaN(tile_id)) {
        return;
    }
    var params = new Object()
    params['tile_id'] = tile_id
    params['aktion'] = 'LOAD_ITEMS_DATA'
    $.ajax({
        url: base_url + '/ajax/room',
        type: 'POST',
        data: params,
        success: function(data) {
            $('#xItems').html(data)
        }
    }) // ajax 
}

function suffer_damage(monster_rowid) {
    var hp = window.prompt('How many hit points?')
    if (hp == null || hp == "") return;
    $.get(base_url + '/ajax/suffer/' + monster_rowid + '/' + hp, 
            function() {
                load_monster_data($("#tile_id").val())
                load_item_data($("#tile_id").val())
            }
    )
}

function delete_item(item_id) {
    $.get(base_url + '/ajax/delete_item/' + item_id,
            function() {
                load_item_data($("#tile_id").val())
            }
    )
}

function roll_dice(mon_id) {
    $.get(base_url + '/ajax/roll_dice/' + mon_id,
            function(jdata) {
                jdata = JSON.parse(jdata)
                $("#"+jdata.element).html(jdata.content)
            }
    )
}

function roll_all() {
    $.get(base_url + '/ajax/roll_all/' + $("#tile_id").val(), 
            function(data) {
                load_monster_data(data)
            }
    )
}
    

function run_local() {
    var k = getCookie('last_tile')
    if (typeof(k) == 'number') {
        $("#tile_id").val(k)
        load_monster_data(k)
        load_tile_picture(k)
        load_item_data(k)
    }


    $("#tile_id").change(function() {
        if (isNaN($("#tile_id").val())) {
            $("#tile_picture").html('<?php  echo img(array("src"=>$imp."hourglass.png")) ;?>')
                $("#tile_id").val($("#tile_id").val().toUpperCase())
                var params = new Object()
                params['aktion'] = 'GET_SINGLE_TILE'
                params['tile_id'] = $("#tile_id").val()
                $.ajax({
                    url: base_url + '/ajax/room',
                    type: 'POST',
                    data: params,
                    success: function(data) {
                        var o = JSON.parse(data)
                        $("#tile_picture").html(o.png)  
                        $("#max_items").val(o.max_items)
                        $("#max_monsters").val(o.max_monsters)
                        $("#xMonsters").html("")
                        $("#xItems").html("")

                    }
                }) // ajax 
        } else {
            setCookie('last_tile',$("#tile_id").val(),10)
            load_monster_data($("#tile_id").val())
            load_tile_picture($("#tile_id").val())
            load_item_data($("#tile_id").val())
            
        }
    }) // tile_id processor   
    
    $("#btn_new_room").mouseup(function() {
        var params = make_param_list(['tile_id','max_monsters','max_items','max_level'])
        if(isNaN(params['tile_id'])) {
            // create a new room
            params['aktion'] = 'MAKE_NEW_ROOM'
            $.ajax({
                url: base_url + '/ajax/room',
                type: 'POST',
                data: params,
                success: function(data) {
                    var o = JSON.parse(data)
                    $("#tile_id").val(o.tile_id)
                    load_monster_data(o.tile_id)
                    load_item_data(o.tile_id)
                }
            }) // ajax 
            
        } else {
            load_monster_data(params['tile_id'])
            load_tile_picture(params['tile_id'])
            load_item_data(params['tile_id'])
        }
    })// btn_room mouse up
    
    $("#btn_add_monsters").click(function() {
        var params = make_param_list(['tile_id','max_monsters','max_level'])
        if(isNaN(params['tile_id'])) return; // only the code of the tile
        params['aktion'] = 'ADD_MONSTERS'
        $.ajax({
            url: base_url + '/ajax/room',
            type: 'POST',
            data: params,
            success: function(data) {
                var o = JSON.parse(data)
                load_monster_data(o.tile_id)
                ShowAlert('Added ' + o.monsters + ' monsters','Success')
            }
        }) // ajax 
    })
    
    $("#rotate_ccw").click(function() {
        var params = make_param_list(['tile_id'])    
        params['aktion'] = 'ROTATE_TILE'
        params['rotation'] = 90
        $.ajax({
            url: base_url + '/ajax/room',
            type: 'POST',
            data: params,
            success: function(jdata) {
                jdata = JSON.parse(jdata)
                if (jdata.result == false) {
                    ShowAlert("This tile can't be rotated","Error")
                } else {
                    load_tile_picture(jdata.tile_id)
                }
            }
        }) // ajax 
        
    })
    $("#rotate_cw").click(function() {
        var params = make_param_list(['tile_id'])    
        params['aktion'] = 'ROTATE_TILE'
        params['rotation'] = -90
        $.ajax({
            url: base_url + '/ajax/room',
            type: 'POST',
            data: params,
            success: function(jdata) {
                jdata = JSON.parse(jdata)
                if (jdata.result == false) {
                    ShowAlert("This tile can't be rotated","Error")
                } else {
                    load_tile_picture(jdata.tile_id)
                }
            }
        }) // ajax 
        
    })
} // run_local    
    
</script>
<h2 id="page_title"><?php echo "$dng_description ($dng_code) - Level: $dng_level" ;  ?></h2>


<div  class="boxed" style="float:left" id="xTile">
    <div style="text-align: center;">
    <?php
        echo img(array(
                    'src'   => $imp . 'rot_ccw.png',
                    'width' => '26',
                    'height'=> '26',
                    'id'    => 'rotate_ccw',
                    'class' => 'clickable',
                    'style' => 'margin-right: 20px'));
        echo img(array(
                    'src'   => $imp . 'rot_cw.png',
                    'width' => '26',
                    'height'=> '26',
                    'class' => 'clickable',
                    'id'    => 'rotate_cw'));
    ?>
    </div>
    <div id="tile_picture" style="text-align: center; margin-top:5px">
        <?php echo img(array(
                'src'   => $imp . 'goblin_no_tiles.png',
                'width' => '224',
                'height'=> '224'
                )
        ); ?>           
    </div>
</div>

<div  class="boxed" style="float:left" id="xCtrlPanel">
    <div id="frmLine001">
        <div class="form_item floating">
            <div class="fixed_width_label">Tile code / ID</div>
            <input class="fixed_w2" type="text" id="tile_id" >
        </div>   
        <div class="form_item">
            <input type="button" value="Create/Load room" id="btn_new_room">
        </div>  
    </div>
    
    <div id="frmLine002">
        <div class="form_item floating">
            <div class="fixed_width_label">Max monsters</div>
            <input class="fixed_w2" type="text" id="max_monsters" value="0">
        </div>
        <div class="form_item floating">
            <div class="fixed_width_label">Max level</div>
            <input class="fixed_w2" type="text" id="max_level" value="1" >
        </div>
        <div class="form_item">
            <input type="button" value="Add monsters" id="btn_add_monsters">
        </div>  
        
    </div>  
    
    <div id="frmLine003">
        <div class="form_item">
            <div class="fixed_width_label">Max items</div>
            <input class="fixed_w2" type="text" id="max_items" value="0">
        </div>   
    </div>
</div>

<div class="boxed scrollable" style="clear:both; float:left" id="xMonsters">Monsters list goes here</div>

<div class="boxed scrollable" style="float:left" id="xItems">Items list goes here</div>
