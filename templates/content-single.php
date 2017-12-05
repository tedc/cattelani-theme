<?php while (have_posts()) : the_post(); ?><?php the_content(); var_dump(get_previous_post()); ?><?php endwhile; 
	?>