<?php
if (!defined('yakusha')) die('...');

$sayfa_baslik = $YAKUSHA["site_baslik"];

$cat 			= $_REQUEST["cat"]; 	settype($cat,"integer");
$user 			= $_REQUEST["user"]; 	settype($user,"integer");
$tweetid		= $_REQUEST["tweet"]; 	settype($tweetid,"integer");
$label 			= $_REQUEST["label"]; 	settype($label,"number");
$search 		= $_REQUEST["search"]; 	$search = f_secure_search($search);

$sayfa 			= $_REQUEST["sayfa"]; 	
$bulten 		= $_REQUEST["bulten"]; 	settype($bulten,"integer");

//get için kullanılacak değerler
$get_search 	= $_REQUEST["search"]; 

//sayfa adına göre SEO, TITLE, FEED değerleri atıyoruz
$sayfaadi = basename($_SERVER['SCRIPT_NAME'],".php");
switch ($sayfaadi)
{
	case 'index':
		if ($cat > 0)
		{
			$sayfaadi = $array_kategorilistesi[$cat]["cat_name"];
			$sayfa_baslik = '"'.$sayfaadi .'" kategorisindeki Tweetler | '. $sayfa_baslik;
			$site_link_canonical = ANASAYFALINK.'?cat='.$cat;			
			$site_feeds = '<link rel="alternate" type="application/rss+xml" 
			title="'.$YAKUSHA['site_isim'].' '.$sayfaadi.' Kategorisi İçin Feed | '.$YAKUSHA['site_slogan'].'"
			href="'.FEEDLINK.'?cat='.$cat.'">';			
		}
		if ($search <> '')
		{
			$sayfa_baslik = '"'.$get_search .'" araması hakkında Tweetler | '. $sayfa_baslik;
			$site_link_canonical = ANASAYFALINK.'?search='.$get_search;			
			$site_feeds = '<link rel="alternate" type="application/rss+xml" 
			title="'.$YAKUSHA['site_isim'].' '.tr_ucwords($get_search).' Araması İçin Feed | '.$YAKUSHA['site_slogan'].'"
			href="'.FEEDLINK.'?search='.$get_search.'">';			
		}
		if ($tweetid > 0)
		{
			$vt->sql('SELECT tweet_text FROM rss_tweet WHERE tweet_id = %u')->arg($tweetid)->sor();
			$baslik = $vt->alTek();
			$baslik = tr_ucwords(trim(stripslashes($baslik)));
			$sayfaadi = $baslik .' | '. $tweetid. '. Tweet';
			$sayfa_baslik = $sayfaadi .' | '. $sayfa_baslik;
			$site_link_canonical = ANASAYFALINK.'?tweet='.$tweetid;			
		}
		if ($label > 0)
		{
			$sayfaadi = label2str($label);
			$sayfa_baslik = $sayfaadi .' tarihli Tweetler | '. $sayfa_baslik;
			$site_link_canonical = ANASAYFALINK.'?label='.$label;			
		}		
		if ($user > 0)
		{
			$sayfaadi = $array_userlist[$user]["user_name"];
			if($user == $_SESSION[SES]["user_id"])
			{
				$sayfa_baslik = 'Tweetlerim | '. $sayfa_baslik;
			}
			else
			{
				$sayfa_baslik = '"'.$sayfaadi .'" kullanıcısına ait Tweetler | '. $sayfa_baslik;
			}
			$site_link_canonical = ANASAYFALINK.'?user='.$user;			
		}
	break;
	case 'sayfalar':
		//sayfa başlık
		$vt->sql('SELECT page_title FROM rss_page WHERE page_name = %s')->arg($sayfa)->sor($cachetime);
		$page_title = $vt->alTek();
		$sayfa_baslik = stripslashes($page_title) .' | '. $sayfa_baslik;
		//canonical url
		$site_link_canonical = SAYFALARLINK.'?sayfa='.$sayfa;		
	break;

	case 'bultenler':
		//sayfa başlık
		$page_title = 'Bültenler';
		$sayfa_baslik = $page_title .' | '. $sayfa_baslik;
		//canonical url
		$site_link_canonical = BULTENLERLINK;	

		if ($bulten > 0)
		{
			//sayfa başlık
			$vt->sql('SELECT bulten_name FROM rss_bulten WHERE bulten_id = %u')->arg($bulten)->sor($cachetime);
			$page_title = $vt->alTek();
			$sayfa_baslik = stripslashes($page_title) .' | '. $sayfa_baslik;
			//canonical url
			$site_link_canonical = BULTENLERLINK.'?bulten='.$bulten;	
			$site_feeds = '<link rel="alternate" type="application/rss+xml" 
			title="'.$YAKUSHA['site_isim'].' Haftalık ve Aylık Bültenler İçin Feed | '.$YAKUSHA['site_slogan'].'"
			href="'.FEEDLINK.'?bulten=1">';			
		}		
	break;
}

include($siteyolu."/_lib_temp/_t_sitebaslangic.php"); 
include($siteyolu."/_lib_page/_page_".$PAGE["islem"].".php");
include($siteyolu."/_lib_temp/_t_siteright.php"); 
include($siteyolu."/_lib_temp/_t_sitebitis.php"); 
?>
