<?php if (have_posts()) :
	while (have_posts()) :
	the_post();

	$all_doctors = ifg_get_practice_doctors(get_the_ID(), false);
	$all_doctors = ifg_order_doctors($all_doctors);
	?>
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
				if ($all_doctors) {
					?>
					<div class="specialties top-spacing">
						<h3 class="specs-heading ucwords">Health Care Providers at <?php the_title(); ?></h3>
						<ul>
							<?php
							foreach ($all_doctors as $doc_id) {
								$doc = get_post($doc_id);
								if (!$doc || is_wp_error($doc)) {
									continue;
								}
								echo '<li><a href="' . get_permalink($doc->ID) . '">' . $doc->post_title . '</a></li>';
							}
							?>
						</ul>
					</div>
					<?php
				}
				?>

				<?php
				$all_articles = ifg_get_practice_articles();
				if ($all_articles):
					?>
					<div class="specialties top-spacing">
						<h3 class="specs-heading ucwords">Articles About <?php the_title(); ?></h3>
						<ul>
							<?php foreach ($all_articles as $a_id):
								$article = get_post($a_id);
								if (!$article || is_wp_error($article)) {
									continue;
								}
								?>
								<li><a href="<?php echo get_permalink($article->ID); ?>"><?php echo $article->post_title; ?></a></li>
							<?php endforeach ?>
						</ul>
					</div>
				<?php endif ?>

			</div><!-- /.dr-bio -->

			<ul class="widgets">
				<li class="widget-wide-ad">
					<?php echo get_option('ifc_google_ads'); ?>
				</li>
			</ul>
		</div><!-- /#content -->
		<div id="sidebar">
			<ul class="widgets">
			<?php
			$location = ifg_get_practice_locations(get_the_ID());
			if ($location) {
				$to_populate = array();
				foreach ($location as $loc) {
					$to_populate[] = get_post($loc);
				}
				$location = $to_populate;
			}

			if ($all_doctors || $location): ?>

				<?php
				$prefix = 'cpt_';
				if (!$location) {
					$prefix = '';
					foreach ($all_doctors as $doc_id) {
						$doc_location = get_posts(array(
						    'numberposts'=> -1,
							'post_type'	 => 'doctor',
							'post_parent' => $doc_id,
						    'orderby'    => 'post_date',
						    'order'	 	 => 'ASC'
						));
						if ($doc_location) {
							foreach ($doc_location as $doc_loc) {
								if ( !in_array($doc_loc, $location) ) {
									$location[] = $doc_loc;
								}
							}

						}
					}
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

				if ($locations_number) : ?>
					<li class="widget-dr-info">
						<?php
							$doctor_logo = get_post_meta(get_the_id(), '_practice_logo', true);
							$doctor_logo_url = get_post_meta(get_the_id(), '_practice_logo_url', true);
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
						$facebook_link = get_post_meta(get_the_id(), '_practice_facebook_link', true);
						$twitter_link = get_post_meta(get_the_id(), '_practice_twitter_link', true);
                        $linkedin_link = get_post_meta(get_the_id(), '_practice_linkedin_link', true);
                        $pinterest_link = get_post_meta(get_the_id(), '_practice_pinterest_link', true);
                        $youtube_link = get_post_meta(get_the_id(), '_practice_youtube_link', true);
                        $googleplus_link = get_post_meta(get_the_id(), '_practice_googleplus_link', true);
						$instagram_link = get_post_meta(get_the_id(), '_practice_instagram_link', true);
                        $website_link = get_post_meta(get_the_id(), '_practice_website', true);

                        $hasSocialLinks = !empty($facebook_link) || !empty($twitter_link) || !empty($linkedin_link) ||
                            !empty($pinterest_link) || !empty($youtube_link) || !empty($googleplus_link) || !empty($instagram_link);
                        if ($hasSocialLinks || $website_link) : ?>
							<h4>Find Them On</h4>
							<?php if ($hasSocialLinks): ?>
								<ul class="widgets">
									<li class="widget-social">
                                        <?php renderActiveSocialIcons($facebook_link, $twitter_link, $linkedin_link, $pinterest_link,
                                            $youtube_link, $googleplus_link, $instagram_link);
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
								<?php if ($i % 2 == 0): ?>
									<li class="cl">&nsbp;</li>
								<?php endif ?>
							<?php endfor; ?>
						</ul>
					</li><!-- /.widget-locations -->
					<?php endif ?>
				<?php endif; ?>


			<?php endif ?>
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
