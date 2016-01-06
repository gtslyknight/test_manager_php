<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {
	$InsID = $_GET["InsID"];
	if (($_GET["CRN"] != "")) {$CRN = $_GET["CRN"];}
	else {header("Location: courses.php?InsID=".$InsID."&error=CRN not passed on.");}
	if (($_GET["Title"] != "")) {$Title = $_GET["Title"];}
} 
else { header("Location: login.php");}

$next_query = "SELECT max(TestID) FROM TESTS WHERE CRN='".$CRN."';";
$next_result = mysql_query($next_query) or die(mysql_error());
$next_array = mysql_fetch_array($next_result);
if ($next_array['max(TestID)'] == NULL) {
	$next_id = 1;} 
else 
	{$next_id = $next_array['max(TestID)']+1;}

if (isset($CRN) && isset($Title))
{		$crn_exists_query = "SELECT CRN FROM COURSES WHERE CRN='".$CRN."';";
		$crn_exists_result = mysql_query($crn_exists_query) or die(mysql_error());
		$old_course_info = mysql_fetch_array($crn_exists_result);
		$crn_exists = mysql_num_rows($crn_exists_result);
				
		if ($crn_exists) {
			//insert into db
			$add_query = "INSERT INTO TESTS (CRN, Title, TestID) VALUES ('".$CRN."','".$Title."','".$next_id."');";
			echo $add_query;
			$add_result = mysql_query($add_query) or die(mysql_error());
			//header("Location: update_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$next_id);
			header("Location: tests.php?InsID=".$InsID."&CRN=".$CRN);
		}
		else {header("Location: courses.php?InsID=".$InsID."&error=CRN does not exist.");}
}
		

?>

<?php mysql_close($con);?>