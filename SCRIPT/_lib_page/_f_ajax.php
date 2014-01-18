<?php
if (!defined('yakusha')) die('...');

//sepete ekliyorsak
if ( $_REQUEST["moretweet"] > 0) 
{
	$moretweet 		= $_REQUEST["moretweet"]; 	settype($moretweet, "integer");
	$cat 			= $_REQUEST["cat"]; 		settype($cat,"integer");
	$user 			= $_REQUEST["user"]; 		settype($user,"integer");
	$label 			= $_REQUEST["label"]; 		settype($label,"number");
	$search 		= $_REQUEST["search"]; 		$search = f_secure_search($search);
	//tweet limit yoksa atıyoruz
	$morelimit = 20;

										$sqlstatus 	= 'tweet_status = 1';
	if($_SESSION[SES]["giris"] == 1) 	$sqlstatus 	= 'tweet_status in (1,2)';

	//sql ilavelerini oluşturuyoruz
	if($cat) 		$sqlilavesi = 'AND tweet_cat ='.$cat;
	if($user) 		$sqlilavesi = 'AND tweet_uid ='.$user;
	if($label) 		$sqlilavesi = 'AND tweet_tar ='.$label;
	if($search) 	$sqlilavesi = 'AND (tweet_text LIKE "%'.$search.'%" 
										OR tweet_desc LIKE "%'.$search.'%" 
										OR tweet_en LIKE "%'.$search.'%" 
										OR tweet_url LIKE "%'.$search.'%")';

	if($lang == L_EN) $ilavesql = ' AND tweet_en <> "" ';

	//önce bir sayı sql sorgusu çalıştıralım
	$vt->sql('SELECT count(tweet_id) FROM rss_tweet WHERE '. $sqlstatus . $ilavesql . ' '. $sqlilavesi)->sor();
	$total = $vt->alTek();
	if($total)
	{
		$moretweet1 = ($moretweet+1);

		//devamı butonunu ve linklerini buradan oluşturalım
		//ajax linklerini oluşturuyoruz
						$url = AJAXLINK.'?moretweet='.$moretweet1;
		if($cat) 		$url = AJAXLINK.'?moretweet='.$moretweet1.'&amp;cat='.$cat;
		if($user) 		$url = AJAXLINK.'?moretweet='.$moretweet1.'&amp;user='.$user;
		if($label) 		$url = AJAXLINK.'?moretweet='.$moretweet1.'&amp;label='.$label;
		if ($search) 	$url = AJAXLINK.'?moretweet='.$moretweet1.'&amp;search='.$search;

		if($total > ($moretweet*$morelimit))
		{
			$morebutton = '
			<span id="moretweet'.$moretweet1.'">
				<input type="hidden" id="mtweet" value="'.$moretweet1.'">
				<input type="hidden" id="url" value="'.$url.'">
				<div align="center">
					<a class="elkaldır"><img onmouseover="more(\''.$url.'\','.$moretweet1.')" 
					title="daha fazla haber" src="'.SITELINK.'/_img/more.png"></a>
				</div>			
			</span>';
		}
		else
		{
			$morebutton = '<div class="successbox">Tüm Tweetler Görüntülendi</div>';
		}
	

		//içeriği getiren sql sorgusunu çalıştıralım
		$sql = 'SELECT * FROM rss_tweet WHERE '. $sqlstatus .' '. $sqlilavesi.' ORDER BY tweet_id DESC limit '.($moretweet*$morelimit).',20';

		$vt->sql($sql)->sor();
		//echo $vt->alSql();
		$sonuc = $vt->alHepsi();
		$bulunanadet = $vt->numRows();

		for ( $i = 0; $i < $bulunanadet; $i++)
		{
			//sorgudan alınıyor
			$id 			= $sonuc[$i]->tweet_id;
			$tweet_url 		= $sonuc[$i]->tweet_url;
			$tweet_text 	= $sonuc[$i]->tweet_text;
			$tweet_en 		= $sonuc[$i]->tweet_en;
			$tweet_desc 	= $sonuc[$i]->tweet_desc;
			$tweet_short 	= $sonuc[$i]->tweet_short;
			$tweet_cat 		= $sonuc[$i]->tweet_cat;
			$tweet_tar 		= $sonuc[$i]->tweet_tar;
			$tweet_status	= $sonuc[$i]->tweet_status;
			$tweet_uid 		= $sonuc[$i]->tweet_uid;
			if($lang == L_EN)
			{ 
				$tweet_desc = '';
				$tweet_text = $sonuc[$i]->tweet_en;
				$cat_name 	= $array_kategorilistesi[$tweet_cat]["cat_name_en"];
			}
			//slash işaretlerini temizleyelim
			$tweet_text 	= tr_ucwords(stripslashes(trim($tweet_text)));
			$tweet_en 		= tr_ucwords(stripslashes(trim($tweet_en)));
			$tweet_desc 	= tr_ucwords(stripslashes(trim($tweet_desc)));


			//icon yoksa varsayılan ikon görünsün
			if($tweet_cat == 0) $array_kategorilistesi[$tweet_cat]["cat_image"] = 'default.png';
			//link açıklamasını parantez içine alalım
			if($tweet_desc <> '') $tweet_desc = '<br><em>('.$tweet_desc.')</em>';
			//tarih etiketimizi damgaya dönüştürelim
			$tweet_tar_label = label2str($tweet_tar);		
			//düzenleme linki sadece admine görülsün
			if ($_SESSION[SES]["ADMIN"]==1)
			{
				//linkleri yeniden tanımlamak yerine acp_define dosyasını çağıralım
				include($siteyolu."/_panel_acp/_acp_define.php"); 
				$duzenlelink = '<a title="Düzenle" href="'.$acp_tweetlink.'&amp;duzenle='.$id.'">düzenle</a>&nbsp;&nbsp;';
			}
		
			if($tweet_short == '') $tweet_short = $tweet_url;
			if($tweet_uid == $_SESSION[SES]["user_id"])
			{
				$tweetsil = '
					<div class="layertweetsilbutton">
					<img class="elkaldır" src="'.SITELINK.'/_img/_xcp/xicon_delete.png"
					onclick="ConfirmTweetSil(\''.$id.'\')" title="Tweet Sil">
					</div>';
			}			
			if($tweet_text <> '')
			{
					$sayfabilgisi.= '
					<span id="tweetsil'.$id.'">
					<div class="stream-item" id="tweet'.$id.'">
						<div class="stream-item-content tweet js-actionable-tweet js-stream-tweet stream-tweet">
							<div class="konu-resmi">
								<img src="'.SITELINK.'/_img/_cat/'.$array_kategorilistesi[$tweet_cat]["cat_image"].'" alt="'.tr_ucwords($array_kategorilistesi[$tweet_cat]["cat_name"]).'" height="48" width="48">
							</div>
							<div class="tweet-content">
								<div class="tweet-row">
									<div class="tweet-text js-tweet-text" valing="center">
										'.$tweet_text.' 
										<a href="'.$tweet_short.'" title="'.$tweet_url.'" target="_blank" class="twitter-timeline-link">'.$tweet_short.'</a>
										'.$tweet_desc.'
									</div>
									<div align="right">
										<span class="tweet-actions">
											'.$tweetsil.'
											<a title="'.$id.'. Tweet görüntüle" 
											href="'.ANASAYFALINK.'?tweet='.$id.'&lang='.$lang.'">
											#'.$id.'</a>
											&nbsp;&nbsp;
											<a title="'.$tweet_tar_label.' tarihli Tweetleri görüntüle" 
											href="'.ANASAYFALINK.'?label='.$tweet_tar.'&lang='.$lang.'">
											#'.$tweet_tar_label.'</a>
											&nbsp;&nbsp;
											<a title="'.tr_ucwords($array_kategorilistesi[$tweet_cat]["cat_name"]).' kategorisindeki Tweetleri görüntüle"
											href="'.ANASAYFALINK.'?cat='.$tweet_cat.'&lang='.$lang.'">
											#'.$array_kategorilistesi[$tweet_cat]["cat_name"].'</a>
											&nbsp;&nbsp;
											<a title="'.tr_ucwords($array_userlist[$tweet_uid]["user_name"]).' kullanıcına ait Tweetleri görüntüle"
											href="'.ANASAYFALINK.'?user='.$tweet_uid.'&lang='.$lang.'">
											@'.$array_userlist[$tweet_uid]["user_name"].'</a>
											&nbsp;&nbsp;
											'.$duzenlelink.'
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					</span>	
				';
			}
		}
		echo $sayfabilgisi.$morebutton;
	}
}

