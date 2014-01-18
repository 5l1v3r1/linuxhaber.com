<?php
if (!defined('yakusha')) die('...');

$exit = $_REQUEST["exit"]; settype($exit,"integer");

if ($exit == 1)
{
	if (isset($_SESSION[SES])) 	unset($_SESSION[SES]);
	$_SESSION[SES]["giris"] 	= 0;
	$sayfa_tazele 				= '0; URL='.SITELINK;
	$sayfa_baslik 				= 'İşleminiz Gerçekleştiriliyor';
	$sayfa_mesaj 				= '<div class="successbox">Çıkış İşleminiz Onaylandı<br>Lütfen bekleyiniz.</div>';
	include($siteyolu."/_lib_temp/_top.php");
	exit();
}

if (isset($_REQUEST["fmemberin"]))
{
	$parola 	= substr($_REQUEST["parola"],0,32);
	$eposta 	= substr($_REQUEST["eposta"],0,70);
	$hash 		= md5($parola);
	//kullanıcı sorgulanıyor
	if ($parola == '' || $eposta == '')
	{
		$sayfa_tazele 		= "3; URL=".SITELINK;
		$sayfa_baslik 		= 'Hata Oluştu';
		$sayfa_mesaj 		= '<div class="errorbox">Kullanıcı adınızı ve Parolanızı boş bırakmayınız.<br>Lütfen tekrar deneyiniz.</div>';
		include($siteyolu."/_lib_temp/_top.php");
		exit();
	}

	$vt->sql('SELECT * FROM rss_user WHERE (user_email = %s OR user_name = %s) AND user_password = %s')->arg($eposta,$eposta,$hash)->sor();
	$sonuc = $vt->alHepsi();
	$adet = $vt->numRows();

	//kullanıcı var ise
	if ($adet)
	{		
		//kullanıcı bilgileri oturuma aktarılıyor
		$_SESSION[SES]["user_id"] 				= $sonuc[0]->user_id;
		$_SESSION[SES]["user_email"] 			= $sonuc[0]->user_email;
		$_SESSION[SES]["user_name"] 			= $sonuc[0]->user_name;
		$_SESSION[SES]["user_username"] 		= $sonuc[0]->user_username;
		$_SESSION[SES]["user_status"] 			= $sonuc[0]->user_status;
		$_SESSION[SES]["user_tweet_status"] 	= $sonuc[0]->user_tweet_status;
		$_SESSION[SES]["giris"] 				= 1;
		$_SESSION[SES]["ADMIN"] 				= 0;
		$_SESSION[SES]["giristar"] 				= time();

		//yönetici ise yönetici oturumu açılıyor
		if ($_SESSION[SES]["user_status"] == 10 ) $_SESSION[SES]["ADMIN"] = 1;
		//sabit ana sayfaya gitsin herkes
		
		//sayfa yönlendirmesi oluşturuluyor
		$sayfa_tazele 	= "0; URL=".SITELINK;
		$sayfa_baslik 	= 'İşleminiz Gerçekleştiriliyor';
		$sayfa_mesaj 	= '<div class="successbox">Üyelik Girişiniz Onaylandı.<br>Lütfen bekleyiniz.</div>';		
		include($siteyolu."/_lib_temp/_top.php");
		exit();
	}
	else
	{
		$sayfa_tazele 	= "30; URL=".SITELINK;
		$sayfa_baslik 	= 'Hata Oluştu';
		$sayfa_mesaj 	= '<div class="errorbox">Lütfen Üye Olup Tekrar Deneyiniz.</div>';
		include($siteyolu."/_lib_temp/_top.php");
		exit();
	}
}

if (isset($_REQUEST["register"]))
{
	$eposta 	= substr($_REQUEST["eposta"],0,70);
	$username 	= substr($_REQUEST["username"],0,70);
	$realname 	= substr($_REQUEST["realname"],0,70);
	$parola 	= substr($_REQUEST["parola"],0,70);
	$reparola 	= substr($_REQUEST["reparola"],0,70);

	//genel hata denetimi başlatılıyor
	$sayfa_tazele 		= "3; URL=".REGISTERLINK;
	$sayfa_baslik 		= 'Hata Oluştu';
	//$sayfa_mesaj 		= '<div class="errorbox">Tanımsız Hata!</div>';

	if ($parola <> $reparola)
	{
		$sayfa_mesaj 	= '<div class="errorbox">İki parola aynı değil. Tüh! Tüm form boşa gitti.</div>';
	}

	if ($eposta == '' or $username == '' or $realname == '' or $parola == '' or $reparola == '')
	{
		$sayfa_mesaj 	= '<div class="errorbox">Hiçbir Alanı Boş Bırakmayınız. Tüh! Tüm form boşa gitti.</div>';
	}

	//kullanıcı yok ise
	if(!$sayfa_mesaj)
	{
		$vt->sql('SELECT count(user_id) FROM rss_user WHERE user_email = %s OR user_name = %s OR user_username = %s');
		$vt->arg($eposta,$username,$realname)->sor();
		//echo $vt->alSql();
		$adet = $vt->alTek();

		if (!$adet)
		{		
		
			$user_password 		= md5($parola);
			$user_status 		= 1; //aktif
			$user_tweet_status 	= 2; //taslak
			$changetar 			= time();
			$vt->sql('INSERT INTO rss_user ( user_email, user_name, user_username, user_status, user_tweet_status, user_password, createtar, changetar)
						VALUES ( %s, %s, %s, %u, %u, %s, %u, %u)');
			$vt->arg($eposta, $username, $realname, $user_status, $user_tweet_status, $user_password, $changetar, $changetar )->sor();
			if($vt->affRows())
			{
				//sayfa yönlendirmesi oluşturuluyor
				$sayfa_mesaj 	= '<div class="successbox">Üyelik kaydınız tamamlandı. Artık Giriş Yapabilirsiniz.</div>';		
				$sayfa_tazele 	= "5; URL=".SITELINK;
				$sayfa_baslik 	= 'İşleminiz Gerçekleştiriliyor';
			}
		}
		else
		{
			$sayfa_tazele 	= "30; URL=".REGISTERLINK;
			$sayfa_baslik 	= 'Hata Oluştu';
			$sayfa_mesaj 	= '<div class="errorbox">Bu bilgilere sahip başka bir üye var. Belki de o üye sizsiniz.</div>';
		}
	}

	if ($sayfa_mesaj)
	{
		include($siteyolu."/_lib_temp/_top.php");
		exit();
	}
}
?>
