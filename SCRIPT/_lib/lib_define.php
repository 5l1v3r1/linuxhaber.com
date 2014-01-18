<?php
$sitelink = 'http://'.$_SERVER['HTTP_HOST']; $sitelink = trim($sitelink);
//$sitelink = 'http://'.$_SERVER['HTTP_HOST'].'/planet'; 

define ('SEO_OPEN',1);
//define ('SEO_OPEN',0);

//istisnai durumlar için dosya adları oluşturuluyor
define ('ANASAYFA','index.php');

//ANA SAYFA
define ('SITELINK',$sitelink);
define ('ANASAYFALINK',SITELINK.'/index.php');
define ('SAYFALARLINK',SITELINK.'/sayfalar.php');
define ('BULTENLERLINK',SITELINK.'/bultenler.php');
define ('REGISTERLINK',SITELINK.'/kayit-ol.php');
define ('GIRISLINK',SITELINK.'/giris.php');

//ACP & UCP
define ('UCPLINK',SITELINK.'/ucp.php');
define ('ACPLINK',SITELINK.'/acp.php');
define ('LOGINLINK',SITELINK.'/login.php');
define ('EXITLINK',SITELINK.'/login.php?exit=1');

//GİZLİ SAYFA
define ('AJAXLINK',SITELINK.'/_ajax.php');
define ('FEEDLINK',SITELINK.'/feed.php');
define ('FEEDBLINK',SITELINK.'/feedbulten.php');

//seo değerleri tanımlanıyor
define ('SEO','.html');

//SECURTY
//platform bağımsız açıklar için session güvencesi...
// ==> | işaretinden sonrası her site için ayrı tanımlanmalıdır 
define ('SES',md5(SITELINK.'|ayri'));

define ('L_EN','en');

//kayıt ve cache yolunu belirtelim
$vt->kayitYolu('./_cache/'); 
$bellekyolu = '_cache';

//google api sabitleri
define('GOOGLE_API_KEY', 'apikeyhear');
define('GOOGLE_ENDPOINT', 'https://www.googleapis.com/urlshortener/v1');
?>
