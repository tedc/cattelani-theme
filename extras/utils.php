<?php
	function print_svg($file) {
        $file = str_replace(array('?lang=en','/en'), '', $file);
		$svg = file_get_contents($file); 
        $svg = preg_replace('/(<[^>]+) id=".*?"/', '$1', $svg);
        $svg = preg_replace('/(<[^>]+) data-name=".*?"/', '$1', $svg);
        $svg = preg_replace('/(w*)?<title>[^>]+<\/title>(w*)?/i', '', $svg);
        return $svg;
	}

	function my_excerpt($length, $excerpt = false) { 
		global $post;
		if ($excerpt) return $excerpt;
	    $text = strip_shortcodes( $post->post_content );
	    $text = apply_filters('the_content', $text);
	    $text = str_replace(']]>', ']]&gt;', $text);
	    $text = strip_tags($text);
	    $excerpt_length = apply_filters('excerpt_length', $length);
	    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
	    if ( count($words) > $excerpt_length ) {
	            array_pop($words);
	            $text = implode(' ', $words);
	            $text = $text;
	    } else {
	            $text = implode(' ', $words);
	    }

	    return apply_filters('wp_trim_excerpt', $text);
	}

	function scrollmagic($val) {
		if(!is_handheld()) {
			echo ' ng-sm=\'{'.$val.'}\'';
		}
	}

	function get_dynamic_sidebar($i = 1) {
		$c = '';
		ob_start();
		dynamic_sidebar($i);
		$c = ob_get_clean();
		return $c;
	}

	// function odd_even($f) {
	// 	$field = get_field_object($f); 
	// 	unset($field['choices'][get_field($f)]); 
	// 	return key($field['choices']);
	// }
	function get_social_link($name) {
		acf_set_language_to_default();
		$links = get_field('social', 'options');
		$index = array_search($name, array_column($links, 'nome'));
		$link = $links[$index]['link'];
		echo $link;
		acf_unset_language_to_default();
	}

	function loader($name, $event) { ?>
		<div class="<?php echo $name; ?>__loader" ng-loader="<?php echo $event; ?>" ng-hide="isNotLoading"><div class="<?php echo $name; ?>__spinner"></div></div>
	<?php }

	function id_by_lang($id, $type, $lang) {
		return apply_filters('wpml_object_id', $id, $type, false, $lang);
	}

	
	add_filter( 'get_next_post_where', function( $where, $in_same_term, $excluded_terms, $taxonomy, $post ) {
	    global $wpdb;

	    // Edit this custom post type to your needs
	    $cpt = 'lampade';

	    // Current post type
	    $post_type = get_post_type( $post );

	    // Nothing to do    
	    if( $cpt !== $post_type )
	        return $where;

	    $join = '';
		$where = '';
		
		if ( $in_same_term || ! empty( $excluded_terms ) ) {
			if ( ! empty( $excluded_terms ) && ! is_array( $excluded_terms ) ) {
				// back-compat, $excluded_terms used to be $excluded_terms with IDs separated by " and "
				if ( false !== strpos( $excluded_terms, ' and ' ) ) {
					_deprecated_argument( __FUNCTION__, '3.3.0', sprintf( __( 'Use commas instead of %s to separate excluded terms.' ), "'and'" ) );
					$excluded_terms = explode( ' and ', $excluded_terms );
				} else {
					$excluded_terms = explode( ',', $excluded_terms );
				}

				$excluded_terms = array_map( 'intval', $excluded_terms );
			}

			if ( $in_same_term ) {
				$join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
				$where .= $wpdb->prepare( "AND tt.taxonomy = %s", $taxonomy );

				if ( ! is_object_in_taxonomy( $post->post_type, $taxonomy ) )
					return '';
				$term_array = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );

				// Remove any exclusions from the term array to include.
				$term_array = array_diff( $term_array, (array) $excluded_terms );
				$term_array = array_map( 'intval', $term_array );

				if ( ! $term_array || is_wp_error( $term_array ) )
					return '';

				$where .= " AND tt.term_id IN (" . implode( ',', $term_array ) . ")";
			}

			/**
			 * Filters the IDs of terms excluded from adjacent post queries.
			 *
			 * The dynamic portion of the hook name, `$adjacent`, refers to the type
			 * of adjacency, 'next' or 'previous'.
			 *
			 * @since 4.4.0
			 *
			 * @param string $excluded_terms Array of excluded term IDs.
			 */
			//$excluded_terms = add_filter( "get_next_post_excluded_terms", function($excluded_terms){ return $excluded_terms;} );

			if ( ! empty( $excluded_terms ) ) {
				$where .= " AND p.ID NOT IN ( SELECT tr.object_id FROM $wpdb->term_relationships tr LEFT JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) WHERE tt.term_id IN (" . implode( ',', array_map( 'intval', $excluded_terms ) ) . ') )';
			}
		}

	    // Next CPT order by last word in title
	    add_filter( 'get_next_post_sort', function( $orderby ) 
	    {
	        return "ORDER BY p.post_title ASC LIMIT 1";
	    } );

	    $where .= $wpdb->prepare( "WHERE p.post_title > %s AND p.post_type = %s AND ( p.post_status = 'publish' OR p.post_status = 'private' )", $post->post_title, $post->post_type )

	    // Modify Next WHERE part
	    return $where;    

	}, 10, 5 );
