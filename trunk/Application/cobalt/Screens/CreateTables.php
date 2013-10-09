<?php
require '../Core/SCV2_Core.php';
init_SCV2();

if($_POST['formKey'] == $_SESSION['formKey'])
{
	if($_POST['Cancel']) header("location: ListView_Tables.php");

	if($_POST['Submit'] || $_POST['particularButton']) extract($_POST);

	if($_POST['Submit'])
	{
		$errMsg = scriptCheckIfNull('DB Connection', $DB_Connection_ID,
									'Table Name', $Table_Name);
		
		for($a=0;$a<$particularsCount;$a++)
		{
			$b = $a + 1;
			$errMsg .= scriptCheckIfNull("Table page #$b", $Page_ID[$a]);
			$Path_Filename[$a] = $Folder . '/' . basename($Filename[$a]);
		}
									
		if($errMsg=="")
		{
			$select = "SELECT `Table_Name` FROM `table` WHERE `Table_Name`='$Table_Name' AND `Table_Name`!='$Orig_Table_Name' AND Project_ID='$_SESSION[Project_ID]'"; 
			$error = "The table name '$Table_Name' already exists. Please choose a new name. <br>";
			$errMsg = scriptCheckIfUnique($select, $error);

			if($errMsg=="")
			{
    			//Add additional info needed before passing $_POST
				$_POST['Project_ID'] = $_SESSION['Project_ID'];
			    $_POST['Path_Filename'] = $Path_Filename;
	    		queryCreateTable($_POST);
				header("location: ../success.php?success_tag=CreateTables");
			}
		}
	}
}

if($particularsCount <= 0)
{
	$numParticulars=5;
	$Page_ID[0] = 1;
	$Page_ID[1] = 2;
	$Page_ID[2] = 3;
	$Page_ID[3] = 4;
	$Page_ID[4] = 5;
}

drawHeader();
drawPageTitle('Create Table',$errMsg);
?>

<div class="container_mid">
<fieldset class="top">
New Table
</fieldset>

<fieldset class="middle">
<table class="input_form">
<?php
drawSelectField('drawDBConnection', 'DB Connection', 'DB_Connection_ID');
drawTextField('Table Name', 'Table_Name');
drawTextField('Folder / Subdirectory', 'Folder');
drawTextField('Remarks','','','Textarea');

drawMultiFieldStart('Table Pages');
	if($numParticulars<1) $numParticulars=1;
	echo "<table>
		  <tr>
		  	<td>&nbsp;</td>
		  	<td>Page</td>
		  	<td>Filename</td>
		  </tr>";
	for($a=0;$a<$numParticulars;$a++) 						
	{
		echo "<tr><td>" . ($a+1) . "</td><td>";
		drawTablePage($Page_ID[$a], TRUE); echo "&nbsp;&nbsp;";
		echo "</td><td>";
		drawTextField('','Filename', FALSE, '', FALSE, TRUE, $a); echo "&nbsp;&nbsp;";
		echo "</td></tr>";
	}
	echo "</table>";
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
?>
