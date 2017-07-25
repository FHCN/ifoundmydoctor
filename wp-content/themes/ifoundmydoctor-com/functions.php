<?php

function theme_init_theme() {
	# Enqueue jQuery
	wp_enqueue_script('jquery');


	if (is_admin()) { /* Front end scripts and styles won't be included in admin area */
		wp_enqueue_script('ifc-practice-admin', get_bloginfo('stylesheet_directory') . '/js/practice-admin.js');
		wp_enqueue_script('bootstrap', get_bloginfo('stylesheet_directory') . '/js/bootstrap.min.js');
		wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/bootstrap.min.css');
		wp_enqueue_style('bootstrap-theme-css', get_template_directory_uri() . '/bootstrap-theme.min.css');
		return;
	}
	# Enqueue Custom Scripts
	# @wp_enqueue_script attributes -- id, location, dependancies, version
	wp_enqueue_script('ifc-caroufredsel', get_bloginfo('stylesheet_directory') . '/js/jquery.carouFredSel-5.6.1-packed.js');
	wp_enqueue_script('ifc-c2selectbox', get_bloginfo('stylesheet_directory') . '/js/jquery.c2selectbox.js');
	wp_enqueue_script('ifc-mousewheel', get_bloginfo('stylesheet_directory') . '/js/jquery.mousewheel.js');
	wp_enqueue_script('ifc-jscrollpane', get_bloginfo('stylesheet_directory') . '/js/jquery.jscrollpane.min.js');
	wp_enqueue_script('ifc-functions', get_bloginfo('stylesheet_directory') . '/js/functions.js');
    wp_enqueue_script('ifc-global-search', get_bloginfo('stylesheet_directory') . '/js/global-search.js');
}
add_action('init', 'theme_init_theme');


add_action('after_setup_theme', 'theme_setup_theme');

# To override theme setup process in a child theme, add your own theme_setup_theme() to your child theme's
# functions.php file.
if ( !function_exists( 'theme_setup_theme' ) ):
function theme_setup_theme() {
	include_once('lib/common.php');

	# Theme supports
	add_theme_support('automatic-feed-links');

	# Manually select Post Formats to be supported - http://codex.wordpress.org/Post_Formats
	// add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

	# Register Theme Menu Locations

	add_theme_support('menus');
	register_nav_menus(array(
		'header-menu'=>__('Header Menu'),
		// 'footer-menu'=>__('Footer Menu'),
		'footer-bottom-menu' => __('Bottom Footer Menu')
	));

	# Add Thumbnails Theme Support
	add_theme_support( 'post-thumbnails' );

	# Register CPTs
	include_once('options/theme-post-types.php');

	# Attach custom widgets
	include_once('lib/custom-widgets/widgets.php');
	include_once('options/theme-widgets.php');

	# Add Custom Menu Walkers
	include_once('options/theme-menu-walkers.php');

	# Include Custom User Meta
	//include_once('lib/custom_user_meta.php');

	# Add Actions
	add_action('widgets_init', 'theme_widgets_init');
	add_action('wp_loaded', 'attach_theme_options');
	add_action('wp_head', '_print_ie6_styles');

	# Add Filters



}
endif;

# Register Sidebars
# Note: In a child theme with custom theme_setup_theme() this function is not hooked to widgets_init
function theme_widgets_init() {
	//Default Sidebar
	register_sidebar(array(
		'name' => 'Default Sidebar',
		'id' => 'default-sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>',
	));
	//Default Blog Sidebar
	register_sidebar(array(
		'name' => 'Default Blog Sidebar',
		'id' => 'default-blog-sidebar',
		'before_widget' => '<li id="%1$s" class="widget-blog %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>',
	));
	//Home Top Sidebar
	register_sidebar(array(
		'name' => 'Home Top Widget Area',
		'id' => 'home-top-widget-area',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>',
	));
	//Sub Page Top Sidebar
	register_sidebar(array(
		'name' => 'Subpage Top Sidebar',
		'id' => 'subpage-top-widget-area',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>',
	));
	//Home Middle Sidebar
	register_sidebar(array(
		'name' => 'Home Middle Widget Area',
		'id' => 'home-middle-widget-area',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>',
	));
	//Home Right Sidebar
	register_sidebar(array(
		'name' => 'Home Right Widget Area',
		'id' => 'home-right-widget-area',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>',
	));
	//Footer Widget Area
	register_sidebar(array(
		'name' => 'Footer Widget Area',
		'id' => 'footer-widget-area',
		'before_widget' => '<li id="%1$s" class="widget-footer-nav %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>',
	));
}

