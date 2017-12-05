<?php
	//global $APTO;
	$term = wp_get_post_terms( $post->ID, 'collezioni' );
	// $args   =   array(
 //                    '_adminsort' =>  array('yes')  
 //                    );

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

	$next = (get_post_type() == 'lampade' ) ? apto_get_adjacent_post( array('taxonomy' => 'collezioni', 'term_id' => $term[0]->term_id), true) : get_previous_post();
	var_dump(get_adjacent_post(false, null, true)->ID);
	if($next) :

	$next_id = id_by_lang($next->ID, get_post_type(), ICL_LANGUAGE_CODE);
	//var_dump($next_id, $next->ID, get_post_type());
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
<?php endif; ?>