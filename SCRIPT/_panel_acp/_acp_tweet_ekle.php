<?php
	if (!defined('yakusha')) die('...');
	if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

	if (isset($_REQUEST["form"]))
	{
		//sayı gelmesi gereken alanlar
		$tweet_cat 		= addslashes(trim($_REQUEST["tweet_cat"]));
		$tweet_tar 		= addslashes(trim($_REQUEST["tweet_tar"]));
		//requestten parse edilen alan
		if(isset($_REQUEST["tweet"]))
		{
			$tweet 			= $_REQUEST["tweet"];
			$str 			= str_replace("\n","|", $tweet);
			$str 			= explode('|', $str);
			//echo '1'.$str[0].'<br>2'.$str[1].'<br>3'.$str[2];
			$tweet_text 	= $str[0];
			$tweet_url 		= $str[1];
			$tweet_en 		= $str[2];
			$tweet_desc 	= $str[3];
		}		
		$tweet_url 			= addslashes(trim($tweet_url));
		$tweet_text 		= addslashes(trim($tweet_text));
		$tweet_en 			= addslashes(trim($tweet_en));
		$tweet_desc 		= addslashes(trim($tweet_desc));

		$tweet_url 			= url_hazirla($tweet_url); 	//link hazırlayalım
		$tweet_short 		= shortenUrl($tweet_url); 	//linki kısaltalım
		$tweet_tar 			= date("Ymd",time()); 		//link tarihini
		$changetar 			= time(); 					//varsayılan değerler

		//HATA KONTROLÜ
		//kısa link dönmezse boş dönelim, hata oluşmasın
		if (!$tweet_short) $tweet_short = '';

		if ( strlen($tweet) < 2 or !eregi("[[:alpha:]]",$tweet) )
		$islem_bilgisi = '<div class="errorbox">Tweet Alanını Boş Bırakmayınız.</div>';

		if ($islem_bilgisi == '')
		{
			$vt->sql('INSERT INTO rss_tweet (tweet_url, tweet_text, tweet_en, tweet_desc, tweet_short, 
						tweet_cat, tweet_tar, tweet_status, tweet_uid, createtar, changetar ) 
						VALUES ( %s,  %s, %s, %s, %s, %u, %u, %u, %u, %u, %u)');
			$vt->arg($tweet_url, $tweet_text, $tweet_en, $tweet_desc, $tweet_short, $tweet_cat, $tweet_tar, 
					$tweet_status, $tweet_uid, $changetar, $changetar)->sor();
			//echo $vt->alSql();
			$islem_bilgisi = '<div class="successbox">tweet sisteme eklenmiştir.</div>';			
		}
	}
?>

<form name="form1" action="<?php echo $acp_tweetlink?>&amp;ekle=1" method="POST">
<input type="hidden" name="menu" value="tweet">
<input type="hidden" name="islem" value="ekle">
<input type="hidden" name="ekle" value="1">

<h1>Yeni Tweet Ekle</h1>

<?php echo $islem_bilgisi ?>


<table valign="top" width="100%" cellspacing="3" border="0">
<tr>
<td height="30"><strong>Metin<br>Link<br>Title<br>Açıklama</strong><br><br>Sırasıyla Ekleyiniz.</td>
<td> : </td>
<td>
<div>
	<textarea name="tweet" rows="5" style="width: 650px"></textarea>
</div>
</td>
</tr>

<tr>
<td height="30">Kategori</td>
<td> : </td>
<td>
	<div>
		<select style="width:125px" name="tweet_cat">
			<option value="0"> -seçiniz- </option>
			<?php echo $kategoriler_options ?>
		</select>
		<input class="button1" id="form" name="form" value="TWEET EKLE" type="submit">
	</div>
</td>
</tr>

</table>
</form>
