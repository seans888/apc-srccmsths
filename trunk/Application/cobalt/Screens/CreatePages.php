<?php
require '../Core/SCV2_Core.php';
init_SCV2();

if($_POST['formKey'] == $_SESSION['formKey'])
{
	if($_POST['Cancel']) header("location: ListView_Pages.php");
	
	if($_POST['Submit'])
	{
		extract($_POST);
		$errMsg = scriptCheckIfNull('Page Name', $Page_Name,
									'Generator', $Generator,
									'Description', $Description);

		if($errMsg=="")
		{
			queryCreatePage($_POST);
			header("location: ../success.php?success_tag=CreatePages");
		}
	}
}

drawHeader();
drawPageTitle('Create Page',$errMsg);
?>
<div class="container_mid">
<fieldset class="top">
New Page Generator
</fieldset>

<fieldset class="middle">
<table class="input_form">
<?php
drawTextField('Page Name', 'Page_Name');
drawTextField('Generator');
drawTextField('Description','','','Textarea');
?>
</table>
</fieldset>
<fieldset class="bottom">
<?php
drawSubmitCancel();
?>
</fieldset>
</div>
<?php
drawFooter();
?>
