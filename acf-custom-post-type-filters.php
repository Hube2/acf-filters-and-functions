<?php 
	
	// generic custom post type filter
	// to match a post of a custom post type
	
	// to a search for 
	//		"my_custom_post_type" and "My Custom Post Type"
	// and replace with your post type slug and name
	
	// add group and filter
	add_filter('acf/location/rule_types', 'acf_my_custom_post_type_filters');
	function acf_my_custom_post_type_filters($choices) {
		// we want to insert it after pages
		// so it's in a nice order
		if (!isset($choices['My Custom Post Type'])) {
			$new_choices = array();
			foreach ($choices as $key => $value) {
				$new_choices[$key] = $value;
				if ($key == 'Page') {
					$new_choices['My Custom Post Type'] = array();
				}
			} // end foreach choices
			$choices = $new_choices;
		} // end if not in choices
		if (!isset($choices['My Custom Post Type']['post'])) {
			$choices['My Custom Post Type']['my_custom_post_type_post'] = 'My Custom Post Type Post';
		}
		return $choices;
	}
	
	// add choices
	add_filter('acf/location/rule_values/my_custom_post_type_post', 'acf_location_rules_values_my_custom_post_type');
	function acf_location_rules_values_my_custom_post_type($choices) {
		// adjust the for loop to the number of levels you need
		$args = array(
			'post_type' => 'my_custom_post_type',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => array('title' => 'ASC', 'date' => 'DESC'),
		);
		$query = new WP_Query($args);
		//echo '<pre>'; print_r($query->posts); echo '</pre>';
		if (count($query->posts)) {
			foreach ($query->posts as $post) {
				$choices[$post->ID] = $post->post_title;
			}
		}
		return $choices;
	}
	
	// use the standard post matching
	// this is copied directly form ACF
	add_filter('acf/location/rule_match/my_custom_post_type_post', 'acf_location_rule_match_my_custom_post_type_post', 10, 3);
	function acf_location_rule_match_my_custom_post_type_post($match, $rule, $options) {
		$post_id = $options['post_id'];
		if( !$post_id ) {
			return false;
		}
		if ($rule['operator'] == "==") {
			$match = ($options['post_id'] == $rule['value']);
		} elseif ($rule['operator'] == "!=") {
			$match = ($options['post_id'] != $rule['value']);
		}
		return $match;
	}
	
?>
