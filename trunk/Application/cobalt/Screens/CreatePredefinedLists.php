<?php
require '../Core/SCV2_Core.php';
init_SCV2();

if($_POST['formKey'] == $_SESSION['formKey'])
{
	if($_POST['Cancel']) header("location: ListView_PredefinedLists.php");

	if($_POST['Submit'] || $_POST['particularButton']) extract($_POST);

	if($_POST['Submit'])
	{
		$errMsg = scriptCheckIfNull('List Name', $List_Name,
									'Remarks', $Remarks);
		
		for($a=0;$a<$particularsCount;$a++)
		{
			$b = $a + 1;
			$errMsg .= scriptCheckIfNull("List Item #$b", $List_Item[$a]);
		}

		$select = "SELECT `List_ID` FROM `table_fields_predefined_list` WHERE `List_Name`='$List_Name'"; 
		$error = "The list name '$List_Name' already exists. Please choose a new one. <br>";
		$errMsg .= scriptCheckIfUnique($select, $error);

		if($errMsg=="")
		{
			queryCreatePredefinedList($_POST);
			header("location: ../success.php?success_tag=CreatePredefinedLists");
		}
	}
}

drawHeader();
drawPageTitle('Create Predefined List',$errMsg);
?>
<div class="container_mid">
<fieldset class="top">
New List
</fieldset>

<fieldset class="middle">
<table class="input_form">
<?php
drawTextField('List Name', 'List_Name');
drawTextField('Remarks','','','Textarea');
drawMultiFieldStart('List Items');
	if($numParticulars<1) $numParticulars=1;
	for($a=0;$a<$numParticulars;$a++) 						
	{
		echo "<li style='margin: 5'><input type='text' name='List_Item[$a]' value='$List_Item[$a]'>";
	}
drawMultiFieldEnd();
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
