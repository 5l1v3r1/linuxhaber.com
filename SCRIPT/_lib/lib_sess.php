<?php
# oturum dosyası
session_start();

if($_REQUEST["lang"])
{
	$l = $_REQUEST["lang"];
	if($l == 'en') $_SESSION[SES]["lang"] = 'en';
	if($l == 'tr') $_SESSION[SES]["lang"] = 'tr';
}

//genel oturumu başlatıyoruz
if ( !isset($_SESSION[SES]) )
{
	$_SESSION[SES]["ip"] 				= $_SERVER["REMOTE_ADDR"]; // Bağlanırken kullanılan IP
	$_SESSION[SES]["tarayici"] 			= $_SERVER["HTTP_USER_AGENT"]; // Bağlantı hangi tarayıcı ile yapılmış?
	$_SESSION[SES]["ilkerisim"] 		= time(); // İlk bağlantının IP si
	$_SESSION[SES]["sonerisim"] 		= time(); // En son yapılan erişim zamanı
	$_SESSION[SES]["giris"] 			= 0;
	$_SESSION[SES]["giristar"] 			= 0;
	$_SESSION[SES]["sessionstarttime"] 	= time();
}
else
{
	$_SESSION[SES]["sonerisim"] 		= time(); // En son yapılan erişim zamanı
}

//dil değerleri
$lang = $_SESSION[SES]["lang"]; 

?>
