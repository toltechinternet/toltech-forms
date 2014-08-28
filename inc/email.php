<?php 

$table_name = $wpdb->prefix."forms_submission";
$generic=$wpdb->get_row($wpdb->prepare('SELECT * FROM '.$table_name.' WHERE form_id=%s AND submission_id=%d',$form_id,$submission_id),OBJECT);

$message = '<html><body>';
$message = '<h1>'.$structure->subject.'</h1>';
$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$message .= '<tr style="background: #eee;"><td><strong>Forename:</strong> </td><td>'.$generic->forename.'</td></tr>';
$message .= '<tr><td><strong>Surname:</strong> </td><td>'.$generic->surname.'</td></tr>';
$message .= '<tr><td><strong>Email:</strong> </td><td>'.$generic->email.'</td></tr>';
$message .= '<tr><td><strong>Telephone:</strong> </td><td>'.$generic->telephone.'</td></tr>';

//GET CUSTOM FIELD DATA
$table_name = $wpdb->prefix."forms_metadata";
$custom=$wpdb->get_results($wpdb->prepare('SELECT * FROM '.$table_name.' WHERE form_id=%s AND submission_id=%d',$form_id,$submission_id),OBJECT);

foreach($custom as $v){
	$message .= '<tr><td><strong>'.$v->meta_key.'</strong> </td><td>'.$v->meta_value.'</td></tr>';
}

$message .= '</table>';
$message .= '</body></html>';


$to = $structure->recipients;
$subject = $structure->subject;
$headers = "From: " . strip_tags($_POST['req-email']) . "\r\n";
$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
$headers .= "CC: joe@toltech.co.uk\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
mail($to, $subject, $message, $headers);


?>