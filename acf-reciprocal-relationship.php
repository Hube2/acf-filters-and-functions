<?php 

	/*
		This proposition is to convert the original function to a dynamic one so the two field IDs are not hardcoded.
	*/

	/* 
		this function shows how to create a simple two way relationship field
		the example assumes that you are using either a single relationship field
		where posts of the same type are related or you can have 2 relationship
		fields on two different post types. this example also assumes that
		the relationship field(s) do not impose any limits on the number
		of selections
		
		The concept covered in this file has also been coverent on the ACF site
		on this page https://www.advancedcustomfields.com/resources/bidirectional-relationships/
		The example shown there is very similar, but requires but is created to work
		where the field name is the same, similar to my plugin that does this.
		This example will let you have fields of different names
	*/

	// Creates a relationship field setting to link to reciprocal field
	add_filter('acf/render_field_settings/type=relationship', 'acf_relationship_reciprocal_setting');
	function acf_relationship_reciprocal_setting($field) {
		$args = array(
			'name' => 'relate_to_key',
			'type' => 'text',
			'label' => __('Two way relate to'),
			'instructions' => __('The field key to relate to.'),
		);
		acf_render_field_setting($field, $args);
	}

	add_filter('acf/update_value/type=relationship', 'acf_reciprocal_relationship', 10, 3);
	
	function acf_reciprocal_relationship($value, $post_id, $field) {
		
		if (!empty($field['relate_to_key'])) {
			
			// set one key variable to the current field key
			// set the other to its related field in field settings
			$key_a = $field['key'];
			$key_b = $field['relate_to_key'];
		
			// get both fields
			// this gets them by using an acf function
			// that can gets field objects based on field keys
			// we may be getting the same field, but we don't care
			$field_a = acf_get_field($key_a);
			$field_b = acf_get_field($key_b);
			
			// set the field names to check
			// for each post
			$name_a = $field_a['name'];
			$name_b = $field_b['name'];
			
			// get the old value from the current post
			// compare it to the new value to see
			// if anything needs to be updated
			// use get_post_meta() to a avoid conflicts
			$old_values = get_post_meta($post_id, $name_a, true);
			// make sure that the value is an array
			if (!is_array($old_values)) {
				if (empty($old_values)) {
					$old_values = array();
				} else {
					$old_values = array($old_values);
				}
			}
			// set new values to $value
			// we don't want to mess with $value
			$new_values = $value;
			// make sure that the value is an array
			if (!is_array($new_values)) {
				if (empty($new_values)) {
					$new_values = array();
				} else {
					$new_values = array($new_values);
				}
			}
			
			// get differences
			// array_diff returns an array of values from the first
			// array that are not in the second array
			// this gives us lists that need to be added
			// or removed depending on which order we give
			// the arrays in
			
			// this line is commented out, this line should be used when setting
			// up this filter on a new site. getting values and updating values
			// on every relationship will cause a performance issue you should
			// only use the second line "$add = $new_values" when adding this
			// filter to an existing site and then you should switch to the
			// first line as soon as you get everything updated
			// in either case if you have too many existing relationships
			// checking end updated every one of them will more then likely
			// cause your updates to time out.
			//$add = array_diff($new_values, $old_values);
			$add = $new_values;
			$delete = array_diff($old_values, $new_values);
			
			// reorder the arrays to prevent possible invalid index errors
			$add = array_values($add);
			$delete = array_values($delete);
			
			if (!count($add) && !count($delete)) {
				// there are no changes
				// so there's nothing to do
				return $value;
			}
			
			// do deletes first
			// loop through all of the posts that need to have
			// the recipricol relationship removed
			for ($i=0; $i<count($delete); $i++) {
				$related_values = get_post_meta($delete[$i], $name_b, true);
				if (!is_array($related_values)) {
					if (empty($related_values)) {
						$related_values = array();
					} else {
						$related_values = array($related_values);
					}
				}
				// we use array_diff again
				// this will remove the value without needing to loop
				// through the array and find it
				$related_values = array_diff($related_values, array($post_id));
				// insert the new value
				update_post_meta($delete[$i], $name_b, $related_values);
				// insert the acf key reference, just in case
				update_post_meta($delete[$i], '_'.$name_b, $key_b);
			}
			
			// do additions, to add $post_id
			for ($i=0; $i<count($add); $i++) {
				$related_values = get_post_meta($add[$i], $name_b, true);
				if (!is_array($related_values)) {
					if (empty($related_values)) {
						$related_values = array();
					} else {
						$related_values = array($related_values);
					}
				}
				if (!in_array($post_id, $related_values)) {
					// add new relationship if it does not exist
					$related_values[] = $post_id;
				}
				// update value
				update_post_meta($add[$i], $name_b, $related_values);
				// insert the acf key reference, just in case
				update_post_meta($add[$i], '_'.$name_b, $key_b);
			}
			
			return $value;
		}
		
	} // end function acf_reciprocal_relationship
	
?>
