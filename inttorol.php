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
  <script language="javascript">
  function nem()
  {
    document.location.replace("intezkedesek.php");
  }
  </script>

<?php
  if ((isset($_GET['intazon'])) and (!(isset($_GET['mellnev']))))
  {   // Int�zked�s t�rl�s�r�l van sz�
    $intazon = $_GET['intazon'];

    $query = "SELECT * FROM intezkedesek WHERE intazon='$intazon';";
    $result = mysql_query($query) or die("�zenet: " . mysql_error());

    $ev = mysql_result($result,0,"ev");
    $sorszam = mysql_result($result,0,"sorszam");
    $intcim = mysql_result($result,0,"intcim");
    $hatlep = mysql_result($result,0,"hatlep");
    $hatveszt = mysql_result($result,0,"hatveszt");
    $hathelyez = mysql_result($result,0,"hathelyez");
    $htmlnev = mysql_result($result,0,"htmlnev");
    $pdfnev = mysql_result($result,0,"pdfnev");

    echo "<TABLE><TR>
      <TD>�v: <TD><INPUT TYPE=\"text\" NAME=\"ev\" VALUE=\"$ev\"><TR>
      <TD>Sorsz�m: <TD><INPUT TYPE=\"text\" NAME=\"sorszam\" VALUE=\"$sorszam\"><TD><TR>
      <TD>Az int�zked�s t�rgya: <TD><INPUT TYPE=\"text\" NAME=\"intcim\" VALUE=\"$intcim\"><TD><TR>
      <TD>Hat�lybal�p�s d�tuma: <TD><INPUT TYPE=\"text\" NAME=\"hatlep\" VALUE=\"$hatlep\"><TD><TR>
      <TD>Hat�ly�t veszti: <TD><INPUT TYPE=\"text\" NAME=\"hatveszt\" VALUE=\"$hatveszt\"><TD><TR>
      <TD>Html el�r�si �tja: <TD><INPUT TYPE=\"text\" NAME=\"htmlnev\" VALUE=\"$htmlnev\"><TD><TR>
      <TD>Pdf el�r�si �tja: <TD><INPUT TYPE=\"text\" NAME=\"pdfnev\" VALUE=\"$pdfnev\"><TD></TABLE>
      <BR>";

      // Kapcsolat vizsg�latok
    if (!($hathelyez==NULL))
    { // Ez az int�zked�s hat�lyon k�v�l lenne helyezve egy m�sik �ltal.
      echo "Ezt az int�zked�st az al�bbi int�zked�s helyezi hat�lyon k�v�l: <B>$hathelyez</B>. Az int�zked�s t�rl�s�vel a kapcsolat is t�rl�sre ker�l.<BR>";
    }

    $queryvizsg = "SELECT * FROM intezkedesek WHERE hathelyez='$intazon';";
    $resultvizsg = mysql_query($queryvizsg) or die("�zenet: " . mysql_error());
    $numvizsg = mysql_numrows($resultvizsg);
    if ($numvizsg>0)
    { // Ez az int. helyez hat�lyon k�v�l valamit...
      $i=0;
      $mit='';
      while ($i < $numvizsg)
      {
        $mit .= mysql_result($resultvizsg,$i,"intazon") . ", ";
        $i++;
      }
      echo "Ez az int�zked�s hat�lyon k�v�l helyezi a k�vetkez� int�zked�s(eke)t: <B>$mit</B>. Az int�zked�s t�rl�s�vel a kapcsolat(ok) is t�rl�sre ker�l(nek).<BR>";
    }

    $queryvizsg2 = "SELECT * FROM modositasok WHERE mit='$intazon';";
    $resultvizsg2 = mysql_query($queryvizsg2) or die("�zenet: " . mysql_error());
    $numvizsg2 = mysql_numrows($resultvizsg2);
    if ($numvizsg2>0)
    { // Ezt az int-et m�dos�tja...
      $i=0;
      $mi='';
      while ($i < $numvizsg2)
      {
        $mi .= mysql_result($resultvizsg2,$i,"mi") . ", ";
        $i++;
      }
      echo "Ezt az int�zked�st a k�vetkez� int�zked�s(ek) m�dos�tja(k): <B>$mi</B>. Az int�zked�s t�rl�s�vel a kapcsolat(ok) is t�rl�sre ker�l(nek).<BR>";
    }

    $queryvizsg3 = "SELECT * FROM modositasok WHERE mi='$intazon';";
    $resultvizsg3 = mysql_query($queryvizsg3) or die("�zenet: " . mysql_error());
    $numvizsg3 = mysql_numrows($resultvizsg3);
    if ($numvizsg3>0)
    { // Ez az int m�dos�t m�sik inteket.
      $i=0;
      $mit='';
      while ($i < $numvizsg3)
      {
        $mit .= mysql_result($resultvizsg3,$i,"mit") . ", ";
        $i++;
      }
      echo "Ez az int�zked�s a k�vetkez� int�zked�s(eke)t m�dos�tja: <B>$mit</B>. Az int�zked�s t�rl�s�vel a kapcsolat(ok) is t�rl�sre ker�l(nek).<BR>";
    }


    echo "<FORM name='torol' method='post' action='intaction.php'>        Biztosan t�r�lni k�v�nja?<BR>
      <input type='hidden' name='tevekenyseg' value='T�rl�s'>
      <input type='hidden' name='intazon' value='$intazon'>
      <INPUT TYPE=\"submit\" VALUE=\"Igen\" NAME=\"gomb\"><INPUT TYPE=\"button\" VALUE=\"Nem\" NAME=\"gomb\" onClick=\"nem();\">
      </FORM>";
  }
  elseif ((isset($_GET['intazon'])) and (isset($_GET['mellnev'])))
  {  // Mell�klet t�rl�s�r�l van sz�
    $intazon = $_GET['intazon'];
    $mellnev = $_GET['mellnev'];
    unlink('./' . $mell_ut . '/' . $mellnev);
    $query = "DELETE FROM mellekletek WHERE mihez='int�zked�s' AND mihez_azon='$intazon' and mellnev='$mellnev';";
    $result = mysql_query($query) or die("�zenet: " . mysql_error());
    header('Location: intezkedes.php?intazon=' . $intazon);
  }
  else
  { // nincs kit�ltve a GET_intazon, k�v�lr�l j�ttek
    echo "kukucs";
  }

  echo "<BR><A HREF=intezkedesek.php>B�ng�sz�s</A>";
?>
</body></html>