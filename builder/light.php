<?php if(get_sub_field('immagine_accesa') && get_sub_field('immagine_spenta')) : ?>
<div class="light light--shrink-fw light--grid">
	<figure class="light__cell light__cell--figure light__cell--s<?php echo (get_sub_field('foto_verticale')) ? 5 : 7; ?>" ng-light-mask ng-mousemove="moveMask($event, '#light_svg_<?php echo $row; ?>')">
		<?php echo wp_get_attachment_image( get_sub_field('immagine_spenta')['ID'], 'full' ); ?>
		<svg class="light__svg" viewBox="0 0 <?php echo get_sub_field('immagine_accesa')['width']; ?> <?php echo get_sub_field('immagine_accesa')['height']; ?>" id="light_svg_<?php echo $row; ?>">
			<defs>
				<radialGradient id="gradient_<?php echo $row; ?>">
					<stop offset="0%" stop-color="white" stop-opacity="1" />
					<stop offset="100%" stop-color="white" stop-opacity="0" />
				</radialGradient>
				<mask id="clip_<?php echo $row; ?>">
					<circle r="<?php echo get_sub_field('radius') ? get_sub_field('radius') : 250; ?>" cy="0" cx="0" class="light__circle" fill="url(#gradient_<?php echo $row; ?>)" id="circle_<?php echo $row; ?>" />
				</mask>
				<clippath id="mask_<?php echo $row; ?>" mask="clip_<?php echo $row; ?>">
					<circle r="<?php echo get_sub_field('radius') ? get_sub_field('radius') : 250; ?>" cy="0" cx="0" class="light__circle" fill="#fff" id="circle_<?php echo $row; ?>" />
				</clippath>
			</defs>
			<g clip-path="url(#mask_<?php echo $row; ?>)">
			<image xlink:href="" x="0" y="0" width="<?php echo get_sub_field('immagine_accesa')['width']; ?>" height="<?php echo get_sub_field('immagine_accesa')['height']; ?>" ng-attr-xlink:href="<?php echo wp_get_attachment_image_src( get_sub_field('immagine_accesa')['ID'], 'full')[0]; ?>" mask="url(#clip_<?php echo $row; ?>)" /></g>
			<image class="light__image" xlink:href="" x="0" y="0" width="<?php echo get_sub_field('immagine_accesa')['width']; ?>" height="<?php echo get_sub_field('immagine_accesa')['height']; ?>" ng-attr-xlink:href="<?php echo wp_get_attachment_image_src( get_sub_field('immagine_accesa')['ID'], 'full')[0]; ?>" />
		</svg>
	</figure>
	<div class="light__cell light__cell--content light__cell--s<?php echo (get_sub_field('foto_verticale')) ? 7 : 5; ?> light__cell--shrink-left-only">
		<div class="light__text light__text--grow-md"><?php _e('Muovi il mouse sopra l’immagine per creare la luce.') ?></div>
	</div>
</div>
<?php endif; ?>