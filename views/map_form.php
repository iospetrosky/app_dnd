<?php
$bu = config_item('base_url') . '/' . config_item('index_page');
$ajax = $bu . "/map";
?>
<script type='text/javascript'>
var base_url = "<?php echo $bu; ?>"
var ajax_url = "<?php echo $ajax; ?>" 

var last_selected_tile = ''

function select_me(item, id) {
    //alert($(item).attr('id'))
    if (last_selected_tile != "") {
        $("#"+last_selected_tile).removeClass('tile_selected').addClass('tile_unselected')
    }
    $(item).removeClass('tile_unselected').addClass('tile_selected')
    last_selected_tile = $(item).attr('id')
    setCookie('last_tile',id,10)
}


function load_map() {
    var params = new Object()

    $.get(ajax_url + "/getmap/",
        function(data) {
            $("#soft_content").html(data)
        }
    )

    last_selected_tile = ''
    setCookie('last_tile','',-10) // dovrebbe scadere
}

function put_on_map(coords) {
    if (last_selected_tile == "") {
        alert('Select a tile first')
        return
    }
    
    var parts = (coords.split('_')) // y , x
    var tile_id = last_selected_tile.split('_')[1]
    var params = ['putonmap', parts[0], parts[1], tile_id]
    $.get(ajax_url + "/" + params.join('/'), load_map())
}


function run_local() {
    load_map()
} // run_local    

 
</script>


<h2 id="page_title"><?php echo "$dng_description ($dng_code) - Level: $dng_level" ;  ?></h2>
<div id="soft_content"></div>

