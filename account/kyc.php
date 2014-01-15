<?php

ob_start();



require_once('include/entryPoint.php');

if($_SESSION['opn_payment'] == '0'){
	redirect('index.php');
}


isLoggedin();


if(isset($_POST['Save']))
{

	if(!empty($_FILES["id_proof"]["name"]))
	{
		$filename=stripslashes($_FILES['id_proof']['name']);
		$set_entry_parameters = array(
			"session" => $_SESSION['session'],
			"module_name" => "Documents",
			"name_value_list" => array(
				array("name" => "document_name", "value" => $filename),
				array("name" => "revision", "value" => "1"),
			),
		);
		$set_entry_result = doRESTCALL($crm_url, "set_entry", $set_entry_parameters);
		
		if(!empty($set_entry_result->id)){
			$document_id = $set_entry_result->id;
			$contents = file_get_contents ($_FILES['id_proof']['tmp_name']);
			$set_document_revision_parameters = array(
				"session" => $_SESSION['session'],
				"note" => array(
					'id' => $document_id,
					'file' => base64_encode($contents),
					'filename' => $filename,
					'revision' => '1',
				),
			);
			$set_document_revision_result = doRESTCALL($crm_url, "set_document_revision", $set_document_revision_parameters);
			echo "<pre>";
			print_r($set_document_revision_result);
			echo "</pre>";
		}
	}
	
	if(!empty($_FILES["address_proof"]["name"]))
	{
		$filename_1=stripslashes($_FILES['address_proof']['name']);
		$set_entry_parameters_1 = array(
			"session" => $_SESSION['session'],
			"module_name" => "Documents",
			"name_value_list" => array(
				array("name" => "document_name", "value" => $filename_1),
				array("name" => "revision", "value" => "1"),
			),
		);
		$set_entry_result_1 = doRESTCALL($crm_url, "set_entry", $set_entry_parameters_1);
		
		if(!empty($set_entry_result_1->id)){
			$document_id_1 = $set_entry_result_1->id;
			$contents_1 = file_get_contents ($_FILES['address_proof']['tmp_name']);
			$set_document_revision_parameters_1 = array(
				"session" => $_SESSION['session'],
				"note" => array(
					'id' => $document_id_1,
					'file' => base64_encode($contents_1),
					'filename' => $filename_1,
					'revision' => '1',
				),
			);
			$set_document_revision_result_1 = doRESTCALL($crm_url, "set_document_revision", $set_document_revision_parameters_1);
			echo "<pre>";
			print_r($set_document_revision_result_1);
			echo "</pre>";
		}
	}
	
	if(!empty($set_document_revision_result->id) && !empty($set_document_revision_result_1->id)){
		
		$set_relationship_parameters = array(
         "session" => $_SESSION['session'],
         "module_name" => "Contacts",
		 "module_id" => $_SESSION['user_id'],
		 "link_field_name" => "documents",
         "related_ids" => array(
             $document_id,
			 $document_id_1,
			),
		);
		$set_relationship_result = doRESTCALL($crm_url, "set_relationship", $set_relationship_parameters);
		
		$set_entry_parameters = array(
         "session" => $_SESSION['session'],
         "module_name" => "Contacts",
         "name_value_list" => array(
             "id" => $_SESSION['user_id'],
             "kyc_uploaded_c"  => '1',
			 "status_c"  => 'Under Review',
			),
		);
		
		$set_entry_result = doRESTCALL($crm_url, "set_entry", $set_entry_parameters);
		$_SESSION['kyc_uploaded_c'] = '1';
		$_SESSION['kyc_uploaded'] = 'Thank you. Your Documents have been forwarded for processing.';
		
	}
	
	redirect("kyc.php");

}	



?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<link href="stylesheet/main.css" rel="stylesheet" type="text/css" />



<title>LydorMarkets</title>



<script src="js/jquery.js" type="text/javascript"></script>


<script src="js/jquery.validate.js" type="text/javascript"></script>

