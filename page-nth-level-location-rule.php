<?php 
	
	/* 
		ACF custom location rule : Page Level
		level "1" = top level parent page
		this should work on any hierarchical post type?
	*/
	
	add_filter('acf/location/rule_types', 'acf_location_rules_page_level');
	function acf_location_rules_page_level($choices) {
		$choices['Page']['page_level'] = 'Page Level';
		return $choices;
	}
	
	add_filter('acf/location/rule_operators', 'acf_location_rules_page_level_operators');
	function acf_location_rules_page_level_operators($choices) {
		// remove operators that you do not need
		$new_choices = array(
			'<' => 'is less than',
			'<=' => 'is less than or equal to',
			'>=' => 'is greater than or equal to',
			'>' => 'is greater than'
		);
		foreach ($new_choices as $key => $value) {
			$choices[$key] = $value;
		}
		return $choices;
	}
	
	add_filter('acf/location/rule_values/page_level', 'acf_location_rules_values_page_level');
	function acf_location_rules_values_page_level($choices) {
		// adjust the for loop to the number of levels you need
		for($i=1; $i<=10; $i++) {
			$choices[$i] = $i;
		}
		return $choices;
	}
	
	add_filter('acf/location/rule_match/page_level', 'acf_location_rules_match_page_level', 10, 3);
	function acf_location_rules_match_page_level($match, $rule, $options) {
		if (!isset($options['post_id'])) {
			return $match;
		}
		$post_type = get_post_type($options['post_id']);
		$page_parent = 0;
		if (!$options['page_parent']) {
			$post = get_post($options['post_id']);
			$page_parent = $post->post_parent;
		} else {
			$page_parent = $options['page_parent'];
		}
		if (!$page_parent) {
			$page_level = 1;
		} else {
			$ancestors = get_ancestors($page_parent, $post_type);
			$page_level = count($ancestors) + 2;
		}
		$operator = $rule['operator'];
		$value = $rule['value'];
		switch ($operator) {
			case '==':
				$match = ($page_level == $value);
				break;
			case '!=':
				$match = ($page_level != $value);
				break;
			case '<':
				$match = ($page_level < $value);
				break;
			case '<=':
				$match = ($page_level <= $value);
				break;
			case '>=':
				$match = ($page_level >= $value);
				break;
			case '>':
				$match = ($page_level > $value);
				break;
		} // end switch
		return $match;
	}
	
?>
