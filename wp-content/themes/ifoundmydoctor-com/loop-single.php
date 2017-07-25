<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
	<div class="main-i">
		<div class="page-title">
			<?php the_title(); ?>
			<p class="meta">
				Posted: <?php the_time('F j, Y'); ?>
				<span>Author: <?php echo get_the_author(); ?></span>
			</p>
		</div><!-- /.page-title -->
		<div id="content">
			<?php ifd_print_the_breadcrumb(); ?>
			<div class="blog-post">
				<?php the_content(); ?>
			</div><!-- /.article -->
			<?php comments_template(); ?>
			<ul class="widgets">
				<li class="widget-wide-ad">
					<?php echo get_option('ifc_google_ads'); ?>
				</li>
			</ul>
		</div><!-- /#content -->
		<?php get_sidebar('blog'); ?>
		<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
	
	
<?php endwhile; ?>
<?php endif; ?>
<?php wp_reset_postdata(); ?>

	