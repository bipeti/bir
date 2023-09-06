<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="content-type" content="text/html; charset=windows-1250">
<SCRIPT LANGUAGE="JavaScript">
function gotopage(hova) {window.location = hova;}
</SCRIPT>
</HEAD>
<BODY>

<?php
  session_start();

  if (isset($_SESSION['felhnev']))
  {             // Be vagyunk jelentkezve
    session_destroy();
    echo "<BODY onload=\"javascript:gotopage('./index.php');return false;\">";
    exit();
  }
?>
