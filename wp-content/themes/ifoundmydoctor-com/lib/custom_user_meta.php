<?php
	class win_custom_user_meta {
	       
	        function win_custom_user_meta() {
	            if ( is_admin() ) {
	                add_action('show_user_profile', array(&$this,'action_show_user_profile'));
	                add_action('edit_user_profile', array(&$this,'action_show_user_profile'));
	                add_action('personal_options_update', array(&$this,'action_process_option_update'));
	                add_action('edit_user_profile_update', array(&$this,'action_process_option_update'));
	            }
	        }
	       
	        function action_show_user_profile($user) {
	            ?>
	            <h3><?php _e('Other Details'); ?></h3>
	       
	            <table class="form-table">
	            <tr>
	                <th><label for="win_buisness_name"><?php _e('Business Name'); ?></label></th>
	                <td><input type="text" name="win_buisness_name" id="win_buisness_name" value="<?php echo esc_attr(get_the_author_meta('win_buisness_name', $user->ID) ); ?>" /></td>
	            </tr>
	            <tr>
	                <th><label for="win_member_name"><?php _e('Member Name'); ?></label></th>
	                <td><input type="text" name="win_member_name" id="win_member_name" value="<?php echo esc_attr(get_the_author_meta('win_member_name', $user->ID) ); ?>" /></td>
	            </tr>
	            <tr>
	                <th><label for="win_buisness_description"><?php _e('Business Description'); ?></label></th>
	                <td><textarea name="win_buisness_description" id="win_buisness_description"><?php echo esc_attr(get_the_author_meta('win_buisness_description', $user->ID) ); ?></textarea></td>
	            </tr>
	            <tr>
	                <th><label for="win_phone_number"><?php _e('Phone Number'); ?></label></th>
	                <td><input type="text" name="win_phone_number" id="win_phone_number" value="<?php echo esc_attr(get_the_author_meta('win_phone_number', $user->ID) ); ?>" /></td>
	            </tr>
	            <tr>
	                <th><label for="win_cell_phone_number"><?php _e('Cell Phone Number'); ?></label></th>
	                <td><input type="text" name="win_cell_phone_number" id="win_cell_phone_number" value="<?php echo esc_attr(get_the_author_meta('win_cell_phone_number', $user->ID) ); ?>" /></td>
	            </tr>
	            <tr>
	                <th><label for="win_other_number"><?php _e('Other Number'); ?></label></th>
	                <td><input type="text" name="win_other_number" id="win_other_number" value="<?php echo esc_attr(get_the_author_meta('win_other_number', $user->ID) ); ?>" /></td>
	            </tr>
	            <tr>
	                <th><label for="win_website"><?php _e('Website'); ?></label></th>
	                <td><input type="text" name="win_website" id="win_website" value="<?php echo esc_attr(get_the_author_meta('win_website', $user->ID) ); ?>" /></td>
	            </tr>
	            <tr>
	                <th><label for="win_other"><?php _e('Other'); ?></label></th>
	                <td><input type="text" name="win_other" id="win_other" value="<?php echo esc_attr(get_the_author_meta('win_other', $user->ID) ); ?>" /></td>
	            </tr>
	           	<tr class="multiple-editors">
	           		<th><label for="">Location</label></th>
	           		<div class="editors">
					<?php wp_editor('', 'test', array('textarea_rows'=>intval(10))); ?>
	           		</div>
	           		<a id="add_new_location" href="#">Add New Location</a>
	           	</tr>

	            </table>
	            <?php
	        }
	       
	        function action_process_option_update($user_id) {
	            update_usermeta($user_id, 'win_buisness_name', ( isset($_POST['win_buisness_name']) ? $_POST['win_buisness_name'] : '' ) );
	            update_usermeta($user_id, 'win_member_name', ( isset($_POST['win_member_name']) ? $_POST['win_member_name'] : '' ) );
	            update_usermeta($user_id, 'win_buisness_description', ( isset($_POST['win_buisness_description']) ? $_POST['win_buisness_description'] : '' ) );
	            update_usermeta($user_id, 'win_phone_number', ( isset($_POST['win_phone_number']) ? $_POST['win_phone_number'] : '' ) );
	            update_usermeta($user_id, 'win_cell_phone_number', ( isset($_POST['win_cell_phone_number']) ? $_POST['win_cell_phone_number'] : '' ) );
	            update_usermeta($user_id, 'win_other_number', ( isset($_POST['win_other_number']) ? $_POST['win_other_number'] : '' ) );
	            update_usermeta($user_id, 'win_website', ( isset($_POST['win_website']) ? $_POST['win_website'] : '' ) );
	            update_usermeta($user_id, 'win_other', ( isset($_POST['win_other']) ? $_POST['win_other'] : '' ) );
	            update_usermeta($user_id, 'win_buisness_category', ( isset($_POST['win_buisness_category']) ? $_POST['win_buisness_category'] : '' ) );
	            update_usermeta($user_id, 'win_chapter', ( isset($_POST['win_chapter']) ? $_POST['win_chapter'] : '' ) );
	        }
	
	}
	
	/* Initialise outselves */
	add_action('init', create_function('','global $custom_user_meta_instance; $custom_user_meta_instance = new win_custom_user_meta();'));