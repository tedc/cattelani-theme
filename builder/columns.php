<div class="section section--shrink-fw section--grid section--grow-lg<?php echo (get_sub_field('column_right')) ? ' section--end' : ''; ?><?php echo (get_sub_field('background')) ? ' section'.get_sub_field('background') : ''; ?>"<?php scrollmagic('"triggerHook":0.45,"class":"section--active","reverse":false'); ?>>
<?php 
$col = 0;
while(have_rows('column')) : the_row('column'); 
$size = (get_sub_field('size') > 0) ? ' section__cell--s'.get_sub_field('size') : '';
$style = (get_sub_field('size') == 0) ? '<style>@media screen and (min-width:'.(850/16).'em){#col_'.$col.'_'.$row.' { width : '.get_sub_field('col_size').'%}}</style>' : '';
echo $style;
?>
	<div class="section__cell section__cell--<?php echo get_row_layout(); ?> section__cell--<?php echo ($col%2==0) ? 'even' : 'odd'; echo $size; if(get_sub_field('align')): echo ' section__cell-'. get_sub_field('size').'-align-'.get_sub_field('align'); endif;?>" id="col_<?php echo $col; ?>_<?php echo $row; ?>">
	<?php 
		include( locate_template( 'builder/columns/'.get_row_layout().'.php', false, true ) );
	?>
	</div>
<?php 
$col++; endwhile; ?>
</div>