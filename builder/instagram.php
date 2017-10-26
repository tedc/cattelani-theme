<div class="instagram instagram--grow-lg" ng-instagram>
	<header class="instagram__header instagram__header--shrink">
		<h3 class="instagram__title instagram__title--large-lighter">Instagram @{{username}}</h3>
	</header>
	<ul class="instagram__posts instagram__posts--grid">
		<li class="instagram__cell instagram__cell--s3" ng-repeat="i in items">
			<a ng-href="{{i.link}}" ng-attr-target="_blank"><img ng-src="{{resize(i.images.thumbnail.url)}}" /></a>
		</li>
	</ul>	
	<div class="instagram__link instagram__link--grow-top">
		<a href="<?php get_social_link('Instagram'); ?>" target="_blank" class="instagram__send"><?php _e('Seguici su instagram', 'catellani'); ?></a>
	</div>
</div>
