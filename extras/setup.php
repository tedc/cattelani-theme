<?php
	function ng_app($html) {
		$html =  $html . ' class="no-js" ng-app="catellani"';
		return $html;
	}

	add_filter( 'language_attributes', 'ng_app', 100 );
	function theme_setup() {
		add_theme_support( 'custom-logo' );
		add_image_size('magazine', 1246, 700, true);
		add_image_size('vertical-thumb', 440, 560, true);
	}
	
	add_action('after_setup_theme', 'theme_setup');

	// BREADCRUMB

	function breadcrumb_shortcode($atts) {
		$term_obj = ($atts['type'] == 'taxonomy') ? get_term($atts['id']) : false;
		$post_obj = ($atts['type'] == 'post') ? get_post($atts['id']) : false;
		$lang = $atts['lang'];
		ob_start();
		include(locate_template('templates/breadcrumb.php', false, true));
		return ob_get_clean();
	}

	add_shortcode( 'breadcrumb', 'breadcrumb_shortcode');

	//add_filter( 'acf/load_field/name=magazine_template', 'hide_field' );
	//add_filter( 'acf/load_field/name=is_front_page', 'hide_field' );

	function hide_field( $field ) {
		$field['conditional_logic'] = 1;

		return $field;
	}

	// ADD BUILDER TO CONTENT
	function builder_shortcode($attr) {
		ob_start();
		if(!get_field('is_front_page')){get_template_part('templates/page', 'header');}
		if(get_field('magazine_template')) :
			get_template_part('index');
		else :
			get_template_part('builder/init');
		endif;
		return ob_get_clean();
	}
	add_shortcode( 'builder', 'builder_shortcode' );

	function collezioni_shortcode($atts) {
		$the_id = $atts['id'];
		$array = array(
			'post_type' => 'lampade',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			//'order' => 'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => 'collezioni',
					'field' => 'term_id',
					'terms' => array($the_id)
				)
			)
		);
		$posts = get_posts($array);
		if(!$posts) return;
		$count = count($posts);
		$loop = $count > 2 ? 'true' : 'false';
		$show = $count > 1 ? 'true' : 'false';
		$bp = ($count == 3) ? ", 'breakpoints' : { 849 : {'loop' : true} }" : '';
		$c = 0;
		$html = '<div class="collections collections--slider-h" ng-scroll-carousel><div class="collections__slider collections__slider--archive" scrollbar="carousel" axis-x="true">';
		foreach ($posts as $post) {
			ob_start();
			include(locate_template( 'templates/content-lampade.php', false, false ));
			$item = ob_get_clean();
			$html .= $item;
			$c++;
		}
		$html .= '</div><div class="collections__loader"><div class="collections__spinner"></div></div><i class="icon-arrow icon-arrow-prev" ng-click="move(false)" ng-class="{hide : !isVisible, inactive : !isPrev}"></i><i class="icon-arrow icon-arrow-next" ng-click="move(true)" ng-class="{hide : !isVisible, inactive : !isNext}"></i></div>';
		return $html;
	}
	add_shortcode( 'collezioni', 'collezioni_shortcode' );

	function glossary_cat_shortcode($atts) {
		$the_id = $atts['id'];
		$current = get_term($the_id, get_option('glossary-settings')['slug-cat']);
		ob_start();
		include(locate_template('templates/page-header.php', false, false));
		include(locate_template( 'templates/'. get_option('glossary-settings')['slug-cat'] .'.php', false, false));
		return ob_get_clean();
	}
	add_shortcode( 'glossary_cat', 'glossary_cat_shortcode' );


	function add_builder_shortcode($post_id) {
		$field = get_field_object('header_kind');
		$layout = get_field_object('layout');
		if(isset($_POST['acf'][$field['key']])) {
			$value = $_POST['acf'][$field['key']] == 0 ? 'default' : $_POST['acf'][$field['key']];
		} else {
			return;
		}
		if( empty($value) ) {
			return;
		}
		$args = array(
			'ID' => $post_id,
			'post_content' => '[builder]'
		);
		wp_update_post($args);
	}
	add_action( 'acf/save_post', 'add_builder_shortcode' );

	// CHANGE WRAPPER

	add_filter('sage/wrap_base', 'my_sage_wrap');
	
	function my_sage_wrap($templates) {
		array_unshift($templates, 'extras/base.php');
		return $templates;
	}

	function na_remove_slug( $post_link, $post, $leavename ) {

	    if ( ('lampade' != $post->post_type && 'progetti' != $post->post_type && 'installazioni' != $post->post_type) || 'publish' != $post->post_status ) {
	        return $post_link;
	    }
	    $uri = '';
	    foreach ( $post->ancestors as $parent ) {
	        $uri = get_post( $parent )->post_name . "/" . $uri;
	    }

	    $post_link = str_replace( $uri, '', $post_link );
	    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

	    return $post_link;
	}
	add_filter( 'post_type_link', 'na_remove_slug', 10, 3 );


	function gp_parse_request_trick( $query ) {
	 
	    // Only noop the main query
	    if ( ! $query->is_main_query() )
	        return;
	 
	    // Only noop our very specific rewrite rule match
	    if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
	        return;
	    }
	 
	    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
	    if ( ! empty( $query->query['name'] ) ) {
	        $query->set( 'post_type', array( 'post', 'page', 'lampade', 'installazioni', 'progetti', 'glossary' ) );
	    }
	}
	add_action( 'pre_get_posts', 'gp_parse_request_trick' );

	// Allow SVG
	add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

		global $wp_version;
		if ( $wp_version !== '4.7.1' ) {
		return $data;
		}

		$filetype = wp_check_filetype( $filename, $mimes );

		return [
		'ext'             => $filetype['ext'],
		'type'            => $filetype['type'],
		'proper_filename' => $data['proper_filename']
		];

	}, 10, 4 );

	function cc_mime_types( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		$mimes['ogv'] = 'video/ogg';
		return $mimes;
	}
	add_filter( 'upload_mimes', 'cc_mime_types' );

	function fix_svg() {
		echo '<style type="text/css">.attachment-266x266, .thumbnail img { width: 100% !important; height: auto !important; }</style>';
	}
	add_action( 'admin_head', 'fix_svg' );

	add_action('admin_head', 'my_custom_fonts');

	function my_custom_fonts() {
	  echo '<style>
	    .toplevel_page_instagram img {
	    	height: 20px;
	    }
	  </style>';
	}

	add_filter('deprecated_constructor_trigger_error', '__return_false');
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	add_filter('excerpt_more','__return_false');

	function my_acf_init() {	
		acf_update_setting('google_api_key', 'AIzaSyAX6KpPbqUz3WUJL_sYlUwlIreVhf1VabM');
	}

	add_action('acf/init', 'my_acf_init');

	add_action( 'init', 'my_add_excerpts_to_pages' );
	function my_add_excerpts_to_pages() {
		add_post_type_support( 'page', 'excerpt' );
	}

	// Register and load the widget
	function wpb_load_widget() {
		register_widget( 'wpb_widget' );
	}
	add_action( 'widgets_init', 'wpb_load_widget' );

	// Creating the widget 
	class wpb_widget extends WP_Widget {

		function __construct() {
			parent::__construct(

			// Base ID of your widget
				'wpb_widget', 

			// Widget name will appear in UI
				__('Icona linkabile', 'catellani'), 

			// Widget description
				array( 'description' => __( 'Inserisci classe dell\'icona e link', 'catellani' ), ) 
			);
		}

		// Creating widget front-end

		public function widget( $args, $instance ) {
			$target = ($instance['target']) ? ' target="_blank"' : '';
			echo '<a href="'.$instance['link'].'"'.$target.' class="icon-'.$instance['icon'].'"></a>';
		}
				
		// Widget Backend 
		public function form( $instance ) {
			if ( isset( $instance[ 'icon' ] ) ) {
				$icon = $instance[ 'icon' ];
			}
			else {
				$icon = '';
			}
			if ( isset( $instance[ 'link' ] ) ) {
				$link = $instance[ 'link' ];
			}
			else {
				$link = '';
			}
			$target = isset( $instance[ 'target' ] ) ? $instance[ 'target' ] : false;
		// Widget admin form
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e( 'Icona:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>" type="text" value="<?php echo esc_attr( $icon ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_attr( $icon ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'target' ); ?>"><?php _e( 'Target:' ); ?></label> 
				<input id="<?php echo $this->get_field_id( 'target' ); ?>" name="<?php echo $this->get_field_name( 'target' ); ?>" type="checkbox" value="<?php echo esc_attr( $target ); ?>" />
			</p>
		<?php 
		}
			
		// Updating widget replacing old instances with new
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['icon'] = ( ! empty( $new_instance['icon'] ) ) ? strip_tags( $new_instance['icon'] ) : '';
			$instance['link'] = ( ! empty( $new_instance['link'] ) ) ? strip_tags( $new_instance['link'] ) : '';
			$instance['target'] = $new_instance['target'];
			return $instance;
		}
	} // Class wpb_widget ends here

	if( function_exists('acf_add_options_page') ) {	
		// acf_add_options_sub_page(array(
		// 	'page_title' 	=> 'Footer',
		// 	'menu_title'	=> 'Footer Settings',
		// 	'parent_slug' => 'themes.php'
		// ));
		// acf_add_options_page(array(
		// 	'page_title' 	=> 'Cookie law',
		// 	'menu_title'	=> 'Cookie law',
		// 	'menu_slug' => 'cookie-law'
		// ));
		acf_add_options_page(array(
			'page_title' 	=> 'Instagram Settings',
			'menu_title'	=> 'Instagram',
			'menu_slug' => 'instagram',
			'icon_url' => get_stylesheet_directory_uri() . '/assets/images/instagram.png'
		));
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Campi comuni',
			'menu_title'	=> 'Campi comuni',
			'parent_slug' => 'themes.php'
		));
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Breadcrumb',
			'menu_title'	=> 'Breadcrumb',
			'parent_slug' => 'themes.php'
		));
		acf_add_options_page(array(
			'page_title' 	=> 'Aforismi',
			'menu_title'	=> 'Aforismi'
		));
	}

	add_filter( 'manage_aforismi_posts_columns', 'set_custom_edit_aforismi_columns' );
	add_action( 'manage_aforismi_posts_custom_column' , 'custom_aforismi_column', 10, 2 );
	
	function set_custom_edit_aforismi_columns($columns) {
	    unset( $columns['author'] );
	    unset($columns['title']);
	    unset($columns['date']);
	    $columns['aforismi_content'] = __( 'Testo', 'catellani' );
	    $columns['aforismi_author'] = __( 'Autore', 'catellani' );

	    return $columns;
	}

	function custom_aforismi_column( $column, $post_id ) {
	    switch ( $column ) {

	        case 'aforismi_content' :
	            echo get_field(  'testo_aforisma' , $post_id ); 
	            break;

	        case 'aforismi_author' :
	            if ( get_field( 'default_sign', $post_id ) )
	                echo 'Enzo Catelanni';
	            else
	                echo get_field( 'firma_aforisma' , $post_id ); 
	            break;

	    }
	}

	function my_yoats_single_link($output, $link) {
		$lang = (preg_match('/(en)/', $link['url'])) ? 'en' : 'it';
		$url = str_replace(get_home_url(), '', $link['url']);
		$sref = '';
		$matches = preg_match('/(category|categorie)/',  $url);
		if($url == '' || $url == '/') {
			$sref = ' ui-sref="app.root({lang : \''.$lang.'\'})"';
		} else {
			if($matches) {
				$sref = ' ui-sref="app.category({lang : \''.$lang.'\', name : \'\'})"';
			} else {
				$sref = ' ui-sref="app.root({lang : \''.$lang.'\', slug : \''.$url.'\'})"';
			}
		}
		$output = str_replace('<a', '<a'.$sref, $output);

		return $output;
	}
	add_filter('wpseo_breadcrumb_single_link', 'my_yoats_single_link', 10, 2);

	if (has_action('wp_head','_wp_render_title_tag') == 1) {
	    remove_action('wp_head','_wp_render_title_tag',1);
	    add_action('wp_head','custom_wp_render_title_tag_filtered',1);
	}

	function custom_wp_render_title_tag_filtered() {
	    if (function_exists('_wp_render_title_tag')) {
	        ob_start(); 
	        _wp_render_title_tag(); 
	        $titletag = ob_get_contents();
	        ob_end_clean();
	    } else {$titletag = '';}
	    $titletag = apply_filters('wp_render_title_tag_filter',$titletag);
	    echo $titletag;
	}

	add_filter('wp_render_title_tag_filter','custom_wp_render_title_tag');

	function custom_wp_render_title_tag($titletag) {
	    $titletag = str_replace('<title','<title ng-bind-html="(title || \''.wp_get_document_title().'\')"',$titletag);
	    return $titletag;
	}

	function collezioni_posts_per_page($query) {
		if($query->is_tax('collezioni') && $query->is_main_query()) {
			$query->set('posts_per_page', -1);
		}
		return $query;
	}
	add_filter( 'pre_get_posts', 'collezioni_posts_per_page' );

	function my_gallery_shortcode( $output = '', $attrs ) {
		$row = str_replace(',', '', $attrs['ids']);
		$ids = explode(',', $attrs['ids']);
		$images = array();
		$full = true;
		foreach ($ids as $id) {
			array_push($images, array('ID' => $id, 'url' => wp_get_attachment_image_src( $id, 'full')[0], 'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true)));
		}
		ob_start();
		include(locate_template('builder/commons/gallery.php', false, false));
		$output = '<div class="container__gallery container__gallery--shrink-fw container__gallery--grow-lg-top container__gallery--grow-md-bottom" id="slider_'.$row.'">'.ob_get_clean().'</div>';
		return $output;
	}

	add_filter( 'post_gallery', 'my_gallery_shortcode', 10, 2 );



	
	add_filter( 'the_excerpt', 'shortcode_unautop');
	add_filter('the_excerpt', 'do_shortcode');
	remove_filter('get_the_excerpt', 'wp_trim_excerpt', 10);
	add_filter('get_the_excerpt', 'my_custom_wp_trim_excerpt', 99, 1);
	function my_custom_wp_trim_excerpt($text) {
	    if(''==$text) {
	        $text= preg_replace('/\s/', ' ', wp_strip_all_tags(get_field('content')));
	        $text= explode(' ', $text, 35);
	        array_pop($text);
	        $text= implode(' ', $text);
	    }
	    return $text;
	}

	function custom_excerpt_length( $length ) {
		return 35;
	}
	add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

	add_action('restrict_manage_posts','lampade_filter_by_collection');
	function lampade_filter_by_collection() {
	    global $typenow;
	    global $wp_query;
	    if ($typenow=='listing') {
	        $taxonomy = 'collezioni';
	        $business_taxonomy = get_taxonomy($taxonomy);
	        wp_dropdown_categories(array(
	            'show_option_all' =>  __("Show All {$business_taxonomy->label}", 'catellani'),
	            'taxonomy'        =>  $taxonomy,
	            'name'            =>  'collezioni',
	            'orderby'         =>  'name',
	            'selected'        =>  $wp_query->query['term'],
	            'hierarchical'    =>  true,
	            'depth'           =>  3,
	            'show_count'      =>  true, // Show # listings in parens
	            'hide_empty'      =>  true, // Don't show businesses w/o listings
	        ));
	    }
	}