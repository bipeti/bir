<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<?php
  session_start();
  include "myconsts.php";
  $dokjog = 0;
  if (isset($_SESSION['felhnev']))
  {
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error());
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");
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
    if (document.getElementById("optelgomb").value=='Cser�l')
    {
      document.getElementById("optel").style.display='inline';
      document.getElementById("feltoltgomb").style.display='inline';
      document.getElementById("optelgomb").value='Elrejt';
    }
    else
    {
      document.getElementById("optel").style.display='none';
      document.getElementById("feltoltgomb").style.display='none';
      document.getElementById("optelgomb").value='Cser�l';
    }
  }
  function kuld()
  {
    if (document.getElementById("optel").value=='')
    {
      alert('El�sz�r v�lassz ki egy f�jlt!');
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
          <li><a href="'. $dok_ut . '/nyilatkozat masodallas.doc">Nyilatkozat m�sod�ll�s</a></li>
          <li><a href='. $dok_ut . '/pdf-dok.doc>Seg�dlet: Dokumentum l�trehoz�sa PDF form�tumban</a></li>
          <li><a href="'. $dok_ut . '/FAR valtozasok/?C=M;O=D" target=_blank>Fogvatartotti Alrendszer v�ltoz�sai</a></li>
	  <li><a href="'. $dok_ut . '/FAR fogvatartotti kezikonyv/" target=_blank>Fogvatartotti Alrendszer k�zik�nyve</a></li><BR>	
          <li><a>�tik�lts�ges</a></li>';

  $opjegyzek = "<a href='$dok_ut/optelefon.doc' title='Felt�lt�tte: Jaczenk� Edit. Ha tudtok olyan v�ltoz�sr�l, amit szerepeltetni kell a dokumentumban, akkor azt fel� jelezz�tek.'>BVOP �s az int�zetek telefonjegyz�ke</a> <abbr title='Felt�lt�tte: Jaczenk� Edit. Ha tudtok olyan v�ltoz�sr�l, amit szerepeltetni kell a dokumentumban, akkor azt fel� jelezz�tek.'>(JE)</abbr>";

  if ($dokjog==1)
  {
    echo "<FORM enctype='multipart/form-data' name='optelfeltolt' id='optelfeltolt' method='post' action='docaction.php'>
          <li>$opjegyzek
          <input type=button value=Cser�l name=optelgomb id=optelgomb onClick=optelkuld()><BR>
          <input type=file name=\"optel\" id= \"optel\" style='display:none;'>
          <INPUT TYPE=\"button\" VALUE=\"Felt�lt\" NAME=\"feltoltgomb\" id=\"feltoltgomb\" onClick=\"kuld()\" style='display:none;'>
          <input type='hidden' name='mit' value='optel'>
          </form>";
  }
  else
  {
    echo "<li>$opjegyzek";
  }


  echo '  </li><li><a href='. $dok_ut . '/levelminta.doc>Lev�lminta</a></li>
          <li><a>K�relmi lap</a></li><BR>
          <li><a href='. $dok_ut . '/felelosen.doc>Felel�sen, felk�sz�lten program</a></li>
          <li><a href='. $dok_ut . '/kodex.doc>A b�ntet�s-v�grehajt�si szervezet Etikai K�dexe</a></li>
          <li><a href='. $dok_ut . '/szaketika.doc>Szaketika</a></li>
          <li><a href='. $dok_ut . '/adatestitok.zip>Adat- �s titokv�delmi tov�bbk�pz�s anyagai</a></li>
          <li><a href='. $dok_ut . '/ugykezelo.zip>�gykezel�i tov�bbk�pz�s anyagai</a></li><BR>

          <li><a href='. $dok_ut . '/intezet-bemutato.ppt>Int�zeti bemutat�</a></li>
        </ul>
      </div>';


  include "alja.php";
?>
