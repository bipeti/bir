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
  include "myconsts.php";

  if ((empty($_POST['felhnev'])) or (empty($_POST['jelszo'])))
  {
    echo "<BODY onload=\"javascript:gotopage('./index.php');return false;\">";
    exit();
  }
  else
  {
    $felhnev = $_POST['felhnev'];
    $jelszo = md5($_POST['jelszo']);

    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error()) ;
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");

    $query = "SELECT * FROM felhasznalok WHERE felhnev='$felhnev' AND jelszo='$jelszo';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
    {
      echo "<BODY onload=\"javascript:gotopage('./index.php');return false;\">";
      exit();
    }
    else
    {
      $nev = mysql_result($result,0,"nev");
      session_register('felhnev');      $_SESSION['felhnev'] = $felhnev;
      session_register('nev');      $_SESSION['nev'] = $nev;
      echo "<BODY onload=\"javascript:gotopage('./index.php');return false;\">";
      exit();
    }
  }
?>

</BODY>
</HTML>