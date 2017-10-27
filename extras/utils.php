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
		<div class="<?php echo $name; ?>__loader" ng-loader="<?php echo $event; ?>" ng-if="!isNotLoading"><div class="<?php echo $name; ?>__spinner"></div></div>
	<?php }

	function id_by_lang($id, $type, $lang) {
		return apply_filters('wpml_object_id', $id, $type, false, $lang);
	}

	
	function get_slider($field, $id, $row, $main = false) {
		if($main) {
			if(get_field($field)) {
				$slider = get_field($field);
			} else {
				$slider = get_field($field, $id);
			}	
		} else {
			if(get_sub_field($field)) {
				$slider = get_sub_field($field);
			} else {
				$slider = get_field('layout', $id)[$row][$field];
			}
		}
		return $slider;
	}

	function get_lamps_order($lampada) {
		$collection = wp_get_post_terms( $lampada, 'collezioni' );
		$collezioni = new WP_Query(
			array(
				'post_type' => 'lampade',
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'sort_column' => 'menu_order',
				'tax_query' => array(
					array(
						'taxonomy' => 'collezioni',
						'field' => 'term_id',
						'terms' => array($the_id)
					)
				)
			)
		);
		$class_lampada = count($lampade) >= 3 ? 3 : count($lampade);
	    $class_lampada = 12 / $class_lampada;
	    $index = 0;
	    $i = 0;
	    $count = $collezioni->found_posts - 1;
	    while($collezioni->have_posts()) : $collezioni->the_post();
			if($post->ID == $lampda){
			    $index = $i;
			    break;
			}
			$i++;
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
		$data = ' data-item-background="'.$cover.'" data-item-size="'.$class_lampada.'" data-item-total="'.$count.'" data-carousel-item="'.$index.'" data-item-slug="'. basename(get_permalink($lampada)).'" light-collection="'.$collection[0]->term_id.'"';
		return $count;
	}


	// function my_next_post_where() {

	// 	global $post, $wpdb;

	// 	$cpt = 'lampade';

	//     // Current post type
	//     $post_type = get_post_type( $post );

	//     // Nothing to do    
	//     if( $cpt !== $post_type )
	//         return $where;

	// 	return $wpdb->prepare( "WHERE p.menu_order > %s AND p.post_type = %s AND p.post_status = 'publish'", $post->menu_order, $post->post_type);
	// }
	// add_filter( 'get_next_post_where', 'my_next_post_where' );

	// function my_next_post_sort() {

	// 	return "ORDER BY p.menu_order asc LIMIT 1";
	// }
	// add_filter( 'get_next_post_sort', 'my_next_post_sort' );
	// function my_wpml_string($string, $domain = 'catellani', $name = 'Catellani') {
	// 	global $sitepress;
	// 	do_action( 'wpml_register_single_string', 'catellani', $name . ': ' .$string , $string );
	// 	return apply_filters( 'wpml_translate_single_string', 'catellani', $domain, $name. ': ' .$string, $sitepress->get_current_language()); 
	// }