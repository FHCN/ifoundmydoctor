<?php  
/*
 * Template Name: Home Layout
 */
		get_header(); ?>
<div id="main">
	<div class="main-i">
		<div class="slideshow">
			<ul class="slider">
			<?php  
				$home_slides = get_posts(array(
										'post_type'   => 'article',
										'numberposts' => 8,
										'meta_key'	  => '_featured',
										'meta_value'  => 'featured',
										'order'		  => 'DESC',
										'orderby'	  => 'date'
								));
				foreach ($home_slides as $slide) :
			?>
				<li>
					<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($slide->ID), 'full' );
							$url = $thumb['0']; ?>
					<img src="<?php bloginfo('stylesheet_directory'); ?>/scripts/tt.php?src=<?php echo $url; ?>&w=989&h=359&q=100&zc=1" />
					<div class="slide-info">
						<div class="si-t"></div>
						<div class="si-m">
							<h3><?php echo $slide->post_title; ?></h3>
							<p>
							<?php 
								$preview_text = str_replace( 'h2', 'strong', ifd_get_excerpt_max_charlength(strip_shortcodes($slide->post_content), 250, '<h2>') ); 
								if (!preg_match('~<\/strong>~', $preview_text)) {
									$preview_text .= '</strong>';
								}
								echo $preview_text;
							?>
							</p>

							<a href="<?php echo get_permalink($slide->ID); ?>" class="more">Read More</a>
						</div>
						<div class="si-b"></div>
					</div><!-- /.slide-info -->
					<div class="slider-mask"></div>
				</li>
			<?php endforeach; ?>
			</ul><!-- /.slider -->
			<div class="slider-arrow"></div>

			<ul class="slide-thumbs">
				<?php foreach ($home_slides as $slide): ?>
				<li>
					<a href="#">
						<?php 
							//echo get_the_post_thumbnail($slide->ID, array(93, 57)); 
							$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($slide->ID), 'full' );
							$url = $thumb['0'];
							$mini_thumb = get_post_meta($slide->ID, '_mini_thumb', true);
							if (!empty($mini_thumb)) {
								$url = get_upload_url() . '/' . $mini_thumb;
							}
						?>
						<img src="<?php bloginfo('stylesheet_directory'); ?>/scripts/tt.php?src=<?php echo $url; ?>&w=93&h=57&q=100&zc=1" />

						<!-- <span class="inner-mask"></span> -->
						<span class="outer-mask"></span>
					</a>
				</li>	
				<?php endforeach ?>
			</ul><!-- .thumbs -->
		</div><!-- /.slideshow -->
		<?php get_sidebar('home-top'); ?>
		<?php get_sidebar('home-middle'); ?>
		<?php get_sidebar('home-right');  ?>
		<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->
<?php 	get_footer(); ?>