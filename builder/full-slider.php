<div class="full-slider full-slider--<?php echo get_post_type(); ?> full-slider--grow-lg full-slider--shrink-fw">
	<?php 
	$images = get_sub_field('is_main_gallery') ? get_field('main_gallery') : get_sub_field('full_slider');
	$full = true;
include(locate_template( 'builder/commons/gallery.php', false, true )); ?>
</div>