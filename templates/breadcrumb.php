<?php
	$lang = ICL_LANGUAGE_CODE;
	$post_type = get_post_type();
	$home = id_by_lang(get_option('page_on_front'), 'page', $lang);
	acf_set_language_to_default();
	$index = array(
		'post' => get_field('magazine_index', 'options'),
		'progetti' => get_field('projects_index', 'options'),
		'installazioni' => get_field('installations_index', 'options'),
		'glossary' => get_field('glossary_index', 'options')
	);
	$sep = ' '.get_field('separator', 'options').' ';
	acf_unset_language_to_default();
	$ancestor = '';
	$current = '';
	if($post_obj && !$term_obj) :
		$post_id = id_by_lang($post_obj->ID, $post_type, $lang);
		if($post_type != 'page') :
			if($post_type != 'lampade') :
				$the_index_id = id_by_lang($index[$post_type], 'page', $lang);
				$ancestor = ($the_index_id) ? '<a ui-sref="app.page({slug : \''.basename(get_permalink($the_index_id)).'\', lang : \''.$lang.'\'})">'.get_the_title($the_index_id).'</a>'.$sep : '';
			else :
				$term = wp_get_post_terms( $post_obj->ID, 'collezioni' )[0];
				$term = get_term(id_by_lang($term->term_id, 'collezioni', $lang));
				$ancestor = ($term) ? '<a href="" ui-sref="app.collection({name : \''.basename(get_term_link($term->term_id)).'\', lang : \''.$lang.'\'})">'.$term->name.'</a>'.$sep : '';
			endif;
		endif;
		$current = ($post_id) ? '<span>'.get_the_title($post_id).'</span>' : '';
	elseif($term_obj) :
		$ancestor = ($term_obj->taxonomy == get_option('glossary-settings')['slug-cat']) ? '<a ui-sref="app.page({slug : \''.basename(get_permalink($index['glossary'])).'\', lang : \''.$lang.'\'})">'.get_the_title(id_by_lang($index['glossary'], 'page', $lang)).'</a>'.$sep : '';
		$term = get_term(id_by_lang($term_obj->term_id, $term_obj->taxonomy, $lang));
		$current = ($term) ? $ancestor.'<span>'.$term->name.'</span>' : '';
	endif;
	if($current != '') :
		if($post_obj && !$term_obj) :
			$post_id = id_by_lang($post_obj->ID, $post_type, $lang);
			if($home != $post_id) :
				echo '<a href="'.get_permalink($home).'" ui-sref="app.root({lang : \''.$lang.'\'})">Home</a>'.$sep.$ancestor.$current;
			endif;
		else :
			echo '<a href="'.get_permalink($home).'" ui-sref="app.root({lang : \''.$lang.'\'})">Home</a>'.$sep.$current;
		endif;
	endif;
?>