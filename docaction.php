<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $dokjog = 0;
  if (isset($_SESSION['felhnev']))
  {
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Dokumentumok' and szint='op-telefon';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
      // Nincs joga
      $dokjog = 0;
    else
    { // Van joga
      $dokjog = 1;
    }
  }
?>
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>BIR - Dokumentumok</title>
  <script language="javascript"></script>
</head>
<body>
<?php

  if (ISSET($_POST['mit']))
  { // ha be van �ll�tva a tevekenyseg
    $mit = $_POST['mit'];
    $optel = basename($_FILES['optel']['name']);
    if (!$optel=='')
    {
      if (is_uploaded_file($_FILES['optel']['tmp_name']))
      {
        if(file_exists($dok_ut . '/optelefon.doc'))
        {  // A f�jl m�r l�tezett, vagy egy�b hiba
          unlink($dok_ut . '/optelefon.doc');
        }
        move_uploaded_file($_FILES['optel']['tmp_name'], $dok_ut . '/optelefon.doc');
        echo "Sikeres felvitel.<BR><a href=dokumentumok.php>Vissza a dokumentumokhoz</a>";
      }
    }  //if (!$optel=='')
    else
    {
      echo "<a href=dokumentumok.php>Vissza a dokumentumokhoz</a>";
    }
  }
    else
  { // nincs be�ll�tva a $tevekenyseg, sz�val k�v�lr�l szeretn�nek ide bejutni.
     echo "A tev�kenys�g nem siker�lt!<BR><a href=dokumentumok.php>Vissza a dokumentumokhoz</a>";
  }
?>
</body>
</html>

