<?php  
/*
 * Template Name: Editions Layout
 */
	  get_header(); 
	  the_post();
	  $all_editions = get_terms('edition', 'hide_empty=0');
	  $editions_size = count($all_editions);
	  $editions_counter = 1;
	  $editions_per_column = ceil($editions_size / 2);
?>
<div id="main" class="subpage editions-page">
	<div class="main-i">
		<div class="page-title">
			<?php the_title(); ?>
		</div><!-- /.page-title -->
		<div id="content">
			<?php ifd_print_the_breadcrumb(); ?>
			<div class="article">
				<?php the_content(); ?>
				
				<div class="editions">
					<div class="section">
					<h3>Current Editions</h3>
					<?php
					if ($editions_size) : 
						foreach ($all_editions as $edition) :
							if ($editions_counter == 1) : ?>
							<ul class="col">
					<?php	endif; ?>
							<li><a href="<?php echo get_term_link($edition); ?>"><?php echo $edition->name; ?></a></li>
					<?php   if ($editions_counter == $editions_per_column) : 
								$editions_counter = 0; ?>
							</ul>
					<?php 	endif;
							$editions_counter++;
						endforeach;
					endif;	//end of edition size check 
					?>
						<div class="cl">&nbsp;</div>
					</div><!-- /.current-edit -->
					<div class="cl">&nbsp;</div>
					<div class="section planned-editions">
						<h3>Planned Editions</h3>
						<?php echo wpautop(get_post_meta(get_the_id(), '_planned_editions', true)); ?>
					</div><!-- /.planned-edit -->
				</div><!-- /.editions -->
			</div><!-- /.article -->

			<ul class="widgets">
				<li class="widget-wide-ad">
					<?php echo get_option('ifc_google_ads'); ?>
				</li>
			</ul>
		</div><!-- /#content -->
		<?php get_sidebar(); ?>
		<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->

<?php get_footer(); ?>