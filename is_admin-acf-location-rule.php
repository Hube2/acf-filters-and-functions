<?php 
  
  /*
    Location rule to for is_admin
  */
		
	add_action('acf/location/rule_types', 'acf_add_special_rule_type');
	function acf_add_special_rule_type($choices) {
		if (!isset($choices['Special'])) {
			$choices['Special'] = array();
		}
		if (!isset($choices['Special']['is_admin'])) {
			$choices['Special']['is_admin'] = 'is_admin';
		}
		return $choices;
	}
	add_filter('acf/location/rule_values/is_admin', 'acf_location_rules_values_special_is_admin');
	function acf_location_rules_values_special_is_admin($choices) {
		$choices['true'] = 'true';
		$choices['false'] = 'false';
		return $choices;
	}
	add_filter('acf/location/rule_match/is_admin', 'acf_location_rules_match_is_admin', 10, 3);
	function acf_location_rules_match_is_admin($match, $rule, $options) {
		if ($rule['param'] != 'is_admin') {
			return $match;
		}
		if ($rule['operator'] == '==') {
			$match = is_admin();
		} elseif ($rule['operator'] == '!=') {
			$match = !is_admin();
		}
		if ($rule['value'] != 'true') {
			$match = !$match;
		}
		return $match;
	}
	
?>
