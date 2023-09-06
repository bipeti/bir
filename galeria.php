<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>BIR - Galéria</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript">
  function newW()
  {
//    windowHandle = window.open('galery/index.php','winGalery','width=1024,height=768,left=0,top=0,location=yes,scrollbars=yes');
    windowHandle = window.open('galery/index.php','winGalery','width='+screen.width+',height='+screen.height+',left=0,top=0,location=yes,scrollbars=yes');
    windowHandle.focus();
  }
  </script>
</head>
<body>

<?php
  session_start();
  include "myconsts.php";
  include "teteje.php";
  include "bal.php";
?>

      <div id="primaryContent">
        <h2>Galéria</h2>
        <p>A galéria indításához kattints <a href = "javascript:newW()">ide</a>. (Új ablakban nyílik meg.)<BR>
        Ha van olyan képetek, amit közzé tennétek a hálózaton, akkor hozzátok. :-)</p>
      </div>

<?php
  include "alja.php";
?>
