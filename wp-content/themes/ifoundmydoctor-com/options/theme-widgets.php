<?php
/*
* Register the new widget classes here so that they show up in the widget list
*/
function load_widgets() {
	
	register_widget('LatestTweets');
	register_widget('IFDTextButtonWidget');
	register_widget('IDFLatestNews');
	register_widget('IDFPageArticleWidget');
	register_widget('IDFGoogleAdsHorizontal');
	register_widget('IDFGoogleAdsSquare'); 
	register_widget('IDFFeauturedDoctors');
	register_widget('IDFRecentPosts');
}
add_action('widgets_init', 'load_widgets');

/*
* Displays a block with latest tweets from particular user
*/
class LatestTweets extends ThemeWidgetBase {
	function LatestTweets() {
		$widget_opts = array(
			'classname' => 'theme-widget',
			'description' => 'Displays a block with your latest tweets'
		);
		$this->WP_Widget('theme-widget-latest-tweets', 'Latest Tweets', $widget_opts);
		$this->custom_fields = array(
			array(
				'name'=>'title',
				'type'=>'text',
				'title'=>'Title',
				'default'=>''
			),
			array(
				'name'=>'username',
				'type'=>'text',
				'title'=>'Username',
				'default'=>''
			),
			array(
				'name'=>'count',
				'type'=>'text',
				'title'=>'Number of Tweets to show',
				'default'=>'5'
			),
		);
	}
	
	/*
	* Called when rendering the widget in the front-end
	*/
	function front_end($args, $instance) {
		extract($args);
		$tweets = TwitterHelper::get_tweets($instance['username'], $instance['count']);
		if (!empty($tweets)) {
			if ($instance['title'])
				echo $before_title . $instance['title'] . $after_title;
		}
		?>
		<ul>
			<?php foreach ($tweets as $tweet): ?>
				<li><?php echo $tweet->tweet_text ?> - <span><?php echo $tweet->time_distance ?> ago</span></li>
			<?php endforeach ?>
		</ul>
		<?php
	}
}

/*
* Text with Button
*/
class IFDTextButtonWidget extends ThemeWidgetBase {
	/*
	* Register widget function. Must have the same name as the class
	*/
	function IFDTextButtonWidget() {
		$widget_opts = array(
			'classname' => 'home-top-text-button-widget', // class of the <li> holder
			'description' => __( 'Displays a block with title, text and button.' ) // description shown in the widget list
		);
		// Additional control options. Width specifies to what width should the widget expand when opened
		$control_ops = array(
			//'width' => 350,
		);
		// widget id, widget display title, widget options
		$this->WP_Widget('ifd-text-button-widget', 'Text - Button Widget', $widget_opts, $control_ops);
		$this->custom_fields = array(
			array(
				'name'=>'title', // field name
				'type'=>'text', // field type (text, textarea, integer etc.)
				'title'=>'Title', // title displayed in the widget form
				'default'=>'ARTICLE SEARCH' // default value
			),
			array(
				'name'=>'text',
				'type'=>'textarea',
				'title'=>'Content', 
				'default'=>'Lorem Ipsum dolor sit amet'
			),
			array(
				'name'=>'link',
				'type'=>'text',
				'title'=>'Link', 
				'default'=>'#'
			),
			array(
				'name'=>'link_caption',
				'type'=>'text',
				'title'=>'Link Caption', 
				'default'=>'Begin Search'
			)
		);
	}
	
