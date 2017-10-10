<div class="full-slider full-slider--<?php echo get_post_type(); ?> full-slider--grow-lg full-slider--shrink-fw"<?php scrollmagic('"triggerHook":0.4,"class":"full-slider--active","reverse":false'); ?>>
	<?php 
	$images = get_sub_field('is_main_gallery') ? get_field('main_gallery') : get_sub_field('full_slider');
	$full = true;
include(locate_template( 'builder/commons/gallery.php', false, true )); ?>
</div>