<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

	include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php"); 

	$ekle 		= $_REQUEST['ekle']; 	//settype($ekle,"integer");

	//toplu güncelleme için gereken değerleri alıyoruz
	//varsayılan değerler
	$changetar = time();

	$bulten_name_s 		= $_REQUEST["bulten_name_s"];
	$bulten_name_en_s 	= $_REQUEST["bulten_name_en_s"];
	$bulten_type_s 		= $_REQUEST["bulten_type_s"];
	$bulten_status_s 	= $_REQUEST["bulten_status_s"];
	$bulten_stime_s 	= $_REQUEST["bulten_stime_s"];
	$bulten_ftime_s 	= $_REQUEST["bulten_ftime_s"];

	$bulten_name 		= $_REQUEST["bulten_name"];
	$bulten_name_en 	= $_REQUEST["bulten_name_en"];
	$bulten_type 		= $_REQUEST["bulten_type"];
	$bulten_status 		= $_REQUEST["bulten_status"];
	$bulten_stime 		= $_REQUEST["bulten_stime"];
	$bulten_ftime 		= $_REQUEST["bulten_ftime"];

	if ($ekle)
	{
		$vt->sql('INSERT INTO rss_bulten ( 
		bulten_name, bulten_name_en, bulten_type, bulten_status, bulten_stime, bulten_ftime, createtar, changetar )
		VALUES ( %s, %s, %u, %u, %u, %u, %u, %u)');
		$vt->arg($bulten_name_s, $bulten_name_en_s, $bulten_type_s, $bulten_status_s, $bulten_stime_s, $bulten_ftime_s, $changetar, $changetar);
		//echo $vt->alSql();
		$vt->sor();
		$islembilgisi = '<div class="successbox">'.stripslashes(stripslashes($bulten_name)).' başlıklı bülten sisteme eklenmiştir.</div>';

		//bellek boşaltalım ki yazı görünsün
		temizle_cache();
	}

	//formla ilgili öncelikli işlemler varsa tamamlıyoruz
 	if (isset ($_REQUEST["form1"]))
	{
		//isim bilgilerini güncelliyoruz
		foreach ($bulten_name as $k => $v)
		{
			$v = addslashes(trim($v));
			$vt->sql('UPDATE rss_bulten SET bulten_name = %s WHERE bulten_id = %u')->arg($v,$k)->sor();
		}

		//ingilizce isim bilgilerini güncelliyoruz
		foreach ($bulten_name_en as $k => $v)
		{
			$v = addslashes(trim($v));
			$vt->sql('UPDATE rss_bulten SET bulten_name_en = %s WHERE bulten_id = %u')->arg($v,$k)->sor();
		}

		//Tip bilgilerini güncelliyoruz
		foreach ($bulten_type as $k => $v)
		{
			settype($v,"integer");
			$vt->sql('UPDATE rss_bulten SET bulten_type = %u WHERE bulten_id = %u')->arg($v,$k)->sor();
		}
		//Status bilgilerini güncelliyoruz
		foreach ($bulten_status as $k => $v)
		{
			settype($v,"integer");
			$vt->sql('UPDATE rss_bulten SET bulten_status = %u WHERE bulten_id = %u')->arg($v,$k)->sor();
		}
		//Stime bilgilerini güncelliyoruz
		foreach ($bulten_stime as $k => $v)
		{
			settype($v,"number");
			$vt->sql('UPDATE rss_bulten SET bulten_stime = %u WHERE bulten_id = %u')->arg($v,$k)->sor();
		}
		//Ftime bilgilerini güncelliyoruz
		foreach ($bulten_ftime as $k => $v)
		{
			settype($v,"number");
			$vt->sql('UPDATE rss_bulten SET bulten_ftime = %u WHERE bulten_id = %u')->arg($v,$k)->sor();
		}
		$islembilgisi = '<div class="successbox">İşlem Başarı İle Tamamlandı.</div>';
	}

	//formun select kutularını oluşturuyoruz
	foreach ($array_bulten_type as $k => $v) 	$bulten_type_options.= '<option value="'.$k.'">'.$v.'</option>'. "\r\n";
	foreach ($array_bulten_status as $k => $v) 	$bulten_status_options.= '<option value="'.$k.'">'.$v.'</option>'. "\r\n";

	//normal form alanına dönüyoruz
	//sql sorgusu oluşturuluyor
	$vt->sql('SELECT * FROM rss_bulten ORDER BY bulten_id DESC')->sor();
	$sonuc = $vt->alHepsi();
	$bulunanadet = $vt->numRows();

	if ($bulunanadet)
	{
		$iii = 1;
		for ( $i = 0; $i < $bulunanadet; $i++)
		{
			//sorgudan alınıyor
			$bulten_id 			= $sonuc[$i]->bulten_id;
			$bulten_name 		= $sonuc[$i]->bulten_name;
			$bulten_name_en		= $sonuc[$i]->bulten_name_en;
			$bulten_type 		= $sonuc[$i]->bulten_type;
			$bulten_status 		= $sonuc[$i]->bulten_status;
			$bulten_stime 		= $sonuc[$i]->bulten_stime;
			$bulten_ftime 		= $sonuc[$i]->bulten_ftime;

			if ($iii%2)  $trcolor = "col2";  else  $trcolor = "col1";

			//formun selected değerlerini oluşturuyoruz
			$bulten_type_selected = '<option value=""'.$bulten_type.'"> &raquo; '. $array_bulten_type[$bulten_type].'</option>';
			$bulten_status_selected = '<option value="'.$bulten_status.'"> &raquo; '. $array_bulten_status[$bulten_status].'</option>';

			$sayfabilgisi.= '
			<tr class="'.$trcolor.'">
				<td>
					<div>
						<input style="width: 180px;" type="text" name="bulten_name['.$bulten_id.']" value="'.$bulten_name.'">
						<input style="width: 180px;" type="text" name="bulten_name_en['.$bulten_id.']" value="'.$bulten_name_en.'">
					</div>
				</td>
				<td>
					<div>
						<select style="width:125px" name="bulten_type['.$bulten_id.']">
						'. $bulten_type_selected.'
						'. $bulten_type_options.'
						</select>
						<select style="width:100px" name="bulten_status['.$bulten_id.']">
						'. $bulten_status_selected.'
						'. $bulten_status_options.'
						</select>
					</div>
				</td>
				<td>
					<div>S/F
					<input style="width: 90px;" type="text" name="bulten_stime['.$bulten_id.']" value="'.$bulten_stime.'">
					<input style="width: 90px;" type="text" name="bulten_ftime['.$bulten_id.']" value="'.$bulten_ftime.'">
					</div>
				</td>
				<td>
					<div>
						<a target="_blank" href="'.BULTENLERLINK.'?bulten='.$bulten_id.'">incele</a>
					</div>
				</td>				
			</tr>';
		}
	}
	else
	{
		$islembilgisi = '<div class="errorbox">Henüz Bülten Tanımlanmamış</div>';
	}
