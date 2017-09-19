<?php if(get_field('storia_items', $s->ID)) : ?>
	<div class="anni anni--grow-lg">
	<?php $a = 0; foreach(get_field('storia_items', $s->ID) as $item) : ?>
	<div class="anni__wrapper anni__wrapper--grow-md anni__wrapper--<?php echo ($a%2==0) ? 'even' : 'odd'; ?> anni__wrapper--grid<?php echo ($item['in_evidenza']) ? ' anni__wrapper--full': ''; ?>">
		<div class="anni__cell anni__cell--shrink anni__cell--figure anni__cell--s<?php echo ($item['in_evidenza']) ? 12 : 5; ?>"">
			<figure class="anni__figure">
				<?php echo wp_get_attachment_image( $item['immagine_anno']['ID'], 'large', false, '' ); ?>
			</figure>
		</div>
		<div class="anni__cell anni__cell--content anni__cell--s<?php echo ($item['in_evidenza']) ? 12 : 7; ?>">
			<div class="anni__content anni__content--grow  anni__content--shrink">
				<h4 class="anni__title"><?php echo $item['anno']; ?></h4>
				<div class="anni__text anni__text--grow-md-top"><?php echo $item['testo_anno']; ?>
					<?php if($item['didascalia_anno']) : ?><div class="anni__dida anni__dida--grow-md-top"><span><?php echo $item['didascalia_anno']; ?></span></div><?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php $a++; endforeach; ?>
	</div>
<?php endif; ?>