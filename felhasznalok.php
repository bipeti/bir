<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $felhjog = 0;
  $szint = '';
  if (isset($_SESSION['felhnev']))
  {             // Csak, ha ve vagyunk jelentkezve!
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Felhaszn�l�k';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num>0)
    {
      $szint = mysql_result($result,0,"szint");
      // Csak, ha rendszergazda �s joga is van
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
  <title>BIR - Felhaszn�l�k</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript">
  </script>
</head>
<body>

<?php
  include "teteje.php";
  include "bal.php";

  echo '<div id="primaryContent">
      <h2>Felhaszn�l�k</h2>';

  $query = "SELECT * FROM felhasznalok;";
  $result = mysql_query($query) or die("U:" . mysql_error());
  $num = mysql_numrows($result);

  echo "<TABLE width=100% border=1><TR><TH width=50>Azonos�t�<TH width=50>Felhaszn�l�k kezel�se<TH width=50>Helyi int�zked�sek<TH width=50>H�rek";
  $i = 0;

  while ($i < $num)
  {
    $felhnev = mysql_result($result,$i,"felhnev");
    $queryfelh = "SELECT * FROM jogosultsagok
              WHERE kinek='$felhnev' AND mire='Felhaszn�l�k';";
    $resultfelh = mysql_query($queryfelh) or die("U:" . mysql_error());
    $numfelh = mysql_numrows($resultfelh);
    if ($numfelh>0)
      $szintfelh = mysql_result($resultfelh,0,"szint");
    else
      $szintfelh = 'Nincs joga.';

    $queryint = "SELECT * FROM jogosultsagok
              WHERE kinek='$felhnev' AND mire='Helyi int�zked�sek';";
    $resultint = mysql_query($queryint) or die("U:" . mysql_error());
    $numint = mysql_numrows($resultint);
    if ($numint>0)
      $szintint = mysql_result($resultint,0,"szint");
    else
      $szintint = 'Nincs joga.';

    $queryhirek = "SELECT * FROM jogosultsagok
              WHERE kinek='$felhnev' AND mire='H�rek';";
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
                  <a href='felhasznalo.php?felhnev=$felhnev'><img src=\"img/edit.png\" alt='Szerkeszt�s' width=\"16\" height=\"16\" border=\"0\" /></a>
                  <a href='felhtorol.php?felhnev=$felhnev'><img src=\"img/torol.png\" alt=\"T�rl�s\" width=\"16\" height=\"16\" border=\"0\" /></a>
                  <a href='felhjelszo.php?felhnev=$felhnev'>Jelsz�</a>
          <TD>$szintfelh<TD>$szintint<TD>$szinthirek";
    $i++;
  }
  echo "</TABLE>";
  echo "<a href='felhasznalo.php'>�j felhaszn�l�</a>";



  echo "</div>";

  include "alja.php";
?>
</body></html>