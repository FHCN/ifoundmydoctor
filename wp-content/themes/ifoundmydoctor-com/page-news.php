<?php 
/*
 * Template Name: News Layout
 */
	get_header();
?>
<div id="main" class="subpage">
	<div class="main-i">
		<div class="page-title">
			News
		</div><!-- /.page-title -->
		<div id="content">
			<?php ifd_print_the_breadcrumb(); ?>
			<div class="article blog-artilces">
		<?php get_template_part('loop', 'news'); ?>
			</div>
		</div><!-- end of content -->
	<?php get_sidebar(); ?>
	<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->
<?php get_footer(); ?>