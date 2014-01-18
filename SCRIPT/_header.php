<?php
if (!defined('yakusha')) die('...');

//sayfa saatini başlatıyoruz
$starttime = microtime(true);

#hata bastırma şekli
//error_reporting(E_ALL);
error_reporting(E_ERROR);

//zaman dilimi de türkiye olsun
setlocale(LC_ALL,'tr_TR');
date_default_timezone_set('Auropa/Istanbul');

//site yolu tanımlamaları
$siteyolu = realpath('./');

//artık eburhan db classını kullanmaya başlıyoruz
require($siteyolu.'/_lib_class/eb.vt.php');

//nesne oluşturalım
$vt = new VT;

# define edilen değerler
include($siteyolu.'/_lib/lib_con.php');
include($siteyolu.'/_lib/lib_define.php');
include($siteyolu.'/_lib/lib_desc.php');
include($siteyolu.'/_lib/lib_sess.php');
include($siteyolu.'/_lib/lib_func.php');
include($siteyolu.'/_lib/lib_cache.php');
include($siteyolu.'/_lib/lib_array.php');
?>
