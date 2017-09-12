<?php
$current = get_queried_object();
include(locate_template('templates/page-header.php', false, false));

include(locate_template( 'templates/'. get_option('glossary-settings')['slug-cat'] .'.php', false, false));
 ?>
