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
