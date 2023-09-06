<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>BIR - Hírek, aktualitások</title>
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

  echo '<div id="primaryContent">
      <h2>Hírek, aktualitások</h2>';

  if (isset($_SESSION['felhnev']))
  {             // Be vagyunk jelentkezve
    $belepett_felhnev = $_SESSION['felhnev'];
    $query = "SELECT * FROM jogosultsagok WHERE kinek='$belepett_felhnev' AND mire='Hírek';";
    $result = mysql_query($query) or die("U:" . mysql_error());
    $num = mysql_numrows($result);
    if ($num>0)
    {
      $szint = mysql_result($result,0,"szint");
    }
    else
    {
      $szint = '';
    }
  }
  if (isset($_GET['hirazon']))
  {  // Egy konkrét hír
    $hirazon = $_GET['hirazon'];
    $query = "SELECT *
              FROM hirek, felhasznalok
              WHERE hirek.felhnev=felhasznalok.felhnev and hirazon='$hirazon';";
    $result = mysql_query($query) or die("üzenet: " . mysql_error());
    $num = mysql_numrows($result);

    if ($num==0)
    {
      echo "Nem létező hír-azonosító.";
    }
    else
    {
      $hircim = mysql_result($result,0,"hircim");
      $kiemelt = mysql_result($result,0,"kiemelt");
      $felhnev = mysql_result($result,0,"felhnev");
      $nev = mysql_result($result,0,"nev");
      $ido = mysql_result($result,0,"ido");
      $meddig = mysql_result($result,0,"meddig");
      $leiras = nl2br(mysql_result($result,0,"leiras"));
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
          $melllista .= "<a href='$mell_ut/$mellnev'>$mellnev</a><BR>";
          $i++;
        }
      }

      $ikonok = '';
      if (isset($_SESSION['felhnev']))
      {             // Be vagyunk jelentkezve
        if ($szint=='rendszergazda')
        {
          $ikonok = "<a href=hir.php?hirazon=$hirazon><img src=\"img/edit.png\" alt=\"Szerkesztés\" width=\"16\" height=\"16\" border=\"0\" /></a>
                     <a href=hirtorol.php?hirazon=$hirazon><img src=\"img/torol.png\" alt=\"Törlés\" width=\"16\" height=\"16\" border=\"0\" /></a>";
        }
        elseif ($szint=='adminisztrátor')
        {
          if ($felhnev==$belepett_felhnev)
          {
            $ikonok = "<a href=hir.php?hirazon=$hirazon><img src=\"img/edit.png\" alt=\"Szerkesztés\" width=\"16\" height=\"16\" border=\"0\" /></a>
                       <a href=hirtorol.php?hirazon=$hirazon><img src=\"img/torol.png\" alt=\"Törlés\" width=\"16\" height=\"16\" border=\"0\" /></a>";
          }
        }
      }

      echo     '<div class="post">
  			<p class="title"><a href="#">' . $hircim . '</a>' . $ikonok . '</p>
  			<p class="byline"><small>' .  $nev . ' ' . $ido . '</small></p>
  			<div class="entry">
  				<p>' . $leiras . '</p>
  			</div>';
     if (!($melllista==''))
     {
       echo "<b>Kapcsolódó anyagok:</b> <BR> $melllista";
     }
     echo '</div>';  // A 'post' záró divje
    }
  }
  else
  {  // lista
    $mainap = date("Y-m-d");

    // A lista előtt megjelenítjük a lejárt híreket azoknak, akiknek kell...
    if (isset($_SESSION['felhnev']))
    {             // Be vagyunk jelentkezve
      if ($szint=='rendszergazda')
      {  // minden lejárt hír érdekel
        $query1 = "SELECT hirazon,hircim,kiemelt,hirek.felhnev,nev,ido,meddig
                  FROM hirek, felhasznalok
                  WHERE (hirek.felhnev=felhasznalok.felhnev) AND (meddig < '$mainap')
                  ORDER BY kiemelt DESC, ido DESC;";
      }
      elseif ($szint=='adminisztrátor')
      {  // mindenkinek a saját lejárt híre jelenjen meg
        $query1 = "SELECT hirazon,hircim,kiemelt,hirek.felhnev,nev,ido,meddig
                  FROM hirek, felhasznalok
                  WHERE (hirek.felhnev=felhasznalok.felhnev) AND (hirek.felhnev='$belepett_felhnev') AND (meddig < '$mainap')
                  ORDER BY kiemelt DESC, ido DESC;";
      }
      $result1 = mysql_query($query1) or die("üzenet: " . mysql_error());
      $num1 = mysql_numrows($result1);
      
      if ($num1>0)
      {  // Ezek szerint meg kell jeleníteni lejárt híreket...

        $i=0;

        echo "Lejárt hírek:";
        echo '<table width=100% border=0 name="hirtable" id="hirtable"><tr valign=top>';
    
        while ($i < $num1)
        {
          $hirazon = mysql_result($result1,$i,"hirazon");
          $hircim = mysql_result($result1,$i,"hircim");
          $kiemelt = mysql_result($result1,$i,"kiemelt");
          $felhnev = mysql_result($result1,$i,"felhnev");
          $nev = mysql_result($result1,$i,"nev");
          $ido = mysql_result($result1,$i,"ido");
          $meddig = mysql_result($result1,$i,"meddig");
          $ikonok = '';
          if (isset($_SESSION['felhnev']))
          {             // Be vagyunk jelentkezve
            if ($szint=='rendszergazda')
            {
              $ikonok = "<a href=hir.php?hirazon=$hirazon><img src=\"img/edit.png\" alt=\"Szerkesztés\" width=\"16\" height=\"16\" border=\"0\" /></a>
                         <a href=hirtorol.php?hirazon=$hirazon><img src=\"img/torol.png\" alt=\"Törlés\" width=\"16\" height=\"16\" border=\"0\" /></a>";
            }
            elseif ($szint=='adminisztrátor')
            {
              if ($felhnev==$belepett_felhnev)
              {
                $ikonok = "<a href=hir.php?hirazon=$hirazon><img src=\"img/edit.png\" alt=\"Szerkesztés\" width=\"16\" height=\"16\" border=\"0\" /></a>
                           <a href=hirtorol.php?hirazon=$hirazon><img src=\"img/torol.png\" alt=\"Törlés\" width=\"16\" height=\"16\" border=\"0\" /></a>";
              }
            }
          }
          echo '<td width=33%>
      		<div class="szurkepost">
      			<p class="title"><a href="hirek.php?hirazon=' . $hirazon .'">' . $hircim . '</a>' . $ikonok . '</p>
      			<p class="byline"><small>' .  $nev . ' ' . $ido . '</small></p>
      		</div>
            </td>';
          if ($i%3==2)
          {  //Sortörés
             echo "</tr><tr valign=top>";
          }
          $i++;
        }
        // Egészítsük ki 3 oszlopra
        if ($num<3)
        {
          $i = $num;
          while ($i<3)
          {
            echo "<td width=33%></td>";
            $i++;
          }
        }
        echo '</tr></table>';


      }
    }

    if (isset($_GET['from']))
    {
      $honnan = $_GET['from']*9;
    }
    else
    {
      $honnan = 0;
    }

    $query = "SELECT hirazon,hircim,kiemelt,hirek.felhnev,nev,ido,meddig,leiras,LEFT(leiras,150) as kisleiras
              FROM hirek, felhasznalok
              WHERE (hirek.felhnev=felhasznalok.felhnev) AND (meddig >= '$mainap')
              ORDER BY kiemelt DESC, ido DESC
              LIMIT $honnan, 9;";

    $result = mysql_query($query) or die("üzenet: " . mysql_error());
    $num = mysql_numrows($result);
    $query2 = "SELECT count(hirazon) as osszesen
              FROM hirek
              WHERE meddig >= '$mainap';";
  
    $result2 = mysql_query($query2) or die("üzenet: " . mysql_error());
    $osszesen =  mysql_result($result2,0,"osszesen");
    $i=0;
  
    echo '<table width=100% border=0 name="hirtable" id="hirtable"><tr valign=top>';

    while ($i < $num)
    {
      $hirazon = mysql_result($result,$i,"hirazon");
      $hircim = mysql_result($result,$i,"hircim");
      $kiemelt = mysql_result($result,$i,"kiemelt");
      $felhnev = mysql_result($result,$i,"felhnev");
      $nev = mysql_result($result,$i,"nev");
      $ido = mysql_result($result,$i,"ido");
      $meddig = mysql_result($result,$i,"meddig");
      $leiras = nl2br(mysql_result($result,$i,"leiras"));
      $kisleiras = mysql_result($result,$i,"kisleiras");
      $ikonok = '';
      if (isset($_SESSION['felhnev']))
      {             // Be vagyunk jelentkezve
        if ($szint=='rendszergazda')
        {
          $ikonok = "<a href=hir.php?hirazon=$hirazon><img src=\"img/edit.png\" alt=\"Szerkesztés\" width=\"16\" height=\"16\" border=\"0\" /></a>
                     <a href=hirtorol.php?hirazon=$hirazon><img src=\"img/torol.png\" alt=\"Törlés\" width=\"16\" height=\"16\" border=\"0\" /></a>";
        }
        elseif ($szint=='adminisztrátor')
        {
          if ($felhnev==$belepett_felhnev)
          {
            $ikonok = "<a href=hir.php?hirazon=$hirazon><img src=\"img/edit.png\" alt=\"Szerkesztés\" width=\"16\" height=\"16\" border=\"0\" /></a>
                       <a href=hirtorol.php?hirazon=$hirazon><img src=\"img/torol.png\" alt=\"Törlés\" width=\"16\" height=\"16\" border=\"0\" /></a>";
          }
        }
      }
      echo '<td width=33%>
  		<div class="post">
  			<p class="title"><a href="hirek.php?hirazon=' . $hirazon .'">' . $hircim . '</a>' . $ikonok . '</p>
  			<p class="byline"><small>' .  $nev . ' ' . $ido . '</small></p>
  			<div class="entry">
  				<p>' . $kisleiras . '</p>
  				<p class="links"><a href="hirek.php?hirazon=' . $hirazon .'" class="more">tovább</a></p>
  			</div>
  		</div>
        </td>';
      if ($i%3==2)
      {  //Sortörés
         echo "</tr><tr valign=top>";
      }
      $i++;
    }
    // Egészítsük ki 3 oszlopra
    if ($num<3)
    {
      $i = $num;
      while ($i<3)
      {
        echo "<td width=33%></td>";
        $i++;
      }
    }
    echo '</tr></table>';
    if (bcdiv($osszesen-1,9)>0)
    {
      $i = 0;
      echo "<center>";
      while ($i<bcdiv($osszesen,9)+1)
      {
        if ($i==0)
        {
          $szoveg = 'Első oldal';
        }
        else
        {
          $szoveg = $i +1;
        }
        if ($i==bcdiv($osszesen,9))
          $szoveg = 'Utolsó oldal';
    
    
        echo "<a href=hirek.php?from=" . $i .">" . $szoveg ."</a>  ";
        $i++;
      }
      echo "</center>";
    }
  }
  echo '</div>';
  include "alja.php";
?>
</body></html>