<?php
	define('yakusha', 1);
	include("_header.php");

	//içeriği getiren sql sorgusunu çalıştıralım
	$sql = 'SELECT tweet_id, tweet_text, changetar FROM rss_tweet WHERE tweet_id > 0 ORDER BY tweet_id DESC limit 0,1000';
	$vt->sql($sql)->sor($cachetime);
	$sonuc = $vt->alHepsi();
	$bulunanadet = $vt->numRows();
		
	$sayfabilgisi  = '<' . '?xml version="1.0" encoding="UTF-8"?' . '>';
	$sayfabilgisi .= '<?xml-stylesheet type="text/xsl" href="'.SITELINK.'/sitemap.xsl"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	for ( $i=0 ; $i < $bulunanadet; $i++ ) 
	{
		//sorgudan alınıyor
		$tweet_id 		= $sonuc[$i]->tweet_id;
		$tweet_text 	= $sonuc[$i]->tweet_text;
		$changetar 		= $sonuc[$i]->changetar;

		$tweet_text 	= stripslashes($tweet_text);
		$tweet_text 	= format_url($tweet_text);
		$changetar 		= date('Y-m-d',$changetar);

		$content_link = ANASAYFALINK.'?tweet=' . $tweet_id .'-'.$tweet_text;

		$sayfabilgisi .= "<url>\n";
		$sayfabilgisi .= "\t<loc>$content_link</loc>\n";
		$sayfabilgisi .= "\t<lastmod>$changetar</lastmod>\n";
		$sayfabilgisi .= "\t<changefreq>daily</changefreq>\n";
		$sayfabilgisi .= "\t<priority>0.5</priority>\n";
		$sayfabilgisi .= "</url>\n\n";
	}
	$sayfabilgisi .= "</urlset>\n";
	header('Content-type: application/xml');
	echo $sayfabilgisi;
?>
