<?php
	$terms = get_terms(
		array(
			'taxonomy' => get_option('glossary-settings')['slug-cat'],
			'hide_empty' => false,
			'orderby' => 'term_order'
		)
	);
 if ($terms) : ?>
<form class="glossary-search glossary-search--shrink glossary-search--gray glossary-search--grow-md" name="glossaryForm">
	<div class="glossary-search__box glossary-search__box--mw" glossary-autocomplete>
		<input type="glossary.search" ng-model="glossary.search" class="glossary-search__input" ng-change="glossary.searchTerms(glossaryForm.$valid)" placeholder="<?php _e('Cerca termini (digita almeno due caratteri)', 'catellani'); ?>" ng-focus="glossary.isSearch = true" ng-model-options="{debounce:500}" ng-minlength="2" ng-blur="glossary.isSearch = off" required />
		<i class="icon-search"></i>
		<ul class="glossary-search__results" ng-class="{'glossary-search__results--visible':glossary.isSearch}">
			<li class="glossary-search__item glossary-search__item--searching" ng-if="!glossary.items.length || glossary.isLoading"><?php _e('Cerca (minimo due caratteri)...', 'catellani'); ?></li>
			<li class="glossary-search__item" ng-repeat="term in glossary.items" ng-click="glossary.goToTerm(term)" ng-bind-html="term.title.rendered | highlight:glossary.search" ng-if="glossary.items.length || !glossary.isLoading"></li>
		</ul>
	</div>
</form>
<div class="glossary glossary--grow-lg">
<ul class="glossary__terms glossary__terms--shrink-fw">
	<?php foreach ($terms as $term) : ?>
	<li class="glossary__term glossary__term--mw" id="<?php echo $term->term_id; ?>">
		<a href="<?php echo get_term_link($term->term_id); ?>" ui-sref="app.glossary({name : '<?php echo $term->slug; ?>', lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})">[<?php echo $term->term_order; ?>] / <?php echo strtolower($term->name); ?></a>
	</li>
	<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>