<?php if(get_post_type() != 'post') : ?>
<?php get_template_part( 'templates/entry-meta', 'projects' ); ?>
<?php endif; ?>
<article class="container container--<?php echo get_post_type(); ?> container--grow-lg container--grow-lg-shrink-fw" id="container_<?php the_ID(); ?>">	
	<?php if(get_post_type() == 'post') : ?>
	<?php get_template_part( 'templates/entry', 'meta' ); ?>
	<div class="container__middle">
	<?php endif; ?>
	<h1 class="container__title container__title--huge"><?php the_title(); ?></h1>
	<div class="container__content container__content--grow-md-top container__content--mw">
		<?php the_field('content'); ?>
	</div>
	<?php if(get_post_type() == 'post') :  ?>
	</div>
	
	<?php 	get_template_part( 'templates/entry-meta', 'social' ); 
	endif; ?>
</article>