<?php require_once('LOCALHOST.php'); ?>
<?php 
if (($_GET["InsID"] != "")){
	$InsID = $_GET["InsID"];
}
else {
	header("Location: login.php");
}

if (($_GET["CRN"] != "")){
	$CRN = $_GET["CRN"];
} else {header("Location: courses.php?InsID=".$InsID);}


if (($_GET["sortby"] != "")){
	$sortby = $_GET["sortby"];
} else { $sortby = "CRN";}

$sortby_TestID = "?InsID=".$InsID."&CRN=".$CRN."&sortby=TestID";
$sortby_Title = "?InsID=".$InsID."&CRN=".$CRN."&sortby=Title";

$tests_query = "SELECT * FROM TESTS	WHERE CRN='".$CRN."' ORDER BY ".$sortby.";";
$tests_result = mysql_query($tests_query) or die(mysql_error());
$tests_found = mysql_num_rows($tests_result);

$update_action_string = "update_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=";
$add_action_string = "add_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=";
$remove_action_string = "remove_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=";
$report_link = "test_report.php?InsID=".$InsID."&CRN=".$CRN."&TestID=";
$print_link = "test_printable.php?InsID=".$InsID."&CRN=".$CRN."&TestID=";



?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Tests</title>
</head>

<body>

<h1>Test Management System - Tests</h1>
<?php if (($_GET['error'] != "") || $error != "") {echo "<p color='red'>".$_GET['error']." ".$error."</p>";} ?>

	<h2>Add a Test</h2>
	<form name="add_form" method="get" action="<?php echo $add_action_string;?>">
	  <p>
		<label>Title
		<input type="text" name="Title">
		</label><br>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Add Test">
		</label>
		<?php if(($InsID != "")) { ?>
			<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
			<input type="hidden" name="CRN" value="<?php echo $CRN; ?>" />
		<?php } ?>
	  </p>
	</form>
	
	<h2>Test List</h2>
	<p>View printable test by clicking on the test title.</p>
	
	<?php 
	if($tests_found) { 
	?>
		<table border="0" cellspacing="2">
		  <tr>
			<th scope="col"><a href="tests.php<?php echo $sortby_CRN;?>">TestID</a></th>
			<th scope="col"><a href="tests.php<?php echo $sortby_Title;?>">Title</a></th>
		  </tr>
		  <?php
		  
		  while($row = mysql_fetch_array($tests_result))
			  { ?>
			  <tr align='center'>
			  	  <td><?php echo $row['TestID'];?></td>
				  <td><a href="<?php echo $print_link.$row['TestID'];?>"><?php echo $row['Title'];?></a></td>
				  <td><a href="<?php echo $report_link.$row['TestID'];?>">View Report</a></td>
				  <td><a href="<?php echo $update_action_string.$row['TestID'];?>">Edit</a></td>
				  <td><a href="<?php echo $remove_action_string.$row['TestID'];?>">Remove</a></td>
			  </tr>
		<?php } ?>
		
		</table>
	<?php } else { echo "<p color='red'>No tests were found.</p>";}?>

<p>
	<a href="courses.php?InsID=<?php echo $InsID;?>">Back to courses</a>
	<br>
	<a href="instructor.php?InsID=<?php echo $InsID;?>">Back to instructor homepage</a>
	<br />
	<a href="login.php">Logout</a>
	
</p>

</body>
</html>

<?php 
mysql_close($con);
mysql_free_result($tests_result);
mysql_free_result($test_result);
?>
