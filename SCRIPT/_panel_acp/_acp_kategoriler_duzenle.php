<?php
	if (!defined('yakusha')) die('...');
	if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

	$cat_id = $_REQUEST["duzenle"];
	$delete = $_REQUEST["delete"];

	if (isset($_REQUEST["form"]))
	{
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
			$vt->sql('UPDATE rss_cat SET cat_name = %s, cat_order = %u, cat_image = %s WHERE cat_id = %u');
			$vt->arg($cat_name, $cat_order, $cat_image, $cat_id)->sor();
			$islem_bilgisi = '<div class="successbox">Kategori bilgileri güncellenmiştir.</div>';
		}
		//cache dizinini temizleyelim
		temizle_cache();
	}

	if ($cat_id > 0 && $delete == 1)
	{
		$vt->sql('DELETE FROM rss_cat WHERE cat_id = %u')->arg($cat_id)->sor();
		$islem_bilgisi = '<div class="successbox">Kategori Sistemden Silinmiştir.</div>';
		//cache dizinini temizleyelim
		temizle_cache();
	}
	
	$vt->sql('SELECT * FROM rss_cat WHERE cat_id = %u')->arg($cat_id)->sor();
	$sonuc = $vt->alHepsi();

	$cat_id 		= $sonuc[0]->cat_id;
	$cat_name 		= $sonuc[0]->cat_name;
	$cat_image 		= $sonuc[0]->cat_image;
	$cat_order 		= $sonuc[0]->cat_order;
	//temizlemeler
	$cat_name 		= stripslashes($cat_name);
?>

<script>
function confirmDelete(delUrl)
{
	if (confirm("Kategoriyi sistemden silmek istediğinize emin misiniz?"))
	{
		document.location = delUrl;
	}
}
</script>

<form name="form1" action="<?php echo $acp_kategorilerlink?>&amp;duzenle=<?php echo $cat_id?>" method="POST">
<input type="hidden" name="menu" value="kategoriler">
<input type="hidden" name="islem" value="duzenle">
<input type="hidden" name="cat_id" value="<?php echo $cat_id?>">

<h1>Kategori Düzenle &raquo; <?php echo $cat_name?> <img src="<?php echo SITELINK?>/caticon/<?php echo $cat_icon?>"></h1>

<?php echo $islem_bilgisi ?>


<table valign="top" width="100%" cellspacing="3" border="0">
<tr>
<th height="25" colspan="2">
<a class="button1" href="javascript:confirmDelete('<?php echo $acp_kategorilerlink?>&amp;duzenle=<?php echo $cat_id?>&amp;delete=1')">KATEGORİ SİL</a>
</th>
<th>
<input class="button1" id="form" name="form" value="KATEGORİ BİLGİLERİNİ DÜZENLE" type="submit">
</th>
</tr>

<tr><td width="150" height="30">Kategori Adı </td><td> : </td><td><div><input type="text" name="cat_name" style="width: 250px" value="<?php echo $cat_name?>"> <font color="red">*</font></div></td></tr>

<tr><td width="150" height="30">Kategori Resmi </td><td> : </td><td><div><input type="text" name="cat_image" style="width: 250px" value="<?php echo $cat_image?>"> <font color="red">*</font></div></td></tr>

<tr><td height="30">Kategori Sıralaması </td><td> : </td><td><div><input type="text" name="cat_order" style="width: 50px" value="<?php echo $cat_order?>"> </div></td></tr>

</table>

</form>
<pre>
* Kırmızı işaretli alanların doldurulması zorunludur.
</pre>
