<?php 
	
	function acf_field_group_columns($columns) {
		$columns['menu_order'] = __('Menu Order');
		$columns['location'] = __('Location Rules');
		return $columns;
	}
	add_filter('manage_edit-acf-field-group_columns', 'acf_field_group_columns', 20);
	
	function acf_field_group_columns_content($column, $post_id) {
		$post = get_post($post_id);
		switch ($column) {
			case 'menu_order':
				echo $post->menu_order;
				break;
			case 'location':
				$details = unserialize($post->post_content);
				$location = $details['location'];
				if (is_array($location) && count($location)) {
					echo '<pre>';
					$or_count = 1;
					foreach ($location as $or) {
						$and_count = 1;
						foreach ($or as $and) {
							if ($and_count == 1) {
								echo '    ';
							}
							echo $and['param'],' ',$and['operator'],' ',$and['value'];
							if ($and_count < count($or)) {
								echo "\r\n",'<strong>AND </strong>';
							}
							$and_count++;
						}
						if ($or_count < count($location)) {
							echo "\r\n\r\n",'<strong>OR</strong>',"\r\n";
						}
						$or_count++;
					}
					echo '</pre>';
				}
				break;
		} // end switch
	}
	add_action('manage_acf-field-group_posts_custom_column', 'acf_field_group_columns_content', 10, 2 );
	
	// some css to pretty up the new columns
	add_action('admin_head', 'admin_acf_columns_css');
	function admin_acf_columns_css() {
		?>
			<style type="text/css">
				#acf-field-group-wrap .wp-list-table .column-menu_order {
					width: 10%;
				}
				.widefat td.column-location {
					padding: 0;
				}
				.widefat td.column-location pre {
					margin: 0;
					padding: 8px 10px;
					border-top: 1px solid #CCC;
				}
				.widefat tbody tr:first-child td.column-location pre {
					border-top: none;
				}
			</style>
		<?php 
	}
	
?>