function attach_theme_options() {
	# Attach theme options
	include_once('lib/theme-options/theme-options.php');

	include_once('options/theme-options.php');
	// include_once('options/other-options.php');

	# Theme Help needs to be after options/theme-options.php
	include_once('lib/theme-options/theme-readme.php');

	# Attach ECFs
	include_once('lib/enhanced-custom-fields/enhanced-custom-fields.php');
	include_once('options/theme-custom-fields.php');

}

/* Custom code goes below this line. */
//Get Breadcrumbs
function ifd_get_the_breadcrumb($page_type = '') {
	global $post;
	global $wp_query;
	$result_array = array();
	$term =	$wp_query->queried_object;
	/*if (!$post) {
		return $result_array;
	}*/
	// echo '<pre>';
	// var_dump($term);
	if ($post) {
		$parent_id = $post->post_parent;
	}


	if ($post && $post->post_type == 'doctor') {
		$pages = get_pages(array(
					'meta_key' => '_wp_page_template',
					'meta_value' => 'page-search-doctors.php'
			));
		if (!empty($pages)) {
			$result_array[] = '<a href="' . get_permalink($pages[0]->ID) . '">Doctors</a>';
		}
	}
	if ($post && $post->post_type == 'article') {
		$pages = get_pages(array(
					'meta_key' => '_wp_page_template',
					'meta_value' => 'page-search-articles.php'
			));
		if (!empty($pages)) {
			$result_array[] = '<a href="' . get_permalink($pages[0]->ID) . '">Articles</a>';
		}
	}

	if ($post && $post->post_type == 'practice') {
		$pages = get_pages(array(
					'meta_key' => '_wp_page_template',
					'meta_value' => 'page-practices-list.php'
			));
		if (!empty($pages)) {
			$result_array[] = '<a href="' . get_permalink($pages[0]->ID) . '">Practices</a>';
		}
	}

	if (ifd_is_term($term)) {

		if ($page_type == 'blog') {
			$pages = get_pages(array(
					'meta_key' => '_wp_page_template',
					'meta_value' => 'page-blog.php'
			));
			$result_array[] = '<a href="' . get_permalink($pages[0]->ID) . '">' . $pages[0]->post_title . '</a>';
			$result_array[] = '<a href="' . get_term_link($term) . '">' . $term->name . '</a>';

		} else {
			$pages = get_pages(array(
					'meta_key' => '_wp_page_template',
					'meta_value' => 'page-editions.php'
			));

			$result_array[] = '<a href="' . get_permalink($pages[0]->ID) . '">' . $pages[0]->post_title . '</a>';	//adding the current page
			$custom_term = $term;
			if ($post && is_wp_error($custom_term)) {
				$post_terms = wp_get_post_terms($post->ID, 'edition');
				$custom_term = $post_terms[0];

			}

			if (isset($_GET['edition_id'])) {
				$custom_term = get_term_by('slug', $_GET['edition_id'], 'edition');
			}
			$result_array[] = '<a href="' . get_term_link($custom_term) . '">' . $custom_term->name . '</a>';
		}

		if ($term->parent != "0") {
			//$result_array[] = '<a href="' . get_term_link($term) . '">' . $term->name . '</a>';
			$result_array[] = '<span>' . $term->name . '</span>';

			do {
				$parent = get_term($term->parent,'date_published');
				$url = get_term_link($parent);
				if (isset($_GET['edition_id'])) {
					add_query_arg('edition_id', $_GET['edition_id'], $url);
				}

				$result_array[] = '<a href="' . $url . '">' . $parent->name . '</a>';
				$parent_id = $parent->parent;
			} while(!is_wp_error($parent) && $parent_id != "0");
		}
		if (!$post) {
			array_unshift($result_array, '<a href="' . get_option('home') . '" title="Home">Home</a>	' );
		}

	} else {
		if ($post->post_type == 'post') {
			$pages = get_pages(array(
					'meta_key' => '_wp_page_template',
					'meta_value' => 'page-blog.php'
			));
			$result_array[] = '<a href="' . get_permalink($pages[0]->ID) . '">' . $pages[0]->post_title . '</a>';

			$post_categories  = get_the_category($post->ID);
			if (!empty($post_categories)) {

					$result_array[] = '<a href="' . get_term_link($post_categories[0]) . '">' . $post_categories[0]->cat_name . '</a>';

			}
			/*
			if ((count($result_array) == 0) && (count($post_categories))) {
				$result_array[] = '<a href="' . get_permalink($post_categories[0]->cat_ID) . '">' . $post_categories[0]->cat_name . '</a>';
			}*/
		} else {
			if ( $parent_id ) {	//if it has parent
				$parent = get_post($parent_id);
				$temp_buffer = array();

				do {
					$temp_buffer[] = '<a href="' . get_permalink($parent->ID) . '">' . $parent->post_title . '</a>';
					$parent_id = $parent->post_parent;
					$parent = get_post($parent->post_parent);
				} while( $parent && $parent_id > 0 );

				$result_array = array_merge($result_array, array_reverse($temp_buffer));

			}
		}
		$result_array[] = '<a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>';	//if there is only one parent
	}



	if ($post) {
		array_unshift($result_array, '<a href="' . get_option('home') . '" title="' . esc_attr($post->post_title) . '">Home</a>	' );
	}

	return $result_array;
}
function ifd_print_the_breadcrumb($page_type = '') {
	$breadcrumbs = ifd_get_the_breadcrumb($page_type);
	if (count($breadcrumbs)) {
?>
	<ul class="breadcrumbs">
<?php
	foreach ($breadcrumbs as $breadcrumb) : ?>
		<li><?php echo $breadcrumb; ?></li>
<?php
	endforeach;
?>
	</ul>
<?php
	} // end of the if
}
//Get the excerpt
function ifd_get_excerpt_max_charlength($old_excerpt, $charlength, $allowed_tags = '')
{
	// Make sure to run the excerpt through the shortcode filter or you will see shortcodes
	$excerpt = do_shortcode(strip_tags($old_excerpt, $allowed_tags));

	$charlength++;

	if (mb_strlen($excerpt) > $charlength) {
		$subex = mb_substr($excerpt, 0, $charlength - 5);
		$exwords = explode(' ', $subex);
		$excut = -(mb_strlen($exwords[count($exwords) - 2]));
		if ($excut < 0) {
			$excerpt = mb_substr($subex, 0, $excut);
		} else {
			$excerpt = $subex;
		}

		$excerpt .= '...';
	} else {
		$excerpt .= '...';
	}

	return $excerpt;
}

