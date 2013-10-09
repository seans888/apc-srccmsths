<?php
require '../Core/SCV2_Core.php';
init_SCV2();

if(isset($_GET['List_ID']))
{
	$List_ID = $_GET['List_ID'];	
	
	$mysqli = connect_DB();
	$mysqli->real_query("SELECT `List_Name`, `Remarks` 
							FROM `table_fields_predefined_list` 
							WHERE `List_ID`='$List_ID'");
	if($result = $mysqli->use_result())
	{
		$data = $result->fetch_assoc();
		extract($data);
	}
	else die($mysqli->error);
	$mysqli->close();
	
	$mysqli = connect_DB();
	$mysqli->real_query("SELECT `List_Item` 
							FROM `table_fields_predefined_list_items` 
							WHERE `List_ID`='$List_ID' 
							ORDER BY `Number`");
}
elseif($_POST['formKey'] == $_SESSION['formKey'])
{
	if($_POST['Cancel']) header('location: ListView_PredefinedLists.php');
}

drawHeader();
drawPageTitle('Detail View: Predefined Lists',$errMsg);
?>
<div class="container_mid">
<fieldset class="top">
View List: <?php echo $List_Name;?>
</fieldset>

<fieldset class="middle">
<table class="input_form">
<?php
drawTextField('List Name', 'List_Name',TRUE);
drawTextField('Remarks','',TRUE);
echo '<tr><td align=right> <br>List items: </td><td></td></tr>
      <tr><td></td><td><ol>';
if($result = $mysqli->store_result()) while($row = $result->fetch_assoc()) echo '<li>' . $row['List_Item'];
echo '</ol></td></tr>';
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
