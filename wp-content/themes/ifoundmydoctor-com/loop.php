<?php
global $page_access;

if ($page_access == 'blog' || is_search()) :

if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
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
	<?php if (  $wp_query->max_num_pages >= 1 ) : ?>
		<div class="paging">
			<span class="number">Page <?php echo max(1, get_query_var('paged')); ?> of <?php echo $wp_query->max_num_pages; ?></span>
			<div class="all-pages">
			<?php
					$big = 999999999; // This needs to be an unlikely integer
					echo paginate_links(array(
										'base' => str_replace( $big, '%#%', get_pagenum_link($big) ),
										'format' => '?paged=%#%',
										'total' => $wp_query->max_num_pages,
										'current' => max(1, get_query_var('paged')),
										'mid_size' => 5
			)); ?>
			</div><!-- end of all pages -->
		</div>
	<?php endif; ?>
<!-- 	</div>
</div> -->
	<!-- <div class="cl">&nbsp;</div> -->
<?php else : ?>
	<div id="post-0" class="post error404 not-found">
		<h2>No results found. Please try again.</h2>

		<div class="entry">
			<?php get_search_form(); ?>
		</div>
	</div>
<?php endif; ?>

<?php else:
		$term =	$wp_query->queried_object;
		//var_dump(get_query_var('edition_id'));
		//var_dump($_GET['edition_id']);
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args = array( 'post_type' => 'article', 'paged' => $paged, 'numberposts' => -1, 'post_status' => array('publish', 'private', 'inherit') );

		$args['tax_query'] = array(
								'relation'	=>	'AND',
								array(
									'taxonomy'	=>	'date_published',
									'field'	=> 'slug',
									'terms'	=> array($term->slug)
								),
								array(
									'taxonomy'	=>	'edition',
									'field'	=> 'slug',
									'terms'	=> array($_GET['edition_id'])
								)
			);
		$the_query = new WP_Query($args);
		//var_dump($the_query);
		global $more;
		if ($the_query->have_posts()) :
			while ($the_query->have_posts()) : $the_query->the_post(); ?>
		<div class="post">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<?php if ( $post->post_type == 'post' ): ?>
				<small><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></small>
			<?php endif ?>

			<div class="entry">
				<?php $more = 0; echo ifd_get_excerpt_max_charlength($post->post_content, 500); ?>
			</div>

			<?php if ( $post->post_type == 'post' ): ?>
				<p class="postmetadata">
					<?php the_tags(__('Tags: '), ', ', '<br />'); ?>
					<?php _e('Posted in '); the_category(', '); ?> |
					<?php comments_popup_link(__('No Comments'), __('1 Comment'), __('% Comments')); ?>
				</p>
			<?php endif ?>

		</div>
<?php	endwhile; ?>
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
		<?php else: ?>
		<div id="post-0" class="post error404 not-found">
		<h3><?php _e('No results found. Please, try again.'); ?></h3>

		<div class="post">

				<?php get_search_form(); ?>
				<p>Or you can go back to the <a href="<?php echo get_option('home'); ?>">Home page</a>.</p>
			</div>
		</div>
		<?php endif ?>



<?php endif; ?>
