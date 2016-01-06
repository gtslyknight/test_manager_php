<?php require_once('LOCALHOST.php'); ?>
<?php 
if ($_GET["InsID"] !=""){
	$InsID = $_GET["InsID"];
	
	if ($_GET["TestID"] != "" && $_GET["CRN"] != "") {
		$TestID = $_GET["TestID"];
		$CRN = $_GET["CRN"];
	} else {header("Location: courses.php?InsID=".$InsID."&error=Course or test in that course does not exist.");}
} else {header("Location: login.php");}

$result1 = mysql_query(
	"SELECT Name, COUNT(*), SUM(Score) FROM QUESTIONS JOIN TOPICS
	ON QUESTIONS.QTopic=TOPICS.TopicID
	WHERE QuestionID IN 
	(SELECT QuestionID FROM TEST_QUESTIONS WHERE TESTID='".$TestID."' AND CRN='".$CRN."')
	GROUP BY QTopic;");
$has_questions = mysql_num_rows($result1);
$result2 = mysql_query(
	"SELECT SUM(Score) FROM QUESTIONS
	WHERE QuestionID IN 
	(SELECT QuestionID FROM TEST_QUESTIONS WHERE TESTID='".$TestID."' AND CRN='".$CRN."');");

$test_link = "tests.php?InsID=".$InsID."&CRN=".$CRN;
$course_link = "courses.php?InsID=".$InsID;
$instructor_link = "instructor.php?InsID=".$InsID;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Test Report</title>
</head>

<body>

<h1>Test Management System - Test Report</h1>
<h2>CRN: <?php echo $CRN;?> Test: <?php echo $TestID;?></h2>

<?php if ($has_questions) { ?>
<table width="500" border="0" cellspacing="1">
  <tr>
    <th scope="col">Topic</th>
    <th scope="col">Number of Questions</th>
    <th scope="col">Score per Topic</th>
  </tr>
  <?php
  
  while($row = mysql_fetch_array($result1))
	  {
	  echo "<tr>";
	  echo "<td>".$row['Name']."</td><td align='center'>".$row['COUNT(*)']."</td><td align='center'>".$row['SUM(Score)']."</td>";
	  echo "</tr>";
	  }
  $total = mysql_fetch_array($result2);
  echo "<tr>";
  echo "<td></td><td align='right'>Total Score</td><td align='center'>".$total['SUM(Score)']."</td>";
  echo "</tr>";
  ?>
</table>

<?php } else { echo "<p color='red'>This test has no questions yet. Go back and edit test to add questions.</p>"; }?>

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