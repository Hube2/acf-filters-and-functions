<?php 
	
	/* 
		Custom location rule to add field group to any public taxonomy
	*/
	
	add_filter('acf/location/rule_values/taxonomy', 'acf_location_rules_values_taxonomy');
	function acf_location_rules_values_taxonomy($choices) {
		if (!isset($choices['public-taxonomy'])) {
			$choices['public-taxonomy'] = __('Public Taxonomy');
		}
		return $choices;
	}
	
	add_filter('acf/location/rule_match/taxonomy', 'acf_location_rules_match_public_taxonomy', 10, 3);
	function acf_location_rules_match_public_taxonomy($match, $rule, $options) {
		if ($rule['value'] != 'public-taxonomy') {
			return $match;
		}
		if (!isset($options['taxonomy'])) {
			return false;
		}
		$taxonomy = $options['taxonomy'];
		$taxonomies = get_taxonomies(array(), 'objects');
		if (!isset($taxonomies[$taxonomy])) {
			return false;
		}
		$public = $taxonomies[$taxonomy]->public;
		if ($rule['operator'] == '==') {
			$match = $public;
		} elseif ($rule['operator'] == '!=') {
			$match = !$public;
		}
		return $match;
	}
	
?>
