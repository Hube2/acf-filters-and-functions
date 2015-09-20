<?php 
	/* 
		ACF custom location rule : Page Ancestor
	*/
	add_filter('acf/location/rule_types', 'acf_location_rules_page_ancestor');
	function acf_location_rules_page_ancestor($choices) {
		$choices['Page']['page_ancestor'] = 'Page Ancestor';
		return $choices;
	}
	
	add_filter('acf/location/rule_values/page_ancestor', 'acf_location_rules_values_page_ancestor');
	function acf_location_rules_values_page_ancestor($choices) {
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
	
	add_filter('acf/location/rule_match/page_ancestor', 'acf_location_rules_match_page_ancestor', 10, 3);
	function acf_location_rules_match_page_ancestor($match, $rule, $options) {
		// this code is with inspiration from
		// acf_location::rule_match_page_parent()
		// check parents recursively to see if any
		// matches the location value
		if (isset($options['page_parent']) && $options['page_parent']) {
			$page_parent = $options['page_parent'];
			unset($options['page_parent']);
		} elseif (isset($options['post_id']) && $options['post_id']) {
			$post = get_post($options['post_id']);
			$page_parent = $post->post_parent;
		}
		$ancestors = array();
		if ($page_parent) {
			$ancestors = get_ancestors($page_parent, 'page');
			$ancestors[] = $page_parent;
		}
		if ($rule['operator'] == "==") {
			$match = in_array($rule['value'], $ancestors);
		} elseif ($rule['operator'] == "!=") {
			$match = !in_array($rule['value'], $ancestors);
		}
		return $match;
	}
	
?>
