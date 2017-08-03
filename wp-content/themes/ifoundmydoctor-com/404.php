<?php get_header(); ?>
<div id="main" class="subpage">
	<div class="main-i">
		<div class="page-title">
				<?php _e('Error 404 - Not Found'); ?>
		</div><!-- /.page-title -->
		<div id="content">
			<?php //ifd_print_the_breadcrumb(); ?>
			<div class="article blog-artilces">
			<p><?php printf(__('Please check the URL for proper spelling and capitalization. If you\'re having trouble locating a destination, try visiting the <a href="%1$s">home page</a>'), get_option('home')); ?></p>
			<?php get_search_form(); ?>
			</div>
		</div><!-- end of content -->
	<?php get_sidebar(); ?>
	<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->
<?php get_footer(); ?>