<?php
	use Roots\Sage\Assets;
	function in_array_r($item , $array){
    	return preg_match('/"'.json_encode($item).'"/i' , json_encode($array));
	}
	function catellani_script() {
		global $sitepress;
		global $post;
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
        $languages = apply_filters('wpml_active_languages', null);
	    $translations = array();
	    $type = (is_tax()) ? get_queried_object()->taxonomy : get_post_type();
	    $the_id = (is_tax()) ? get_queried_object()->term_id : $post->ID;
	    $front_page = [];
	    foreach ($languages as $language) {
	    	$default_locale = $language['default_locale'];
	    	$current_lang = $language['language_code'];
        	$post_id = apply_filters('wpml_object_id', $the_id, $type, false, $current_lang);
	        $href = (is_tax()) ? get_term_link($post_id, $type) : get_permalink($post_id);
	        $front_page[$current_lang] = $sitepress->convert_url(get_home_url(), $current_lang);
	        $href = (is_front_page()) ? $front_page[$current_lang] : $href;
	        $translations[$current_lang] = $href;
	        $translations[substr($default_locale, 3, 2)] = $href;
	        $translations[$default_locale] = $href;
	    }
		// $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		// $lang = explode('-', $lang)[0];
		// if($lang != $sitepress->get_default_language()) {
		// 	$lang = in_array_r(array('language_code'=>$lang), $languages) ? $lang : 'en';
		// } else {
		// 	$lang = $lang;
		// }
		// $url = (isset($translations[$lang])) ? $translations[$lang] : $front_page[$lang];
		$redirect = array('default_lang' => $sitepress->get_default_language(),'current' => ICL_LANGUAGE_CODE, 'langs' => $translations);
		$ajax = array('url' => admin_url('admin-ajax.php'), 'action' => 'catellanipdf');
		$sent = __('Messaggio inviato correttamente','catellani');
        $tnx = __('Grazie per averci contattato.<br/>Ti risponderemo prima possibile','catellani');
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
				'errorTitle' => __('Pagina non trovata', 'catellani'),
				'redirect' => $redirect,
				'formMsg' => "<h3 class='form__title'>".$sent."</h3><p>".$tnx."</p><a ui-sref='app.root({lang : \"".ICL_LANGUAGE_CODE."\"})' class='form__send' href='".get_home_url()."'>".__('Torna alla home', 'catellani')."</a>"
			),
			"api" => array(	
				'google_api_key' => acf_get_setting('google_api_key'),
				'start_latlng' => get_option('wpsl_settings')['start_name'],
				'store_limit' => get_option('wpsl_settings')['autoload_limit'],
				'count_posts' => $posts,
				'count_collections' => count(get_terms(array('taxonomy' => 'collezioni'))),
				'count_positions' => count(get_terms(array('taxonomy' => 'posizioni'))),
				'count_sources' => count(get_terms(array('taxonomy' => 'fonti'))),
				'ajax' => $ajax
			),
			"strings" => array(
				'btn_stores' => __('Cerca rivenditori', 'catellani'),
				'select_any' => __('Qualsiasi', 'catellani'),
				'city_label' => __('Città', 'catellani'),
				'region_label' => __('Regione', 'catellani'),
				'country_label' => __('Paese', 'catellani'),
				'empty_store' => __('Non risultano store presenti nella zona', 'catellani'),
				'more_stores' => __('Carica altri', 'catellani'),
				'search_country' => __('Cerca una nazione...', 'catellani'),
				'search_city' => __('Cerca una nazione...', 'catellani')
			)
		);
		wp_localize_script( 'catellanijs', 'vars', $vars );
		wp_deregister_script( 'cffscripts' );
		wp_deregister_script( 'sb_instagram_scripts' );
		wp_deregister_script( 'wp-embed' );
	}

	add_action('wp_enqueue_scripts', 'catellani_script', 200);

	function third_parties_scripts() {
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
	<script>
	(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-K7JJCPB');</script>
	<!-- End Google Tag Manager -->
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-29668127-1"></script>
<script>
 window.dataLayer = window.dataLayer || [];
 function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());

 gtag('config', 'UA-29668127-1');
</script>
	<!-- Facebook Pixel Code -->
<script>
 !function(f,b,e,v,n,t,s)
 {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
 n.callMethod.apply(n,arguments):n.queue.push(arguments)};
 if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
 n.queue=[];t=b.createElement(e);t.async=!0;
 t.src=v;s=b.getElementsByTagName(e)[0];
 s.parentNode.insertBefore(t,s)}(window, document,'script',
 'https://connect.facebook.net/en_US/fbevents.js');
 fbq('init', '1777622158914529');
 fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
 src="https://www.facebook.com/tr?id=1777622158914529&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
	<?php }

	add_action('wp_head', 'third_parties_scripts');
