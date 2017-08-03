<div class="post">
    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
    <?php if ( $post->post_type == 'post' ): ?>
        <small>
            <?php the_time('F jS, Y') ?> <?php get_the_author() ?>
            <?php $doctorAuthor = get_post_meta($post->ID, '_doctor_author', true);
            if (!empty($doctorAuthor)) {
                $authorPost = get_post($doctorAuthor);
                echo ' - <a href="' . $authorPost->guid . '">' . $authorPost->post_title . '</a>';
            }?>
        </small>
    <?php endif ?>

    <div class="entry">
        <?php if (has_post_thumbnail()) { ?>
            <div class="alignleft featured-image">
                <a href="<?php echo get_permalink(get_the_id()); ?>" class="result-thumb"><?php the_post_thumbnail(array(100, 100)); ?></a>
            </div>
        <?php }

        add_filter('the_content', 'strip_images', 2);
        echo strip_shortcodes(wp_trim_words( get_the_content(), 80));
        ?>
        <br />
        <div class="text-right">
            <a href="<?php the_permalink(); ?>">Read more</a>
        </div>
    </div>

    <?php if ( $post->post_type == 'post' ): ?>
        <p class="postmetadata">
            <?php the_tags(__('Tags: '), ', ', '<br />'); ?>
            <?php _e('Posted in '); the_category(', '); ?> |
            <?php comments_popup_link(__('No Comments'), __('1 Comment'), __('% Comments')); ?>
        </p>
    <?php endif ?>
    <hr />
</div>