	/*
	* Called when rendering the widget in the front-end
	*/
	function front_end($args, $instance) {
		extract($args);
		?>
		<h3 class="widgettitle"><?php echo $instance['title']; ?></h3>
		<div class="widgetbody">
			<p><?php echo $instance['text'];?></p>
			<a href="<?php echo $instance['link']; ?>" class="button"><span><?php echo $instance['link_caption']; ?></span></a>
		</div><!-- /.widgetbody -->
		<?php
	}
}
/*
* Latest News Widget
*/
class IDFLatestNews extends ThemeWidgetBase {
	/*
	* Register widget function. Must have the same name as the class
	*/
	function IDFLatestNews() {
		$widget_opts = array(
			'classname' => 'widget-news', // class of the <li> holder
			'description' => __( 'Displays a block with the latest news.' ) // description shown in the widget list
		);
		// Additional control options. Width specifies to what width should the widget expand when opened
		$control_ops = array(
			//'width' => 350,
		);
		// widget id, widget display title, widget options
		$this->WP_Widget('ifd-latest-news-widget', 'News Widget', $widget_opts, $control_ops);
		
		$this->custom_fields = array(
			array(
				'name'=>'title', // field name
				'type'=>'text', // field type (text, textarea, integer etc.)
				'title'=>'Title', // title displayed in the widget form
				'default'=>'RECENT NEWS' // default value
			),
			array(
				'name'=>'number_of_news',
				'type'=>'integer',
				'title'=>'Number of posts to show', 
				'default'=>2
			),
			array(
				'name'=>'link',
				'type'=>'text',
				'title'=>'Link', 
				'default'=>'#'
			),
			array(
				'name'=>'orderby',
				'type'=>'select',
				'title'=>'Order', 
				'options'=>array(
								'post_date' 	=> 	'Date',
								'menu_order'	=> 	'Menu Order',
								'random'		=> 	'Random'
								)
			),
			array(
				'name'=>'order',
				'type'=>'select',
				'title'=>'Sort by', 
				'options'=>array('DESC' => 'Descending', 'ASC'	=> 'Ascending')
			)
		);
	}
	
	/*
	* Called when rendering the widget in the front-end
	*/
	function front_end($args, $instance) {
		extract($args);
		$all_news = get_posts(array(
							'post_type'		=>	'post',
							'numberposts'	=>	$instance['number_of_news'],
							'order'			=>	$instance['order'],
							'orderby'		=>	$instance['orderby']
					));
		$hide_desription = FALSE;
		?>
		<h3 class="widgettitle"><?php echo $instance['title']; ?></h3>
		<ul>
		<?php foreach ($all_news as $news): 
		?>
			<li>
				<h4><?php echo $news->post_title; ?></h4>
				<p><?php echo ifd_get_excerpt_max_charlength($news->post_content, 45); ?><br />
				<a href="<?php echo get_permalink($news->ID); ?>">Read More</a></p>
			</li>
		<?php  $hide_desription = TRUE;	
			   endforeach ?>
		</ul>
		<?php
	}
}
/*
* Page Article Widget
*/
class IDFPageArticleWidget extends ThemeWidgetBase {
	/*
	* Register widget function. Must have the same name as the class
	*/
	function IDFPageArticleWidget() {
		$widget_opts = array(
			'classname' => 'widget-about', // class of the <li> holder
			'description' => __( 'Displays a block with the text and title of page.' ) // description shown in the widget list
		);
		// Additional control options. Width specifies to what width should the widget expand when opened
		$control_ops = array(
			//'width' => 350,
		);
		// widget id, widget display title, widget options
		$this->WP_Widget('ifd-widget-about', 'Page Widget', $widget_opts, $control_ops);
		$all_pages = get_posts(array(
							'post_type'		=>		'page',
							'numberposts'	=>		-1
					));
		$all_pages_options = array();
		foreach ($all_pages as $page) {
			$all_pages_options[$page->ID] = $page->post_title;
		}
		$this->custom_fields = array(
			array(
				'name'=>'title', // field name
				'type'=>'text', // field type (text, textarea, integer etc.)
				'title'=>'Title', // title displayed in the widget form
				'default'=>'WHO WE ARE' // default value
			),
			array(
				'name'=>'page',
				'type'=>'select',
				'title'=>'Page', 
				'options'=>$all_pages_options
			),
			array(
				'name'=>'what_to_use',
				'type'=>'select',
				'title'=>'Use', 
				'options'=>array('custom_title' => 'Custom Title', 'page_title'	=> 'Page Title')
			)
		);
	}
	
