<?php 
	
	/*
		ACF shows the menu_title as the choice for location rules
		can be confusing when mutltiple options pages have the same menu title
		this filter will change the display to the page title
	*/
	
	add_filter('acf/location/rule_values/options_page', 'options_page_rule_values_titles', 20);
	function options_page_rule_values_titles($choices) {
		$pages = acf_get_options_pages();
		if (!$pages) {
			return $choices;
		}
		foreach ($pages as $page) {
			$choices[$page['menu_slug']] = $page['page_title'];
		}
		return $choices;
	}
	
?>
