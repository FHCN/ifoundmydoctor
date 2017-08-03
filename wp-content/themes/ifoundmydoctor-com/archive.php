<?php
	get_header();
?>
<div id="main" class="subpage">
	<div class="main-i">
		<div class="page-title">
				<?php if (is_category()) { ?>
					Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category
				<?php } elseif( is_tag() ) { ?>
					Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;
				<?php } elseif( is_tax('date_published') ) { ?>
					<?php
					echo $wp_query->queried_object->name;
					if ($wp_query->queried_object->parent) {
						$parent_term = get_term_by('id', $wp_query->queried_object->parent, 'date_published');
						if ($parent_term) {
							echo ' ' . $parent_term->name;
						}
					}
					?>
				<?php } elseif (is_day()) { ?>
					Archive for <?php the_time('F jS, Y'); ?>
				<?php } elseif (is_month()) { ?>
					Archive for <?php the_time('F, Y'); ?>
				<?php } elseif (is_year()) { ?>
					Archive for <?php the_time('Y'); ?>
				<?php } elseif (is_author()) { ?>
					Author Archive
				<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
					Blog Archives
				<?php } ?>
		</div><!-- /.page-title -->
		<div id="content">
			<?php
				global $page_access;
				$page_access = 'edition';
				if (is_category() || is_tag() || is_day() || is_month() || is_year() || is_author()) {
					$page_access = 'blog';
				}
				ifd_print_the_breadcrumb($page_access);
			?>
			<?php //ifd_print_the_breadcrumb(); ?>
			<div class="article blog-artilces">
		<?php get_template_part('loop'); ?>
			</div>
		</div><!-- end of content -->
	<?php get_sidebar(); ?>
	<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->
<?php get_footer(); ?>
