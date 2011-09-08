<HTML>
<HEAD>
    <TITLE>Hosts online statistics</TITLE>
    <!-- Command line is easier to read using "View Page Properties" of your browser -->
    <!-- But not all browsers show that information. :-(                             -->
    <meta http-equiv="content-type" content="text/html; charset=utf-8" >
    <META HTTP-EQUIV="Refresh" CONTENT="60" >
    <META HTTP-EQUIV="Cache-Control" content="no-cache" >
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache" >
    <META HTTP-EQUIV="Expires" CONTENT="Sat, 29 Aug 2009 12:33:04 GMT" >
    <LINK HREF="favicon.ico" rel="shortcut icon" >

<style type="text/css">
/* commandline was: /usr/bin/indexmaker --output /var/www/mrtg/index.html --columns=2 /etc/mrtg.cfg */
/* sorry, no style, just abusing this to place the commandline and pass validation */
</style>
</HEAD>

<BODY bgcolor="#fafafa" text="#000000" link="#000000" vlink="#000000" alink="#000000">

<table><tr>
<td>
<A HREF="/"><IMG
    BORDER=0 SRC="/mrtg/home.jpg" WIDTH=22 HEIGHT=22
    ALT="Home"></A>
</td>
<td bgcolor="#A0A0A0"> <A HREF="/mrtg/index.html">ICSTEC Server Status</A> </td>
<td bgcolor="#A0FFA0"> Hosts online statistics</td>
<td bgcolor="#A0A0A0"> <A title="hello" HREF="/mrtg/pinger/index_gr.php">Хосты по группам</A> </td>
</tr></table>


<?php
$host = "localhost";
$user = "icstec";
$password = "Chahmei4";
$db = "pinger";

// Производим попытку подключения к серверу MySQL:
if (!mysql_connect($host, $user, $password))
{
echo "<h2>MySQL Error!</h2>";
exit;
}

// Выбираем базу данных:
mysql_select_db($db);

// Выводим заголовок таблицы:

?>

<table BORDER=0 CELLPADDING=0 CELLSPACING=7 width="100%" bgcolor="#DFFFE1">

<tr>

<td></td>

<td><IMG SRC="header.png"></td>

</tr>

<?
// SQL-запрос:
$q = mysql_query ("select ip from history where time_down>now()-interval 18 hour or time_down=0 group by ip");

// Выводим таблицу:
for ($c=0; $c<mysql_num_rows($q); $c++)
{
echo "<tr>";

$f = mysql_fetch_array($q);
echo "<td>$f[ip]</td>";
echo "<td>

<IMG border=0 vspace=0 SRC=\"$f[ip].png\">
<IMG border=0 vspace=0 SRC=\"line.png\">

</td>";

echo "</tr>";
}
echo "</table>";

$q = mysql_query ("select count(*) as sa from online");
$count =  mysql_fetch_array($q);
$q = mysql_query ("select count(*) as sa from history");
$count1 =  mysql_fetch_array($q);

echo "Хостов онлайн: <b>$count[sa]</b>, всего записей в history: <b>$count1[sa]</b>";

?>
</BODY>
</HTML>
