<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>DND Game home</title>

<link rel="stylesheet" href="app_dnd/libraries/main.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="app_dnd/libraries/cookies.js"></script>

<script type="text/javascript">
$(document).ready(run)
function run() {
    $(".navbar-button").mouseenter(function() {
        $(this).addClass('navbar-button-selected')
    })
    
    $(".navbar-button").click(function() {
        var id = ($(this).attr('id'))
        switch(id) {
            case 'btn_home':
                $("#if_content").attr('src','init_form.htm')
                break
            case 'btn_map':
                $("#if_content").attr('src','map.htm')
                break
        }
    })
    
    $(".navbar-button").mouseleave(function() {
        $(this).removeClass('navbar-button-selected')
    })
    
    $('#if_content').on('load', function(){
        this.style.height=this.contentDocument.body.scrollHeight + 20 +'px'
    });

} //run
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

<?php echo APPPATH; ?>