<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>DND Game home</title>

<?php
echo link_tag('app_dnd/libraries/main.css');
echo link_tag('app_dnd/libraries/forms.css');

if (isset($css)) {
    foreach($css as $c) {
        echo link_tag('app_dnd/libraries/'.$c);
    }
}


?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="<?php echo config_item('base_url'); ?>/app_dnd/libraries/cookies.js"></script>

<script type="text/javascript">
$(document).ready(function () {

        $(".navbar-button").mouseenter(function() {
            $(this).addClass('navbar-button-selected')
        })
        
        $(".navbar-button").click(function() {
            var id = ($(this).attr('id'))
            $u = "<?php echo config_item('base_url'); ?>"
            switch(id) {
                case 'btn_home':
                    window.location.replace($u + '/dnd.php')
                    break
                case 'btn_room':
                    window.location.replace($u + '/dnd.php/room')
                    break
            }
        })
        
        $(".navbar-button").mouseleave(function() {
            $(this).removeClass('navbar-button-selected')
        })
        
        $('#if_content').on('load', function(){
            this.style.height=this.contentDocument.body.scrollHeight + 20 +'px'
        });
    
        run_local(); // must be defined in the subsequent views
    
    }) 
</script>


</head>

<body>
<div class="navbar navbar-fixed_top">
    <div class="navbar-button" id="btn_home">Home</div>
    <div class="navbar-button" id="btn_map">Map</div>
    <div class="navbar-button" id="btn_room">Room</div>
    <div class="navbar-button" id="btn_tileset">Tile set</div>
    <div class="navbar-button">Other</div>
</div>

<?php 
//echo APPPATH; 
?>