<?php
//defining the globals, used in the filter functions
define('MILE_TO_KM', 1.609344);
define('EARTH_RADIUS', 6371);
global $speciality;
global $radius;
global $zipcode;
global $coords;
global $keywords;
global $lat1, $lon1;
global $wpdb;

/************ FILTER FUNCTIONS ************************************************************/
function ifd_select_filter_distance($select) {
	global $coords;
	global $wpdb;


	$radians = pi()/180;
	$lat_radians = floatval($coords['ltd']) * $radians;
	$lng_degrees = floatval($coords['lng']);

	$select .= ", " . $wpdb->prefix . "doctors_locations.*, (
		(
			ACOS(
				SIN(" . $lat_radians . ") * SIN(" . $wpdb->prefix . "doctors_locations.ltd * " . $radians . ")
				 + COS(" . $lat_radians . ") * COS(" . $wpdb->prefix . "doctors_locations.ltd * " . $radians . ") * COS((" . $lng_degrees . " - " . $wpdb->prefix . "doctors_locations.lng) * " . $radians . ")
			) * 180 / PI()
		) * 60 * 1.1515
	) AS distance";

	return $select;
}


function ifd_join_filter_distance($join) {
	global $wpdb;

	// If a taxonomy was included then there will be an inner join on it. We need to also include the practices so add that
    // to the inner join query. Practices do not have a taxonomy assigned to them, so we will use an or statement.
    /*if (!empty($join)) {
        $join .= " OR (" . $wpdb->posts . ".post_type = 'practice')";
    } */

	// Match the doctor practice id against the post id
    $join .= ' LEFT JOIN ' . $wpdb->postmeta
        . ' AS s_doctor_postmeta ON (s_doctor_postmeta.post_id = ' . $wpdb->posts
        . '.ID AND s_doctor_postmeta.meta_key = "_doctor_select_practice")' . "\n";
	// Match the location id
    $join .= ' LEFT JOIN ' . $wpdb->postmeta
        . ' AS s_location_postmeta ON (s_doctor_postmeta.meta_value = s_location_postmeta.meta_value'
        . ' AND s_location_postmeta.meta_key = "_location_select_practice")' . "\n";
    // Match the practice id
	$join .= ' LEFT JOIN wp_live2_postmeta as s_practice_postmeta ON (s_practice_postmeta.meta_key = "'
        . '_location_select_practice" AND s_practice_postmeta.meta_value=wp_live2_posts.ID)';

	//legacy doctors -> locations
	$join .= '
		LEFT JOIN ' . $wpdb->prefix . 'doctors_locations
			ON (
				(' . $wpdb->prefix . 'doctors_locations.user_id = ' . $wpdb->posts . '.ID)'
				// include the doctor location
                . 'OR (s_location_postmeta.post_id = ' . $wpdb->prefix . 'doctors_locations.location )'
                // include the practice location
                . 'OR (s_practice_postmeta.post_id = wp_live2_doctors_locations.location)'
			. ')' . "\n";

	return $join;
}

function ifd_filter_where($where='') {
    $where = ifd_filter_where_keywords($where);
    $where = ifd_filter_where_distance($where);

    return $where;
}

/**
 * Add where clause based on keywords
 * @param string $where
 * @return string
 */
function ifd_filter_where_keywords($where = '') {
    global $keywords;

    // Set the keywords
    $keywords = array();
    if (!empty($_GET['ifd_keywords']) && $_GET['ifd_keywords'] !== 'Keywords (separate with comma)') {
				$keywords = split_keywords($_GET['ifd_keywords']);
        $numKeywords = count($keywords);

        $where .= "AND (";
        foreach ($keywords as $entry => $keyword) {
						$keyword_regexp = implode('.*', $keyword);

            $where .= "(wp_live2_posts.post_content REGEXP '" . $keyword_regexp . "'";
            $where .= " OR wp_live2_posts.post_title REGEXP '" . $keyword_regexp . "')";
            if ($entry < $numKeywords - 1) {
                $where .= " OR ";
            }
        }
        $where .= ")";
    }

    return $where;
}

/**
 * Add where clause based on distance.
 * @param string $where
 * @return string
 */
function ifd_filter_where_distance($where='') {
	global $radius;
	global $coords;
	global $wpdb;

	$radians = pi()/180;
	$lat_radians = floatval($coords['ltd']) * $radians;
	$lng_degrees = floatval($coords['lng']);

    $where .= "AND
	(
		(
			ACOS(
				SIN(" . $lat_radians . ") * SIN(" . $wpdb->prefix . "doctors_locations.ltd * " . $radians . ")
				 + COS(" . $lat_radians . ") * COS(" . $wpdb->prefix . "doctors_locations.ltd * " . $radians . ") * COS((" . $lng_degrees . " - " . $wpdb->prefix . "doctors_locations.lng) * " . $radians . ")
			) * 180 / PI()
		) * 60 * 1.1515
	) < " . $radius . "
	";

	return $where;
}

function ifd_order_filter_distance($order) {
	$order = ' distance ASC';

	return $order;
}
function ifd_groupby_filter_distance($groupby) {
	global $wpdb;

	if (empty($groupby)) {
		$groupby .= ' ' . $wpdb->prefix . 'posts.ID';
	}

 	return $groupby;
}

function ifd_join_location_keywords($join = '') {
	global $wpdb;

	$join .= ' LEFT JOIN ' . $wpdb->posts . ' AS locations ON ' . $wpdb->posts . '.ID=locations.post_parent ';

	$join .= ' INNER JOIN ' . $wpdb->postmeta . ' AS pm_doc ON pm_doc.post_id = ' . $wpdb->posts . '.ID';
	$join .= ' INNER JOIN ' . $wpdb->postmeta . ' AS pm_loc ON pm_loc.post_id = locations.ID';

	$join .= " LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id LEFT JOIN {$wpdb->term_taxonomy} tt
		  	   ON tt.term_taxonomy_id=tr.term_taxonomy_id
		  	   LEFT JOIN {$wpdb->terms} t ON (t.term_id = tt.term_id)";

	return $join;
}
function ifd_groupby_keywords($groupby = '') {
	global $wpdb;
	$groupby = $wpdb->posts . '.ID';

	return $groupby;
}
/*******************************************************************************************/
	if (($_SERVER['REQUEST_METHOD'] == "GET") && (isset($_GET['ifd_action']) && (isset($_GET['ifd_action']) == "ifd-search-doctors"))) {
		$is_paged = get_query_var('paged');

		$final_args = array();

		//If is set speciality
		if ($_GET['ifd_specialty'] != 'default') {
			$speciality = filter_var($_GET['ifd_specialty'], FILTER_SANITIZE_STRING);
			$tax_args = array(
					'relation'	=>	'AND',
					array(
						'taxonomy'	=>	'speciality',
						'field'		=>	'id',
						'terms'		=>	array($speciality)
						)
			);
			$final_args['tax_query'] = $tax_args;
		}


		//If the zip code is set
		if (!empty($_GET['ifd_zipcode'])) {
			$zipcode = intval($_GET['ifd_zipcode']);
			$radius = '';

			if ($_GET['ifd_radius'] != 'default') {
				$radius = intval($_GET['ifd_radius']);
				if ($radius <= 0 || $radius > 7855) {
					$radius = get_option('ifd_max_level');
				}
			} else {
				$radius = get_option('ifd_max_level');
			}

			//$country_code = filter_var($_GET['ifd_country_code'], FILTER_SANITIZE_STRING);
			$country_code = 'US';
			$coords = ifd_get_zip_coordinates($zipcode, $country_code);

			if (!isset($coords['ltd'])) {
				$coords['ltd'] = 1;
			}
			if (!isset($coords['lng'])) {
				$coords['lng'] = 1;
			}

		} else if ($_GET['ifd_radius'] != 'default') {

			$radius = intval($_GET['ifd_radius']);

			if ($radius <= 0 || $radius > 7855) {
				$radius = get_option('ifd_max_level');
			}

			//If ZipCode Is Not Provided
			$coords['ltd'] = $_GET['ifd_lat'];
			$coords['lng'] = $_GET['ifd_lng'];

		} else {
            // No radius or zip code is specified. In this case, we aren't searching by distance, all we care about
            // are the keywords, but let's grab the maximum radius as the value and search based on the geoip lng
            // and lat.
            $radius = get_option('ifd_max_level');

            $coords['ltd'] = $_GET['ifd_lat'];
            $coords['lng'] = $_GET['ifd_lng'];
        }

        add_filter('posts_fields', 'ifd_select_filter_distance');
        add_filter('posts_join', 'ifd_join_filter_distance');
        add_filter('posts_where', 'ifd_filter_where');
        add_filter('posts_orderby', 'ifd_order_filter_distance');
        add_filter('posts_groupby', 'ifd_groupby_filter_distance');
	}

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$final_args['paged'] = $paged;

    // The doctor's search should include both doctors and practices pages
	$final_args['post_type'] = array('doctor', 'practice');
	if (empty($_GET['ifd_keywords'])) {
		$final_args['post_parent'] = '0';	//since subpages are the locations, show only the parent posts
	}

	$the_query = new WP_query($final_args);
	if ( isset($_GET['htmldebug']) ) {
		echo "<pre>";
		print_r( $the_query );
		exit();
	}
	global $more;

	if ( $the_query->have_posts() ):
		while ($the_query->have_posts()) : $the_query->the_post(); ?>
	<li>
		<?php
			$temp_post = $post;
		?>

		<h4><?php echo $temp_post->post_title; ?></h4>
		<a href="<?php echo get_permalink($temp_post->ID); ?>" class="result-thumb">
			<?php
				$thumb = '';
				$thumbnail = wp_get_attachment_image_src ( get_post_thumbnail_id ( $temp_post->ID), 'thumbnail' ) ;
				if (empty($thumbnail)) {
					$thumb = get_bloginfo('stylesheet_directory') . '/images/' . 'no-image.png';
				} else {
					$thumb = $thumbnail[0];
				}
			?>

			<img src="<?php bloginfo('stylesheet_directory'); ?>/scripts/tt.php?src=<?php echo $thumb; ?>&q=100&zc=1&w=102&h=141" />
		</a>
		<div class="result-body">
			<p><?php echo ifd_get_excerpt_max_charlength($temp_post->post_content, 650); ?></p>
			<a href="<?php echo get_permalink($temp_post->ID); ?>" class="button"><span>Learn More</span></a>
		</div>
		<div class="cl">&nbsp;</div>
	</li>

	<?php endwhile; ?>

	<?php if (  $the_query->max_num_pages > 1 ) : ?>
		<div class="pagination right">
			<span class="number">Page <?php echo max(1, get_query_var('paged')); ?> of <?php echo $the_query->max_num_pages; ?></span>
			<div class="all-pages">
			<?php
					$big = 999999999; // This needs to be an unlikely integer
					echo paginate_links(array(
										'base' => str_replace( $big, '%#%', get_pagenum_link($big)),
										'format' => '?paged=%#%',
										'total' => $the_query->max_num_pages,
										'current' => max(1, get_query_var('paged')),
										'prev_text' => '< Previous',
										'next_text' => 'Next >',
										'mid_size' => 5
			)); ?>
			</div><!-- end of all pages -->
		</div>
		<div class="cl">&nbsp;</div>
	<?php endif; ?>
		<div class="cl">&nbsp;</div>
	<?php else : ?>
	<li>
		<h4>No results found. Please try again.</h4>
	</li>
	<?php endif; ?>
	<?php
		wp_reset_postdata();
	?>
