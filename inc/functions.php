<?php

// Define Metabox
add_action( 'add_meta_boxes', 'add_metaboxes' );
function add_metaboxes() {
    add_meta_box("toltech_form_details", "Toltech Form Details", "toltech_form_ui", "forms");
}

function toltech_form_ui() {
    global $post;
    $custom = get_post_custom($post->ID);

    global $wpdb;
    
    $table_name = $wpdb->prefix . 'forms_structure';
    $switch=0;
    $form_id=$wpdb->get_var(	$wpdb->prepare('SELECT form_id FROM '.$table_name.' WHERE post_id=%d',$post->ID) 	);
    if($form_id==''){$form_id = uniqid();$switch=1;}//IF NEW FORM $switch=1

    $description = $custom['_description'][0];

    $output .= '<p><label for="_price"><strong>Genereated Shortcode: <span style="color: #0074a2;">[form id="'. $form_id .'"]</span></strong></label><br />
    <small>Please copy above shortcode into your page to display this form</small></p>';
   
    $output .= '<hr>';

    $output .= '<div style="margin-top: 30px;"></div>';
        
    $output .= '<a id="form-field" href="#" style="margin-bottom: 15px;" class="button button-primary button-large" />Add New Form Field</a>';

    // Default Fields
    $output .= '<table>';
        $output .= '<tr>';
            $output .= '<td>
            				<input type="hidden" name="_form_id" id="_form_id" value="'.$form_id.'" />
                            <input disabled type="text" name="_forename" id="_forename" value="Forename" />
                        </td>';  
            $output .= '<td>
                           <span style="color: red;">Mandatory</span>
                        </td>';
        $output .= '</tr>';

        $output .= '<tr>';     
            $output .= '<td>
                            <input disabled type="text" name="_surname" id="_surname" value="Surname" />
                        </td>';
            $output .= '<td>
                            <span style="color: red;">Mandatory</span>
                        </td>';
        $output .= '</tr>';

        $output .= '<tr>';
            $output .= '<td>
                            <input disabled type="text" name="_email" id="_email" value="Email" />
                        </td>';
            $output .= '<td>
                            <span style="color: red;">Mandatory</span>
                        </td>';
        $output .= '</tr>';

        $output .= '<tr>';
            $output .= '<td>
                            <input disabled type="text" name="_telephone" id="_telephone" value="Telephone" />
                        </td>';
            $output .= '<td>
                            <span style="color: red;">Mandatory</span>
                        </td>';
                $output .= '<td>
                        </td>';
        $output .= '</tr>';
    $output .= '</table>';


    // Custom Field
    $output.="<hr>";
    $x=0;
    if($switch==0){
    	$table_name = $wpdb->prefix . 'forms_customfields';
    	$results=$wpdb->get_results($wpdb->prepare('SELECT * FROM '.$table_name.' WHERE form_id=%s',$form_id));
    	foreach($results as $result){
    
    		        $output .= '<table class="table">';
    		            $output .= '<tr>';
    		                $output .= '<td colspan="3">
    		                                <input type="text" name="'.$x.'_label" id="'.$x.'_label" value="'.$result->label.'" />
    		                            </td>
    		                            </tr><tr>';
    		                $output .= '<td>
    		                                <input type="text" name="'.$x.'_name" id="'.$x.'_name" value="'.$result->name.'" readonly="readonly"/>
    		                            </td>';
    		                $output .= '<td>
    		                                <select name="'.$x.'_type" id="'.$x.'_type">
    		                                <option>Text Input</option>
    		                                <option>Text Area</option>
    		                                <option>Radio Button</option>
    		                                <option>Checkbox</option>
    		                                <option>Select</option>
    		                                </select>
    		                            </td>';
    		                $output .= '<td>
    		                            <div style="margin-left: 10px;">
    		                                Required Field? <input type="checkbox" name="'.$x.'_mandatory" id="'.$x.'_mandatory" value="Field 1 Name" />
    		                                <a style=" margin-left: 15px;"href="#" />[x]</a>
    		                            </div>
    		                        </td>';
    		                $output .= '<tr>';
    		        $output .= '</table>';
    		        $x++; 
    	}
    }
    else{
    	$output.='<div class="placeholder"><br>Custom Form Fields Will Appear Here....</div>';
    }
        // jQuery Dynamic Custom Field
        
        $output .='<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script><script>
        $(document).ready(function(){
                var label="0_label"
                var name="0_name";
                var type="0_type";
                var mandatory="0_mandatory";
                
                
                
                var i = '.$x.';
                var j = '.($x+1).';
                var foo = "first_cf";
                
               	if(i>0){foo="";i--;j--;} //only executes on a form with existing custom fields 
                
//                console.log("i="+i+" j="+j+" foo="+foo);
//                console.log("----------------------------");
                
	            $("#form-field").click(function() {
	                
	                if(foo=="first_cf"){
	                	$("div.placeholder").replaceWith("<table class=\"table\"><tr><td colspan=\"3\"><input type=\"text\" name=\"'.$x.'_label\" id=\"'.$x.'_label\" value=\"Label\" /></td><tr><td><input type=\"text\" name=\"'.$x.'_name\" id=\"'.$x.'_name\" value=\"Custom Field Unique ID\" /></td><td><select name=\"'.$x.'_type\" id=\"'.$x.'_type\"><option>Text Input</option><option>Text Area</option><option>Radio Button</option><option>Checkbox</option><option>Select</option></select></td><td>Required Field? <input type=\"checkbox\" name=\"'.$x.'_mandatory\" id=\"'.$x.'_mandatory\" value=\"Field 1 Name\" /><a style=\" margin-left: 15px;\" href=\"#\" />[x]</a></td></tr></table>");
	                	foo="";
	                }else{                
		                var cloned = $(".table").last().clone();                
		                cloned.find("#" + i + "_label").attr("id", j + "_label").attr("name", j + "_label");
		                cloned.find("#" + i + "_name").attr("id", j + "_name").attr("name", j + "_name");
		                	cloned.find("#" + j + "_name").prop("readonly",false);
		    			cloned.find("#" + i + "_type").attr("id", j + "_type").attr("name", j + "_type");
		    			cloned.find("#" + i + "_mandatory").attr("id", j + "_mandatory").attr("name", j + "_mandatory");
	    				i++; j++;
	    			}
	    			  
	    			
//	    			console.log("i="+i+" j="+j);
//	    			console.log("------");
	    			 
	    			cloned.appendTo($(".next-table"));
	    			
	    			$("#cf").attr("value",j);
	    			
	                return false;
	    
	            });
        });
            </script>';
    
    //Hidden field to hold number of custom fields added
    $output .='<input type="hidden" name="cf" id="cf" value="'.$x.'">';

    // Dynamic new field controlled by jQuery
    $output .= '<div class="next-table"></div>';
    

    $output .= '<div style="margin-top: 30px;"></div>';
    $output .= '<hr>';
    
    $output .= '<h3 style="border: 0px; padding-left: 0px; padding-top: 0px;">Form Settings</h3>';
    $output .= '<table style="float: left; margin-right: 15px;">
                    <tr>
                        <td>Recipients:</td>
                        <td><input type="text" name="_recipients" id="_recipients" value="" /></td>
                    </tr>
                    <tr>
                        <td>Subject:</td>
                        <td><input type="text" name="_subject" id="_subject" value="" /></td>
                    </tr>
                    <tr>
                        <td>Return URL:</td>
                        <td><input type="text" name="_return_url" id="_return_url" value="" /></td>
                    </tr>
                    <tr>
                        <td>Success Message:</td>
                        <td><input type="text" name="_success_message" id="_success_message" value="" /></td>
                    </tr>
                    <tr>
                        <td>Google Analytics:</td>
                        <td><input type="text" name="_ga" id="_ga" value="" /></td>
                    </tr>
                    <tr>
                        <td>Google Goal:</td>
                        <td><input type="text" name="_ga_goal" id="_ga_goal" value="" /></td>
                    </tr>
                    <tr>
                        <td>CSS Class Name:</td>
                        <td><input type="text" name="_class" id="_class" value="" /></td>
                    </tr>
                </table>';

    $output .= '<table style="float: left;">
                    <tr>
                        <td>Test Mode <input type="checkbox" name="_test_mode" id="_test_mode" value="Field 1 Name" /></td>
                    </tr>
                </table>';
    
    $output .= '<div style="clear: both;"></div>';

    echo $output;  

}


