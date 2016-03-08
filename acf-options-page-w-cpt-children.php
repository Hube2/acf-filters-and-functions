<?php 
	
	/* 
			The Problem:
				You cannot create an ACF Options Page and have a Custom Post Type as
				a Sub Menu Item without the AFC page redirecting to the CPT edit page.
			
			The Cause:
				ACF adds options page on the "admin_menu" hook with a priority > 9
				See this note on http://codex.wordpress.org/Function_Reference/register_post_type
				for the "show_in_menu" argument:
			
					Note: When using 'some string' to show as a submenu of a menu page created by a 
					plugin, this item will become the first submenu item, and replace the location of 
					the top-level link. If this isn't desired, the plugin that creates the menu page 
					needs to set the add_action priority for admin_menu to 9 or lower.
					
			The Solution:
				Add the options page twice, once with ACF and once with the standard WP function add_menu_page()
			
				But this causes another problem, the menu will be duplicated.
				The solution to this is to remove the duplicate
				
		You should rename these funtions
	
	*/
	
	// add an ACF Options Page
	add_action('init', 'add_a_test_acf_options_page');
	function add_a_test_acf_options_page() {
		if (!function_exists('acf_add_options_page')) {
			return;
		}
		// all of these arguments are identical to the arguments
		// used to create in the function add_menu_page()
		$args = array(
			'page_title' => 'Test Options Page',
			'menu_title' => 'Test Options Page',
			// set the page slug, do not let it be generated
			// or you may not be able to find it to remove
			'menu_slug' => 'test-options-page',
			'capability' => 'edit_posts',
			// choose a menu postion that you know will not be changed
			'position' => '75.374981',
			'parent_slug' => '',
			'icon_url' => 'dashicons-warning',
			'redirect' => false,
			'post_id' => 'options',
			'autoload' => true
		);
		acf_add_options_page($args);
	}
	
	// add a menu page in WP on admin_init with priority < 10
	add_action('admin_menu', 'add_a_test_menu_page', 9);
	function add_a_test_menu_page() {
		// all of these arguments are identical to the arguments
		// used to create in the function acf_add_options_page() 
		$page_title = 'Test Options Page';
		$menu_title = 'Test Options Page';
		$capability = 'edit_posts';
		// choose a menu postions that you know will not be changed
		$position = '75.374981';
			// set the page slug, do not let it be generated
			// or you may not be able to find it to remove
		$menu_slug = 'test-options-page';
		$callback = '';
		$icon = 'dashicons-warning';
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback, $icon, $position);
	}
	
	// remove the duplicate menu item
	// ACF uses a priority of 99 for the admin_menu hook
	// so we just need to call this with a higher priority
	add_action('admin_menu', 'remove_duplicate_admin_menu', 100);
	function remove_duplicate_admin_menu() {
		global $menu;
		// loop trrough the menu and remove one of the duplicates
		// this loop is looking for the page slug
		foreach ($menu as $key => $values) {
			if ($values[2] == 'test-options-page') {
				// found our slug, unset the menu item and exit
				unset($menu[$key]);
				break;
			}
		}
	}
	
	// add a CPT that is a sub menu item of the options page
	// so that we can see that it works
	add_action('init', 'add_test_cpt');
	function add_test_cpt() {
		$args = array(
			'labels' => array('name' => 'Test CPT', 'singular_name' => 'Test CPT'),
			'show_ui' => true,
			'show_in_menu' => 'test-options-page',
			'map_meta_cap' => true
		);
		register_post_type('test-cpt', $args);
	}
	
	
?>
