<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {
	$InsID = $_GET["InsID"];
	if (($_GET["CRN"] != "")) {$CRN = $_GET["CRN"];}
	else {header("Location: courses.php?InsID=".$InsID."&error=CRN not passed on.");}} 
else { header("Location: login.php");}

if (isset($CRN))
{		$crn_exists_query = "SELECT CRN FROM COURSES WHERE CRN='".$CRN."';";
		$crn_exists_result = mysql_query($crn_exists_query) or die(mysql_error());
		$old_course_info = mysql_fetch_array($crn_exists_result);
		$crn_exists = mysql_num_rows($crn_exists_result);
				
		if ($crn_exists) {
			//insert into db
			$delete_query = "DELETE FROM COURSES WHERE CRN='".$CRN."';";
			$delete_result = mysql_query($delete_query) or die(mysql_error());
			header("Location: courses.php?InsID=" . $InsID);
		}
		else {header("Location: courses.php?InsID=".$InsID."&error=CRN does not exist.");}

}
		

?>

<?php mysql_close($con);?>