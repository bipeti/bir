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

  if (ISSET($_POST['tevekenyseg']))
  { // ha be van állítva a tevekenyseg

    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error()) ;
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");

    $tevekenyseg = $_POST['tevekenyseg'];

    if (!($tevekenyseg=='Törlés'))
    {
      $nev = $_POST['nev'];
      $kapott_felhnev=$_POST['kapott_felhnev'];
  //    $felhnev = fent a session-ből áttöltve
      $helyiintjog = $_POST['helyiintjog'];
      $hirjog = $_POST['hirjog'];
      $felhjog = $_POST['felhjog'];
    }

    switch ($tevekenyseg)
    {
      case 'Feltöltés':
      {
        $query1 = "INSERT INTO felhasznalok (felhnev,nev,jelszo)
                    VALUES ('$kapott_felhnev','$nev', 'XXX');";

        $uzenet = "Az új felhnasználó rögzítése sikerült.";
        break;
      }  // Feltöltés vége
      case 'Módosítás':
      {
        $query1 = "UPDATE felhasznalok SET
                    nev='$nev'
                    WHERE felhnev='$kapott_felhnev';";
        $querytorol = "DELETE FROM jogosultsagok WHERE kinek='$kapott_felhnev';";
        $resulttorol = mysql_query($querytorol) or die("üzenet: " . mysql_error());

        $uzenet = "A felhasználó jogainak módosítása sikerült.";
        break;
      }  // Módosítás vége
      case 'Törlés':
      {
        $query = "DELETE FROM hirek WHERE hirazon='$hirazon';";
        $uzenet = "A hír törlése sikerült.";

        // Mellékletek tábla
        $queryvizsg5 = "SELECT * FROM mellekletek WHERE mihez='hírek' AND mihez_azon='$hirazon';";
        $resultvizsg5 = mysql_query($queryvizsg5) or die("üzenet: " . mysql_error());
        $numvizsg5 = mysql_numrows($resultvizsg5);

        $j=0;
        while ($j < $numvizsg5)
        { // mellékletek fizikai törlése
          $mellnev = mysql_result($resultvizsg5,$j,"mellnev");
          unlink('./' . $mell_ut . '/' . $mellnev);
          $j++;
        }

        $queryvizsg4 = "DELETE FROM mellekletek WHERE mihez='hírek' AND mihez_azon='$hirazon';";
        $resultvizsg4 = mysql_query($queryvizsg4) or die("üzenet: " . mysql_error());
        break;
      }  // Törlés vége
    }

    $result1 = mysql_query($query1) or die("üzenet: " . mysql_error());

    if (!($tevekenyseg=='Törlés'))
    {
      if (!($helyiintjog==''))
        $query2 = "INSERT INTO jogosultsagok (kinek,mire,szint)
                   VALUES ('$kapott_felhnev','Helyi intézkedések', '$helyiintjog');";
      if (!($hirjog==''))
        $query3 = "INSERT INTO jogosultsagok (kinek,mire,szint)
                   VALUES ('$kapott_felhnev','Hírek', '$hirjog');";
      if (!($felhjog==''))
        $query4 = "INSERT INTO jogosultsagok (kinek,mire,szint)
                   VALUES ('$kapott_felhnev','Felhasználók', '$felhjog');";


      if (!($helyiintjog==''))
        $result2 = mysql_query($query2) or die("üzenet: " . mysql_error());
      if (!($hirjog==''))
        $result3 = mysql_query($query3) or die("üzenet: " . mysql_error());
      if (!($felhjog==''))
        $result4 = mysql_query($query4) or die("üzenet: " . mysql_error());
    }

    echo "$uzenet";
  }
    else
  { // nincs beállítva a $tevekenyseg, szóval kívülről szeretnének ide bejutni.
     echo "Nem piszka!";
  }
  echo "</div>";
  include "alja.php";
?>
</body>
</html>

