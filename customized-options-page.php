<?php 
	
	/*
		At the time that I am setting up this example it is currently not possible to
		modify an ACF Options Page beyond the settings in the options page and adding
		field groups.
		
		This files gives an example of how to insert content into several places in an
		options page.
		
		A note about the action hooks to use.
		
		You must know tha action hook that will be used by WP to call the callback function when
		ACF uses add_menu_page() and add_submenu_page(). The hook is not returned so you will need
		to figure out what it is.
		
		top level options page slug = "toplevel_page_{$acf_menu_slug}"
		
		if top level page redirects to first sub level page use sub options page slug
		
		sub options page slug = "{$acf_parent_slug}_page_{$acf_sub_page_slug}"
		
	*/
	
	/*
		create an action for your options page that will run before the ACF callback function
		see above for information on the hook you need to use
	*/
	add_action('toplevel_page_YOUR-PAGE-SLUG', 'before_acf_options_page', 1);
	function before_acf_options_page() {
		/*
			Before ACF outputs the options page content
			start an object buffer to that we can capture the output
		*/
		ob_start();
	}
	
	/*
		create an action for your options page that will run after the ACF callback function
		see above for information on the hook you need to use
	*/
	add_action('toplevel_page_YOUR-PAGE-SLUG', 'after_acf_options_page', 20);
	function after_acf_options_page() {
		/*
			After ACF finishes get the output and modify it
		*/
		$content = ob_get_clean();
		
		$count = 1; // the number of times we should replace any string
		
		// insert something before the <h1>
		$my_content = '<p>This will be inserted before the &lt;h1&gt;</p>';
		$content = str_replace('<h1', $my_content.'<h1', $content, $count);
		
		// insert something after the <h1>
		$my_content = '<p>This will be inserted after the &lt;h1&gt;</p>';
		$content = str_replace('</h1>', '</h1>'.$my_content, $content, $count);
		
		// insert something after the form
		$my_content = '<p>This will be inserted after the form</p>';
		$content = str_replace('</form>', '</form>'.$my_content, $content, $count);
		
		// output the new content
		echo $content;
	}
	
?>