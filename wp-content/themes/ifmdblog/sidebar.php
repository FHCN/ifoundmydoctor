<div id="sidebar">
	<ul class="widgets">
		<?php
			$sidebar = get_post_meta(get_the_id(), '_custom_sidebar', true);
			if (!$sidebar) {
				$sidebar = 'Default Sidebar';
			}

			dynamic_sidebar($sidebar);
		?>
	</ul>
</div>