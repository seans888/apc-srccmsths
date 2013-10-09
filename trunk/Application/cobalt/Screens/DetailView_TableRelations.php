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

	$mysqli = connect_DB();
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("SELECT a.Table_Name, b.Field_Name FROM `table` a, `table_fields` b WHERE a.Table_ID=b.Table_ID AND Field_ID=?"))
	{
		$stmt->bind_param("i", $Field_ID);
		
		$Field_ID = $Parent_Field_ID;
		$stmt->execute();
	    $stmt->bind_result($Parent_Table, $Parent_Field);
	    $stmt->fetch();

		$Field_ID = $Child_Field_ID;
		$stmt->execute();
	    $stmt->bind_result($Child_Table, $Child_Field);
	    $stmt->fetch();

		$Parent = $Parent_Table . '.' . $Parent_Field;
		$Child = $Child_Table . '.' . $Child_Field;

		$stmt->close();
	}
	else die("Umm... " . $stmt->error);
	$mysqli->close();	
}
elseif($_POST['formKey'] == $_SESSION['formKey'])
{
	if($_POST['Cancel']) header('location: ListView_TableRelations.php');
}

drawHeader();
drawPageTitle('Detail View: Table Relation',$errMsg);
?>
<div class="container_mid_huge">
<fieldset class="top">
View Table Relation
</fieldset>

<fieldset class="middle">
<table class="input_form">
<?php
drawTextField('Label','',TRUE);
drawTextField('Relation','',TRUE);
drawTextField('Parent', '',TRUE);
drawTextField('Child', '',TRUE);
drawTextField('Child Field Subtext Fields','Child_Field_Subtext', TRUE);
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
?>
