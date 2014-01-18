<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php"); 

$duzenle	= $_REQUEST['duzenle']; settype($duzenle,"integer");
$ekle 		= $_REQUEST['ekle']; 	settype($ekle,"integer");
if ($duzenle > 0)
{
	include($siteyolu."/_panel_acp/_acp_kategoriler_duzenle.php");
}
elseif ($ekle > 0)
{
	include($siteyolu."/_panel_acp/_acp_kategoriler_ekle.php");
}
else
{

	//toplu güncelleme için gereken değerleri alıyoruz
	$cat_name 		= $_REQUEST["cat_name"];
	$cat_name_en 	= $_REQUEST["cat_name_en"];
	$cat_image 		= $_REQUEST["cat_image"];
 	$cat_order 		= $_REQUEST["cat_order"];

	//formla ilgili öncelikli işlemler varsa tamamlıyoruz
 	if (isset ($_REQUEST["form1"]))
	{
		//kategori isim bilgilerini güncelliyoruz
		foreach ($cat_name as $k => $v)
		{
			$v = addslashes(trim($v));
			$vt->sql('UPDATE rss_cat SET cat_name = %s WHERE cat_id = %u')->arg($v,$k)->sor();
		}

		//kategori isim ingilizce bilgilerini güncelliyoruz
		foreach ($cat_name_en as $k => $v)
		{
			$v = addslashes(trim($v));
			$vt->sql('UPDATE rss_cat SET cat_name_en = %s WHERE cat_id = %u')->arg($v,$k)->sor();
		}

		//kategori resim bilgilerini güncelliyoruz
		foreach ($cat_image as $k => $v)
		{
			$v = addslashes(trim($v));
			$vt->sql('UPDATE rss_cat SET cat_image = %s WHERE cat_id = %u')->arg($v,$k)->sor();
		}

		//kategori sıralaması bilgilerini güncelliyoruz
		foreach ($cat_order as $k => $v)
		{
			settype($v,"integer");
			$vt->sql('UPDATE rss_cat SET cat_order = %u WHERE cat_id = %u')->arg($v,$k)->sor();
		}


		$islemsonucu = '<div class="successbox">İşlem Başarı İle Tamamlandı.</div>';
	}

	//normal form alanına dönüyoruz
	//sql sorgusu oluşturuluyor
	$vt->sql('SELECT * FROM rss_cat ORDER BY cat_order ASC')->sor();
	$sonuc = $vt->alHepsi();
	$bulunanadet = $vt->numRows();

	if ($bulunanadet)
	{
		$iii = 1;
		for ( $i = 0; $i < $bulunanadet; $i++)
		{
			//sorgudan alınıyor
			$cat_id 		= $sonuc[$i]->cat_id;
			$cat_name 		= $sonuc[$i]->cat_name;
			$cat_name_en	= $sonuc[$i]->cat_name_en;
			$cat_image 		= $sonuc[$i]->cat_image;
			$cat_order 		= $sonuc[$i]->cat_order;

			if ($iii%2)  $trcolor = "col2";  else  $trcolor = "col1";

			$sayfabilgisi.= '
			<tr class="'.$trcolor.'">
				<td>
					<a class="vitrinler" title="kategori bilgilerini düzenle" href="'.$acp_kategorilerlink.'&amp;duzenle='.$cat_id.'">
					<img src="'.SITELINK.'/_img/icon_edit.gif"></a>
				</td>
				<td>
					<div>
						<input style="width: 35px;" type="text" name="cat_order['.$cat_id.']" value="'.$cat_order.'">
						<input style="width: 250px;" type="text" name="cat_name['.$cat_id.']" value="'.$cat_name.'">
						<input style="width: 250px;" type="text" name="cat_name_en['.$cat_id.']" value="'.$cat_name_en.'">
					 </div>
				</td>
				<td>
					<a class="vitrinler" href="'.ANASAYFALINK.'?cat='.$cat_id.'"><img width="28" src="'.SITELINK.'/_img/_cat/'.$cat_image.'"></a>
				</td>
				<td>
					<div>
						<input style="width: 250px;" type="text" name="cat_image['.$cat_id.']" value="'.$cat_image.'">
					 </div>
				</td>
			</tr>';
		}
	}
?>

<a class="button1" href="<?php echo $acp_kategorilerlink?>&ekle=1"><img src="<?php echo SITELINK?>/_img/icon_ekle.png">KATEGORİ EKLE</a>

Bu paneli kullanarak kategori bilgilerinizi güncelleyebilir ve silebilirsiniz.<br><br>

<?php echo $islemsonucu?>

<form name="form1" action="<?php echo $acp_kategorilerlink?>" method="POST">
<input type="hidden" name="menu" value="kategoriler">
<input type="hidden" name="form1" value="1">
<table class="vitrinler" width="%100" border="0" cellpadding="3" cellspacing="3">
<tr>
<th width="1"></th>
<th>KATEGORİ</th>
<th colspan="2">İKON</th>
</tr>
<?php echo $sayfabilgisi?>
<tr>
	<td colspan="12"><div align="right"><input class="button1" type="submit" name="form1" value="TOPLU DÜZENLE"></div></td>
</tr>
</table>

<?php } ?>		
<?php include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php"); ?>
