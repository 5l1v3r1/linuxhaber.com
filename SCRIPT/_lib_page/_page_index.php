<div id="content">
<div class="post">
<div class="entry">
<?php 
if (!defined('yakusha')) die('...');

	$morelimit = 20;
	//tweet limit yoksa atıyoruz
	if (!$moretweet) $moretweet = 1;

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
	//echo $vt->alSql();
	$total = $vt->alTek();

	if($total)
	{
		//devamı butonunu ve linklerini buradan oluşturalım
		//ajax linklerini oluşturuyoruz
						$url = AJAXLINK.'?moretweet='.$moretweet;
		if($cat) 		$url = AJAXLINK.'?moretweet='.$moretweet.'&amp;cat='.$cat;
		if($user) 		$url = AJAXLINK.'?moretweet='.$moretweet.'&amp;user='.$user;
		if($label) 		$url = AJAXLINK.'?moretweet='.$moretweet.'&amp;label='.$label;
		if ($search) 	$url = AJAXLINK.'?moretweet='.$moretweet.'&amp;search='.$search;

		if($total > $morelimit)
		{
			$morebutton = '
			<span id="moretweet'.$moretweet.'">
				<input type="hidden" id="mtweet" value="'.$moretweet.'">
				<input type="hidden" id="url" value="'.$url.'">
				<div align="center">
					<a class="elkaldır"><img onmouseover="more(\''.$url.'\','.$moretweet.')" 
					title="daha fazla haber" src="'.SITELINK.'/_img/more.png"></a>
				</div>			
			</span>';

		}
		else
		{
			$morebutton = '<div class="successbox">Tüm Tweetler Görüntülendi</div>';
		}

		//içeriği getiren sql sorgusunu çalıştıralım
		$sql = 'SELECT * FROM rss_tweet WHERE '. $sqlstatus .' '.$sqlilavesi.' ORDER BY tweet_id DESC limit 0,20';

		$vt->sql($sql)->sor();
		//echo $vt->alSql();
		$sonuc = $vt->alHepsi();
		$bulunanadet = $vt->numRows();

		if($label) 		$sayfabilgisi = '<div class="bluehat">'.$sayfaadi.' tarihli Tweetler</div>';	
		if($tweetid) 	$sayfabilgisi = '<div class="bluehat">'.$tweetid.'. Tweet</div>';	
		if($search) 	$sayfabilgisi = '<div class="bluehat">'.$get_search.' hakkında Tweetler</div>';	
		if($cat) 		$sayfabilgisi = '<div class="bluehat">'.$array_kategorilistesi[$cat]["cat_name"].' kategorisindeki Tweetler</div>';
		if($user)
		{
			$sayfabilgisi = '<div class="bluehat">'.$array_userlist[$user]["user_name"].' kullanıcısına ait Tweetler ('. $total.' adet)</div>';
			if($user == $_SESSION[SES]["user_id"])
			{
				$sayfabilgisi = '<div class="bluehat">Tweetlerim ('.$total.' adet)</div>';
			}
		}
	}

	//tek bir tweet görüntülenmek istiyorsa daha basit bir sorgu çalıştıralım
	if($tweetid) 	
	{
		$vt->sql('SELECT * FROM rss_tweet WHERE tweet_id ='.$tweetid)->sor();
		$sonuc = $vt->alHepsi();
		$bulunanadet = $vt->numRows();
		$morebutton = '';
	}
	
	if($total)
	{
		for ( $i = 0; $i < $bulunanadet; $i++)
		{
			//sorgudan alınıyor
			$id 			= $sonuc[$i]->tweet_id;
			$tweet_url 		= $sonuc[$i]->tweet_url;
			$tweet_text 	= $sonuc[$i]->tweet_text;
			$tweet_en	 	= $sonuc[$i]->tweet_en;
			$tweet_desc 	= $sonuc[$i]->tweet_desc;
			$tweet_short 	= $sonuc[$i]->tweet_short;
			$tweet_cat 		= $sonuc[$i]->tweet_cat;
			$tweet_tar 		= $sonuc[$i]->tweet_tar;
			$tweet_uid 		= $sonuc[$i]->tweet_uid;
			$tweet_status	= $sonuc[$i]->tweet_status;
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
			if($tweet_desc <> '') $tweet_desc 	= '<br><em>('.$tweet_desc.')</em>';
			if($tweet_en <> '') $tweet_en 		= '<br><em>['.$tweet_en.']</em>';
			//tarih etiketimizi damgaya dönüştürelim
			$tweet_tar_label = label2str($tweet_tar);
			//düzenleme linki sadece admine görülsün
			if ($_SESSION[SES]["ADMIN"]==1)
			{
				//linkleri yeniden tanımlamak yerine acp_define dosyasını çağıralım
				include($siteyolu."/_panel_acp/_acp_define.php"); 
				$duzenlelink = '<a title="Düzenle" href="'.$acp_tweetlink.'&amp;duzenle='.$id.'">düzenle</a>&nbsp;&nbsp;';
			}
			if(!$tweet) $tweet_en = '';
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
										<a href="'.$tweet_short.'" title="'.$tweet_url.'" target="_blank">'.$tweet_short.'</a>
										'.$tweet_desc.''.$tweet_en.'
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
	}
	else
	{
		$morebutton = '';
		$sayfabilgisi = '<div class="redhat">Hiçbir tweet bulunamadı</div>';
	}

?>

<link rel="stylesheet" href="style_core.css" type="text/css" media="screen" />

<?php include($siteyolu.'/_lib_temp/_t_block_tweet.php'); ?>

<?php echo $sayfabilgisi.$morebutton ?>

<?php if ($tweetid > 0) { ?>

<br>
<div id="disqus_thread"></div>
<script type="text/javascript">
/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
var disqus_shortname = 'linuxhaber'; // required: replace example with your forum shortname
var disqus_identifier = "<?php echo $site_link_canonical?>";
var disqus_url = "<?php echo $site_link_canonical?>";
var disqus_title = "<?php echo $baslik?>";

/* * * DON'T EDIT BELOW THIS LINE * * */
(function() {
var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
})();
</script>
<?php } ?>

</div>
</div>
</div>
