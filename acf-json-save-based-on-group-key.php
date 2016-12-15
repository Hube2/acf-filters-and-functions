<?php 
  
  /*
       this example will let you save 
       field group json files 
       to different folders
       based on the field key
  */
  
  // for this I am creating a class so that we can
  // easily store infomation in one filter and then
  // retrieve it in another
  
  new acf_save_json_based_on_group_key();
  
  class acf_save_json_based_on_group_key {
    
    // $groups is an array of field group key => path pairs
    // these will be set later
    private $groups = array();
    
    // this variable will store the current group key
    // that is being saved so that we can retrieve it later
    private $current_group_being_saved;
    
    public function __construct() {
      
      // this init action will set up the save paths
      add_action('admin_init', array($this, 'admin_init'));
      
      // this action is called by ACF before saving a field group
      // the priority is set to 1 so that it runs before the internal ACF action
      add_action('acf/update_field_group', array($this, 'update_field_group'), 1, 1);
      
    } // end public function __construct
    
    public function admin_init() {
      
      // in this function we set up the paths where we want to store JSON files
      // in this example we're creating two folders in the theme header and footer
      // change the field groups and keys based on your groups
      $footer = get_stylesheet_directory().'/modules/footer';
      $header = get_stylesheet_directory().'/modules/header';
      $this->groups = array(
        'group_584d5b7986f02' => $header,
        'group_584d5b7986f03' => $footer
      );
        
    } // end public function admin_init
    
    public function update_field_group($group) {
      // the purpose of this function is to see if we want to
      // change the location where this group is saved
      // and if we to to add a filter to alter the save path
      
      // first check to see if this is one of our groups
      if (!isset($this->groups[$group['key']])) {
        // not one or our groups
        return $group;
      }
      
      // store the group key and add action
      $this->current_group_being_saved = $group['key'];
      add_action('acf/settings/save_json',  array($this, 'override_json_location'), 9999);
      
      // don't forget to return the groups
      return $group;
      
    } // end public function update_field_group
    
    public function override_json_location($path) {
      
      // alter the path based on group being saved and
      // our save locations
      $path = $this->groups[$this->current_group_being_saved];
      
      return $path;
      
    } // end public function override_json_location
    
  } // end class acf_save_json_based_on_group_key
  
  
?>
