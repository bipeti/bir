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
  var eve=false;
  var sorszame=false;
  var intcime=false;
  var hatlepe=false;
  var htmlneve=false;
  var pdfneve=false;
  var evjo=false;
  var sorszamjo=false;
  var intcimjo=false;
  var hatlepjo=false;
  var htmlnevjo=false;
  var pdfnevjo=false;
  var hatvesztjo=false;

  var phpell=false;

  function atrak(hova)
  {
    document.location.replace("inttorol.php?hatinttorol=" + hova);
    return;
  };
  function openWindow(hova)
  {
    window.open("inttorol.php?hatinttorol=" + hova, 'ablakinttorol', 'scrollbars=no, resizable=no, menu=no, width=460, height=500');
    document.close();
    return;
  }
  function writeText()
  {
    var t=document.getElementById('desc');
    var tb=t.getElementsByTagName('tbody')[0];
    var tr=document.createElement('tr');
    var td1=document.createElement('td');
    var td2=document.createElement('td');
    var inp=document.createElement('input');
    inp.type="file";
    inp.name="mell[]"
    inp.size="80"
    var szoveg=document.createTextNode("Mellékletek feltöltése: ");
    td1.appendChild(szoveg);
    td2.appendChild(inp);
    tr.appendChild(td1);
    tr.appendChild(td2);
    tb.appendChild(tr);
  }

  // Űrlap kitöltésének ellenőrzése

  function evEll()
  {
    eve=true;
    if (document.getElementById("ev").value=="")
    {
      evjo=false;
      document.getElementById("tdev").innerHTML="<font color='red'>Kötelező kitölteni!</font>";
      document.getElementById("tdev").style.display="inline";
    }
    else
    {
      evjo=true;
      document.getElementById("tdev").style.display="none";
    }
  }
  function sorszamEll()
  {
    sorszame=true;
    if (document.getElementById("sorszam").value=="")
    {
      sorszamjo=false;
      document.getElementById("tdsorszam").innerHTML="<font color='red'>Kötelező kitölteni!</font>";
      document.getElementById("tdsorszam").style.display="inline";
    }
    else
    {
      sorszamjo=true;
      document.getElementById("tdsorszam").style.display="none";
    }
  }
  function intcimEll()
  {
    intcime=true;
    if (document.getElementById("intcim").value=="")
    {
      intcimjo=false;
      document.getElementById("tdintcim").innerHTML="<font color='red'>Kötelező kitölteni!</font>";
      document.getElementById("tdintcim").style.display="inline";
    }
    else
    {
      intcimjo=true;
      document.getElementById("tdintcim").style.display="none";
    }
  }
  function hatlepEll()
  {
    hatlepe=true;
    if (document.getElementById("hatlep").value=="")
    {
      hatlepjo=false;
      document.getElementById("tdhatlep").innerHTML="<font color='red'>Kötelező kitölteni!</font>";
      document.getElementById("tdhatlep").style.display="inline";
    }
    else
    {
      if (datecheck(document.getElementById("hatlep").value))
      {
        hatlepjo=true;
        document.getElementById("tdhatlep").style.display="none";
      } else
      {
        hatlepjo=false;
        document.getElementById("tdhatlep").innerHTML="<font color='red'>Nem megfelelő formátum! ÉÉÉÉ-HH-NN</font>";
        document.getElementById("tdhatlep").style.display="inline";
      }
    }
  }
  function hatvesztEll()
  {
    if (document.getElementById("hatveszt").value=="")
    {
      hatvesztjo=true;   // Ez most akkor is jó, ha üres
      document.getElementById("tdhatveszt").style.display="none";
    }
    else
    {
      if (datecheck(document.getElementById("hatveszt").value))
      {  // Ha a dátum típusa jó
        if (document.getElementById("hatveszt").value>=document.getElementById("hatlep").value)
        {
          hatvesztjo=true;
          document.getElementById("tdhatveszt").style.display="none";
        }
        else
        {
          hatvesztjo=false;
          document.getElementById("tdhatveszt").innerHTML="<font color='red'>A hatályvesztés dátuma nem lehet korábbi, mint a hatályba lépésé!</font>";
          document.getElementById("tdhatveszt").style.display="inline";
        }
      }
      else
      {
        hatvesztjo=false;
        document.getElementById("tdhatveszt").innerHTML="<font color='red'>Nem megfelelő formátum! ÉÉÉÉ-HH-NN</font>";
        document.getElementById("tdhatveszt").style.display="inline";
      }
    }
  }
  function htmlnevEll()
  {
    htmlneve=true;
    if (document.getElementById("htmlnev").value=="")
    {
      htmlnevjo=false;
      document.getElementById("tdhtmlnev").innerHTML="<font color='red'>Kötelező kitölteni!</font>";
      document.getElementById("tdhtmlnev").style.display="inline";
    }
    else
    {
      htmlnevjo=true;
      document.getElementById("tdhtmlnev").style.display="none";
    }
  }
  function pdfnevEll()
  {
    pdfneve=true;
    if (document.getElementById("pdfnev").value=="")
    {
      pdfnevjo=false;
      document.getElementById("tdpdfnev").innerHTML="<font color='red'>Kötelező kitölteni!</font>";
      document.getElementById("tdpdfnev").style.display="inline";
    }
    else
    {
      pdfnevjo=true;
      document.getElementById("tdpdfnev").style.display="none";
    }
  }
  function kuld()
  {
    if (eve==false)
    {
      evEll();
    }
    if (sorszame==false)
    {
      sorszamEll();
    }
    if (intcime==false)
    {
      intcimEll();
    }
    if (hatlepe==false)
    {
      hatlepEll();
    }
    hatvesztEll();

    if (document.getElementById("feltoltgomb").value=="Feltöltés")
    {
      if (htmlneve==false)
      {
        htmlnevEll();
      }
      if (pdfneve==false)
      {
        pdfnevEll();
      }
    }
    else
    { // módosítás
      htmlnevjo=true;
      pdfnevjo=true;
    }
    if (evjo && sorszamjo && intcimjo && hatlepjo && htmlnevjo && pdfnevjo && hatvesztjo)
    { // Ezeken a helyeken már jártunk és mindegyik jó is, tehát mehet php-s ellenőrzésre
    //&& (phpell==false)
      phpell=true;
      if (document.getElementById("feltoltgomb").value=="Feltöltés")
      {
        document.getElementById('controll').src='check.php?ev='+document.getElementById("ev").value+'&sorszam='+document.getElementById("sorszam").value+'&htmlnev='+document.getElementById("htmlnev").value;
      }
      else
      {
        document.getElementById('controll').src='check.php?ev='+document.getElementById("ev").value+'&sorszam='+document.getElementById("sorszam").value;
      }
    }
  }

  // Űrlap kitöltése ellenőrzésének a vége

  </script>
