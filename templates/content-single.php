<?php while (have_posts()) : the_post(); ?><?php the_content(); ?><div style="display: none">
	<?php var_dump($post->ID, get_previous_post()->ID); ?>
</div><?php endwhile; 
	?>