<?php
/* Pages Options */
$panel =& new ECF_Panel('pages-options', 'Page\'s Options', 'page', 'normal', 'high');
$panel->add_fields(array(
	ECF_Field::factory('choose_sidebar', 'custom_sidebar')->help_text('Page Sidebar')
));
/* End of Pages Options */

/* Blog Options */
$panel =& new ECF_Panel('blog-options', 'Post\'s Options', 'post', 'normal', 'high');
$panel->add_fields(array(
	ECF_Field::factory('choose_sidebar', 'blog_custom_sidebar')->set_default_value('Default Blog Sidebar')->help_text('Page Sidebar')
));
/* End of Blog Options */

/* Page-Editions Options */
$editions_panel =& new ECF_Panel('pages-editions-options', 'Editions Page\'s Options', 'page', 'normal', 'high');
$editions_panel->add_fields(array(
	ECF_Field::factory('rich_text', 'planned_editions')->help_text('Planed Editions')->set_default_value('<ul>
																												<li>The Villages</li>
																												<li>St. Augustine</li>
																												<li>New Tampa/East Pasco</li>  
																												<li>East Seminole County</li>
																												<li>West Seminole County</li>
																												<li>Lake County</li>
																										 </ul>')
));
$editions_panel->show_on_template('page-editions.php');
/* End of Pages Options */

/* Location Options */
$all_doctors = get_posts(array(
						'post_type'		=> 'doctor',
						'numberposts'	=> -1
					));
$doctors_options = array('none');
foreach($all_doctors as $doctor) {
	$doctors_options[$doctor->ID] = $doctor->post_title; 
}
/*
$locations_panel =& new ECF_Panel('location-options', 'Location\'s Options', 'location', 'normal', 'high');
$locations_panel->add_fields(array(
	ECF_Field::factory('select', 'doctor')->add_options($doctors_options)->help_text('For Doctor'),
	ECF_Field::factory('text', 'location_name')->help_text('Location Name'),
	ECF_Field::factory('text', 'location_address')->help_text('Location Street Address'),
	ECF_Field::factory('text', 'location_city')->help_text('City'),
	ECF_Field::factory('text', 'location_state')->help_text('State'),
	ECF_Field::factory('text', 'location_zip')->help_text('Zip'),
	ECF_Field::factory('text', 'location_telephone')->help_text('Location Telephone'),
	ECF_Field::factory('map', 'location_coordinates')->help_text('Map Location'),
));*/
/* End of Location Options */

/* Doctors Options */
$doctors_panel =& new ECF_Panel('doctors-options', 'Doctor\'s Options', 'doctor', 'normal', 'high');
$doctors_panel->add_fields(array(
	ECF_Field::factory('text', 'doctor_facebook_link')->help_text('Facebook Link'),
	ECF_Field::factory('text', 'doctor_twitter_link')->help_text('Twitter Link'),
	ECF_Field::factory('text', 'doctor_website')->help_text('Website'),	 
	ECF_Field::factory('text', 'practice_name')->help_text('Practice Name'),	 
	ECF_Field::factory('image', 'doctor_logo')->help_text('Logo'),	 
	ECF_Field::factory('select', 'doctor_featured')->add_options(array('non-featured' => 'Non-featured', 
																 'featured' => 'Featured'))
											->help_text('Is Featured')

));
$doctors_panel->show_on_level(1);

$doctor_location_panel =& new ECF_Panel('doctors-location-options', 'Doctor\'s Location Options', 'doctor', 'normal', 'high');
$doctor_location_panel->add_fields(array(
	ECF_Field::factory('text', 'location_address')->help_text('Location Street Address'),
	ECF_Field::factory('text', 'location_city')->help_text('City'),
	ECF_Field::factory('text', 'location_state')->help_text('State'),
	ECF_Field::factory('text', 'location_zip')->help_text('Zip'),
	ECF_Field::factory('text', 'location_telephone')->help_text('Location Telephone')
	//ECF_Field::factory('map', 'location_coordinates')->help_text('Map Location'),
				));
$doctor_location_panel->show_on_level(2);
/* End of Doctor's Options */

/* Article Options */
$article_panel =& new ECF_Panel('article-options', 'Article\'s Options', 'article', 'normal', 'high');
$article_panel->add_fields(array(
	ECF_Field::factory('multiply_select', 'doctors')->multiply()->add_options($doctors_options),
	ECF_Field::factory('select', 'featured')->add_options(array('non-featured' => 'Non-featured', 
																 'featured' => 'Featured'))
											->help_text('Is Feautured')
));
/* End of Pages Options */
?>