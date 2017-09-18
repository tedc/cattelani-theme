<div class="<?php echo get_row_layout(); ?> <?php echo get_row_layout(); ?>--grow-lg-top" id="<?php echo get_row_layout(); ?>_<?php echo $row; ?>" ng-class="{'manifesto--active' : isManifestoItem!=false}" ng-init="isManifestoItem = false">
	<div class="manifesto__cover" ng-style="{'background-image':'url(<?php the_sub_field('immagine_di_sfondo_inattiva'); ?>)'}"></div>
	<?php $c= 1; foreach (get_sub_field('immagine_di_sfondo') as $img) : ?>
	<div class="manifesto__cover manifesto__cover--light" ng-class="{'manifesto__cover--light-active' : isManifestoItem == <?php echo $c; ?> && isManifestoItem}" data-cover="<?php echo $c; ?>" ng-style="{'background-image':'url(<?php echo $img['url']; ?>)'}"></div>
	<?php $c++; endforeach; ?>
	<header class="manifesto__header manifesto__header--grow-lg">		
		<h2 class="<?php echo get_row_layout(); ?>__title <?php echo get_row_layout(); ?>__title--big-lighter">
			<?php the_sub_field('titolo_manifesto'); ?>
		</h2>
	</header>
	<ul class="manifesto__list">
		<?php 
		$i = 1;
		$rot = 0;
		$angle = 360 / 8;
	 	while(have_rows('voci')) : the_row(); ?>
		<li class="manifesto__item" data-manifesto-item="<?php echo $i; ?>" ng-class="{'manifesto__item--active' : isManifestoItem == <?php echo $i; ?>}" ng-click="isManifestoItem = (isManifestoItem!=<?php echo $i; ?>) ?  <?php echo $i; ?> : false">
			<div class="manifesto__content">
				<span class="manifesto__line"></span>
				<div class="manifesto__name">
					<?php the_sub_field('voce') ?>
					<div class="manifesto__desc">
						<?php the_sub_field('descrizione'); ?>
					</div>	
				</div>
			</div>
		</li>
		<?php $rot = $rot + $angle; $i++; endwhile; ?>
	</ul>
</div>