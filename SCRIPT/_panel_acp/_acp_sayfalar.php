<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php"); 

$duzenle 	= $_REQUEST['duzenle']; 	settype($id,"integer");
$ekle 		= $_REQUEST['ekle']; 		settype($ekle,"integer");

if ($duzenle > 0)
{
	include($siteyolu."/_panel_acp/_acp_sayfalar_duzenle.php");
}
elseif ($ekle > 0)
{
	include($siteyolu."/_panel_acp/_acp_sayfalar_ekle.php");
}
else
{
 	if (isset ($_REQUEST["topluduzenle"]))
	{
		//kategori açıklama bilgilerini güncelliyoruz
		foreach ($page_order as $k => $v)
		{
			$vt->sql('UPDATE rss_page SET page_order = %s WHERE id = %u')->arg($v,$k)->sor();
		}
		$islemsonucu = '<div class="successbox">İşlem Başarı İle Tamamlandı.</div>';
	}

	$text_title 				= 'Sayfalar';
	$text_tumliste 				= 'Tüm Sayfalar';
	$text_pageinfo 				= 'Bu paneli kullanarak dinamik olmayan sayfalarınızın bilgilerini güncelleyebilirsiniz.';

	$acp_sayfalink 				= $acp_sayfalarlink;
	$acp_menuname 				= 'sayfalar';
	$tablename 					= 'rss_page';
	$tableid 					= 'id';

	$page_count_sql 			= 'SELECT count(id) as sayim FROM '.$tablename.' WHERE id > 0';
	$page_full_sql 				= 'SELECT * FROM '.$tablename.' WHERE id > 0'; 

	$array_sablon["text"][1] 	= 'TIME'; 
	$array_sablon["type"][1] 	= 'tarih'; 
	$array_sablon["sql"][1] 	= 'createtar'; 
	
	$array_sablon["text"][2] 	= 'ID'; 
	$array_sablon["type"][2] 	= 'id'; 
	$array_sablon["sql"][2] 	= 'id'; 

	$array_sablon["text"][3] 	= 'Sayfa Adı'; 
	$array_sablon["type"][3] 	= 'page_name'; 
	$array_sablon["sql"][3] 	= 'page_name'; 

	$array_sablon["text"][4] 	= 'Sayfa Başlığı'; 
	$array_sablon["type"][4] 	= 'page_title'; 
	$array_sablon["sql"][4] 	= 'page_title'; 

	$array_sablon["text"][5] 	= ''; 
	$array_sablon["type"][5] 	= 'page_image'; 
	$array_sablon["sql"][5] 	= 'page_image'; 

	$array_sablon["text"][6] 	= 'Sayfa Metni'; 
	$array_sablon["type"][6] 	= 'page_content'; 
	$array_sablon["sql"][6] 	= 'page_content'; 

	$array_sablon["text"][7] 	= 'Sıralama'; 
	$array_sablon["type"][7] 	= 'page_order'; 
	$array_sablon["sql"][7] 	= 'page_order'; 

	$array_sablon["text"][8] 	= 'Durum'; 
	$array_sablon["type"][8] 	= 'page_status'; 
	$array_sablon["sql"][8] 	= 'page_status'; 

	$siralama 					= '';
	$sorguilavesi 				= '';

	//form için gereken değişkenler alınıyor
	$listetipi 					= $_REQUEST['lt'];
	$limit 						= $_REQUEST['limit'];
	$siralamatipi 				= $_REQUEST['order'];
	$by = $_REQUEST['by'];
	if ($by == 0) 
	{
		$by = 0;
		$order_by = 'ASC';
	}
	else
	{
		$by = 1;
		$order_by = 'DESC';
	}

	//formu oluşturmaya başlıyoruz
	//iç sıralamalar oluşturuluyor
	if ($siralamatipi == $array_sablon["type"][1])
	{
		$siralama = $array_sablon["sql"][1].' '.$order_by;	
	}
	else if ($siralamatipi == $array_sablon["type"][2])
	{
		$siralama = $array_sablon["sql"][2].' '.$order_by;
	}
	else if ($siralamatipi == $array_sablon["type"][3])
	{
		$siralama = $array_sablon["sql"][3].' '.$order_by.', '.$array_sablon["sql"][2].' ASC';	
	}
	else if ($siralamatipi == $array_sablon["type"][4])
	{
		$siralama = $array_sablon["sql"][4].' '.$order_by.', '.$array_sablon["sql"][2].' ASC';	
	}
	else if ($siralamatipi == $array_sablon["type"][5])
	{
		$siralama = $array_sablon["sql"][5].' '.$order_by.', '.$array_sablon["sql"][2].' ASC';	
	}
	else if ($siralamatipi == $array_sablon["type"][6])
	{
		$siralama = $array_sablon["sql"][6].' '.$order_by.', '.$array_sablon["sql"][2].' ASC';	
	}
	else if ($siralamatipi == $array_sablon["type"][7])
	{
		$siralama = $array_sablon["sql"][7].' '.$order_by.', '.$array_sablon["sql"][2].' ASC';	
	}
	else if ($siralamatipi == $array_sablon["type"][8])
	{
		$siralama = $array_sablon["sql"][8].' '.$order_by.', '.$array_sablon["sql"][2].' ASC';	
	}
	else
	{
		$siralama = 'page_order ASC';	
	}
	//sıralama tipi oluşturuluyor
	if (!$siralama) $siralama = $array_sablon["sql"][1].' '.$order_by;

	//limit sorgusu oluşturuluyor
	$sayfasonucmiktari = 20;
	
	if ($limit > 0)
	{ 
		$limitsorgusu = "limit ".($limit*$sayfasonucmiktari ).",".$sayfasonucmiktari;
	} 
	else
	{ 
		$limitsorgusu = "limit 0,".$sayfasonucmiktari ;
	}

	if ($limit == "hepsi") $limitsorgusu = '';
	
	//sayım için sorgu gönderiliyor
	$vt->sql($page_count_sql.' '.$sorguilavesi)->sor();
	$sayim = $vt->alTek();
	$sayi = ($sayim /$sayfasonucmiktari);

	//sayfalama özelliği başlatılıyor
	if($sayi > 1)
	{
		$sayfalama = '<div class="successbox">Sayfalar: ';
		for ($i = 0; $i < $sayi; $i++)
		{
			$sayfalama.= '<a href="'.$acp_sayfalink.'';
			if ($listetipi)
			{
				$sayfalama.='&amp;lt='.$listetipi;
			}
			if ($siralamatipi)
			{
				$sayfalama.='&amp;order='.$siralamatipi;
			}
			if ($by)
			{
				$sayfalama.='&amp;by='.$by;
			}
			$sayfalama.= ($limit == $i && $limit <> "hepsi") ? '&amp;limit='.$i.'"><strong> '.($i+1).' </strong></a> |' : '&amp;limit='.$i.'"> '.($i+1).' </a> |';		
		}
		$sayfalama.= ' <a href="'.$acp_sayfalink.'&amp;lt='.$listetipi;
		if ($siralamatipi)
		{
			$sayfalama.='&amp;order='.$siralamatipi;
		}
		if ($by)
		{
			$sayfalama.='&amp;by='.$by;
		}
		$sayfalama.= '&limit=hepsi">';
		$sayfalama.= ($limit == "hepsi") ? 'Hepsi ' : 'Hepsi';
		$sayfalama.= '</a> | ('.$sayim.' adet)';
		$sayfalama.= "</div>";
	}

	$sayfalink = $acp_sayfalink;
	if ($listetipi)
	{
		$sayfalink.='&amp;lt='.$listetipi;
	}

	//sql sorgusu oluşturuluyor
	$vt->sql($page_full_sql.' '.$sorguilavesi.' ORDER BY '.$siralama.' '.$limitsorgusu)->sor();
	$sonuc_liste = $vt->alHepsi();
	$adet = $vt->numRows();
	//sayfa içi oluşturuluyor, döne döne
	if ($adet)
	{
		for ( $i = 0; $i < $adet; $i++)
		{
			$id = $sonuc_liste[$i]->$tableid;
			$sonuc_2 = $sonuc_liste[$i]->$array_sablon["sql"][2];
			$sonuc_3 = $sonuc_liste[$i]->$array_sablon["sql"][3];
			$sonuc_4 = $sonuc_liste[$i]->$array_sablon["sql"][4];
			$sonuc_5 = $sonuc_liste[$i]->$array_sablon["sql"][5];
			$sonuc_6 = $sonuc_liste[$i]->$array_sablon["sql"][6];
			$sonuc_7 = $sonuc_liste[$i]->$array_sablon["sql"][7];
			$sonuc_8 = $sonuc_liste[$i]->$array_sablon["sql"][8];

			//renklendirme
			if ($i%2) { $trcolor = "col2"; } else { $trcolor = "col1"; }

			//slash işaretleri temizleniyor
			$sonuc_2 = stripslashes($sonuc_2);
			$sonuc_3 = stripslashes($sonuc_3);
			$sonuc_4 = stripslashes($sonuc_4);
			$sonuc_5 = stripslashes($sonuc_5);
			$sonuc_6 = stripslashes($sonuc_6);
			$sonuc_7 = stripslashes($sonuc_7);
			$sonuc_8 = stripslashes($sonuc_8);

			//düzenlenmesi gereken değerler
			$sonuc_2 = '<a href="'.$acp_sayfalink.'&amp;duzenle='.$id.'"><img src="'.SITELINK.'/_img/icon_edit.gif">Düzenle</a>';
			$sonuc_3 = '<a href="'.SITELINK.'/sayfalar.php?sayfa='.$sonuc_3.'">'.$sonuc_4.'</a>';
			if ($sonuc_5 <> '') $sonuc_5 = '<img width="40" src="'.SITELINK.'/_img/_cat/'.$sonuc_5.'">'; 
			$sonuc_6 = strip_tags($sonuc_6); 
			$sonuc_6 = substr($sonuc_6, 0, 30);
			$sonuc_7 = '<input style="width: 30px" type="text" name="page_order['.$id.']" value="'.$sonuc_7.'">';
			$sonuc_8 = $array_page_status[$sonuc_8];

			$sayfabilgisi.= '
			<tr class="'.$trcolor.'">
				<td>'.$sonuc_2.'</td>
				<td>'.$sonuc_3.'</td>
				<td>'.$sonuc_4.'</td>
				<td width="40">'.$sonuc_5.'</td>
				<td>'.$sonuc_6.'</td>
				<td>'.$sonuc_7.'</td>
				<td>'.$sonuc_8.'</td>
			</tr>';
		}
	}
	else
	{
		$sayfabilgisi = '<div class="errorbox">Hiçbir Sonuç Bulunamadı!</div>';
	}
?>	

<?php echo $ilavebilgi?>

<a class="button1" href="<?php echo $acp_sayfalink?>&amp;ekle=1"><img src="<?php echo SITELINK?>/_img/icon_ekle.png">SAYFA EKLE</a>
<?php echo $text_pageinfo?><br>
<br>
<?php echo $islemsonucu?>

<form name="topluduzenle" action="<?php echo $acp_sayfalarlink?>" method="POST">
<input type="hidden" name="menu" value="sayfalar">
<input type="hidden" name="topluduzenle" value="1">

<table class="vitrinler" width="%100" border="0" cellpadding="3" cellspacing="3">
<tr>
<td colspan="9"><?php echo $sayfalama?></td>
</tr>
<tr>

<th width="70">
<?php if($siralamatipi == $array_sablon["type"][1] && $by == 0) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][1].'&by=1"">'.$array_sablon["text"][1].' <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == $array_sablon["type"][1] && $by == 1) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][1].'&by=0">'.$array_sablon["text"][1].' <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][1].'">'.$array_sablon["text"][1].'</a> '; } ?>
</th>

