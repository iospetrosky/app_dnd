<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>DND Game home</title>

<?php
echo link_tag('app_dnd/libraries/main.css');
echo link_tag('app_dnd/libraries/forms.css');
echo link_tag('app_dnd/libraries/modal.css');

if (isset($css)) {
    foreach($css as $c) {
        echo link_tag('app_dnd/libraries/'.$c);
    }
}


?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="<?php echo config_item('base_url'); ?>/app_dnd/libraries/cookies.js"></script>

<script type="text/javascript">

function ShowAlert(atext, atitle = 'Warning', afooter = '') {
    $(".modal-header h2").text(atitle)
    $(".modal-body").html(atext)
    $(".modal-header h3").text(afooter)
    $("#myModal").fadeIn(200)
}

var works = 0

function HourGlass(m) {
    if (m) {
        works = works +1
        $("#WIP").fadeIn(1)
    } else {
        works = works - 1
        if (works <= 0) {
            works = 0
            $("#WIP").fadeOut(1)
        }
    }
}    
    
$(document).ready(function () {
    if (getCookie('last_dungeon') == '' || getCookie('last_level') == '' || getCookie('last_tileset') == 'Select')  {
        if ($("#main_form").length == 0) {
            ShowAlert("Basic parameters of the game are not set. Go back to the main page","Error")
            setTimeout(function() {
                window.location.replace('<?php echo config_item('base_url'); ?>/dnd.php')
            },5000)
        //return
        }

    }


    $(".navbar-button").mouseenter(function() {
        $(this).addClass('navbar-button-selected')
    })
    
    $(".navbar-button").click(function() {
        var id = ($(this).attr('id'))
        var u = "<?php echo config_item('base_url'); ?>"


        switch(id) {
            case 'btn_home':
                window.location.replace(u + '/dnd.php')
                break
            case 'btn_room':
                window.location.replace(u + '/dnd.php/room')
                break
            case 'btn_map':
                window.location.replace(u + '/dnd.php/map')
                break
            case 'btn_tileset':
                window.location.replace(u + '/dnd.php/tileset')
                break
                
        }
    })
    
    $(".navbar-button").mouseleave(function() {
        $(this).removeClass('navbar-button-selected')
    })
    
    $('#if_content').on('load', function(){
        this.style.height=this.contentDocument.body.scrollHeight + 20 +'px'
    });

    
    $(".close, .modal").click(function() {
    	$("#myModal").fadeOut(200)
    })

    
    run_local() // must be defined in the subsequent views

}) 
</script>


</head>

<body>
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>-</h2>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
      <h3>&nbsp;</h3>
    </div>
  </div>
</div>
    
<div class="navbar navbar-fixed_top">
    <div class="navbar-button" id="btn_home">Home</div>
    <div class="navbar-button" id="btn_room">Room</div>
    <div class="navbar-button" id="btn_map">Map</div>
    <div class="navbar-button" id="btn_tileset">Tile set</div>
    <div class="navbar-button">Other</div>
</div>

<?php 
//echo APPPATH; 
?>