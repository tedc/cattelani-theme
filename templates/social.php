<?php if(have_rows('social', 'options')) : ?>
<p class="social">
<?php while(have_rows('social', 'options')) : the_row(); ?>
    <a href="<?php the_sub_field('link'); ?>" class="icon-<?php echo strtolower(get_sub_field('nome')); ?>" targer="_blank"></a>
<?php endwhile; ?>
</p>
<?php endif; ?>