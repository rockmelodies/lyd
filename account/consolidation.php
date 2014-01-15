<?php
ob_start();
require_once('include/entryPoint.php');


isLoggedin();

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

$(document).ready(function(){
    $('#master_consolidation').validationEngine();
    $('#child_account_validation').validationEngine();
});

function verifyMaster(){
    var password = $('#master_password').val();
    if($('#master_consolidation').validationEngine('validate')){
        $('#master_message').html('<img src="images/loading_transparent.gif">');
        var request = $.ajax({
            type: 'POST',
            url: 'validateContact.php',
            data: { password: password, acctype: 'master'},
            dataType: 'html',
            success: function( msg ) {
                $obj = eval('('+msg+')');
                $('#master_message').html($obj.message);
                if($obj.no_of_shares != ''){
                    $('#master_opn_share').html($obj.no_of_shares);
                }
                if($obj.message == 'VERIFIED'){
                    $('#child_account').show();
                }else{
                   $('#master_password').val('');
                   $('#master_opn_share').html('');
                   $('#child_account').hide(); 
                   $('#child_account_validation').validationEngine('hide');
                }
            },
        });
    }    
}


function verifyChild(){
    var account_number = $('#child_account_number').val();
    var password = $('#child_password').val();
    
    if($('#child_account_validation').validationEngine('validate')){
        $('#child_message').html('<img src="images/loading_transparent.gif">');
        var request = $.ajax({
            type: 'POST',
            url: 'validateContact.php',
            data: { account_number : account_number, password: password, acctype: 'child'},
            dataType: 'html',
            success: function( msg ) {
                $obj = eval('('+msg+')');
                $('#child_message').html($obj.message);
                if($obj.no_of_shares != ''){
                    $('#child_opn_share').html($obj.no_of_shares);
                }
                if($obj.message == 'VERIFIED'){
                    $('#merge_account_button').show();
                    $('#merge_account_number').val(account_number);
                    document.getElementById("merge_account_button").disabled = false;
                }else{
                   $('#child_opn_share').html(''); 
                   $('#child_password').val('');
                   $('#merge_account_button').hide(); 
                }
            },
        });
    }    
}

function mergeAccount(){
    var account_number = $('#merge_account_number').val();
    document.getElementById("merge_account_button").disabled = true; 
    //$('#merge_account_button').css('disabled', true);
    $('#merge_message').html('<img src="images/loading_transparent.gif">');
    var request = $.ajax({
        type: 'POST',
        url: 'mergeAccount.php',
        data: { account_number : account_number},
        dataType: 'html',
        success: function( msg ) {
            $obj = eval('('+msg+')');
            
            if($obj.message == 'MERGED'){
                $('#merge_message').html('Accounts merged Successfully<br><br>.');
				$('#merge_again').html('Do you want to merge another account?<br><input type="button" name="merge_again_button" id="merge_again_button" value="Yes" class="form-button" onclick="mergeAgain();">&nbsp;&nbsp;<input type="button" name="merge_cancel_button" id="merge_cancel_button" value="No" class="form-button" onclick="mergeCancel();">');
                $('#master_opn_share').html($obj.no_of_shares);
            }else{
                $('#merge_message').html($obj.message+'<br><br>');
				$('#merge_again').html('Do you want to merge another account?<br><input type="button" name="merge_again_button" id="merge_again_button" value="Yes" class="form-button" onclick="mergeAgain();">&nbsp;&nbsp;<input type="button" name="merge_cancel_button" id="merge_cancel_button" value="No" class="form-button" onclick="mergeCancel();">');
            }
        },
    });
}

function mergeAgain(){
	document.getElementById("merge_account_button").disabled = false;
	$('#merge_message').html('');
	$('#merge_again').html('');
	$('#child_opn_share').html(''); 
	$('#child_password').val('');
	$('#merge_account_button').hide();
	$('#child_message').html('');
	$('#merge_account_number').val('');
	$('#child_account_number').val('');
}

function mergeCancel(){
	location.href='index.php';
}
</script>

</head>



<body>



<div id="container">



<!--header start-->

<?php include_once "eurivex_header.php"; ?>

<!--end header-->



<!--start main-->
 <div class="clr"></div>
  
  <div class="content">
	  <div class="images/logo.png"></div> <!------eurix logo----------->
    <!--<div class="content-back2"></div>-->
  
    <div class="content_resize" >
      <div class="mainbar" style="width:800px;float:none;margin:0 auto;color:#3367CD;">
        <div class="article" >
       

<div class="custom" style="border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#495391;margin-top:25px;overflow:hidden;color:#fff; ">
<script type="text/javascript">
$(document).ready(function(){
	$('#login_form').validationEngine();
});
</script>

 <div style="background:#178d27;height: 80px;text-align:center;">
         <div id="subheader">CONSOLIDATION</div><!--<img src="images/ch_password.png" style="margin-top: 10%;"/>-->
         </div>

        
        
        

        <form name="master_consolidation" id="master_consolidation" action="" method="post">
        <div class="table-head">Merge/Consolidate Accounts</div>
        <div style="clear:both"></div>
        <table class="formTable" cellpadding="0" cellspacing="0" width="90%">
          <tr>
            <td align="left" width="25%">Master Account</td>
            <td align="left" width="25%">Password</td>
            <td align="left" width="10%"></td>
            <td align="left" width="20%"></td>
            <td align="left" width="20%">OPN Share</td>
          </tr>
          
          <tr>
            <td><?php echo $_SESSION['username'] ?></td>
            <td><input type="password" name="master_password" id="master_password" class="inputbox validate[required]" style="width: 120px;"/></td>
            <td><input type="button" name="verify_master" value="Verify" id="verify_master" class="form-button" onclick="verifyMaster();"  /></td>
            <td><span id="master_message"></span></td>   
            <td><span id="master_opn_share"></span></td>       
          </tr>               
        </table>
        </form>
        
        <div id="child_account" style="display: none;"> 
        
            <form name="child_account_validation" id="child_account_validation" action="" method="post">
            <table class="formTable" cellpadding="0" cellspacing="0" width="90%">
              <tr>
                <td align="left" width="25%">Account Number</td>
                <td align="left" width="25%">Password</td>
                <td align="left" width="10%"></td>
                <td align="left" width="20%"></td>
                <td align="left" width="20%">OPN Share</td>
              </tr>
              
              <tr>
                <td><input type="text" name="child_account_number" id="child_account_number" class="inputbox validate[required]" style="width: 120px;"/></td>
                <td><input type="password" name="child_password" id="child_password" class="inputbox validate[required]" style="width: 120px;"/></td>
                <td><input type="button" name="verify_child" value="Verify" id="verify_child" class="form-button" onclick="verifyChild();"  /></td>
                <td><span id="child_message"></span></td>   
                <td><span id="child_opn_share"></span></td>       
              </tr>
              <tr>
                <td>
                <input type="hidden" name="merge_account_number" id="merge_account_number" value="" >
                <input type="button" name="merge_account_button" id="merge_account_button" value="Merge" style="display: none;" class="form-button" onclick="mergeAccount();"></td>
                <td colspan="2"><span id="merge_message"></span></td>
				<td colspan="2"><span id="merge_again"></span></td>
              </tr>        
            </table>
            </form>
            
        </div>

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