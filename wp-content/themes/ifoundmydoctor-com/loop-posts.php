<?php 
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
 $args = array( 'post_type' => 'post', 'paged' => $paged, 'numberposts' => -1 );	 
 $old_var = $wp_query;
 $the_query = new WP_Query($args);
 
 global $more;

if ($the_query->have_posts()) : ?>
	<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
		<div class="post">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<?php if ( $post->post_type == 'post' ): ?>
				<small><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></small>
			<?php endif ?>

			<div class="entry">
				<?php $more = 0; the_content(__('Read the rest of this entry &raquo;')); ?>
			</div>
			
			<?php if ( $post->post_type == 'post' ): ?>
				<p class="postmetadata">
					<?php the_tags(__('Tags: '), ', ', '<br />'); ?> 
					<?php _e('Posted in '); the_category(', '); ?> | 
					<?php comments_popup_link(__('No Comments'), __('1 Comment'), __('% Comments')); ?>
				</p>
			<?php endif ?>
		</div>

	<?php endwhile; ?>

	<?php if (  $the_query->max_num_pages > 1 ) : ?>
		<div class="paging">
			<span class="number">Page <?php echo max(1, get_query_var('paged')); ?> of <?php echo $the_query->max_num_pages; ?></span>
			<div class="all-pages">
			<?php 
					$big = 999999999; // This needs to be an unlikely integer
					echo paginate_links(array(
										'base' => str_replace( $big, '%#%', get_pagenum_link($big) ),
										'format' => '?paged=%#%',
										'total' => $the_query->max_num_pages,
										'current' => max(1, get_query_var('paged')),
										'mid_size' => 5	
			)); ?>
			</div><!-- end of all pages -->
		</div>
	<?php endif; ?>
	
<?php else : ?>
	<div id="post-0" class="post error404 not-found">
		<h2>No results found. Please try again.</h2>
		
		<div class="entry">
			<?php  
				if ( is_category() ) { // If this is a category archive
					printf("<p>Sorry, but there aren't any posts in the %s category yet.</p>", single_cat_title('',false));
				} else if ( is_date() ) { // If this is a date archive
					echo("<p>Sorry, but there aren't any posts with this date.</p>");
				} else if ( is_author() ) { // If this is a category archive
					$userdata = get_userdatabylogin(get_query_var('author_name'));
					printf("<p>Sorry, but there aren't any posts by %s yet.</p>", $userdata->display_name);
				} else if ( is_search() ) {
					echo("<p>No posts found. Try a different search?</p>");
				} else {
					echo("<p>No posts found.</p>");
				}
			?>
			<?php get_search_form(); ?>
		</div>
	</div>
<?php endif; ?>
<?php wp_reset_postdata(); ?>