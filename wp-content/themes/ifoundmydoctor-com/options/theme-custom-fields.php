<?php


global $my_admin_page;
$post_id = -1;
if (isset($_GET['post']))
    $post_id = $_GET['post'];

if (empty($post_id) && isset($_POST['post_ID']))
    $post_id = $_POST['post_ID'];


// Record all doctors and map to their id's
$all_doctors = get_posts(array(
    'post_type' => 'doctor',
    'numberposts' => -1,
    'post_parent' => 0
));

$doctors_options = array('none');
foreach ($all_doctors as $doctor) {
    $doctors_options[$doctor->ID] = $doctor->post_title;
}

// Pages Options
$panel = new ECF_Panel('pages-options', 'Page\'s Options', 'page', 'normal', 'high');
$panel->add_fields(array(
    ECF_Field::factory('choose_sidebar', 'custom_sidebar')->help_text('Page Sidebar')
));

// Blog Options
$panel = new ECF_Panel('blog-options', 'Post\'s Options', 'post', 'normal', 'high');
$panel->add_fields(array(
    ECF_Field::factory('choose_sidebar', 'blog_custom_sidebar')->set_default_value('Default Blog Sidebar')->help_text('Page Sidebar'),
    ECF_Field::factory('select', 'doctor_author')->add_options($doctors_options)
));

// Page-Editions Options
$editions_panel = new ECF_Panel('pages-editions-options', 'Editions Page\'s Options', 'page', 'normal', 'high');
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


// Doctors Options
$doctors_panel = new ECF_Panel('doctors-options', 'Doctor\'s Options', 'doctor', 'normal', 'high');
$doctors_panel->add_fields(array(
    ECF_Field::factory('text', 'doctor_facebook_link')->help_text('Facebook Link'),
    ECF_Field::factory('text', 'doctor_twitter_link')->help_text('Twitter Link'),
    ECF_Field::factory('text', 'doctor_linkedin_link')->help_text('Linked-In Link'),
    ECF_Field::factory('text', 'doctor_pinterest_link')->help_text('Pinterest Link'),
    ECF_Field::factory('text', 'doctor_youtube_link')->help_text('Youtube Link'),
    ECF_Field::factory('text', 'doctor_googleplus_link')->help_text('Google+ Link'),
    ECF_Field::factory('text', 'doctor_instagram_link')->help_text('Instagram Link'),
    ECF_Field::factory('text', 'doctor_website')->help_text('Website'),
    ECF_Field::factory('text', 'practice_name')->help_text('Practice Name'),
    ECF_Field::factory('image', 'doctor_logo', 'Logo'),
    ECF_Field::factory('text', 'doctor_logo_url', 'Logo URL'),
    ECF_Field::factory('select', 'doctor_featured')->add_options(array(
        'non-featured' => 'Not featured',
        'featured' => 'Featured'
    ))
));
$doctors_panel->show_on_level(1);

$doctor_location_panel = new ECF_Panel('doctors-location-options', 'Doctor\'s Location Options', 'doctor', 'normal', 'high');
$doctor_location_panel->add_fields(array(
    ECF_Field::factory('text', 'location_address')->help_text('Location Street Address'),
    ECF_Field::factory('text', 'location_city')->help_text('City'),
    ECF_Field::factory('text', 'location_state')->help_text('State'),
    ECF_Field::factory('text', 'location_zip')->help_text('Zip'),
    ECF_Field::factory('text', 'location_telephone')->help_text('Location Telephone')
));
$doctor_location_panel->show_on_level(2);

// Article Options
$pretty_practices = ifd_get_dropdown_posts('practice');
// Sort the practices
asort($pretty_practices);
$article_panel = new ECF_Panel('article-options', 'Article\'s Options', 'article', 'normal', 'high');
$article_panel->add_fields(array(
    ECF_Field::factory('select', 'practice', 'Practice')->add_options($pretty_practices),
    ECF_Field::factory('multiply_select', 'doctors')->multiply()->add_options($doctors_options),
    ECF_Field::factory('text', 'expiration')->help_text('Expiration Date (yyyy-mm-dd)'),
    ECF_Field::factory('select', 'featured')->add_options(array(
        'non-featured' => 'Not Featured',
        'featured' => 'Featured'
    )),
    ECF_Field::factory('image', 'mini_thumb')->help_text('For the home slider(93 x 57 px recommended)')
));


