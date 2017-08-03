<?php  
/* Article Custom Post Type */
register_post_type('article', array(
	'labels' => array(
		'name'	 => 'Articles',
		'singular_name' => 'Article',
		'add_new' => __( 'Add New' ),
		'add_new_item' => __( 'Add new Article' ),
		'view_item' => 'View Article',
		'edit_item' => 'Edit Article',
		'new_item' => __('New Article'),
		'view_item' => __('View Article'),
		'search_items' => __('Search Articles'),
		'not_found' =>  __('No articles found'),
		'not_found_in_trash' => __('No articles found in Trash'),
	),
	'public' => true,
	'exclude_from_search' => false,
	'show_ui' => true,
	'capability_type' => 'post',
	'hierarchical' => true,
	'_edit_link' =>  'post.php?post=%d',
	'rewrite' => array(
		"slug" => "article",
		"with_front" => false,
	),
	'query_var' => true,
	'supports' => array('title', 'editor', 'page-attributes', 'thumbnail', 'author'),
));
register_taxonomy('date_published',array('article'), array(
	'hierarchical' => true,
	'labels' => array(
			'name' => _x( 'Date Published', 'taxonomy general name' ),
			'singular_name' => _x( 'Date Published', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Date Published' ),
			'all_items' => __( 'All Date Published' ),
			'parent_item' => __( 'Parent Date Published' ),
			'parent_item_colon' => __( 'Parent Date Published:' ),
			'edit_item' => __( 'Edit Date Published' ), 
			'update_item' => __( 'Update Date Published' ),
			'add_new_item' => __( 'Add New Date Published' ),
			'new_item_name' => __( 'New Date Published Name' ),
			'menu_name' => __( 'Date Published' ),
		),
	'show_ui' => true,
	'query_var' => true,
	'rewrite' => array( 'slug' => 'date_published' ),
));

/* Registering the Edition Taxonomy  */
register_taxonomy('edition',array('article'), array(
	'hierarchical' => true,
	'labels' => array(
		'name' => _x( 'Editions', 'taxonomy general name' ),
		'singular_name' => _x( 'Edition', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Editions' ),
		'all_items' => __( 'All Editions' ),
		'parent_item' => __( 'Parent Edition' ),
		'parent_item_colon' => __( 'Parent Edition:' ),
		'edit_item' => __( 'Edit Edition' ), 
		'update_item' => __( 'Update Edition' ),
		'add_new_item' => __( 'Add New Edition' ),
		'new_item_name' => __( 'New Edition Name' ),
		'menu_name' => __( 'Edition' ),
	),
	'show_ui' => true,
	'query_var' => true,
	'rewrite' => array( 'slug' => 'edition' ),
));

/* End of Article Post Type */

/* Doctr Custom Post Type */
register_post_type('doctor', array(
	'labels' => array(
		'name'	 => 'Doctors',
		'singular_name' => 'Doctor',
		'add_new' => __( 'Add New' ),
		'add_new_item' => __( 'Add new Doctor' ),
		'view_item' => 'View Doctor',
		'edit_item' => 'Edit Doctor',
	    'new_item' => __('New Doctor'),
	    'view_item' => __('View Doctor'),
	    'search_items' => __('Search Doctors'),
	    'not_found' =>  __('No doctors found'),
	    'not_found_in_trash' => __('No doctors found in Trash'),
	),
	'public' => true,
	'exclude_from_search' => false,
	'show_ui' => true,
	'capability_type' => 'post',
	'hierarchical' => true,
	'_edit_link' =>  'post.php?post=%d',
	'rewrite' => array(
		"slug" => "doctor",
		"with_front" => false,
	),
	'query_var' => true,
	'supports' => array('title', 'editor', 'page-attributes', 'thumbnail'),
));
/* Registering the Speciality Taxonomy  */
register_taxonomy('speciality',array('doctor'), array(
	'hierarchical' => true,
	'labels' => array(
			'name' => _x( 'Speciality', 'taxonomy general name' ),
			'singular_name' => _x( 'Speciality', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Specialities' ),
			'all_items' => __( 'All Specialities' ),
			'parent_item' => __( 'Parent Speciality' ),
			'parent_item_colon' => __( 'Parent Speciality:' ),
			'edit_item' => __( 'Edit Speciality' ), 
			'update_item' => __( 'Update Speciality' ),
			'add_new_item' => __( 'Add New Speciality' ),
			'new_item_name' => __( 'New Speciality Name' ),
			'menu_name' => __( 'Speciality' ),
		),
	'show_ui' => true,
	'query_var' => true,
	'rewrite' => array( 'slug' => 'speciality' ),
));


/* Edition files */
register_post_type('edition-files', array(
	'labels' => array(
		'name'	 => 'Edition Files',
		'singular_name' => 'Edition File',
		'add_new' => __( 'Add New' ),
		'add_new_item' => __( 'Add new Edition File' ),
		'view_item' => 'View Edition File',
		'edit_item' => 'Edit Edition File',
	    'new_item' => __('New Edition File'),
	    'view_item' => __('View Edition File'),
	    'search_items' => __('Search Edition Files'),
	    'not_found' =>  __('No edition files found'),
	    'not_found_in_trash' => __('No edition files found in Trash'),
	),
	'public' => true,
	'exclude_from_search' => false,
	'show_ui' => true,
	'capability_type' => 'post',
	'hierarchical' => true,
	'_edit_link' =>  'post.php?post=%d',
	'rewrite' => array(
		"slug" => "edition-files",
		"with_front" => false,
	),
	'query_var' => true,
	'supports' => array('title'),
));
register_taxonomy_for_object_type('edition', 'edition-files');
register_taxonomy_for_object_type('date_published', 'edition-files');

register_post_type('practice', array(
	'labels' => array(
		'name'	 => 'Practices',
		'singular_name' => 'Practice',
		'add_new' => __( 'Add New' ),
		'add_new_item' => __( 'Add new Practice' ),
		'view_item' => 'View Practice',
		'edit_item' => 'Edit Practice',
	    'new_item' => __('New Practice'),
	    'view_item' => __('View Practice'),
	    'search_items' => __('Search Practices'),
	    'not_found' =>  __('No practices found'),
	    'not_found_in_trash' => __('No practices found in Trash'),
	),
	'public' => true,
	'exclude_from_search' => false,
	'show_ui' => true,
	'capability_type' => 'post',
	'hierarchical' => false,
	'_edit_link' =>  'post.php?post=%d',
	'rewrite' => array(
		"slug" => "practice",
		"with_front" => false,
	),
	'query_var' => true,
	'supports' => array('title', 'editor', 'page-attributes', 'thumbnail'),
));

register_post_type('location', array(
	'labels' => array(
		'name'	 => 'Locations',
		'singular_name' => 'Location',
		'add_new' => __( 'Add New' ),
		'add_new_item' => __( 'Add new Location' ),
		'view_item' => 'View Location',
		'edit_item' => 'Edit Location',
	    'new_item' => __('New Location'),
	    'view_item' => __('View Location'),
	    'search_items' => __('Search Locations'),
	    'not_found' =>  __('No locations found'),
	    'not_found_in_trash' => __('No locations found in Trash'),
	),
	'public' => false,
	'exclude_from_search' => true,
	'show_ui' => true,
	'capability_type' => 'post',
	'hierarchical' => false,
	'_edit_link' =>  'post.php?post=%d',
	'rewrite' => false,
	'query_var' => true,
	'supports' => array('title', 'editor', 'page-attributes', 'thumbnail'),
));

add_action('init', 'my_custom_init');
function my_custom_init() {
    add_post_type_support( 'article', 'publicize' );
}