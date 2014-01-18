<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

if (isset($_REQUEST["form1"]))
{
	$page_order 	= $_REQUEST["page_order"]; 		settype($page_order,"integer");
	$page_status 	= $_REQUEST["page_status"]; 	settype($page_status,"integer");

	//metin gelmesi gereken alanlar
	$page_name 		= addslashes(trim(strip_tags($_REQUEST["page_name"])));
	$page_title 	= addslashes(trim(strip_tags($_REQUEST["page_title"])));
	$page_image 	= addslashes(trim(strip_tags($_REQUEST["page_image"])));
	//etiket bulundurur
	$page_content 	= addslashes(trim($_REQUEST["page_content"]));
	
	//özel işlem
	$page_name 		= format_url($page_name);

	//HATA KONTROLÜ
	if ( strlen($page_name) < 2 or !eregi("[[:alpha:]]",$page_name) )
	$islem_bilgisi = '<div class="errorbox">Sayfa Adı alanını boş bırakmayınız.</div>';

 	if ( strlen($page_name) > 2 )
	{
		$vt->sql('SELECT count(page_name) FROM rss_page WHERE page_name = %s')->arg($page_name)->sor();
		$sayi = $vt->alTek();
		if ( $sayi > 0 )
		{
			$islem_bilgisi = '<div class="errorbox">'.$page_name.' isimli sayfa kayıtlı, lütfen kontrol ediniz.</div>';
		}
	}	

	if ($islem_bilgisi == '')
	{
		$vt->sql('INSERT INTO rss_page ( page_name, page_title, page_image, page_content, page_order, page_status ) VALUES ( %s, %s, %s, %s, %u,%u)');
		$vt->arg($page_name, $page_title, $page_image, $page_content, $page_order, $page_status)->sor();
		$islem_bilgisi = '<div class="successbox">'.$page_title.' isimli sayfa sisteme eklenmiştir.</div>';
		$page_name = $page_title = $page_image = $page_content = $page_order = $page_status = '';
		temizle_cache();
	}
}

?>

<form name="form1" action="<?php echo $acp_sayfalarlink?>&amp;ekle=1" method="POST">
<input type="hidden" name="menu" value="sayfalar">
<input type="hidden" name="ekle" value="1">

<h1>Yeni Sayfa Ekle</h1>

<?php echo $islem_bilgisi ?>

<table valign="top" width="100%" cellspacing="3" border="0">
	<tr class="col1">
		<th colspan="2">
			
		</th>
		<th>
			<div><input class="button1" id="form1" name="form1" value="SAYFA EKLE" type="submit"></div>
		</th>
	</tr>

	<tr>
		<td height="30" width="200">Durum / Sıralama / Resim</td><td> : </td><td>
			<div>
				<select style="width: 155px;" name="page_status">
				<?php
					foreach ($array_page_status as $k => $v)
					{
						echo '<option value="'.$k.'">'.$v.'</option>'. "\r\n";
					}
				?>
				</select>
				<input type="text" name="page_order" style="width: 50px" maxlength="3" value="<?php echo $page_order?>"> 
				<input type="text" name="page_image" style="width: 300px" maxlength="70" value="<?php echo $page_image?>">
			</div>
		</td>
	</tr>
	
	<tr>
		<td height="30">Sayfa Adı / Başlığı</td>
		<td> : </td>
		<td>
			<div>
				<input type="text" name="page_name" style="width: 210px" maxlength="70" value="<?php echo $page_name?>"> 
				<input type="text" name="page_title" style="width: 300px" maxlength="70" value="<?php echo $page_title?>"> 
			</div>
		</td>
	</tr>
	<tr>
		<td height="30">Sayfa İçerik </td>
		<td> : </td>
		<td>
			<div>
				<textarea name="page_content" rows="10" style="width: 520px"><?php echo $page_content?></textarea>
			</div>
		</td>
	</tr>		
</table>
</form>
<pre>
* Bütün alanların doldurulması zorunludur.
</pre>
