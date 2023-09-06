<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>BIR - Helyi intézkedések</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript"></script>
</head>
<body>

<?php
  session_start();
  include "myconsts.php";
  include "teteje.php";
  include "bal.php";
  $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error()) ;
  @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");

  echo "<div id=\"primaryContent\">
        <h2>Helyi intézkedések</h2>";

  echo "<a href = \"javascript:windowHandle = window.open('help/intsugo.html','windowname','width=700,height=280,location=yes'); windowHandle.focus();\">Használati útmutató</a><BR><BR>";
  $hatjelol = '';
  $mindenjelol = '';
  $query1 = "SELECT * FROM intezkedesek ";
  $query2 = " ";
  $query3 = " ORDER BY ev DESC,intazon DESC;";

  $mainap = date("Y-m-d");
  if (isset($_GET['szuro']))
  {
    $szuro = $_GET['szuro'];
    switch ($szuro)
    {
      case 'minden':
      {
        $mindenjelol = 'selected';
        break;
      }
      case 'hat':
      {
        $hatjelol = 'selected';
        $query2 = "WHERE (hatveszt IS NULL) OR (hatveszt > '$mainap')";
        break;
      }
    }
  }
  if (isset($_GET['kulcsszo']))
    $kulcsszo = $_GET['kulcsszo'];
  else
    $kulcsszo = '';

  echo "<FORM enctype='multipart/form-data' name='szures' method='get'>
        <select name='szuro' onChange='this.form.submit()'>
        <option value='minden' $mindenjelol>Minden intézkedés</option>
        <option value='hat' $hatjelol>Csak a hatályosak</option>
        </select>";
  $querytag = "SELECT * FROM tags
               WHERE mihez='intézkedés'
               GROUP BY kulcsszo;";
  $resulttag = mysql_query($querytag) or die("üzenet: " . mysql_error());
  $numtag = mysql_numrows($resulttag);
  $i=0;

  echo " Tárgyszó: <select name='kulcsszo' onChange='this.form.submit()'>
                   <option value=''>Válassz...</option>";
  while ($i < $numtag)
  {
    $kulcsszo_ad = mysql_result($resulttag,$i,"kulcsszo");
    if ($kulcsszo==$kulcsszo_ad)
      $selected = 'selected';
    else
      $selected = '';
    echo "<option value='$kulcsszo_ad' $selected>$kulcsszo_ad</option>";
    $i++;
  }
  echo "</select>
        </FORM>";
  if (!($kulcsszo==''))
  {  // szűrni kell
    $query1 ="SELECT * FROM intezkedesek,tags ";
    if ($query2==' ')
    {  // nincs hatályszűrés
      $query2 = " WHERE intezkedesek.intazon=tags.mihez_azon AND tags.mihez='intézkedés' AND tags.kulcsszo='$kulcsszo'";
    }
    else
    {
      $query2 = " WHERE ((hatveszt IS NULL) OR (hatveszt > '$mainap')) AND
                 intezkedesek.intazon=tags.mihez_azon AND tags.mihez='intézkedés' AND tags.kulcsszo='$kulcsszo'";
    }
  }

  echo "<TABLE width=100% border=1><TR><TH width=50>Sorszám<TH>Cím<TH width=50>Hatályba lépés<TH width=50>Hatályát veszti
  <TH width=60><ABBR title='Hatályon kívül helyező intézkedés'>HK</ABBR><TH width=60><ABBR title='Módosító intézkedés'>MI</ABBR><TH>Mellékletek";

  $query = $query1 . $query2 . $query3;
  $result = mysql_query($query) or die("üzenet: " . mysql_error());
  $num = mysql_numrows($result);
  $i=0;

  while ($i < $num)
  {
    $intazon = mysql_result($result,$i,"intazon");
    $ev = mysql_result($result,$i,"ev");
    $sorszam = mysql_result($result,$i,"sorszam");
    $intcim = mysql_result($result,$i,"intcim");
    $hatlep = mysql_result($result,$i,"hatlep");
    $hatveszt = mysql_result($result,$i,"hatveszt");
    $hathelyez = mysql_result($result,$i,"hathelyez");
    $htmlnev = mysql_result($result,$i,"htmlnev");
    $eredeti = mysql_result($result,$i,"pdfnev");

    $querymod = "SELECT *
               FROM modositasok
               WHERE mit='$intazon'
               ORDER BY mi;";
    $resultmod = mysql_query($querymod) or die("üzenet: " . mysql_error());
    $nummod = mysql_numrows($resultmod);
    $j=0;
    $mod_szoveg='';
    while ($j < $nummod)
    {
      if ($j>0)
      {
        $mod_szoveg .= "<BR>";
      }
      $mi = mysql_result($resultmod,$j,"mi");
      $mod_fajl = fajlnevve($mi) . '.html';
      $mod_szoveg .= "<a href=$html_ut/$mod_fajl>$mi</a>";
      $j++;
    }

    $querymell = "SELECT *
               FROM mellekletek
               WHERE mihez='intézkedés' AND mihez_azon='$intazon';";
    $resultmell = mysql_query($querymell) or die("üzenet: " . mysql_error());
    $nummell = mysql_numrows($resultmell);

    $j=0;
    $mell ='';
    while ($j < $nummell)
    {
      $mellnev = mysql_result($resultmell,$j,"mellnev");
      $mell .= "<a href='$mell_ut/$mellnev'>$mellnev</a>" ."<BR>";
      $j++;
    }
    $hathelyez_fajl = fajlnevve($hathelyez) . '.html';
    $paros = $i % 2;
    if ($paros==0) 
      $sor="rowA";
    else
      $sor="rowB";
    if ((!($hatveszt=='')) and ($hatveszt<=$mainap))
      $sor="rowC";

    echo "<TR class='$sor'><TD>$intazon<TD><a href=$html_ut/$htmlnev>$intcim</a>";
    if ($helyiintjog == 1)
    {
      echo "<a href=intezkedes.php?intazon=$intazon><img src=\"img/edit.png\" alt='Szerkesztés' width=\"16\" height=\"16\" border=\"0\" /></a>
      <a href=inttorol.php?intazon=$intazon><img src=\"img/torol.png\" alt=\"Törlés\" width=\"16\" height=\"16\" border=\"0\" /></a>";
    }
    echo "<a href=$html_ut/$eredeti><img src=\"img/print.png\" alt=\"Nyomtatás\" width=\"16\" height=\"16\" border=\"0\" /></a>
    <TD>$hatlep<TD>$hatveszt<TD><a href=$html_ut/$hathelyez_fajl>$hathelyez</a><TD>$mod_szoveg<TD>$mell";
    $i++;
 }
 echo "</TABLE></div>";
 include "alja.php";
?>

</body></html>