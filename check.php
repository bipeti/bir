<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
</head>
  <script language="javascript">

  function sorszamHiba()
  {
    parent.sorszamjo=false;
    parent.document.getElementById("tdsorszam").innerHTML="<font color='red'>M�r van ilyen sorsz�m� int�zked�s!</font>";
    parent.document.getElementById("tdsorszam").style.display="inline";
  }
  function htmlnevHiba()
  {
    parent.sorszamjo=false;
    parent.document.getElementById("tdhtmlnev").innerHTML="<font color='red'>Csak .htm vagy .html kiterjeszt�s� f�jlt lehet felt�lteni!</font>";
    parent.document.getElementById("tdhtmlnev").style.display="inline";
  }

  </script>
<body>
<?php
  // Ellen�rz�sek
  session_start();
  include "myconsts.php";
  $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error());
  @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");

  $ev = $_GET['ev'];
  $sorszam = $_GET['sorszam'];
  if (isset($_GET['htmlnev']))
  {
    $tevekenyseg = 'Felt�lt�s';
    $htmlnev = $_GET['htmlnev'];
  }
  else
  {
      $tevekenyseg = 'M�dos�t�s';
  }

  $ok = true;


  // Felvitel eset�n: Adott �vben van-e m�r ilyen sorsz�mmal int�zked�s
  if ($tevekenyseg=='Felt�lt�s')
  {
    $query = "SELECT * FROM intezkedesek WHERE ev='$ev' and sorszam='$sorszam';";
    $result = mysql_query($query) or die("�zenet: " . mysql_error());
    $num = mysql_numrows($result);

    if ($num>0)
    {
      $ok = false;
      print "<script language=\"javascript\">sorszamHiba()</script>";
    }
    // A html f�jl kiterjeszt�se htm, vagy html legyen...
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
