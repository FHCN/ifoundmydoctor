<?php
/*
 * Template Name: Practices List Layout
 */  
get_header();
global $post;
?>

<div id="main" class="subpage dr-search">
	<div class="main-i">
		<div class="page-title">
			<?php the_title(); ?>
		</div><!-- /.page-title -->
		<?php ifd_print_the_breadcrumb(); ?>

		<ul class="search-entries">
			<?php get_template_part('loop', 'practices'); ?>	
		</ul><!-- /.search-results -->

		<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->

<?php get_footer(); ?>