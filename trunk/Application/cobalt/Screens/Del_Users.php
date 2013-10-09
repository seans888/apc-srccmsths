<?php
require '../Core/SCV2_Core.php';
init_SCV2();

if(isset($_GET['Username']))
{
	$Username = $_GET['Username'];	
	
	$mysqli = connect_DB();
	$mysqli->real_query("SELECT `Username`
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
	elseif($_POST['Delete'])
	{
		queryDeleteUser($_POST);
		header("location: /SCV2/success.php?success_tag=DeleteUsers");
	}
}

drawHeader($errMsg);
drawPageTitle('Detail View: User');
?>
<input type="hidden" name="Username" value="<?php echo $Username;?>">
<?php 
drawFieldSetStart();
drawTextField('Username', 'Username',TRUE);
drawSubmitCancel(TRUE,2,'Delete','DELETE');
drawFieldSetEnd();
drawFooter();
