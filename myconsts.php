<script language="javascript">
  function datecheck(s)
  {
    var a;
    var r;
    var y, m, d, v;

    a = s.match(/^(\d{4})\-(\d{2})\-(\d{2})$/);
    try {
      y = parseInt(a[1], 10);
      m = parseInt(a[2], 10) - 1;
      d = parseInt(a[3], 10);
      v = new Date(y, m, d);
      r = (y == v.getFullYear()) && (m == v.getMonth()) && (d == v.getDate());
    } catch (e) {
      r = false;
    }
    return r;
  }
</script>
<?php
  $mysql_serverurl = "localhost";
  $mysql_database = "bir";
  $username = "root";
  $password = "mysql";
  $html_ut = "html";
  $mell_ut = "mell";
  $dok_ut = "dok";

  /* Az intezkedes.php-ben az oszlopok szélessége:*/

  $oszlop1 = "200";
  $oszlop2 = "160";
  $oszlop3 = "160";
  $oszlop4 = "160";
  
  $hiroszlop1 = "140";

  function fajlnevve($mit)
  {
    $eredmeny = str_replace("/", "-", $mit);
    return $eredmeny;
  }
?>


