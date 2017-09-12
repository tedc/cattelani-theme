<?php 
$images = get_sub_field('slider');
$full = false;
$row = $row .'_'.$col;
include(locate_template( 'builder/commons/gallery.php', false, true )); ?>