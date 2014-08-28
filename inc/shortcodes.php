<?php 


add_shortcode( 'form', 'shortcode_toltech_forms' );
function shortcode_toltech_forms($atts) {
extract(shortcode_atts(array(
	'id' => ''
), $atts));


if($id==''){
	echo 'You must specify a valid form ID in order for it to be displayed.';
}else{

		//****************************************************************************//
		// Update form_structure table or insert new record if adding new table type  //
		//****************************************************************************//
		global $wpdb;
		$table_name = $wpdb->prefix . 'forms_structure';
	
			$query = $wpdb->prepare("SELECT html from ".$table_name." WHERE form_id=%s ",$id);
			$results = $wpdb->get_var($query);
			//print_r($results);
			echo $results;
	
}

return;

}

?>