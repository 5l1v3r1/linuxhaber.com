<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]['ADMIN']==1) exit ();

$acp_anamenulink 		= ACPLINK.'?menu=giris';
$acp_uyelerlink 		= ACPLINK.'?menu=uyeler';
$acp_kategorilerlink 	= ACPLINK.'?menu=kategoriler';
$acp_tweetlink 			= ACPLINK.'?menu=tweet';
$acp_sayfalarlink		= ACPLINK.'?menu=sayfalar';
$acp_bultenlerlink		= ACPLINK.'?menu=bultenler';

?>
