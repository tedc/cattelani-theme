<?php 
	global $sitepress;
	$countries = array();
	$cities = array();
	$regions = array();
	$stores = array();
	foreach (get_terms(array('hide_empty' => 0, 'orderby' => 'name', 'taxonomy'=> 'wpsl_store_category')) as $c) {
		array_push($stores, array('name' => $c->name, 'id' => $c->term_id, 'language_ids' => add_store_language_ids($c->term_id, 'wpsl_store_category')));
	}
	foreach (get_terms(array('hide_empty' => 0, 'orderby' => 'name', 'taxonomy'=> 'countries')) as $cr) {
		array_push($countries, array('name' => $cr->name, 'id' => $cr->term_id, 'language_ids' => add_store_language_ids($cr->term_id, 'countries')));
	}
	foreach (get_terms(array('hide_empty' => 0, 'orderby' => 'name', 'taxonomy'=> 'cities')) as $cy) {
		$original_city_id = apply_filters('wpml_object_id', $cy->term_id, 'cities', false, $sitepress->get_default_language());
		$country_ref = get_field('country_ref', 'cities_'.$original_city_id);
		$region_ref = get_field('region_ref', 'cities_'.$cy->term_id);
		array_push($cities, array('name' => $cy->name, 'id' => $cy->term_id, 'language_ids' => add_store_language_ids($cy->term_id, 'cities'), 'country_ref' => $country_ref, 'region_ref' => $region_ref));
	}
	foreach (get_terms(array('hide_empty' => 0, 'orderby' => 'name', 'taxonomy'=> 'regioni')) as $r) {
		$original_region_id = apply_filters('wpml_object_id', $r->term_id, 'regioni', false, $sitepress->get_default_language());
		$country_ref = get_field('country_ref', 'regioni_'.$original_region_id);
		array_push($regions, array('name' => $r->name, 'id' => $r->term_id, 'language_ids' => add_store_language_ids($r->term_id, 'regioni'), 'country_ref' => $country_ref));
	}
	$store_terms = '';
	if(get_sub_field('store_new')) {
		$store_terms = ' terms="{';
		if(!empty($countries)) {
			$store_terms .= 'countries:'.htmlspecialchars( wp_json_encode( $countries ) ) .',';
		}
		if(!empty($cities)) {
			$store_terms .= 'cities:'.htmlspecialchars( wp_json_encode( $cities ) ).',';
		}
		if(!empty($regions)) {
			$store_terms .= 'regions:'.htmlspecialchars( wp_json_encode( $regions ) ).',';
		}
		if(!empty($stores)) {
			$store_terms .= 'stores:'.htmlspecialchars( wp_json_encode( $stores ) ).',';
		}
		$store_terms .= '}"';
	}
?>

<ng-store<?php echo (get_sub_field('store_new')) ? '-new' : ''; ?> current-lang="<?php echo ICL_LANGUAGE_CODE; ?>" default-lang="<?php global $sitepress; echo $sitepress->get_default_language(); ?>"<?php echo $store_terms; ?>></ng-store>