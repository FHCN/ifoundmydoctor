<?php
/*
 * Template Name: Search Doctors Layout
 */
	get_header();
	global $post;
	$temp_speciality = isset($_GET['ifd_specialty']) ? $_GET['ifd_specialty'] : 'default';
	$temp_zipcode = isset($_GET['ifd_zipcode']) ? $_GET['ifd_zipcode'] : 'Zip Code';
	$temp_radius = isset($_GET['ifd_radius']) ? $_GET['ifd_radius'] : 'default';
	$temp_keywords = isset($_GET['ifd_keywords']) ? $_GET['ifd_keywords'] : 'Keywords (separate with comma)';
	$geoIpData = FreeGeoIP::fetch();
?>

<div id="main" class="subpage dr-search">
	<div class="main-i">
		<div class="page-title">
			Find <span class="big">MY</span> Doctor Search
		</div><!-- /.page-title -->
		<?php ifd_print_the_breadcrumb(); ?>
		<div class="doctor-form">
		<div class="gform_wrapper">
			<form action="<?php echo get_permalink($post->ID); ?>" method="get" class="search-form" id="search-doctors-form">
				<input type="hidden" name="page_id" value="<?php echo $post->ID; ?>" />
				<input type="hidden" name="ifd_action" value="ifd-search-doctors" />
				<input type="hidden" name="ifd_lat" id="ifd_lat" value="<?php echo $geoIpData->latitude; ?>" />
				<input type="hidden" name="ifd_lng" id="ifd_lng" value="<?php echo $geoIpData->longitude; ?>" />
				<input type="hidden" name="ifd_country_code" id="ifd_country_code" value="<?php echo $geoIpData->country_code; ?>" />

				<div class="cl">&nbsp;</div>
				<div class="gform_heading">
					<h3 class="gform_title">Begin your search</h3>
				</div>
				<?php
					$all_specs = get_terms('speciality', 'hide_empty=0');
					if (is_wp_error($all_specs)) {
						$all_specs = array('Not Avaiable');
					}
 				?>
				<div class="gform_body">
					<ul class="gform_fields">
						<li class="gfield">
							<div class="ginput_container">
								<select class="medium gfield_select" name="ifd_specialty">
									<option value="default">Select Speciality</option>

									<?php
										if ($all_specs)
										{
											$special_class = '';
											foreach ($all_specs as $spec):
												if ($spec->term_id == $temp_speciality) {
													$special_class = 'selected="selected"';
												}
									?>
									<option <?php echo $special_class; ?> value="<?php echo $spec->term_id; ?>"><?php echo $spec->name; ?></option>
									<?php 	$special_class = '';
											endforeach;
										}
									?>
								</select>
							</div><!-- /.ginput_container -->
						</li><!-- /.gfield -->
						<li class="gfield blink-cnt input-text input-text-small">
							<div class="ginput_container">
								<input type="text" class="small" value="<?php echo ($temp_zipcode) ? $temp_zipcode : 'Zip Code'; ?>" title="Zip Code" name="ifd_zipcode" id="ifd_zip" />
							</div><!-- /.ginput_container -->
						</li><!-- /.gfield -->
						<li class="gfield small-field">
							<?php
								$step = get_option('ifd_step');
								$start_level = get_option('ifd_start_level');
								$max_level = get_option('ifd_max_level');
							?>
							<div class="ginput_container">
								<select class="small gfield_select" name="ifd_radius">
									<option value="default">Radius</option>
									<?php
										$special_class = '';
										for ($mile = $start_level; $mile <= $max_level; $mile+=$step) :
											if ($mile == $temp_radius) {
												$special_class = 'selected="selected"';
											}
									?>
									<option <?php echo $special_class; ?> value="<?php echo $mile; ?>"><?php echo $mile; ?> Miles</option>
									<?php
											$special_class = '';
										endfor; ?>
								</select>
							</div><!-- /.ginput_container -->
						</li><!-- /.gfield -->

						<li class="gfield blink-cnt input-text last">
							<div class="ginput_container">
								<input type="text" id="ifd_keywords" class="medium" value="<?php echo ($temp_keywords) ? $temp_keywords : 'Keywords (separate with comma)'; ?>" title="Keywords (separate with comma)" name="ifd_keywords" />
							</div><!-- /.ginput_container -->
						</li><!-- /.gfield -->
					</ul><!-- /.gform_fields -->
				</div><!-- /.gform-body -->
				<div class="gform_footer">
					<input type="submit" value="Submit" class="button" name="btn-search" />
				</div>
			</form>
		</div><!-- /.gform_wrapper -->
		</div><!-- end doctor-form -->
		<ul class="search-entries">
		<?php get_template_part('loop', 'doctors'); ?>
		</ul><!-- /.search-results -->

		<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->

<?php get_footer(); ?>
