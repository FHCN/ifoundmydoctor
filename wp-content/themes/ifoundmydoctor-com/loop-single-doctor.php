<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="main-i">
		<div class="page-title">
			<?php the_title(); ?>
		</div><!-- /.page-title -->
		<div id="content">
			<?php ifd_print_the_breadcrumb(); ?>
			<div class="dr-bio">
				<div class="dr-photo">
					<?php
				$thumb = '';
				$thumbnail = wp_get_attachment_image_src ( get_post_thumbnail_id ( get_the_id() ), 'thumbnail' ) ;
				if (empty($thumbnail)) {
					$thumb = get_bloginfo('stylesheet_directory') . '/images/' . 'no-image.png';
				} else {
					$thumb = $thumbnail[0];
				}
			?>

			<img src="<?php bloginfo('stylesheet_directory'); ?>/scripts/tt.php?src=<?php echo $thumb; ?>&q=100&zc=1&w=96&h=138" />

				</div>
				<div class="bio-body">
					<?php the_content(); ?>
				</div>
				<div class="cl">&nbsp;</div>
			<?php
				$doctor_specialties = wp_get_post_terms(get_the_id(), 'speciality');
				if ($doctor_specialties) :
			?>
				<div class="specialties top-spacing">
					<h3 class="specs-heading">SPECIALTIES</h3>
					<?php
						$max_specs = count($doctor_specialties);
						$spec_per_column = ceil($max_specs / 3);

						$spec_counter = 1;
						foreach ($doctor_specialties as $spec) {
							if ($spec_counter == 1) {
								echo '<ul class="col">';
							}
							if ($spec_counter == ($spec_per_column+1)) {
								echo '</ul><ul class="col">';
								$spec_counter = 1;
							}
							echo '<li>' . $spec->name . '</li>';

							$spec_counter++;
						}
					?>
				</div>
		  <?php endif; ?>
			</div><!-- /.dr-bio -->

			<div class="articles-by">
			<?php
				$all_articles = get_posts(array(
								'post_type'		=>	'article',
								'numberposts'   =>  -1,
								'meta_key'		=>	'_doctors',
								'meta_value'	=>	get_the_id()
							));
			?>
				<h3>Articles About <?php the_title(); ?></h3>
				<ul>
					<?php foreach ($all_articles as $article): ?>
					<li><a href="<?php echo get_permalink($article->ID); ?>"><?php echo $article->post_title; ?></a></li>
					<?php endforeach ?>
				</ul>
			</div>
			<ul class="widgets">
				<li class="widget-wide-ad">
					<?php echo get_option('ifc_google_ads'); ?>
				</li>
			</ul>
		</div><!-- /#content -->
		<div id="sidebar">
			<?php

				$prefix = 'cpt_';
				$practice = get_post_meta(get_the_ID(), '_doctor_select_practice', 1);
				if ($practice) {
					$location = ifg_get_practice_locations($practice);
					if ($location) {
						$to_populate = array();
						foreach ($location as $loc) {
							$to_populate[] = get_post($loc);
						}
						$location = $to_populate;
					}
				}

				if (empty($location)) {
					$prefix = '';
					$location = get_posts(array(
					    'numberposts'=> -1,
						'post_type'	 => 'doctor',
						'post_parent' => get_the_id(),
					    'orderby'    => 'post_date',
					    'order'	 	 => 'ASC'
					));
				}

				$locations_number = count($location);
				$location = ifg_sort_locations_by_name($location);
				if ($location[0]->post_type == 'location') {
					$practice_name = $location[0]->post_title;
				} else {
					$practice_name = get_post_meta($location[0]->ID, '_practice_name', true);
				}
				$street_address = get_post_meta($location[0]->ID, '_location_' . $prefix . 'address', true);
				$city = get_post_meta($location[0]->ID, '_location_' . $prefix . 'city', true);
				$zip = get_post_meta($location[0]->ID, '_location_' . $prefix . 'zip', true);
				$state = get_post_meta($location[0]->ID, '_location_' . $prefix . 'state', true);
				$phone = get_post_meta($location[0]->ID, '_location_' . $prefix . 'telephone', true);
			?>
			<ul class="widgets">
			<?php if ($locations_number) : ?>
				<li class="widget-dr-info">
					<?php
						if ($practice) {
							$doctor_logo = get_post_meta($practice, '_practice_logo', true);
							$doctor_logo_url = get_post_meta($practice, '_practice_logo_url', true);
						} else {
							$doctor_logo = get_post_meta(get_the_id(), '_doctor_logo', true);
							$doctor_logo_url = get_post_meta(get_the_id(), '_doctor_logo_url', true);
						}
						$logo_format = empty($doctor_logo_url) ? '%s': '<a href="%s">%s</a>';

						if (!empty($doctor_logo)) {
							$doctor_logo_dir = get_upload_dir() . '/' . $doctor_logo;
							$doctor_logo = get_upload_url() . '/' . $doctor_logo;
							$logo_size = getimagesize($doctor_logo_dir);	//0 - width, 1 - height

							if ( $doctor_logo_url ) {
								echo '<a href="' . $doctor_logo_url . '">';
							}

							if ($logo_size[0] > 271) {
								echo '<img src="' . get_bloginfo('stylesheet_directory') . '/scripts/tt.php?src=' . $doctor_logo . '&w=271&q=100" />';
							} else {
								echo '<img src="' . $doctor_logo . '" />';
							}

							if ( $doctor_logo_url ) {
								echo '</a>';
							}
						} ?>
					<h4>Location</h4>
					<ul>
						<?php if (!empty($practice_name)): ?>
						<li><?php echo $practice_name; ?></li>
						<?php endif ?>
						<li><?php echo $street_address; ?></li>
						<li><?php echo $city . ', ' . $state . ' ' . $zip; ?></li>
						<li><a href="tel:+<?php echo preg_replace('/[()-.\s]+/', '', $phone); ?>"><?php echo $phone; ?></a></li>
					</ul>

					<?php
					if ($prefix) {
						$facebook_link = get_post_meta($practice, '_practice_facebook_link', true);
						$twitter_link = get_post_meta($practice, '_practice_twitter_link', true);
                        $linkedin_link = get_post_meta($practice, '_practice_linkedin_link', true);
                        $pinterest_link = get_post_meta($practice, '_practice_pinterest_link', true);
                        $youtube_link = get_post_meta($practice, '_practice_youtube_link', true);
                        $googleplus_link = get_post_meta($practice, '_practice_googleplus_link', true);
						$instagram_link = get_post_meta($practice, '_practice_instagram_link', true);
						$website_link = get_post_meta($practice, '_practice_website', true);
					} else {
						$facebook_link = get_post_meta(get_the_id(), '_doctor_facebook_link', true);
						$twitter_link = get_post_meta(get_the_id(), '_doctor_twitter_link', true);
                        $linkedin_link = get_post_meta(get_the_id(), '_doctor_linkedin_link', true);
                        $pinterest_link = get_post_meta(get_the_id(), '_doctor_pinterest_link', true);
                        $youtube_link = get_post_meta(get_the_id(), '_doctor_youtube_link', true);
                        $googleplus_link = get_post_meta(get_the_id(), '_doctor_googleplus_link', true);
						$instagram_link = get_post_meta(get_the_id(), '_doctor_instagram_link', true);
						$website_link = get_post_meta(get_the_id(), '_doctor_website', true);
					}
                    $hasSocialLinks = !empty($facebook_link) || !empty($twitter_link) || !empty($linkedin_link) ||
                        !empty($pinterest_link) || !empty($youtube_link) || !empty($googleplus_link) || !empty($instagram_link);
                    if ($hasSocialLinks || $website_link) :
                    ?>
				<h4>Find Them On</h4>
					<?php if ($hasSocialLinks): ?>
					<ul class="widgets">
						<li class="widget-social">
                            <?php renderActiveSocialIcons($facebook_link, $twitter_link, $linkedin_link,
                                $pinterest_link, $youtube_link, $googleplus_link, $instagram_link);
                            ?>
						</li>
					</ul>
					<?php endif; ?>
					<?php
						endif;
						if (!empty($website_link)): ?>
						<a target="_blank" href="<?php echo esc_attr($website_link); ?>"><?php echo $website_link; ?></a>
					<?php endif ?>
				</li><!-- /.widget-dr-info -->
				<?php if ($locations_number > 1): ?>
				<li class="widget-locations <?php if($locations_number==2) echo 'single-location'; ?>">
					<h3 class="widgettitle">MORE LOCATIONS</h3>
					<ul>
						<?php
						for ($i = 1; $i < $locations_number; $i++) :
							$street_address = get_post_meta($location[$i]->ID, '_location_' . $prefix . 'address', true);
							$city = get_post_meta($location[$i]->ID, '_location_' . $prefix . 'city', true);
							$zip = get_post_meta($location[$i]->ID, '_location_' . $prefix . 'zip', true);
							$state = get_post_meta($location[$i]->ID, '_location_' . $prefix . 'state', true);
							$phone = get_post_meta($location[$i]->ID, '_location_' . $prefix . 'telephone', true);
						?>
							<li>
								<h3 class="widgettitle"><?php echo $city ?></h3>
								<?php echo $street_address; ?>
								<?php echo $city . ', ' . $state . ' ' . $zip; ?><br />
								<a href="tel:+<?php echo preg_replace('/[()-.\s]+/', '', $phone); ?>"><?php echo $phone; ?></a>
							</li>
						<?php endfor; ?>
					</ul>
				</li><!-- /.widget-locations -->
				<?php endif ?>

		<?php endif; ?>
				<li class="widget-square-ad">
					<?php echo get_option('ifc_google_ads_script_square'); ?>
				</li>
			</ul><!-- /.widgets -->

		</div><!-- /#sidebar -->
		<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
<?php endwhile; ?>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
