<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $helyiintjog = 0;
  if (isset($_SESSION['felhnev']))
  {             // Be vagyunk jelentkezve
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Helyi int�zked�sek';";
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
  <title>Helyi int�zked�sek</title>
  <script language="javascript"></script>
</head>
<body>
<?php

  if (ISSET($_POST['tevekenyseg']))
  { // ha be van �ll�tva a tevekenyseg
    $tevekenyseg = $_POST['tevekenyseg'];

    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error()) ;
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");

    $intazon=$_POST['intazon'];

    if (ISSET($_POST['ev']))  $ev=$_POST['ev'];

    if (!(empty($_POST['sorszam']))) {$sorszam=$_POST['sorszam'];} else {$sorszam='';}
    if (!(empty($_POST['intcim']))) { $intcim=$_POST['intcim']; } else {$intcim='';}
    if (!(empty($_POST['hatlep']))) {$hatlep=$_POST['hatlep'];} else {$hatlep='';}
    if (!(empty($_POST['hatveszt']))) $hatveszt=$_POST['hatveszt']; else {$hatveszt='';}

    if ($tevekenyseg=='Felt�lt�s')
    {  // Csak Felt�lt�s eset�n kell �talak�tani az intazont
      if ($sorszam<100)
      {
        if ($sorszam<10)
        {   //egyjegy�
          $sorszam = '00' . $sorszam;
        }
        else
        {   //k�tjegy�
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

    if (($tevekenyseg=='Felt�lt�s') or ($tevekenyseg=='M�dos�t�s'))
    {   // A f�jl felt�lt�se ebben a k�t esetben ugyan�gy m�k�dik
      $htmlnev = basename($_FILES['htmlnev']['name']);
      $pdfnev = basename($_FILES['pdfnev']['name']);
      $sikeresfeltoltes=0;
      $marvanilyen = false;

      if (!(($htmlnev=='') and ($pdfnev=='')))
      {
        if (is_uploaded_file($_FILES['pdfnev']['tmp_name']))
        {
          $kiterjesztes = strrchr($pdfnev,'.');
          // Ha az eredeti kiterjeszt�s is html, akkor gond lehet a felt�lt�sn�l, hiszen ez is ugyanolyan nevet kap, mint pl. a word.
          // Emiatt a n�v v�g�re tesz�nk egy e bet�t...
          if ($kiterjesztes=='.html')
          {
            $fajlnev_eredeti = $fajlnev . 'e' . $kiterjesztes;
          }
          else
          {
            $fajlnev_eredeti = $fajlnev . $kiterjesztes;
          }
        }
  
        // Csak a felt�lt�s el�tt itt vizsg�ljuk meg, hogy l�teznek-e a kijel�lt f�jlok... (a mell-et m�dos�t�sn�l is)
        if ($tevekenyseg=='Felt�lt�s')
        {   // A f�jl felt�lt�se ebben a k�t esetben ugyan�gy m�k�dik
  
          if (!$htmlnev=='')
          {
            if (is_uploaded_file($_FILES['htmlnev']['tmp_name']))
            {
              if(file_exists($html_ut . '/' . $fajlnev_html))
              {  // A f�jl m�r l�tezett, vagy egy�b hiba
                $marvanilyen = true;
                echo "A html f�jl m�r l�tezett, vagy egy�b hiba. Nem t�rt�nt $tevekenyseg.<BR>";
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
                echo "Az eredeti f�jl m�r l�tezett, vagy egy�b hiba. Nem t�rt�nt $tevekenyseg.<BR>";
              }
              else
              {
                $marvanilyen = false;
              }
            }
          }
        }  // if tev = felt�lt�s v�ge
      }    //      if !(($html=='') and ($pdfnev=='')) v�ge


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
          echo "Nem t�rt�nt $tevekenyseg. A k�vetkez� mell�klet(ek valamelyike) m�r l�tezett, vagy egy�b hiba:<BR>";
          echo "<b>$melllista</b>";
        }
      }
             // Megvizsg�ltuk a fent l�v� f�jlokat. Ha nincs gond, akkor mehet a felt�lt�s...

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
            $query = "INSERT INTO mellekletek (mihez, mihez_azon, mellnev) VALUES ('int�zked�s','$intazonuj','$mellnev');";
            $result = mysql_query($query) or die("�zenet: " . mysql_error());
          }
        }
      }
      else
      {  // A f�jl m�r l�tezett, vagy egy�b hiba
        $mindenok = false;
        echo "<A HREF=intezkedesek.php>B�ng�sz�s</A>";
      }
    } // if $tevekenyseg== felt�lt�s, vagy m�dos�t�s v�ge
    else
    {  // ha t�rl�s, akkor j�n ide
      $mindenok=true;
    }

    if ($mindenok == true)
    {                 /* $mindenok akkor true, ha a f�jl felt�lt�sek sor�n nem volt gond
                         A t�rl�sn�l nem sz�m�t a mindenok �rt�ke, de az is itt van, ez�rt t�rl�sn�l true-ra �ll�tjuk.*/
      switch ($tevekenyseg)
      {
        case 'Felt�lt�s':
        {
          if ($hatveszt=='')
          { // Ha a hat�lyveszt�s nincs kit�ltve
            $query = "INSERT INTO intezkedesek (intazon,ev,sorszam,intcim,hatlep,htmlnev,pdfnev)
                        VALUES ('$intazonuj','$ev', '$sorszam', '$intcim', '$hatlep', '$fajlnev_html', '$fajlnev_eredeti');";
          } else
          { // Ha a hat�lyveszt�s nincs kit�ltve
            $query = "INSERT INTO intezkedesek (intazon,ev,sorszam,intcim,hatlep,hatveszt,htmlnev,pdfnev)
                        VALUES ('$intazonuj','$ev', '$sorszam', '$intcim', '$hatlep', '$hatveszt', '$fajlnev_html', '$fajlnev_eredeti');";
          }
          $uzenet = "Az �j int�zked�s r�gz�t�se siker�lt.";
          break;
        }  // Felt�lt�s v�ge
        case 'M�dos�t�s':
        {
          $query = "UPDATE intezkedesek SET
                      intazon='$intazon',
                      ev='$ev',
                      sorszam='$sorszam',
                      intcim='$intcim',
                      hatlep='$hatlep'";

          if (!($hatveszt==''))
          { // Ha a hat�lyveszt�s ki van t�ltve
            $query .= ",hatveszt='$hatveszt'";
          }
          if (!($pdfnev==''))
          { // Ha a hat�lyveszt�s ki van t�ltve
            $query .= ",pdfnev='$fajlnev_eredeti'";
          }
          $query .= "WHERE intazon='$intazon';";
  
          $uzenet = "Az int�zked�s m�dos�t�sa siker�lt.";
          break;
        }  // M�dos�t�s v�ge
  
        case 'T�rl�s':
        {
          $query = "SELECT pdfnev FROM intezkedesek WHERE intazon='$intazon';";
          $result = mysql_query($query) or die("�zenet: " . mysql_error());
          $fajlnev_eredeti = mysql_result($result,0,"pdfnev");
  
          $query = "DELETE FROM intezkedesek WHERE intazon='$intazon'";
          $uzenet = "A t�rl�s siker�lt.";
  
          // Ha a m�dos�t�sok t�bl�t �rinti az int�zked�s t�rl�se, akkor ott is t�r�lni kell.
          $queryvizsg2 = "DELETE FROM modositasok WHERE mi='$intazon';";
          $resultvizsg2 = mysql_query($queryvizsg2) or die("�zenet: " . mysql_error());

          $queryvizsg3 = "DELETE FROM modositasok WHERE mit='$intazon';";
          $resultvizsg3 = mysql_query($queryvizsg3) or die("�zenet: " . mysql_error());

          // Mell�kletek t�bla
          $queryvizsg5 = "SELECT * FROM mellekletek WHERE mihez='int�zked�s' AND mihez_azon='$intazon';";
          $resultvizsg5 = mysql_query($queryvizsg5) or die("�zenet: " . mysql_error());
          $numvizsg5 = mysql_numrows($resultvizsg5);

          $j=0;
          while ($j < $numvizsg5)
          { // mell�kletek fizikai t�rl�se
            $mellnev = mysql_result($resultvizsg5,$j,"mellnev");
            unlink('./' . $mell_ut . '/' . $mellnev);
            $j++;
          }

          // Tags t�bla
          $queryvizsg6 = "DELETE FROM tags WHERE mihez='int�zked�s' AND mihez_azon='$intazon';";
          $resultvizsg6 = mysql_query($queryvizsg6) or die("�zenet: " . mysql_error());

          // V�g�l t�r�lni kell a html-f�jlt
          unlink('./' . $html_ut . '/' . $fajlnev_html);
          unlink('./' . $html_ut . '/' . $fajlnev_eredeti);

          $queryvizsg4 = "DELETE FROM mellekletek WHERE mihez='int�zked�s' AND mihez_azon='$intazon';";
          $resultvizsg4 = mysql_query($queryvizsg4) or die("�zenet: " . mysql_error());
  
          break;
        }  // T�rl�s v�ge
      }
  
      $result = mysql_query($query) or die("�zenet: " . mysql_error());
  
      /* Ak�r szerkeszt�sr�l, ak�r felvitelr�l van sz�, be kell �ll�tani, hogy miket helyez hat�lyon k�v�l �s miket m�dos�t.
      Az esetleges m�dos�t�s miatt �gy kell be�ll�tani, hogy az eddigi be�ll�t�sokat t�r�lni kell �s az �jat kell be�rni.
      Ugyanezt meg kell csin�lni a kulcsszavakkal kapcsolatban is.*/
  
  
      // Hat�lyon k�v�lik t�rl�se...
      $queryhat = "UPDATE intezkedesek SET
                      hathelyez=NULL
                      WHERE hathelyez='$intazon';";
      $resulthat = mysql_query($queryhat) or die("�zenet: " . mysql_error());
  
      // Majd felvittel...
      if (!(empty($_POST['hathelyez'])))
      {
        $hathelyez=$_POST['hathelyez'];
        if ($hathelyez)
        {
          foreach ($hathelyez as $intazon_a)
          {
            $querykilo = "UPDATE intezkedesek SET hathelyez='$intazonuj' WHERE intazon='$intazon_a';";
            $resultkilo = mysql_query($querykilo) or die("�zenet: " . mysql_error());
          }
        }
      }
  
      // M�dos�t�sok t�rl�se...
      $querymod = "DELETE FROM modositasok WHERE mi='$intazon'";
      $resultmod = mysql_query($querymod) or die("�zenet: " . mysql_error());
  
      // Majd felvitel...
      if (!(empty($_POST['mitmodosit'])))
      {
        $mitmodosit=$_POST['mitmodosit'];
        if ($mitmodosit)
        {
          foreach ($mitmodosit as $intazon_a)
          {
            $querymod = "INSERT INTO modositasok (mi,mit) VALUES ('$intazonuj','$intazon_a');";
            $resultmod = mysql_query($querymod) or die("�zenet: " . mysql_error());
          }
        }
      }

      // Kulcsszavak t�rl�se...
      $querytag = "DELETE FROM tags WHERE mihez='int�zked�s' AND mihez_azon='$intazonuj'";
      $resulttag = mysql_query($querytag) or die("�zenet: " . mysql_error());

      if (!(empty($_POST['kulcsszo'])))
      {
        $kulcsszo = $_POST['kulcsszo'];
        $szavak = explode(", ", $kulcsszo);
        foreach ($szavak as $szo)
        {
          if (!($szo==''))
          {
            $querytag = "INSERT INTO tags (mihez,mihez_azon,kulcsszo)
                          VALUES ('int�zked�s','$intazonuj', '$szo');";
            $resulttag = mysql_query($querytag) or die("�zenet: " . mysql_error());
          }
        }
      }
      echo "$uzenet" . "<BR><A HREF=intezkedes.php>�j int.</A><BR><A HREF=intezkedesek.php>B�ng�sz�s</A>";
    }  // if mindenok==true v�ge
  }
    else
  { // nincs be�ll�tva a $tevekenyseg, sz�val k�v�lr�l szeretn�nek ide bejutni.
     echo "A tev�kenys�g nem siker�lt!<BR><A HREF='intezkedesek.php'>Vissza az int�zked�sekhez</A>";
  }

?>
</body>
</html>

