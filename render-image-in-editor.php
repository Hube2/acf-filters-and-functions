<?php 
	
	// this is a filter that can be used to render
	// an image based on a value entered into a
	// field like a url field
	
	// change the field type to the type
	// of field you want this run on
	// use priority of 1 for before field
	// use priority of 20 for before field	
	add_action('acf/render_field/type=url', 'field_name_render_url_image', 20, 1);
	// change the function name to something unique
	function field_name_render_url_image($field) {
		 // change $field name to 
		 // the field to add the image to 
		$field_name = 'the_field_name';
		if ($field['_name'] != $field_name) {
			// not our field
			return;
		}
		// uncomment this if you want to see value of $field
		//echo '<pre>'; print_r($field); echo '</pre>';
		// get the post id
		global $post;
		$post_id = $post->ID;
		// get the current value of the field
		// using get_post_meta to avoid confilcts
		$url = get_post_meta($post_id, $field_name, true);
		if (!$url) {
			// nothing has been entered
			?><p>Enter a URL and Update to View.</p><?php 
			return;
		}
		// make sure the url is an image
		// alter regex to allow additional image extensions
		if (!preg_match('/\.(jpg|jpeg|png|gif)$/i', $url)) {
			// not an image we want to allow
			?><p>The URL Entered is Not A Valid Image URL. Enter a Valid Image URL and Update to View.</p><?php 
			return;
		}
		// if we get here then show the image
		?>
			<img src="<?php echo $url; ?>" />
		<?php 
	} // end function field_name_render_url_image
	
?>
