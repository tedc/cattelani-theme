<div class="modal__container modal__container--contact" id="modal-contact"<?php if(is_handheld()) : ?> scrollbar<?php endif; ?>>
	<section class="contact contact--grid contact--grow-md-bottom">
		<div class="contact__cell contact__cell--s6 contact__cell--content contact__cell--shrink-double">
			<header class="contact__header contact__header--grow-md-bottom">
				<h2 class="contact__title contact__title--small">
					<?php _e('Contatti', 'catellani'); ?>
				</h2>
			</header>
			<?php 
	        $custom_logo_id = get_theme_mod( 'custom_logo' );
	        $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
	        echo print_svg( $image[0]);
	        ?>
			<?php acf_set_language_to_default(); the_field('info_contatti', 'options'); acf_unset_language_to_default(); ?>
			<?php get_template_part( 'templates/social'); ?>
		</div>
		<div class="contact__cell contact__cell--s6 contact__cell--shrink-double contact__cell--form" ng-form>
			<header class="contact__header contact__header--grow-md-bottom" data-title="<?php _e('Contatti', 'catellani'); ?>"></header>
			<?php get_template_part( 'templates/form', null ); ?>
		</div>
	</section>
</div>