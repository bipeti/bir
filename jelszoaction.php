<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $profiljog = 0;
  if (isset($_SESSION['felhnev']))
  {             // Be vagyunk jelentkezve
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $profiljog = 1;
  }
  if ($profiljog == 0)
  {exit();}
?>
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>H�rek</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript"></script>
</head>
<body>
<?php
  include "teteje.php";
  include "bal.php";
  echo '<div id="primaryContent">
      <h2>H�rek, aktualit�sok</h2>';

  $regijelszo = md5($_POST['regijelszo']);
  $nev = $_POST['nev'];
  $ujjelszo = $_POST['ujjelszo'];
  $jelszomegint = $_POST['jelszomegint'];

  $query = "SELECT * FROM felhasznalok WHERE felhnev='$felhnev' AND jelszo='$regijelszo';";
  $result = mysql_query($query) or die("U:" . mysql_error());
  $num = mysql_numrows($result);
  $ok = 1;
  $duma = '';
  if($num == 0)  {$duma = "Hib�s jelsz�!<BR>";  $ok = 0;}
  if(strlen($ujjelszo)< 6) {$duma .= "Jelsz� t�l r�vid!<BR>"; $ok = 0;}
  if($ujjelszo!=$jelszomegint) {$duma .= "Jelsz� nem egyezik!<BR>"; $ok = 0;}
  if ($ok == 1)
  {
    $ujjelszo = md5($ujjelszo);
    $query = "UPDATE felhasznalok SET nev='$nev', jelszo='$ujjelszo' WHERE felhnev='$felhnev';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $_SESSION['nev'] = $nev;
    $duma = "Sikeres m�dos�t�s!<BR>";
  }
  else
  {
    $duma = $duma . "Nem t�rt�nt m�dos�t�s.<BR>";
  }
  $duma = $duma . "<a href=profil.php>Vissza a profilhoz</a>";
  echo "$duma";

  echo "</div>";
  include "alja.php";
?>
</body>
</html>