?>

Bu paneli kullanarak bülten bilgilerinizi yönetebilir, yeni bültenler ekleyebilirsiniz.<br>

<?php echo $islembilgisi?>

<form name="form1" action="<?php echo $acp_bultenlerlink?>" method="POST">
<input type="hidden" name="menu" value="bultenler">
<input type="hidden" name="form1" value="1">

<table class="vitrinler" width="%100" border="0" cellpadding="3" cellspacing="3">
<tr class="col2">
<td>
<div>
<input style="width: 180px;" type="text" name="bulten_name_s">
<input style="width: 180px;" type="text" name="bulten_name_en_s">
</div>
</td>
<td width="255px">
<div>
<select style="width:125px" name="bulten_type_s">
<?php echo $bulten_type_options ?>
</select>
<select style="width:100px" name="bulten_status_s">
<?php echo $bulten_status_options ?>
</select>
</div>
</td>
<td>
<div>S/F
<input style="width: 90px;" type="text" name="bulten_stime_s">
<input style="width: 90px;" type="text" name="bulten_ftime_s">
</div>
</td>
<td>
<input class="button1" type="submit" name="ekle" value="Ekle"></div>
</td>
</tr>
<tr><th colspan="12">Bültenler Listesi</th></tr>
<?php echo $sayfabilgisi?>

<tr>
	<td colspan="12"><div align="right"><input class="button1" type="submit" name="form1" value="TOPLU DÜZENLE"></div></td>
</tr>
</table>
</form>

<?php include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php"); ?>
