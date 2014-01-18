<?php
if (!defined('yakusha')) die('...');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Language" content="tr">
<meta http-equiv="Cache-Control" content="public">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="refresh" content="<?php echo $sayfa_tazele?>">
<meta name="copyright" content="Sabri ÜNAL">
<title><?php echo $YAKUSHA["site_isim"]?> &bull; Yönetim Paneli</title>
</head>
<body>

<?php include($siteyolu."/_panel_acp/_temp/_t_admincss.php");?>

<div id="wrap">

<div id="page-body">
<div id="tabs">

<?php
$menu = $_GET['menu'];
?>
<ul>
<li <?php 
if(!$menu || $menu == "giris")
echo 'id="activetab"'; ?>><a href="<?php echo $acp_anamenulink?>"><span>YÖNETİM</span></a></li>
<li <?php if($menu == "tweet") echo 'id="activetab"'; ?>><a href="<?php echo $acp_tweetlink?>"><span>TWEETLER</span></a></li>
<li <?php if($menu == "bultenler") echo 'id="activetab"'; ?>><a href="<?php echo $acp_bultenlerlink?>"><span>BÜLTEN YÖNETİMİ</span></a></li>
<li <?php if($menu == "kategoriler") echo 'id="activetab"'; ?>><a href="<?php echo $acp_kategorilerlink?>"><span>KATEGORİLER</span></a></li>
<li <?php if($menu == "sayfalar") echo 'id="activetab"'; ?>><a href="<?php echo $acp_sayfalarlink?>"><span>SAYFALAR</span></a></li>
<li <?php if($menu == "uyeler") echo 'id="activetab"'; ?>><a href="<?php echo $acp_uyelerlink?>"><span>ÜYE YÖNETİMİ</span></a></li>
<li><a href="<?php echo ANASAYFALINK?>"><span>ANA SAYFA</span></a></li>
</ul>
</div>

<div id="acp">
<div class="panel">
<span class="corners-top"><span></span></span>
<div id="content">
