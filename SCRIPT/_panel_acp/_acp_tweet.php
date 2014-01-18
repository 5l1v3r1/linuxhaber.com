<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php"); 

$duzenle	= $_REQUEST['duzenle']; 	settype($duzenle,"integer");
$ekle 		= $_REQUEST['ekle']; 		settype($ekle,"integer");
$status 	= $_REQUEST['status']; 		settype($ekle,"status");

$delete 	= $_REQUEST["delete"]; 		settype($delete,"integer");

if ($delete > 0 )
{
	$vt->sql('DELETE FROM rss_tweet WHERE tweet_id = %u')->arg($delete)->sor();
	$islem_bilgisi = '<div class="successbox">Link Sistemden Silinmiştir.</div>';
}

if ($duzenle > 0)
{
	include($siteyolu."/_panel_acp/_acp_tweet_duzenle.php");
}
elseif ($ekle > 0)
{
	include($siteyolu."/_panel_acp/_acp_tweet_ekle.php");
}
else
{

	//toplu güncelleme için gereken değerleri alıyoruz
 	$tweet_cat	= $_REQUEST["tweet_cat"];

	//formla ilgili öncelikli işlemler varsa tamamlıyoruz
 	if (isset ($_REQUEST["form1"]))
	{
		//link categori bilgilerini güncelliyoruz
		foreach ($tweet_cat as $k => $v)
		{
			if($v <> 0) $vt->sql('UPDATE rss_tweet SET tweet_cat = %s WHERE tweet_id = %u')->arg($v,$k)->sor();
		}
		$islemsonucu = '<div class="successbox">İşlem Başarı İle Tamamlandı.</div>';
	}

	//normal form alanına dönüyoruz
	
	
	//taslak bekleyen tweet var mı, önce ona bakalım
	$vt->sql('SELECT count(tweet_id) FROM rss_tweet WHERE tweet_status = 2')->sor(); 
	$bekleyen = $vt->alTek();
	if($bekleyen > 0) $bekleyenlink = ' | <a class="button1" href="'.$acp_tweetlink.'&amp;status=2">Taslak Tweetler</a> ';

	//pasif tweet var mı, önce ona bakalım
	$vt->sql('SELECT count(tweet_id) FROM rss_tweet WHERE tweet_status = 0')->sor(); 
	$pasif = $vt->alTek();
	if($pasif > 0) $pasiflink = ' | <a class="button1" href="'.$acp_tweetlink.'&amp;status=0">Pasif Tweetler</a> ';
	
	//aktif tweet yazısını pasif ve taslak tweet yoksa göstermeyelim
	if($bekleyen > 0 || $pasif > 0) $aktiflink = ' <a class="button1" href="'.$acp_tweetlink.'&amp;status=1">Aktif Tweetler</a> ';

	//listeleme sql sorgusu oluşturuluyor
	$limitsql = 'LIMIT 0,7';
	if($status == '0') 	{ $ilavesql = 'AND tweet_status = 0'; $limitsql = ''; }
	if($status == '2') 	{ $ilavesql = 'AND tweet_status = 2'; $limitsql = ''; }

	$vt->sql('SELECT * FROM rss_tweet WHERE tweet_id > 0 '.$ilavesql.' ORDER BY tweet_id DESC '.$limitsql)->sor();
	//echo $vt->alSql();
	$sonuc = $vt->alHepsi();
	$bulunanadet = $vt->numRows();

	if ($bulunanadet)
	{
		for ( $i = 0; $i < $bulunanadet; $i++)
		{
			//sorgudan alınıyor
			$tweet_id 		= $sonuc[$i]->tweet_id;
			$tweet_url 		= $sonuc[$i]->tweet_url;
			$tweet_text 	= $sonuc[$i]->tweet_text;
			$tweet_en 		= $sonuc[$i]->tweet_en;
			$tweet_desc 	= $sonuc[$i]->tweet_desc;
			$tweet_short 	= $sonuc[$i]->tweet_short;
			$tweet_cat 		= $sonuc[$i]->tweet_cat;
			$tweet_tar 		= $sonuc[$i]->tweet_tar;
			$tweet_uid 		= $sonuc[$i]->tweet_uid;
			//slash işaretlerini temizleyelim
			$tweet_text 	= stripslashes($tweet_text);
			$tweet_desc 	= stripslashes($tweet_desc);			

			//icon yoksa varsayılan ikon görünsün
			if($tweet_cat == 0) $array_kategorilistesi[$tweet_cat]["cat_image"] = 'default.png';

			$sayfabilgisi.= '
				<div class="stream-item">
					<div class="stream-item-content tweet js-actionable-tweet js-stream-tweet stream-tweet">
						<div class="konu-resmi">
							<img src="'.SITELINK.'/_img/_cat/'.$array_kategorilistesi[$tweet_cat]["cat_image"].'" alt="'.$array_kategorilistesi[$tweet_cat]["cat_name"].'" height="48" width="48">
						</div>
						<div class="tweet-content">
							<div class="tweet-row">
								<div class="tweet-text js-tweet-text" valing="center">
									'.$tweet_text.' 
									<a href="'.$tweet_short.'" title="'.$tweet_url.'" target="_blank" class="twitter-timeline-link">'.$tweet_short.'</a>
									'.$tweet_desc.'
								</div>
								<div align="right">
									<span class="tweet-actions action-favorite">
										@'.$array_userlist[$tweet_uid]["user_name"].'
										&nbsp;&nbsp;
										<select style="width:125px" name="tweet_cat['.$tweet_id.']">
										<option value="'.$tweet_cat.'"> &raquo; '.$array_kategorilistesi[$tweet_cat]["cat_name"].'</option>
										'.$kategoriler_options.'
										</select>
										&nbsp;&nbsp;
									</span>
										<a title="Düzenle" href="'.$acp_tweetlink.'&amp;duzenle='.$tweet_id.'">
										<img src="'.SITELINK.'/_img/icon_edit.gif"></a> &nbsp;&nbsp;
								</div>
													
							</div>
						</div>
					</div>
				</div>
			';
		}
	}
	else
	{
		$islemsonucu = '<div class="errorbox">Aranan Sonuç Bulunamadı</div>';
	}
?>
<a class="button1" href="<?php echo $acp_tweetlink?>&ekle=1"><img src="<?php echo SITELINK?>/_img/icon_ekle.png">TWEET EKLE</a>

Bu paneli kullanarak tweet bilgilerinizi yönetebilirsiniz.<br><br>

<link rel="stylesheet" href="style_core.css" type="text/css" media="screen" />

<?php echo $islemsonucu?>

<?php echo $aktiflink.$pasiflink.$bekleyenlink ?>

<form name="form1" action="<?php echo $acp_tweetlink?>" method="POST">
<input type="hidden" name="menu" value="tweetler">
<input type="hidden" name="form1" value="1">
<table>
</tr>
	<tr>
		<td>
			<?php echo $hatali?>
			<?php echo $sayfabilgisi?>
		</td>
	</tr>
</table>
<div align="right"><br><input class="button1" type="submit" name="form1" value="TOPLU DÜZENLE"></div>
</form>
<?php } ?>		
<?php include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php"); ?>
