<?php 
  
  // add default image setting to ACF image fields
  // let's you select a defualt image
  
	add_action('acf/render_field_settings/type=image', 'add_default_value_to_image_field', 20);
	function add_default_value_to_image_field($field) {
		acf_render_field_setting( $field, array(
			'label'			=> __('Default Image ID','acf'),
			'instructions'	=> __('Appears when creating a new post','acf'),
			'type'			=> 'image',
			'name'			=> 'default_value',
		));
	}
  
?>
