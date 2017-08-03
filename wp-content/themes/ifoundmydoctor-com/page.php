<?php get_header(); ?>
<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
<div id="main" class="subpage">
	<div class="main-i">
		<div class="page-title">
			<?php the_title(); ?>
		</div><!-- /.page-title -->
		<div id="content">
			<?php ifd_print_the_breadcrumb(); ?>
			<div class="article">
				<?php the_content(); ?>
			</div><!-- /.article -->
			<ul class="widgets">
				<li class="widget-wide-ad">
					<?php echo get_post_meta(get_the_id(), '_google_ads', true); ?>
				</li>
			</ul>
		</div><!-- /#content -->
		<?php get_sidebar(); ?>
		<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->
	<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>