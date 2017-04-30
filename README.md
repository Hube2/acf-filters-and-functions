# ACF Filters & Functions

General filters and function for use with Advanced Custom Fields WP Plugin

For examples of how to create dynamic loading fields based on other field selections, for example dynamically loading a select field based on a selection made in another select field [see my other examples here](https://github.com/Hube2/acf-dynamic-ajax-select-example).

File names basically tell you what the code does. See comments in individual files for more information.

Since this repo has started to grow, here is a list of what you'll find in the files.

##### [acf-custom-post-type-filters.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-custom-post-type-filters.php)
This is a file that gives an example of creating custom location rules to match the posts in a custom
post type similar to Post and Page.

##### [acf-delete-images-when-removed-from-gallery.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-delete-images-when-removed-from-gallery.php)
This is an example of how to delete images from the media library when they are removed from a gallery field

##### [acf-extended-admin-columns.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-extended-admin-columns.php)
This is an example of how to extend the ACF admin columns to show additional information. This example adds
the menu order and the location rules for each group to the admin.

##### [acf-field-label-functions.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-field-label-functions.php)
This is a group of custom functions for returning the labels of fields as well as the labels for choices in
choice fields like radio, select and checkbox fields.

##### [acf-form-kses.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-form-kses.php)
This file has a function for applying safely `wp_kses_post()` to all ACF fields. You can't apply this to
repeaters and flexible content fields becuase these fields contain array values and the function deletes
these arrays. This filter can also be used as an example of recursively applying any function to ACF fields
and arrays in general. 

##### [acf-image-aspect-ratio-validation.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-image-aspect-ratio-validation.php)
Add image aspect ratio validation to image fields. Also an example of how to add multiple setting fields to
a single setting row for an acf field type setting.

##### [acf-load-parent-theme-field-groups.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-load-parent-theme-field-groups.php)
This file contains small class that will conditionally load ACF field groups from a theme parent. You add
this to your parent theme. It looks at the files in the parent themes acf-json folder and if a field group
in this folder does not already exist then it is loaded.

This is useful because it lets you work on the field groups in your development environment without needing to
delete those field groups. When an update is applied to your parent theme any changes you've made to the
field groups will be automatically applied to child themes.

Just as as side note, I also use this process when developming plugins, with a few modifications. It lets me
keep a working copy of my plugin that uses ACF field groups that I edit using ACF. I can then copy the changes
from tha theme folder to my plugin and the changes will be applied when the plugin is updated on a site.

##### [acf-options-page-w-cpt-children.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-options-page-w-cpt-children.php)
This is an example of how to add a custom post type as a sub menu item to an ACF Options Page. The last I checked
this cannot be done because ACF uses a priority > 9 for the `admin_menu` hook. For more information see the
comment at the top of the file.

##### [acf-page-ancestor-location-rule.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-page-ancestor-location-rule.php)
This is another custom location rule example. This custom location rule lets you choose to set a field group
to be located on any page that is a descendant of the page selected.

##### [acf-page-granparent-location-rule.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-page-granparent-location-rule.php)
This is another custom location rule example and it is similar to the ancestor location rule except that the
field group will only be located on pages that have a particular grand parent, or the second ancestor.

##### [acf-post-category-ancestor-location-rule.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-post-category-ancestor-location-rule.php)
This is another custom location rule example. This one sets a location based on category ancestor. It will
actually work with any hierarchical taxonomy.

##### [acf-reciprocal-relationship.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-reciprocal-relationship.php)
This file contains and example of how to create a reciprical or two way relationship field using either 1 or 2
relationship or post object fields. This file must be edited to match the field or fields that you wish to
convert into a bidirectional relationship. See the comments in the file for more information.

##### [customized-options-page.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/customized-options-page.php)
This is an example of how you can make modification to an ACF Options Page to add additional content into
the page that is generated by ACF, for example between the title and the ACF field groups.

##### [change-option-page-location-display.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/change-option-page-location-display.php)
ACF shows the menu_title as the choice in location rules. This can be confusing for those of use that create
multiple options pages with the same menu title and different page titles. This filter alters the location
display to show the page title instead.

##### [correct-number-field-mouse-scrollwheel-action.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/correct-number-field-mouse-scrollwheel-action.php)
Correct number field scrollwheel behavior

##### [default-image-for-image-field.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/default-image-for-image-field.php)
This is a simple example of how to add a default image setting to image fields. Note that this is only a bisic example and may require you to save the field group before you can select a default image. [I have posted more
information here on how to correct this situation](https://acfextras.com/default-image-for-image-field/)

##### [is_admin-acf-location-rule.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/is_admin-acf-location-rule.php)
This is another custom location rule example. This rule lets you choose a field group to be used only in the
in the admin or on a front end form.

##### [page-nth-level-location-rule.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/page-nth-level-location-rule.php)
This is another custom location rule example. This rule let's you choose to display a field group only on a
specific level of a hierarchical post type. This rule should work with any hierarchical post type.

##### [public-post-type-location-rule.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/public-post-type-location-rule.php)
This is another custom location rule example. This one will let you choose a location based on whether or not
the post type is a public post type.

##### [public-taxonomy-location-rule.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/public-taxonomy-location-rule.php)
This is like the public post type rule, but for taxonomies

##### [render-image-in-editor.php](https://github.com/Hube2/acf-filters-and-functions/blob/master/render-image-in-editor.php)
This is an example or how to render additional information in a field. This particular example shows how to
display an image when the URL for the image is from another site rather than an image in the media library.
