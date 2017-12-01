<?php
	global $sitepress;
	$the_id = apply_filters('wpml_object_id', $post->ID, get_post_type(), false, $sitepress->get_default_language());
	$images = get_sub_field('is_main_gallery') ? get_slider('main_gallery', $the_id, $row, true) : get_slider('full_slider', $the_id, $row);
	var_dump($images);
?>