	/*
	* Called when rendering the widget in the front-end
	*/
	function front_end($args, $instance) {
		extract($args);
		$title = $instance['title'];
		$selected_post = get_post($instance['page']);
		if (empty($title) || ($instance['what_to_use'] == 'custom_title')) {
			$title = $selected_post->post_title;
		}
		?>
		<h3 class="widgettitle"><?php echo $instance['title']; ?></h3>
		<div class="widgetbody">
			<p><?php echo ifd_get_excerpt_max_charlength($selected_post->post_content, 500); ?> <a href="<?php echo get_permalink($selected_post->ID); ?>">Learn More&raquo;</a></p>
		</div><!-- /.widgetbody -->
		<?php
	}
}
/*
* Google Ads Widget - Horizontal
*/
class IDFGoogleAdsHorizontal extends ThemeWidgetBase {
	/*
	* Register widget function. Must have the same name as the class
	*/
	function IDFGoogleAdsHorizontal() {
		$widget_opts = array(
			'classname' => 'google-ads', // class of the <li> holder
			'description' => __( 'Google Ads Script.' ) // description shown in the widget list
		);
		// Additional control options. Width specifies to what width should the widget expand when opened
		$control_ops = array(
			//'width' => 350,
		);
		// widget id, widget display title, widget options
		$this->WP_Widget('ifd-ads-horizontal', 'Google Ads Horizontal', $widget_opts, $control_ops);
		
		$this->custom_fields = array(
			array(
				'name'=>'script', // field name
				'type'=>'textarea', // field type (text, textarea, integer etc.)
				'title'=>'Google Ads Script', // title displayed in the widget form
				'default'=>'' // default value
			)
		);
	}
	
	/*
	* Called when rendering the widget in the front-end
	*/
	function front_end($args, $instance) {
		extract($args);
		echo $instance['script'];
	}
}
/*
* Google Ads Widget
*/
class IDFGoogleAdsSquare extends ThemeWidgetBase {
	/*
	* Register widget function. Must have the same name as the class
	*/
	function IDFGoogleAdsSquare() {
		$widget_opts = array(
			'classname' => 'widget-square-ad', // class of the <li> holder
			'description' => __( 'Google Ads Script.' ) // description shown in the widget list
		);
		// Additional control options. Width specifies to what width should the widget expand when opened
		$control_ops = array(
			//'width' => 350,
		);
		// widget id, widget display title, widget options
		$this->WP_Widget('ifd-ads-square', 'Google Ads Square', $widget_opts, $control_ops);
		
		$this->custom_fields = array(
			array(
				'name'=>'script', // field name
				'type'=>'textarea', // field type (text, textarea, integer etc.)
				'title'=>'Google Ads Script', // title displayed in the widget form
				'default'=>'' // default value
			)
		);
	}
	
	/*
	* Called when rendering the widget in the front-end
	*/
	function front_end($args, $instance) {
		extract($args);
		echo $instance['script'];
	}
}
/*
* Feautured Doctors
*/
class IDFFeauturedDoctors extends ThemeWidgetBase {

