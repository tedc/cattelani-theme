<?php if(have_rows('sheet_items')) : ?>
<section class="sheet sheet--shrink-fw sheet--grow-md sheet--gray">
	<div class="sheet__container sheet__container--grow-md">
		<h3 class="sheet__title sheet__title--small-lighter"><?php _e('Scheda tecnica', 'catellani'); ?></h3>
		<?php $count = count(get_field('sheet_items')) - 1; $c = 0; while(have_rows('sheet_items')) : the_row(); ?>
		<div class="sheet__item sheet__item--grow-md<?php echo ($c==$count) ? '-top' : ''; ?> sheet__item--mw <?php echo ($c==$count) ? 'sheet__item--last ' : ''; ?>sheet__item--grid">
			<div class="sheet__cell sheet__cell--shrink-right-only sheet__cell--s4">
				<h4 class="sheet__title sheet__title--light"><?php the_sub_field('sheet_title'); ?></h4>
			</div>
			<div class="sheet__cell sheet__cell--shrink-left-only sheet__cell--s8">
				<?php 
				if(get_sub_field('sheet_links')) :
					foreach (get_sub_field('sheet_links') as $link) : ?>
					<p><a href="<?php echo $link['sheet_link']; ?>" class="sheet__send" target="_blank"><span><?php echo $link['sheet_link_name']; ?></span></a></p>
				<?php endforeach;
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
						$content_str = (!get_sub_field('is_size')) ? 'sheet_content' : 'sheet_content_size';
						$content = get_sub_field($content_str);
						$content = strip_tags($content, '<img><p><br><strong>');
						$content = preg_replace("/(<img[^>]+\>)/i", "$1<span>", $content);
						$content = str_replace("</p>", "</span></p>", $content);
						echo $content; 
					endif;
				endif;
			?>
			</div>
		</div>
		<?php $c++; endwhile; ?>
		<div class="sheet__pdf sheet__pdf--mw sheet__pdf--grow-md-top" download-form><form ng-submit="download(<?php the_ID(); ?>)"><button class="sheet__send"><?php _e('Scarica la scheda tecnica', 'catellani'); ?></button></form></div>
	</div>
</section>
<?php endif; ?>