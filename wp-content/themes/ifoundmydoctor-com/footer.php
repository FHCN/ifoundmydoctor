		<div id="footer">
			<div class="footer-t">
				<?php get_sidebar('footer'); ?>

				<div class="footer-contact right">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/fhcn_footer_logo.png" alt="" />
					<p>
						<?php echo get_option('ifc_address'); ?><br />
						<?php echo get_option('ifc_telephones'); ?>
						<a href="mailto:<?php echo get_option('ifc_email'); ?>"><?php echo get_option('ifc_email'); ?></a>
					</p>
				</div><!-- /.footer-contact -->
				<div class="cl">&nbsp;</div>

				<div class="copyrights left">
					<p>&copy; <?php echo date('Y'); ?>  <?php echo get_option('ifc_copyrights'); ?></p>
					<p class="bottom-header-menu">
						<?php
							wp_nav_menu(array(
									'theme_location'	=> 		'footer-bottom-menu',
									'container'			=> 		'',
									'container_class' 	=>		false,
									'items_wrap'		=>		'%3$s',
									'after'		=>		'<span>|</span>',
									'walker'			=>		new IFDFooterWalker()
								));
						?>
					</p>
				</div>

				<div class="footer-logo left"><a href="<?php echo site_url(); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/footer-logo.png" alt="" /></a></div>

				<div class="design-by right">
					<a target="_blank" href="http://www.leveragedigitalmedia.com/">
						Web Design by
					</a>
				</div>
				<div class="cl">&nbsp;</div>
			</div><!-- /.footer-t -->
		</div><!-- /#footer -->
	</div><!-- /.shell -->
	<?php wp_footer(); ?>
</body>
</html>
