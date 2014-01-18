<?php
$dbCon = array(
	'host' => 'localhost',
	'name' => 'dbname',
	'user' => 'dbuser',
	'pass' => 'dbpass',
	'lang' => 'utf8',
);

//bir de class bir bağlantı oluşturalım
$vt->hataGoster(true);
$vt->baglan($dbCon) or die ('<center><img src="http://www.linuxhaber.com/_img/dberror.png"></center>');
?>
