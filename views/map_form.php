
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


function run_local() {
    load_map()

/*	
	$("#dMap").on('click','span.empty_tile',function(e) {
		if (last_selected_tile == "") {
		    alert('Select a tile first')
		    return
		}
		
		var parts = (($(this).attr('id')).split('_'))
		var params = new Object() //make_param_list(['tDungeonId','tLevel','tTileId'])
        params['dungeon'] = getCookie('last_dungeon')
        params['level'] = getCookie('last_level')
        params['tile_id'] = last_selected_tile.split('_')[1]
		
        params['y'] = parts[1]
        params['x'] = parts[2]
        params['aktion'] = 'PUT_ON_MAP'
        $.ajax({
        type: "POST",
        url: "ajax/map.php",
        data: params,
        cache: false,
        success: function(data) {
                if (data != "") {
                    load_map()
                } else {
                    $("#dMap").html("Something really bad happened on the server")
                }
            } // function
        }) // ajax
	
	})	
*/          
} // run_local    

 
</script>


<h2 id="page_title"><?php echo "$dng_description ($dng_code) - Level: $dng_level" ;  ?></h2>
<div id="soft_content"></div>