<script type="text/javascript">
$().ready(function() {
	/*$.validator.addMethod('filesize', function(value, element, param) {
    	return this.optional(element) || (element.files[0].size <= param) 
	}, 'Please select a file.');
	*/
	$("#uploadKYC").validate({
		rules: {
			id_proof: {
				required: true,
				accept: "jpg|png|gif|jpeg|doc|docx|pdf|txt"
			},
			address_proof: {
				required: true,
				accept: "jpg|png|gif|jpeg|doc|docx|pdf|txt"
			},
		},
		messages: {
			id_proof: "Please select a  document file",
			address_proof: "Please select a document file",
		}
	});

});
</script>

</head>

<body>


<div id="container">


<!--header start-->



<?php include_once "eurivex_header.php"; ?>



<!--end header-->


<!--start main-->



<!--<div id="main">-->

<div class="clr"></div>
  
  <div class="content">
    <!--<div class="content-back2"></div>-->
	  <div class="images/logo.png"></div> <!------eurix logo----------->
    <div class="content_resize" >
      <div class="mainbar" style="width:660px;float:none;margin:0 auto;color:#3367CD;">
        <div class="article" >
       

<div class="custom" style="border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#495391;margin-top:25px;overflow:hidden;color:#fff; ">
<script type="text/javascript">
$(document).ready(function(){
	$('#login_form').validationEngine();
});
</script>

 <div style="background:#178d27;height: 80px;text-align:center;">
         <div id="subheader">UPLOAD KYC DOCUMENTS</div><!--<img src="images/upload_kyc.png" style="margin-top: 10%;"/>-->
         
         </div>

    	
        
			
            <?php if(isset($_SESSION['kyc_uploaded'])){ ?>
            <form>
             <table class="formTable" cellpadding="0" cellspacing="0" width="90%">
             
            <tr>
            
            <td colspan="3" align="center"><span class="success"><?php  echo $_SESSION['kyc_uploaded']; ?></span></td>
            
            </tr>
            </table>
            </form>
            <?php }else if(isset($_SESSION['kyc_uploaded_c']) && ($_SESSION['kyc_uploaded_c'] == 1) ){ ?> 
            <form>
             <table class="formTable" cellpadding="0" cellspacing="0" width="90%">
        
        	<tr>
            	<th valign="top" colspan="3" style="text-align:center">You have already submitted your KYC Documents. Thank You.</th>
            </tr>
        
        
        </table>
        </form>
        
	<?php
		}else{
			
	?>
	<form name="uploadKYC" id="uploadKYC" action="" method="post" enctype="multipart/form-data" onsubmit="ajaxUpload();">


        <table class="formTable" cellpadding="0" cellspacing="0" width="90%">


          <tr>



          	<td colspan="3" align="center" class="dpTitleText"></td>



          </tr>	
          
          
         
          
           <tr>



            <th valign="top">ID Proof:</th>



            <td><input type="file" name="id_proof" id="id_proof" class="form-file"/></td>



            <td></td>



          </tr> 
          
          <tr>



            <th valign="top">Addrress Proof:</th>



            <td><input type="file" name="address_proof" id="address_proof" class="form-file"/></td>



            <td></td>



          </tr> 

          <tr>



          	<th>&nbsp;</th>



			<td valign="top">



			<input type="submit" name="Save" value="Upload" id="Save" class="form-button"/>



			<input type="reset" value="Reset" class="form-button"/>



			</td>



			<td></td>



          </tr>



        </table>



        <input type="hidden" name="date_entered" id="date_entered" value="<?php echo date('Y-m-d H:i:s') ?>" />



        <input type="hidden" name="date_modified" id="date_modified" value="<?php echo date('Y-m-d H:i:s') ?>" />



        <input type="hidden" name="created_by" id="created_by" value="<?php echo $_SESSION['user_id'] ?>" />



        <input type="hidden" name="modified_by" id="modified_by" value="<?php echo $_SESSION['user_id'] ?>" />



        </form>

		<?php } ?>

</div>

<div class="clr"></div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>

<!--end main-->



<!--start footer-->



<div id="footer">



	<?php include_once "footer.php"; ?>



</div>



<!--end footer-->



</div>


</body>



</html>



<?php ob_flush(); ?>