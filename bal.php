  <div id="content">
    <div id="xbg1"></div>
    <div id="primaryContentContainer">
    <div id="secondaryContent">
      <h3>Bejelentkez�s</h3>

<?php
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("�zenet: " . mysql_error()) ;
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatb�zishoz csatlakozni!");

  if (isset($_SESSION['felhnev']))
  {             // Be vagyunk jelentkezve
    $nev = $_SESSION['nev'];
    $felhnev = $_SESSION['felhnev'];
    echo "<p>�dv. $nev</p>";

// N�zz�k, hogy van-e jog helyi int�zked�sre...
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Helyi int�zked�sek';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
      $helyiintjog = 0;
    else
      $helyiintjog = 1;
    if ($helyiintjog == 1)
    {
      echo "<a href=intezkedes.php>�j int�zked�s <img src=\"img/uj.png\" alt='�j' border=\"0\" /></a><BR>";
    }
// N�zz�k, hogy van-e jog h�rekre
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='H�rek';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
      $hirjog = 0;
    else
      $hirjog = 1;
    if ($hirjog == 1)
    {
      echo "<a href=hir.php>�j h�r <img src=\"img/uj.png\" alt='�j' border=\"0\" /></a><BR>";
    }
// N�zz�k, hogy van-e jog felhaszn�l�kra
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Felhaszn�l�k';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
      $felhjog = 0;
    else
      $felhjog = 1;
    if ($felhjog == 1)
    {
      echo "<a href=felhasznalok.php>Felhaszn�l�k kezel�se <img src=\"img/uj.png\" alt='�j' border=\"0\" /></a><BR>";
    }



    echo "<BR><a href='profil.php'>Profil</a><BR>
             <a href='kilep.php'>Kijelentkez�s</a><BR><BR>";
  }
  else
  {
    $helyiintjog = 0;
    $hirjog = 0;
    $felhjog = 0;
    echo "<form enctype='multipart/form-data' action='./belep.php' method='post'>
      <img src=\"img/nev.png\" alt='Szerkeszt�s' border=\"0\" /> <input name='felhnev' type=text size=15>
      <img src=\"img/jelszo.png\" alt='Szerkeszt�s' border=\"0\" /> <input name='jelszo' type=password size=10>
      <input type=submit name='submit' value='�'></form><BR>";
  }
  echo "<h3>Kiemelt h�rek</h3>";
    $mainap = date("Y-m-d");
  $query = "SELECT *
            FROM hirek
            WHERE (kiemelt>0) AND (meddig >= '$mainap')
            ORDER BY kiemelt DESC, ido DESC;";
  $result = mysql_query($query) or die("�zenet: " . mysql_error());
  $num = mysql_numrows($result);
  $i=0;
  echo "<ul>";
  while ($i < $num)
  {
    $hirazon = mysql_result($result,$i,"hirazon");
    $hircim = mysql_result($result,$i,"hircim");
    echo '<li><a href="hirek.php?hirazon=' . $hirazon .'">' . $hircim . '</a></li>';
    $i++;
  }
  echo "</ul>";
?>
    </div>
