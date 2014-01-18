<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="google-site-verification" content="DoXQO-ztrdY7CXEjc7oujoJuCbB8Fq2WHXiqeWftEzA" />
<meta http-equiv="Content-Language" content="tr">
<meta http-equiv="Cache-Control" content="public">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="category" content="general">
<meta name="robots" content="index, follow">
<meta name="distribution" content="global">
<meta name="resource-type" content="document">
<link rel="stylesheet" type="text/css" href="<?php echo SITELINK?>/style.css" media="screen">
<?php if ($site_link_canonical) echo '<link rel="canonical" href="'.$site_link_canonical.'">';?>
<title><?php echo $sayfa_baslik?></title>
<link href="<?php echo SITELINK?>/mozilla.xml" rel="search" type="application/opensearchdescription+xml" title="Linux Haber arama motoru" />
<link rel="alternate" type="application/rss+xml" title="<?php echo $YAKUSHA[site_isim_big]?> Tweetleri İçin Feed | <?php echo $YAKUSHA[site_slogan]?>" href="<?php echo FEEDLINK?>">
<link rel="alternate" type="application/rss+xml" title="<?php echo $YAKUSHA[site_isim_big]?> Bültenleri İçin Feed | <?php echo $YAKUSHA[site_slogan]?>" href="<?php echo FEEDBLINK?>">
<?php if ($site_feeds) echo $site_feeds?>
</head>
<body>
<noscript>
LİNUX HABER, LİNUX DÜNYASINDAN GÜNCEL HABERLER
</noscript>
<div id="header-wrapper">
<div id="header">
</div>
</div>
<div id="logo">
<h1><a href="<?php echo SITELINK?>" title="<?php echo $YAKUSHA["site_isim_big"]?>"><?php echo $YAKUSHA["site_isim_short"]?></a></h1>
<p><?php echo $YAKUSHA["site_slogan_big"]?></p>
<div class="logobanner">
<!-- linux atasözleri -->

piii 800mhz 128mb bir makinede ayni anda web server, mail server, gateway/firewall, ftp server, smb file server, oracle8i server calistirip bana misin demeyen akil alip agzi acik baktiran uptime rekoruna kosan deli fisek oha birsey.
<br>
<br>(zen, 31.05.2001 06:59 ~ 22:41) - Ekşi Sözlük #Linux Maddesi

<!-- linux atasözleri sonu -->

</div>
</div>
<div id="wrapper">
<div id="page">
<div id="page-bgtop">

<script type="text/javascript" src="<?php echo SITELINK?>/_lib_js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo SITELINK?>/_lib_js/eyceks.php"></script>
<script type="text/javascript">

function noError()
{
	return true;
}
window.onerror = noError;


function more(url,id)
{
	var url,id;
	JXP(1, 'moretweet'+id, url , '')	
	//alert(url);
}

$(document).ready
(
	function()
	{ 
		$(window).scroll
		(
			function()
			{ 
				if ($(window).scrollTop() == $(document).height() - $(window).height())
				{ 
					var url, mtweet, cat, label, search;
					mtweet = document.getElementById('mtweet').value;
					url = document.getElementById('url').value;
					more(url,mtweet);
				}
			}
		);
	}
);

function tweet_it()
{
	var cat, tweet, url;
	cat = document.getElementById('cat').value;
	tweet = document.getElementById('tweet').value;
	var sc = 'tweetthis=1&cat='+cat+'&tweet='+tweet;
	JXP(1, 'load', '<?php echo SITELINK?>/_ajax.php', sc);
}

<?php if($_SESSION[SES]["giris"] == 1) { ?>

function ConfirmTweetSil(id)
{
	if (confirm("Bu Tweeti silmek istediğinize emin misiniz?"))
	{
		tweet_sil(id);
	}
}

function tweet_sil(id)
{
	var id;
	var sc = 'tweet_del=1&tweet='+id;
	JXP(1, 'tweetsil'+id, '<?php echo SITELINK?>/_ajax.php', sc);
}

<?php } ?>

</script>

