<?php
/*
 * SCV2_LibDataAccess.php
 * FRIDAY, November 28, 2006 
 * SCV2 library file containing data access (SQL) functions
 * JV Roig
 */

function connect_DB()
{
	//Create DB connection constants.
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASS', '');
	define('DB_USE', 'cobalt');

	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_USE);

	if(mysqli_connect_errno())
	{
		printf("<hr> FATAL ERROR: Cobalt failed to connect to its database: %s <hr>\n" , mysqli_connect_error());
		exit();
	}
	
	return $mysqli;
}

function checkUsernamePassword($username, $password)
{
	$mysqli = connect_DB();		

	$login_Result = array();
	
	// create a prepared statement	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("SELECT Username, Password,Skin FROM user WHERE Username=? AND Password=?"))
	{
		$stmt->bind_param("ss", $username, $password);
		
		//If password is encrypted, do encryption here
		//
		// $password = JVRoigHash200($password); 

		$stmt->execute();
		$stmt->store_result();	

		if($stmt->num_rows == 1)
		{
		    $stmt->bind_result($user, $pass, $skin);
		    $stmt->fetch();
		    $login_Result[] = "Success";
		    $login_Result[] = $user;
		    $login_Result[] = $pass;
		    $login_Result[] = $skin;
		}
		else
		{
			$login_Result[] = "Failed";
		}
	}
	else die($stmt->error);
	
	return $login_Result;
		
}

function queryProjectName($Project_ID)
{
	$db_handle = connect_DB();
	$db_handle->real_query("SELECT Project_Name FROM project WHERE Project_ID='$Project_ID'");

    if ($result = $db_handle->use_result())
    {
        $row = $result->fetch_assoc();
        $result->close();
    }
    
    return $row['Project_Name'];
}

function queryCreateNewProject($param)
{
	$mysqli = connect_DB();

	extract($param);
			
	//get new Project_ID
	$result = $mysqli->query("SELECT MAX(Project_ID) AS `Max_ID` FROM project") or die($mysqli->error);
	$data = $result->fetch_assoc();
	$result->close();
	$Project_ID = $data['Max_ID'];
	$Project_ID++;

	// create a prepared statement	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO project(`Project_ID`, `Project_Name`, Client_Name, Project_Description, Base_Directory) VALUES (?,?,?,?,?)"))
	{
		$stmt->bind_param("issss", $Project_ID, $Project_Name, $Client_Name, $Project_Description, $Base_Directory);
		$stmt->execute();
		$stmt->close();
		
		$_SESSION['Project_Name'] = $Project_Name;
		$_SESSION['Project_ID'] = $Project_ID;
	}
	else die($stmt->error);
}

