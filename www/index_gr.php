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
<td bgcolor="#A0A0A0"> <A HREF="/mrtg/pinger/index.php">Hosts online statistics</A> </td>
<td bgcolor="#A0FFA0">Хосты по группам</td>
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

<table BORDER=0 CELLPADDING=0 CELLSPACING=7 bgcolor='#DFFFE1' style="font-family: courier; font-size: 70%">

<tr>

<td></td>

<td></td><td><IMG SRC="header.png"></td>

</tr>

<?

function print_part($q)
{
        for ($c=0; $c<mysql_num_rows($q); $c++)
        {
                echo "<tr>";
                $f = mysql_fetch_array($q);
                echo "<td><a  style='{text-decoration: none; color: #000000; }' href='/mrtg/pinger/modify.php?mac=$f[name]'>";
		if ($f[host_name]=='')
			{ echo $f[name]; }
		else    { echo $f[host_name]; }
		echo "</a></td>";
		$ip=explode('.',$f[ip]);
		echo "<td style='{color: #ff8966;}'>$ip[3]</td>";
                echo "<td>
                <IMG border=0 vspace=0 SRC=\"img_$f[name].png\">
                <IMG border=0 vspace=0 SRC=\"line.png\">
                </td>";
                echo "</tr>";
        }
}

function move_to_undefined($q)
{
	for ($c=0; $c<mysql_num_rows($q); $c++)
	{
		$f = mysql_fetch_array($q);
		$sql="insert into groups (gr_name, mac_addr) values ('', '".$f['name']."')";
		mysql_query($sql);
	}
}

function print_header($str)
{
	?>
	<tr><td bgcolor='<? echo $bgcolor ?>'><br>
	<FONT FACE="Arial,Helvetica" SIZE=1 COLOR=#c49062>
	<?
	echo $str
	?>
	<FONT>
	</td><td></td><td></td></tr>
	<?
}

$g = mysql_query ("select * from history where name not in (select mac_addr from groups) and (time_down>now()-interval 18 hour or time_down=0) group by name");
move_to_undefined($g);

$g = mysql_query ("select gr_name from groups group by gr_name");
for ($h=0; $h<mysql_num_rows($g); $h++)
{
	$y = mysql_fetch_array($g);
	#print_header($y[gr_name]);
	$q = mysql_query ("select ip,name,host_name from history h join groups g on (h.name = g.mac_addr) where (time_down>now()-interval 18 hour or time_down=0)and gr_name='$y[gr_name]' group by name");
	if (mysql_num_rows($q)!=0) {print_header($y[gr_name]);};
	print_part($q);
}

//print_header("Не определённые");
// $g = mysql_query ("select * from history where name not in (select mac_addr from groups where gr_name<>'') and (time_down>now()-interval 18 hour or time_down=0) group by name");

// $g = mysql_query ("select ip from history where time_down>now()-interval 18 hour or time_down=0 group by ip");
//print_part($g);
echo "</table>";

$q = mysql_query ("select count(*) as sa from history where time_down=0");
$count =  mysql_fetch_array($q);
$q = mysql_query ("select count(*) as sa from history");
$count1 =  mysql_fetch_array($q);

echo "Хостов онлайн: <b>$count[sa]</b>, всего записей в history: <b>$count1[sa]</b>";

?>
</BODY>
</HTML>
