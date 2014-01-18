<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php"); 
$user_name  = '';

$duzenle 	= $_REQUEST['duzenle']; 	settype($user_id,"integer");
$uye_ekle 	= $_REQUEST['ekle']; 		settype($uye_ekle,"integer");
$delete 	= $_REQUEST["delete"]; 		settype($delete,"integer");

if ($delete > 0 )
{
	$vt->sql('DELETE FROM rss_user WHERE user_id = %u')->arg($delete)->sor();
	$islem_bilgisi = '<div class="errorbox">Üye silinmiştir.</div>';
}

if ($duzenle > 0)
{
	include($siteyolu."/_panel_acp/_acp_uyeler_duzenle.php");
}
elseif ($uye_ekle > 0)
{
	include($siteyolu."/_panel_acp/_acp_uyeler_ekle.php");
}
else
{
	$text_title 				= 'Üyeler';
	$text_tumliste 				= 'Tüm Üyeler';
	$text_pageinfo 				= 'Bu paneli kullanarak üyelerinizin bilgilerini güncelleyebilir ve yetkilerini silebilirsiniz.';

	$acp_sayfalink 				= $acp_uyelerlink;
	$acp_menuname 				= 'uyeler';
	$tablename 					= 'rss_user';
	$tableid 					= 'user_id';

	$page_count_sql 			= 'SELECT count(user_id) as sayim FROM '.$tablename.' WHERE user_id > 0';
	$page_full_sql 				= 'SELECT * FROM '.$tablename.' WHERE user_id > 0'; 

	$array_sablon["text"][1] 	= 'TIME'; 
	$array_sablon["type"][1] 	= 'tarih'; 
	$array_sablon["sql"][1] 	= 'createtar'; 
	
	$array_sablon["text"][2] 	= 'ID'; 
	$array_sablon["type"][2] 	= 'user_id'; 
	$array_sablon["sql"][2] 	= 'user_id'; 

	$array_sablon["text"][3] 	= 'SEVİYE'; 
	$array_sablon["type"][3] 	= 'user_status'; 
	$array_sablon["sql"][3] 	= 'user_status'; 

	$array_sablon["text"][4] 	= 'AD, SOYAD'; 
	$array_sablon["type"][4] 	= 'user_username'; 
	$array_sablon["sql"][4] 	= 'user_username'; 

	$array_sablon["text"][5] 	= 'ÜYE ADI'; 
	$array_sablon["type"][5] 	= 'user_name'; 
	$array_sablon["sql"][5] 	= 'user_name'; 

	$array_sablon["text"][6] 	= 'E-POSTA'; 
	$array_sablon["type"][6] 	= 'user_email'; 
	$array_sablon["sql"][6] 	= 'user_email'; 

	$array_sablon["text"][7] 	= 'TWETT DURUM'; 
	$array_sablon["type"][7] 	= 'user_tweet_status'; 
	$array_sablon["sql"][7] 	= 'user_tweet_status'; 

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
	else
	{
		$siralama = 'user_id DESC';	
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
			if ($aramaanahtari)
			{
				$sayfalama.='&amp;aramaanahtari='.$aramaanahtari;
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
		if ($aramaanahtari)
		{
			$sayfalama.='&amp;aramaanahtari='.$aramaanahtari;
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
	if ($aramaanahtari)
	{
		$sayfalink.='&amp;aramaanahtari='.$aramaanahtari;
	}
	
	//sql sorgusu oluşturuluyor
	$vt->sql($page_full_sql.' '.$sorguilavesi.' ORDER BY '.$siralama.' '.$limitsorgusu)->sor();
	$sonuc_liste = $vt->alHepsi();
	$bulunanadet = $vt->numRows();
	//sayfa içi oluşturuluyor, döne döne
	if ($bulunanadet)
	{
		for ( $i = 0; $i < $bulunanadet; $i++)
		{
			$id = $sonuc_liste[$i]->$tableid;
			$sonuc_2 = $sonuc_liste[$i]->$array_sablon["sql"][2];
			$sonuc_3 = $sonuc_liste[$i]->$array_sablon["sql"][3];
			$sonuc_4 = $sonuc_liste[$i]->$array_sablon["sql"][4];
			$sonuc_5 = $sonuc_liste[$i]->$array_sablon["sql"][5];
			$sonuc_6 = $sonuc_liste[$i]->$array_sablon["sql"][6];
			$sonuc_7 = $sonuc_liste[$i]->$array_sablon["sql"][7];

			//renklendirme
			if ($i%2) { $trcolor = "col2"; } else { $trcolor = "col1"; }

			//slash işaretleri temizleniyor
			$sonuc_2 = stripslashes($sonuc_2);
			$sonuc_3 = stripslashes($sonuc_3);
			$sonuc_4 = stripslashes($sonuc_4);
			$sonuc_5 = stripslashes($sonuc_5);
			$sonuc_6 = stripslashes($sonuc_6);
			$sonuc_7 = stripslashes($sonuc_7);

			//düzenlenmesi gereken değerler
			$sonuc_2 = '<a href="'.$acp_sayfalink.'&amp;duzenle='.$id.'"><img src="'.SITELINK.'/_img/icon_edit.gif">Düzenle</a>';
			$sonuc_3 = $array_user_status[$sonuc_3];
			$sonuc_7 = $array_tweet_status[$sonuc_7];

			$sayfabilgisi.= '
			<tr class="'.$trcolor.'">
				<td>'.$sonuc_2.'</td>
				<td>'.$sonuc_3.'</td>
				<td>'.$sonuc_4.'</td>
				<td>'.$sonuc_5.'</td>
				<td>'.$sonuc_6.'</td>
				<td>'.$sonuc_7.'</td>
			</tr>';
		}
	}
	else
	{
		$sayfabilgisi = '<div class="errorbox">Hiçbir Sonuç Bulunamadı!</div>';
	}
?>	

<?php echo $ilavebilgi?>

<a class="button1" href="<?php echo $acp_sayfalink?>&amp;ekle=1"><img src="<?php echo SITELINK?>/_img/icon_ekle.png">ÜYE EKLE</a>
<?php echo $text_pageinfo?><br>
<br>
<?php echo $islem_bilgisi?>

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


<?php echo $sayfabilgisi?>

<tr>
<td colspan="9"><?php echo $sayfalama?></td>
</tr>
</table>
<?php
	//(if $user_id > 0) ... else sonu
	}
?>

<?php include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php"); ?>
