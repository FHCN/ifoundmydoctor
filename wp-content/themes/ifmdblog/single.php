<?php get_header('blog'); ?>
    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">
            <p><?php echo get_post_format();?></p>
            <?php /* The loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="blog-content">
                    <?php get_template_part( 'content', get_post_format() ); ?>
                    <?php get_sidebar('blog'); ?>
                </div>
                <div class="clearfix"></div>
                <?php twentythirteen_post_nav(); ?>
                <?php comments_template(); ?>
            <?php endwhile; ?>

        </div><!-- #content -->
    </div><!-- #primary -->
<?php get_footer();