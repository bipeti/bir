<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>BIR - Programok</title>
  <link href="default.css" rel="stylesheet" type="text/css" />
  <script language="javascript"></script>
</head>
<body>

<?php
  session_start();
  include "myconsts.php";
  include "teteje.php";
  include "bal.php";
?>

      <div id="primaryContent">
        <h2>Programok</h2>
        <p>Az alábbiakban találhatóak meg az intézet által használt webes felületen futó alkalmazások:</p>
        <ul>
          <li><a href="http://172.17.12.130">OP intézkedések</a></li>
          <li><a href="intezkedesek.php">Helyi intézkedések</a></li>
          <li><a href="http://172.17.4.121/vmzadmin/index.php">VAMZER rendszer</a></li>
          <li><a href="http://172.16.180.12/owa/">Levelező program (owa)</a></li>
          <li><a href="http://172.16.180.17/">ÜTI jelentések</a></li>
        </ul>
      </div>

<?php
  include "alja.php";
?>
