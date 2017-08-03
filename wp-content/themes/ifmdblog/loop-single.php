<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">

            <?php /* The loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'content', get_post_format() ); ?>
                <?php twentythirteen_post_nav(); ?>
                <?php comments_template(); ?>

            <?php endwhile; ?>

        </div><!-- #content -->
    </div><!-- #primary -->


<?php endwhile; ?>
<?php endif; ?>
<?php wp_reset_postdata(); ?>

