<?php 

/**
 * Like the acf-reciprocal-relationship.php example this function allows two
 * different reltionship fields to be kept in sync. The difference being that
 * this function usues PHP closures to avoid needing hard-coded field keys
 * inside the function, and so can be used for more than one set of reciprocal
 * relationships.
 *
 * PHP 5.3+ required for PHP closures.
 *
 * The example assumes that you are using either a single relationship field
 * where posts of the same type are related or you can have 2 relationship
 * fields on two different post types. This example also assumes that
 * the relationship field(s) do not impose any limits on the number
 * of selections.
 *  
 * The concept covered in this file has also been coverent on the ACF site on this
 * page https://www.advancedcustomfields.com/resources/bidirectional-relationships/
 * The example shown there is very similar, but requires that the field name is the
 * same; similar to my plugin that does this. This example allows for fields of
 * different names across different post types.
 */

/**
 * Reciprocal relationship example 1
 * Between two relationship fields which belong to different post types
 */
// Set the field keys for the two relationship fields
$key_a = 'field_5b310e28be9c2';
$key_b = 'field_5b3113ceed9c1';

// Add the filter to the first relationship field
// The key must match $key_a above
add_filter(
    'acf/update_value/key=field_5b310e28be9c2',
    function ($value, $post_id, $field) use ($key_a, $key_b) {
        return acf_reciprocal_relationship($value, $post_id, $field, $key_a, $key_b);
    },
    10, 5
);

// Add the filter to the second relationship field
// The key must match $key_b above
add_filter(
    'acf/update_value/key=field_5b3113ceed9c1',
    function ($value, $post_id, $field) use ($key_a, $key_b) {
        return acf_reciprocal_relationship($value, $post_id, $field, $key_a, $key_b);
    },
    10, 5
);

/**
 * Reciprocal relationship example 2
 * Between two relationship fields which belong to different post types
 */
// Set the field keys for the two relationship fields
// In this example the fileds belong to different post types
$key_a = 'field_5b339d33cab0a';
$key_b = 'field_5b339d70a440d';

// Add the filter to the first relationship field
// The key must match $key_a above
add_filter(
    'acf/update_value/key=field_5b339d33cab0a',
    function ($value, $post_id, $field) use ($key_a, $key_b) {
        return acf_reciprocal_relationship($value, $post_id, $field, $key_a, $key_b);
    },
    10, 5
);

// Add the filter to the second relationship field
// The key must match $key_b above
add_filter(
    'acf/update_value/key=field_5b339d70a440d',
    function ($value, $post_id, $field) use ($key_a, $key_b) {
        return acf_reciprocal_relationship($value, $post_id, $field, $key_a, $key_b);
    },
    10, 5
);


/**
 * When a relationship field is set, a reciprocal relationship
 * is also set on the target post type.
 *
 * @param [type] $value
 * @param [type] $post_id
 * @param [type] $field
 * @param [type] $key_a
 * @param [type] $key_b
 * @return void
 */
function acf_reciprocal_relationship($value, $post_id, $field, $key_a, $key_b)
{
    // figure out wich side we're working on and set up variables
    // $key_a represents the field for the current posts
    // and $key_b represents the field on related posts
    if ($key_a !== $field['key']) {
        $temp = $key_a;
        $key_a = $key_b;
        $key_b = $temp;
    }
    
    // get both fields
    // this gets them by using an acf function
    // that can gets field objects based on field keys
    // we may be getting the same field, but we don't care
    $field_a = acf_get_field($key_a);
    $field_b = acf_get_field($key_b);
    
    // set the field names to check
    // for each post
    $name_a = $field_a['name'];
    $name_b = $field_b['name'];
    
    // get the old value from the current post
    // compare it to the new value to see
    // if anything needs to be updated
    // use get_post_meta() to a avoid conflicts
    $old_values = get_post_meta($post_id, $name_a, true);
    // make sure that the value is an array
    if (!is_array($old_values)) {
        if (empty($old_values)) {
            $old_values = array();
        } else {
            $old_values = array($old_values);
        }
    }
    // set new values to $value
    // we don't want to mess with $value
    $new_values = $value;
    // make sure that the value is an array
    if (!is_array($new_values)) {
        if (empty($new_values)) {
            $new_values = array();
        } else {
            $new_values = array($new_values);
        }
    }
    
    // get differences
    // array_diff returns an array of values from the first
    // array that are not in the second array
    // this gives us lists that need to be added
    // or removed depending on which order we give
    // the arrays in
    
    // this line is commented out, this line should be used when setting
    // up this filter on a new site. getting values and updating values
    // on every relationship will cause a performance issue you should
    // only use the second line "$add = $new_values" when adding this
    // filter to an existing site and then you should switch to the
    // first line as soon as you get everything updated
    // in either case if you have too many existing relationships
    // checking end updated every one of them will more then likely
    // cause your updates to time out.
    //$add = array_diff($new_values, $old_values);
    $add = $new_values;
    $delete = array_diff($old_values, $new_values);
    
    // reorder the arrays to prevent possible invalid index errors
    $add = array_values($add);
    $delete = array_values($delete);
    
    if (!count($add) && !count($delete)) {
        // there are no changes
        // so there's nothing to do
        return $value;
    }
    
    // do deletes first
    // loop through all of the posts that need to have
    // the recipricol relationship removed
    for ($i=0; $i<count($delete); $i++) {
        $related_values = get_post_meta($delete[$i], $name_b, true);
        if (!is_array($related_values)) {
            if (empty($related_values)) {
                $related_values = array();
            } else {
                $related_values = array($related_values);
            }
        }
        // we use array_diff again
        // this will remove the value without needing to loop
        // through the array and find it
        $related_values = array_diff($related_values, array($post_id));
        // insert the new value
        update_post_meta($delete[$i], $name_b, $related_values);
        // insert the acf key reference, just in case
        update_post_meta($delete[$i], '_'.$name_b, $key_b);
    }
    
    // do additions, to add $post_id
    for ($i=0; $i<count($add); $i++) {
        $related_values = get_post_meta($add[$i], $name_b, true);
        if (!is_array($related_values)) {
            if (empty($related_values)) {
                $related_values = array();
            } else {
                $related_values = array($related_values);
            }
        }
        if (!in_array($post_id, $related_values)) {
            // add new relationship if it does not exist
            $related_values[] = $post_id;
        }
        // update value
        update_post_meta($add[$i], $name_b, $related_values);
        // insert the acf key reference, just in case
        update_post_meta($add[$i], '_'.$name_b, $key_b);
    }
    
    return $value;
}