</head>
<body>
<?php
  echo "<iframe id='controll' frameborder='0' width='0' height='0' style='display:none;'></iframe>";
  $tevekenyseg = 'Feltöltés';
  if (isset($_GET['intazon']))
  {   // Szerkesztésről van szó 
    $tevekenyseg = 'Módosítás';
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

    $querytag = "SELECT * FROM tags WHERE mihez='intézkedés' AND mihez_azon='$intazon'";
    $resulttag = mysql_query($querytag) or die("üzenet: " . mysql_error());
    $numtag = mysql_numrows($resulttag);
    $kulcsszo = '';
    if ($numtag>0)
    {
      $i=0;
      while ($i < $numtag)
      {
        $kulcsszo .= mysql_result($resulttag,$i,"kulcsszo") . ", ";
        $i++;
      }
    }

    $readonly = 'readonly';
  } else
  {   // Új felvitel
    $queryev = "SELECT MAX(ev) as maxev FROM intezkedesek;";
    $resultev = mysql_query($queryev) or die("üzenet: " . mysql_error());
    $numev = mysql_numrows($resultev);
    if ($numev>0)
    {
      $ev = mysql_result($resultev,0,"maxev");
    } else $ev = '';

    $query = "SELECT MAX(sorszam) as maxi FROM intezkedesek WHERE ev='$ev';";
    $result = mysql_query($query) or die("üzenet: " . mysql_error());
    $maxi = mysql_result($result,0,"maxi");

    $sorszam = $maxi+1;
    $intazon = '';
    $intcim = '';
    $hatlep = '2009-01-01';
    $hatveszt = NULL;
    $htmlnev = '';
    $mell='';
    $pdfnev = '';
    $kulcsszo = '';
    $readonly = '';
  };
  echo "<FORM enctype='multipart/form-data' name='felvitel' id='felvitel' method='post' action='intaction.php'>
        <TABLE border=0 width='100%'><tr height=0><th width=$oszlop1></th><th width=$oszlop2></th><th width=$oszlop3></th><th width=$oszlop4></th><th></th></tr><TR>
        <TD>Év: </TD><TD><INPUT onblur=\"evEll();\" TYPE=\"text\" NAME=\"ev\" id=\"ev\" VALUE=\"$ev\" $readonly></TD>
          <TD id='tdev' style='display:none;'></TD><TR>
        <TD>Sorszám: <TD><INPUT onblur=\"sorszamEll();\" TYPE=\"text\" NAME=\"sorszam\" id=\"sorszam\" VALUE=\"$sorszam\" $readonly></TD>
          <TD id='tdsorszam' style='display:none;'></TD><TR>
        <TD>Az intézkedés tárgya:</TD><TD colspan='3'><INPUT TYPE=\"text\" size=\"80\" onblur=\"intcimEll();\" NAME=\"intcim\" id=\"intcim\" VALUE=\"$intcim\"></TD>
          <TD id='tdintcim' style='display:none;'></TD><TR>
        <TD>Hatálybalépés dátuma: <TD><INPUT onblur=\"hatlepEll();\" TYPE=\"text\" NAME=\"hatlep\" id=\"hatlep\" VALUE=\"$hatlep\"></TD>
          <TD id='tdhatlep' style='display:none;'></TD><TR>
        <TD>Hatályát veszti: <TD><INPUT onblur=\"hatvesztEll();\" TYPE=\"text\" NAME=\"hatveszt\" id=\"hatveszt\" VALUE=\"$hatveszt\"></TD>
          <TD id='tdhatveszt' style='display:none;'></TD><TR>";

  $query = "SELECT intazon FROM intezkedesek;";
  $result = mysql_query($query) or die("üzenet: " . mysql_error());
  $num = mysql_numrows($result);

  // Ha hatályon kívül helyez valamit, akkor jelenítsük meg, hogy mit vizsgálat:
  $queryhat = "SELECT intazon FROM intezkedesek WHERE hathelyez='$intazon';";
  $resulthat = mysql_query($queryhat) or die("üzenet: " . mysql_error());
  $numhat = mysql_numrows($resulthat);

  $i=0;
  $option = '';
  while ($i < $num)
  {
    $intazon_a = mysql_result($result,$i,"intazon");
    $jelol = " ";
    $j=0;
    while (($j < $numhat) and ($jelol ==" "))
    {
      $intazon_jelolni = mysql_result($resulthat,$j,"intazon");
      if ($intazon_jelolni==$intazon_a)
      {  // jelölni kell, mert megvan
        $jelol = " selected";
      }
      $j++;
    }
    $option .= "<option value=$intazon_a" . $jelol . "> " . "$intazon_a</option>";
    $i++;
  }

  if ($tevekenyseg == 'Feltöltés')
  {   // Felvitelről van szó
    $optionmod=$option;
  }
  else
  {
    // Ha módosít valamit, akkor jelenítsük meg, hogy mit vizsgálat:
    $querymod = "SELECT mit FROM modositasok WHERE mi='$intazon';";
    $resultmod = mysql_query($querymod) or die("üzenet: " . mysql_error());
    $nummod = mysql_numrows($resultmod);
  
    $i=0;
    $optionmod = '';
    while ($i < $num)
    {
      $intazon_a = mysql_result($result,$i,"intazon");
      $jelol = " ";
      $j=0;
      while (($j < $nummod) and ($jelol ==" "))
      {
        $intazon_jelolni = mysql_result($resultmod,$j,"mit");
        if ($intazon_jelolni==$intazon_a)
        {  // jelölni kell, mert megvan
          $jelol = " selected";
        }
        $j++;
      }
      $optionmod .= "<option value=$intazon_a" . $jelol . "> " . "$intazon_a</option>";
      $i++;
    }
  }
  $optionment = $option;
  $optionmodment = $optionmod;
  echo "    <TD>Hatályon kívül helyezi a következő intézkedéseket:
            <TD><select multiple NAME=\"hathelyez[]\" size=\"10\">
            $option
            </select></TD>
        <TD>Módosítja a következő intézkedéseket:
            <TD><select multiple NAME=\"mitmodosit[]\" size=\"10\">
            $optionmod
            </select><TD><TR>";
  echo "<TD>Kulcsszavak (Vesszővel, szóközzel!): <TD colspan=3><INPUT TYPE=\"text\" NAME=\"kulcsszo\" id=\"kulcsszo\" size=80 VALUE=\"$kulcsszo\"></TD>
          <TD></TD><TR>";


  switch ($tevekenyseg)
  {
    case 'Feltöltés':
    {
      echo "  <td>A html elérési útja:</td><td colspan='3'><input onblur=\"htmlnevEll();\" type=\"file\" size=\"80\" name=\"htmlnev\" id=\"htmlnev\" value=\"$htmlnev\"></td>
                <TD id='tdhtmlnev' style='display:none;'></TD><TR>
              <TD>Eredeti fájl elérési útja: <TD colspan='3'><INPUT onblur=\"pdfnevEll();\" TYPE=\"file\" size=\"80\" NAME=\"pdfnev\" id=\"pdfnev\" VALUE=\"$pdfnev\"></TD>
                <TD id='tdpdfnev' style='display:none;'></TD></TR></table>";
      break;
    }
    case 'Módosítás':
    {
      echo "  <td>A jelenleg feltöltött html fájl:</td><td colspan=3><a href=$html_ut/$htmlnev>$htmlnev</a> (Ha cserélni szeretnéd, válassz másikat, egyébként hagyd üresen.)</td><tr>
              <td>Html cseréje:</td><td colspan=3><input type=\"file\" name=\"htmlnev\" size=\"80\" value=\"$htmlnev\"></td><tr>
              <TD>A jelenleg feltöltött eredeti fájl:</TD><TD colspan=3><a href=$html_ut/$pdfnev>$pdfnev</a> (Ha cserélni szeretnéd, válassz másikat, egyébként hagyd üresen.)</td><tr>
              <td>Eredeti fájl cseréje</td><td  colspan=3><input type=\"file\" name=\"pdfnev\" size=\"80\" value=\"$pdfnev\"></td><tr>";

      $query = "SELECT * FROM mellekletek WHERE mihez='intézkedés' AND mihez_azon='$intazon';";
      $result = mysql_query($query) or die("üzenet: " . mysql_error());
      $num = mysql_numrows($result);
      if ($num>0)
      {
        $i=0;
        echo "<td>Eddigi mellékletek:</td><td>";
        while ($i < $num)
        {
          $mellnev = mysql_result($result,$i,"mellnev");
          echo "<a href='$mell_ut/$mellnev'>$mellnev</a><a href='inttorol.php?intazon=$intazon&mellnev=$mellnev'><img src=\"img/torol.png\" alt=\"Törlés\" width=\"16\" height=\"16\" border=\"0\" /></a><BR>";
          $i++;
        }
      }
      echo "</td></tr></table>";
      break;
    }
  }
  echo "<TABLE id='desc'><tbody>
        <TR><TD width=$oszlop1>Mellékletek feltöltése: </TD>
        <TD><INPUT TYPE='file' size=\"80\" name=\"mell[]\" id=\"mell[]\">
        <input type='button' name='megegy' value='Még egy...' onClick='writeText();'></TD></TR></tbody></TABLE>";

  echo "<input type='hidden' name='tevekenyseg' value='$tevekenyseg'>
        <input type='hidden' name='intazon' value='$intazon'>
        <INPUT TYPE=\"button\" VALUE=\"$tevekenyseg\" NAME=\"feltoltgomb\" id=\"feltoltgomb\" onClick=\"kuld()\">
        </FORM>";
  echo "<A HREF=intezkedesek.php>Böngészés</A>";
?>
</body></html>