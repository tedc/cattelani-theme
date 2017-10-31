<?php
	function deregister_styles() {
		wp_deregister_style( 'cff' );
		wp_deregister_style( 'cff-font-awesome' );
		wp_deregister_style( 'sb_instagram_styles' );
		wp_deregister_style( 'wpsl-styles' );
		wp_deregister_style( 'sb_instagram_icons' );
		wp_deregister_style( 'glossary-hint' );
		
	}	
	add_action( 'wp_print_styles', 'deregister_styles', 100 );

	function media_styles($html, $handle, $href, $media) {
		if($media != 'none') {
			return $html;
		}
		$html = str_replace('media', 'onload="if(media!=\'all\')media=\'all\'" media', $html);
		return $html;
	}
	add_filter('style_loader_tag', 'media_styles', 10, 4);
