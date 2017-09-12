<?php 
	$page = get_sub_field('page');
?>
<div class="section__content section__content--grow-lg section__content--shrink section__content--shrink<?php echo ($col%2==0) ? '-right-double' : '-left-double'; ?>"<?php scrollmagic('"tween":{"y":-80},"triggerElement":"#col_'.$col.'_'.$row.'", "triggerHook":1,"duration":"200vh"'); ?>>
	<div class="section__background<?php echo (get_sub_field('sfondo') == 'bg') ? ' section__background--image' : ''; ?>"<?php if(get_sub_field('sfondo') == 'bg') : $p = (get_sub_field('background')['height'] * 100) /get_sub_field('background')['width']; echo ' style="padding-top:'.$p.'%"'; else : square('page'); endif; ?>>
		<?php if(get_sub_field('sfondo') == 'bg') : ?>
		<img src="<?php echo get_sub_field('background')['url']; ?>">
		<?php endif; ?>
	</div>
	<h4 class="section__title--big-upper"><?php echo get_the_title($page->ID); ?></h4>
	<?php if(get_field('subtitle', $page->ID)) : ?><h4 class="section__title section__title--small"><em><?php the_field('subtitle', $page->ID); ?></em></h4><?php endif; ?>
	<?php echo '<p>'.$page->post_excerpt.'</p>'; ?>
	<div class="section__button section__button--grow-md-top">
		<a href="<?php echo get_permalink($page->ID); ?>" class="<?php the_sub_field('button'); ?>">
			<?php echo (get_sub_field('button') == 'more') ? __('Leggi tutto', 'sprfc') : '<span class="button__label">'.get_the_title($page->ID).'</span>'; ?>
		</a>
	</div>
</div>