// Edition file Options
$edition_file_panel = new ECF_Panel('edition-file-options', 'Edition file Options', 'edition-files', 'normal', 'high');
$edition_file_panel->add_fields(array(
    ECF_Field::factory('file', 'edition_pdf', 'Edition PDF')
));

$practice_cpt_panel = new ECF_Panel('doctor-practice-select-options', ucwords('Doctor Practice Options'), 'doctor', 'normal', 'high');
$practice_cpt_panel->add_fields(array(
    ECF_Field::factory('select', 'doctor_select_practice', 'Practice')
        ->add_options($pretty_practices)
));
$practice_cpt_panel->show_on_level(1);

$location_cpt_panel = new ECF_Panel('location-practice-select-options', ucwords('Location Practice Options'), 'location', 'normal', 'high');
$location_cpt_panel->add_fields(array(
    ECF_Field::factory('select', 'location_select_practice', 'Practice')
        ->add_options($pretty_practices),
    ECF_Field::factory('text', 'location_cpt_address', 'Location Address')->help_text('Location Street Address'),
    ECF_Field::factory('text', 'location_cpt_city', 'Location City')->help_text('City'),
    ECF_Field::factory('text', 'location_cpt_state', 'Location State')->help_text('State')->set_default_value('Florida'),
    ECF_Field::factory('text', 'location_cpt_zip', 'Location Zip')->help_text('Zip'),
    ECF_Field::factory('text', 'location_cpt_telephone', 'Location Telephone')->help_text('Location Telephone')
));
$location_cpt_panel->show_on_level(1);


// Practice Options
$practice_panel = new ECF_Panel('practice_panel', 'Practice Options', 'practice', 'normal', 'high');

$practice_fields = array();
$practice_fields[] = ECF_Field::factory('text', 'practice_facebook_link', 'Facebook Link');
$practice_fields[] = ECF_Field::factory('text', 'practice_twitter_link', 'Twitter Link');
$practice_fields[] = ECF_Field::factory('text', 'practice_linkedin_link', 'Linked-In Link');
$practice_fields[] = ECF_Field::factory('text', 'practice_pinterest_link', 'Pinterest Link');
$practice_fields[] = ECF_Field::factory('text', 'practice_youtube_link', 'Youtube Link');
$practice_fields[] = ECF_Field::factory('text', 'practice_googleplus_link', 'Google+ Link');
$practice_fields[] = ECF_Field::factory('text', 'practice_instagram_link', 'Instagram Link');
$practice_fields[] = ECF_Field::factory('text', 'practice_website', 'Website');
$practice_fields[] = ECF_Field::factory('image', 'practice_logo', 'Logo');
$practice_fields[] = ECF_Field::factory('text', 'practice_logo_url', 'Logo URL');
$practice_fields[] = ECF_Field::factory('separator', 'doctor_order_separator', 'Doctor Order');

$practice_fields = add_doctor_fields_to_practice_panel($post_id, $practice_fields, $all_doctors);

// Add the fields to the panel
$practice_panel->add_fields($practice_fields);

/**
 * Adds doctor information to the practice panel.
 * @param {string} $post_id The post id
 * @param {Array} $pracice_fields The practice fields array
 * @param {Array} $all_doctors array of all doctors
 */
function add_doctor_fields_to_practice_panel ($post_id, $practice_fields, $all_doctors) {
  if ($post_id === -1 && $_POST['post_ID'] === -1)
    return $practice_fields;
  else if ($post_id === -1 && $_POST['post_ID'] > -1)
    $post_id = $_POST['post_ID'];

  // Retrieve only the ids of doctors associated with this practice
  $practice_doctors = ifg_get_practice_doctors($post_id);

  // Filter out only the doctors that match the array of id's from the full list of doctors
  $filtered_doctors = array_filter($all_doctors, function ($doctor) use ($practice_doctors) {
      return in_array($doctor->ID, $practice_doctors);
  });

 // Add a doctor order field for each doctor to the $practice_fields array
  foreach ($filtered_doctors as $doctor) {
      $practice_fields[] = ECF_Field::factory('select', 'doctor_order_' . $doctor->ID, $doctor->post_title)
        ->add_options(generate_order_options(count($filtered_doctors)));
  }

  return $practice_fields;
}

/**
 * Generates an dictionary from 0 to length in the format: [0 => 1, 1 => 2, n => n+1]
 * @param {int} $length
 * @return array
 */
function generate_order_options ($length) {
    $orderOptions = array();
    for ($i = 0; $i < $length; $i++) {
        $orderOptions[$i] = $i + 1;
    }

    return $orderOptions;
}
