<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/images/favicon.ico" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?v=2" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/print.css" type="text/css" media="print" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/jquery.jscrollpane.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/jquery.c2selectbox.css" type="text/css" media="all" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php  
if ( $post && $post->post_type == 'doctor' && $post->post_parent > 0 ) {
	wp_no_robots();
}
?>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<script type="text/javascript">
	var ifd_show_speed = <?php echo floatval(get_option('ifc_slideshow_speed')) * 1000; ?>;
</script>
<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
	<div class="shell">
		<div id="header">
			<div class="header-top-bar">

				<form role="search" method="get" id="searchform" action="<?php bloginfo('url'); ?>" class="site-search right">
					<input type="submit" value="" id="searchsubmit" class="submit" />
					<span class="field">
						<input type="text" value="<?php the_search_query(); ?>" name="s" />
					</span>
				</form>

				<div id="navigation">
					<?php  
						wp_nav_menu(array(
										'theme_location' 	=> 'header-menu',
										'container'      	=> '',
										'container_class'	=> false,
									));
					?>
				</div><!-- /#navigation -->
			</div><!-- /.header-top-bar -->

			<div class="header-right right">
				<ul class="widgets right">
					<li class="widget-social">
						<h3 class="widgettitle">FIND US ON</h3>
						<ul>
							<li><a target="_blank" href="<?php echo get_option('ifc_linkedin_link'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/linkedin.png" alt="" /></a></li>
							<li><a target="_blank" href="<?php echo get_option('ifc_facebook_link'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/fb.png" alt="" /></a></li>
							<li><a target="_blank" href="<?php echo get_option('ifc_twitter_link'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/twitter.png" alt="" /></a></li>
							<li><a target="_blank" href="<?php echo get_option('ifc_pinit_link'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/pinterest.png" alt="" /></a></li>
                            <li><a target="_blank" href="<?php echo get_option('ifc_googleplus_link'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/google_plus.png" alt="" /></a></li>
							<li><a target="_blank" href="<?php echo get_option('ifc_youtube_link'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/youtube.png" alt="" /></a></li>
						</ul>
					</li>
				</ul><!-- /.widgets -->
				<div class="cl">&nbsp;</div>

				<div class="site-info right">
					<p>The online presence of <em>Florida Health Care News</em></p>
					<div class="ribbon"></div>
				</div><!-- /.site-info -->
			</div><!-- /.header-right -->
			
			<div id="logo"><a href="<?php echo site_url(); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.png" alt="" /></a></div>
			<?php 
				global $post; 
				$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
				if ($post && $post->post_type == "article" && !ifd_is_term($current_term)) {
                    $article_date = get_the_time('F j, Y');
				}
			?>
			<p class="publish-date"><?php if (isset($article_date)) : ?><strong>Published:</strong> <?php echo $article_date; endif; ?></p>
			<div class="cl">&nbsp;</div>

		</div><!-- /#header -->