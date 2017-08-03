<?php
/**
 * The template for displaying Category pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header('blog'); ?>

    <div id="primary" class="content-area">
        <div id="content" class="tag-content" role="main">

            <?php if ( have_posts() ) : ?>
                <header class="archive-header">
                    <h1 class="archive-title"><?php printf( __( 'Tag Archives: %s', 'twentythirteen' ), single_tag_title( '', false ) ); ?></h1>

                    <?php if ( tag_description() ) : // Show an optional category description ?>
                        <div class="archive-meta"><?php echo tag_description(); ?></div>
                    <?php endif; ?>
                </header><!-- .archive-header -->

                <?php /* The loop */ ?>
                <div class="posts-container">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part('post-list', get_post_format()); ?>
                <?php endwhile; ?>
                </div>
                <?php get_sidebar(); ?>
                <?php twentythirteen_paging_nav(); ?>

            <?php else : ?>
                <?php get_template_part( 'content', 'none' ); ?>
            <?php endif; ?>

        </div><!-- #content -->
    </div><!-- #primary -->

<?php get_footer(); ?>