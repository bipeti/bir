<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $hirjog = 0;
  $szint = '';
  if (isset($_SESSION['felhnev']))
  {             // Csak, ha ve vagyunk jelentkezve �s szerkeszt�sr�l van sz�!
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='H�rek';";
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
    {  // A bel�pett szm�lynek van joga a h�rek-re �s szerkeszt...
      $hirazon = $_GET['hirazon'];
      $query = "SELECT * FROM hirek WHERE hirazon='$hirazon' and felhnev='$felhnev';"; // Az adott h�r felt�lt�je l�pett-e be...
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

  if (ISSET($_POST['tevekenyseg']))
  { // ha be van �ll�tva a tevekenyseg

    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error()) ;
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");

    $tevekenyseg = $_POST['tevekenyseg'];
    $hirazon=$_POST['hirazon'];
    if (!($tevekenyseg=='T�rl�s'))
    {
      $hircim = $_POST['hircim'];
      $kiemelt = $_POST['kiemelt'];
  //    $felhnev = fent a session-b�l �tt�ltve
      $ido = date('Y-m-d H:i:s');
      $meddig = $_POST['meddig'];
      $leiras = $_POST['leiras'];
    }

    $mindenok = true;

    if (($tevekenyseg=='Felt�lt�s') or ($tevekenyseg=='M�dos�t�s'))
    {   // A f�jl felt�lt�se ebben a k�t esetben ugyan�gy m�k�dik
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
        echo "Nem t�rt�nt $tevekenyseg. A k�vetkez� mell�klet(ek valamelyike) m�r l�tezett, vagy egy�b hiba:<BR>";
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
            $query = "INSERT INTO mellekletek (mihez, mihez_azon, mellnev) VALUES ('h�rek','$hirazon','$mellnev');";
            $result = mysql_query($query) or die("�zenet: " . mysql_error());
            $mindenok = true;
          }
        }
      }
      else
      {  // A f�jl m�r l�tezett, vagy egy�b hiba
        echo "<A HREF=hirek.php>Ugr�s a h�rekhez</A>";
      }
    }

    if ($mindenok == true)
    {
      switch ($tevekenyseg)
      {
        case 'Felt�lt�s':
        {
          $query = "INSERT INTO hirek (hirazon,hircim,kiemelt,felhnev,ido,meddig,leiras)
                      VALUES ('$hirazon','$hircim', '$kiemelt', '$felhnev', '$ido', '$meddig', '$leiras');";
          $uzenet = "Az �j h�r r�gz�t�se siker�lt.";
          break;
        }  // Felt�lt�s v�ge
        case 'M�dos�t�s':
        {
          $query = "UPDATE hirek SET
                      hirazon='$hirazon',
                      hircim='$hircim',
                      kiemelt='$kiemelt',
                      meddig='$meddig',
                      leiras='$leiras'
                      WHERE hirazon='$hirazon'";

          $uzenet = "A h�r m�dos�t�sa siker�lt.";
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
    }

    $result = mysql_query($query) or die("�zenet: " . mysql_error());
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

