<?php 
	use Roots\Sage\Titles;
	$obj = (isset($current)) ? $current : false;
	$the_id = ($obj) ? $obj->taxonomy.'_'.$obj->term_id : $post->ID;
	$kind = get_field('header_kind');
	$cover = get_field('featured_image', $the_id) ? get_the_post_thumbnail_url($post->ID, 'full') : get_field('cover_image', $the_id)['url'];
	$className  = $kind == 0 ? 'header--bg header--bg-'. get_post_type() : 'header--white';
	$data = '';
	if(get_post_type() == 'lampade')  :
	$data = get_lamps_order($post->ID);
	endif;
?>

<header class="header header--shrink header--<?php echo get_post_type(); ?> <?php echo $className; ?> <?php echo ($kind == 0) ? 'header--grow-md-bottom' : 'header--grow-lg-bottom'; ?>"<?php echo $data; ?>>
	<?php if( $kind == 0 ): ?>
	<div class="header__cover" ng-style="{'background-image' : 'url(<?php echo $cover; ?>)'}"></div>
	<?php endif; ?>
	<?php if(get_post_type() != 'post') : ?>
	<h1 class="header__title<?php echo (get_field('is_scroller_hidden', $the_id) || (get_post_type() == 'post' || get_post_type() == 'progetti'  || get_post_type() == 'installazioni' )) ? ' header__title--noscroller' : ''; ?> <?php echo ($kind == 0) ? 'header__title--medium' : 'header__title--large-lighter'; ?>"><?php
		if($obj) :
			echo $obj->name;
		else :
			echo Titles\title(); 
		endif;
	?></h1>
	<?php endif;
	if(get_field('header_video', $the_id)) : ?>
		<i class="icon-play" ng-class="{ready : isReady == 'video_<?php echo get_the_ID(); ?>'}" ng-click="open('video_<?php echo get_the_ID(); ?>')"></i>
	<?php endif; ?>
</header>
<div ng-sm='{"triggerHook":"onLeave","class":{"element":"body","classes":"white"}}'></div>
<?php get_template_part('templates/modal', 'video'); ?>