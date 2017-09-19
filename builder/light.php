<?php if(get_sub_field('immagine_accesa') && get_sub_field('immagine_spenta')) : ?>
<div class="light light--shrink-fw light--grid">
	<figure class="light__cell light__cell--figure light__cell--s<?php echo (get_sub_field('foto_verticale')) ? 5 : 8; ?>" ng-light-mask ng-mousemove="moveMask($event, '#circle_<?php echo $row; ?>')">
		<?php echo wp_get_attachment_image( get_sub_field('immagine_spenta')['ID'], 'full' ); ?>
		<svg class="light__svg" viewBox="0 0 <?php echo get_sub_field('immagine_accesa')['width']; ?> <?php echo get_sub_field('immagine_accesa')['height']; ?>">
			<defs>
				<radialGradient id="gradient_<?php echo $row; ?>">
					<stop offset="0%" stop-color="white" stop-opacity="1" />
					<stop offset="100%" stop-color="white" stop-opacity="0" />
				</radialGradient>
				<mask id="mask_<?php echo $row; ?>">
					<circle r="<?php echo get_sub_field('radius') ? get_sub_field('radius') : 150; ?>" cy="0" cx="0" class="light__circle" fill="url(#gradient_<?php echo $row; ?>)" id="circle_<?php echo $row; ?>" />
				</mask>
			</defs>
			<image xlink:href="" x="0" y="0" width="<?php echo get_sub_field('immagine_accesa')['width']; ?>" height="<?php echo get_sub_field('immagine_accesa')['height']; ?>" ng-attr-xlink:href="<?php echo wp_get_attachment_image_src( get_sub_field('immagine_accesa')['ID'], 'full')[0]; ?>" mask="url(#mask_<?php echo $row; ?>)" />
		</svg>
	</figure>
	<div class="light__cell light__cell--content light__cell--s<?php echo (get_sub_field('foto_verticale')) ? 7 : 4; ?> light__cell--shrink-left-only">
		<div class="light__text light__text--grow-md"><?php _e('Muovi il mouse sopra l’immagine per creare la luce.') ?></div>
	</div>
</div>
<?php endif; ?>