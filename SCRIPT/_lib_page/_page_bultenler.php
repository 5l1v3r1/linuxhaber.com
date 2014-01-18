<?php
if (!defined('yakusha')) die('...');

if(!$bulten)
{
	$vt->sql('SELECT * FROM rss_bulten WHERE bulten_status = 1 ORDER BY bulten_id DESC')->sor($cachetime);
	$son = $vt->alHepsi();
	$bulunanadet = $vt->numRows();
	$sayfabilgisi.= '<h2>'.$page_title.'</h2>';
	$sayfabilgisi.= '<ul>';
	for ( $i = 0; $i < $bulunanadet; $i++)
	{
		//sorgudan alınıyor
		$id 			= $son[$i]->bulten_id;
		$bulten_name 	= $son[$i]->bulten_name;
		$tweet_bulten 	= BULTENLERLINK.'?bulten='.$id;
		//echo $lang;
		if($lang == L_EN)
		{
			$tweet_bulten 	= BULTENLERLINK.'?lang='.L_EN.'&amp;bulten='.$id;
			$bulten_name 	= $son[$i]->bulten_name_en;
		}
		$sayfabilgisi.= '<li><a href="'.$tweet_bulten.'" title="'.$bulten_name.'" target="_blank">'.$YAKUSHA["site_isim"].' '.$bulten_name.'</a></li>';
	}
	$sayfabilgisi.= '</ul>';
}

$vt->sql('SELECT * FROM rss_bulten WHERE bulten_id = %s')->arg($bulten)->sor($cachetime);
$sonuc = $vt->alHepsi();

if ($sonuc)
{
	//bülten header bilgilerini çağırıyoruz
	$id 			= $sonuc[0]->bulten_id;
	$bulten_name 	= $sonuc[0]->bulten_name;
	$bulten_stime 	= $sonuc[0]->bulten_stime;
	$bulten_ftime 	= $sonuc[0]->bulten_ftime;
	
	//bülten için sql sorgusunu gönderiyoruz
	if($lang == L_EN) $ilavesql = ' AND tweet_en <> "" ';

	$vt->sql('
		SELECT * FROM rss_tweet, rss_cat 
		WHERE 
			rss_tweet.tweet_cat = rss_cat.cat_id
			'.$ilavesql.'
		AND rss_tweet.tweet_status = 1
		AND rss_tweet.tweet_tar >= "'.$bulten_stime.'"
		AND rss_tweet.tweet_tar <= "'.$bulten_ftime.'"
		ORDER BY 
			rss_cat.cat_order ASC, rss_tweet.tweet_text ASC
	')->sor($cachetime);
	//echo $vt->alSql();	
	$sonuc = $vt->alHepsi();
	$bulunanadet = $vt->numRows();
	$title_ilave = ' ('. $bulunanadet .' tweet bulundu )';

	for ( $i = 0; $i < $bulunanadet; $i++)
	{
		//sorgudan alınıyor
		$id 			= $sonuc[$i]->tweet_id;
		$tweet_url 		= $sonuc[$i]->tweet_url;
		$tweet_text 	= $sonuc[$i]->tweet_text;
		$tweet_desc 	= $sonuc[$i]->tweet_desc;
		$tweet_cat 		= $sonuc[$i]->tweet_cat;
		$tweet_tar 		= $sonuc[$i]->tweet_tar;
		$tweet_short 	= $sonuc[$i]->tweet_short;
		//önceki ve sonraki kategoriyi bulalım
		$tweet_cat_e1 	= $sonuc[($i-1)]->tweet_cat;
		$tweet_cat_a1 	= $sonuc[($i+1)]->tweet_cat;
		$cat_name 		= $array_kategorilistesi[$tweet_cat]["cat_name"];
		//link açıklamasını parantez içine alalım
		if($tweet_desc <> '') $tweet_desc = '<br><small><em>('.$tweet_desc.')</em></small>';
		if($lang == L_EN)
		{ 
			$tweet_desc = '';
			$tweet_text = $sonuc[$i]->tweet_en;
			$cat_name 	= $array_kategorilistesi[$tweet_cat]["cat_name_en"];
		}
		//slash sil, ilk harf büyüt, boşluk temizle
		$tweet_text 	= tr_ucwords(trim(stripslashes($tweet_text)));
		$tweet_desc 	= tr_ucwords(trim(stripslashes($tweet_desc)));

		if($tweet_short == '') $tweet_short = $tweet_url;
		//bölüm başlığı açılışı
		if($tweet_cat <> $tweet_cat_e1) $sayfabilgisi.= '<div class="adresBar"><h2>'.$cat_name.'</h2></div>';
		//ul etiketi açılışı
		if($tweet_cat <> $tweet_cat_e1 ) $sayfabilgisi.= '<ul>';

		$sayfabilgisi.= '<li><a href="'.$tweet_short.'" title="'.$tweet_url.'" target="_blank">'.$tweet_text.'</a>'.$tweet_desc.'</li>';
		//ul etiketi kapatması
		if($tweet_cat <> $tweet_cat_a1 ) $sayfabilgisi.= '</ul>';
		
	}
}

if ($bulunanadet){
?>

<div id="content">
<div class="post">

<div class="adresBar">
	<?php echo $page_title.$title_ilave?>
	<?php if ($_SESSION[SES]["ADMIN"]==1) echo ' | <a href="'.SITELINK.'/acp.php?menu=bultenler&amp;duzenle='.$id.'">Düzenle</a>'; ?>
</div>

<div class="entry entrypage">

<?php echo $sayfabilgisi?>

<div id="disqus_thread"></div>
<script type="text/javascript">
/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
// The following are highly recommended additional parameters. Remove the slashes in front to use.
var disqus_shortname = "linuxhaber";
var disqus_identifier = "<?php echo $site_link_canonical?>";
var disqus_url = "<?php echo $site_link_canonical?>";
var disqus_title = "<?php echo $page_title?>";

/* * * DON'T EDIT BELOW THIS LINE * * */
(function() {
var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
})();
</script>


</div>
</div>
</div>


<?php } else { include($siteyolu."/_lib_temp/_hata.php"); } ?>
