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
				$ancestor = ($the_index_id) ? '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.get_permalink($the_index_id).'" ui-sref="app.page({slug : \''.basename(get_permalink($the_index_id)).'\', lang : \''.$lang.'\'})"><span itemprop="name">'.get_the_title($the_index_id).'</span></a><meta itemprop="position" content="2" /></span>'.$sep : '';
			else :
				$term = wp_get_post_terms( $post_obj->ID, 'collezioni' )[0];
				$term = get_term(id_by_lang($term->term_id, 'collezioni', $lang));
				$ancestor = ($term) ? '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.get_permalink($the_index_id).'" ui-sref="app.collection({name : \''.basename(get_term_link($term->term_id)).'\', lang : \''.$lang.'\'})"><span itemprop="name">'.$term->name.'</span></a><meta itemprop="position" content="2" /></span>'.$sep : '';
			endif;
		endif;
		$current = ($post_id) ? '<span>'.get_the_title($post_id).'</span>' : '';
	elseif($term_obj) :
		$ancestor = ($term_obj->taxonomy == get_option('glossary-settings')['slug-cat']) ? '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.get_permalink($index['glossary']).'" ui-sref="app.page({slug : \''.basename(get_permalink($index['glossary'])).'\', lang : \''.$lang.'\'})"><span itemprop="name">'.get_the_title(id_by_lang($index['glossary'], 'page', $lang)).'</span></a><meta itemprop="position" content="2" /></span>'.$sep : '';
		$term = get_term(id_by_lang($term_obj->term_id, $term_obj->taxonomy, $lang));
		$current = ($term) ? $ancestor.'<span itemprop="name">'.$term->name.'</span>' : '';
	endif;
	if($current != '') :
		$current = ($ancestor != '') ? '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="item">'.$current.'<meta itemprop="position" content="3" /></span></span>' : '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="item">'.$current.'<meta itemprop="position" content="2" /></span></span>';
  		if($post_obj && !$term_obj) :
			$post_id = id_by_lang($post_obj->ID, $post_type, $lang);
			if($home != $post_id) :
				echo '<span  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.get_permalink($home).'" ui-sref="app.root({lang : \''.$lang.'\'})"><span itemprop="name">Home</span></a><meta itemprop="position" content="1" /></span>'.$sep.$ancestor.$current;
			endif;
		else :
			echo '<a href="'.get_permalink($home).'" ui-sref="app.root({lang : \''.$lang.'\'})">Home</a>'.$sep.$current;
		endif;
	endif;
?>