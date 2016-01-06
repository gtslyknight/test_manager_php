<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {
	$InsID = $_GET["InsID"];
	if (($_GET["CRN"] != "")) {$CRN = $_GET["CRN"];}
	else {header("Location: courses.php?InsID=".$InsID."&error=CRN not passed on.");}
	if (($_GET["TestID"] != "")) {$TestID = $_GET["TestID"];}
	else {header("Location: test.php?InsID=".$InsID."&CRN=".$CRN."&error=Test not passed on.");}
	
	if ($_GET["Add_QuestionID"] != "") {$Add_QuestionID = $_GET["Add_QuestionID"];}
	if ($_GET["Remove_QuestionID"] != "") {$Remove_QuestionID = $_GET["Remove_QuestionID"];}
	if (($_GET["Title"] != "")) {$Title = $_GET["Title"];}
} 
else { header("Location: login.php");}

//Get info from db 
$test_query = "SELECT Title FROM TESTS WHERE CRN='".$CRN."' AND TestID='".$TestID."';";
$test_result = mysql_query($test_query) or die(mysql_error());
$test_array = mysql_fetch_array($test_result);

// get test questions
$questions_query = "SELECT QuestionID, QText, Score FROM QUESTIONS WHERE QuestionID IN (SELECT QuestionID FROM TEST_QUESTIONS WHERE CRN='".$CRN."' AND TestID='".$TestID."');";
$questions_result = mysql_query($questions_query) or die(mysql_error());
$questions_found = mysql_num_rows($questions_result);


///Update test title
$next_query = "SELECT max(TestID) FROM TESTS WHERE CRN='".$CRN."';";
$next_result = mysql_query($next_query) or die(mysql_error());
$next_array = mysql_fetch_array($next_result);
if ($next_array['max(TestID)'] == NULL) {
	$next_id = 1;} 
else 
	{$next_id = $next_array['max(TestID)']+1;}

