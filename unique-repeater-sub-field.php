/*
		This filter can be used to ensure the unuqueness of a repeater sub field value
*/

// example add_filter, can use any valid repeater sub field key
add_filter('acf/validate_value/key=field_59dd1ad221414', 'unique_repeater_subfield', 20, 4);

function unique_repeater_subfield($valid, $value, $field, $input) {
		if (!$valid) {
			return $valid;
		}

		// get list of array indexes from $input
		// [ <= this fixes my IDE, it has problems with unmatched brackets in the regex
		preg_match_all('/\[([^\]]+)\]/', $input, $matches);
		if (!count($matches[1])) {
			// this should actually never happen
			return $valid;
		}
		// only need the index list captured
		$matches = $matches[1];

		// walk the acf input to find the repeater and current row			
		$array = $_POST['acf'];

		$repeater_key = false;
		$repeater_value = false;
		$row_key = false;
		$row_value = false;
		$field_key = false;
		$field_value = false;

		for ($i=0; $i<count($matches); $i++) {
			if (isset($array[$matches[$i]])) {
				$repeater_key = $row_key;
				$repeater_value = $row_value;
				$row_key = $field_key;
				$row_value = $field_value;
				$field_key = $matches[$i];
				$field_value = $array[$matches[$i]];
				if ($field_key == $field['key']) {
				  // found the level we need to be at
					break;
				}
				// get next level of acf input to check
				$array = $array[$matches[$i]];
			} // end isset
		} // end foreach index

		if (!$repeater_key) {
			// this should not happen
			return $valid;
		}

		// look for duplicate values in the repeater
		foreach ($repeater_value as $index => $row) {
			if ($index != $row_key && strtolower($row[$field_key]) == strtolower($value)) {
				$valid = 'this value is not unuque';
				break;
			}
		}

		return $valid;
	} // end public function unique_repeater_subfield
