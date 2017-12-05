<?php
	$post_type = get_post_type();
	$aforisma = [];
	$testi = [];
	$row = 0;
	while(have_rows('layout')) :
		the_row();
		include(locate_template( 'builder/' .get_row_layout() .'.php', false, true ));
	$row++;
	endwhile;
	if($post_type != 'lampade' && $post_type != 'page') :
		//include(locate_template( 'builder/content.php', false, true));
	endif;
	if($post_type == 'lampade') :
		include(locate_template( 'builder/colori.php', false, true ));
		include(locate_template( 'builder/sheet.php', false, true ));
	endif;
	if($post_type != 'page') :
		if($post_type ==  'progetti'  || $post_type == 'installazioni' ) {
			include(locate_template( 'builder/related.php', false, true));
		}
		include(locate_template( 'templates/next.php', false, false));
	endif;
?>