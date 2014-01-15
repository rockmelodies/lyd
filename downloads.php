<?php include('header.php');?>

<?php
//download
  if(isset($_GET['dl']))
  {
	  	$file="downloads/".$_GET['dl'];
	 
		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename='.basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			exit;
		}
  }

?>

      <div class="clr"></div>
  
  <div class="content">
    <div class="content_resize">
      <div class="mainbar" style="width:960px;">
        <div class="article">
         <h2>Downloads</h2>
		 
	
<br />
<table width="100%" border="0">
  <tr>
    <td width="28%"><a href="downloads/Consumers-Guide-to-MiFID.pdf">Consumers Guide to MiFID</a></td>
    <td width="21%"><a href="downloads.php?dl=Consumers-Guide-to-MiFID.pdf"><img src="images/download.png" width="30px"/> </a>
</td>  
<tr>
    <td width="28%"><a href="downloads/Users-Guide.pdf">User Guide</a></td>
    <td width="21%"><a href="downloads.php?dl=Users-Guide.pdf"><img src="images/download.png" width="30px"/> </a></td>
    <td width="51%">&nbsp;</td>
  <tr>
    <td width="28%"><a href="downloads/Wire_Transfer_Details.pdf">Wire Transfer Details</a></td>
    <td width="21%"><a href="downloads.php?dl=Wire_Transfer_Details.pdf"><img src="images/download.png" width="30px"/> </a></td>
  <tr>
    <td width="28%"><a href="downloads/Change_Details_Form.pdf">Change Of Personal Details Form</a></td>
    <td width="21%"><a href="downloads.php?dl=Change_Details_Form.pdf"><img src="images/download.png" width="30px"/> </a></td>
    <td width="51%">&nbsp;</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>


</p>

         
          
          <div class="clr"></div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <?php include('footer.php');?>