<?php 
	
	// category ancestor location rule
	
	add_filter('acf/location/rule_types', 'acf_location_types_category_ancestor');
	function acf_location_types_category_ancestor($choices) {
		if (!isset($choices['Post']['post_category_ancestor'])) {
			$choices['Post']['post_category_ancestor'] = 'Post Category Ancestor';
		}
		return $choices;
	}
	
	add_filter('acf/location/rule_values/post_category_ancestor', 'acf_location_rule_values_category_ancestor');
	function acf_location_rule_values_category_ancestor($choices) {
		// copied from acf rules values for post_category
		$terms = acf_get_taxonomy_terms( 'category' );
		if(!empty($terms)) {
			$choices = array_pop($terms);
		}
		return $choices;
	}
	
	add_filter('acf/location/rule_match/post_category_ancestor', 'acf_location_rule_match_category_ancestor', 10, 3);
	function acf_location_rule_match_category_ancestor($match, $rule, $options) {
		// most of this copied directly from acf post category rule
		$terms = $options['post_taxonomy'];
		$data = acf_decode_taxonomy_term($rule['value']);
		$term = get_term_by('slug', $data['term'], $data['taxonomy']);
		if (!$term && is_numeric($data['term'])) {
			$term = get_term_by('id', $data['term'], $data['taxonomy']);
		}
		// this is where it's different than ACf
		// get terms so we can look at the parents
		if (is_array($terms)) {
			foreach ($terms as $index => $term_id) {
				$terms[$index] = get_term_by('id', intval($term_id), $term->taxonomy);
			}
		}
		if (!is_array($terms) && $options['post_id']) {
			$terms = wp_get_post_terms(intval($options['post_id']), $term->taxonomy);
		}
		if (!is_array($terms)) {
			$terms = array($terms);
		}
		$terms = array_filter($terms);
		$match = false;
		// collect a list of ancestors
		$ancestors = array();
		if (count($terms)) {
			foreach ($terms as $term_to_check) {
				$ancestors = array_merge(get_ancestors($term_to_check->term_id, $term->taxonomy));
			} // end foreach terms
		} // end if
		// see if the rule matches any term ancetor
		if ($term && in_array($term->term_id, $ancestors)) {
			$match = true;
		}
		
		if ($rule['operator'] == '!=') {
			// reverse the result
			$match = !$match;
		}
		return $match;
	}
	
?>
