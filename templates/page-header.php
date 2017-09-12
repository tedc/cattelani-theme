<?php 
	use Roots\Sage\Titles;
	$obj = (isset($current)) ? $current : false;
	$the_id = ($obj) ? $obj->taxonomy.'_'.$obj->term_id : $post->ID;
	$kind = get_field('header_kind');
	$cover = get_field('featured_image', $the_id) ? get_the_post_thumbnail_url($post->ID, 'full') : get_field('cover_image', $the_id)['url'];
	$className  = $kind == 0 ? 'header--bg header--bg-'. get_post_type() : 'header--white';
	$lampade = get_posts(array('post_type'=>'lampade','posts_per_page'=>-1));
    $class_lampada = count($lampade) >= 3 ? 3 : count($lampade);
    $class_lampada = 12 / $class_lampada;
	$data = (get_post_type() == 'lampade') ? ' data-cover="'.$cover.'" data-size="'.$class_lampada.'"' : '';
?>
<header class="header header--shrink <?php echo $className; ?> <?php echo ($kind == 0) ? 'header--grow-md-bottom' : 'header--grow-lg-bottom'; ?>"<?php echo $data; ?>>
	<?php if( $kind == 0 ): ?>
	<div class="header__cover" ng-style="{'background-image' : 'url(<?php echo $cover; ?>)'}"></div>
	<?php endif; ?>
	<h<?php echo (is_singular('post') || is_singular( 'progetti' ) || is_singular( 'installazioni' )) ? 2 : 1;?> class="header__title<?php echo (get_field('is_scroller_hidden', $the_id) || (is_singular('post') || is_singular( 'progetti' ) || is_singular( 'installazioni' ))) ? ' header__title--noscroller' : ''; ?> <?php echo ($kind == 0) ? 'header__title--medium' : 'header__title--large-lighter'; ?>"><?php
		if(is_singular('post') || is_singular( 'progetti' ) || is_singular( 'installazioni' )) :
			if(is_singular( 'progetti' ) || is_singular( 'installazioni' )) :
				echo get_post_type_object( get_post_type() )->labels->name;
			endif;
		else :
			if($obj) :
				echo $obj->name;
			else :
				echo Titles\title(); 
			endif;
		endif;
	?></h<?php echo (is_singular('post') || is_singular( 'progetti' ) || is_singular( 'installazioni' )) ? 2 : 1;?>>
	<?php if (get_field('header_video', $the_id)) : ?>
		<i class="icon-play" ng-class="{ready : isReady == 'video_<?php echo get_the_ID(); ?>'}" ng-click="open('video_<?php echo get_the_ID(); ?>')"></i>
	<?php endif; ?>
</header>