<th>
<?php if($siralamatipi == $array_sablon["type"][3] && $by == 0) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][3].'&by=1"">'.$array_sablon["text"][3].' <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == $array_sablon["type"][3] && $by == 1) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][3].'&by=0">'.$array_sablon["text"][3].' <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][3].'">'.$array_sablon["text"][3].'</a> '; } ?>
</th>

<th>
<?php if($siralamatipi == $array_sablon["type"][4] && $by == 0) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][4].'&by=1"">'.$array_sablon["text"][4].' <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == $array_sablon["type"][4] && $by == 1) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][4].'&by=0">'.$array_sablon["text"][4].' <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][4].'">'.$array_sablon["text"][4].'</a> '; } ?>
</th>

<th>
<?php if($siralamatipi == $array_sablon["type"][5] && $by == 0) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][5].'&by=1"">'.$array_sablon["text"][5].' <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == $array_sablon["type"][5] && $by == 1) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][5].'&by=0">'.$array_sablon["text"][5].' <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][5].'">'.$array_sablon["text"][5].'</a> '; } ?>
</th>

<th>
<?php if($siralamatipi == $array_sablon["type"][6] && $by == 0) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][6].'&by=1"">'.$array_sablon["text"][6].' <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == $array_sablon["type"][6] && $by == 1) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][6].'&by=0">'.$array_sablon["text"][6].' <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][6].'">'.$array_sablon["text"][6].'</a> '; } ?>
</th>

<th>
<?php if($siralamatipi == $array_sablon["type"][7] && $by == 0) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][7].'&by=1"">'.$array_sablon["text"][7].' <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == $array_sablon["type"][7] && $by == 1) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][7].'&by=0">'.$array_sablon["text"][7].' <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][7].'">'.$array_sablon["text"][7].'</a> '; } ?>
</th>

<th>
<?php if($siralamatipi == $array_sablon["type"][8] && $by == 0) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][8].'&by=1"">'.$array_sablon["text"][8].' <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == $array_sablon["type"][8] && $by == 1) { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][8].'&by=0">'.$array_sablon["text"][8].' <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order='.$array_sablon["type"][8].'">'.$array_sablon["text"][8].'</a> '; } ?>
</th>

<?php echo $sayfabilgisi?>

<tr>
<td colspan="9"><?php echo $sayfalama?></td>
</tr>
</table>
<div align="right"><input class="button1" type="submit" name="topluduzenle" value="TOPLU DÜZENLE"></div>
</form>

<?php
	//(if $id > 0) ... else sonu
	}
?>

<?php include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php"); ?>
