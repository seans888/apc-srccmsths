<?php
require '../Core/SCV2_Core.php';
init_SCV2();

if($_POST['formKey'] == $_SESSION['formKey'])
{
	if($_POST['Cancel']) header("location: ListView_TableRelations.php");

	if($_POST['particularButton'] || $_POST['Submit'])
	{
		extract($_POST);		
	}
	
	if($_POST['Submit'])
	{
		$errMsg = scriptCheckIfNull('Relation', $Relation,
									'Parent', $Parent_Field_ID,
									'Child', $Child_Field_ID);
									
		if($Relation=="ONE-to-ONE") $errMsg .= scriptCheckIfNull('Child Field Subtext', $Child_Field_Subtext);
									
		if($errMsg=="")
		{
			queryDefineTableRelation($_POST);
			header("location: ../success.php?success_tag=DefineTableRelations");
		}
	}
}
drawHeader();
?>
<script language="JavaScript" type="text/JavaScript">
function toggleChildFieldSubtext()
{
    var field = document.getElementById("relation_field");
    if(field.value == "ONE-to-MANY")
        document.getElementById("Child_Field_Subtext").disabled = true;
    else
        document.getElementById("Child_Field_Subtext").disabled = false;
}

</script>
<?php
drawPageTitle('Define Table Relation', $errMsg);
?>
<div class="container_mid_huge">
<fieldset class="top">
New Table Relation
</fieldset>

<fieldset class="middle">
<table class="input_form">
<?php
drawSelectField('drawTableRelationType', 'Relation', 'Relation', TRUE,'id="relation_field" onChange="toggleChildFieldSubtext()"');
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
