<?php 
	
	// when images are removed from a gallery
	// delete them from media library
	
	add_filter('acf/update_value/type=gallery', 'remove_gallery_images', 10, 3);
	
	function remove_gallery_images($value, $post_id, $field) {
		$images_to_delete = array();
		$old_value = get_post_meta($post_id, $field['name'], true);
		$old_value = maybe_unserialize($old_value);
		if (!is_array($old_value)) {
			return $value;
		}
		if (!empty($value)) {
			$images_to_delete = $old_value;
		} else {
			foreach ($old_value as $image_id) {
				if (!in_array($image_id, $value)) {
					$images_to_delete[] = $image_id;
				}
			}
		}
		if (count($images_to_delete)) {
			foreach ($images_to_delete as $image_id) {
				wp_delete_attachment($image_id, true);
			}
		}
		return $value;
	}
	
?>