if ( $_REQUEST["tweetthis"]) 
{

	$get_tweet 	= stripslashes($_REQUEST["tweet"]);
 	if($_SESSION[SES]["giris"] == 1)
	{
		$cat 		= $_REQUEST["cat"]; 		settype($cat,"integer");
		$tweet 		= $_REQUEST["tweet"];		
			
		$tweet 		= stripslashes(trim($tweet));
		$lasttweet 	= $_SESSION[SES]["lasttweet"];
		if($tweet <> $lasttweet)
		{
			$str 			= str_replace("\n","|", $tweet);
			$str 			= explode('|', $str);
			//echo '1'.$str[0].'<br>2'.$str[1].'<br>3'.$str[2];

			//$str 			= explode('http', $tweet);
			$tweet_text 	= $str[0];
			$tweet_url 		= $str[1];
			$tweet_desc 	= $str[2];
			$tweet_en 	= $str[3];

			$tweet_text 	= addslashes(trim($tweet_text));
			$tweet_url 		= addslashes(trim($tweet_url));
			$tweet_desc 	= addslashes(trim($tweet_desc));
			$tweet_en 		= addslashes(trim($tweet_en));

			$tweet_cat 		= $cat; 					//link kategorisi
			$tweet_url 		= url_hazirla($tweet_url); 	//link hazırlayalım
			$tweet_short 	= shortenUrl($tweet_url); 	//linki kısaltalım
			$tweet_tar 		= date("Ymd",time()); 		//link tarihini
			$changetar 		= time(); 					//varsayılan değerler

			//HATA KONTROLÜ
			//kısa link dönmezse boş dönelim, hata oluşmasın
			if (!$tweet_short) $tweet_short = '';

			if ( strlen($tweet_text) < 2 or !eregi("[[:alpha:]]",$tweet_text) )
			$islem_bilgisi = '<div class="redhat">Tweet Alanını Boş Bırakmayınız.</div>';
			
			$tweet_url_test = substr($tweet_url, 0, 4);

			if ( $tweet_url_test <> 'http' or strlen($tweet_url) < 8)
			$islem_bilgisi = '<div class="redhat">Tweet Bağlantısını Boş Bırakmayınız.</div>';
			//echo $tweet_url;

			if ($islem_bilgisi == '')
			{
				$tweet_status 	= $_SESSION[SES]["user_tweet_status"];
				//echo $tweet_status;
				$tweet_uid 		= $_SESSION[SES]["user_id"];

				$vt->sql('INSERT INTO rss_tweet (tweet_url, tweet_text, tweet_short, tweet_desc, tweet_en, 
							tweet_cat, tweet_tar, tweet_status, tweet_uid, createtar, changetar ) 
							VALUES ( %s, %s, %s, %s, %s, %u, %u, %u, %u, %u, %u)');
				$vt->arg($tweet_url, $tweet_text, $tweet_short, $tweet_desc, $tweet_en, 
							$tweet_cat, $tweet_tar, $tweet_status, $tweet_uid, $changetar, $changetar);
				//echo $vt->alSql();
				$vt->sor();
				$islem_bilgisi = '<div class="bluehat">tweet sisteme eklenmiştir.</div>';
				$get_tweet = '';
			}
		}
		//tweet gitti, son tweet'i bellekleyelim
		$_SESSION[SES]["lasttweet"] = $tweet;
	}
	else
	{
		$islem_bilgisi = '<div class="redhat">Lütfen <a href="'.REGISTERLINK.'">Üye Olunuz</a> veya <a href="'.GIRISLINK.'">Giriş Yapınız</a></div>';
	}
	include($siteyolu.'/_lib_temp/_t_block_tweet.php');
	echo $islem_bilgisi;
}

if ($_REQUEST["tweet_del"]) 
{
	function tweet_delete()
	{
		global $vt;
		$id 	= $_REQUEST["tweet"];
		$uid 	= $_SESSION[SES]["user_id"];
		if($_SESSION[SES]["giris"] == 1)
		{
			$vt->sql('UPDATE rss_tweet SET tweet_status = 0 WHERE tweet_uid = %u AND tweet_id = %u')->arg($uid,$id)->sor();
		}
	}
	tweet_delete();
}

?>
