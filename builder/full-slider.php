<?php
	global $sitepress;
	$the_id = apply_filters('wpml_object_id', $post->ID, get_post_type(), false, $sitepress->get_default_language());
	$images = get_sub_field('is_main_gallery') ? get_slider('main_gallery', $the_id, $row, true) : get_slider('full_slider', $the_id, $row);
	
?>
<?php if($images) : ?>
<div class="full-slider full-slider--<?php echo get_post_type(); ?> full-slider--grow-lg full-slider--shrink-fw"<?php scrollmagic('"triggerHook":0.4,"class":"full-slider--active","reverse":false'); ?>>
	<?php
	$full = true;
include(locate_template( 'builder/commons/gallery.php', false, true )); ?>
</div>
<?php endif; ?>