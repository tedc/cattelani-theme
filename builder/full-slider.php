<?php
	global $sitepress;
	$the_id = apply_filters('wpml_object_id', $post->ID, get_post_type(), false, $sitepress->get_default_language());
?>
<div class="full-slider full-slider--<?php echo get_post_type(); ?> full-slider--grow-lg full-slider--shrink-fw"<?php scrollmagic('"triggerHook":0.4,"class":"full-slider--active","reverse":false'); ?>>
	<?php
	$images = get_sub_field('is_main_gallery') ? get_slider('main_gallery', $the_id, $row, true) : get_slider('full_slider', $the_id, $row);
	$full = true;
include(locate_template( 'builder/commons/gallery.php', false, true )); ?>
</div>