function ifd_get_season($month_number) {
	/*** northern hemisphere seasons ***/
    $northern_seasons = array(
	    'Summer' => array(6, 7, 8),
	    'Fall' => array(9, 10, 11),
	    'Winter' => array(12, 1, 2),
	    'Spring' => array(3, 4, 5)
    );
    foreach ($northern_seasons as $season_name => $season_months) {
    	if (in_array($month_number, $season_months)) {
    		return $season_name;
    	}
    }
    return NULL;
}
function ifd_word_count() {
	global $post;
	$content = get_the_content($post->ID);
	$count = str_word_count(strip_tags($content));

	return $count;
}
function pagination($pages = '', $range = 4)
{
     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }

     if(1 != $pages)
     {

         echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</div>\n";
     }
}
function ifd_is_chached_zipcode($zipcode, $countrycode, &$coords) {
	global $wpdb;

	$query = "SELECT * FROM wp_ifd_zip_codes WHERE zip='" . $zipcode . "' AND countrycode='" . $countrycode . "'";
	$query_results = $wpdb->get_results($query);

	if ($query_results) {
		$coords['ltd'] = $query_results[0]->ltd;
		$coords['lng'] = $query_results[0]->lng;

		return true;
	} else {
		return false;
	}
}
function ifd_save_zipcode($zipcode, $coords, $countrycode) {
	global $wpdb;

	if (empty($countrycode)) {
		return false;
	}
	$query = "INSERT INTO wp_ifd_zip_codes(id, countrycode, zip, ltd, lng) VALUES('', '" . $countrycode  . "','" . $zipcode . "', '" . $coords['ltd'] . "','" . $coords['lng'] . "')";
	$query_results = $wpdb->get_results($query);

	if ($query_results) {
		return true;
	} else {
		return false;
	}
}

function _ifd_get_coordinates($zipcode, $country_code) {
	$query = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($zipcode . ' ' . $country_code) . '&sensor=false';
	$query_results = json_decode(file_get_contents($query));

	$coords = array();
	if (!empty($query_results->results)) {
		foreach ($query_results->results as $res) {
			$coords['lng'] = $res->geometry->location->lng;
			$coords['ltd'] = $res->geometry->location->lat;
			break;
		}
	}


	return $coords;
}

