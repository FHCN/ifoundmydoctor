<?php
//defining the globals, used in the filter functions  
global $wpdb;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$final_args['paged'] = $paged;
	$final_args['post_type'] = 'practice';

	$the_query = new WP_query($final_args);

	global $more;

	if ( $the_query->have_posts() ):
		while ($the_query->have_posts()) : $the_query->the_post(); ?>
	<li>
		<?php  
			$temp_post = $post;
			
			
		?>
		
		<h4><?php echo $temp_post->post_title; ?></h4>
		<a href="<?php echo get_permalink($temp_post->ID); ?>" class="result-thumb">
			<?php 
				$thumb = ''; 
				$thumbnail = wp_get_attachment_image_src ( get_post_thumbnail_id ( $temp_post->ID), 'thumbnail' ) ; 
				if (empty($thumbnail)) {
					$thumb = get_bloginfo('stylesheet_directory') . '/images/' . 'no-image.png'; 
				} else {
					$thumb = $thumbnail[0]; 
				}
			?>
	
			<img src="<?php bloginfo('stylesheet_directory'); ?>/scripts/tt.php?src=<?php echo $thumb; ?>&q=100&zc=1&w=102&h=141" /> 
		</a>
		<div class="result-body">
			<p><?php echo ifd_get_excerpt_max_charlength($temp_post->post_content, 650); ?></p>
			<a href="<?php echo get_permalink($temp_post->ID); ?>" class="button"><span>Learn More</span></a>
		</div>
		<div class="cl">&nbsp;</div>
	</li>
	
	<?php endwhile; ?>

	<?php if (  $the_query->max_num_pages > 1 ) : ?>
		<div class="pagination right">
			<span class="number">Page <?php echo max(1, get_query_var('paged')); ?> of <?php echo $the_query->max_num_pages; ?></span>
			<div class="all-pages">
			<?php 
					$big = 999999999; // This needs to be an unlikely integer
					echo paginate_links(array(
										'base' => str_replace( $big, '%#%', get_pagenum_link($big)),
										'format' => '?paged=%#%',
										'total' => $the_query->max_num_pages,
										'current' => max(1, get_query_var('paged')),
										'prev_text' => '< Previous',
										'next_text' => 'Next >',
										'mid_size' => 5	
			)); ?>
			</div><!-- end of all pages -->
		</div>
		<div class="cl">&nbsp;</div>
	<?php endif; ?>
		<div class="cl">&nbsp;</div>
	<?php else : ?>
	<li>
		<h4>No results found. Please try again.</h4>
	</li>
	<?php endif; ?>
	<?php 
		wp_reset_postdata(); 
	?>