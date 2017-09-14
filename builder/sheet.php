<?php if(have_rows('sheet_items')) : ?>
<section class="sheet sheet--shrink-fw sheet--grow-lg sheet--gray">
	<div class="sheet__container sheet__container--grow-lg">
		<h3 class="sheet__title sheet__title--small-lighter"><?php _e('Scheda tecnica', 'catellani'); ?></h3>
		<?php $count = count(get_field('sheet_items')) - 1; $c = 0; while(have_rows('sheet_items')) : the_row(); ?>
		<div class="sheet__item sheet__item--grow-md<?php echo ($c==$count) ? '-top' : ''; ?> sheet__item--mw <?php echo ($c==$count) ? 'sheet__item--last ' : ''; ?>sheet__item--grid">
			<div class="sheet__cell sheet__cell--shrink-right-only sheet__cell--s4">
				<h4 class="sheet__title sheet__title--light"><?php the_sub_field('sheet_title'); ?></h4>
			</div>
			<div class="sheet__cell sheet__cell--shrink-left-only sheet__cell--s8">
				<?php 
				if(get_sub_field('sheet_links')) :
					echo '<p><a href="'.get_sub_field('sheet_link').'" class="sheet__send" target="_blank"><span>'.get_sub_field('sheet_link_name').'</span></a></p>';
				else :
					if(get_sub_field('cod')):
						$table = trim( get_sub_field('sheet_content'));
						$table = explode('<br />', $table);
						echo '<table class="sheet__table">';
						foreach ($table as $tr) {
							$tr = trim($tr);
							$tr = preg_split('/\s+/', $tr, -1);
							$new_tr = array();
							for ($i = 1; $i < count($tr); $i++) {
								array_push($new_tr, $tr[$i]);
							}
							
							$right = join(' ', $new_tr);
							echo '<tr><td class="sheet__td">'.$tr[0].'</td><td>'.$right.'</td></tr>';
						}
						echo '</table>';
						

					//the_sub_field('sheet_content'); 
					else :
						the_sub_field('sheet_content'); 
					endif;
				endif;
			?>
			</div>
		</div>
		<?php $c++; endwhile; ?>
	</div>
</section>
<?php endif; ?>