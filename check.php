<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
</head>
  <script language="javascript">

  function sorszamHiba()
  {
    parent.sorszamjo=false;
    parent.document.getElementById("tdsorszam").innerHTML="<font color='red'>Már van ilyen sorszámú intézkedés!</font>";
    parent.document.getElementById("tdsorszam").style.display="inline";
  }
  function htmlnevHiba()
  {
    parent.sorszamjo=false;
    parent.document.getElementById("tdhtmlnev").innerHTML="<font color='red'>Csak .htm vagy .html kiterjesztésű fájlt lehet feltölteni!</font>";
    parent.document.getElementById("tdhtmlnev").style.display="inline";
  }

  </script>
<body>
<?php
  // Ellenőrzések
  session_start();
  include "myconsts.php";
  $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error());
  @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");

  $ev = $_GET['ev'];
  $sorszam = $_GET['sorszam'];
  if (isset($_GET['htmlnev']))
  {
    $tevekenyseg = 'Feltöltés';
    $htmlnev = $_GET['htmlnev'];
  }
  else
  {
      $tevekenyseg = 'Módosítás';
  }

  $ok = true;


  // Felvitel esetén: Adott évben van-e már ilyen sorszámmal intézkedés
  if ($tevekenyseg=='Feltöltés')
  {
    $query = "SELECT * FROM intezkedesek WHERE ev='$ev' and sorszam='$sorszam';";
    $result = mysql_query($query) or die("üzenet: " . mysql_error());
    $num = mysql_numrows($result);

    if ($num>0)
    {
      $ok = false;
      print "<script language=\"javascript\">sorszamHiba()</script>";
    }
    // A html fájl kiterjesztése htm, vagy html legyen...
    $kiterjesztes = strtolower(strrchr($htmlnev,'.'));
    if (!(($kiterjesztes=='.htm') or ($kiterjesztes=='.html')))
    {
      $ok = false;
      print "<script language=\"javascript\">htmlnevHiba()</script>";
    }
  }


  if ($ok == true)
  {  //ha nincs hiba
    print "<script language=\"javascript\">parent.document.getElementById(\"felvitel\").submit();</script>";
  }



?>
</body>
</html>
