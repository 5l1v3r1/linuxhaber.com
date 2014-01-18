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

	//içeriği getiren sql sorgusunu çalıştıralım
	$sql = 'SELECT * FROM rss_bulten WHERE bulten_id > 0 AND bulten_status = 1 ORDER BY bulten_id DESC limit 0,20';
	$vt->sql($sql)->sor($cachetime);
	//echo $vt->alSql();
	$sonuc = $vt->alHepsi();
	$bulunanadet = $vt->numRows();
	$title = $YAKUSHA['site_isim'].' Bültenleri İçin Feed';
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
		$bulten_id 		= $sonuc[$i]->bulten_id;
		$bulten_name 	= tr_ucwords($sonuc[$i]->bulten_name);
		$changetar 		= $sonuc[$i]->changetar;
		//slash işaretlerini temizleyelim
		$bulten_name 	= stripslashes($bulten_name);
		$changetar 		= pco_format_date($changetar);

		$bulten_link = BULTENLERLINK.'?bulten=' . $bulten_id;
		$bulten = $YAKUSHA['site_isim'].' '.$bulten_name.' Bülteni Yayınlanmıştır. <a href="'.$bulten_link.'">Okumak İçin Tıklayın &raquo; &raquo; </a>';
	
		$sayfabilgisi.= "\n<item>\n";
		$sayfabilgisi.= "\t<dc:creator>@linuxhaber</dc:creator>\n";
		$sayfabilgisi.= "\t<pubDate>".$changetar."</pubDate>\n";
		$sayfabilgisi.= "\t<link>".$bulten_link."</link>\n";
		$sayfabilgisi.= "\t<guid>".$bulten_link."</guid>\n";
		$sayfabilgisi.= "\t<title>".$YAKUSHA['site_isim']. ' '.$bulten_name." Bülteni</title>\n";
		$sayfabilgisi.= "\t<description><![CDATA[".$bulten."]]></description>\n";
		$sayfabilgisi.= "</item>";
	}
	$sayfabilgisi .= "\n\t</channel>\n\t</rss>";
	header('Content-type: application/xml');
	echo $sayfabilgisi;
?>
