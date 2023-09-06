<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $dokjog = 0;
  if (isset($_SESSION['felhnev']))
  {
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");
    $felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Dokumentumok' and szint='op-telefon';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
      // Nincs joga
      $dokjog = 0;
    else
    { // Van joga
      $dokjog = 1;
    }
  }
?>
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>BIR - Dokumentumok</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript">
  function optelkuld()
  {
    if (document.getElementById("optelgomb").value=='Cserél')
    {
      document.getElementById("optel").style.display='inline';
      document.getElementById("feltoltgomb").style.display='inline';
      document.getElementById("optelgomb").value='Elrejt';
    }
    else
    {
      document.getElementById("optel").style.display='none';
      document.getElementById("feltoltgomb").style.display='none';
      document.getElementById("optelgomb").value='Cserél';
    }
  }
  function kuld()
  {
    if (document.getElementById("optel").value=='')
    {
      alert('Először válassz ki egy fájlt!');
    }
    else
        document.getElementById("optelfeltolt").submit();
  }

  </script>
</head>
<body>

<?php
  include "teteje.php";
  include "bal.php";

  echo '
      <div id="primaryContent">
        <h2>Dokumentumok</h2>
        <ul>
          <li><a href="'. $dok_ut . '/nyilatkozat masodallas.doc">Nyilatkozat másodállás</a></li>
          <li><a href='. $dok_ut . '/pdf-dok.doc>Segédlet: Dokumentum létrehozása PDF formátumban</a></li>
          <li><a href="'. $dok_ut . '/FAR valtozasok/?C=M;O=D" target=_blank>Fogvatartotti Alrendszer változásai</a></li>
	  <li><a href="'. $dok_ut . '/FAR fogvatartotti kezikonyv/" target=_blank>Fogvatartotti Alrendszer kézikönyve</a></li><BR>	
          <li><a>Útiköltséges</a></li>';

  $opjegyzek = "<a href='$dok_ut/optelefon.doc' title='Feltöltötte: Jaczenkó Edit. Ha tudtok olyan változásról, amit szerepeltetni kell a dokumentumban, akkor azt felé jelezzétek.'>BVOP és az intézetek telefonjegyzéke</a> <abbr title='Feltöltötte: Jaczenkó Edit. Ha tudtok olyan változásról, amit szerepeltetni kell a dokumentumban, akkor azt felé jelezzétek.'>(JE)</abbr>";

  if ($dokjog==1)
  {
    echo "<FORM enctype='multipart/form-data' name='optelfeltolt' id='optelfeltolt' method='post' action='docaction.php'>
          <li>$opjegyzek
          <input type=button value=Cserél name=optelgomb id=optelgomb onClick=optelkuld()><BR>
          <input type=file name=\"optel\" id= \"optel\" style='display:none;'>
          <INPUT TYPE=\"button\" VALUE=\"Feltölt\" NAME=\"feltoltgomb\" id=\"feltoltgomb\" onClick=\"kuld()\" style='display:none;'>
          <input type='hidden' name='mit' value='optel'>
          </form>";
  }
  else
  {
    echo "<li>$opjegyzek";
  }


  echo '  </li><li><a href='. $dok_ut . '/levelminta.doc>Levélminta</a></li>
          <li><a>Kérelmi lap</a></li><BR>
          <li><a href='. $dok_ut . '/felelosen.doc>Felelősen, felkészülten program</a></li>
          <li><a href='. $dok_ut . '/kodex.doc>A büntetés-végrehajtási szervezet Etikai Kódexe</a></li>
          <li><a href='. $dok_ut . '/szaketika.doc>Szaketika</a></li>
          <li><a href='. $dok_ut . '/adatestitok.zip>Adat- és titokvédelmi továbbképzés anyagai</a></li>
          <li><a href='. $dok_ut . '/ugykezelo.zip>Ügykezelői továbbképzés anyagai</a></li><BR>

          <li><a href='. $dok_ut . '/intezet-bemutato.ppt>Intézeti bemutató</a></li>
        </ul>
      </div>';


  include "alja.php";
?>
