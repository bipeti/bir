<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $profiljog = 0;
  if (isset($_SESSION['felhnev']))
  {             // Be vagyunk jelentkezve
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $profiljog = 1;
  }
  if ($profiljog == 0)
  {exit();}
?>
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>BIR - Profil</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript">
  </script>
</head>
<body>
<?php
  include "teteje.php";
  include "bal.php";

  $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error()) ;
  @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");

  echo '<div id="primaryContent">
      <h2>Profil</h2>';
  $query = "SELECT * FROM felhasznalok WHERE felhnev='$felhnev';";
  $result = mysql_query($query) or die("U:" . mysql_error());
  $nev = mysql_result($result,0,"nev");

  echo "<FORM enctype='multipart/form-data' name='felvitel' id='felvitel' method='post' action='jelszoaction.php'>
        <TABLE width=50% border=0>
        <TR><TD>Felhasználó név: </TD><TD><INPUT TYPE=\"text\" NAME=\"felhnev\" id=\"felhnev\" VALUE=\"$felhnev\" readonly></TD></TR>
        <TR><TD>Név: </TD><TD><INPUT TYPE=\"text\" NAME=\"nev\" id=\"nev\" VALUE=\"$nev\"></TD></TR>
        <TR><TD>A jelenlegi jelszó: </TD><TD><INPUT TYPE=\"password\" NAME=\"regijelszo\" id=\"regijelszo\"></TD></TR>
        <TR><TD>Új jelszó: </TD><TD><INPUT TYPE=\"password\" NAME=\"ujjelszo\" id=\"ujjelszo\"></TD></TR>
        <TR><TD>Jelszó még egyszer: </TD><TD><INPUT TYPE=\"password\" NAME=\"jelszomegint\" id=\"jelszomegint\"></TD></TR>
        </TABLE>
        <INPUT TYPE=\"submit\" VALUE=\"Módosítás\" NAME=\"feltoltgomb\" id=\"feltoltgomb\">
        </FORM>
        </div>";

  include "alja.php";
?>
</body></html>