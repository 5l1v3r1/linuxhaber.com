<?php
	if (!defined('yakusha')) die('...');
	if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

	if (isset($_REQUEST["form"]))
	{
		//varsayılan değerler
		$changetar 		= time();
		//metin gelmesi gereken alanlar
		$cat_name 		= addslashes(trim($_REQUEST["cat_name"]));
		$cat_image 		= addslashes(trim($_REQUEST["cat_image"]));
		//sayı gelmesi gereken alanlar
		$cat_order 		= $_REQUEST["cat_order"];

		//HATA KONTROLÜ
		if ( strlen($cat_name) < 2 or !eregi("[[:alpha:]]",$cat_name) )
		$islem_bilgisi = '<div class="errorbox">Kategori Adı alanını boş bırakmayınız.</div>';

		if ($islem_bilgisi == '')
		{
			$vt->sql('INSERT INTO rss_cat (cat_name, cat_image, cat_order, createtar, changetar ) VALUES ( %s, %s, %u, %u, %u )');
			$vt->arg($cat_name, $cat_image, $cat_order, $changetar, $changetar)->sor();
			$islem_bilgisi = '<div class="successbox">'.stripslashes($cat_name).' isimli kategori sisteme eklenmiştir.</div>';
			//cache dizinini temizleyelim
			temizle_cache();
		}
	}
?>

<form name="form1" action="<?php echo $acp_kategorilerlink?>&amp;ekle=1" method="POST">
<input type="hidden" name="menu" value="kategoriler">
<input type="hidden" name="islem" value="ekle">
<input type="hidden" name="ekle" value="1">

<h1>Yeni Kategori Ekle</h1>

<?php echo $islem_bilgisi ?>


<table valign="top" width="100%" cellspacing="3" border="0">
<tr>
<th height="25" colspan="2">
</th>
<th>
<input class="button1" id="form" name="form" value="KATEGORİ EKLE" type="submit">
</th>
</tr>

<tr><td width="150" height="30">Kategori Adı </td><td> : </td><td><div><input type="text" name="cat_name" style="width: 250px"> <font color="red">*</font></div></td></tr>

<tr><td width="150" height="30">Kategori Resmi </td><td> : </td><td><div><input type="text" name="cat_image" style="width: 250px"> <font color="red">*</font></div></td></tr>

<tr><td height="30">Kategori Sıralaması </td><td> : </td><td><div><input type="text" name="cat_order" style="width: 50px"></div></td></tr>

</table>

</form>
<pre>
* Kırmızı işaretli alanların doldurulması zorunludur.
</pre>
