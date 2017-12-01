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
	$next = (get_post_type() == 'post' || get_post_type() == 'progetti' || get_post_type() == 'installazioni' ) ? get_previous_post() : apto_get_adjacent_post( array('taxonomy' => 'collezioni', 'term_id' => $term[0]->term_id), true);
	if($next) :
	$next_id = id_by_lang($next->ID, get_post_type(), ICL_LANGUAGE_CODE);
?>

<?php endif; ?>