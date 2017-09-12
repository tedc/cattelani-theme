<?php 
	$glossary = new WP_Query(array(
		'post_type' =>  get_option('glossary-settings')['slug'],
		'posts_per_page' => -1,
		'orderby' => 'term_order',
		'tax_query' => array(
			array(
				'taxonomy' => get_option('glossary-settings')['slug-cat'],
				'field' => 'term_id',
				'terms' => array($current->term_id)
			)
		)
	));
	$terms = get_terms(
		array(
			'taxonomy' => get_option('glossary-settings')['slug-cat'],
			'hide_empty' => false,
			'orderby' => 'term_order',
			'exclude' => $current->term_id
		)
	);
?>
<div class="glossary glossary--grow-lg-top">
	<div class="glossary__terms glossary__terms--grow-lg">
		<div class="glossary__term" id="<?php echo $current->term_id; ?>">
			<span class="glossary__link glossary__link--mw">[<?php echo $current->term_order; ?>] / <?php echo $current->name; ?></span>
			<ul class="glossary__list">
				<?php while($glossary->have_posts()) : $glossary->the_post(); ?>
				<li class="glossary__item accordion" id="glossary_<?php the_ID(); ?>" ng-class="{'accordion--active':isGlossary[<?php the_ID(); ?>]}">
					<header class="glossary__header glossary__header--mw glossary__header--grow-md" ng-click="isGlossary[<?php the_ID(); ?>]=!isGlossary[<?php the_ID(); ?>]">
						<h3 class="glossary__title glossary__title--small-lighter"><?php the_title(); ?></h3>
						<i class="icon-close"></i>
					</header>
					<div class="glossary__content accordion-content glossary__content--shrink glossary__content--gray">
						<div class="glossary__text glossary__text--lighter glossary__text--mw glossary__text--grow-md">
							<?php the_content(); ?>
						</div>
					</div>
				</li>
				<?php endwhile; wp_reset_query(); ?>
			</ul>
		</div>
	</div>
	<ul class="glossary__terms glossary__terms--grow-lg glossary__terms--gray">
		<?php foreach ($terms as $term) : ?>
		<li class="glossary__term glossary__term--grow" id="<?php echo $term->term_id; ?>">
			<a class="glossary__link glossary__link--mw" href="<?php echo get_term_link($term->term_id); ?>" ui-sref="app.glossary({name : '<?php echo $term->slug; ?>'})">[<?php echo $term->term_order; ?>] / <?php echo $term->name; ?></a>
		</li>
		<?php endforeach; ?>
	</ul>
</div>