<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $felhjog = 0;
  $szint = '';
  if (isset($_SESSION['felhnev']))
  {             // Csak, ha ve vagyunk jelentkezve!
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Felhaszn�l�k';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num>0)
    {
      $szint = mysql_result($result,0,"szint");
      // Csak, ha rendszergazda �s joga is van
      if ($szint=='rendszergazda')
      {
        $felhjog = 1;
      }
    }
  }
  if ($felhjog == 0)
  {exit();}
?>
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-2">
  <title>BIR - Felhaszn�l�k</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript">
  var felhneve=false;
  var felhnevjo=false;
  var neve=false;
  var nevjo=false;

  function felhnevEll()
  {
    felhneve=true;
    if (document.getElementById("kapott_felhnev").value=="")
    {
      felhnevjo=false;
      document.getElementById("tdfelhnev").innerHTML="<font color='red'>K�telez� kit�lteni!</font>";
      document.getElementById("tdfelhnev").style.display="inline";
    }
    else
    {
      felhnevjo=true;
      document.getElementById("tdfelhnev").style.display="none";
    }
  }
  function nevEll()
  {
    neve=true;
    if (document.getElementById("nev").value=="")
    {
      nevjo=false;
      document.getElementById("tdnev").innerHTML="<font color='red'>K�telez� kit�lteni!</font>";
      document.getElementById("tdnev").style.display="inline";
    }
    else
    {
      nevjo=true;
      document.getElementById("tdnev").style.display="none";
    }
  }

  function kuld()
  {
    if (felhneve==false)
    {
      felhnevEll();
    }
    if (neve==false)
    {
      nevEll();
    }
    if (felhnevjo && nevjo)
    {
      document.getElementById("felvitel").submit();
    }
  }
  </script>
</head>
<body>

<?php
  echo "<iframe id='controll' frameborder='0' width='0' height='0' style='display:none;'></iframe>";
  include "teteje.php";
  include "bal.php";

  echo '<div id="primaryContent">
      <h2>Felhaszn�l�k</h2>';

  if (isset($_GET['felhnev']))
  {   // Szerkeszt�sr�l van sz�
    $tevekenyseg = 'M�dos�t�s';
    $kapott_felhnev = $_GET['felhnev'];
    $query = "SELECT * FROM felhasznalok WHERE felhnev='$kapott_felhnev';";
    $result = mysql_query($query) or die("�zenet: " . mysql_error());
    $nev = mysql_result($result,0,"nev");;

    $query = "SELECT * FROM jogosultsagok WHERE kinek='$kapott_felhnev' AND mire='Felhaszn�l�k';";
    $result = mysql_query($query) or die("�zenet: " . mysql_error());
    $num = mysql_numrows($result);
    if ($num > 0)
    {
      $felhjog = mysql_result($result,0,"szint");
      switch ($felhjog)
      {
        case 'rendszergazda':
        {
          $felhnincs = '';
          $felhrend = 'selected';
          break;
        }
      }
    }
    else
    {
      $felhnincs = 'selected';
      $felhrend = '';
    }

    $query = "SELECT * FROM jogosultsagok WHERE kinek='$kapott_felhnev' AND mire='Helyi int�zked�sek';";
    $result = mysql_query($query) or die("�zenet: " . mysql_error());
    $num = mysql_numrows($result);
    if ($num > 0)
    {
      $helyiintjog = mysql_result($result,0,"szint");
      switch ($helyiintjog)
      {
        case 'adminisztr�tor':
        {
          $helyinincs = '';
          $helyiadm = 'selected';
          break;
        }
      }
    }
    else
    {
      $helyinincs = 'selected';
      $helyiadm = '';
    }

    $query = "SELECT * FROM jogosultsagok WHERE kinek='$kapott_felhnev' AND mire='H�rek';";
    $result = mysql_query($query) or die("�zenet: " . mysql_error());
    $num = mysql_numrows($result);
    if ($num > 0)
    {
      $hirjog = mysql_result($result,0,"szint");
      switch ($hirjog)
      {
        case 'adminisztr�tor':
        {
          $hirnincs = '';
          $hiradm = 'selected';
          $hirrend = '';
          break;
        }
        case 'rendszergazda':
        {
          $hirnincs = '';
          $hiradm = '';
          $hirrend = 'selected';
          break;
        }
      }
    }
    else
    {
      $hirnincs = 'selected';
      $hiradm = '';
      $hirrend = '';
    }

    $readonly = 'readonly';
  } else
  {   // �j felvitel
    $tevekenyseg = 'Felt�lt�s';
    $kapott_felhnev  = '';
    $nev = '';
    $helyiintjog = '';
    $hirjog     = '';
    $felhjog  = '';

    $felhnincs = 'selected';
    $felhrend = '';
    $helyinincs = 'selected';
    $helyiadm = '';
    $hirnincs = 'selected';
    $hiradm = '';
    $hirrend = '';

    $readonly = '';
  };

  echo "<FORM enctype='multipart/form-data' name='felvitel' id='felvitel' method='post' action='felhaction.php'>
        <TABLE border=0 width='100%'><tr height=0><th width=$hiroszlop1></th><th width=$oszlop2></th><th></th></tr><TR>
        <TD>Felhaszn�l�n�v: </TD><TD><INPUT TYPE=\"text\" onblur=\"felhnevEll();\" NAME=\"kapott_felhnev\" id=\"kapott_felhnev\" VALUE=\"$kapott_felhnev\" $readonly></TD>
          <TD id='tdfelhnev' style='display:none;'></TD><TR>
        <TD>N�v: <TD><INPUT TYPE=\"text\" onblur=\"nevEll();\" NAME=\"nev\" id=\"nev\" VALUE=\"$nev\"></TD>
          <TD id='tdnev' style='display:none;'></TD><TR>
        <TD>Felhaszn�l�k kezel�se: </TD><TD><SELECT NAME=\"felhjog\" id=\"felhjog\" VALUE=\"$felhjog\">
                <option value='' $felhnincs>Nincs</option>
                <option value='rendszergazda' $felhrend>Rendszergazda</option>
                </select></TD>
          <TD></TD><TR>
        <TD>Helyi int�zked�sek: </TD><TD><SELECT NAME=\"helyiintjog\" id=\"helyiintjog\" VALUE=\"$helyiintjog\">
                <option value='' $helyinincs>Nincs</option>
                <option value='adminisztr�tor' $helyiadm>Adminisztr�tor</option>
                </select></TD>
          <TD></TD><TR>
        <TD>H�rek: </TD><TD><SELECT NAME=\"hirjog\" id=\"hirjog\" VALUE=\"$hirjog\">
                <option value='' $hirnincs>Nincs</option>
                <option value='adminisztr�tor' $hiradm>Adminisztr�tor</option>
                <option value='rendszergazda' $hirrend>Rendszergazda</option>
                </select></TD>
          <TD></TD></TABLE>";
  if ($tevekenyseg =='M�dos�t�s')
    echo "<a href='felhjelszo.php?felhnev=$kapott_felhnev'>Jelsz� m�dos�t�s�hoz katt. ide</a><BR>";

  echo "<input type='hidden' name='tevekenyseg' value='$tevekenyseg'>
        <input type='hidden' name='felhnev' value='$felhnev'>
        <INPUT TYPE=\"button\" VALUE=\"$tevekenyseg\" NAME=\"feltoltgomb\" id=\"feltoltgomb\" onClick=\"kuld()\">";

  echo "  </FORM>";
  echo "</div>";

  include "alja.php";
?>
</body></html>