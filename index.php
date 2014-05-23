<?php
/**
 * Plugin Name: Toltech Forms
 * Plugin URI: http://www.toltech.co.uk
 * Description: A plugin for tracking and reporting your online enquiries.
 * Version: 1.0
 * Author: Toltech Internet Solutions
 * Author URI: http://www.toltech.co.uk
 */


// Define Directory
define( 'ROOT', plugins_url( '', __FILE__ ) );
define( 'IMAGES', ROOT . '/images/' );
define( 'STYLES', ROOT . '/css/' );
define( 'SCRIPTS', ROOT . '/js/' );

// Define Custom Post Type
function toltech_custom_post_type() {
    $labels = array(
        'name'                  =>   __( 'Toltech Forms', 'toltech' ),
        'singular_name'         =>   __( 'Toltech Form', 'toltech' ),
        'add_new'          =>   __( 'Create New Form', 'toltech' ),
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

// Define Metabox
add_action( 'add_meta_boxes', 'add_metaboxes' );
function add_metaboxes() {
    add_meta_box("toltech_form_details", "Toltech Form Details", "toltech_form_ui", "forms");
}

function toltech_form_ui() {
    global $post;
    $custom = get_post_custom($post->ID);

    $id = md5(mt_rand());
    $description = $custom['_description'][0];

    $output .= '<p><label for="_price"><strong>Genereated Shorcode: <span style="color: #0074a2;">[form id="'. $id .'"]</span></strong></label><br />
    <small>Please copy above shortcode into your page to display form</small></p>';
   
    $output .= '<hr>';

    $output .= '<div style="margin-top: 30px;"></div>';
    
    // jQuery Dynamic Custom Field
    $output .='<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script><script>
    $(document).ready(function(){
            $("#form-field").click(function() {
            
            $(".table")
                .last()
                .clone()
                .appendTo($(".next-table"))
                .find("input").attr("name",function(i,oldVal) {
                    return oldVal.replace(/\[(\d+)\]/,function(_,m){
                        return "[" + (+m + 1) + "]";
                    });
                });
            
            return false;
            
        });
    });
        </script>';
    
    $output .= '<a id="form-field" href="#" style="margin-bottom: 15px;" class="button button-primary button-large" />Add New Form Field</a>';

    // Default Fields
    $output .= '<table>';
        $output .= '<tr>';
            $output .= '<td>
                            <input type="text" name="_description" id="_description" value="Firstname" />
                        </td>';  
            $output .= '<td>
                            <select>
                            <option>Text Input</option>
                            <option>Text Area</option>
                            <option>Radio Button</option>
                            <option>Checkbox</option>
                            </select>
                        </td>';
            $output .= '<td>
                            <div style="margin-left: 10px;">
                                Required Field? <input type="checkbox" name="_description" id="_description" value="Field 1 Name" />
                                <a style=" margin-left: 15px;"href="#" />Remove Field</a>
                            </div>
                        </td>';
        $output .= '</tr>';

        $output .= '<tr>';     
            $output .= '<td>
                            <input type="text" name="_description" id="_description" value="Lastname" />
                        </td>';
            $output .= '<td>
                            <select>
                            <option>Text Input</option>
                            <option>Text Area</option>
                            <option>Radio Button</option>
                            <option>Checkbox</option>
                            </select>
                        </td>';
            $output .= '<td>
                            <div style="margin-left: 10px;">
                                Required Field? <input type="checkbox" name="_description" id="_description" value="Field 1 Name" />
                                <a style=" margin-left: 15px;"href="#" />Remove Field</a>
                            </div>
                        </td>';
        $output .= '</tr>';

        $output .= '<tr>';
            $output .= '<td>
                            <input type="text" name="_description" id="_description" value="Email" />
                        </td>';
            $output .= '<td>
                            <select>
                            <option>Text Input</option>
                            <option>Text Area</option>
                            <option>Radio Button</option>
                            <option>Checkbox</option>
                            </select>
                        </td>';
            $output .= '<td>
                            <div style="margin-left: 10px;">
                                Required Field? <input type="checkbox" name="_description" id="_description" value="Field 1 Name" />
                                <a style=" margin-left: 15px;"href="#" />Remove Field</a>
                            </div>
                        </td>';
        $output .= '</tr>';

        $output .= '<tr>';
            $output .= '<td>
                            <input type="text" name="_description" id="_description" value="Telephone" />
                        </td>';
            $output .= '<td>
                            <select>
                            <option>Text Input</option>
                            <option>Text Area</option>
                            <option>Radio Button</option>
                            <option>Checkbox</option>
                            </select>
                        </td>';
                $output .= '<td>
                            <div style="margin-left: 10px;">
                                Required Field? <input type="checkbox" name="_description" id="_description" value="Field 1 Name" />
                                <a style=" margin-left: 15px;"href="#" />Remove Field</a>
                            </div>
                        </td>';
        $output .= '</tr>';
    $output .= '</table>';


    // Custom Field
    $output .= '<table class="table">';
        $output .= '<tr>';
            $output .= '<td>
                            <input type="text" name="_description" id="_description" value="Custom Field" />
                        </td>';
            $output .= '<td>
                            <select>
                            <option>Text Input</option>
                            <option>Text Area</option>
                            <option>Radio Button</option>
                            <option>Checkbox</option>
                            </select>
                        </td>';
            $output .= '<td>
                        <div style="margin-left: 10px;">
                            Required Field? <input type="checkbox" name="_description" id="_description" value="Field 1 Name" />
                            <a style=" margin-left: 15px;"href="#" />Remove Field</a>
                        </div>
                    </td>';
            $output .= '<tr>';
    $output .= '</table>';

    // Dynamic new field controlled by jQuery
    $output .= '<div class="next-table"></div>';
    

    $output .= '<div style="margin-top: 30px;"></div>';
    $output .= '<hr>';
    
    $output .= '<h3 style="border: 0px; padding-left: 0px; padding-top: 0px;">Form Settings</h3>';
    $output .= '<table style="float: left; margin-right: 15px;">
                    <tr>
                        <td>Recipients:</td>
                        <td><input type="text" name="_description" id="_description" value="" /></td>
                    </tr>
                    <tr>
                        <td>Subject:</td>
                        <td><input type="text" name="_description" id="_description" value="" /></td>
                    </tr>
                    <tr>
                        <td>Return URL:</td>
                        <td><input type="text" name="_description" id="_description" value="" /></td>
                    </tr>
                    <tr>
                        <td>Success Message:</td>
                        <td><input type="text" name="_description" id="_description" value="" /></td>
                    </tr>
                    <tr>
                        <td>Google Analytics:</td>
                        <td><input type="text" name="_description" id="_description" value="" /></td>
                    </tr>
                    <tr>
                        <td>Google Goal:</td>
                        <td><input type="text" name="_description" id="_description" value="" /></td>
                    </tr>
                </table>';

    $output .= '<table style="float: left;">
                    <tr>
                        <td></td>
                        <td>Test Mode <input type="checkbox" name="_description" id="_description" value="Field 1 Name" />
                            Live Mode <input type="checkbox" name="_description" id="_description" value="Field 1 Name" /></td>
                    </tr>

                </table>';
    
    $output .= '<div style="clear: both;"></div>';

    echo $output;  

}   

// Define Shortcode
add_shortcode( 'toltech-forms', 'shortcode_toltech_forms' );
function shortcode_toltech_forms() {
}

// Define save button as "Save Toltech Form"
add_filter( 'gettext', 'change_publish_button', 10, 2 );
function change_publish_button( $translation, $text ) {
    if ( 'forms' == get_post_type())
    if ( $text == 'Publish' )
        return 'Save Toltech Form';

    return $translation;
}