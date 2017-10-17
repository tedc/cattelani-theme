<div class="error error--grid" ng-error-page>
	<div class="error__cell error__cell--image">
		<figure class="error__image error__image--off" ng-style="{backgroundImage : 'url(<?php echo get_stylesheet_directory_uri() . '/assets/images/404-off.jpg' ?>)'}">
			<img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/404-on.jpg' ?>" alt="<?php echo get_bloginfo('name'); ?> - 404">
		</figure>
		<div class="error__image error__image--on" ng-style="{backgroundImage : 'url(<?php echo get_stylesheet_directory_uri() . '/assets/images/404-on.jpg' ?>)'}">
		</div>
		<a href="<?php echo home_url(); ?>" ng-click="goToHome()">
			<?php _e('Torna alla', 'catellani'); ?><br />
			<?php _e('Home', 'catellani'); ?>
		</a>
	</div>
	<div class="error__cell error__cell--content">
		<h1 class="error__title">
			<?php _e('Oops, pagina non trovata', 'catellani'); ?>
		</h1>
		<h2 class="erro__title">
			<?php _e('Accendi Miracolo per tornare alla Home', 'catellani'); ?>
		</h2>
	</div>
</div>
