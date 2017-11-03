<?php
	$post_type = get_post_type();
	$post_type_object = get_post_type_object( $post_type );
	$next = ($post_type == 'post' || $post_type == 'progetti' || $post_type == 'installazioni' ) ? get_previous_post() : get_previous_post( true, null, 'collezioni');
	//$term = wp_get_post_terms( $post->ID, 'collezioni' );
	//$next = ($post_type == 'post' || $post_type == 'progetti' || $post_type == 'installazioni' ) ? get_previous_post() : apto_get_adjacent_post( array('taxonomy' => 'collezioni', 'term_id' => $term[0]->term_id), true);
	if($next) :
	$next_id = id_by_lang($next->ID, get_post_type(), ICL_LANGUAGE_CODE);
?>
<a class="next next--grow-lg next--<?php echo $post_type; ?>" next-element href="<?php echo get_permalink($next->ID); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink($next->ID)); ?>', lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})">
	<span class="next__cover" ng-style="{'background-image':'url(<?php echo get_the_post_thumbnail_url($next->ID, 'full'); ?>)'}"></span>
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
	<span class="next__title next__title--medium-alternate"><?php echo get_the_title($next->ID); ?>
	<?php echo ($post_type == 'progetti' || $post_type == 'installazioni') ? '<br/><span class="next__city">'.get_field('city', $next_id).'</span>' : '' ; ?>
	</span>
	</span>
</a>
<?php endif; ?>