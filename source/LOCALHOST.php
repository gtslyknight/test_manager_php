<?php
$con = mysql_connect("localhost","cs4400_a13","6w5Dzgif");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("cs4400_a13", $con);
?>