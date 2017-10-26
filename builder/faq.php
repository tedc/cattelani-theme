<?php $faq = get_posts(array(
	'post_type' => 'domande',
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
	'suppress_filters' => 0
));
if($faq) : ?>
<ul class="faq" ng-init="isFaq=[]">
	<?php foreach($faq as $f) : ?>
	<li class="faq__item accordion" id="<?php echo $f->ID; ?>" ng-class="{'accordion--active':isFaq[<?php echo $f->ID; ?>]}">
		<header class="faq__header faq__header--shrink faq__header--mw faq__header--grow-md" ng-click="isFaq[<?php echo $f->ID; ?>]=!isFaq[<?php echo $f->ID; ?>]">
			<h3 class="faq__title faq__title--small-lighter"><?php echo get_the_title($f->ID); ?></h3>
			<i class="icon-close"></i>
		</header>
		<div class="faq__content faq__content--shrink accordion-content faq__content--gray">
			<div class="faq__text faq__text--lighter faq__text--mw faq__text--grow-md">
				<?php echo apply_filters('the_content', $f->post_content); ?>
			</div>
		</div>
	</li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>