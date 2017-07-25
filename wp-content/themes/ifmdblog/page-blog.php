<?php
/*
 * Template Name: Blog
 */
get_header('blog');
?>
    <div id="primary" class="content-area">
        <header class="entry-header"><h1>The Waiting Room</h1></header>
        <!-- /.page-title -->
        <div id="content" class="entry-content">
            <?php get_template_part('loop', 'posts'); ?>
        </div>
        <!-- end of content -->
        <?php get_sidebar(); ?>
    </div><!-- /#primary -->
<?php get_footer(); ?>