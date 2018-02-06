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

    last_selected_tile = ''
    setCookie('last_tile','',-10) // dovrebbe scadere
    $("#messages").text('Loading map... wait please');
    $.get(ajax_url + "/getmap/",
        function(data) {
            $("#soft_content").html(data)
            $("#messages").text('Work completed');
        }
    )
}

function view_tile(id) {
    //alert($("[name='sel_function']").val())
    if ($("[name='sel_function']").val() == 'put') {
        setCookie('last_tile',id,10)
        window.location.replace(base_url + "/room")
    }
    if ($("[name='sel_function']").val() == 'del') {
        setCookie('last_tile',id,10)
        $.get(ajax_url + "/remove/" + id).done(load_map())
    }
}

function put_on_map(coords) {
    if (last_selected_tile == "") {
        ShowAlert('Select a tile first','Warning')
        return
    }
    
    var parts = (coords.split('_')) // y , x
    var tile_id = last_selected_tile.split('_')[1]
    var params = ['putonmap', parts[0], parts[1], tile_id]
    //probabilmente obbligatorio perche' load_map non aspetta dati da GET 
    //e quindi viene eseguito al volo o usato come argomento
    //per generare i parametri. Usare .done()
    $("#messages").text('Saving... wait please');
    $.get(ajax_url + "/" + params.join('/')).done(load_map())
}


function run_local() {
    load_map()
} // run_local    

 
</script>


<h2 id="page_title"><?php echo "$dng_description ($dng_code) - Level: $dng_level" ;  ?></h2>
<?php
echo form_dropdown('sel_function',array('put'=>'Put on map','del'=>'Remove from map'),"id=sel_function");
echo span(' --- messages ---', array('id'=>'messages'));
?>
<div id="soft_content"></div>


