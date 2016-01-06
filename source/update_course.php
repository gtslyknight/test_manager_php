<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {
	$InsID = $_GET["InsID"];
	if (($_GET["old_CRN"] != "")) {$old_crn = $_GET["old_CRN"];}
	else {header("Location: courses.php?InsID=".$InsID."&error=CRN not passed on.");}
	if (($_GET["CRN"] != "")) {$CRN = $_GET["CRN"];}
	if (($_GET["Title"] != "")) {$Title = $_GET["Title"];}
	if (($_GET["CourseNum"] != "")) {$CourseNum = $_GET["CourseNum"];}
	if (($_GET["Semester"] != "")) {$Semester = $_GET["Semester"];}
	if (($_GET["Year"] != "")) {$Year = $_GET["Year"];}
	if (($_GET["Section"] != "")) {$Section = $_GET["Section"];}
} 
else { header("Location: login.php");}

if (isset($old_crn) && isset($CRN) && isset($Title) && isset($CourseNum) && isset($Semester) && isset($Year) && isset($Section))
{		$crn_exists_query = "SELECT CRN FROM COURSES WHERE CRN='".$CRN."';";
		$crn_exists_result = mysql_query($crn_exists_query) or die(mysql_error());
		$old_course_info = mysql_fetch_array($crn_exists_result);
		$crn_exists = mysql_num_rows($crn_exists_result);
				
		if ($CRN == $old_crn || $crn_exists == 0) {
			//insert into db
			$update_query = "UPDATE COURSES SET CRN='".$CRN."', Title='".$Title."', CourseNum='".$CourseNum."',Year='".$Year."',Semester='".$Semester."',Section='".$Section."' WHERE CRN='".$old_crn."';";
			$update_result = mysql_query($update_query) or die(mysql_error());
			header("Location: courses.php?InsID=" . $InsID);
		}
		else {header("Location: courses.php?InsID=".$InsID."&error=CRN taken by another course.");}

}
		

?>

<?php mysql_close($con);?>