if (isset($CRN) && isset($TestID) && isset($Title))
{		$test_exists_query = "SELECT TestID FROM TESTS WHERE CRN='".$CRN."' AND TestID='".$TestID."';";
		$test_exists_result = mysql_query($test_exists_query) or die(mysql_error());
		$test_course_info = mysql_fetch_array($test_exists_result);
		$test_exists = mysql_num_rows($test_exists_result);
				
		if ($test_exists) {
			//insert into db
			$update_query = "UPDATE TESTS SET Title='".$Title."' WHERE CRN='".$CRN."' AND TestID='".$TestID."';";
			$update_result = mysql_query($update_query) or die(mysql_error());
			header("Location: update_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID);
		}
		else {header("Location: courses.php?InsID=".$InsID."&error=Test does not exist.");}
}

if (isset($CRN) && isset($TestID) && isset($Add_QuestionID))
{		$question_exists_query = "SELECT QuestionID FROM QUESTIONS WHERE QuestionID='".$Add_QuestionID."';";
		$question_exists_result = mysql_query($question_exists_query) or die(mysql_error());
		$question_course_info = mysql_fetch_array($question_exists_result);
		$question_exists = mysql_num_rows($question_exists_result);
		
		$on_test_exists_query = "SELECT QuestionID FROM TEST_QUESTIONS WHERE CRN='".$CRN."' AND TestID='".$TestID."' AND QuestionID='".$Add_QuestionID."';";
		$on_test_exists_result = mysql_query($on_test_exists_query) or die(mysql_error());
		$on_test_course_info = mysql_fetch_array($on_test_exists_result);
		$on_test = mysql_num_rows($on_test_exists_result);
				
		if ($question_exists && $on_test == 0) {
			//insert into db
			$insert_query = "INSERT INTO TEST_QUESTIONS (TestID, CRN, QuestionID) Values ('".$TestID."','".$CRN."','".$Add_QuestionID."');";
			$insert_result = mysql_query($insert_query) or die(mysql_error());
			header("Location: update_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID);
		}
		else {header("Location: courses.php?InsID=".$InsID."&error=Question does not exist, or it is already on this test.");}
}

if (isset($CRN) && isset($TestID) && isset($Remove_QuestionID))
{		
		$on_test_exists_query = "SELECT QuestionID FROM TEST_QUESTIONS WHERE CRN='".$CRN."' AND TestID='".$TestID."' AND QuestionID='".$Remove_QuestionID."';";
		$on_test_exists_result = mysql_query($on_test_exists_query) or die(mysql_error());
		$on_test_course_info = mysql_fetch_array($on_test_exists_result);
		$on_test = mysql_num_rows($on_test_exists_result);
				
		if ($on_test) {
			//insert into db
			$delete_query = "DELETE FROM TEST_QUESTIONS WHERE TestID='".$TestID."' AND CRN='".$CRN."' AND QuestionID='".$Remove_QuestionID."';";
			$delete_result = mysql_query($delete_query) or die(mysql_error());
			header("Location: update_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID);
		}
		else {header("Location: courses.php?InsID=".$InsID."&error=Question not on this test.");}
}

$update_link = "update_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID;
$update_question_url = "update_question.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&QuestionID=";
$remove_question_url = "update_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&Remove_QuestionID=";
$add_question_url = "add_test_question.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID;
$test_link = "tests.php?InsID=".$InsID."&CRN=".$CRN;
$course_link = "courses.php?InsID=".$InsID;
$instructor_link = "instructor.php?InsID=".$InsID;
$search_questions_url = "search_questions.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID;

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Edit Test</title>
</head>

<body>

<h1>Test Management System - Edit Test</h1>
<p>

</p>

<p>Edit the Test Title</p>
<form name="add_form" method="get" action="update_test.php?">
	<p>
	<label>Title
	<input type="text" name="Title" value="<?php echo $test_array['Title']?>">
	</label><br>
	</p>
	<p>
	<label>
	<input type="submit" value="Save Changes">
	</label>
	<?php if(($InsID != "")) { ?>
		<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
		<input type="hidden" name="CRN" value="<?php echo $CRN; ?>" />
		<input type="hidden" name="TestID" value="<?php echo $TestID; ?>" />
	<?php } ?>
	</p>
</form>

<p>Add Question by entering the QustionID or <a href="<?php echo $search_questions_url; ?>">Search for question</a></p>
<form name="add_form" method="get" action="update_test.php?">
	<p>
	<label>QuestionID
	<input type="text" name="Add_QuestionID">
	</label><br>
	</p>
	<p>
	<label>
	<input type="submit" value="Add Question">
	</label>
	<?php if(($InsID != "")) { ?>
		<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
		<input type="hidden" name="CRN" value="<?php echo $CRN; ?>" />
		<input type="hidden" name="TestID" value="<?php echo $TestID; ?>" />
	<?php } ?>
	</p>
</form>

<?php 
	if($questions_found) { 
	?>
		<table border="0" cellspacing="2">
		  <tr>
			<th scope="col"><a href="<?php echo $sortby_QuestionID;?>">QuestionID</a></th>
			<th scope="col">Question Text</th>
			<th scope="col"><a href="<?php echo $sortby_Instructor;?>">Score</a></th>
		  </tr>
		  <?php
		  
		  while($row = mysql_fetch_array($questions_result))
			  { ?>
			  <tr align='center'>
			  <td><?php echo $row['QuestionID'];?></td>
			  <td><?php echo $row['QText'];?></td>
			  <td><?php echo $row['Score'];?></td>
			  <td><a href="<?php echo $update_question_url.$row['QuestionID'];?>">Edit</a></td>
			  <td><a href="<?php echo $remove_question_url.$row['QuestionID'];?>">Remove</a></td>
			  </tr>
		<?php } ?>
		
		</table>
	<?php } else { echo "<p color='red'>No tests were found.</p>";}?>

	<br>
	<a href="<?php echo $test_link;?>">Back to test list</a>
	<br />
	<a href="<?php echo $course_link;?>">Select another course</a>
	<br />
	<a href="<?php echo $instructor_link;?>">Back to instructor homepage</a>
	<br />
	<a href="login.php">Logout</a>

</body>
</html>

<?php mysql_close($con);?>