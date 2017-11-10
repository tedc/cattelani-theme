<?php
	//include( locate_template( 'extras/vendor/autoload.php', false, false) );
	use Dompdf\Dompdf;
	function generatePdf($html, $title) {
		
		$dompdf = new Dompdf();
		$dompdf->set_option('isRemoteEnabled', true);
		$dompdf->set_option('isHtml5ParserEnabled', true);

		$dompdf->loadHtml($html);

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream($title);
	}
	add_action( 'wp_ajax_catellanipdf', 'lamp_pdf', 10, 1 );

	function lamp_pdf() {
		$id = intval($_REQUEST['post_pdf']);
		$html = '';
		$title = '';
		$q = new WP_Query(
			array(
				'post_type' => 'lampade',
				'post__in' => array($id),
				'posts_per_page' => 1
			)
		);
		if($q->have_posts()):
			while($q->have_posts()) : $q->the_post();
				ob_start();
				if(have_rows('sheet_items')) :
					$title = sanitize_title(get_the_title()) . '-' ._('scheda', 'catellani');
					echo '<html><head><link rel="stylesheet" href="'.get_stylesheet_directory_uri().'/assets/styles/pdf.css" /></head><body>';
				?>
				<section class="sheet">
					<div class="sheet__container">
						<table class="sheet__top">
							<tr>
							<td><?php the_title(); ?></td>
							<td class="sheet__top-right"><?php _e('Scheda tecnica', 'catellani'); ?></td>
							</tr>
						</table>
						<table class="sheet__items">
						<?php $count = count(get_field('sheet_items')) - 1; $c = 0; while(have_rows('sheet_items')) : the_row(); ?>		
							<tr>
							<td class="sheet__cell">
								<strong><?php the_sub_field('sheet_title'); ?></strong>
							</td>
							<td class="sheet__cell">
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
										$content = str_replace("<p>", "<table>", $content);
										$content = preg_replace("/(<img[^>]+\>)/i", "<tr><td>$1</td><td>", $content);
										$content = str_replace("</p>", "</td></tr></table>", $content);
										$content = str_replace("<table></table>", "", $content);
										echo $content; 
									endif;
								endif;
							?>
							</td>
							</tr>
						<?php $c++; endwhile; ?>			
						</table>
					</div>
				</section>
				<?php 
				echo '</body></html>';
				$html = ob_get_clean();
				endif; 
			endwhile;
			wp_reset_query();
			wp_reset_postdata();
		endif;
		//die( generatePdf($html, $title) );
		die( $html );
	}