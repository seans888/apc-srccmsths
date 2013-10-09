<?php
require '../Core/SCV2_Core.php';
init_SCV2();

if(isset($_GET['DB_Connection_ID']))
{
	$DB_Connection_ID = $_GET['DB_Connection_ID'];	
	
	$mysqli = connect_DB();
	$mysqli->real_query("SELECT `DB_Connection_Name`, `Hostname`, `Username`, `Password`, `Database` 
							FROM `database_connection` 
							WHERE `DB_Connection_ID`='$DB_Connection_ID'");
	if($result = $mysqli->use_result())
	{
		$data = $result->fetch_assoc();
		extract($data);
	}
	else die($mysqli->error);
	$result->close();
	$mysqli->close();

	$mysqli = connect_DB();
	$mysqli->real_query("SELECT `Database_Connection_ID` 
							FROM `project` 
							WHERE Project_ID='$_SESSION[Project_ID]'");
	if($result = $mysqli->use_result())
	{
		$info = $result->fetch_row();
		if($info[0] == $DB_Connection_ID) $Default_Connection = 'Yes';
		else $Default_Connection = 'No';
	}
	else die($mysqli->error);
	$result->close();
	$mysqli->close();
}

if($_POST['formKey'] == $_SESSION['formKey'])
{
	if($_POST['Cancel']) header("location: ListView_DBConnections.php");
}

drawHeader();
drawPageTitle('Detail View: Database Connection',$errMsg);
?>

<div class="container_mid">
<fieldset class="top">
View DB Connection: <?php echo $DB_Connection_Name;?>
</fieldset>

<fieldset class="middle">
<table class="input_form">
<?php

drawTextField('DB Connection Name', 'DB_Connection_Name',TRUE);
drawTextField('Hostname','',TRUE);
drawTextField('Database','',TRUE);
drawTextField('Username','',TRUE);
drawTextField('Password','',TRUE);
drawTextField('Use as Default','Default_Connection',TRUE);
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
