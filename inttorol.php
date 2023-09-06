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
  <script language="javascript">
  function nem()
  {
    document.location.replace("intezkedesek.php");
  }
  </script>

<?php
  if ((isset($_GET['intazon'])) and (!(isset($_GET['mellnev']))))
  {   // Intézkedés törléséről van szó
    $intazon = $_GET['intazon'];

    $query = "SELECT * FROM intezkedesek WHERE intazon='$intazon';";
    $result = mysql_query($query) or die("üzenet: " . mysql_error());

    $ev = mysql_result($result,0,"ev");
    $sorszam = mysql_result($result,0,"sorszam");
    $intcim = mysql_result($result,0,"intcim");
    $hatlep = mysql_result($result,0,"hatlep");
    $hatveszt = mysql_result($result,0,"hatveszt");
    $hathelyez = mysql_result($result,0,"hathelyez");
    $htmlnev = mysql_result($result,0,"htmlnev");
    $pdfnev = mysql_result($result,0,"pdfnev");

    echo "<TABLE><TR>
      <TD>Év: <TD><INPUT TYPE=\"text\" NAME=\"ev\" VALUE=\"$ev\"><TR>
      <TD>Sorszám: <TD><INPUT TYPE=\"text\" NAME=\"sorszam\" VALUE=\"$sorszam\"><TD><TR>
      <TD>Az intézkedés tárgya: <TD><INPUT TYPE=\"text\" NAME=\"intcim\" VALUE=\"$intcim\"><TD><TR>
      <TD>Hatálybalépés dátuma: <TD><INPUT TYPE=\"text\" NAME=\"hatlep\" VALUE=\"$hatlep\"><TD><TR>
      <TD>Hatályát veszti: <TD><INPUT TYPE=\"text\" NAME=\"hatveszt\" VALUE=\"$hatveszt\"><TD><TR>
      <TD>Html elérési útja: <TD><INPUT TYPE=\"text\" NAME=\"htmlnev\" VALUE=\"$htmlnev\"><TD><TR>
      <TD>Pdf elérési útja: <TD><INPUT TYPE=\"text\" NAME=\"pdfnev\" VALUE=\"$pdfnev\"><TD></TABLE>
      <BR>";

      // Kapcsolat vizsgálatok
    if (!($hathelyez==NULL))
    { // Ez az intézkedés hatályon kívül lenne helyezve egy másik által.
      echo "Ezt az intézkedést az alábbi intézkedés helyezi hatályon kívül: <B>$hathelyez</B>. Az intézkedés törlésével a kapcsolat is törlésre kerül.<BR>";
    }

    $queryvizsg = "SELECT * FROM intezkedesek WHERE hathelyez='$intazon';";
    $resultvizsg = mysql_query($queryvizsg) or die("üzenet: " . mysql_error());
    $numvizsg = mysql_numrows($resultvizsg);
    if ($numvizsg>0)
    { // Ez az int. helyez hatályon kívül valamit...
      $i=0;
      $mit='';
      while ($i < $numvizsg)
      {
        $mit .= mysql_result($resultvizsg,$i,"intazon") . ", ";
        $i++;
      }
      echo "Ez az intézkedés hatályon kívül helyezi a következő intézkedés(eke)t: <B>$mit</B>. Az intézkedés törlésével a kapcsolat(ok) is törlésre kerül(nek).<BR>";
    }

    $queryvizsg2 = "SELECT * FROM modositasok WHERE mit='$intazon';";
    $resultvizsg2 = mysql_query($queryvizsg2) or die("üzenet: " . mysql_error());
    $numvizsg2 = mysql_numrows($resultvizsg2);
    if ($numvizsg2>0)
    { // Ezt az int-et módosítja...
      $i=0;
      $mi='';
      while ($i < $numvizsg2)
      {
        $mi .= mysql_result($resultvizsg2,$i,"mi") . ", ";
        $i++;
      }
      echo "Ezt az intézkedést a következő intézkedés(ek) módosítja(k): <B>$mi</B>. Az intézkedés törlésével a kapcsolat(ok) is törlésre kerül(nek).<BR>";
    }

    $queryvizsg3 = "SELECT * FROM modositasok WHERE mi='$intazon';";
    $resultvizsg3 = mysql_query($queryvizsg3) or die("üzenet: " . mysql_error());
    $numvizsg3 = mysql_numrows($resultvizsg3);
    if ($numvizsg3>0)
    { // Ez az int módosít másik inteket.
      $i=0;
      $mit='';
      while ($i < $numvizsg3)
      {
        $mit .= mysql_result($resultvizsg3,$i,"mit") . ", ";
        $i++;
      }
      echo "Ez az intézkedés a következő intézkedés(eke)t módosítja: <B>$mit</B>. Az intézkedés törlésével a kapcsolat(ok) is törlésre kerül(nek).<BR>";
    }


    echo "<FORM name='torol' method='post' action='intaction.php'>        Biztosan törölni kívánja?<BR>
      <input type='hidden' name='tevekenyseg' value='Törlés'>
      <input type='hidden' name='intazon' value='$intazon'>
      <INPUT TYPE=\"submit\" VALUE=\"Igen\" NAME=\"gomb\"><INPUT TYPE=\"button\" VALUE=\"Nem\" NAME=\"gomb\" onClick=\"nem();\">
      </FORM>";
  }
  elseif ((isset($_GET['intazon'])) and (isset($_GET['mellnev'])))
  {  // Melléklet törléséről van szó
    $intazon = $_GET['intazon'];
    $mellnev = $_GET['mellnev'];
    unlink('./' . $mell_ut . '/' . $mellnev);
    $query = "DELETE FROM mellekletek WHERE mihez='intézkedés' AND mihez_azon='$intazon' and mellnev='$mellnev';";
    $result = mysql_query($query) or die("üzenet: " . mysql_error());
    header('Location: intezkedes.php?intazon=' . $intazon);
  }
  else
  { // nincs kitöltve a GET_intazon, kívülről jöttek
    echo "kukucs";
  }

  echo "<BR><A HREF=intezkedesek.php>Böngészés</A>";
?>
</body></html>