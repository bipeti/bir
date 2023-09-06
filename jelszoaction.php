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
  <title>Hírek</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript"></script>
</head>
<body>
<?php
  include "teteje.php";
  include "bal.php";
  echo '<div id="primaryContent">
      <h2>Hírek, aktualitások</h2>';

  $regijelszo = md5($_POST['regijelszo']);
  $nev = $_POST['nev'];
  $ujjelszo = $_POST['ujjelszo'];
  $jelszomegint = $_POST['jelszomegint'];

  $query = "SELECT * FROM felhasznalok WHERE felhnev='$felhnev' AND jelszo='$regijelszo';";
  $result = mysql_query($query) or die("U:" . mysql_error());
  $num = mysql_numrows($result);
  $ok = 1;
  $duma = '';
  if($num == 0)  {$duma = "Hibás jelszó!<BR>";  $ok = 0;}
  if(strlen($ujjelszo)< 6) {$duma .= "Jelszó túl rövid!<BR>"; $ok = 0;}
  if($ujjelszo!=$jelszomegint) {$duma .= "Jelszó nem egyezik!<BR>"; $ok = 0;}
  if ($ok == 1)
  {
    $ujjelszo = md5($ujjelszo);
    $query = "UPDATE felhasznalok SET nev='$nev', jelszo='$ujjelszo' WHERE felhnev='$felhnev';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $_SESSION['nev'] = $nev;
    $duma = "Sikeres módosítás!<BR>";
  }
  else
  {
    $duma = $duma . "Nem történt módosítás.<BR>";
  }
  $duma = $duma . "<a href=profil.php>Vissza a profilhoz</a>";
  echo "$duma";

  echo "</div>";
  include "alja.php";
?>
</body>
</html>

