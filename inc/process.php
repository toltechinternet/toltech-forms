<?php

/* This file will deal with a form submission

1.0 Save the submission data into forms_submission & forms_data tables
2.0 Generate and send email to recipients listed
3.0 Display success message
4.0 Process any google tracing/goals etc
5.0 Redirect back to value of return_url 

*/

//Allows us to use wordpress to query DB
include_once('../../../../wp-config.php');
include_once('../../../../wp-includes/wp-db.php');
global $wpdb;

/********************************/
/*** 1.0 SAVE SUBMISSION DATA ***/
/********************************/
	
	//print_r($_REQUEST);
	
	//Standard submission data
	$form_id=$_REQUEST['form_id'];
	$forename=$_REQUEST['forename'];
	$surname=$_REQUEST['surname'];
	$email=$_REQUEST['email'];
	$telephone=$_REQUEST['telephone'];
	
	//Add standard data to forms_submission table
	//INSERT
	$table_name = $wpdb->prefix."forms_submission";
	$wpdb->query(
		$wpdb->prepare(
		"INSERT INTO ".$table_name."
		(form_id,forename,surname,email,telephone,date_submitted) 
		VALUES 
		(%s,%s,%s,%s,%s,now())",
		$form_id,
		$forename,
		$surname,
		$email,
		$telephone
		)
	);
	
	//Add dynamic data to forms_metadata table
	//add non standard fields
	$cf=$_REQUEST['cf'];
	$x=0;
	$submission_id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
	while($x!=$cf){
		
			
		$meta_key=$_REQUEST[$x.'_key'];
		$meta_value=$_REQUEST[$x.'_name'];
		
		//echo "(".$submission.")(".$meta_key.")(".$meta_value.")<br>";
		
		$table_name = $wpdb->prefix."forms_metadata";
		$wpdb->query(
			$wpdb->prepare(
			"INSERT INTO ".$table_name."
			(form_id,submission_id,meta_key,meta_value) 
			VALUES 
			(%s,%d,%s,%s)",
			$form_id,
			$submission_id,
			$meta_key,
			$meta_value
			)
		);
		$x++;
	
	}
	
/***********************************************/
/*** 2.0 GENERATE & SEND EMAIL TO RECIPIENTS ***/
/***********************************************/
//PULL ALL RELEVANT INFO FROM DB
$table_name = $wpdb->prefix."forms_structure";
$structure=$wpdb->get_row($wpdb->prepare('SELECT * FROM '.$table_name.' WHERE form_id=%d',$form_id),OBJECT);
//echo "Email Sent To:".$structure->recipients;
include 'email.php';	

/***********************************************/
/*** 3.0 DISPLAY SUCCESS MESSAGE ***************/
/***********************************************/


/***********************************************/
/*** 4.0 GOOGLE TRACKING ***********************/
/***********************************************/

/***********************************************/
/*** 5.0 SEND BACK TO return_url SPECIFIED *****/
/***********************************************/
	header('Location: '.$structure->return_url);

?>