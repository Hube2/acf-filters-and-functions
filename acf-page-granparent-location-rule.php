<?php 
	
	/*
		Adds a custom location rule to ACF to select page grandparent
	*/
	
	add_filter('acf/location/rule_types', 'acf_location_rules_page_grandparent');
	function acf_location_rules_page_grandparent($choices) {
		$choices['Page']['page_grandparent'] = 'Page Grandparent';
		return $choices;
	}
	
	add_filter('acf/location/rule_values/page_grandparent', 'acf_location_rules_values_page_grandparent');
	function acf_location_rules_values_page_grandparent($choices) {
		// this code is copied directly from 
		// render_location_values() 
		// case "page"
		$groups = acf_get_grouped_posts(array(
			'post_type' => 'page'
		));
		if (!empty($groups)) {
			foreach(array_keys($groups) as $group_title) {
				$posts = acf_extract_var($groups, $group_title);
				foreach(array_keys($posts) as $post_id) {
					$posts[$post_id] = acf_get_post_title($posts[$post_id]);
				};
				$choices = $posts;
			}
		}
		// end of copy from ACF
		return $choices;
	}
	
	add_filter('acf/location/rule_match/page_grandparent', 'acf_location_rules_match_page_grandparent', 10, 3);
	function acf_location_rules_match_page_grandparent($match, $rule, $options) {
		// this code is with inspiration from
		// acf_location::rule_match_page_parent()
		// with addition of adding grandparent check
		$post_grandparent = 0;
		if (isset($options['page_parent']) && $options['page_parent']) {
			$parent = get_post($options['page_parent']);
			if ($parent->post_parent) {
				$post_grandparent = $parent->post_parent;
			}
		} elseif (isset($options['post_id']) && $options['post_id']) {
			$post = get_post($options['post_id']);
			if ($post->post_parent) {
				$parent = get_post($post->post_parent);
				if ($parent->post_parent) {
					$post_grandparent = $parent->post_parent;
				}
			}
		}
		if (!$post_grandparent) {
			return false;
		}
		if ($rule['operator'] == "==") {
			$match = ($post_grandparent == $rule['value']);
		} elseif ($rule['operator'] == "!=") {
			$match = ($post_grandparent != $rule['value']);
		}
		return $match;
	}
	
?>
