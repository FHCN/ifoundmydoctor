<?php get_header(); ?>
<div id="main" class="subpage">
	<div class="main-i">
		<div class="page-title">
				<?php _e('Search Results'); ?>
		</div><!-- /.page-title -->
			<?php //ifd_print_the_breadcrumb(); ?>
		<?php get_template_part('loop', 'search'); ?>
	<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->
<?php get_footer(); ?>