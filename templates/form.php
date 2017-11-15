<?php get_template_part( 'templates/form', 'header' ); 
$current_url = home_url(add_query_arg(array(),$wp->request)); ?>
<form id="contactForm" class="form" name="contactForm" ng-submit="submit(contactForm.$valid, '<?php echo $current_url; ?>')" novalidate>
	<p><input class="form__input" type="text" required ng-model="formData.sender" placeholder="<?php _e('Nome e cognome (richiesto)', 'catellani'); ?>"></p>
	<p><input class="form__input" type="email" required ng-model="formData.email" placeholder="<?php _e('Indirizzo e-mail (richiesto)', 'catellani'); ?>"></p>
	<p><input class="form__input" type="tel" required ng-model="formData.tel" placeholder="<?php _e('Telefono (richiesto)', 'catellani'); ?>"></p>
	<p><textarea class="form__textarea" ng-model="formData.message" placeholder="<?php _e('Messaggio', 'catellani'); ?>"></textarea></p>
	<em class="form__privacy"><?php _e('Inviando questo form acconsento al trattamento dei dati personali ai sensi del D. Lgs. 196/03.', 'catellani'); ?></em>
	<p class="form__footer">
		<button type="submit" class="form__send" ng-disabled="contactForm.$invalid">
			<span><?php _e('Invia', 'catellani'); ?></span>
		</button>
	</p>
	<div class="form__alert" ng-class="{'form__alert--visible' : isSubmitted}" ng-click="isSubmitted=false">
		<div class="form__message" bind-html-compile="alert" ng-class="{'form__message--visible' : isContactSent}"></div>
	</div>
	<div class="form__loader" ng-class="{'form__loader--visible' : isSubmitted && !isContactSent}">
		<div class="form__spinner"></div>
	</div>
</form>