function queryCreateDBConnection($param)
{
	$mysqli = connect_DB();

	extract($param);

	//get new Database_Connection_ID
	$result = $mysqli->query("SELECT MAX(DB_Connection_ID) AS `Max_ID` FROM database_connection") or die($mysqli->error);
	$data = $result->fetch_assoc();
	$result->close();
	$DB_ID = $data['Max_ID'];
	$DB_ID++;
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO database_connection(`Project_ID`, `DB_Connection_ID`, `DB_Connection_Name`, `Hostname`, `Username`, `Password`, `Database`) VALUES(?,?,?,?,?,?,?)"))
	{
		$stmt->bind_param("iisssss", $Project_ID, $DB_ID, $DB_Connection_Name, $Hostname, $Username, $Password, $Database);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
	
	if($Default_Connection == 'Yes')
	{
		$stmt = $mysqli->stmt_init();
		if($stmt->prepare("UPDATE `project` SET Database_Connection_ID=? WHERE Project_ID=?"))
		{
			$stmt->bind_param("ii", $DB_ID, $_SESSION['Project_ID']);
			$stmt->execute();
			$stmt->close();
		}
		else die($stmt->error);
	}

	return $DB_ID;
}

function queryCreatePage($param)
{
	$mysqli = connect_DB();

	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO page(`Page_Name`, `Generator`, `Description`) VALUES(?,?,?)"))
	{
		$stmt->bind_param("sss", $Page_Name, $Generator, $Description);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
}

function queryCreatePredefinedList($param)
{
	$mysqli = connect_DB();

	extract($param);

	//get new List_ID
	$result = $mysqli->query("SELECT MAX(List_ID) AS `Max_ID` FROM table_fields_predefined_list") or die($mysqli->error);
	$data = $result->fetch_assoc();
	$result->close();
	$List_ID = $data['Max_ID'];
	$List_ID++;

	$numItems = count($List_Item);	

	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO table_fields_predefined_list(`List_ID`, `List_Name`, `Remarks`) VALUES(?,?,?)"))
	{
		$stmt->bind_param("iss", $List_ID, $List_Name, $Remarks);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO table_fields_predefined_list_items(`List_ID`, `Number`, `List_Item`) VALUES(?,?,?)"))
	{
		$stmt->bind_param("iis", $List_ID, $Number, $New_Item);
		for($a=0;$a<$numItems;$a++)
		{
		    $Number = $a+1;
			$New_Item = $List_Item[$a];
			$stmt->execute();
		}
		$stmt->close();
	}
	else die($stmt->error);	
}

function queryCreateTable($param)
{
	$mysqli = connect_DB();
	
	extract($param);

	//Get new Table_ID
	$result = $mysqli->query("SELECT MAX(Table_ID) AS `Max_ID` FROM `table`");
	$data = $result->fetch_assoc();
	$result->close();
	$Table_ID = $data['Max_ID'];
	$Table_ID++;

	$numPages = count($Page_ID);	
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO `table` (`Table_ID`, `Project_ID`, `DB_Connection_ID`, `Table_Name`, `Remarks`) VALUES(?,?,?,?,?)"))
	{
		$stmt->bind_param("iiiss", $Table_ID, $Project_ID, $DB_Connection_ID, $Table_Name, $Remarks);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO table_pages(`Table_ID`, `Page_ID`, `Path_Filename`) VALUES(?,?,?)"))
	{
		$stmt->bind_param("iis", $Table_ID, $New_Page, $New_Path_Filename);
		
		for($a=0; $a<$numPages; $a++)
		{
			$New_Page = $Page_ID[$a];
			$New_Path_Filename = $Path_Filename[$a];
			$stmt->execute();		
		}

		$stmt->close();
	}
	else die($stmt->error);	
}

function queryCreateUser($param)
{
	$mysqli = connect_DB();

	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO `user`(`Username`, `Password`) VALUES(?,?)"))
	{
		$stmt->bind_param("ss", $Username, $Password);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
}

function queryDefineTableField()
{
	$mysqli = connect_DB();
	
	extract($_POST);

	//Get new Field_ID
	$result = $mysqli->query("SELECT MAX(Field_ID) AS `Max_ID` FROM `table_fields`");
	$data = $result->fetch_assoc();
	$result->close();
	$Field_ID = $data['Max_ID'];
	$Field_ID++;

	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO table_fields(Field_ID, Table_ID, Field_Name, Data_Type, Length, Attribute, Control_Type, Label, In_Listview) VALUES(?,?,?,?,?,?,?,?,?)"))
	{
		$stmt->bind_param("iississss", $Field_ID, $Table_ID, $Field_Name, $Data_Type, $Length, $Attribute, $Control_Type, $Label, $In_Listview);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);	

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO table_fields_secondary_validation(Field_ID, Validation_Routine) VALUES(?,?)"))
	{
		$stmt->bind_param("is", $Field_ID, $New_Validation_Routine);
		for($a=0;$a<$particularsCount;$a++)
		{
			if($particularsCount > 1 && trim($Validation_Routine[0])!= "") 
			{
				$New_Validation_Routine = $Validation_Routine[$a];
				$stmt->execute();
			}
		}
		$stmt->close();
	}
	else die($stmt->error);

	if($Control_Type != "textbox" || $Control_Type != "none")
	{
		$stmt = $mysqli->stmt_init();	
		if($Control_Type=="special textbox")
		{
			if($stmt->prepare("INSERT INTO table_fields_book_list(Field_ID, Book_List_Generator) VALUES(?,?)"))
			{
				$stmt->bind_param("is", $Field_ID, $Book_List_Generator);
				$stmt->execute();
			}
			else die($stmt->error);	
		}
		elseif($Control_Type=="radio buttons")
		{
			if($stmt->prepare("INSERT INTO table_fields_list(Field_ID, List_ID) VALUES (?,?)"))
			{
				$stmt->bind_param("ii", $Field_ID, $List_ID);
				$stmt->execute();
			}
			else die($stmt->error);	
		}
		elseif($Control_Type=="drop-down list")
		{
			if($DropdownType=="predefined")
			{
				if($stmt->prepare("INSERT INTO table_fields_list(Field_ID, List_ID) VALUES (?,?)"))
				{
					$stmt->bind_param("ii", $Field_ID, $List_ID);
					$stmt->execute();
				}
				else die($stmt->error);	
			}
			elseif($DropdownType=="source")
			{
				if($stmt->prepare("INSERT INTO table_fields_list_source_select(Field_ID, Select_Field_ID, Display) VALUES(?,?,?)"))
				{
					$stmt->bind_param("iis", $Field_ID, $New_Select_Field_ID, $New_Display);
					
					for($a=0;$a<$selectCount;$a++)
					{
						$New_Select_Field_ID = $Select_Field_ID[$a];
						$New_Display =  $Select_Field_Display[$a];
						$stmt->execute();
					}
				}
				else die($stmt->error);
				
				$stmt = $mysqli->stmt_init();
				if($stmt->prepare("INSERT INTO table_fields_list_source_where(Field_ID, Where_Field_ID, Where_Field_Operand, Where_Field_Value, Where_Field_Connector) VALUES(?,?,?,?,?)"))
				{
					$stmt->bind_param("iisss", $Field_ID, $New_Where_Field_ID, $New_Where_Field_Operand, $New_Where_Field_Value, $New_Where_Field_Connector);
					
					for($a=0;$a<$whereCount;$a++)
					{
					    if($Where_Field_ID[$a] > 0)
					    {
						    $New_Where_Field_ID = $Where_Field_ID[$a];
						    $New_Where_Field_Operand = $Where_Field_Operand[$a];
						    $New_Where_Field_Value = $Where_Field_Value[$a];
						    $New_Where_Field_Connector = $Where_Field_Connector[$a];
                        }
                        else
                        {
						    $New_Where_Field_ID = 0;
						    $New_Where_Field_Operand = '';
						    $New_Where_Field_Value = '';
						    $New_Where_Field_Connector = '';
						}
						$stmt->execute();
					}
				}
				else die($stmt->error);
			}
		}
		$stmt->close();
	}
}

function queryDefineTableRelation($param)
{
	$mysqli = connect_DB();
	
	extract($param);
	
	if(!isset($Child_Field_Subtext)) $Child_Field_Subtext='';

	//Get new Relation_ID
	$result = $mysqli->query("SELECT MAX(Relation_ID) AS `Max_ID` FROM `table_relations`");
	$data = $result->fetch_assoc();
	$result->close();
	$Relation_ID = $data['Max_ID'];
	$Relation_ID++;

	//Create relation label.
	$result = $mysqli->query("SELECT a.Table_Name FROM `table` a, `table_fields` b WHERE a.Table_ID=b.Table_ID AND b.Field_ID='$Parent_Field_ID'");
	$data = $result->fetch_assoc();
	$result->close();
	$Label = $data['Table_Name'] . "=>";

	$result = $mysqli->query("SELECT a.Table_Name FROM `table` a, `table_fields` b WHERE a.Table_ID=b.Table_ID AND b.Field_ID='$Child_Field_ID'");
	$data = $result->fetch_assoc();
	$result->close();
	$Label .= $data['Table_Name'];

	$stmt = $mysqli->stmt_init();
	
	if($stmt->prepare("INSERT INTO table_relations(Relation_ID, Project_ID, Relation, 
												   Parent_Field_ID, Child_Field_ID, Label, Child_Field_Subtext) VALUES(?,?,?,?,?,?,?)"))
	{
		$stmt->bind_param("iisiiss", $Relation_ID, $Project_ID, $Relation, 
									 $Parent_Field_ID, $Child_Field_ID, $Label, $Child_Field_Subtext);
		
		$Project_ID = $_SESSION['Project_ID'];
		
		$stmt->execute();
		$stmt->close();	
	}
	else die($stmt->error);

    //****************************************************************************************************
	//If this is a ONE-to-ONE relationship, we have to update the child field to reflect the relationship.
    if($Relation == 'ONE-to-ONE')
    {
    	$mysqli = connect_DB();
	    //Attribute should be foreign key, or primary&foreign key if previously a primary key or primary&foreign key
	    $mysqli->query("UPDATE table_fields SET Attribute='foreign key' WHERE Field_ID='$Child_Field_ID' AND (Attribute != 'primary key' AND Attribute != 'primary&foreign key')");
	    $mysqli->query("UPDATE table_fields SET Attribute='primary&foreign key' WHERE Field_ID='$Child_Field_ID' AND (Attribute='primary key' OR Attribute='primary&foreign key')");

	    //Control type should be "Drop-down List".
	    $mysqli->query("UPDATE table_fields SET Control_Type='drop-down list' WHERE Field_ID='$Child_Field_ID'");

	    //Make sure to delete any existing records in the predefined list table (`table_fields_list`) so that
	    //we're sure the list source is not a predefined list, and then also clear the "select" and "where" clause
	    //tables to make sure we start from scratch later (`table_fields_list_source_select` and `table_fields_list_source_where`).
	    $mysqli->query("DELETE FROM table_fields_list WHERE Field_ID='$Child_Field_ID'");
	    $mysqli->query("DELETE FROM table_fields_list_source_select WHERE Field_ID='$Child_Field_ID'");
	    $mysqli->query("DELETE FROM table_fields_list_source_where WHERE Field_ID='$Child_Field_ID'");

	    //Label: If it ends with " id" or " code", just trim that part
	    //(The rationale for this is: the child field label may be "employee id" or "item code" (or whatever), but what
	    //we are actually displaying now courtesy of the 1-1 relationship is actually the employee name or item name,
	    //so we just do the developer a service by making the label "employee" or "item" instead of "employee id" or
	    //"item code")
	    $result = $mysqli->query("SELECT `Label` FROM table_fields WHERE Field_ID='$Child_Field_ID'");
	    $data = $result->fetch_row();
	    $label = $data[0];
	    if(strtoupper(substr($label,-3, 3)) == ' ID') $label = substr($label, 0, strlen($label) -3);
	    elseif(strtoupper(substr($label,-5, 5)) == ' CODE') $label = substr($label, 0, strlen($label) -5);

	    if($label != $data[0])
	        $mysqli->query("UPDATE table_fields SET `Label`='$label' WHERE Field_ID='$Child_Field_ID'");
	    
	    //List Source Select: the link field (of the parent) serves as NO, while all the child field subtext entries 
	    //(which are also parent fields) are all YES (we're talking about Display)
	    //Preliminary step: Before we can get the Field_ID of the child fields, we need the table ID (of the parent, not the child)
	    //so we can match the child field subtext entries with the field names in the correct table
		$result = $mysqli->query("SELECT `Table_ID` FROM `table_fields` WHERE Field_ID='$Parent_Field_ID'");
		$data = $result->fetch_row();
		$Table_ID = $data[0];

       	$stmt = $mysqli->stmt_init();
        $stmt->prepare("INSERT INTO table_fields_list_source_select(Field_ID, Select_Field_ID, Display) VALUES(?,?,?)");
		$stmt->bind_param("iis", $Field_ID, $Select_Field_ID, $Display);
	    $Field_ID = $Child_Field_ID;
	    $Select_Field_ID = $Parent_Field_ID;
	    $Display = 'No';
		$stmt->execute();
		
		$Subtext = explode(',', $Child_Field_Subtext);
		foreach($Subtext as $child_field)
		{
            $cntr++;
		    //FIX IN THE FUTURE: Because the number of rows retreived isn't checked (should always be 1), this part
		    //may fail if the child field subtext the dev typed is non-existent.
		    //Of course, a real fix for this issue may be to make the child field subtext input more advanced yet
		    //user-friendly, instead of just a simple textbox!
		    $child_field = trim($child_field);
		    $datacon = connect_DB();
		    $result = $datacon->query("SELECT `Field_ID` FROM `table_fields` WHERE `Table_ID`='$Table_ID' AND Field_Name='$child_field'");
		    $data = $result->fetch_row();
		    $result->close();
		    $datacon->close();
		    $Subtext_Field_ID = $data[0];
		    
	        $Field_ID = $Child_Field_ID;
	        $Select_Field_ID = $Subtext_Field_ID;
	        $Display = 'Yes';
		    $stmt->execute();
	    }
		$stmt->close();	
		
	    //List Source Where: none (0 for where_field_ID, blanks for all other fields)
	    $mysqli->query("INSERT INTO table_fields_list_source_where(Field_ID, Where_Field_ID) VALUES ('$Child_Field_ID','0')");
	    $mysqli->close();
	}

	
    //****************************************************************************************************
	//If this is a ONE-to-MANY relationship, we have to update the child field to reflect the relationship.
    if($Relation == 'ONE-to-MANY')
    {
    	$mysqli = connect_DB();
	    //Attribute should be foreign key, or primary&foreign key if previously a primary key
	    $mysqli->query("UPDATE table_fields SET Attribute='foreign key' WHERE Field_ID='$Child_Field_ID' AND Attribute != 'primary key'");
	    $mysqli->query("UPDATE table_fields SET Attribute='primary&foreign key' WHERE Field_ID='$Child_Field_ID' AND Attribute='primary key'");

	    //Control type should be "none".
	    $mysqli->query("UPDATE table_fields SET Control_Type='none' WHERE Field_ID='$Child_Field_ID'");
	    $mysqli->close();        
    }
}

function queryDeleteDBConnection($param)
{
	$mysqli = connect_DB();

	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM database_connection WHERE DB_Connection_ID=?"))
	{
		$stmt->bind_param("i", $DB_Connection_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
	$stmt->close();

    //Check if this connection is the chosen default connection. If so, reset the Database_Connection_ID field in Project table to 0.
    $mysqli->real_query("SELECT Database_Connection_ID FROM project WHERE Project_ID='$_SESSION[Project_ID]' AND Database_Connection_ID='$DB_Connection_ID'");
    if($result = $mysqli->use_result())
    {
        while($row = $result->fetch_assoc())
        {
            //whistle a happy tune with your hands in your pockets, nothing to do here
        }
        $num_rows = $result->num_rows;
        $result->close();
    }

    if($num_rows == 1)
    {
        //Deleted connection was indeed the default connection of the project
        $mysqli->real_query("UPDATE project SET Database_Connection_ID='0' WHERE Project_ID='$_SESSION[Project_ID]'");
    }

    $mysqli->close();

}

function queryDeletePage($param)
{
	$mysqli = connect_DB();

	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM page WHERE Page_ID=?"))
	{
		$stmt->bind_param("i", $Page_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
}

function queryDeletePredefinedList($param)
{
	$mysqli = connect_DB();

	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM table_fields_predefined_list WHERE List_ID=?"))
	{
		$stmt->bind_param("i", $List_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM table_fields_predefined_list_items WHERE List_ID=?"))
	{
		$stmt->bind_param("i", $List_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
}

function queryDeleteTable($param, $mysqli='')
{
    if($mysqli==='')
    {
	    $mysqli = connect_DB();
    }
    
	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `table` WHERE Table_ID=?"))
	{
		$stmt->bind_param("i", $Table_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM table_pages WHERE Table_ID=?"))
	{
		$stmt->bind_param("i", $Table_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
	
	$mysqli->real_query("SELECT Field_ID 
							FROM `table_fields` 
							WHERE Table_ID='$Table_ID'");
	if($result = $mysqli->use_result())
	{
	    $mysqli2 = connect_DB();
		while($data = $result->fetch_assoc())
		{
			queryDeleteTableField($data, $mysqli2);
		}
	}
	else die($mysqli->error);
	$result->close();
}

function queryDeleteTableRelation($param)
{
    $mysqli = connect_DB();

    extract($param);

    //If 1-1, undo changes in the chield table field

    $mysqli->real_query("SELECT Child_Field_ID 
                            FROM `table_relations` 
                            WHERE Relation_ID='$Relation_ID'");
    if($result = $mysqli->use_result())
    {
        while($data = $result->fetch_assoc())
        {
            $Child_Field_ID = $data['Child_Field_ID'];
        }
    }

    //See what the attribute value is.
    //- if "primary&foregin key", change back to "primary".
    //- if "foreign key", change back to "required".
    $mysqli->real_query("SELECT Attribute FROM table_fields WHERE Field_ID='$Child_Field_ID'");
    if($result = $mysqli->use_result())
    {
        while($data = $result->fetch_assoc())
        {
            $Child_Field_Attribute = $data['Attribute'];
        }
    }

    if($Child_Field_Attribute == 'primary&foreign key')
    {
        $new_attribute = 'primary key';
    }
    else
    {
        $new_attribute = 'required';
    }

    $stmt = $mysqli->stmt_init();
    if($stmt->prepare("UPDATE table_fields SET Attribute=? WHERE Field_ID=?"))
    {
        $stmt->bind_param("si", $new_attribute, $Child_Field_ID);
        $stmt->execute();
        $stmt->close();
    }
    else die($stmt->error);



    $stmt = $mysqli->stmt_init();
    if($stmt->prepare("DELETE FROM `table_relations` WHERE Relation_ID=?"))
    {
        $stmt->bind_param("i", $Relation_ID);
        $stmt->execute();
        $stmt->close();
    }
    else die($stmt->error);
    
    
    
}

function queryDeleteTableField($param, $mysqli='')
{
    if($mysqli==='')
    {
	    $mysqli = connect_DB();
    }

	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `table_fields` WHERE Field_ID=?"))
	{
		$stmt->bind_param("i", $Field_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `table_fields_list` WHERE Field_ID=?"))
	{
		$stmt->bind_param("i", $Field_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `table_fields_book_list` WHERE Field_ID=?"))
	{
		$stmt->bind_param("i", $Field_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

/*  Obsolete code. This table does not exist anymore.
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `table_fields_list_source_link` WHERE Field_ID=?"))
	{
		$stmt->bind_param("i", $Field_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
*/
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `table_fields_list_source_select` WHERE Field_ID=?"))
	{
		$stmt->bind_param("i", $Field_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `table_fields_list_source_where` WHERE Field_ID=?"))
	{
		$stmt->bind_param("i", $Field_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `table_fields_secondary_validation` WHERE Field_ID=?"))
	{
		$stmt->bind_param("i", $Field_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `table_relations` WHERE Parent_Field_ID=? OR Child_Field_ID=? OR Parent2_Field_ID=?"))
	{
		$stmt->bind_param("iii", $Field_ID, $Field_ID, $Field_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);		
}

function queryDeleteProject($param, $mysqli)
{
	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `project` WHERE Project_ID=?"))
	{
		$stmt->bind_param("i", $Project_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);


	//HERE'S MY FAVORITE PART: CASCADING DESTRUCTION!!!!! BWAHAHAHA
	//Delete all tables and fields, including all stuff related to those tables and fields
	//(table relations, field list sources, etc)
	$mysqli->real_query("SELECT `Table_ID` 
							FROM `table` 
							WHERE `Project_ID`='$Project_ID'");
	if($result = $mysqli->use_result())
	{
	    $mysqli2 = connect_DB();
		while($data = $result->fetch_assoc()) queryDeleteTable($data, $mysqli2);
	}
	else die($mysqli->error);
    $result->close();
	
	//Delete all databaes connections as well...
    $stmt = $mysqli->stmt_init();
    if($stmt->prepare("DELETE FROM database_connection WHERE Project_ID=?"))
    {
        $stmt->bind_param("i", $Project_ID);
        $stmt->execute();
        $stmt->close();
    }
    else die($stmt->error);

	unset($_SESSION['Project_ID']);
	unset($_SESSION['Project_Name']);	
}

function queryDeleteUser($param)
{
	$mysqli = connect_DB();

	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `user` WHERE `Username`=?"))
	{
		$stmt->bind_param("s", $Username);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
}

function queryUpdateDBConnection($param)
{
	$mysqli = connect_DB();

	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("UPDATE database_connection 
						SET `DB_Connection_Name` = ?, 
						    `Hostname` = ?, 
						    `Username` = ?, 
						    `Password` = ?, 
						    `Database` = ? 
						WHERE DB_Connection_ID=?"))
	{
		$stmt->bind_param("sssssi", $DB_Connection_Name, $Hostname, $Username, $Password, $Database, $Orig_DB_Connection_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	if($Default_Connection == 'Yes')
	{
		$stmt = $mysqli->stmt_init();
		if($stmt->prepare("UPDATE `project` SET Database_Connection_ID = ? WHERE Project_ID = ?"))
		{
			$stmt->bind_param("ii", $Orig_DB_Connection_ID, $_SESSION['Project_ID']);
			$stmt->execute();
			$stmt->close();
		}
		else die($stmt->error);
	}
}

function queryUpdatePage($param)
{
	$mysqli = connect_DB();

	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("UPDATE page 
						SET `Page_Name` = ?, 
						    `Generator` = ?, 
						    `Description` = ? 
						WHERE Page_ID=?"))
	{
		$stmt->bind_param("sssi", $Page_Name, $Generator, $Description, $Orig_Page_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
}

function queryUpdatePredefinedList($param)
{
	$mysqli = connect_DB();

	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("UPDATE table_fields_predefined_list 
						SET `List_Name` = ?, 
						    `Remarks` = ?
						WHERE List_ID=?"))
	{
		$stmt->bind_param("ssi", $List_Name, $Remarks, $Orig_List_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM table_fields_predefined_list_items WHERE List_ID=?"))
	{
		$stmt->bind_param("i", $Orig_List_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
	
	$mysqli->query("OPTIMIZE TABLE `table_fields_predefined_list_items` ");	
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO table_fields_predefined_list_items(`List_ID`, `Number`, `List_Item`) VALUES(?,?,?)"))
	{
		$stmt->bind_param("iis", $Orig_List_ID, $Number, $New_Item);
		
		for($a=0;$a<$particularsCount;$a++)
		{
		    $Number = $a+1;
			$New_Item = $List_Item[$a];
			$stmt->execute();
		}
		$stmt->close();
	}
	else die($stmt->error);	
}

function queryUpdateProject($param)
{
	$mysqli = connect_DB();
	
	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("UPDATE `project` 
						SET `Project_Name` = ?, 
						    `Client_Name` = ?,
						    `Project_Description` = ?,
						    `Base_Directory` = ?,
						    `Database_Connection_ID` = ? 
						WHERE Project_ID=?"))
	{
		$stmt->bind_param("ssssii", $Project_Name, $Client_Name, $Project_Description, $Base_Directory, $Database_Connection_ID, $Orig_Project_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
	
	$_SESSION['Project_Name'] = $Project_Name;
	
}
function queryUpdateTable($param)
{
	$mysqli = connect_DB();
	
	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("UPDATE `table`
						SET `DB_Connection_ID` = ?,
						    `Table_Name` = ?, 
						    `Remarks` = ?
						WHERE Table_ID=?"))
	{
		$stmt->bind_param("issi", $DB_Connection_ID, $Table_Name, $Remarks, $Table_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `table_pages` WHERE Table_ID=?"))
	{
		$stmt->bind_param("i", $Table_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	$numPages = count($Page_ID);	

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO `table_pages` (Table_ID, Page_ID, Path_Filename) VALUES(?,?,?)"))
	{
		$stmt->bind_param("iis", $Table_ID, $New_Page_ID, $New_Path_Filename);
		
		for($a=0;$a<$numPages;$a++)
		{
			$New_Page_ID = $Page_ID[$a];
			$New_Path_Filename = $Path_Filename[$a];
			$stmt->execute();
		}
		$stmt->close();
	}
	else die($stmt->error);
}

function queryUpdateTableField()
{
    $mysqli = connect_DB();

    extract($_POST);

    $stmt = $mysqli->stmt_init();
    if($stmt->prepare("UPDATE `table_fields`
                        SET `Table_ID` = ?,
                            `Field_Name` = ?, 
                            `Data_Type` = ?,
                            `Length`= ?, 
                            `Attribute`= ?, 
                            `Control_Type`= ?, 
                            `Label`= ?, 
                            `In_Listview`= ? 
                        WHERE Field_ID=?"))
    {
        $stmt->bind_param("ississssi", $Table_ID, $Field_Name, $Data_Type, $Length, $Attribute, $Control_Type, $Label, $In_Listview, $Field_ID);
        $stmt->execute();
        $stmt->close();
    }
    else die($stmt->error);

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("DELETE FROM `table_fields_secondary_validation` WHERE Field_ID=?"))
	{
		$stmt->bind_param("i", $Field_ID);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);

	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("INSERT INTO table_fields_secondary_validation(Field_ID, Validation_Routine) VALUES(?,?)"))
	{
		$stmt->bind_param("is", $Field_ID, $New_Validation_Routine);
		for($a=0;$a<$particularsCount;$a++)
		{
			if($particularsCount > 1 && trim($Validation_Routine[0])!= "") 
			{
				$New_Validation_Routine = $Validation_Routine[$a];
				$stmt->execute();
			}
		}
		$stmt->close();
	}
	else die($stmt->error);

	if($Control_Type != "textbox" && $Control_Type != "textarea" && $Control_Type != "none" && $Control_Type != "date controls")
	{
		if($Control_Type=="special textbox")
		{
			$stmt = $mysqli->stmt_init();
			if($stmt->prepare("DELETE FROM `table_fields_book_list` WHERE Field_ID=?"))
			{
				$stmt->bind_param("i", $Field_ID);
				$stmt->execute();
			}
			else die($stmt->error);

			$stmt = $mysqli->stmt_init();
			if($stmt->prepare("INSERT INTO table_fields_book_list(Field_ID, Book_List_Generator) VALUES(?,?)"))
			{
				$stmt->bind_param("is", $Field_ID, $Book_List_Generator);
				$stmt->execute();
			}
			else die($stmt->error);	
		}
		elseif($Control_Type=="radio buttons")
		{
			$stmt = $mysqli->stmt_init();
			if($stmt->prepare("DELETE FROM `table_fields_list` WHERE Field_ID=?"))
			{
				$stmt->bind_param("i", $Field_ID);
				$stmt->execute();
			}
			else die($stmt->error);

			$stmt = $mysqli->stmt_init();
			if($stmt->prepare("INSERT INTO table_fields_list(Field_ID, List_ID) VALUES (?,?)"))
			{
				$stmt->bind_param("ii", $Field_ID, $List_ID);
				$stmt->execute();
			}
			else die($stmt->error);	
		}
		elseif($Control_Type=="drop-down list")
		{
			if($DropdownType=="Predefined")
			{
    			$stmt = $mysqli->stmt_init();
    			if($stmt->prepare("DELETE FROM `table_fields_list` WHERE Field_ID=?"))
    			{
    				$stmt->bind_param("i", $Field_ID);
    				$stmt->execute();
    			}
    			else die($stmt->error);

    			$stmt = $mysqli->stmt_init();
    			if($stmt->prepare("INSERT INTO table_fields_list(Field_ID, List_ID) VALUES (?,?)"))
    			{
    				$stmt->bind_param("ii", $Field_ID, $List_ID);
    				$stmt->execute();
    			}
    			else die($stmt->error);	
			}
			elseif($DropdownType=="Source")
			{
				$stmt = $mysqli->stmt_init();
				if($stmt->prepare("DELETE FROM `table_fields_list_source_select` WHERE Field_ID=?"))
				{
					$stmt->bind_param("i", $Field_ID);
					$stmt->execute();
				}
				else die($stmt->error);
	
				$stmt = $mysqli->stmt_init();
				if($stmt->prepare("INSERT INTO table_fields_list_source_select(Field_ID, Select_Field_ID, Display) VALUES(?,?,?)"))
				{
					$stmt->bind_param("iis", $Field_ID, $New_Select_Field_ID, $New_Display);
					
					for($a=0;$a<$selectCount;$a++)
					{
						$New_Select_Field_ID = $Select_Field_ID[$a];
						$New_Display =  $Select_Field_Display[$a];
						$stmt->execute();
					}
				}
				else die($stmt->error);
	
				$stmt = $mysqli->stmt_init();
				if($stmt->prepare("DELETE FROM `table_fields_list_source_where` WHERE Field_ID=?"))
				{
					$stmt->bind_param("i", $Field_ID);
					$stmt->execute();
				}
				else die($stmt->error);
				
				$stmt = $mysqli->stmt_init();
				if($stmt->prepare("INSERT INTO table_fields_list_source_where(Field_ID, Where_Field_ID, Where_Field_Operand, Where_Field_Value, Where_Field_Connector) VALUES(?,?,?,?,?)"))
				{
					$stmt->bind_param("iisss", $Field_ID, $New_Where_Field_ID, $New_Where_Field_Operand, $New_Where_Field_Value, $New_Where_Field_Connector);
					
					for($a=0;$a<$whereCount;$a++)
					{
					    if($Where_Field_ID[$a] > 0)
					    {
						    $New_Where_Field_ID = $Where_Field_ID[$a];
						    $New_Where_Field_Operand = $Where_Field_Operand[$a];
						    $New_Where_Field_Value = $Where_Field_Value[$a];
						    $New_Where_Field_Connector = $Where_Field_Connector[$a];
                        }
                        else
                        {
						    $New_Where_Field_ID = 0;
						    $New_Where_Field_Operand = '';
						    $New_Where_Field_Value = '';
						    $New_Where_Field_Connector = '';
						}
						$stmt->execute();
					}
				}
				else die($stmt->error);
			}
		}
		$stmt->close();
	}
}

function queryUpdateTableRelation($param)
{
	$mysqli = connect_DB();
	
	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("UPDATE `table_relations`
						SET `Relation` = ?, 
						    `Parent_Field_ID` = ?, 
						    `Child_Field_ID` = ?, 
						    `Label` = ?,
						    `Child_Field_Subtext` = ? 
						WHERE Relation_ID=?"))
	{
		$stmt->bind_param("siissi", $Relation, $Parent_Field_ID, $Child_Field_ID, $Label, $Child_Field_Subtext, $Relation_ID);
		
		if($Label=="")
		{
			//Create relation label.
			$result = $mysqli->query("SELECT Table_Name FROM `table` WHERE Table_ID='$Parent_Table_ID'");
			$data = $result->fetch_assoc();
			$result->close();
			$Label = $data['Table_Name'] . "=>";

			$result = $mysqli->query("SELECT Table_Name FROM `table` WHERE Table_ID='$Child_Table_ID'");
			$data = $result->fetch_assoc();
			$result->close();
			$Label .= $data['Table_Name'];
		}
		
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
}

function queryUpdateUser($param)
{
	$mysqli = connect_DB();

	extract($param);
	
	$stmt = $mysqli->stmt_init();
	if($stmt->prepare("UPDATE `user` 
						SET `Username` = ?, 
						    `Password` = ? 
						WHERE Username=?"))
	{
		$stmt->bind_param("sss", $Username, $Password, $Orig_Username);
		$stmt->execute();
		$stmt->close();
	}
	else die($stmt->error);
}

?>
