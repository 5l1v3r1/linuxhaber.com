<?php
	if (!defined('yakusha')) die('...');
	if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

	$id 	= $_REQUEST["duzenle"];
	$delete = $_REQUEST["delete"];

	if (isset($_REQUEST["form"]))
	{
		//varsayılan değerler
		$changetar 		= time();
		//metin gelmesi gereken alanlar
		$tweet_url 		= addslashes(trim($_REQUEST["tweet_url"]));
		$tweet_text 	= addslashes(trim($_REQUEST["tweet_text"]));
		$tweet_en 		= addslashes(trim($_REQUEST["tweet_en"]));
		$tweet_desc 	= addslashes(trim($_REQUEST["tweet_desc"]));
		$tweet_short 	= addslashes(trim($_REQUEST["tweet_short"]));
		//sayı gelmesi gereken alanlar
		$tweet_cat 		= addslashes(trim($_REQUEST["tweet_cat"]));
		$tweet_tar 		= addslashes(trim($_REQUEST["tweet_tar"]));
		$tweet_status 	= addslashes(trim($_REQUEST["tweet_status"]));
		//checkbox gelen alan
		$check_short_url = $_REQUEST["check_short_url"];
		//link hazırlayalım
		$tweet_url 		= url_hazirla($tweet_url); 	

		if ($islem_bilgisi == '')
		{
			$vt->sql('
			UPDATE rss_tweet SET tweet_url = %s, tweet_text = %s, tweet_en = %s, tweet_desc = %s, tweet_short = %s, 
			tweet_cat = %u, tweet_tar = %u, tweet_status = %u, changetar = %u WHERE tweet_id = %u');
			$vt->arg($tweet_url, $tweet_text, $tweet_en, $tweet_desc, $tweet_short, $tweet_cat, $tweet_tar, $tweet_status, $changetar, $id)->sor();

			//linki yeniden kısaltmak istenirse
			if ($check_short_url)
			{
				$tweet_short = shortenUrl($tweet_url);
				if($tweet_short) $vt->sql('UPDATE rss_tweet SET tweet_short = %s WHERE tweet_id = %u')->arg($tweet_short, $id)->sor();
			}
			//işlem bilgisi bastıralım
			$islem_bilgisi = '<div class="successbox">Link bilgileri güncellenmiştir.</div>';
		}
	}
	
	$vt->sql('SELECT * FROM rss_tweet WHERE tweet_id = %u')->arg($id)->sor();
	$sonuc = $vt->alHepsi();

	$id 			= $sonuc[0]->tweet_id;
	$tweet_url 		= $sonuc[0]->tweet_url;
	$tweet_text 	= $sonuc[0]->tweet_text;
	$tweet_en 		= $sonuc[0]->tweet_en;
	$tweet_desc 	= $sonuc[0]->tweet_desc;
	$tweet_short 	= $sonuc[0]->tweet_short;
	$tweet_cat 		= $sonuc[0]->tweet_cat;
	$tweet_tar 		= $sonuc[0]->tweet_tar;
	$tweet_uid 		= $sonuc[0]->tweet_uid;
	$tweet_status	= $sonuc[0]->tweet_status;
	//tweet_short
	//temizlemeler
	$tweet_url 		= stripslashes($tweet_url);
	$tweet_text 	= stripslashes($tweet_text);
	$tweet_en 		= stripslashes($tweet_en);
	$tweet_desc 	= stripslashes($tweet_desc);
?>
<script>
function confirmDelete(delUrl)
{
	if (confirm("Linki silmek istediğinize emin misiniz?"))
	{
		document.location = delUrl;
	}
}
</script>

<form name="form1" action="<?php echo $acp_tweetlink?>&amp;duzenle=<?php echo $id?>" method="POST">
<input type="hidden" name="menu" value="tweet">
<input type="hidden" name="islem" value="duzenle">
<input type="hidden" name="duzenle" value="<?php echo $id?>">

<h1>Tweet Düzenle &raquo; <a href="<?php echo ANASAYFALINK?>?tweet=<?php echo $id?>"><?php echo $id?>. Tweet</a></h1>

<?php echo $islem_bilgisi ?>

<table valign="top" width="100%" cellspacing="3" border="0">
<tr class="col1">
<th height="25" colspan="2">
<div>
<a class="button1" href="javascript:confirmDelete('<?php echo $acp_tweetlink?>&amp;delete=<?php echo $id?>')">TWEET SİL</a>
</div>
</th>
<th>
<div>
<input class="button1" id="form" name="form" value="TWEET DÜZENLE" type="submit">
</div>
</th>
</tr>

<tr><td height="30">Başlık<br><br>Title </td>
<td> : </td>
<td>
	<div><input type="text" name="tweet_text" style="width: 750px" value="<?php echo $tweet_text ?>"> <font color="red">*</font></div>
	<br>
	<div><input type="text" name="tweet_en" style="width: 750px" value="<?php echo $tweet_en ?>"> <font color="red">*</font></div>
</td>
</tr>

<tr>
<td width="150" height="30">URL </td>
<td> : </td>
<td>
	<div><input type="text" name="tweet_url" style="width: 750px" value="<?php echo $tweet_url ?>"> <font color="red">*</font></div>
</td>
</tr>

<tr>
<td height="30">Açıklama</td>
<td> : </td>
<td>
	<div><input type="text" name="tweet_desc" style="width: 750px" value="<?php echo $tweet_desc ?>"></div>
</td>
</tr>

<tr>
<td height="30"></td>
<td> : </td>
<td>
	<div>
		<input type="text" name="tweet_short" style="width: 140px" value="<?php echo $tweet_short?>"> 
		<select style="width:125px" name="tweet_cat">
			<option value="<?php echo $tweet_cat?>"> &raquo; <?php echo $array_kategorilistesi[$tweet_cat]["cat_name"]?></option>
			<option> - seçiniz -</option>
			<?php echo $kategoriler_options ?>
		</select>
		<select style="width:125px" name="tweet_status">
			<option value="<?php echo $tweet_status?>"> &raquo; <?php echo $array_tweet_status[$tweet_status]?></option>
			<?php
			foreach ($array_tweet_status as $k => $v)
			{
				echo '<option value="'.$k.'">'.$v.'</option>'. "\r\n";
			}
			?>
		</select>
		<input type="text" name="tweet_tar" style="width: 100px" value="<?php echo $tweet_tar?>"> 
		<font color="red">*</font>
		@<?php echo $array_userlist[$tweet_uid]["user_name"]?>
	</div>
	Linki Yeniden Kısalt <input type="checkbox" name="check_short_url" id="check_short_url">
</td>
</tr>
</table>

</form>
<pre>
* Kırmızı işaretli alanların doldurulması zorunludur.
</pre>
