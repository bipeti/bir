  <div id="content">
    <div id="xbg1"></div>
    <div id="primaryContentContainer">
    <div id="secondaryContent">
      <h3>Bejelentkezés</h3>

<?php
    $kapcsolat = mysql_connect($mysql_serverurl, $username, $password) or die("üzenet: " . mysql_error()) ;
    @mysql_select_db($mysql_database) or die( "Nem lehet az adatbázishoz csatlakozni!");

  if (isset($_SESSION['felhnev']))
  {             // Be vagyunk jelentkezve
    $nev = $_SESSION['nev'];
    $felhnev = $_SESSION['felhnev'];
    echo "<p>Üdv. $nev</p>";

// Nézzük, hogy van-e jog helyi intézkedésre...
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Helyi intézkedések';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
      $helyiintjog = 0;
    else
      $helyiintjog = 1;
    if ($helyiintjog == 1)
    {
      echo "<a href=intezkedes.php>Új intézkedés <img src=\"img/uj.png\" alt='Új' border=\"0\" /></a><BR>";
    }
// Nézzük, hogy van-e jog hírekre
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Hírek';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
      $hirjog = 0;
    else
      $hirjog = 1;
    if ($hirjog == 1)
    {
      echo "<a href=hir.php>Új hír <img src=\"img/uj.png\" alt='Új' border=\"0\" /></a><BR>";
    }
// Nézzük, hogy van-e jog felhasználókra
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$felhnev' AND mire='Felhasználók';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num==0)
      $felhjog = 0;
    else
      $felhjog = 1;
    if ($felhjog == 1)
    {
      echo "<a href=felhasznalok.php>Felhasználók kezelése <img src=\"img/uj.png\" alt='Új' border=\"0\" /></a><BR>";
    }



    echo "<BR><a href='profil.php'>Profil</a><BR>
             <a href='kilep.php'>Kijelentkezés</a><BR><BR>";
  }
  else
  {
    $helyiintjog = 0;
    $hirjog = 0;
    $felhjog = 0;
    echo "<form enctype='multipart/form-data' action='./belep.php' method='post'>
      <img src=\"img/nev.png\" alt='Szerkesztés' border=\"0\" /> <input name='felhnev' type=text size=15>
      <img src=\"img/jelszo.png\" alt='Szerkesztés' border=\"0\" /> <input name='jelszo' type=password size=10>
      <input type=submit name='submit' value='»'></form><BR>";
  }
  echo "<h3>Kiemelt hírek</h3>";
    $mainap = date("Y-m-d");
  $query = "SELECT *
            FROM hirek
            WHERE (kiemelt>0) AND (meddig >= '$mainap')
            ORDER BY kiemelt DESC, ido DESC;";
  $result = mysql_query($query) or die("üzenet: " . mysql_error());
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
