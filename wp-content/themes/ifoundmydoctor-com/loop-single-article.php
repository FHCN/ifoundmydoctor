<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php
	$combined_doctors = get_post_meta(get_the_id(), '_doctors', false);	//returns array with ids of the doctors
    $practice = get_post_meta(get_the_id(), '_practice', 1); // returns the practice if it exists
?>
<div class="main-i">
		<div class="page-title">
			<?php the_title(); ?>
		</div><!-- /.page-title -->
		<div id="content">
			<?php ifd_print_the_breadcrumb('edition'); ?>
			<div class="article">
				<?php the_content(); ?>
				<div class="article-control">
					<?php
						wp_link_pages(array(
									'before'	=> '<ul class="pagination right">',
									'after'		=> '</ul>',
									'link_before'	=> '<li>',
									'link_after'	=> '</li>',
									'nextpagelink'     => __('Next>'),
									'previouspagelink' => __('<Previous')
									));
					?>

					<?php ifd_post_author(); ?>

					<a href="javascript:window.print();"><strong>Print This Article</strong></a>
				</div><!-- /.article-control -->
                <footer class="hippa_footer">
                    <?php echo get_option('article_hippa_footer'); ?>
                </footer>
            </div><!-- /.article -->

			<ul class="widgets">
				<?php
					//$all_coauthors = get_coauthors();
				?>
				<li class="widget-tabs">

					<ul class="tabs-nav">
                        <?php
                        if (!empty($practice)) { ?>
                            <li><a href=""><?php echo get_post($practice)->post_title; ?></a></li>
                        <?php } ?>

						<?php foreach($combined_doctors as $key=>$doctor_id) : ?>
						<li><a href=""><?php echo get_post($doctor_id)->post_title; ?></a></li>
						<?php endforeach; ?>
					</ul>
					<ul class="tabs-content">
						<?php if (!empty($practice)) {
                            $practicePost = get_post($practice);
                        ?>
                            <li>
                                <h4><a href="<?php echo get_permalink($practice); ?>"><?php echo $practicePost->post_title; ?></a></h4>
                                <p><?php echo ifd_get_excerpt_max_charlength($practicePost->post_content, 250); ?> <a href="<?php echo get_permalink($practice); ?>">Read More</a></p>
                            </li>
                        <?php } ?>
                        <?php foreach($combined_doctors as $key=>$doctor_id) :
						    $doctor = get_post($doctor_id);
						?>
						<li>
							<h4><a href="<?php echo get_permalink($doctor_id); ?>"><?php echo $doctor->post_title; ?></a></h4>
							<p><?php echo ifd_get_excerpt_max_charlength($doctor->post_content, 250); ?> <a href="<?php echo get_permalink($doctor_id); ?>">Read More</a></p>
						</li>
						<?php endforeach; ?>
					</ul><!-- /.tabs-content -->
				</li><!-- /.widget-tabs -->
			</ul>
		</div><!-- /#content -->
		<div id="sidebar">
        			<?php
				$temp_doctors = array_values($combined_doctors);
				$first_doctor_id = $temp_doctors[0];

				$prefix = 'cpt_';

                if (empty($practice)) {
				    $practice = get_post_meta($first_doctor_id, '_doctor_select_practice', 1);
                }

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
					    'post_parent'	 => $first_doctor_id,
					    'orderby'    => 'post_date',
					    'order'	 	 => 'ASC'
					));
				}

			?>
				<ul class="widget-dr-info">
					<?php
						if ($practice) {
							$doctor_logo = get_post_meta($practice, '_practice_logo', true);
							$doctor_logo_url = get_post_meta($practice, '_practice_logo_url', true);
						} else {
							$doctor_logo = get_post_meta($first_doctor_id, '_doctor_logo', true);
							$doctor_logo_url = get_post_meta($first_doctor_id, '_doctor_logo_url', true);
						}
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
								?>
								<img src="<?php echo $doctor_logo; ?>" />
								<?php
							}

							if ( $doctor_logo_url ) {
								echo '</a>';
							}

						}

						$locations_number = count($location);
						$location = ifg_sort_locations_by_name($location);
						if ($location[0]->post_type == 'location') {
							$practice_name = $location[0]->post_title;
						} else {
							$practice_name = get_post_meta($first_doctor_id, '_practice_name', true);
						}
						$street_address = get_post_meta($location[0]->ID, '_location_' . $prefix . 'address', true);
						$city = get_post_meta($location[0]->ID, '_location_' . $prefix . 'city', true);
						$zip = get_post_meta($location[0]->ID, '_location_' . $prefix . 'zip', true);
						$state = get_post_meta($location[0]->ID, '_location_' . $prefix . 'state', true);
						$phone = get_post_meta($location[0]->ID, '_location_' . $prefix . 'telephone', true);
					?>
					<h4>Location</h4>
					<ul class="location-info">
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
							$facebook_link = get_post_meta($first_doctor_id, '_doctor_facebook_link', true);
							$twitter_link = get_post_meta($first_doctor_id, '_doctor_twitter_link', true);
                            $linkedin_link = get_post_meta($first_doctor_id, '_doctor_linkedin_link', true);
                            $pinterest_link = get_post_meta($first_doctor_id, '_doctor_pinterest_link', true);
                            $youtube_link = get_post_meta($first_doctor_id, '_doctor_youtube_link', true);
                            $googleplus_link = get_post_meta($first_doctor_id, '_doctor_googleplus_link', true);
							$instagram_link = get_post_meta($first_doctor_id, '_doctor_instagram_link', true);
							$website_link = get_post_meta($first_doctor_id, '_doctor_website', true);
						}
                        $hasSocialLinks = !empty($facebook_link) || !empty($twitter_link) || !empty($linkedin_link) ||
                            !empty($pinterest_link) || !empty($youtube_link) || !empty($googleplus_link);
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
				<?php endif ?>

					<?php
						endif;
						if (!empty($website_link)): ?>
						<a href="<?php echo esc_attr($website_link); ?>"><?php echo $website_link; ?></a>
				<?php endif ?>
				</ul><!-- /.widget-dr-info -->
			<ul class="widgets">
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
				<li class="widget-square-ad">
					<ul>
						<li><?php echo get_option('ifc_google_ads_script_square'); ?></li>
					</ul>
				</li>

				<?php
					$article_editions = wp_get_post_terms(get_the_id(), 'edition');
					if ($article_editions) :
				?>
				<li class="widget-editions">
					<h3 class="widgettitle">EDITIONS</h3>
					<strong>This article was published in the following editions:</strong>

					<ul>
						<?php foreach ($article_editions as $edition) : ?>
						<li><a href="<?php echo get_term_link($edition); ?>"><?php echo $edition->name; ?></a></li>
						<?php endforeach; ?>
					</ul>
				</li><!-- /.widget-editions -->
			<?php endif; ?>
			<?php


				$all_articles   = get_posts(array(
									'post_type'		=>		'article',
									'numberposts'	=>		-1,
									'orderby'	    =>		'post_date',
									'order'			=>		'DESC'
								));


				$found_articles = array();
				$author_name = '';
				foreach ($combined_doctors as $key => $author_id) {
					foreach ($all_articles as $article) {
						if ($article->ID != get_the_id())
						{
						 	$temp_array = get_post_meta($article->ID, '_doctors', false);
						 	if (in_array($author_id, $temp_array)) {
						 		$found_articles[] = $article;
						 	}
						 	unset($temp_array);
						}
					 }
					 if (count($found_articles)) {
					 	$author_name = get_post($author_id)->post_title;
					 	break; break;
					 }
				}

				//the doctors have a particular order
				// if (count($doctor_orders)) {

				//}
			?>
				<?php if ($found_articles): ?>
					<li class="widget-own-articles">

						<h3 class="widgettitle">ARTICLES ABOUT THIS DOCTOR</h3>

						<ul>
							<?php $found_articles_counter = 0; ?>
							<?php foreach($found_articles as $doctor_article) : ?>
							<li>
								<?php if ($found_articles_counter == 5) break; ?>
								<h4><?php echo $doctor_article->post_title; ?></h4>
								<?php echo ifd_get_excerpt_max_charlength($doctor_article->post_content, 140); ?> <a href="<?php echo get_permalink($doctor_article->ID); ?>">Read More</a>
							</li>
							<?php 	$found_articles_counter++;
								  endforeach;
							?>
						</ul>
					</li><!-- /.widget-own-articles -->
				<?php endif ?>

				<li class="widget-square-ad last">
					<ul>
						<li><?php echo get_option('ifc_google_ads_script_skyscraper'); ?></li>
					</ul>
				</li>
			</ul><!-- /.widgets -->

		</div><!-- /#sidebar -->
		<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
<?php endwhile; ?>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
