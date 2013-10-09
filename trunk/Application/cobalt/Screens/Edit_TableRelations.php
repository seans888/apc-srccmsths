<?php
require '../Core/SCV2_Core.php';
init_SCV2();

if(isset($_GET['Relation_ID']))
{
	$Relation_ID = $_GET['Relation_ID'];	
	
	$mysqli = connect_DB();
	$mysqli->real_query("SELECT Relation_ID, Relation, Label, 
								Parent_Field_ID, Child_Field_ID, Child_Field_Subtext 
							FROM `table_relations`  
							WHERE `Relation_ID`='$Relation_ID'");
	if($result = $mysqli->use_result())
	{
		$data = $result->fetch_assoc();
		extract($data);
	}
	else die($mysqli->error);
	$mysqli->close();
}
elseif($_POST['formKey'] == $_SESSION['formKey'])
{
	if($_POST['Cancel']) header("location: ListView_TableRelations.php");

	if($_POST['Submit'])
	{
		extract($_POST);		
		$errMsg = scriptCheckIfNull('Relation', $Relation,
									'Parent', $Parent_Field_ID,
									'Child', $Child_Field_ID);
									
		if($Relation=="ONE-to-ONE") $errMsg .= scriptCheckIfNull('Child Field Subtext', $Child_Field_Subtext);
									
		if($errMsg=="")
		{
			queryUpdateTableRelation($_POST);
			header("location: ../success.php?success_tag=EditTableRelations");
		}
	}
}

drawHeader();
drawPageTitle('Edit Table Relations',$errMsg);
echo '<input type="hidden" name="Relation_ID" value="' . $Relation_ID . '">';
?>
<div class="container_mid_huge">
<fieldset class="top">
Modify Table Relation
</fieldset>

<fieldset class="middle">
<table class="input_form">
<?php
drawTextField('Label','Label',FALSE,'text',TRUE,FALSE,0,'size="50"');
drawSelectField('drawTableRelationType', 'Relation');
drawSelectField('drawFields', 'Parent', 'Parent_Field_ID');
drawSelectField('drawFields', 'Child', 'Child_Field_ID');
drawTextField('Child Field Subtext','Child_Field_Subtext',FALSE,'text',TRUE,FALSE,0,'size="50"');
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
