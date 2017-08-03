<?php
function attach_main_options_page() {
	$title = "Theme Options";
	add_menu_page(
		$title,
		$title, 
		'edit_themes', 
	    basename(__FILE__),
		create_function('', '')
	);
}
add_action('admin_menu', 'attach_main_options_page');

$inner_options = new OptionsPage(array(
	//Header
	wp_option::factory('separator', 'ifc_header', __('Header')),
    wp_option::factory('text', 'ifc_linkedin_link', __('LinkedIn Link:'))->set_default_value('#'),
    wp_option::factory('text', 'ifc_facebook_link', __('Facebook Link:'))->set_default_value('#'),
    wp_option::factory('text', 'ifc_twitter_link', __('Twitter Link:'))->set_default_value('#'),
    wp_option::factory('text', 'ifc_pinit_link', __('Pinit Link:'))->set_default_value('#'),
    wp_option::factory('text', 'ifc_googleplus_link', __('Google Plus Link:'))->set_default_value('#'),
    wp_option::factory('text', 'ifc_youtube_link', __('Youtube Link:'))->set_default_value('#'),
    wp_option::factory('text', 'ifc_slideshow_speed', 'Slider Speed:')->set_default_value('0.5')->help_text('Set the speed of the slideshow cycling, in seconds'),


    //Footer
	wp_option::factory('separator', 'ifc_footer', __('Footer')),
    wp_option::factory('text', 'ifc_copyrights', __('Copyright Information:'))->set_default_value('Florida Health Care News, Inc.'),
    wp_option::factory('rich_text', 'ifc_address', __('Address:'))->set_default_value('215 Bullard Parkway, Temple Terrace, FL 33617'),
    wp_option::factory('rich_text', 'ifc_telephones', __('Telephones:'))->set_default_value('813.989.1330 <span>|</span> 888.714.6728 <span>|</span>'),
    wp_option::factory('rich_text', 'ifc_email', __('Email:'))->set_default_value('info@ifoundmydoctor.com'),

	//Misc
	wp_option::factory('separator', 'ifc_misc', __('Misc')),
    wp_option::factory('rich_text', 'article_hippa_footer', __('Article HIPPA footer:'))->set_default_value(''),
    wp_option::factory('rich_text', 'ifc_google_ads', __('Bottom Google Ads Script For Doctor page, Article Page:'))->set_default_value(''),
    wp_option::factory('rich_text', 'ifc_google_ads_script_square', __('Square Google Ads Script:'))->set_default_value(''),
    wp_option::factory('rich_text', 'ifc_google_ads_script_skyscraper', __('Skyscraper Google Ads Script:'))->set_default_value(''),
    wp_option::factory('text', 'ifd_start_level', __('Search Radius Start From:'))->set_default_value('5'),
    wp_option::factory('text', 'ifd_step', __('Search Radius Step:'))->set_default_value('10'),
    wp_option::factory('text', 'ifd_max_level', __('Search Radius Max Level:'))->set_default_value('100'),
    wp_option::factory('select', 'ifd_enable_author_box', __('Enable Article Author Box:'))
        ->set_default_value(0)
        ->add_options(array(
            'No',
            'Yes'
        )),
    wp_option::factory('header_scripts', 'header_script'),
    wp_option::factory('footer_scripts', 'footer_script'),
));
$inner_options->title = 'General';
$inner_options->file = basename(__FILE__);
$inner_options->parent = "theme-options.php";
$inner_options->attach_to_wp();

?>