<?php 
	
	function acf_reciprocal_relationship($value, $post_id, $field) {
		
		// set the two fields that you want to create
		// a two way relationship for
		// these values can be the same field key
		
		// the field key of one side of the relationship
		$key_a = 'field_0123456789abc';
		// the field key of the other side of the relationship
		// as noted above, this can be the same as $key_a
		$key_b = 'field_cba9876543210';
		
		// figure out wich side we're doing and set up variables
		// if the keys are the same above then this won't matter
		if ($key_a != $field['key']) {
			// this is side b, swap the value
			$temp = $key_a;
			$key_a = $key_b;
			$key_b = $temp;
		}
		
		// get both fields
		// this gets them by using an acf function
		// that can gets field objects based on field keys
		$field_a = acf_get_field($key_a);
		$field_b = acf_get_field($key_b);
		
		// set the field names to check
		$name_a = $field_a['name'];
		$name_b = $field_b['name'];
		
		// get the old value from the current post
		// compare it to the new value to see
		// if anything needs to be updated
		// use get_post_meta() to a avoind conflicts
		$old_values = get_post_meta($post_id, $name_a, true);
		if (!is_array($old_values)) {
			if (empty($old_values)) {
				$old_values = array();
			} else {
				$old_values = array($old_values);
			}
		}
		// set new values to the above
		$new_values = $value;
		
		// get differences
		// array_diff returns an array of values from the fires array that are no in the second array
		$add = array_diff($new_values, $old_values);
		$delete = array_diff($old_values, $new_values);
		
		if (!count($add) && !count($delete)) {
			// nothing to do
			return;
		}
		
		// we need to know the format to save the values
		// of the related post in
		// if it is a relationship field or a post object field
		// then we save is as an array
		// otherwise we only save a single value
		$save_array = true;
		if ($field_b['type'] == 'post_object' && !$field_b['muliple']) {
			$save_array = false;
		}
		
		// do deletes first
		for ($i=0; $i<count($delete); $i++) {
			$related_values = get_post_meta($delete[$i], $name_b, true);
			if (!is_array($related_values)) {
				if (empty($related_values)) {
					$related_values = array();
				} else {
					$related_values = array($related_values);
				}
			}
			$related_values = array_diff($related_values, array($delete[$i]));
		}
		
		
		// check the field type of field b
		// and see if it can have multiple values
		// we will set it to 0 (zero) for unlimited
		$max_values = 0;
		if ($field_b['type'] == 'relationship') {
			if ($field_b['max']) {
				// there is a maximum number of values allowed
				// in the related post relationship field
				$max_values = $field_b['max'];
			}
		} else {
			// $field_b['type'] == post_object
			if (!$field_b['multiple']) {
				// post object field does not allow multiple selections
				$max_values = 1;
			}
		}
		
		
	} // end function acf_reciprocal_relationship
	
?>