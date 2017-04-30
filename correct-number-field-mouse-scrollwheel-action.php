/*
  This function can be added to functions.php in order to correct the mouse scrollwheel action.
  
  This is not just an ACF issue, this is a browser issue.
  Whenever a number field has focus, scrolling the mousewheel will change the value of a field,
  even when this is not the desired effect, for example what you really want to do is scroll
  the page but forgot to click off of the number field before trying to do so.
  This is, in my opinion and that of others, to be incorrect behavior
  
  This script will only allow the scrollwheel to alter the field value when
  1) The number field has focus
  AND
  2) The mouse is actually over the field
*/

add_action('admin_footer', 'correct_number_scrollwheel');
function correct_number_scrollwheel() {
	?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				// need to loop through all number fields
				// in order to add a flag to enable/disable the mousewheel
				$('input[type="number"]').each(function(index, element) {
					// disable mousewheel by default on all number fields
					$(element).data('disable-mousewheel', true);
					// test if mousewheel is disabled, if it is prevent changing number field
					$(element).on('mousewheel', function(e) {
						if ($(element).data('disable-mousewheel')) {
							e.preventDefault();
						}
					}); // end on mousewheel
					// only enable the mousewheel when the mouse enters the number field
					$(element).on('mouseenter', function(e) {
						$(element).data('disable-mousewheel', false);
					}); // end on mouseenter
					// disable the mousewheel when the mouse leaves the number field
					$(element).on('mouseleave', function(e) {
						$(element).data('disable-mousewheel', true);
					}); // end on mouseleave
				}); // end each number element
			}); // end doc ready
		</script>
	<?php 
}	
