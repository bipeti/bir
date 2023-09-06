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
    var szoveg=document.createTextNode("Mell�kletek felt�lt�se: ");
    td1.appendChild(szoveg);
    td2.appendChild(inp);
    tr.appendChild(td1);
    tr.appendChild(td2);
    tb.appendChild(tr);
  }

  // �rlap kit�lt�s�nek ellen�rz�se

  function evEll()
  {
    eve=true;
    if (document.getElementById("ev").value=="")
    {
      evjo=false;
      document.getElementById("tdev").innerHTML="<font color='red'>K�telez� kit�lteni!</font>";
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
      document.getElementById("tdsorszam").innerHTML="<font color='red'>K�telez� kit�lteni!</font>";
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
      document.getElementById("tdintcim").innerHTML="<font color='red'>K�telez� kit�lteni!</font>";
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
      document.getElementById("tdhatlep").innerHTML="<font color='red'>K�telez� kit�lteni!</font>";
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
        document.getElementById("tdhatlep").innerHTML="<font color='red'>Nem megfelel� form�tum! ����-HH-NN</font>";
        document.getElementById("tdhatlep").style.display="inline";
      }
    }
  }
  function hatvesztEll()
  {
    if (document.getElementById("hatveszt").value=="")
    {
      hatvesztjo=true;   // Ez most akkor is j�, ha �res
      document.getElementById("tdhatveszt").style.display="none";
    }
    else
    {
      if (datecheck(document.getElementById("hatveszt").value))
      {  // Ha a d�tum t�pusa j�
        if (document.getElementById("hatveszt").value>=document.getElementById("hatlep").value)
        {
          hatvesztjo=true;
          document.getElementById("tdhatveszt").style.display="none";
        }
        else
        {
          hatvesztjo=false;
          document.getElementById("tdhatveszt").innerHTML="<font color='red'>A hat�lyveszt�s d�tuma nem lehet kor�bbi, mint a hat�lyba l�p�s�!</font>";
          document.getElementById("tdhatveszt").style.display="inline";
        }
      }
      else
      {
        hatvesztjo=false;
        document.getElementById("tdhatveszt").innerHTML="<font color='red'>Nem megfelel� form�tum! ����-HH-NN</font>";
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
      document.getElementById("tdhtmlnev").innerHTML="<font color='red'>K�telez� kit�lteni!</font>";
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
      document.getElementById("tdpdfnev").innerHTML="<font color='red'>K�telez� kit�lteni!</font>";
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

    if (document.getElementById("feltoltgomb").value=="Felt�lt�s")
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
    { // m�dos�t�s
      htmlnevjo=true;
      pdfnevjo=true;
    }
    if (evjo && sorszamjo && intcimjo && hatlepjo && htmlnevjo && pdfnevjo && hatvesztjo)
    { // Ezeken a helyeken m�r j�rtunk �s mindegyik j� is, teh�t mehet php-s ellen�rz�sre
    //&& (phpell==false)
      phpell=true;
      if (document.getElementById("feltoltgomb").value=="Felt�lt�s")
      {
        document.getElementById('controll').src='check.php?ev='+document.getElementById("ev").value+'&sorszam='+document.getElementById("sorszam").value+'&htmlnev='+document.getElementById("htmlnev").value;
      }
      else
      {
        document.getElementById('controll').src='check.php?ev='+document.getElementById("ev").value+'&sorszam='+document.getElementById("sorszam").value;
      }
    }
  }

  // �rlap kit�lt�se ellen�rz�s�nek a v�ge

  </script>
</head>
<body>
<?php
  echo "<iframe id='controll' frameborder='0' width='0' height='0' style='display:none;'></iframe>";
  $tevekenyseg = 'Felt�lt�s';
  if (isset($_GET['intazon']))
  {   // Szerkeszt�sr�l van sz� 
    $tevekenyseg = 'M�dos�t�s';
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

    $querytag = "SELECT * FROM tags WHERE mihez='int�zked�s' AND mihez_azon='$intazon'";
    $resulttag = mysql_query($querytag) or die("�zenet: " . mysql_error());
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
  {   // �j felvitel
    $queryev = "SELECT MAX(ev) as maxev FROM intezkedesek;";
    $resultev = mysql_query($queryev) or die("�zenet: " . mysql_error());
    $numev = mysql_numrows($resultev);
    if ($numev>0)
    {
      $ev = mysql_result($resultev,0,"maxev");
    } else $ev = '';

    $query = "SELECT MAX(sorszam) as maxi FROM intezkedesek WHERE ev='$ev';";
    $result = mysql_query($query) or die("�zenet: " . mysql_error());
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
        <TD>�v: </TD><TD><INPUT onblur=\"evEll();\" TYPE=\"text\" NAME=\"ev\" id=\"ev\" VALUE=\"$ev\" $readonly></TD>
          <TD id='tdev' style='display:none;'></TD><TR>
        <TD>Sorsz�m: <TD><INPUT onblur=\"sorszamEll();\" TYPE=\"text\" NAME=\"sorszam\" id=\"sorszam\" VALUE=\"$sorszam\" $readonly></TD>
          <TD id='tdsorszam' style='display:none;'></TD><TR>
        <TD>Az int�zked�s t�rgya:</TD><TD colspan='3'><INPUT TYPE=\"text\" size=\"80\" onblur=\"intcimEll();\" NAME=\"intcim\" id=\"intcim\" VALUE=\"$intcim\"></TD>
          <TD id='tdintcim' style='display:none;'></TD><TR>
        <TD>Hat�lybal�p�s d�tuma: <TD><INPUT onblur=\"hatlepEll();\" TYPE=\"text\" NAME=\"hatlep\" id=\"hatlep\" VALUE=\"$hatlep\"></TD>
          <TD id='tdhatlep' style='display:none;'></TD><TR>
        <TD>Hat�ly�t veszti: <TD><INPUT onblur=\"hatvesztEll();\" TYPE=\"text\" NAME=\"hatveszt\" id=\"hatveszt\" VALUE=\"$hatveszt\"></TD>
          <TD id='tdhatveszt' style='display:none;'></TD><TR>";

  $query = "SELECT intazon FROM intezkedesek;";
  $result = mysql_query($query) or die("�zenet: " . mysql_error());
  $num = mysql_numrows($result);

  // Ha hat�lyon k�v�l helyez valamit, akkor jelen�ts�k meg, hogy mit vizsg�lat:
  $queryhat = "SELECT intazon FROM intezkedesek WHERE hathelyez='$intazon';";
  $resulthat = mysql_query($queryhat) or die("�zenet: " . mysql_error());
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
      {  // jel�lni kell, mert megvan
        $jelol = " selected";
      }
      $j++;
    }
    $option .= "<option value=$intazon_a" . $jelol . "> " . "$intazon_a</option>";
    $i++;
  }

  if ($tevekenyseg == 'Felt�lt�s')
  {   // Felvitelr�l van sz�
    $optionmod=$option;
  }
  else
  {
    // Ha m�dos�t valamit, akkor jelen�ts�k meg, hogy mit vizsg�lat:
    $querymod = "SELECT mit FROM modositasok WHERE mi='$intazon';";
    $resultmod = mysql_query($querymod) or die("�zenet: " . mysql_error());
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
        {  // jel�lni kell, mert megvan
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
  echo "    <TD>Hat�lyon k�v�l helyezi a k�vetkez� int�zked�seket:
            <TD><select multiple NAME=\"hathelyez[]\" size=\"10\">
            $option
            </select></TD>
        <TD>M�dos�tja a k�vetkez� int�zked�seket:
            <TD><select multiple NAME=\"mitmodosit[]\" size=\"10\">
            $optionmod
            </select><TD><TR>";
  echo "<TD>Kulcsszavak (Vessz�vel, sz�k�zzel!): <TD colspan=3><INPUT TYPE=\"text\" NAME=\"kulcsszo\" id=\"kulcsszo\" size=80 VALUE=\"$kulcsszo\"></TD>
          <TD></TD><TR>";


  switch ($tevekenyseg)
  {
    case 'Felt�lt�s':
    {
      echo "  <td>A html el�r�si �tja:</td><td colspan='3'><input onblur=\"htmlnevEll();\" type=\"file\" size=\"80\" name=\"htmlnev\" id=\"htmlnev\" value=\"$htmlnev\"></td>
                <TD id='tdhtmlnev' style='display:none;'></TD><TR>
              <TD>Eredeti f�jl el�r�si �tja: <TD colspan='3'><INPUT onblur=\"pdfnevEll();\" TYPE=\"file\" size=\"80\" NAME=\"pdfnev\" id=\"pdfnev\" VALUE=\"$pdfnev\"></TD>
                <TD id='tdpdfnev' style='display:none;'></TD></TR></table>";
      break;
    }
    case 'M�dos�t�s':
    {
      echo "  <td>A jelenleg felt�lt�tt html f�jl:</td><td colspan=3><a href=$html_ut/$htmlnev>$htmlnev</a> (Ha cser�lni szeretn�d, v�lassz m�sikat, egy�bk�nt hagyd �resen.)</td><tr>
              <td>Html cser�je:</td><td colspan=3><input type=\"file\" name=\"htmlnev\" size=\"80\" value=\"$htmlnev\"></td><tr>
              <TD>A jelenleg felt�lt�tt eredeti f�jl:</TD><TD colspan=3><a href=$html_ut/$pdfnev>$pdfnev</a> (Ha cser�lni szeretn�d, v�lassz m�sikat, egy�bk�nt hagyd �resen.)</td><tr>
              <td>Eredeti f�jl cser�je</td><td  colspan=3><input type=\"file\" name=\"pdfnev\" size=\"80\" value=\"$pdfnev\"></td><tr>";

      $query = "SELECT * FROM mellekletek WHERE mihez='int�zked�s' AND mihez_azon='$intazon';";
      $result = mysql_query($query) or die("�zenet: " . mysql_error());
      $num = mysql_numrows($result);
      if ($num>0)
      {
        $i=0;
        echo "<td>Eddigi mell�kletek:</td><td>";
        while ($i < $num)
        {
          $mellnev = mysql_result($result,$i,"mellnev");
          echo "<a href='$mell_ut/$mellnev'>$mellnev</a><a href='inttorol.php?intazon=$intazon&mellnev=$mellnev'><img src=\"img/torol.png\" alt=\"T�rl�s\" width=\"16\" height=\"16\" border=\"0\" /></a><BR>";
          $i++;
        }
      }
      echo "</td></tr></table>";
      break;
    }
  }
  echo "<TABLE id='desc'><tbody>
        <TR><TD width=$oszlop1>Mell�kletek felt�lt�se: </TD>
        <TD><INPUT TYPE='file' size=\"80\" name=\"mell[]\" id=\"mell[]\">
        <input type='button' name='megegy' value='M�g egy...' onClick='writeText();'></TD></TR></tbody></TABLE>";

  echo "<input type='hidden' name='tevekenyseg' value='$tevekenyseg'>
        <input type='hidden' name='intazon' value='$intazon'>
        <INPUT TYPE=\"button\" VALUE=\"$tevekenyseg\" NAME=\"feltoltgomb\" id=\"feltoltgomb\" onClick=\"kuld()\">
        </FORM>";
  echo "<A HREF=intezkedesek.php>B�ng�sz�s</A>";
?>
</body></html>