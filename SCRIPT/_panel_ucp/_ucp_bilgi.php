<?php
if (!defined('yakusha')) die('...');

	if ($_REQUEST["form1"])
	{
		$user_email 	= (trim(strip_tags($_REQUEST["user_email"])));
		$user_name 		= (trim(strip_tags($_REQUEST["user_name"])));
		$user_username 	= (trim(strip_tags($_REQUEST["user_username"])));
	
		//üye adı bizim standartlarımızda olsun
		$user_name 	= str_replace(array("-"),"", format_url($user_name));
	
	
		//üye adı ve eposta adresi başkasına ait olmasın
		$vt->sql('SELECT count(user_id) FROM rss_user WHERE (user_email = %s OR user_name = %s) AND user_id <> %u');
		$vt->arg($user_email, $user_name, $_SESSION[SES]["user_id"])->sor();
		$varolan = $vt->alTek();
		if($varolan) $islem_bilgisi = '<div class="errorbox">Bu eposta adresi veya kullanıcı adı başka bir kullanıcı adına kayıtlıdır</div>';
		
		if(!$islem_bilgisi)
		{
			$vt->sql('UPDATE rss_user SET user_email = %s, user_name = %s, user_username = %s WHERE user_id = %u');
			$vt->arg($user_email, $user_name, $user_username, $_SESSION[SES]["user_id"])->sor();
			$islem_bilgisi = '<div class="successbox">Üye bilgileriniz güncellendi.</div>';
		}
	}

	$vt->sql('SELECT * FROM rss_user WHERE user_id = %u')->arg($_SESSION[SES]["user_id"])->sor();
	//echo $vt->alSql(),
	$sonuc 	= $vt->alHepsi();

	$user_email 		= $sonuc[0]->user_email;
	$user_name 			= $sonuc[0]->user_name;
	$user_username 		= $sonuc[0]->user_username;
	$user_status 		= $sonuc[0]->user_status;
	$user_tweet_status 	= $sonuc[0]->user_tweet_status;
	//slash işaretleri temizleniyor
	$user_email 		= stripslashes($user_email);
	$user_name 			= stripslashes($user_name);
	$user_username 		= stripslashes($user_username);

include($siteyolu."/_panel_ucp/_temp/_t_ucp_bilgi.php");
?>
