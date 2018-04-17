<?php while (have_posts()) : the_post(); echo 'prova'; ?>
  <?php get_template_part('templates/content', 'page');?>
<?php endwhile; ?>
