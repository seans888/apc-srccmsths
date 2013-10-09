<?php
require '../Core/SCV2_Core.php';
init_SCV2();

if(isset($_GET['Username']))
{
	$Username = $_GET['Username'];	
	
	$mysqli = connect_DB();
	$mysqli->real_query("SELECT `Username`,`Password` 
							FROM `user` 
							WHERE `Username`='$Username'");
	if($result = $mysqli->use_result())
	{
		$data = $result->fetch_assoc();
		extract($data);
	}
	else die($mysqli->error);
}
elseif($_POST['formKey'] == $_SESSION['formKey'])
{
	if($_POST['Cancel']) header('location: ListView_Users.php');
}

drawHeader($errMsg);
drawPageTitle('Detail View: Users');
drawFieldSetStart();
drawTextField('Username', 'Username',TRUE);
drawTextField('Password', 'Password',TRUE);
drawButton('SPECIAL','button1','Cancel','BACK',TRUE,2); 
drawFieldSetEnd();
drawFooter(); ?>
