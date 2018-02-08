<script type="text/javascript">
var base_url = "<?php echo config_item('base_url') . '/' . config_item('index_page'); ?>"

function run_local() {
    $("#frmTileSets").val(getCookie('last_tileset'))
    $("#frmDungeonName").val(getCookie('last_dungeon'))
    $("#frmDungeonLevel").val(getCookie('last_level'))

    $(".autosave").change(function() {
        if ($(this).attr('id')=='frmDungeonName') {
            $(this).val($(this).val().toUpperCase())
        }

        setCookie('last_dungeon',$("#frmDungeonName").val(),10)
        setCookie('last_level',$("#frmDungeonLevel").val(),10)
        setCookie('last_tileset',$("#frmTileSets").val(),10)

    })

    $("#btn_new_dungeon").click(function() {
        var descr = window.prompt("Insert a description for this dungeon")
        if (descr == null || descr == "") { return }
        
        HourGlass(true)
        $.get(encodeURI(base_url + '/Welcome/newdungeon/' + $("#frmDungeonName").val() + '/' + descr),
            function(data) {
                HourGlass(false)
                var o = JSON.parse(data)
                if (o.result == 'OK') {
                    ShowAlert('Dungeon created','Success',data)
                } else {
                    ShowAlert('Something bad happened','Error')
                }
            }
        ) // get
    }) // btn_new_dungeon.click
} // run_local    
    
</script>

<div id="main_form">
    <div class="form_item">
        <div class="fixed_width_label">Dungeon name</div>
        <input id="frmDungeonName" class="fixed_w1 autosave" type='text' >
        <input type="button" value="New dungeon" id="btn_new_dungeon">
    </div>
    <div class="form_item">
        <div class="fixed_width_label">Dungeon level</div><input id="frmDungeonLevel" class="fixed_w1 autosave" type='text' >
    </div>
    <div class="form_item"><div class="fixed_width_label">Tile set</div>
        <select id="frmTileSets" class="fixed_w1 autosave">
            <option selected value="Select">Select one...</option>
            <?php foreach($tile_sets as $set): ?>
            <option value="<?php echo $set->tile_set; ?>"><?php echo $set->tile_set; ?></option>
            <?php endforeach; ?>
        </select></div>   
</div>
