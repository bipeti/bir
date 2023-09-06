<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $hirjog = 0;
  $szint = '';
  if (isset($_SESSION['felhnev']))
  {             // Csak, ha be vagyunk jelentkezve és szerkesztésrõl van szó!
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Hírek';";
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
    {  // A belépett szmélynek van joga a hírek-re és szerkeszt...
      $hirazon = $_GET['hirazon'];
      $query = "SELECT * FROM hirek WHERE hirazon='$hirazon' and felhnev='$felhnev';"; // Az adott hír feltöltõje lépett-e be...
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
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-2">
  <title>BIR - Hírek, aktualitások</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript">
  var hircime=false;
  var hircimjo=false;
  var meddige=false;
  var meddigjo=false;
  var leirase=false;
  var leirasjo=false;

  function hircimEll()
  {
    hircime=true;
    if (document.getElementById("hircim").value=="")
    {
      hircimjo=false;
      document.getElementById("tdhircim").innerHTML="<font color='red'>Kötelezõ kitölteni!</font>";
      document.getElementById("tdhircim").style.display="inline";
    }
    else
    {
      hircimjo=true;
      document.getElementById("tdhircim").style.display="none";
    }
  }
  function meddigEll()
  {
    meddige=true;
    if (document.getElementById("meddig").value=="")
    {
      meddigjo=false;
      document.getElementById("tdmeddig").innerHTML="<font color='red'>Kötelezõ kitölteni!</font>";
      document.getElementById("tdmeddig").style.display="inline";
    }
    else
    {
      if (datecheck(document.getElementById("meddig").value))
      {  // Ha a dátum típusa jó
        meddigjo=true;
        document.getElementById("tdmeddig").style.display="none";
      }
      else
      {
        meddigjo=false;
        document.getElementById("tdmeddig").innerHTML="<font color='red'>Nem megfelelõ formátum! ÉÉÉÉ-HH-NN</font>";
        document.getElementById("tdmeddig").style.display="inline";
      }
    }
  }
  function leirasEll()
  {
    leirase=true;
    if (document.getElementById("leiras").value=="")
    {
      leirasjo=false;
      document.getElementById("tdleiras").innerHTML="<font color='red'>Kötelezõ kitölteni!</font>";
      document.getElementById("tdleiras").style.display="inline";
    }
    else
    {
      leirasjo=true;
      document.getElementById("tdleiras").style.display="none";
    }
  }

  function kuld()
  {
    if (hircime==false)
    {
      hircimEll();
    }
    if (meddige==false)
    {
      meddigEll();
    }
    if (leirase==false)
    {
      leirasEll();
    }
    if (hircimjo && meddigjo && leirasjo)
    {
      document.getElementById("felvitel").submit();
    }
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
    inp.size="55"
    var szoveg=document.createTextNode("Mellékletek feltöltése: ");
    td1.appendChild(szoveg);
    td2.appendChild(inp);
    tr.appendChild(td1);
    tr.appendChild(td2);
    tb.appendChild(tr);
  }
  </script>
</head>
<body>

<?php
  echo "<iframe id='controll' frameborder='0' width='0' height='0' style='display:none;'></iframe>";
  include "teteje.php";
  include "bal.php";

  echo '<div id="primaryContent">
      <h2>Hírek, aktualitások</h2>';

  echo "<a href = \"javascript:windowHandle = window.open('help/hirsugo.html','windowname','width=800,height=300,location=yes'); windowHandle.focus();\">Használati útmutató</a>";

  if (isset($_GET['hirazon']))
  {   // Szerkesztésrõl van szó
    $tevekenyseg = 'Módosítás';
    $hirazon = $_GET['hirazon'];
    $query = "SELECT * FROM hirek WHERE hirazon='$hirazon';";
    $result = mysql_query($query) or die("üzenet: " . mysql_error());

    $hircim  = mysql_result($result,0,"hircim");
    $kiemelt = mysql_result($result,0,"kiemelt");
    $felhnev = mysql_result($result,0,"felhnev");
    $ido     = mysql_result($result,0,"ido");
    $meddig  = mysql_result($result,0,"meddig");
    $leiras  = mysql_result($result,0,"leiras");

    $readonly = 'readonly';
  } else
  {   // Új felvitel
    $tevekenyseg = 'Feltöltés';
    $query = "SELECT max(hirazon) as maxi FROM hirek;";
    $result = mysql_query($query) or die("üzenet: " . mysql_error());

    $hirazon = mysql_result($result,0,"maxi") + 1;
    $hircim  = '';
    $kiemelt = '';
    $felhnev = '';
    $ido     = '';
    $meddig  = '';
    $leiras  = '';
    $readonly = '';
  };

  echo "<FORM enctype='multipart/form-data' name='felvitel' id='felvitel' method='post' action='hiraction.php'>
        <TABLE border=0 width='100%'><tr height=0><th width=$hiroszlop1></th><th width=$oszlop2></th><th></th></tr><TR>
        <TD>Hír azonosító: </TD><TD><INPUT TYPE=\"text\" NAME=\"hirazon\" id=\"hirazon\" VALUE=\"$hirazon\" readonly></TD>
          <TD></TD><TR>
        <TD>A hír tárgya: <TD><INPUT TYPE=\"text\" onblur=\"hircimEll();\" NAME=\"hircim\" id=\"hircim\" VALUE=\"$hircim\" maxlength='40'></TD>
          <TD id='tdhircim' style='display:none;'></TD><TR>";

  // A kiemelthez a rendszergazdák bármit írhatnak, az adminisztrátorok csak nullát, vagy egyet.
  if ($szint=='rendszergazda')
  {
    echo "<TD>Kiemelt:</TD><TD><INPUT TYPE=\"text\" onblur=\"kiemeltEll();\" NAME=\"kiemelt\" id=\"kiemelt\" VALUE=\"$kiemelt\"></TD>
            <TD id='tdkiemelt' style='display:none;'></TD><TR>";
  }
  else
  {
    if ($kiemelt==0)
    {
      $selectnem = 'selected';
      $selectigen = '';
    }
    else
    {
      $selectnem = '';
      $selectigen = 'selected';
    }
    echo "<TD>Kiemelt:</TD><TD><select name='kiemelt' id=\"kiemelt\">
        <option value='0' $selectnem>Nem</option>
        <option value='1' $selectigen>Igen</option>
        </select></TD><TR>";
  }
  echo "<TD><ABBR title='Ezen a napon még megjelenik'>Hatályát veszti:</ABBR><TD><ABBR title='Ezen a napon még megjelenik'><INPUT TYPE=\"text\" onblur=\"meddigEll();\" NAME=\"meddig\" id=\"meddig\" VALUE=\"$meddig\"></ABBR></TD>
          <TD id='tdmeddig' style='display:none;'></TD></TR>
        <TD>Leírás:<TD><textarea NAME=\"leiras\" onblur=\"leirasEll();\" id=\"leiras\" rows=\"8\" cols=\"41\">$leiras</textarea></TD>
          <TD id='tdleiras' style='display:none;'></TD></TR>";

  if ($tevekenyseg=='Módosítás')
  {  // Feltöltött mellékletek
    $melllista = '';
    $querymell = "SELECT *
                  FROM mellekletek
                  WHERE mihez='hírek' and mihez_azon='$hirazon';";
    $resultmell = mysql_query($querymell) or die("üzenet: " . mysql_error());
    $nummell = mysql_numrows($resultmell);
    if ($nummell>0)
    {
      $i=0;
      while ($i < $nummell)
      {
        $mellnev = mysql_result($resultmell,$i,"mellnev");
        $melllista .= "<a href='$mell_ut/$mellnev'>$mellnev</a><a href='hirtorol.php?hirazon=$hirazon&mellnev=$mellnev'><img src=\"img/torol.png\" alt=\"Törlés\" width=\"16\" height=\"16\" border=\"0\" /></a><BR>";
        $i++;
      }
    }
    echo "<TR><TD>Feltöltött mellékletek:<TD>$melllista</TD></TR>";
  }
  echo "</TABLE>";
  echo "<TABLE id='desc'><tbody>
        <TR><TD width=$hiroszlop1>Mellékletek feltöltése: </TD>
        <TD><INPUT TYPE='file' size=\"55\" name=\"mell[]\" id=\"mell[]\">
        <input type='button' name='megegy' value='Még egy...' onClick='writeText();'></TD></TR></tbody></TABLE>";

  echo "<input type='hidden' name='tevekenyseg' value='$tevekenyseg'>
        <input type='hidden' name='felhnev' value='$felhnev'>
        <INPUT TYPE=\"button\" VALUE=\"$tevekenyseg\" NAME=\"feltoltgomb\" id=\"feltoltgomb\" onClick=\"kuld()\">";

  echo "  </FORM>";
  echo "</div>";

  include "alja.php";
?>
</body></html>