<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $felhjog = 0;
  $szint = '';
  if (isset($_SESSION['felhnev']))
  {             // Csak, ha ve vagyunk jelentkezve!
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Felhasználók';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num>0)
    {
      $szint = mysql_result($result,0,"szint");
      // Csak, ha rendszergazda és joga is van
      if ($szint=='rendszergazda')
      {
        $felhjog = 1;
      }
    }
  }
  if ($felhjog == 0)
  {exit();}
?>
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>BIR - Felhasználók</title>
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
      <h2>Felhasználók</h2>';

  if (isset($_GET['felhnev']))
  {   // Szerkesztésről van szó
    $kapott_felhnev = $_GET['felhnev'];
    $query = "SELECT * FROM felhasznalok WHERE felhnev='$kapott_felhnev';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $nev = mysql_result($result,0,"nev");

    echo "<FORM enctype='multipart/form-data' name='felvitel' id='felvitel' method='post' action='felhjelszoaction.php'>
          <TABLE width=50% border=0>
          <TR><TD>Felhasználó név: </TD><TD><INPUT TYPE=\"text\" NAME=\"kapott_felhnev\" id=\"kapott_felhnev\" VALUE=\"$kapott_felhnev\" readonly></TD></TR>
          <TR><TD>Név: </TD><TD><INPUT TYPE=\"text\" NAME=\"nev\" id=\"nev\" VALUE=\"$nev\"></TD></TR>
          <TR><TD>Új jelszó: </TD><TD><INPUT TYPE=\"password\" NAME=\"ujjelszo\" id=\"ujjelszo\"></TD></TR>
          <TR><TD>Jelszó még egyszer: </TD><TD><INPUT TYPE=\"password\" NAME=\"jelszomegint\" id=\"jelszomegint\"></TD></TR>
          </TABLE>
          <INPUT TYPE=\"submit\" VALUE=\"Módosítás\" NAME=\"feltoltgomb\" id=\"feltoltgomb\">
          </FORM>
          </div>";
  }
  else
  {
    echo "kukucs";
  }

  include "alja.php";
?>
</body></html>