<?php
	use Roots\Sage\Assets;
	function catellani_script() {
		global $sitepress;
		// $terms = get_terms('collezioni');
		// $max_posts = [];
		// foreach ($terms as $term) {
		// 	$tax_query = array(
		// 		array(
		// 			'taxonomy' => 'collezioni',
		// 			'field' => 'term_id',
		// 			'terms' => array($term->term_id)
		// 		)
		// 	);
		// 	$posts = get_posts(array('posts_per_page' => -1, 'post_type' => 'lampade'));
		// 	$max_posts[] = array($term->term_id => count($posts));
		// }
		$posts = get_posts(array('posts_per_page' => -1, 'post_type' => 'lampade'));
		$posts = count($posts);
		$white = (get_field('header_kind') != 0 && !is_front_page()) ? 'white-header' : ''; 
		$contactBar = (get_field('is_contact_bar')) ? 'contact-bar' : ''; 
		$log = (is_user_logged_in() && is_admin_bar_showing()) ? ' logged-in admin-bar' : ''; 
		$body_classes = join( ' ', get_body_class( array($contactBar, $white ) ) );
		wp_deregister_script( 'sage/js' );
		//wp_enqueue_script('lib', Assets\asset_path('scripts/lib.js'), null, null, true);
		wp_enqueue_script('catellanijs', Assets\asset_path('scripts/main.js#asyncload'), null, null, true);
		$languages = icl_get_languages('skip_missing=0&orderby=code');
		ob_start();
		include(locate_template( '404.php', false, true ));
		$error = ob_get_clean();
        $translations = [];
        foreach ($languages as $language) {
        	$translations[] = $language['language_code'];
        }
        $codes = join('|', $translations);
            
		$vars = array(
			"main" => array(
				'mobile' => (bool)is_handheld(),
				'assets' => get_stylesheet_directory_uri() . '/assets/',
				'base' => get_home_url(),
				'glossary' => get_option('glossary-settings')['slug-cat'],
				'glossary_slug' => get_option('glossary-settings')['slug'],
				'home' => get_post(get_option('page_on_front'))->post_name,
				'logged_classes' => $log,
				'body_classes' => $body_classes,
				'langs' => $codes,
				'error' => $error,
				'errorTitle' => __('Pagina non trovata', 'catellani')
			),
			"api" => array(	
				'google_api_key' => acf_get_setting('google_api_key'),
				'start_latlng' => get_option('wpsl_settings')['start_latlng'],
				'store_limit' => get_option('wpsl_settings')['autoload_limit'],
				'count_posts' => $posts,
				'count_collections' => count(get_terms(array('taxonomy' => 'collezioni'))),
				'count_positions' => count(get_terms(array('taxonomy' => 'posizioni'))),
				'count_sources' => count(get_terms(array('taxonomy' => 'fonti')))
			),
			"strings" => array(
				'btn_stores' => __('Cerca rivenditori', 'catellani'),
				'select_any' => __('Qualsiasi', 'catellani'),
				'empty_store' => __('Non risultano store presenti nella zona', 'catellani')
			)
		);
		wp_localize_script( 'catellanijs', 'vars', $vars );
		wp_deregister_script( 'cffscripts' );
		wp_deregister_script( 'sb_instagram_scripts' );
	}

	add_action('wp_enqueue_scripts', 'catellani_script', 200);

	function third_party_scripts() {
		?>
	<script type="text/javascript">
		var _iub = _iub || [];
		_iub.csConfiguration = {
			cookiePolicyId: 8257206,
			siteId: 942881,
			lang: "<?php echo ICL_LANGUAGE_CODE; ?>"
		};
	</script>
	<script type="text/javascript" src="//cdn.iubenda.com/cookie_solution/safemode/iubenda_cs.js" charset="UTF-8" async></script>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-K7JJCPB');</script>
	<!-- End Google Tag Manager -->
	<?php }

