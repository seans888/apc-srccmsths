<?php
require '../Core/SCV2_Core.php';
init_SCV2();

if(isset($_GET['Page_ID']))
{
	$Page_ID = $_GET['Page_ID'];	
	
	$mysqli = connect_DB();
	$mysqli->real_query("SELECT `Page_Name`, `Generator`, `Description` 
							FROM `page` 
							WHERE `Page_ID`='$Page_ID'");
	if($result = $mysqli->use_result())
	{
		$data = $result->fetch_assoc();
		extract($data);
	}
	else die($mysqli->error);
}
elseif($_POST['formKey'] == $_SESSION['formKey'])
{
	if($_POST['Cancel']) header('location: ListView_Pages.php');
}

drawHeader();
drawPageTitle('Detail View: Page',$errMsg);
?>
<div class="container_mid">
<fieldset class="top">
View Page Generator: <?php echo $Page_Name;?>
</fieldset>

<fieldset class="middle">
<table class="input_form">
<?php
drawTextField('Page Name', 'Page_Name',TRUE);
drawTextField('Generator','',TRUE);
drawTextField('Description','',TRUE);
?>
</table>
</fieldset>
<fieldset class="bottom">
<?php 
drawButton('SPECIAL','button1','Cancel','BACK',TRUE,2);
?>
</fieldset>
</div>
<?php
drawFooter();
