<?php 
/*
 * Template Name: Blog
 */
	get_header();
?>
<div id="main" class="subpage">
	<div class="main-i">
		<div class="page-title">
			Blog Posts
		</div><!-- /.page-title -->
		<div id="content">
			<?php ifd_print_the_breadcrumb(); ?>
			<div class="article blog-artilces">
		<?php get_template_part('loop', 'posts'); ?>
			</div>
		</div><!-- end of content -->
	<?php get_sidebar(); ?>
	<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->
<?php get_footer(); ?>