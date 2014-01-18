<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php");
include($siteyolu."/_panel_acp/_temp/_t_adminmenuleri.php");
?>
<div id="main">

<h1><?php echo $YAKUSHA["site_isim"]?> Yönetim Paneline Hoşgeldiniz</h1>

<p>Bu sayfadan panonuz için gerekli olan tüm fonksiyonlara hızlı bir şekilde ulaşabilirsiniz.</p>

<?php echo $mesaj?>

<table>
<tr>
<th width="33%">TWEET YÖNETİMİ</th>
<th width="33%">KATEGORİ YÖNETİMİ</th>
</tr>
<tr>
<td class="middle">
<a class="main-item" href="<?php echo $acp_tweetlink?>"><img src="<?php echo SITELINK?>/_img/_xcp/xicon_tweetlist.png"><br>Tweetler</a>
<a class="main-item" href="<?php echo $acp_tweetlink?>&amp;ekle=1"><img src="<?php echo SITELINK?>/_img/_xcp/xicon_tweetadd.png"><br>Tweet Ekle</a>
</td>
<td class="middle">
<a class="main-item" href="<?php echo $acp_kategorilerlink?>"><img src="<?php echo SITELINK?>/_img/_xcp/xicon_catlist.png"><br>Kategori Listesi</a>
<a class="main-item" href="<?php echo $acp_kategorilerlink?>&amp;ekle=1"><img src="<?php echo SITELINK?>/_img/_xcp/xicon_catadd.png"><br>Kategori Ekle</a>
</td>
<tr>
<th width="33%">ÜYE YÖNETİMİ</th>
<th width="33%">SAYFA YÖNETİMİ</th>
</tr>
</tr>
<tr>
<td class="middle">
<a class="main-item" href="<?php echo $acp_uyelerlink?>"><img src="<?php echo SITELINK?>/_img/_xcp/xicon_userlist.png"><br>Üye Listesi</a>
<a class="main-item" href="<?php echo $acp_uyelerlink?>&amp;ekle=1"><img src="<?php echo SITELINK?>/_img/_xcp/xicon_useradd.png"><br>Üye Ekle</a>
</td>
<td class="middle">
<a class="main-item" href="<?php echo $acp_sayfalarlink?>"><img src="<?php echo SITELINK?>/_img/_xcp/xicon_pagelist.png"><br>Sayfa Listesi</a>
<a class="main-item" href="<?php echo $acp_sayfalarlink?>&amp;ekle=1"><img src="<?php echo SITELINK?>/_img/_xcp/xicon_pageadd.png"><br>Sayfa Ekle</a>
</td>
</tr>
</table>
<!--
<fieldset>
<legend>Hızlı Fonksiyonlar</legend>
<dl>

<dt>
<label>RSS Verilerini Güncelle</label>
</dt>
<dd>
<a href="<?php echo $acp_anamenulink?>&amp;get_rss_all=1"><input class="button2" value="Şimdi çalıştır" type="submit"></a>
</dd>

</dl>
</fieldset>
-->
</div>


<?php include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php"); ?>
