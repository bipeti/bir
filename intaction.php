<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $helyiintjog = 0;
  if (isset($_SESSION['felhnev']))
  {             // Be vagyunk jelentkezve
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Helyi intézkedések';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
      $helyiintjog = 0;
    else
      $helyiintjog = 1;
  }
  if ($helyiintjog == 0)
  {exit();}
?>
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>Helyi intézkedések</title>
  <script language="javascript"></script>
</head>
<body>
<?php

  if (ISSET($_POST['tevekenyseg']))
  { // ha be van állítva a tevekenyseg
    $tevekenyseg = $_POST['tevekenyseg'];

    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error()) ;
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");

    $intazon=$_POST['intazon'];

    if (ISSET($_POST['ev']))  $ev=$_POST['ev'];

    if (!(empty($_POST['sorszam']))) {$sorszam=$_POST['sorszam'];} else {$sorszam='';}
    if (!(empty($_POST['intcim']))) { $intcim=$_POST['intcim']; } else {$intcim='';}
    if (!(empty($_POST['hatlep']))) {$hatlep=$_POST['hatlep'];} else {$hatlep='';}
    if (!(empty($_POST['hatveszt']))) $hatveszt=$_POST['hatveszt']; else {$hatveszt='';}

    if ($tevekenyseg=='Feltöltés')
    {  // Csak Feltöltés esetén kell átalakítani az intazont
      if ($sorszam<100)
      {
        if ($sorszam<10)
        {   //egyjegyű
          $sorszam = '00' . $sorszam;
        }
        else
        {   //kétjegyű
          $sorszam = '0' . $sorszam;
        }
      }
      $intazonuj = $sorszam . '/' . $ev;
    }
    else
    {
      $intazonuj = $intazon;
    }

    $fajlnev = fajlnevve($intazonuj);
    $fajlnev_html = $fajlnev . ".html";

    if (($tevekenyseg=='Feltöltés') or ($tevekenyseg=='Módosítás'))
    {   // A fájl feltöltése ebben a két esetben ugyanúgy működik
      $htmlnev = basename($_FILES['htmlnev']['name']);
      $pdfnev = basename($_FILES['pdfnev']['name']);
      $sikeresfeltoltes=0;
      $marvanilyen = false;

      if (!(($htmlnev=='') and ($pdfnev=='')))
      {
        if (is_uploaded_file($_FILES['pdfnev']['tmp_name']))
        {
          $kiterjesztes = strrchr($pdfnev,'.');
          // Ha az eredeti kiterjesztés is html, akkor gond lehet a feltöltésnél, hiszen ez is ugyanolyan nevet kap, mint pl. a word.
          // Emiatt a név végére teszünk egy e betűt...
          if ($kiterjesztes=='.html')
          {
            $fajlnev_eredeti = $fajlnev . 'e' . $kiterjesztes;
          }
          else
          {
            $fajlnev_eredeti = $fajlnev . $kiterjesztes;
          }
        }
  
        // Csak a feltöltés előtt itt vizsgáljuk meg, hogy léteznek-e a kijelölt fájlok... (a mell-et módosításnál is)
        if ($tevekenyseg=='Feltöltés')
        {   // A fájl feltöltése ebben a két esetben ugyanúgy működik
  
          if (!$htmlnev=='')
          {
            if (is_uploaded_file($_FILES['htmlnev']['tmp_name']))
            {
              if(file_exists($html_ut . '/' . $fajlnev_html))
              {  // A fájl már létezett, vagy egyéb hiba
                $marvanilyen = true;
                echo "A html fájl már létezett, vagy egyéb hiba. Nem történt $tevekenyseg.<BR>";
              }
              else
              {
                $marvanilyen = false;
              }
            }
          }
          if ($marvanilyen==false)
          {
            if (is_uploaded_file($_FILES['pdfnev']['tmp_name']))
            {
              if(file_exists($html_ut . '/' . $fajlnev_eredeti))
              {
                $marvanilyen = true;
                echo "Az eredeti fájl már létezett, vagy egyéb hiba. Nem történt $tevekenyseg.<BR>";
              }
              else
              {
                $marvanilyen = false;
              }
            }
          }
        }  // if tev = feltöltés vége
      }    //      if !(($html=='') and ($pdfnev=='')) vége


      if ($marvanilyen==false)
      {
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
          echo "Nem történt $tevekenyseg. A következő melléklet(ek valamelyike) már létezett, vagy egyéb hiba:<BR>";
          echo "<b>$melllista</b>";
        }
      }
             // Megvizsgáltuk a fent lévő fájlokat. Ha nincs gond, akkor mehet a feltöltés...

      if ($marvanilyen==false)
      {
        if (!($htmlnev=='') and (move_uploaded_file($_FILES['htmlnev']['tmp_name'],'./' . $html_ut . '/' . $fajlnev_html)));
        {
          $mindenok = true;
        }
        if (!($pdfnev=='') and (move_uploaded_file($_FILES['pdfnev']['tmp_name'],'./' . $html_ut . '/' . $fajlnev_eredeti)));
        {
          $mindenok = true;
        }
        foreach ($_FILES["mell"]["error"] as $key => $error)
        {
          if ($error == UPLOAD_ERR_OK)
          {
            $mellnev = $_FILES["mell"]["name"][$key];
            move_uploaded_file($_FILES["mell"]["tmp_name"][$key], './' . $mell_ut . '/' . $_FILES["mell"]["name"][$key]);
            $query = "INSERT INTO mellekletek (mihez, mihez_azon, mellnev) VALUES ('intézkedés','$intazonuj','$mellnev');";
            $result = mysql_query($query) or die("üzenet: " . mysql_error());
          }
        }
      }
      else
      {  // A fájl már létezett, vagy egyéb hiba
        $mindenok = false;
        echo "<A HREF=intezkedesek.php>Böngészés</A>";
      }
    } // if $tevekenyseg== feltöltés, vagy módosítás vége
    else
    {  // ha törlés, akkor jön ide
      $mindenok=true;
    }

    if ($mindenok == true)
    {                 /* $mindenok akkor true, ha a fájl feltöltések során nem volt gond
                         A törlésnél nem számít a mindenok értéke, de az is itt van, ezért törlésnél true-ra állítjuk.*/
      switch ($tevekenyseg)
      {
        case 'Feltöltés':
        {
          if ($hatveszt=='')
          { // Ha a hatályvesztés nincs kitöltve
            $query = "INSERT INTO intezkedesek (intazon,ev,sorszam,intcim,hatlep,htmlnev,pdfnev)
                        VALUES ('$intazonuj','$ev', '$sorszam', '$intcim', '$hatlep', '$fajlnev_html', '$fajlnev_eredeti');";
          } else
          { // Ha a hatályvesztés nincs kitöltve
            $query = "INSERT INTO intezkedesek (intazon,ev,sorszam,intcim,hatlep,hatveszt,htmlnev,pdfnev)
                        VALUES ('$intazonuj','$ev', '$sorszam', '$intcim', '$hatlep', '$hatveszt', '$fajlnev_html', '$fajlnev_eredeti');";
          }
          $uzenet = "Az új intézkedés rögzítése sikerült.";
          break;
        }  // Feltöltés vége
        case 'Módosítás':
        {
          $query = "UPDATE intezkedesek SET
                      intazon='$intazon',
                      ev='$ev',
                      sorszam='$sorszam',
                      intcim='$intcim',
                      hatlep='$hatlep'";

          if (!($hatveszt==''))
          { // Ha a hatályvesztés ki van töltve
            $query .= ",hatveszt='$hatveszt'";
          }
          if (!($pdfnev==''))
          { // Ha a hatályvesztés ki van töltve
            $query .= ",pdfnev='$fajlnev_eredeti'";
          }
          $query .= "WHERE intazon='$intazon';";
  
          $uzenet = "Az intézkedés módosítása sikerült.";
          break;
        }  // Módosítás vége
  
        case 'Törlés':
        {
          $query = "SELECT pdfnev FROM intezkedesek WHERE intazon='$intazon';";
          $result = mysql_query($query) or die("üzenet: " . mysql_error());
          $fajlnev_eredeti = mysql_result($result,0,"pdfnev");
  
          $query = "DELETE FROM intezkedesek WHERE intazon='$intazon'";
          $uzenet = "A törlés sikerült.";
  
          // Ha a módosítások táblát érinti az intézkedés törlése, akkor ott is törölni kell.
          $queryvizsg2 = "DELETE FROM modositasok WHERE mi='$intazon';";
          $resultvizsg2 = mysql_query($queryvizsg2) or die("üzenet: " . mysql_error());

          $queryvizsg3 = "DELETE FROM modositasok WHERE mit='$intazon';";
          $resultvizsg3 = mysql_query($queryvizsg3) or die("üzenet: " . mysql_error());

          // Mellékletek tábla
          $queryvizsg5 = "SELECT * FROM mellekletek WHERE mihez='intézkedés' AND mihez_azon='$intazon';";
          $resultvizsg5 = mysql_query($queryvizsg5) or die("üzenet: " . mysql_error());
          $numvizsg5 = mysql_numrows($resultvizsg5);

          $j=0;
          while ($j < $numvizsg5)
          { // mellékletek fizikai törlése
            $mellnev = mysql_result($resultvizsg5,$j,"mellnev");
            unlink('./' . $mell_ut . '/' . $mellnev);
            $j++;
          }

          // Tags tábla
          $queryvizsg6 = "DELETE FROM tags WHERE mihez='intézkedés' AND mihez_azon='$intazon';";
          $resultvizsg6 = mysql_query($queryvizsg6) or die("üzenet: " . mysql_error());

          // Végül törölni kell a html-fájlt
          unlink('./' . $html_ut . '/' . $fajlnev_html);
          unlink('./' . $html_ut . '/' . $fajlnev_eredeti);

          $queryvizsg4 = "DELETE FROM mellekletek WHERE mihez='intézkedés' AND mihez_azon='$intazon';";
          $resultvizsg4 = mysql_query($queryvizsg4) or die("üzenet: " . mysql_error());
  
          break;
        }  // Törlés vége
      }
  
      $result = mysql_query($query) or die("üzenet: " . mysql_error());
  
      /* Akár szerkesztésről, akár felvitelről van szó, be kell állítani, hogy miket helyez hatályon kívül és miket módosít.
      Az esetleges módosítás miatt úgy kell beállítani, hogy az eddigi beállításokat törölni kell és az újat kell beírni.
      Ugyanezt meg kell csinálni a kulcsszavakkal kapcsolatban is.*/
  
  
      // Hatályon kívülik törlése...
      $queryhat = "UPDATE intezkedesek SET
                      hathelyez=NULL
                      WHERE hathelyez='$intazon';";
      $resulthat = mysql_query($queryhat) or die("üzenet: " . mysql_error());
  
      // Majd felvittel...
      if (!(empty($_POST['hathelyez'])))
      {
        $hathelyez=$_POST['hathelyez'];
        if ($hathelyez)
        {
          foreach ($hathelyez as $intazon_a)
          {
            $querykilo = "UPDATE intezkedesek SET hathelyez='$intazonuj' WHERE intazon='$intazon_a';";
            $resultkilo = mysql_query($querykilo) or die("üzenet: " . mysql_error());
          }
        }
      }
  
      // Módosítások törlése...
      $querymod = "DELETE FROM modositasok WHERE mi='$intazon'";
      $resultmod = mysql_query($querymod) or die("üzenet: " . mysql_error());
  
      // Majd felvitel...
      if (!(empty($_POST['mitmodosit'])))
      {
        $mitmodosit=$_POST['mitmodosit'];
        if ($mitmodosit)
        {
          foreach ($mitmodosit as $intazon_a)
          {
            $querymod = "INSERT INTO modositasok (mi,mit) VALUES ('$intazonuj','$intazon_a');";
            $resultmod = mysql_query($querymod) or die("üzenet: " . mysql_error());
          }
        }
      }

      // Kulcsszavak törlése...
      $querytag = "DELETE FROM tags WHERE mihez='intézkedés' AND mihez_azon='$intazonuj'";
      $resulttag = mysql_query($querytag) or die("üzenet: " . mysql_error());

      if (!(empty($_POST['kulcsszo'])))
      {
        $kulcsszo = $_POST['kulcsszo'];
        $szavak = explode(", ", $kulcsszo);
        foreach ($szavak as $szo)
        {
          if (!($szo==''))
          {
            $querytag = "INSERT INTO tags (mihez,mihez_azon,kulcsszo)
                          VALUES ('intézkedés','$intazonuj', '$szo');";
            $resulttag = mysql_query($querytag) or die("üzenet: " . mysql_error());
          }
        }
      }
      echo "$uzenet" . "<BR><A HREF=intezkedes.php>Új int.</A><BR><A HREF=intezkedesek.php>Böngészés</A>";
    }  // if mindenok==true vége
  }
    else
  { // nincs beállítva a $tevekenyseg, szóval kívülről szeretnének ide bejutni.
     echo "A tevékenység nem sikerült!<BR><A HREF='intezkedesek.php'>Vissza az intézkedésekhez</A>";
  }

?>
</body>
</html>

