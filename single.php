<?php get_header(); ?>
    <?php $author_name = get_the_author_meta('display_name'); ?>
    <div class="container">
        <?= get_the_author() ?>
    <?php while(have_posts()): the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; ?>
    </div>
<?php get_footer(); ?>