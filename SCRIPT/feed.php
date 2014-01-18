<?php
	define('yakusha', 1);
	include("_header.php");

	$title = $YAKUSHA["site_baslik"];

	/*
	* prepare to $metin for rss
	*/
	function pco_rss_metin_hazirla($metin)
	{
		$metin = trim(strip_tags($metin));
		$metin = stripslashes($metin);
		// embed $metin into CDATA tags in case it contains HTML tags or entities
		if (preg_match('/<[^>]+>|&#?[\w]+;/', $metin))
		{
			// replace any ]]>
			$metin = str_replace(']]>', ']]&gt;', $metin);
			//$metin = '<![CDATA[' . $metin . ']]>';
		}
		//$metin = str_replace('`', '\'', $metin);
		//$metin = str_replace('', '\'', $metin);
		return $metin;
	}

	/**
	* create a date according to  RFC 822 for RSS2
	*/
	function pco_format_date($tarih)
	{
		return date('D, d M Y H:i:s O', $tarih);
	}

	// get time, use current time
	$last_build_date = mktime();

	$cat 			= $_REQUEST["cat"]; 	settype($cat,"integer");
	$search 		= $_REQUEST["search"]; 	$search = f_secure_search($search);

	//get için kullanılacak değerler
	$get_search 	= $_REQUEST["search"]; 

	//sql ilavelerini oluşturuyoruz
	if($cat) 		$sqlilavesi = 'AND tweet_cat ='.$cat;
	if ($search) 	$sqlilavesi = 'AND (tweet_text LIKE "%'.$search.'%" OR tweet_desc LIKE "%'.$search.'%")';

	//içeriği getiren sql sorgusunu çalıştıralım
	$sql = 'SELECT * FROM rss_tweet WHERE tweet_id > 0 '.$sqlilavesi.' ORDER BY tweet_id DESC limit 0,120';

	$vt->sql($sql)->sor($cachetime);
	//echo $vt->alSql();
	$sonuc = $vt->alHepsi();
	$bulunanadet = $vt->numRows();

					$title = $YAKUSHA['site_isim'].' Tweetleri İçin Feed';
	if($search) 	$title = '"'.$get_search.'" Kelime Araması İçin Feed | '.$YAKUSHA['site_isim'];	
	if($cat) 		$title = '"'.$array_kategorilistesi[$cat]["cat_name"].'" Kategorisi İçin Feed | '.$YAKUSHA['site_isim'];

	$sayfabilgisi = '<?xml version="1.0" encoding="UTF-8"?>
	<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
	<channel>
		<title>'.$title.'</title>
		<link>'.SITELINK.'/feed.php</link>
		<description>'.$title.'</description>
		<language>TR-tr</language>
		<pubDate>'.pco_format_date($last_build_date).'</pubDate>
		<lastBuildDate>'.pco_format_date($last_build_date).'</lastBuildDate>
		<docs></docs>
		<generator>Sabri ÜNAL</generator>
		<managingEditor></managingEditor>
		<webMaster></webMaster>
	';

	for ( $i = 0; $i < $bulunanadet; $i++)
	{
		//sorgudan alınıyor
		$tweet_id 		= $sonuc[$i]->tweet_id;
		$tweet_url 		= $sonuc[$i]->tweet_url;
		$tweet_text 		= tr_ucwords($sonuc[$i]->tweet_text);
		$tweet_desc 		= tr_ucwords($sonuc[$i]->tweet_desc);
		$tweet_cat 		= $sonuc[$i]->tweet_cat;
		$tweet_tar 		= $sonuc[$i]->tweet_tar;
		$tweet_short 	= $sonuc[$i]->tweet_short;
		$changetar 		= $sonuc[$i]->changetar;
		//slash işaretlerini temizleyelim
		$tweet_text 		= stripslashes($tweet_text);
		$tweet_desc 		= stripslashes($tweet_desc);

		//link açıklamasını parantez içine alalım
		if($tweet_desc <> '') $tweet_desc = '<br><em>('.$tweet_desc.')</em>';
		//tarih etiketimizi damgaya dönüştürelim
		$tweet_tar_label = label2str($tweet_tar);		

		if($tweet_short == '') $tweet_short = $tweet_url;
		$changetar = pco_format_date($changetar);

		$content_link = ANASAYFALINK.'?tweet=' . $tweet_id;
		$content = $tweet_text.'&nbsp;<a href="'.$tweet_short.'" title="'.$tweet_url.'" target="_blank" class="twitter-timeline-link">'.$tweet_short.'</a>&nbsp;'.$tweet_desc;
	
		$sayfabilgisi.= "\n<item>\n";
		$sayfabilgisi.= "\t<dc:creator>@linuxhaber</dc:creator>\n";
		$sayfabilgisi.= "\t<pubDate>".$changetar."</pubDate>\n";
		$sayfabilgisi.= "\t<link>".$content_link."</link>\n";
		$sayfabilgisi.= "\t<guid>".$content_link."</guid>\n";
		$sayfabilgisi.= "\t<title>".$tweet_text."</title>\n";
		$sayfabilgisi.= "\t<description><![CDATA[".$content."]]></description>\n";
		$sayfabilgisi.= "</item>";
	}
	$sayfabilgisi .= "\n\t</channel>\n\t</rss>";
	header('Content-type: application/xml');
	echo $sayfabilgisi;
?>
