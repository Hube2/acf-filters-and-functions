<?php 
	
	/*
		Add this class to a parent theme that uses ACF5 Pro
		When loaded from a parent theme it will load all of the
		field groups in the acf-json theme folder of the parent theme
		This lets you create themes using ACF and have the field groups
		automagically added to the child theme
	*/
	
	new acf_load_parent_theme_field_groups();
	
	class acf_load_parent_theme_field_groups {
		
		public function __construct() {
			add_action('acf/include_fields', array($this, 'include_fields'), 50);
		} // end public function __construct
		
		public function include_fields() {
			$path = get_template_directory().'/acf-json';
			if (!is_dir($path) ||
					($files = scandir($path)) === false ||
					!count($files)) {
				return;
			}
			$groups = $this->get_acf_field_groups();
			foreach ($files as $file) {
				$file_path = $path.'/'.$file;
				if (is_dir($file_path) || !preg_match('/\.json$/', $file)) {
					continue;
				}
				$group_key = preg_replace('/\.json$/', '', $file);
				if (!isset($groups[$group_key]) &&
						($json = file_get_contents($file_path)) !== false &&
						($field_group = json_decode($json, true)) !== NULL) {
					acf_add_local_field_group($field_group);
				}
			}
			// need to delete the ACF cache
			wp_cache_delete('get_field_groups', 'acf');
		} // end public function include_fields
		
		private function get_acf_field_groups() {
			$groups = array();
			$acf_groups = acf_get_field_groups();
			if (!count($acf_groups)) {
				return;
			}
			foreach ($acf_groups as $group) {
				$groups[$group['key']] = $group['key'];
			}
			return $groups;
		} // end private function get_acf_field_groups
		
	} // end class acf_load_theme_groups
	
?>
