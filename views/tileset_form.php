
<?php
$bu = config_item('base_url') . '/' . config_item('index_page');
$ajax = $bu . "/xxx/";
$imp = 'app_dnd/graphics/tiles/';
?>
<script type='text/javascript'>
var base_url = "<?php echo $bu; ?>"
var ajax_url = "<?php echo $ajax; ?>" 


function run_local() {


            
} // run_local    
    
</script>

<?php

//    print_r($images);
    $x = 0;
    foreach($images as $img) {
        echo img(array(
                    'src'   => $imp . "map_" . str_replace(".png","_0.png",$img->png),
                    'width' => '60',
                    'height'=> '60',
                    'id'    => $img->tcode,
                    'class' => 'clickable',
                    'style' => 'margin-right: 2px; margin-bottom: 2px'));
        $x++;
        if ($x > 12) {
            echo br();
            $x = 0;
        }
    }


?>
    