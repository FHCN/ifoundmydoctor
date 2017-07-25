<div id="sidebar">
	<ul class="widgets">
		<?php
		
			$sidebar = get_post_meta(get_the_id(), '_blog_custom_sidebar', 1);
			if (!$sidebar) {
				$sidebar = 'Default Blog Sidebar';
			}
			dynamic_sidebar($sidebar);
		?>
	</ul>
</div>