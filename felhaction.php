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
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>BIR - Felhaszn�l�k</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript"></script>
</head>
<body>
<?php
  include "teteje.php";
  include "bal.php";
  echo '<div id="primaryContent">
      <h2>Felhaszn�l�k</h2>';

  if (ISSET($_POST['tevekenyseg']))
  { // ha be van �ll�tva a tevekenyseg

    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error()) ;
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");

    $tevekenyseg = $_POST['tevekenyseg'];

    if (!($tevekenyseg=='T�rl�s'))
    {
      $nev = $_POST['nev'];
      $kapott_felhnev=$_POST['kapott_felhnev'];
  //    $felhnev = fent a session-b�l �tt�ltve
      $helyiintjog = $_POST['helyiintjog'];
      $hirjog = $_POST['hirjog'];
      $felhjog = $_POST['felhjog'];
    }

    switch ($tevekenyseg)
    {
      case 'Felt�lt�s':
      {
        $query1 = "INSERT INTO felhasznalok (felhnev,nev,jelszo)
                    VALUES ('$kapott_felhnev','$nev', 'XXX');";

        $uzenet = "Az �j felhnaszn�l� r�gz�t�se siker�lt.";
        break;
      }  // Felt�lt�s v�ge
      case 'M�dos�t�s':
      {
        $query1 = "UPDATE felhasznalok SET
                    nev='$nev'
                    WHERE felhnev='$kapott_felhnev';";
        $querytorol = "DELETE FROM jogosultsagok WHERE kinek='$kapott_felhnev';";
        $resulttorol = mysql_query($querytorol) or die("�zenet: " . mysql_error());

        $uzenet = "A felhaszn�l� jogainak m�dos�t�sa siker�lt.";
        break;
      }  // M�dos�t�s v�ge
      case 'T�rl�s':
      {
        $query = "DELETE FROM hirek WHERE hirazon='$hirazon';";
        $uzenet = "A h�r t�rl�se siker�lt.";

        // Mell�kletek t�bla
        $queryvizsg5 = "SELECT * FROM mellekletek WHERE mihez='h�rek' AND mihez_azon='$hirazon';";
        $resultvizsg5 = mysql_query($queryvizsg5) or die("�zenet: " . mysql_error());
        $numvizsg5 = mysql_numrows($resultvizsg5);

        $j=0;
        while ($j < $numvizsg5)
        { // mell�kletek fizikai t�rl�se
          $mellnev = mysql_result($resultvizsg5,$j,"mellnev");
          unlink('./' . $mell_ut . '/' . $mellnev);
          $j++;
        }

        $queryvizsg4 = "DELETE FROM mellekletek WHERE mihez='h�rek' AND mihez_azon='$hirazon';";
        $resultvizsg4 = mysql_query($queryvizsg4) or die("�zenet: " . mysql_error());
        break;
      }  // T�rl�s v�ge
    }

    $result1 = mysql_query($query1) or die("�zenet: " . mysql_error());

    if (!($tevekenyseg=='T�rl�s'))
    {
      if (!($helyiintjog==''))
        $query2 = "INSERT INTO jogosultsagok (kinek,mire,szint)
                   VALUES ('$kapott_felhnev','Helyi int�zked�sek', '$helyiintjog');";
      if (!($hirjog==''))
        $query3 = "INSERT INTO jogosultsagok (kinek,mire,szint)
                   VALUES ('$kapott_felhnev','H�rek', '$hirjog');";
      if (!($felhjog==''))
        $query4 = "INSERT INTO jogosultsagok (kinek,mire,szint)
                   VALUES ('$kapott_felhnev','Felhaszn�l�k', '$felhjog');";


      if (!($helyiintjog==''))
        $result2 = mysql_query($query2) or die("�zenet: " . mysql_error());
      if (!($hirjog==''))
        $result3 = mysql_query($query3) or die("�zenet: " . mysql_error());
      if (!($felhjog==''))
        $result4 = mysql_query($query4) or die("�zenet: " . mysql_error());
    }

    echo "$uzenet";
  }
    else
  { // nincs be�ll�tva a $tevekenyseg, sz�val k�v�lr�l szeretn�nek ide bejutni.
     echo "Nem piszka!";
  }
  echo "</div>";
  include "alja.php";
?>
</body>
</html>