function ifd_get_zip_coordinates($zipcode, $country_code) {
	$coords = array();
	if (ifd_is_chached_zipcode($zipcode,$country_code, $coords)) {

		return $coords;
	} else {
		$coords = _ifd_get_coordinates($zipcode, $country_code);
		ifd_save_zipcode($zipcode, $coords, $country_code);
		return $coords;
	}
}

function ifd_is_term($term) {
	if (isset($term->term_id)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function ifd_doctor_save($post_id) {
	global $wpdb;
	$temp_post = get_post($post_id);
	if ($temp_post->post_type == 'doctor' && $temp_post->post_parent != "0") {	//update
		$address = get_post_meta($temp_post->ID, '_location_address', true);
		$city = get_post_meta($temp_post->ID, '_location_city', true);
		$zip = get_post_meta($temp_post->ID, '_location_zip', true);
		$state = get_post_meta($temp_post->ID, '_location_state', true);

		// TODO: Use arrays, implode() and array_filter()
		if (!empty($address) || !empty($city) || !empty($state) || !empty($zip)) {	//if at least one of the fields is not empty
			$query_str = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address . ' ' . $city . ' ' . $state . ' ' . $zip) . '&sensor=false';
			$query_results = json_decode(file_get_contents($query_str));

			$coords = array();
			if (!empty($query_results->results)) {
				foreach ($query_results->results as $res) {
					$coords['lng'] = $res->geometry->location->lng;
					$coords['ltd'] = $res->geometry->location->lat;
					break;
				}
			}

			$query = "SELECT * FROM {$wpdb->prefix}doctors_locations WHERE location='" . $post_id . "'";
			$db_query_results = $wpdb->get_results($query);
			if (count($db_query_results)) {
				$query = "UPDATE {$wpdb->prefix}doctors_locations SET {$wpdb->prefix}doctors_locations.lng='" . $coords['lng'] . "', {$wpdb->prefix}doctors_locations.ltd='" . $coords['ltd'] . "' WHERE location='" . $post_id . "'";
			} else {
				$query = "INSERT INTO {$wpdb->prefix}doctors_locations(id,user_id,lng,ltd, location)
							VALUES(''," . $temp_post->post_parent . ", '" . $coords['lng'] . "','" . $coords['ltd'] . "', '" . $post_id . "')";
			}
			$db_query_results = $wpdb->get_results($query);
		}
	}
}
add_action('save_post', 'ifd_doctor_save');



function ifd_filter_general_search( $query ) {
	// general search
    if ( $query->is_search() && !is_admin() ) {
        $query->set( 'post_parent', '0' );
    }

    // sitemap
    if ( is_page('sitemap') && $query->query_vars['post_type'] == 'doctor' ) {
    	$query->set( 'post_parent', '0' );
    }

    //author template
    if ( is_author() && $query->is_main_query() && !is_admin() ) {
    	$query->set('post_type', array('article', 'post'));
    }
}

add_action( 'pre_get_posts', 'ifd_filter_general_search' );

function ifd_relevanssi_filter_general_search($where) {
	// echo "<pre>";
	// var_dump($where);
	// exit();
	$where = str_replace('WHERE post_type IN', 'WHERE post_parent=0  AND post_type IN', $where);
	return $where;
}
add_action( 'relevanssi_where', 'ifd_relevanssi_filter_general_search' );

function ifd_get_expiring_articles() {
	global $wpdb;
	$today = date('Y-m-d', strtotime('-1 year'));
	$expiring = $wpdb->get_col(
		"SELECT ID
		 FROM {$wpdb->posts}
		 WHERE post_modified < '{$today}'
		 AND post_status IN ('publish', 'private', 'inherit', 'future')
		 AND post_type = 'article'
	");
	return $expiring;
}

add_action('template_redirect', 'ifd_expiring_articles_notification');
function ifd_expiring_articles_notification() {
	if (!empty($_GET['_cron_article_expiration_27z835a']) && $_GET['_cron_article_expiration_27z835a'] == '8aJ2b') {
		$expiring = ifd_get_expiring_articles();
		if (!$expiring) {
			exit;
		}
		$message = 'The following articles have expired and need attention:<br />';
		$message .= '<ul>';
		foreach ($expiring as $post_id) {
			$message .= '<li><a href="' . get_permalink($post_id) . '"><strong>' . get_the_title($post_id) . '</strong></a> <a href="' . get_edit_post_link($post_id) . '"><em>(Edit)</em></a></li>';
		}
		$message .= '</ul>';
		add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
		wp_mail(get_bloginfo('admin_email'), get_bloginfo('name') . ' - Expired articles', $message);
		exit;
	}
}

function ifd_expiring_articles_dashboard_widget() {
	$expiring = ifd_get_expiring_articles();
	if ($expiring) {
		echo '<p>The following articles have expired and need attention:</p>';
		echo '<ul>';
		foreach ($expiring as $post_id) {
			echo '<li><a href="' . get_permalink($post_id) . '"><strong>' . get_the_title($post_id) . '</strong></a> <a href="' . get_edit_post_link($post_id) . '"><em>(Edit)</em></a></li>';
		}
		echo '</ul>';
	} else {
		echo '<p>There are no expired articles at this time.</p>';
	}
}

// Create the function use in the action hook

add_action('wp_dashboard_setup', 'ifd_expiring_articles_add_dashboard_widget' );
function ifd_expiring_articles_add_dashboard_widget() {
	wp_add_dashboard_widget('expiring_articles_dashboard_widget', 'Expired Articles', 'ifd_expiring_articles_dashboard_widget');
}

function ifd_post_author($post_id = null) {
	if (!get_option('ifd_enable_author_box')) {
		return;
	}
	if (!$post_id) {
		global $post;
		$post_id = $post->ID;
	}
	$post_obj = get_post($post_id);
	$post_author = get_user_by('id', $post_obj->post_author);
	?>
	<div class="post-author-block">
		<?php echo get_avatar($post_author->user_email, 100); ?>
		<h4>About <a href="<?php echo get_author_posts_url($post_author->ID); ?>"><?php echo $post_author->display_name; ?></a href="<?php echo get_author_posts_url($post_author->ID); ?>"></h4>
		<?php echo wpautop( get_user_meta( $post_author->ID, 'description', 1 ) ); ?>
		<div class="cl">&nbsp;</div>
	</div>
	<?php
}

function ifd_get_dropdown_posts($post_type = 'post') {
	global $wpdb;
	$results = $wpdb->get_results(
		"SELECT ID, post_title
		 FROM $wpdb->posts
		 WHERE post_type = '$post_type'
		 AND post_status = 'publish'
		 GROUP BY ID
		 ORDER BY post_date DESC
	");

	$fixed = array(
		0 => '- - - None (please select) - - - '
	);
	foreach ($results as $r) {
		$fixed[$r->ID] = html_entity_decode($r->post_title);
	}

	return $fixed;
}

function ifg_get_practice_doctors($practice_id = null, $children = true) {
	if (!$practice_id) {
		global $post;
		$practice_id = $post->ID;
	}
	global $wpdb;
	$results = $wpdb->get_col(
	   "SELECT DISTINCT pm.post_id
		FROM $wpdb->postmeta AS pm
		INNER JOIN $wpdb->posts AS p
		ON p.ID = pm.post_id
		WHERE pm.meta_key = '_doctor_select_practice'
		AND pm.meta_value = '{$practice_id}'
		AND p.post_status = 'publish'
		AND p.post_type = 'doctor'
	");

	if ($results && $children) {
		foreach ($results as $res) {
			$res_post = get_post($res);
			if ($res_post->post_parent && !in_array($res_post->post_parent, $results) ) {
				$results[] = $res_post->post_parent;
			}
		}
	}

	if ($results && $children) {
		foreach ($results as $par) {
			$maybe_siblings = $wpdb->get_col(
			   "SELECT DISTINCT ID
				FROM $wpdb->posts
				WHERE post_parent = {$par}
			");
			foreach ($maybe_siblings as $s) {
				if ( !in_array($s, $results) ) {
					$results[] = $s;
				}
			}
		}
	}

	return array_merge($results);
}

function ifg_order_doctors($doctorIds) {
	if (!is_array($doctorIds) || count($doctorIds) === 0)
		return array();

	$orderKeys = '';
	foreach($doctorIds as $id) {
		$orderKeys .= "pm.meta_key = '_doctor_order_{$id}' OR ";
	}
	$orderKeys = substr($orderKeys, 0, count($orderKeys) - 5);

	global $wpdb;
	$results = $wpdb->get_results(
		"SELECT pm.meta_key, pm.meta_value
		FROM $wpdb->postmeta AS pm
		WHERE {$orderKeys}
	");

	// If there are no results then simply return the original doctor ids.
	if (empty($results)) {
		return $doctorIds;
	}

	$orderMap = array();
	foreach($results as $doctor) {
		$orderMap[str_replace('_doctor_order_', '', $doctor->meta_key)] = $doctor->meta_value;
	}
	asort($orderMap);
	return array_keys($orderMap);
}

function ifg_get_practice_articles($practice_id = null) {
	if (!$practice_id) {
		global $post;
		$practice_id = $post->ID;
	}

	// We used to get the articles from the doctors. In the new system,
	// articles contain a _practice meta key.
	$args = array(
			'post_type' => 'article',
			'numberposts' => -1 ,
			'meta_key' => '_practice',
			'meta_value' => $practice_id
	);
	$postResults = new WP_Query( $args );

	$postIdMapper = function($post) {
			return $post->ID;
	};

	$results = array_map($postIdMapper, $postResults->posts);

	if (empty($results)) {
			$doctors = ifg_get_practice_doctors($practice_id);
	    if ($doctors) {
		      global $wpdb;
		      $results = $wpdb->get_col(
		          "SELECT DISTINCT pm.post_id
		          FROM $wpdb->postmeta AS pm
		          INNER JOIN $wpdb->posts AS p
		          ON p.ID = pm.post_id
		          WHERE pm.meta_key = '_doctors'
		          AND pm.meta_value IN (" . implode(',', $doctors) . ")
		          AND p.post_status = 'publish'
		          AND p.post_type = 'article'"
		      );
			}
	}

  return $results;
}

//add_filter( 'admin_body_class', 'ifmd_admin_body_class' );
function ifmd_admin_body_class($classes) {
	if (!empty($_GET['post'])) {
		$p_obj = get_post($_GET['post']);
		if ($p_obj->post_type == 'practice') {
			$classes .= ' admin-edit-single-practice';
		}
	}
	return $classes;
}

add_filter('post_row_actions', 'ifmd_practice_post_row_actions', 10, 2);
function ifmd_practice_post_row_actions($actions = null, $post = null) {
	if (!empty($post)) {
		if ($post->post_type == 'practice') {
			$actions_to_prevent = array(
				'delete',
			);
			foreach ($actions_to_prevent as $a) {
				if ( !empty($actions[$a]) ) {
					$actions[$a] = str_replace('<a ', '<a onclick="return practice_on_delete_alert();"', $actions[$a]);
				}
			}
		}
	}
	return $actions;
}

add_action('delete_post', 'ifmd_on_delete_practice', 10, 1);
function ifmd_on_delete_practice($post_id) {
	$p_obj = get_post($post_id);
	if ($p_obj->post_type == 'practice') {
		$articles = ifg_get_practice_articles($p_obj->ID);
		$doctors = ifg_get_practice_doctors($p_obj->ID);
		$locations = ifg_get_practice_locations($p_obj->ID);
		$to_delete = array_merge($articles, $doctors, $locations);

		if ($to_delete) {
			foreach ($to_delete as $td) {
				wp_delete_post($td, true);
			}
		}
	}
}

function ifg_get_practice_locations($practice_id = null) {
	if (!$practice_id) {
		global $post;
		$practice_id = $post->ID;
	}
	global $wpdb;
	$results = $wpdb->get_col(
	   "SELECT DISTINCT pm.post_id
		FROM $wpdb->postmeta AS pm
		INNER JOIN $wpdb->posts AS p
		ON p.ID = pm.post_id
		WHERE pm.meta_key = '_location_select_practice'
		AND pm.meta_value = '{$practice_id}'
		AND p.post_status = 'publish'
		AND p.post_type = 'location'
	");

	return $results;
}

/**
 * Locates the single location that doesn't have "- %s Location" in the post_title, pulls it out of the array
 * and pushes it to the beginning of the array.
 * @param {Array} $locations
 * return {Array}
 */
function ifg_sort_locations_by_name($locations) {
	$filtered_locations = array_filter($locations, function ($location) {
		if (preg_match("/\\w.+[-] \\w.+ Location/", $location->post_title) !== 1)
			return $location;
	});
	$filtered_locations = array_values($filtered_locations);

	$other_locations = array_udiff($locations, $filtered_locations, function ($location1, $location2) {
		return $location1->ID - $location2->ID;
	});
	$other_locations = array_values($other_locations);
	array_unshift($other_locations, $filtered_locations[0]);

	return $other_locations;
}

function ifd_location_save($post_id) {
	global $wpdb;
	$temp_post = get_post($post_id);
	if ($temp_post->post_type == 'location') {
		$address = get_post_meta($temp_post->ID, '_location_cpt_address', true);
		$city = get_post_meta($temp_post->ID, '_location_cpt_city', true);
		$zip = get_post_meta($temp_post->ID, '_location_cpt_zip', true);
		$state = get_post_meta($temp_post->ID, '_location_cpt_state', true);

		// TODO: Use arrays, implode() and array_filter()
		if (!empty($address) || !empty($city) || !empty($state) || !empty($zip)) {	//if at least one of the fields is not empty

			$query_str = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address . ' ' . $city . ' ' . $state . ' ' . $zip) . '&sensor=false';
			$query_results = json_decode(file_get_contents($query_str));

			$coords = array();
			if (!empty($query_results->results)) {
				foreach ($query_results->results as $res) {
					$coords['lng'] = $res->geometry->location->lng;
					$coords['ltd'] = $res->geometry->location->lat;
					break;
				}
			}

			if ($coords['lng'] != '0' && $coords['ltd'] != '0') {
				$query = "SELECT * FROM {$wpdb->prefix}doctors_locations WHERE location='" . $post_id . "'";
				$db_query_results = $wpdb->get_results($query);
				if (count($db_query_results)) {
					$query = "UPDATE {$wpdb->prefix}doctors_locations SET {$wpdb->prefix}doctors_locations.lng = '" . $coords['lng'] . "', {$wpdb->prefix}doctors_locations.ltd='" . $coords['ltd'] . "' WHERE location='" . $post_id . "'";
				} else {

					$query = "INSERT INTO {$wpdb->prefix}doctors_locations(id,user_id,lng,ltd, location)
							VALUES(''," . $temp_post->post_parent . ", '" . $coords['lng'] . "','" . $coords['ltd'] . "', '" . $post_id . "')";
				}
				$db_query_results = $wpdb->get_results($query);
			}
		}
	}
}
add_action('save_post', 'ifd_location_save');

// Default wordpress behavior states that checked items in a taxonomy should be moved to the top.
// Overwrite this behavior to maintain hierarchical order.
add_filter('wp_terms_checklist_args', 'set_taxonomy_item_order_args');
function set_taxonomy_item_order_args($args) {
    $args['checked_ontop'] = false;
    return $args;
}

// remove edition pdf's when an edition is removed
add_action('before_delete_post', 'ifmd_on_delete_edition');
function ifmd_on_delete_edition ($postId) {
	$edition = get_post($post_id);
	if ($edition->post_type == 'edition-files') {
		$pdf_link = get_post_meta($edition->ID, '_edition_pdf', true);
		if (!empty($pdf_link)) {
			$pdf_link = '../wp-content/uploads/' . $pdf_link;
			unlink($pdf_link);
		}
	}
}

		// When a doctor is deleted we need to remove that doctor's meta data from all articles
add_action( 'delete_post', 'handle_deleted_post');
//add_action('save_post', 'handle_deleted_post');
function handle_deleted_post( $postId ) {
    global $post_type;

		if ($post_type == 'doctor') {
        // Get all articles and their corresponding meta
        $args = array( 'post_type' => 'article', 'posts_per_page' => -1 );
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post();
            $id = get_the_ID();
            $meta = get_post_meta($id);

            // Is the postId of the doctor being deleted referenced in the articles $meta['_doctors'] array?
            if (isset($meta['_doctors']) && is_array($meta['_doctors']) && in_array($postId, $meta['_doctors'])) {
                // The doctor that is being deleted is being referenced in an article. Remove the reference to this
                // doctor in the article.
                delete_post_meta($id, '_doctors', $postId);
            }

        endwhile;
    }
}

/**
 * Renders the clickable social media icons
 * @param $facebook_link
 * @param $twitter_link
 * @param $linkedin_link
 * @param $pinterest_link
 * @param $youtube_link
 * @param $googleplus_link
 */
function renderActiveSocialIcons($facebook_link, $twitter_link, $linkedin_link, $pinterest_link, $youtube_link,
                                 $googleplus_link, $instagram_link)
{
    $stylesheetDir = get_stylesheet_directory_uri();
    if ($facebook_link) {
        echo '<a href="' . esc_attr($facebook_link) . '" target="_blank"><img src="' . $stylesheetDir . '/images/fb.png" alt="Facebook Profile" /></a>';
    }
    if ($twitter_link) {
        echo '<a href="' . esc_attr($twitter_link) .'" target="_blank"><img src="' . $stylesheetDir . '/images/twitter.png" alt="Twitter Profile" /></a >';
    }
    if ($linkedin_link) {
        echo '<a href="' . esc_attr($linkedin_link) . '" target="_blank"><img src="' . $stylesheetDir . '/images/linkedin.png" alt="Linked-In Profile" /></a>';
    }
    if ($pinterest_link) {
        echo '<a href="' . esc_attr($pinterest_link) . '" target="_blank"><img src="' . $stylesheetDir . '/images/pinterest.png" alt="Pinterest Profile" /></a>';
    }
    if ($youtube_link) {
        echo '<a href="' . esc_attr($youtube_link) . '" target="_blank"><img src="' . $stylesheetDir . '/images/youtube.png" alt="Youtube Profile" /></a>';
    }
    if ($googleplus_link) {
        echo '<a href="' . esc_attr($googleplus_link) . '" target="_blank"><img src="' . $stylesheetDir . '/images/google_plus.png" alt="Google+ Profile" /></a>';
    }
	if ($instagram_link) {
		echo '<a href="' . esc_attr($instagram_link) . '" target="_blank"><img src="' . $stylesheetDir . '/images/instagram.png" alt="Instagram Profile" /></a>';
	}
}

function split_keywords ($keywords) {
	$keywords = explode(',', $keywords);
	return array_map(function ($phrase) {
		return explode(' ', trim($phrase));
	}, $keywords);
}

/**
 * Generates html for the list item in the global search results and potentially on the article page.
 * @param {Object} $post
 * @return string
 */
function generate_search_result_item($post) {
    $permaLink = get_permalink($post->ID);

    $searchResultItem = <<<HTML

    <li>
        <h4>{$post->post_title}</h4>
            <p class="meta">
HTML;

    //getting the editions
    $article_editions = get_the_terms($post->ID, 'edition');
    if ($article_editions) {
        $searchResultItem .= '<strong>Edition:</strong>';
        $output = '<span>';
        foreach ($article_editions as $edition) {
            $output .= $edition->name . ', ';
        }
        $output[strlen($output) - 2] = '';
        $output .= '</span>';
        $searchResultItem .= $output;
    }

    $terms = wp_get_object_terms($post->ID, 'date_published');
    $month_number = get_the_time('n', $post->ID);
    $season_name = count($terms) > 1 ? $terms[count($terms)-1]->name : ifd_get_season($month_number);
    $season_name = str_ireplace(' issue', '', $season_name);
    $year = !empty($terms[0]) ? $terms[0]->name : get_the_time('Y', $post->ID);
    $publishedSeasonAndYear = $season_name . ' ' . $year;
    $searchResultItem .= <<<HTML
<span><strong>Published:</strong>{$publishedSeasonAndYear}</span></p>
<a href="{$permaLink}" class="result-thumb">
HTML;

    $thumb = '';
    $thumbnail = wp_get_attachment_image_src ( get_post_thumbnail_id ( $post->ID ), 'thumbnail' ) ;
    if (empty($thumbnail)) {
        $thumb = '<img src="' . get_bloginfo('stylesheet_directory') . '/images/' . 'no_image.jpg' . '" alt="' . esc_attr($post->post_title) . '"/>';
    } else {
        $thumb = $thumbnail[0];
    }

    // If a mini_thumb is associated with the id then set the thumbnail to be the mini thumbnail.
    $mini_thumb = get_post_meta($post->ID, '_mini_thumb', true);
    if (!empty($mini_thumb)) {
        $thumb = get_upload_url() . '/' . $mini_thumb;
    }
    if (!empty($thumbnail)) {
        $thumbSrc = get_template_directory_uri() . "/scripts/tt.php?src=" . $thumb . "&q=100&zc=1&w=103&h=103";
        $searchResultItem .= '<img src="' . $thumbSrc. '" />';
    } else {
        $searchResultItem .= $thumb;
    }
    $resultBody = ifd_get_excerpt_max_charlength(apply_filters('the_content', $post->post_content), 750, '<h2>');
    $searchResultItem .= <<<HTML
</a>
<div class="result-body">
    {$resultBody}
    <p></p>
    <p><a href="{$permaLink}" class="button"><span>Read More</span></a></p>
</div>
<div class="cl">&nbsp;</div>
</li>
HTML;

    return $searchResultItem;
}

@ini_set('upload_max_size', '64M');
@ini_set('post_max_size', '64M');
@ini_set('max_execution_time', '300');
