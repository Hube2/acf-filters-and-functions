<?php 
	
	/*
			ACF Field Label Functions
			
			Get or Display labels for fields and field choices
			
			Most of these function work just like the acf functions for fields and explanations 
			should not be necessary, see the acf documentation. Most of this code in these 
			functiona is copied directly from the acf functions for field and sub field value.
			
			These function have the same requirements and work in the same way and places as 
			the ACF function get_field(), the_field(), get_sub_field(), and the_sub_field()
			
			The following fields should be self explanatory if you can use ACF functions:
			
				- get_field_label($field_name[, $post_id])
				- the_field_label($field_name[, $post_id])
				
				- get_sub_field_label($field_name)
				- the_sub_field_label($field_name)
				
			The following function are for getting choice labels from fields that have choices 
			like select, radio or checkbox fields
			
				- get_field_choice_label($field_name, $value[, $post_id])
				- the_field_choice_label($field_name, $value[, $post_id])
					
					Example:
						
						$value = get_field('select');
						$label = get_field_choice_label('select', $value);
						echo $label,': ',$value,'<br />';
						
				- get_sub_field_choice_label($selector, $value)
				- the_sub_field_choice_label($selector, $value)
					
					Example:
						
						if (have_rows('repeater')) {
							while(have_rows('repeater')) {
								the_row();
								$values = get_sub_field('checkbox');
								if ($values) {
									foreach ($values as $value) {
										the_sub_field_choice_label('checkbox', $value);
										echo '<br />';
									}
								}
							}
						}
	*/
	
	// this is done on plugins_loaded to make sure that ACF is loaded
	add_action('plugins_loaded', 'acf_label_functions');
	function acf_label_functions() {
		// make sure acf is active
		if (!class_exists('acf')) {
			return;
		}
		// check if functions exist just in case they get added to acf
		if (!function_exists('get_field_label')) {
			function get_field_label($selector, $post_id=false) {
				$post_id = acf_get_valid_post_id($post_id);
				$field = acf_maybe_get_field($selector, $post_id);
				if (!$field) {
					return NULL;
				}
				return $field['label'];
			} // end function get_field_label
		} // end !function
		if (!function_exists('the_field_label')) {
			function the_field_label($selector, $post_id=false) {
				echo get_field_label($selector, $post_id);
			} // end function the_field_label
		} // end !function
		if (!function_exists('get_sub_field_label')) {
			function get_sub_field_label($selector) {
				$row = acf_get_loop('active');
				if (!$row) {
					return NULL;
				}
				$sub_field = get_row_sub_field($selector);
				if (!$sub_field) {
					return NULL;
				}
				return $sub_field['label'];
			} // end function get_sub_field_label
		} // end !function
		if (!function_exists('the_sub_field_label')) {
			function the_sub_field_label($selector) {
				echo get_sub_field_label($selector);
			} // end function the_sub_field_label
		} // end !function
		if (!function_exists('get_field_choice_label')) {
			function get_field_choice_label($selector, $value, $post_id=false) {
				$post_id = acf_get_valid_post_id($post_id);
				$field = acf_maybe_get_field($selector, $post_id);
				if (!$field || !isset($field['choices']) || !isset($field['choices'][$value])) {
					return NULL;
				}
				return $field['choices'][$value];
			} // end function get_field_choice_label
		} // end !function
		if (!function_exists('the_field_choice_label')) {
			function the_field_choice_label($selector, $value, $post_id=false) {
				echo get_field_choice_label($selector, $value, $post_id);
			} // end function the_field_choice_label
		} // end if !function
		if (!function_exists('get_sub_field_choice_label')) {
			function get_sub_field_choice_label($selector, $value) {
				$row = acf_get_loop('active');
				if (!$row) {
					return NULL;
				}
				$sub_field = get_row_sub_field($selector);
				if (!$sub_field || !isset($sub_field['choices']) || !isset($sub_field['choices'][$value])) {
					return NULL;
				}
				return $sub_field['choices'][$value];
			} // end function get_sub_field_choice_label
		} // end !function
		if (!function_exists('the_sub_field_choice_label')) {
			function the_sub_field_choice_label($selector, $value) {
				echo get_sub_field_choice_label($selector, $value);
			} // end function the_sub_field_choice_label
		} // end !function
	} // end function acf_label_functions
	
?>