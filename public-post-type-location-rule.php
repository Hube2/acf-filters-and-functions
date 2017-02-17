<?php 
	
	/* 
		Custom location rule to add field group to any public post type
	*/
	
	add_filter('acf/location/rule_values/post_type', 'acf_location_rules_values_public_post_type');
		
	function acf_location_rules_values_public_post_type($choices) {
		if (!isset($choices['public-post-type'])) {
			$choices['public-post-type'] = __('Public Post Type');
		}
		return $choices;
	}
	
	add_filter('acf/location/rule_match/post_type', 'acf_location_rules_match_public_post_type', 10, 3);
		
	function acf_location_rules_match_public_post_type($match, $rule, $options) {
		if ($rule['value'] != 'public-post-type' || $options['comment']) {
			return $match;
		}
		if (isset($options['post_type'])) {
			$post_type = $options['post_type'];
		} else {
			if (!isset($options['post_id'])) {
				return false;
			}
			$post_type = get_post_type(intval($options['post_id']));
		}
		$post_type = get_post_type_object($post_type);
		if (!$post_type) {
			return false;
		}
		if ($rule['operator'] == '==') {
			$match = $post_type->public;
		} elseif ($rule['operator'] == '!=') {
			$match = !$post_type->public;
		}
		return $match;
	}
	
?>