/*/ Saving Meta Boxes /*/
add_action('save_post', 'form_structure_save_post');
function form_structure_save_post($post_id) {

	$post = get_post($post_id);	
	if ($post->post_type != 'forms' || !isset($_POST['_form_id']))
			return;
	/////////////////////////////////
	// CREATE SOME LOCAL VARIABLES //
	/////////////////////////////////
	$form_id=$_REQUEST['_form_id'];
	$cf=$_REQUEST['cf'];
	$html=''; //populated below
	$recipients=$_REQUEST['_recipients'];
	$subject=$_REQUEST['_subject'];
	$return_url=$_REQUEST['_return_url'];
	$success_message=$_REQUEST['_success_message'];
	$ga=$_REQUEST['_ga'];
	$ga_goal=$_REQUEST['_ga_goal'];
	$class=$_REQUEST['_class'];
	$test_mode=$_REQUEST['_test_mode'];
	
	$standard = array();
	$standard['0']="Forename"; $standard['1']="Surname"; $standard['2']="Email"; $standard['3']="Telephone";
	
	$action=plugins_url()."/toltech-forms/inc/process.php";
	
	/////////////////////////////////////////////
	// CREATE HTML FOR DISPLAYING ON FRONTEND //
	////////////////////////////////////////////
	$html='<div class="'.$class.'">';
		$html.='<small><span style="color: red;">*</span> Required Field.</small>';
		$html.='<form method="post" action="'.$action.'">';
			//add standard fields
			foreach($standard as $field){
			$html.='<br />
			<label for="'.$field.'">'.$field.':</label> <span style="color: red;">*</span><br />
			<input type="text" name="'.strtolower($field).'" size="50" id="'.strtolower($field).'" /><br /><br />';
			}
			//add non standard fields

			$x=0;
			while($x!=$cf){
				$label=$_REQUEST[$x.'_label'];
				$name=$_REQUEST[$x.'_name'];
				$type=$_REQUEST[$x.'_type'];
				switch($type)
				{
				    case 'Text Input':
				        $html.='<br />
				        <label for="'.strtolower($name).'">'.$label.':</label> <span style="color: red;">*</span><br />
				        <input type="text" name="'.$x.'_name" size="50" id="'.$x.'_name" />
				        <input type="hidden" name="'.$x.'_key" size="50" id="'.$x.'_key" value="'.$name.'"/>
				        <br /><br />';
				        break;
				
				    case 'Text Area':
				        
				        break;
				
				    case 'Radio Button':
				        
				        break;
				
				    case 'Checkbox':
				        
				        break;
				        
				    case 'Select':
				        
				        break;
				
				    default:
				        
				        break;
				}
				$x++;
			}
			$html.='<input type="hidden" name="cf" id="cf" value="'.$cf.'">';
			$html.='<input type="hidden" name="form_id" id="form_id" value="'.$form_id.'">';
			$html.='<input type="submit" name="submit" value="Get In Touch" id="button" class="enquiryform" />';
		$html.='</form>';
	$html.="</div>";
	
	
	//////////////////////////////////////////////////////////////////
	// ADD TO form_structure TABLE									//
	// 1. INSERTS NEW RECORD, A NEW FORM TYPE (enquiry, signup etc) //
	// 2. UPDATES A RECORD, UPDATING AN EXISTING FORM TYPE	 		//
	//////////////////////////////////////////////////////////////////
	global $wpdb;
	error_log("-----------------------------------------------------------------------------------".PHP_EOL, 3, LOG_FILE);
	$table_name = $wpdb->prefix . 'forms_structure';
    $existing_form=$wpdb->get_var($wpdb->prepare('SELECT form_id FROM '.$table_name.' WHERE post_id=%d',$post->ID));
	
	if($existing_form===NULL){	
		////////////////////////////////////////////////////////////////////////////
	    // NEW RECORD, OR PUT SIMPLY A NEW FORM TYPE!!							  //
	    // 1. INSERT RECORD WHICH STORES THE HTML AND OTHER VARIOUS FORM DETAILS  //
	    // 2. INSERT RECORDS INTO forms_customfields.							  //
	    //	  THEY DESCRIBE CUSTOM FIELDS AND GIVE US THE ABILITY TO EDIT		  //
	    //	  OR FORMS IN THE WP BACKEND										  //
	    ////////////////////////////////////////////////////////////////////////////  
	    
	    $wpdb->query(
			$wpdb->prepare(
			"INSERT INTO ".$table_name."
			(form_id,post_id,html,recipients,subject,return_url,success_message,ga,ga_goal,class,test_mode) 
			VALUES 
			(%s,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
			$form_id,
			$post_id,
			$html,
			$recipients,
			$subject,
			$return_url,
			$success_message,
			$ga,
			$ga_goal,
			$class,
			$test_mode
			)
		);
		
		///////////////////////////////////////////////////////////////////////
		// LOOP THROUGH ALL THE CUSTOM FIELDS WHICH DESCRIBE THE FORM 		 //
		// INSERT EACH ONE INTO A NEW RECORD IN THE forms_customfields TABLE // 
		///////////////////////////////////////////////////////////////////////
		$x=0;
		while($x!=$cf){
			// USE $cf TO CONTROL HOW MANY CUSTOM FIELDS TO ADD
			// TAKEN FROM PREVIOUS FORM SUBMIT 
			$label=$_REQUEST[$x.'_label'];
			$name=$_REQUEST[$x.'_name'];
			$type=$_REQUEST[$x.'_type'];
	
			$table_name = $wpdb->prefix . 'forms_customfields';
			$wpdb->query(
				$wpdb->prepare(
				"INSERT INTO ".$table_name."
				(form_id,label,name,type) 
				VALUES 
				(%s,%s,%s,%s)",
				$form_id,
				$label,
				$name,
				$type
				)
			);
		$x++;
		}
		
		
	}else{
		///////////////////////////////////////////////////////////////////////////////
		// UPDATE EXISTING RECORD, OR SIMPLY PUT UPDATE EXISTING FORM TYPE 			 //
		//	1. UPDATE GENERIC FORM DETAILS, EASY ENOUGH								 //
		//	2. NOT AS SIMPLE AS BEFORE AS NUMBER OF CUSTOM FIELDS COULD HAVE CHANGED //
		//	   MEANING THAT SOME WILL NEED TO BE UPDATED AND ANY NEW ONES INSERTED!! //
		///////////////////////////////////////////////////////////////////////////////
		//mail("joe@toltech.co.uk","1","Test","From:\n");
		error_log(">> ATTEMPTING TO UPDATE (".$table_name.") ".PHP_EOL, 3, LOG_FILE);		
		
		$wpdb->query(
			$wpdb->prepare(
			"UPDATE ".$table_name." SET
			html=%s, recipients=%s, subject=%s, return_url=%s, success_message=%s, ga=%s, ga_goal=%s, class=%s, test_mode=%s
			WHERE form_id=%s AND post_id=%d	
			",
			$html,
			$recipients,
			$subject,
			$return_url,
			$success_message,
			$ga,
			$ga_goal,
			$class,
			$test_mode,
			$form_id,
			$post_id
			)
		);
		error_log(">> SUCCESS UPDATING (".$table_name.") ".PHP_EOL, 3, LOG_FILE);

		$x=0;
		while($x!=$cf){ // && isset($_REQUEST[$x.'_label']
			//Decide if new custom field or existing one
			$label	=	$_REQUEST[$x.'_label'];
			$name	=	$_REQUEST[$x.'_name'];
			$type	=	$_REQUEST[$x.'_type'];			
			
			error_log(">> INSIDE WHILE LOOP (".$x.")".PHP_EOL, 3, LOG_FILE);
			$table_name = $wpdb->prefix . 'forms_customfields';
			
			$field_id=$wpdb->get_var($wpdb->prepare('SELECT field_id FROM '.$table_name.' WHERE name=%s AND form_id=%s',$name,$form_id));
			
			error_log("		form_id			=	".$form_id."".PHP_EOL, 3, LOG_FILE);	
			error_log("		field_id		=	".$field_id."".PHP_EOL, 3, LOG_FILE);
			error_log("		".$x."_label	=	".$label."".PHP_EOL, 3, LOG_FILE);
			error_log("		".$x."_name		=	".$name."".PHP_EOL, 3, LOG_FILE);
			error_log("		".$x."_type		=	".$type."".PHP_EOL, 3, LOG_FILE);
			
			if($field_id===NULL){
			error_log(">> NEW CUSTOM FIELD (".$x.")".PHP_EOL, 3, LOG_FILE);
			error_log(">> INSERT INTO ".$table_name." (form_id,label,name,type) VALUES (".$form_id.",".$label.",".$name.",".$type.")".PHP_EOL, 3, LOG_FILE);
			//NEW CUSTOM FIELD
				//INSERT NEW CUSTOM FIELD
					$wpdb->query(
						$wpdb->prepare(
						"INSERT INTO ".$table_name."
						(form_id,label,name,type) 
						VALUES 
						(%s,%s,%s,%s)",
						$form_id,
						$label,
						$name,
						$type
						)
					);
			}else{
			error_log(">> UPDATING EXISTING CUSTOM FIELD (".$x.")".PHP_EOL, 3, LOG_FILE);
			error_log(">> UPDATE ".$table_name." SET label=".$label." WHERE field_id=".$field_id." AND form_id=".$form_id."".PHP_EOL, 3, LOG_FILE);
			//UPDATE EXISTING CUSTOM FIELD
				//UPDATE CUSTOM FIELD
				$wpdb->query(
					$wpdb->prepare(
					"UPDATE ".$table_name." SET
					label=%s
					WHERE field_id=%s AND form_id=%s",
					$label,
					$field_id,
					$form_id
					)
				);
			}
			
		$x++;
		error_log("------".PHP_EOL, 3, LOG_FILE);		
		}
		error_log("FINISHED UPDATING!!!".PHP_EOL, 3, LOG_FILE);
		error_log("-----------------------------------------------------------------------------------".PHP_EOL, 3, LOG_FILE);
	}
	
}

?>