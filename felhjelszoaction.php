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
  <script language="javascript"></script>
</head>
<body>
<?php
  include "teteje.php";
  include "bal.php";
  echo '<div id="primaryContent">
      <h2>Felhasználók</h2>';

  $kapott_felhnev = $_POST['kapott_felhnev'];
  $nev = $_POST['nev'];
  $ujjelszo = $_POST['ujjelszo'];
  $jelszomegint = $_POST['jelszomegint'];

  $ok = 1;
  $duma = '';
  if(strlen($ujjelszo)< 6) {$duma .= "Jelszó túl rövid!<BR>"; $ok = 0;}
  if($ujjelszo!=$jelszomegint) {$duma .= "Jelszó nem egyezik!<BR>"; $ok = 0;}
  if ($ok == 1)
  {
    $ujjelszo = md5($ujjelszo);
    $query = "UPDATE felhasznalok SET nev='$nev', jelszo='$ujjelszo' WHERE felhnev='$kapott_felhnev';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $duma = "Sikeres módosítás!<BR>";
  }
  else
  {
    $duma = $duma . "Nem történt módosítás.<BR>";
  }
  $duma = $duma . "<a href=felhasznalok.php>Vissza a felhasználókhoz</a>";
  echo "$duma";

  echo "</div>";
  include "alja.php";
?>
</body>
</html>

