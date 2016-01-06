<?php require_once('LOCALHOST.php'); ?>
<?php 
if (($_GET["InsID"] != "")){
	$InsID = $_GET["InsID"];
}
else {
	header("Location: login.php");
}

if (($_GET["sortby"] != "")){
	$sortby = $_GET["sortby"];
} else { $sortby = "CRN";}

$sortby_CRN = "?InsID=".$InsID."&sortby=CRN";
$sortby_CourseNum = "?InsID=".$InsID."&sortby=CourseNum, Section";
$sortby_Title = "?InsID=".$InsID."&sortby=Title, Section";
$sortby_Semester = "?InsID=".$InsID."&sortby=Semester, Title, Section";
$sortby_Year = "?InsID=".$InsID."&sortby=Year, Title, Section";
$sortby_Section = "?InsID=".$InsID."&sortby=Section, Year, Title";


$courses_query = "SELECT * FROM COURSES	ORDER BY ".$sortby.";";
$courses_result = mysql_query($courses_query) or die(mysql_error());
$courses_found = mysql_num_rows($courses_result);

$update_action_string = "update_course.php?InsID=".$InsID;
$add_action_string = "add_course.php?InsID=".$InsID;
$remove_action_string = "remove_course.php?InsID=".$InsID;

$tests_link = "tests.php?InsID=".$InsID."&CRN=";

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Courses</title>
</head>

<body>

<h1>Test Management System - Courses</h1>
<?php if (($_GET['error'] != "") || $error != "") {echo "<p color='red'>".$_GET['error']." ".$error."</p>";} ?>

<?php
if (($_GET['CRN'] != "")) {
	$CRN = $_GET['CRN'];
	$course_query = "SELECT * FROM COURSES WHERE CRN='".$CRN."';";
	$course_result = mysql_query($course_query) or die(mysql_error());
	$course_info_array = mysql_fetch_array($course_result);
	$course_found = mysql_num_rows($course_result);
	$old_CRN = $CRN;
	
	if ($course_found) {

?>
	<h2>Update Course Information</h2>
	<form name="add_form" method="get" action="<?php echo $update_action_string;?>">
	  <p>
		<label>CRN
		<input type="text" name="CRN" size="5" maxlength="5" value="<?php echo $course_info_array['CRN'];?>">
		</label><br>
		<label>Course Title
		<input type="text" name="Title" value="<?php echo $course_info_array['Title'];?>">
		</label><br>
		<label>Course Number
		<input type="text" name="CourseNum" value="<?php echo $course_info_array['CourseNum'];?>">
		</label><br>
		<label>Semester
		<select name="Semester">
			<option value="Fall" <?php if($course_info_array['Semester'] == "Fall"){echo "SELECTED";}?>>Fall</option>
			<option value="Spring" <?php if($course_info_array['Semester'] == "Spring"){echo "SELECTED";}?>>Spring</option>
			<option value="Summer" <?php if($course_info_array['Semester'] == "Summer"){echo "SELECTED";}?>>Summer</option>
		</select>
		</label>
		<label>Year
		<input type="text" name="Year" size="4" maxlength="4" value="<?php echo $course_info_array['Year'];?>">
		</label>
		<label>Section
		<input type="text" name="Section" size="1" maxlength="1" value="<?php echo $course_info_array['Section'];?>">
		</label>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Save Changes">
		</label>
		<?php if(($InsID != "")) { ?>
			<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
			<input type="hidden" name="old_CRN" value="<?php echo $old_CRN; ?>" />
		<?php } ?>
	  </p>
	</form>

	<a href="courses.php?InsID=<?php echo $InsID;?>">Cancel</a>
<?php 
	} else {header("Location: courses.php?InsID=".$InsID);}
} else { ?>

	<h2>Add a Course</h2>
	<form name="add_form" method="get" action="<?php echo $add_action_string;?>">
	  <p>
		<label>CRN
		<input type="text" name="CRN" size="5" maxlength="5">
		</label><br>
		<label>Course Title
		<input type="text" name="Title">
		</label><br>
		<label>Course Number
		<input type="text" name="CourseNum">
		</label><br>
		<label>Semester
		<select name="Semester">
			<option value="Fall">Fall</option>
			<option value="Spring">Spring</option>
			<option value="Summer">Summer</option>
		</select>
		</label>
		<label>Year
		<input type="text" name="Year" size="4" maxlength="4">
		</label>
		<label>Section
		<input type="text" name="Section" size="1" maxlength="1">
		</label>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Add Course">
		</label>
		<?php if(($InsID != "")) { ?>
			<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
		<?php } ?>
	  </p>
	</form>
	
	<h2>Course List</h2>
	<p>Choose a course below by clicking the CRN</p>
	<?php 
	if($courses_found) { 
	?>
		<table border="0" cellspacing="2">
		  <tr>
			<th scope="col"><a href="courses.php<?php echo $sortby_CRN;?>">CRN</a></th>
			<th scope="col"><a href="courses.php<?php echo $sortby_Title;?>">Title</a></th>
			<th scope="col"><a href="courses.php<?php echo $sortby_CourseNum;?>">Course Number</a></th>
			<th scope="col"><a href="courses.php<?php echo $sortby_Year;?>">Year</a></th>
			<th scope="col"><a href="courses.php<?php echo $sortby_Semester;?>">Semester</a></th>
			<th scope="col"><a href="courses.php<?php echo $sortby_Section;?>">Section</a></th>
		  </tr>
		  <?php
		  
		  while($row = mysql_fetch_array($courses_result))
			  { ?>
			  <tr align='center'>
				  <td><a href="<?php echo $tests_link.$row['CRN'];?>"><?php echo $row['CRN'];?></a></td>
				  <td><?php echo $row['Title'];?></td>
				  <td><?php echo $row['CourseNum'];?></td>
				  <td><?php echo $row['Year'];?></td>
				  <td><?php echo $row['Semester'];?></td>
				  <td><?php echo $row['Section'];?></td>
				  <td><a href="courses.php?InsID=<?php echo $InsID;?>&CRN=<?php echo $row['CRN'];?>">Edit</a></td>
				  <td><a href="<?php echo $remove_action_string;?>&CRN=<?php echo $row['CRN'];?>">Remove</a></td>
			  </tr>
		<?php } ?>
		
		</table>
	<?php } else { echo "<p color='red'>No courses were found.</p>";}?>
	
<?php } ?>

<p>
	<a href="instructor.php?InsID=<?php echo $InsID;?>">Back to instructor homepage</a>
	<br />
	<a href="login.php">Logout</a>
	
</p>

</body>
</html>

<?php 
mysql_close($con);
mysql_free_result($courses_result);
mysql_free_result($course_result);
?>
