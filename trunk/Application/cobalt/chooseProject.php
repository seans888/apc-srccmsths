<?php
require 'Core/SCV2_Core.php';
init_SCV2();

if($_POST['formKey'] == $_SESSION['formKey'])
{
	extract($_POST);

	if($ChooseProject)
	{
		if($Project != '')
		{
			$_SESSION['Project_ID'] = $Project;
			$_SESSION['Project_Name'] = queryProjectName($Project);
			header("location: main.php");
		}
		else $errMsg = "You need to have a project stored in the repository in order to start working on it. <br>"
					.  "If there are no projects available, please start by creating a new project.";
	}
	
	elseif($CreateProject)
	{
		$errMsg = scriptCheckIfNull('Project Name', $Project_Name, 'Client', $Client_Name, 
									'Description', $Project_Description, 'Base Directory', $Base_Directory);
		if($errMsg=="")
		{
			queryCreateNewProject($_POST);
			header("location: main.php");
		}
	}
}

drawHeader(TRUE,TRUE,FALSE);
drawPageTitle("PROJECT", $errMsg, $msgType);
?>

<div class="container_mid_large">
    <fieldset class="top">
        CHOOSE EXISTING PROJECT
    </fieldset>
    <fieldset class="middle">     
    	<table border="0" width="100%" cellspacing="1">
    	<tr>	
    		<td align=right width=150> Project: </td>
    		<td>
    			<?php drawProjectChooser($Project);?> &nbsp;
    		</td>
    	</table>
    	</tr>
    </fieldset>
    <fieldset class="bottom">
	    <input type=submit value="START" name=ChooseProject>
    </fieldset>
</div>

<div class="container_mid_large">

    <fieldset class="top">
        CREATE A NEW PROJECT
    </fieldset>
    <fieldset class="middle">     
<!--        <legend>Create a New Project</legend> -->

    	<table border="0" width="100%" cellspacing="1">
    	<tr>
    		<td align=right width=150> Project Name: </td>
    		<td><input type=text size=40 maxlength=50 name="Project_Name" value="<?php echo $Project_Name;?>"></td>
    	</tr>
    	<tr>
    		<td align=right> Client: </td>
    		<td><input type=text size=40 maxlength=50 name="Client_Name" value="<?php echo $Client_Name;?>"></td>
    	</tr>
    	<tr>
    		<td align=right> Description: </td>
    		<td><textarea name=Project_Description rows=5 cols=43><?php echo $Project_Description;?></textarea></td>
    	</tr>
    	<tr>
    		<td align=right> Base Directory: </td>
    		<td><input type=text size=40 maxlength=50 name="Base_Directory" value="<?php echo $Base_Directory;?>"></td>
    	</tr>
    	</table>
    </fieldset>
    <fieldset class="bottom">
   	    <input type=submit value="CREATE NEW PROJECT" name=CreateProject>
    </fieldset>
</div>


<?php drawFooter(); ?>
