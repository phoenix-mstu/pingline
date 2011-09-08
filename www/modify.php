<?php

$host = "localhost";
$user = "icstec";
$password = "Chahmei4";
$db = "pinger";

if (!mysql_connect($host, $user, $password))
	{
	echo "<h2>MySQL Error!</h2>";
	exit;
	}

mysql_select_db($db);

if ($_GET['apply']<>'1')
	{
	$mac=$_GET['mac'];
	#TODO сделать проверку $mac
	#TODO Выпадающий список групп
	$sql = "select * from groups where mac_addr='".$mac."' limit 1";
	$q = mysql_query ($sql);
	if (mysql_num_rows($q)==0) 
		{
		$already_has_name=false;
		} 
	else
		{
		$f = mysql_fetch_array($q);
		$group=$f['gr_name'];
		$name=$f['host_name'];
		}
?>
<html>
<HEAD>
    <TITLE>Информация о хосте</TITLE>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" >
    <META HTTP-EQUIV="Cache-Control" content="no-cache" >
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache" >
    <META HTTP-EQUIV="Expires" CONTENT="Sat, 29 Aug 2009 12:33:04 GMT" >
<style type="text/css">
</style>
</HEAD>
<body>
<table width="100%" height="100%">
<tr>
<td align="center" valign="middle">
<table bgcolor=#AAAAAA> 
<tr>
<td align="center" valign="middle">
 <form action="modify.php">
  <p>Хост <?echo $mac?></p>
  Группа:<br>
  <input type="input" name="group" value="<? echo $group?>"><br>
  Имя хоста:<br>
  <input type="input" name="name" value="<? echo $name?>"><br>
  <input type="hidden" name="mac" value="<? echo $mac?>">
  <input type="hidden" name="apply" value="1">
  <input type="submit">
 </form>
</td>
</tr>
<tr>
<td>
5 последних подключений:
<table border=1>
<?
        $sql = "select ip, time_up from history where name='$mac' order by time_up desc limit 5";
        $q = mysql_query ($sql);
        if (mysql_num_rows($q)==0)
                {
                }
        else
                {
                for ($h=0; $h<mysql_num_rows($q); $h++)
                        {
			if ($h==0)
				{ echo "<tr bgcolor='green'>"; }
			else    { echo "<tr>"; }
			$y = mysql_fetch_array($q);
			$ip=$y['ip'];
			$time=$y['time_up'];
			echo "<td>$h</td><td>$ip</td><td>$time</td></tr>";
                        }
                };
?>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
<?
	} 
else
	{
	$mac=$_GET['mac'];
	$name=$_GET['name'];
	$group=$_GET['group'];
	#TODO проверка значений

	$sql = "delete from groups where mac_addr='$mac'";
	mysql_query ($sql);
	if (($group<>'')or($name<>''))
	{
        	$sql = "insert into groups (gr_name, host_name, mac_addr) values ('$group', '$name', '$mac')";
	}
        mysql_query ($sql);
?>
<meta http-equiv='refresh' content='0; url=index_gr.php'>
<?
	}
?>
