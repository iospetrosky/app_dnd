<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>DND Game home</title>
<link rel="icon" href="<?php echo config_item('base_url');?>/app_dnd/favicon.ico" type="image/ico">
<style>
@font-face {
    font-family: "Fancy menu";
    src: url('<?php
        // this must be generated
        echo config_item('base_url') . "/app_dnd/libraries/menu_font.ttf";
    ?>');
}
</style>
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
    $(".modal-footer h3").text(afooter)
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
    //$('#nav2').addClass("out_of_screen")

    $(".close, .modal").click(function() {
        $("#myModal").fadeOut(200)
    })

    if (getCookie('last_dungeon') == '' || getCookie('last_level') == '' || getCookie('last_tileset') == 'Select')  {
        if ($("#main_form").length == 0) {
            ShowAlert("Basic parameters of the game are not set. Go back to the main page","Error")
            setTimeout(function() {
                window.location.replace('<?php echo config_item('base_url'); ?>/dnd.php')
            },5000)
        //return
        }
    }

    var visible_menu = getCookie('visible_menu','none')

    $(".nav_opener").click(function() {
        var id = ($(this).attr('id'))
        switch(id) {
            case 'menu1':
                $("#nav2").animate({opacity:0},1000, function(){
                    $('#nav2').addClass("out_of_screen")
                    $('#nav1').removeClass("out_of_screen")
                    $("#nav1").animate({opacity:1},1000)
                })
                break
            case 'menu2':
                $("#nav1").animate({opacity:0},1000, function() {
                    $('#nav1').addClass("out_of_screen")
                    $('#nav2').removeClass("out_of_screen")
                    $("#nav2").animate({opacity:1},1000)
                })
                break
        }
    })

    $(".menu_button").click(function(){
        var index = $(".menu_button").index(this)
        var u = "<?php echo config_item('base_url'); ?>"

        switch(index){
            case 0:
                window.location.replace(u + '/dnd.php')
                break
            case 1:
                window.location.replace(u + '/dnd.php/room')
                break
            case 2:
                window.location.replace(u + '/dnd.php/map')
                break
            case 3:
                window.location.replace(u + '/dnd.php/tileset')
                break
        }
    })

    $('#if_content').on('load', function(){
        this.style.height = this.contentDocument.body.scrollHeight + 20 + 'px'
    });

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

<!-- new navbar -->
    <div class="nav_bar">
        <div class="nav_block">
            <div class="nav_opener" id="menu1">
                <?php echo img(array(
                    'src' => 'app_dnd/graphics/menu.png',
                    'width' => 45, 'height' => 45,
                    )); ?>
            </div>
            <div class="nav_menu" id="nav1">
                <?php
                    $links = [
                        ['Home','ico_home.png'],
                        ['Room','ico_sword.png'],
                        ['Map','ico_map.png'],
                        ['Tile set','ico_tiles.png'],
                        //['Other...','ico_sword.png'],
                    ];
                    foreach ($links as $lnk) {
                        echo "<div class='menu_button' name='{$lnk[0]}'>";
                        echo img(array(
                            'src' => 'app_dnd/graphics/' . $lnk[1],
                            'width' => 32, 'height' => 32,
                            'style' => 'float: left'
                        ));
                        echo "<div class='button_text'>{$lnk[0]}</div>";
                        echo "</div>";
                    }

                ?>
            </div>
        </div>

        <div class="nav_block">
            <div class="nav_menu out_of_screen" id="nav2">
                <?php
                    $links = [
                        ['Pers. info','ico_sword.png'],
                        ['User info','ico_sword.png'],
                    ];
                    foreach ($links as $lnk) {
                        echo "<div class='menu_button'  name='{$lnk[0]}'>";
                        echo img(array(
                            'src' => 'app_dnd/graphics/' . $lnk[1],
                            'width' => 32, 'height' => 32,
                            'style' => 'float: left'
                        ));
                        echo "<div class='button_text'>{$lnk[0]}</div>";
                        echo "</div>";
                    }

                ?>
            </div>
            <div class="nav_opener" id="menu2"><?php echo img(array(
                    'src' => 'app_dnd/graphics/menu.png',
                    'width' => 45, 'height' => 45,
                    )); ?>
            </div>
        </div>
    </div>

<?php 
?>