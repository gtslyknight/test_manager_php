<?php require_once('LOCALHOST.php'); ?>
<?php 
if ($_GET["InsID"] !=""){
	$InsID = $_GET["InsID"];
	
	if ($_GET["TestID"] != "" && $_GET["CRN"] != "") {
		$TestID = $_GET["TestID"];
		$CRN = $_GET["CRN"];
	} else {header("Location: courses.php?InsID=".$InsID."&error=Course or test in that course does not exist.");}
} else {header("Location: login.php");}



$mc_result = mysql_query(
"SELECT Q.QText, Q.Score, MCQ.Ans, MCWA.WrongAns
FROM TESTS AS T
JOIN COURSES AS C ON C.CRN='".$CRN."' AND T.CRN='".$CRN."'
JOIN TEST_QUESTIONS AS TQ ON TQ.TestID='".$TestID."' AND T.TestID='".$TestID."'AND TQ.CRN='".$CRN."'
JOIN QUESTIONS AS Q ON Q.QuestionID=TQ.QuestionID
JOIN MC_QUESTIONS AS MCQ ON MCQ.QuestionID=Q.QuestionID
JOIN MC_WRONG_ANS AS MCWA ON MCQ.QuestionID=MCWA.QuestionID;");
$has_mc_questions = mysql_num_rows($mc_result);

//TEST
//$test_array = mysql_fetch_array($mc_result);
//echo $test_array['MC_WRONG_ANS.WrongAns'];

$tf_result = mysql_query(
"SELECT Q.QText, Q.Score, TFQ.Ans
FROM TESTS AS T
JOIN COURSES AS C ON C.CRN='".$CRN."' AND T.CRN='".$CRN."'
JOIN TEST_QUESTIONS AS TQ ON TQ.TestID='".$TestID."' AND T.TestID='".$TestID."'AND TQ.CRN='".$CRN."'
JOIN QUESTIONS AS Q ON Q.QuestionID=TQ.QuestionID
JOIN TF_QUESTIONS AS TFQ ON TFQ.QuestionID=Q.QuestionID;");
$has_tf_questions = mysql_num_rows($tf_result);

$test_title_result = mysql_query("SELECT Title FROM TESTS WHERE TESTID='".$TestID."' AND CRN='".$CRN."';");
$test_title_array = mysql_fetch_array($test_title_result);
$test_title = $test_title_array['Title'];

$test_link = "tests.php?InsID=".$InsID."&CRN=".$CRN;
$course_link = "courses.php?InsID=".$InsID;
$instructor_link = "instructor.php?InsID=".$InsID;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $test_title;?></title>
</head>

<body>

<h1>Printable Test: <?php echo $test_title;?></h1>
<h2>For course CRN: <?php echo $CRN;?> Test: <?php echo $TestID;?></h2>

<h2>Multiple Choice Questions</h2>
<?php if ($has_mc_questions) { ?>
<table width="800" border="0" cellspacing="1">
  <tr>
    <th scope="col">Questions</th>
    <th scope="col" width="40">Score</th>
  </tr>
  <?php
  $last_qtext = "";
  $question_num = 1;
  while($row = mysql_fetch_array($mc_result))
	  {
		  if ($last_qtext != $row['QText']) {
	  		  echo "<tr><td>-</td></tr>";
			  echo "<tr>";
			  echo "<td>".$question_num.". ".$row['QText']."</td><td align='center'>".$row['Score']."</td>";
			  echo "</tr>";
			  echo "<tr>";
			  echo "<td>Correct Answer: ".$row['Ans']."</td>";
			  echo "</tr>";
			  echo "<tr>";
			  echo "<td>Wrong Answer: ".$row['WrongAns']."</td>";
			  echo "</tr>";

			  
			  $last_qtext = $row['QText'];
			  $question_num += 1;
		  }
		  else {
		  	  echo "<tr>";
			  echo "<td>Wrong Answer: ".$row['WrongAns']."</td>";
			  echo "</tr>";
		  }
	  }
?>
</table>
<?php } else { echo "<p>None</p>"; }?>
<h2>True/False Questions</h2>
<?php if ($has_tf_questions) { ?>
<table width="800" border="0" cellspacing="1">
<tr>
    <th scope="col">Questions</th>
    <th scope="col" width="40">Score</th>
  </tr>
<?php
  $question_num = 1;
  while($row = mysql_fetch_array($tf_result))
	  {
	  if ($row['TFQ.Ans']) {$tf_answer = "True";} else {$tf_answer = "False";}
	  echo "<tr>";
	  echo "<td>".$question_num.". ".$row['QText']."</td><td align='center'>".$row['Score']."</td>";
	  echo "</tr>";
	  echo "<tr>";
	  echo "<td>Answer: ".$tf_answer."</td>";
	  echo "</tr>";
	  echo "<tr><td>-</td></tr>";
	  $question_num += 1;
	  }  
?>
</table>

<?php } else { echo "<p>None</p>"; }?>



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