	function IDFFeauturedDoctors() {
		$widget_opts = array(
			'classname' => 'widget-featured', // class of the <li> holder
			'description' => __( 'Feautured Doctors' ) // description shown in the widget list
		);
		// Additional control options. Width specifies to what width should the widget expand when opened
		$control_ops = array(
			//'width' => 350,
		);
		// widget id, widget display title, widget options
		$this->WP_Widget('ifd-feautured-doctors', 'Feautured Doctors', $widget_opts, $control_ops);
		
		$this->custom_fields = array(
			array(
				'name'=>'title', // field name
				'type'=>'text', // field type (text, textarea, integer etc.)
				'title'=>'Title', // title displayed in the widget form
				'default'=>'FEATURED DOCTORS' // default value
			),
			array(
				'name'=>'number_of_doctors', // field name
				'type'=>'integer', // field type (text, textarea, integer etc.)
				'title'=>'Number of Doctors', // title displayed in the widget form
				'default'=>3 // default value
			),
			array(
				'name'=>'order', // field name
				'type'=>'select', // field type (text, textarea, integer etc.)
				'title'=>'Order By', // title displayed in the widget form
				'options'=>array('DESC' => 'Descending', 'ASC' => 'Ascending'),
				'default'=>'DESC'
			),
			array(
				'name'=>'orderby', // field name
				'type'=>'select', // field type (text, textarea, integer etc.)
				'title'=>'Order', // title displayed in the widget form
				'options'=>array('post_date' => 'Date', 'menu_order' => 'As Ordered', 'random' => 'Random'),
				'default'=>'post_date'
			)
		);
	}
	

	function front_end($args, $instance) {
		extract($args);

		$doctors = get_posts(array(
						'post_type'	 => 'doctor',
						'meta_key'	 => '_doctor_featured',
						'meta_value' => 'featured',
						'order'		 => $instance['order'],
						'orderby'	 => $instance['orderby'],
						'numberposts'=> $instance['number_of_doctors']
				)); ?>
		<h3 class="widgettitle"><?php echo $instance['title']; ?></h3>
		<ul>
<?php	foreach ($doctors as $doctor): ?>
						<li>
							<a href="<?php echo get_permalink($doctor->ID); ?>" class="featured-thumb">
								<?php $thumbnail = wp_get_attachment_image_src ( get_post_thumbnail_id ( $doctor->ID ), 'thumbnail' ) ; 
								 ?>
								<img src="<?php bloginfo('stylesheet_directory'); ?>/scripts/tt.php?src=<?php echo $thumbnail[0]; ?>&q=100&zc=1&w=61&h=69" /> 
							</a>
							<div class="featured-body">
								<h4><?php echo $doctor->post_title; ?></h4>
								<p><?php echo ifd_get_excerpt_max_charlength($doctor->post_content, 185); ?></p>
							</div>
							<a href="<?php echo get_permalink($doctor->ID); ?>" class="button button-pink"><span>Read Full Bio</span></a>
							<div class="cl">&nbsp;</div>
						</li>
<?php	endforeach; ?>
		</ul>
<?php
	} 
}

/*
* Recent Posts Widget
*/
class IDFRecentPosts extends ThemeWidgetBase {
	/*
	* Register widget function. Must have the same name as the class
	*/
	function IDFRecentPosts() {
		$widget_opts = array(
			'classname' => 'widget-own-articles', // class of the <li> holder
			'description' => __( 'Recent Posts Widget' ) // description shown in the widget list
		);
		// Additional control options. Width specifies to what width should the widget expand when opened
		$control_ops = array(
			//'width' => 350,
		);
		// widget id, widget display title, widget options
		$this->WP_Widget('ifd-recent-posts', 'Recent Posts', $widget_opts, $control_ops);
		
		$this->custom_fields = array(
			array(
				'name'=>'number_of_posts', // field name
				'type'=>'integer', // field type (text, textarea, integer etc.)
				'title'=>'Number of Posts', // title displayed in the widget form
				'default'=>5 // default value
			)
		);
	}
	

	function front_end($args, $instance) {
		extract($args);
		$recent_posts = get_posts(array(
								'post_type'		=>		'post',
								'numberposts'	=>		$instance['number_of_posts']
							));
		echo '<h3>Recent Posts</h3>'; ?>
<ul>
<?php		foreach ($recent_posts as $blogpost) : ?>
			<li><h4><?php echo $blogpost->post_title; ?></h4><?php echo ifd_get_excerpt_max_charlength($blogpost->post_content, 140); ?> 
				<a href="<?php echo get_permalink($blogpost->ID); ?>">Read More</a></li>
<?php		endforeach; ?>
</ul>
<?php
	}
}
?>