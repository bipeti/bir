<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $hirjog = 0;
  if (isset($_SESSION['felhnev']))
  {             // Be vagyunk jelentkezve
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Hírek';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
      $hirjog = 0;
    else
      $hirjog = 1;
  }
  if ($hirjog == 0)
  {exit();}
?>
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>BIR - Hírek, aktualitások</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript">
  function nem()
  {
    document.location.replace("hirek.php");
  }
  </script>

<?php
  include "teteje.php";
  include "bal.php";
    echo '<div id="primaryContent">
      <h2>Hírek, aktualitások</h2>';

//   if ((isset($_GET['intazon'])) and (!(isset($_GET['mellnev']))))

  if ((isset($_GET['hirazon'])) and (!(isset($_GET['mellnev']))))
  {   // Hír törléséről van szó
    $hirazon = $_GET['hirazon'];
    $query = "SELECT * FROM hirek WHERE hirazon='$hirazon';";
    $result = mysql_query($query) or die("üzenet: " . mysql_error());

    $hircim  = mysql_result($result,0,"hircim");
    $kiemelt = mysql_result($result,0,"kiemelt");
    $felhnev = mysql_result($result,0,"felhnev");
    $ido     = mysql_result($result,0,"ido");
    $meddig  = mysql_result($result,0,"meddig");
    $leiras  = mysql_result($result,0,"leiras");

    echo "<TABLE border=0 width='100%'><tr height=0><th width=$hiroszlop1></th><th width=$oszlop2></th><th></th></tr><TR>
        <TD>Hír azonosító: </TD><TD><INPUT TYPE=\"text\" NAME=\"hirazon\" id=\"hirazon\" VALUE=\"$hirazon\" readonly></TD>
          <TD></TD><TR>
        <TD>A hír tárgya: <TD><INPUT TYPE=\"text\" onblur=\"hircimEll();\" NAME=\"hircim\" id=\"hircim\" VALUE=\"$hircim\" readonly></TD>
          <TD id='tdhircim' style='display:none;'></TD><TR>
        <TD>Kiemelt:</TD><TD><INPUT TYPE=\"text\" onblur=\"kiemeltEll();\" NAME=\"kiemelt\" id=\"kiemelt\" VALUE=\"$kiemelt\" readonly></TD>
          <TD id='tdkiemelt' style='display:none;'></TD><TR>
        <TD>Hatályát veszti:<TD><INPUT TYPE=\"text\" onblur=\"meddigEll();\" NAME=\"meddig\" id=\"meddig\" VALUE=\"$meddig\" readonly></TD>
          <TD id='tdmeddig' style='display:none;'></TD><TR>
        <TD>Leírás:<TD><textarea NAME=\"leiras\" onblur=\"leirasEll();\" id=\"leiras\" rows=\"8\" cols=\"41\" readonly>$leiras</textarea></TD>
          <TD id='tdleiras' style='display:none;'></TD></TR></TABLE>
      <BR>";

    echo "<FORM name='torol' method='post' action='hiraction.php'><center><b>Biztosan törölni kívánja?</b><BR>
      <input type='hidden' name='tevekenyseg' value='Törlés'>
      <input type='hidden' name='hirazon' value='$hirazon'>
      <INPUT TYPE=\"submit\" VALUE=\"Igen\" NAME=\"gomb\"><INPUT TYPE=\"button\" VALUE=\"Nem\" NAME=\"gomb\" onClick=\"nem();\"></center>
      </FORM>";
  }
  elseif ((isset($_GET['hirazon'])) and (isset($_GET['mellnev'])))
  {  // Melléklet törléséről van szó
    $hirazon = $_GET['hirazon'];
    $mellnev = $_GET['mellnev'];
    unlink('./' . $mell_ut . '/' . $mellnev);
    $query = "DELETE FROM mellekletek WHERE mihez='hírek' AND mihez_azon='$hirazon' and mellnev='$mellnev';";
    $result = mysql_query($query) or die("üzenet: " . mysql_error());
    header('Location: hir.php?hirazon=' . $hirazon);
  }
  else
  { // nincs kitöltve a GET_intazon, kívülről jöttek
    echo "kukucs";
  }
  echo "<BR><center><A HREF=hirek.php>Böngészés</A></center>";
  echo "</div>";
  include "alja.php";
?>
</body></html>