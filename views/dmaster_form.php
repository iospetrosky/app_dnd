
<?php
$bu = config_item('base_url') . '/' . config_item('index_page');
$ajax = $bu . "/dmaster/";
?>
<script type='text/javascript'>
var base_url = "<?php echo $bu; ?>"
var ajax_url = "<?php echo $ajax; ?>" 


function run_local() {
    $.get("<?php echo config_item('base_url') . '/iam.php/control/api_gui/' . $user_id; ?>", function (data) {
        if (data != 'ERR') {
            data = JSON.parse(data)
            $("#username").html(data.fullname + ' -- ' + data.username)
            // now get the data about the Dungeon master with the same ID
            //$.get("<?php echo $ajax . "get_dm/" . $user_id ;?>", function(data) {
                $.ajax({
                url: "<?php echo $ajax . "get_dm/" . $user_id ;?>",
                type: 'POST',
                //data: params,
                success: function(data) {
                    if (data != 'ERR') {
                        data = JSON.parse(data)
                        $("#notes").val(data.notes)
                    } else {
                        $("#notes").val('Ufffffff')
                    }
                }
            }) // ajax 
        } else {
            $("#username").html('Error!!!')
        }
    })    
} // run_local    
    
</script>

<h2 id="username">username</h2>
<textarea id="notes" style="width:300px;height:90px"></textarea>