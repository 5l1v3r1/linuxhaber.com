<?php
	//cache olduğu için cache değerini buradan atadık
	if ($_SESSION[SES]["giris"] == 1) $cachetime = 0; else $cachetime = 600;

	######################################################################################################
	# KATEGORiLER İÇİN DİZİ FONKSİYONU
	######################################################################################################

	//kategoriler için sql sorgusu oluşturuluyor
	$vt->sql('SELECT * FROM rss_cat ORDER BY cat_name ASC')->sor($cachetime);
	$sonuc = $vt->alHepsi();
	$bulunanadet = $vt->numRows();

	if ($bulunanadet)
	{
		for ( $i = 0; $i < $bulunanadet; $i++)
		{
			//sorgudan alınıyor
			$cat_id 		= $sonuc[$i]->cat_id;
			$cat_name 		= $sonuc[$i]->cat_name;
			$cat_name_en	= $sonuc[$i]->cat_name_en;
			$cat_image 		= $sonuc[$i]->cat_image;

			//kimi varsayılan değerler üretiyoruz
			$kategoriler_options.= '<option value="'.$cat_id.'">'.$cat_name.'</option>'. "\r\n";

			//isimler dizide dursun
			$array_kategorilistesi[$cat_id]["cat_id"] 		= $cat_id;		
			$array_kategorilistesi[$cat_id]["cat_name"] 	= $cat_name;		
			$array_kategorilistesi[$cat_id]["cat_name_en"] 	= $cat_name_en;		
			$array_kategorilistesi[$cat_id]["cat_image"] 	= $cat_image;
			$array_mycat = $array_kategorilistesi;
		}
	}
	
	######################################################################################################
	# ÜYELER İÇİN DİZİ FONKSİYONU
	######################################################################################################
	/*
	//üyeler için sql sorgusu oluşturuluyor
	$vt->sql('SELECT * FROM rss_user')->sor($cachetime);
	$sonuc = $vt->alHepsi();
	$bulunanadet = $vt->numRows();

	if ($bulunanadet)
	{
		for ( $i = 0; $i < $bulunanadet; $i++)
		{
			//sorgudan alınıyor
			$user_id 	= $sonuc[$i]->user_id;
			$user_name 	= $sonuc[$i]->user_name;

			//kimi varsayılan değerler üretiyoruz
			$user_options.= '<option value="'.$user_id.'">'.$user_name.'</option>'. "\r\n";

			//isimler dizide dursun
			$array_userlist[$user_id]["user_id"] 	= $user_id;		
			$array_userlist[$user_id]["user_name"] 	= $user_name;		
		}
	}
	*/
?>
