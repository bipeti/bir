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
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-2">
  <title>BIR - Felhasználók</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript">
  </script>
</head>
<body>

<?php
  include "teteje.php";
  include "bal.php";

  echo '<div id="primaryContent">
      <h2>Felhasználók</h2>';

  $query = "SELECT * FROM felhasznalok;";
  $result = mysql_query($query) or die("U:" . mysql_error());
  $num = mysql_numrows($result);

  echo "<TABLE width=100% border=1><TR><TH width=50>Azonosító<TH width=50>Felhasználók kezelése<TH width=50>Helyi intézkedések<TH width=50>Hírek";
  $i = 0;

  while ($i < $num)
  {
    $felhnev = mysql_result($result,$i,"felhnev");
    $queryfelh = "SELECT * FROM jogosultsagok
              WHERE kinek='$felhnev' AND mire='Felhasználók';";
    $resultfelh = mysql_query($queryfelh) or die("U:" . mysql_error());
    $numfelh = mysql_numrows($resultfelh);
    if ($numfelh>0)
      $szintfelh = mysql_result($resultfelh,0,"szint");
    else
      $szintfelh = 'Nincs joga.';

    $queryint = "SELECT * FROM jogosultsagok
              WHERE kinek='$felhnev' AND mire='Helyi intézkedések';";
    $resultint = mysql_query($queryint) or die("U:" . mysql_error());
    $numint = mysql_numrows($resultint);
    if ($numint>0)
      $szintint = mysql_result($resultint,0,"szint");
    else
      $szintint = 'Nincs joga.';

    $queryhirek = "SELECT * FROM jogosultsagok
              WHERE kinek='$felhnev' AND mire='Hírek';";
    $resulthirek = mysql_query($queryhirek) or die("U:" . mysql_error());
    $numhirek = mysql_numrows($resulthirek);
    if ($numhirek>0)
      $szinthirek = mysql_result($resulthirek,0,"szint");
    else
      $szinthirek = 'Nincs joga.';
    $paros = $i % 2;
    if ($paros==0) 
      $sor="rowA";
    else
      $sor="rowB";

    echo "<TR class='$sor'><TD><b>$felhnev</b> 
                  <a href='felhasznalo.php?felhnev=$felhnev'><img src=\"img/edit.png\" alt='Szerkesztés' width=\"16\" height=\"16\" border=\"0\" /></a>
                  <a href='felhtorol.php?felhnev=$felhnev'><img src=\"img/torol.png\" alt=\"Törlés\" width=\"16\" height=\"16\" border=\"0\" /></a>
                  <a href='felhjelszo.php?felhnev=$felhnev'>Jelszó</a>
          <TD>$szintfelh<TD>$szintint<TD>$szinthirek";
    $i++;
  }
  echo "</TABLE>";
  echo "<a href='felhasznalo.php'>Új felhasználó</a>";



  echo "</div>";

  include "alja.php";
?>
</body></html>