<div class="error error--grid" ng-error-page>
	<div class="error__cell error__cell--image">
		<figure class="error__image error__image--off">
			<div class="error__image error__image--on" ng-style="{backgroundImage : 'url(<?php echo get_stylesheet_directory_uri() . '/assets/images/404-on.jpg' ?>)'}">
			</div>
			<div class="error__contain" ng-style="{backgroundImage : 'url(<?php echo get_stylesheet_directory_uri() . '/assets/images/404-off.jpg' ?>)'}"></div>
			<img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/404-on.jpg' ?>" alt="<?php echo get_bloginfo('name'); ?> - 404">
			<a href="<?php echo home_url(); ?>" ng-click="goToHome($event)">
				<?php echo my_wpml_string('Torna alla', 'catellani'); ?><br />
				<?php echo my_wpml_string('Home', 'catellani'); ?>
			</a>
		</figure>
	</div>
	<div class="error__cell error__cell--content">
		<h1 class="error__title error__title--huge-lighter">
			<?php echo my_wpml_string('Oops, pagina non trovata', 'catellani'); ?>
		</h1>
		<h2 class="error__title error__title--medium-lighter">
			<?php echo my_wpml_string('Accendi Miracolo per tornare alla Home', 'catellani'); ?>
		</h2>
	</div>
</div>
