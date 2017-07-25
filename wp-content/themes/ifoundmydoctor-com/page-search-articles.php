<?php  
/*
 * Template Name: Search Articles layout
 */
	get_header(); 
	global $post;
	$temp_edition = '';
	$temp_year = '';
	$temp_quarter = '';
	$temp_keywords = '';
	
	if (($_SERVER['REQUEST_METHOD'] == "GET") && isset($_GET['ifd_action'])) {
		$temp_edition = $_GET['ifd_edition'];
		$temp_year	  = $_GET['ifd_year'];
		$temp_quarter = $_GET['ifd_quarter'];
		$temp_keywords = $_GET['ifd_keywords'];
	}
?>
<div id="main" class="subpage">
	<div class="main-i">
		<div class="page-title">
			ARTICLE Search
		</div><!-- /.page-title -->
		<?php ifd_print_the_breadcrumb(); ?>
		<div class="doctor-form">
		<div class="gform_wrapper">
			<form action="<?php echo get_permalink($post->ID); ?>&" method="get" class="search-form" id="search-form">
				<input type="hidden" name="page_id" value="<?php echo $post->ID; ?>" />
				<input type="hidden" name="ifd_action" value="search_articles" />
				<div class="gform_heading">
					<h3 class="gform_title">Begin your search</h3>
				</div>
				<?php  
					$all_editions = get_terms('edition', array(
															'hide_empty'	=>		false,
														));
				?>
				<div class="gform_body">
					<ul class="gform_fields">
						<li class="gfield">
							<div class="ginput_container">
								<select class="medium gfield_select" name="ifd_edition">
									<option value="edition">Edition</option>
									<?php 
									foreach ($all_editions as $edition): 
										$special_class = '';
										if ($edition->term_id == $temp_edition) {
											$special_class = 'selected="selected"';
										} 
									?>
										<option <?php echo $special_class; ?> value="<?php echo $edition->term_id; ?>"><?php echo $edition->name; ?></option>	
									<?php endforeach ?>
								</select>
							</div><!-- /.ginput_container -->
						</li><!-- /.gfield -->
						<?php $current_year = date('Y'); ?>
						<li class="gfield small-field">
							<div class="ginput_container">
								<select class="small gfield_select" name="ifd_year">
									<option value="year">Year</option>
									<?php 
									for($year_counter = $current_year; $year_counter >= 2012; $year_counter--): 
										$special_class = '';
										if ($temp_year == $year_counter) {
											$special_class = 'selected="selected"';
										}
									?>
										<option <?php echo $special_class; ?> value="<?php echo $year_counter; ?>"><?php echo $year_counter; ?></option>
									<?php endfor; ?>
								</select>
							</div><!-- /.ginput_container -->
						</li><!-- /.gfield -->
						<li class="gfield small-field">
							<?php  
								$seasons = array(
									'quarter' => 'Quarter',
									'winter'  => 'Winter',
									'spring'  => 'Spring',
									'summer'  => 'Summer',
									'fall'   =>  'Fall'
								);
							?>
							<div class="ginput_container">
								<select class="small gfield_select" name="ifd_quarter">
									<?php 
									foreach ($seasons as $key => $value): 
										$special_class = '';
										if ($key == $temp_quarter) {
											$special_class = 'selected="selected"';
										}
									?>
										<option <?php echo $special_class; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>	
									<?php endforeach ?>
								</select>
							</div><!-- /.ginput_container -->
						</li><!-- /.gfield -->
						<li class="gfield blink-cnt input-text last">
							<div class="ginput_container">
								<input type="text" name="ifd_keywords" id="ifd_keywords" class="medium" value="<?php echo (!empty($temp_keywords)) ? $temp_keywords : 'Keywords (separate with comma)'; ?>" title="Keywords (separate with comma)" />
							</div><!-- /.ginput_container -->
						</li><!-- /.gfield -->
					</ul><!-- /.gform_fields -->
				</div><!-- /.gform-body -->
				<div class="gform_footer">
					<input type="submit" value="Submit" class="button" name="ifd_submit" />
				</div>
			</form>
		</div><!-- /.gform_wrapper -->
		</div><!-- end of doctor-form -->
		<ul class="search-entries">
			<?php get_template_part('loop', 'articles'); ?>
		</ul><!-- /.search-results -->
		<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->
<?php get_footer(); ?>