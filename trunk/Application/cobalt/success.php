<?php
require 'Core/SCV2_Core.php';
init_SCV2();

if($_POST['formKey'] == $_SESSION['formKey'])
{
	if($_POST['Submit']) header("location: $_POST[location]");
	elseif($_POST['CreateDBConnections']) header("location: /cobalt/Screens/CreateDBConnections.php");
	elseif($_POST['CreatePages']) header("location: /cobalt/Screens/CreatePages.php");
	elseif($_POST['CreatePredefinedLists']) header("location: /cobalt/Screens/CreatePredefinedLists.php");
	elseif($_POST['CreateTables']) header("location: /cobalt/Screens/CreateTables.php");
	elseif($_POST['CreateUsers']) header("location: /cobalt/Screens/CreateUsers.php");
	elseif($_POST['DefineTableFields']) header("location: /cobalt/Screens/DefineTableFields.php");
	elseif($_POST['DefineTableRelations']) header("location: /cobalt/Screens/DefineTableRelations.php");
	elseif($_POST['ImportDBConnection']) 
	{
	    $DB_ID = $_POST['DB_ID'];
	    header("location: /cobalt/Screens/Import_Tables.php?DB_ID=$DB_ID");
	}
	die();
}

if(!isset($_GET['success_tag'])) header("location: " . HOME_PAGE);
else $success_tag = $_GET['success_tag'];

drawHeader();
drawPageTitle('Success');
echo '<div class="container_mid_prompt">';
echo '<fieldset class="success_prompt">';
echo '<table>';
echo '<tr><td width="60"><img src="/cobalt/images/icons/ok.png"></td><td>';
echo '<table>';


if($success_tag=="CreateDBConnections")
{
	echo '<tr><td align=center>Cobalt has successfully added the database connection. </td></tr>'
		.'<tr><td align=left>'
		.'  <input type=submit name=Submit value=CONTINUE class=button1>'
		.'  <input type=submit name=CreateDBConnections value="ADD ANOTHER CONNECTION" class=button1>'
		.'  <input type=submit name=ImportDBConnection value="IMPORT TABLES" class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_DBConnections.php">'
		.'<input type=hidden name=DB_ID value="' . $_GET['DB_ID'] . '">';
}
elseif($success_tag=="CreatePages")
{
	echo '<tr><td align=center>Cobalt has successfully added the page. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'	<input type=submit name=CreatePages value="ADD ANOTHER PAGE" class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_Pages.php">';
}
elseif($success_tag=="CreatePredefinedLists")
{
	echo '<tr><td align=center>Cobalt has successfully added the predefined list. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'	<input type=submit name=CreatePredefinedLists value="ADD ANOTHER LIST" class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_PredefinedLists.php">';
}
elseif($success_tag=="CreateTables")
{
	echo '<tr><td align=center>Cobalt has successfully added the table. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'	<input type=submit name=CreateTables value="ADD ANOTHER TABLE" class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_Tables.php">';
}
elseif($success_tag=="CreateUsers")
{
	echo '<tr><td align=center>Cobalt has successfully added the new user. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'	<input type=submit name=CreateUsers value="ADD ANOTHER USER" class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_Users.php">';
}
elseif($success_tag=="DefineTableFields")
{
	echo '<tr><td align=center>Cobalt has successfully added the field information. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'	<input type=submit name=DefineTableFields value="ADD ANOTHER FIELD" class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_TableFields.php">';
}
elseif($success_tag=="DefineTableRelations")
{
	echo '<tr><td align=center>Cobalt has successfully added the table relation. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'	<input type=submit name=DefineTableRelations value="ADD ANOTHER RELATION" class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_TableRelations.php">';
}
elseif($success_tag=="DeleteDBConnections")
{
	echo '<tr><td align=center>Cobalt has successfully deleted the database connection. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_DBConnections.php">';
}
elseif($success_tag=="DeletePages")
{
	echo '<tr><td align=center>Cobalt has successfully deleted the page. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_Pages.php">';
}
elseif($success_tag=="DeletePredefinedLists")
{
	echo '<tr><td align=center>Cobalt has successfully deleted the predefined list. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_PredefinedLists.php">';
}
elseif($success_tag=="DeleteTables")
{
	echo '<tr><td align=center>Cobalt has successfully deleted the table. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_Tables.php">';
}
elseif($success_tag=="DeleteTableFields")
{
	echo '<tr><td align=center>Cobalt has successfully deleted the table field. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_TableFields.php">';
}
elseif($success_tag=="DeleteTableRelations")
{
	echo '<tr><td align=center>Cobalt has successfully deleted the table relation. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_TableRelations.php">';
}
elseif($success_tag=="DeleteUsers")
{
	echo '<tr><td align=center>Cobalt has successfully deleted the user. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_Users.php">';
}
elseif($success_tag=="EditDBConnections")
{
	echo '<tr><td align=center>Cobalt has successfully updated the database connection. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_DBConnections.php">';
}
elseif($success_tag=="EditPages")
{
	echo '<tr><td align=center>Cobalt has successfully updated the page. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_Pages.php">';
}
elseif($success_tag=="EditPredefinedLists")
{
	echo '<tr><td align=center>Cobalt has successfully updated the predefined list. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_PredefinedLists.php">';
}
elseif($success_tag=="EditProject")
{
	echo '<tr><td align=center>Cobalt has successfully updated the project information. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/main.php">';
}elseif($success_tag=="EditTables")
{
	echo '<tr><td align=center>Cobalt has successfully updated the table. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_Tables.php">';
}
elseif($success_tag=="EditTableFields")
{
	echo '<tr><td align=center>Cobalt has successfully updated the table field. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_TableFields.php">';
}
elseif($success_tag=="EditTableRelations")
{
	echo '<tr><td align=center>Cobalt has successfully updated the table relation. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_TableRelations.php">';
}
elseif($success_tag=="EditUsers")
{
	echo '<tr><td align=center>Cobalt has successfully updated the user. </td></tr>'
		.'<tr><td align=center>'
		.'	<input type=submit name=Submit value=CONTINUE class=button1>'
		.'</td></tr>'
		.'<input type=hidden name=location value="/cobalt/Screens/ListView_Users.php">';
}

echo '</table>';
echo '</table>';
echo '</fieldset>';
echo '</div>';
drawFooter();
?>
