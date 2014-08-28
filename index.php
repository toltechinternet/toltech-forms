<?php
 /*
 * Plugin Name: Toltech Forms
 * Plugin URI: http://www.toltech.co.uk
 * Description: A plugin for tracking and reporting your online enquiries.
 * Version: 1.1
 * Author: Toltech Internet Solutions
 * Author URI: http://www.toltech.co.uk
 */


// Define Directory
define( 'ROOT', plugins_url( '', __FILE__ ) );
define( 'IMAGES', ROOT . '/images/' );
define( 'STYLES', ROOT . '/css/' );
define( 'SCRIPTS', ROOT . '/js/' );
//define( 'LOG_FILE','/Applications/XAMPP/xamppfiles/htdocs/toltech-forms/wp-content/plugins/toltech-forms/log.log'); //home
define( 'LOG_FILE','D:\TIS Projects\T\Toltech Internet Solutions\sites\dev.toltech.co.uk\wp-content\plugins\toltech-forms\log.log');

register_activation_hook( __FILE__, 'toltech_forms_activation' );

function toltech_forms_activation() {
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'forms_structure'; 
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
		/*****************************************************/
		// Create table to store types of forms *************//
		/*****************************************************/
		$wpdb->query("CREATE TABLE " . $table_name . " (
		  form_id VARCHAR(13) NOT NULL,
		  post_id INT(11) NOT NULL,
		  html TEXT NOT NULL,
		  recipients TEXT NOT NULL,
		  subject TEXT NOT NULL,
		  return_url TEXT NOT NULL,
		  success_message TEXT NOT NULL,
		  ga VARCHAR(225),
		  ga_goal VARCHAR(225),
		  class VARCHAR(225),
		  test_mode CHAR(1),
		  PRIMARY KEY (form_id)
		)");
    	$message .= $table_name.' CREATED\r\n';
    }else{
    	$message .= $table_name.' NOT CREATED\r\n';
    }
	
	$table_name = $wpdb->prefix . 'forms_submission'; 
	if($wpdb->get_var("SHOW TABLES LIKE '%".$table_name."%'") != $table_name){
	
		/*****************************************************/
		/*** Create table to store standard submission data **/
		/*****************************************************/
		
		$wpdb->query("CREATE TABLE " . $table_name . " (
		  submission_id int(11) NOT NULL AUTO_INCREMENT,
		  form_id VARCHAR(13) NOT NULL,
		  forename VARCHAR(225),
		  surname VARCHAR(225),
		  email VARCHAR(225),
		  telephone VARCHAR(50),
		  date_submitted DATETIME,
		  PRIMARY KEY (submission_id)
		)");
		
		$message .= $table_name.' CREATED\r\n';
	}else{
		$message .= $table_name.' NOT CREATED\r\n';
	}
	
	$table_name = $wpdb->prefix . 'forms_customfields'; 
	if($wpdb->get_var("SHOW TABLES LIKE '%".$table_name."%'") != $table_name){
	
		/*****************************************************/
		/*** Create table to store dynamic field types *******/
		/*****************************************************/
		
		$wpdb->query("CREATE TABLE " . $table_name . " (
		  field_id int(11) NOT NULL AUTO_INCREMENT,
		  form_id VARCHAR(13) NOT NULL,
		  label TEXT,
		  name VARCHAR(225),
		  type VARCHAR(225),
		  PRIMARY KEY (field_id)
		)");
		
		$message .= $table_name.' CREATED\r\n';
	}else{
		$message .= $table_name.' NOT CREATED\r\n';
	}
	
	$table_name = $wpdb->prefix . 'forms_metadata'; 
	if($wpdb->get_var("SHOW TABLES LIKE '%".$table_name."%'") != $table_name){
	
		/*****************************************************/
		/*** Create table to store dynamic submission data **/
		/*****************************************************/
		
		$wpdb->query("CREATE TABLE " . $table_name . " (
		  meta_id int(11) NOT NULL AUTO_INCREMENT,
		  form_id VARCHAR(13) NOT NULL,
		  submission_id INT(11),
		  meta_key VARCHAR(255),
		  meta_value TEXT,
		  PRIMARY KEY (meta_id)
		)");
		
		$message .= $table_name.' CREATED\r\n';
	}else{
		$message .= $table_name.' NOT CREATED\r\n';
	}
	
	
	
	
	
	

	$to      = 'joe@toltech.co.uk';
	$subject = 'Plugin Activation';
	$headers = 'From: webmaster@example.com' . "\r\n" .
	    'Reply-To: webmaster@example.com' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $message, $headers);
}

include('inc/functions.php');
include('inc/shortcodes.php');

// Define Custom Post Type
function toltech_custom_post_type() {
    $labels = array(
        'name'                  =>   __( 'Toltech Forms', 'toltech' ),
        'singular_name'         =>   __( 'Toltech Form', 'toltech' ),
        'add_new'          		=>   __( 'Create New Form', 'toltech' ),
        'all_items'             =>   __( 'All Forms', 'toltech' ),
        'edit_item'             =>   __( 'Edit Form', 'toltech' ),
        'new_item'              =>   __( 'New Form', 'toltech' ),
        'view_item'             =>   __( 'View Form', 'toltech' ),
        'not_found'             =>   __( 'No Forms Found', 'toltech' ),
        'not_found_in_trash'    =>   __( 'No Forms Found in Trash', 'toltech' )
    );
 
    $supports = array(
        'title'
    );
 
    $args = array(
        'label'         =>   __( 'Forms', 'toltech' ),
        'labels'        =>   $labels,
        'description'   =>   __( 'A list of Toltech Forms', 'toltech' ),
        'public'        =>   true,
        'show_in_menu'  =>   true,
        'menu_icon'     =>   'dashicons-feedback',
        'has_archive'   =>   true,
        'rewrite'       =>   true,
        'supports'      =>   $supports
    );
 
    register_post_type( 'forms', $args );
}
add_action( 'init', 'toltech_custom_post_type' );


// Define plugin navigation and pages
function toltech_forms_navigation(){
    add_submenu_page( 'edit.php?post_type=forms', 'View All Enquiries', 'View All Enquiries', 'manage_options', 'view_enquiries', 'toltech_submission_page' );
    //add_submenu_page( 'edit.php?post_type=forms', 'Reporting Data', 'Reporting Data', 'manage_options', 'reporting', 'toltech_reporting_page');
    add_submenu_page( 'edit.php?post_type=forms', 'Settings', 'Settings', 'manage_options', 'settings', 'toltech_settings_page');
}
add_action( 'admin_menu', 'toltech_forms_navigation' );

function toltech_submission_page(){
 echo "<div class='wrap'><h2>Enquiry's Overview</h2></div>";
}

/*function toltech_reporting_page(){
 echo "<div class='wrap'><h2>Reporting Data</h2></div>";
}*/

function toltech_settings_page(){
 echo "<div class='wrap'><h2>Settings</h2></div>";
}

// Define save button as "Save Toltech Form"
add_filter( 'gettext', 'change_publish_button', 10, 2 );
function change_publish_button( $translation, $text ) {
    if ( 'forms' == get_post_type())
    if ( $text == 'Publish' )
        return 'Save Toltech Form';

    return $translation;
}