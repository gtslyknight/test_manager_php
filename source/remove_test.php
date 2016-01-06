<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {
	$InsID = $_GET["InsID"];
	if (($_GET["CRN"] != "")) {$CRN = $_GET["CRN"];}
	else {header("Location: courses.php?InsID=".$InsID."&error=CRN not passed on.");}
	if (($_GET["TestID"] != "")) {$TestID = $_GET["TestID"];}
} 
else { header("Location: login.php");}

if (isset($CRN) && isset($TestID))
{		$crn_exists_query = "SELECT TestID FROM TESTS WHERE CRN='".$CRN."' AND TestID='".$TestID."';";
		$crn_exists_result = mysql_query($crn_exists_query) or die(mysql_error());
		$old_course_info = mysql_fetch_array($crn_exists_result);
		$crn_exists = mysql_num_rows($crn_exists_result);
				
		if ($crn_exists) {
			//insert into db
			$add_query = "DELETE FROM TESTS WHERE CRN='".$CRN."' AND TestID='".$TestID."';";
			echo $add_query;
			$add_result = mysql_query($add_query) or die(mysql_error());
			//header("Location: update_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$next_id);
			header("Location: tests.php?InsID=".$InsID."&CRN=".$CRN);
		}
		else {header("Location: courses.php?InsID=".$InsID."&error=Test does not exist.");}
}
		

?>

<?php mysql_close($con);?>