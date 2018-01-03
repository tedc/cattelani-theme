<?php
	global $sitepress;
	$current_lang = $sitepress->get_current_language();
	$post_type = get_post_type();
	$the_id = $post->ID;
	$sitepress->switch_lang($sitepress->get_default_language()); 
	$args = array(
		'post_type' => $post_type,
		'post__in' => array($the_id),
		'suppress_filters' => false
	);
	$q = new WP_Query($args);
	if($q->have_posts()) :
		var_dump($the_id);
		while($q->have_posts()) : $q->the_post(); 
			$next = get_previous_post();
	if($next) :
	$next_id = (ICL_LANGUAGE_CODE != $sitepress->get_default_language()) ? apply_filters('wpml_object_id', $next->ID, get_post_type(), false, ICL_LANGUAGE_CODE) : $next->ID;
	if($next_id) :
	//var_dump($next_id, $next->ID, get_post_type());
	//echo previous_posts_link(__('Prossimo', 'catellani'));
?>
<a class="next next--grow-lg next--<?php echo $post_type; ?>" next-element href="<?php echo get_permalink($next_id); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink($next_id)); ?>', lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})">
	<span class="next__cover" ng-style="{'background-image':'url(<?php echo get_the_post_thumbnail_url($next_id, 'full'); ?>)'}"></span>
	<span class="next__container next__container--shrink-fw next__container--grow-md">
	<span class="next__label next__label--grow-md-bottom"><?php 
	$prossimo = ($post_type == 'post' || $post_type == 'progetti') ? __('Prossimo', 'catellani') : __('Prossima', 'catellani');
	if($post_type == 'post') {
		$post_name = __('articolo', 'catellani');
	} elseif($post_type == 'lampade') {
		$post_name = __('lampada', 'catellani');
	} elseif($post_type == 'installazioni') {
		$post_name = __('installazione', 'catellani');
	} elseif($post_type == 'progetti') {
		$post_name = __('progetto', 'catellani');
	} 
	echo $prossimo . ' ' .$post_name; ?></span>
	<span class="next__title next__title--medium-alternate"><?php echo get_the_title($next_id); ?>
	<?php echo ($post_type == 'progetti' || $post_type == 'installazioni') ? '<br/><span class="next__city">'.get_field('city', $next_id).'</span>' : '' ; ?>
	</span>
	</span>
</a>
<?php endif; endif; 
		endwhile;
	endif;
	wp_reset_query(); wp_reset_postdata();
	$sitepress->switch_lang($current_lang);
?>