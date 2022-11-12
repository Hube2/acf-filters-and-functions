<?php 
	
	/* 
		ACF custom location rule : Page Level
		level "1" = top level parent page
		this should work on any hierarchical post type?
		Works on number of ancestors
	*/
	
	if (class_exists('ACF_LOCATION')) {
		
		class location_page_level_jh extends ACF_LOCATION {
			
			function initialize() {
				$this->name = 'page_level';
				$this->label = 'Page (Post) Level';
				$this->category = 'page';
			} // end function initialize
			
			static function get_operators($rule) {
				$operators = array(
					'!=' => 'is not equal to',
					'<' => 'is less than',
					'<=' => 'is less than or equal to',
					'==' => 'is equal to',
					'>=' => 'is greater and or equal to',
					'>' => 'is greater than'
				);
				return $operators;
			} // end static function get_operators
			
			public function get_values($rule) {
				// value indicates number of ancestors
				$value_add = array(
					' (Parent)',
					' (Child)',
					' (Grandchild)'
				);
				$values = array();
				for ($i=0; $i<10; $i++) {
					$values[$i] = strval($i+1);
					if (isset($value_add[$i])) {
						$values[$i] .= $value_add[$i];
					}
				} // end for
				return $values;
			} // end public function get_values
			
			public function match($rule, $screen, $field_group) {
				$match = false;
				if (!isset($screen['post_id'])) {
					return $match;
				}
				if (!isset($screen['page_parent'])) {
					$ancestors = count(get_ancestors($screen['post_id'], $screen['post_type'], 'post_type'));
				} else {
					$ancestors = count(get_ancestors($screen['page_parent'], $screen['post_type'], 'post_type'))+1;
				}
				switch ($rule['operator']) {
					case '!=':
						$match = ($rule['value'] != $ancestors);
						break;
					case '<':
						$match = ($rule['value'] < $ancestors);
						break;
					case '<=':
						$match = ($rule['value'] <= $ancestors);
						break;
					case '==':
						$match = ($rule['value'] == $ancestors);
						break;
					case '>=':
						$match = ($rule['value'] >= $ancestors);
						break;
					case '>':
						$match = ($rule['value'] > $ancestors);
						break;
					default:
						// do nothing
						break;
				} // end switch
				return $match;
			} // end public function match
			
		} // end class location_page_level_jh
		
		acf_register_location_type('location_page_level_jh');
		
	} // end if class exists
