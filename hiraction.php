<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $hirjog = 0;
  $szint = '';
  if (isset($_SESSION['felhnev']))
  {             // Csak, ha ve vagyunk jelentkezve és szerkesztésrõl van szó!
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Hírek';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
      $hirjog = 0;
    else
    {
      $szint = mysql_result($result,0,"szint");
      $hirjog = 1;
    }
    if (($hirjog==1) and (isset($_GET['hirazon'])))
    {  // A belépett szmélynek van joga a hírek-re és szerkeszt...
      $hirazon = $_GET['hirazon'];
      $query = "SELECT * FROM hirek WHERE hirazon='$hirazon' and felhnev='$felhnev';"; // Az adott hír feltöltõje lépett-e be...
      $result = mysql_query($query) or die("U:" . mysql_error());
      $num = mysql_numrows($result);
      if ($num==0)
        $hirjog = 0;
      else
      {
        $hirjog = 1;
      }
      if ($szint=='rendszergazda')
      {
        $hirjog = 1;
      }
    }
  }

  if ($hirjog == 0)
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

  if (ISSET($_POST['tevekenyseg']))
  { // ha be van állítva a tevekenyseg

    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error()) ;
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");

    $tevekenyseg = $_POST['tevekenyseg'];
    $hirazon=$_POST['hirazon'];
    if (!($tevekenyseg=='Törlés'))
    {
      $hircim = $_POST['hircim'];
      $kiemelt = $_POST['kiemelt'];
  //    $felhnev = fent a session-bõl áttöltve
      $ido = date('Y-m-d H:i:s');
      $meddig = $_POST['meddig'];
      $leiras = $_POST['leiras'];
    }

    $mindenok = true;

    if (($tevekenyseg=='Feltöltés') or ($tevekenyseg=='Módosítás'))
    {   // A fájl feltöltése ebben a két esetben ugyanúgy mûködik
      $marvanilyen = false;
      $melllista = '';
      foreach ($_FILES["mell"]["error"] as $key => $error)
      {
        if((file_exists($mell_ut . '/' . $_FILES["mell"]["name"][$key])) and $_FILES["mell"]["name"][$key]<>'')
        {
          $marvanilyen = true;
          $melllista .= $_FILES["mell"]["name"][$key] . "<BR>";
        }
      }
      if ($marvanilyen==true)
      {
        echo "Nem történt $tevekenyseg. A következõ melléklet(ek valamelyike) már létezett, vagy egyéb hiba:<BR>";
        echo "<b>$melllista</b>";
        $mindenok = false;
      }

      if ($marvanilyen==false)
      {
        foreach ($_FILES["mell"]["error"] as $key => $error)
        {
          if ($error == UPLOAD_ERR_OK)
          {
            $mellnev = $_FILES["mell"]["name"][$key];
            move_uploaded_file($_FILES["mell"]["tmp_name"][$key], './' . $mell_ut . '/' . $_FILES["mell"]["name"][$key]);
            $query = "INSERT INTO mellekletek (mihez, mihez_azon, mellnev) VALUES ('hírek','$hirazon','$mellnev');";
            $result = mysql_query($query) or die("üzenet: " . mysql_error());
            $mindenok = true;
          }
        }
      }
      else
      {  // A fájl már létezett, vagy egyéb hiba
        echo "<A HREF=hirek.php>Ugrás a hírekhez</A>";
      }
    }

    if ($mindenok == true)
    {
      switch ($tevekenyseg)
      {
        case 'Feltöltés':
        {
          $query = "INSERT INTO hirek (hirazon,hircim,kiemelt,felhnev,ido,meddig,leiras)
                      VALUES ('$hirazon','$hircim', '$kiemelt', '$felhnev', '$ido', '$meddig', '$leiras');";
          $uzenet = "Az új hír rögzítése sikerült.";
          break;
        }  // Feltöltés vége
        case 'Módosítás':
        {
          $query = "UPDATE hirek SET
                      hirazon='$hirazon',
                      hircim='$hircim',
                      kiemelt='$kiemelt',
                      meddig='$meddig',
                      leiras='$leiras'
                      WHERE hirazon='$hirazon'";

          $uzenet = "A hír módosítása sikerült.";
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
    }

    $result = mysql_query($query) or die("üzenet: " . mysql_error());
    echo "$uzenet";
  }
    else
  { // nincs beállítva a $tevekenyseg, szóval kívülrõl szeretnének ide bejutni.
     echo "Nem piszka!";
  }
  echo "</div>";
  include "alja.php";
?>
</body>
</html>

