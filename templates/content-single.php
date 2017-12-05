<?php while (have_posts()) : the_post(); ?><?php the_content(); ?><?php endwhile; var_dump(get_previous_post());
	?>