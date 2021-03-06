<?php
	global $sitepress;
	global $post;
	$post_type = get_post_type();
	//global $APTO;
	// $args   =   array(
 //                    '_adminsort' =>  array('yes')  
 //                    );
	$term = wp_get_post_terms( $post->ID, 'collezioni' );
	// //create the arguments of a query
	// $query  =   new stdClass();
	// //set the post type
	// $query->query_vars['post_type'] =   'lampade';
	// //set taxonomy
	// $query->tax_query->queries['relation']  =   'AND';
	// $query->tax_query->queries[]    =   array(
	//                                             'taxonomy'  =>   'collezioni',
	//                                             'field'     =>   'term_id',
	//                                             'terms'     =>   array( $term[0]->term_id ),
	//                                             'operator'  =>  'IN'
	//                                             );
	                    
	// $sort_view_id   =   $APTO->functions->query_match_sort_id($query, $args);

	// $sort_view_post     =   get_post($sort_view_id);
	// $sortID             =   $sort_view_post->post_parent;
	// var_dump($sortID);
	// $post_type = get_post_type();
	// $post_type_object = get_post_type_object( $post_type );
	
	//$next = ($post_type == 'post' || $post_type == 'progetti' || $post_type == 'installazioni' ) ? get_previous_post() : get_previous_post( true, null, 'collezioni');

	$next = (get_post_type() == 'lampade' ) ? apto_get_adjacent_post( array('taxonomy' => 'collezioni', 'term_id' => $term[0]->term_id), true) : apto_get_adjacent_post( FALSE, TRUE);

	if($next) :
	$next_id = ($sitepress->get_current_language() != $sitepress->get_default_language()) ? apply_filters('wpml_object_id', $next->ID, get_post_type(), false, ICL_LANGUAGE_CODE) : $next->ID;

	if($next_id) :

	$original_next_id = apply_filters('wpml_object_id', $next_id, get_post_type(), false, $sitepress->get_default_language());
	//var_dump($next_id, $next->ID, get_post_type());
	//echo previous_posts_link(__('Prossimo', 'catellani'));
?>
<a class="next next--grow-lg next--<?php echo $post_type; ?>" next-element href="<?php echo get_permalink($next_id); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink($next_id)); ?>', lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})">
	<span class="next__cover" ng-style="{'background-image':'url(<?php echo get_the_post_thumbnail_url($original_next_id, 'full'); ?>)'}"></span>
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
<?php endif; endif; ?>