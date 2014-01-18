<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

$duzenle = $_REQUEST["duzenle"];

if (isset($_REQUEST["form1"]))
{
	$id 			= $_REQUEST["duzenle"]; 		settype($duzenle,"integer");
	$page_order 	= $_REQUEST["page_order"]; 		settype($page_order,"integer");
	$page_status 	= $_REQUEST["page_status"]; 	settype($page_status,"integer");

	//metin gelmesi gereken alanlar
	$page_name 		= addslashes(trim(strip_tags($_REQUEST["page_name"])));
	$page_title 	= addslashes(trim(strip_tags($_REQUEST["page_title"])));
	$page_image 	= addslashes(trim(strip_tags($_REQUEST["page_image"])));
	//etiket bulundurur
	$page_content 	= trim($_REQUEST["page_content"]);
	
	//özel işlem
	$page_name 		= format_url($page_name);

	//HATA KONTROLÜ
	if ( strlen($page_name) < 2 or !eregi("[[:alpha:]]",$page_name) )
	$islem_bilgisi = '<div class="errorbox">Sayfa Adı alanını boş bırakmayınız.</div>';

	if ($islem_bilgisi == '')
	{
		$vt->sql('UPDATE rss_page SET page_name = %s, page_title = %s, page_image = %s, page_content = %s, page_order = %s, page_status = %s WHERE id = %u');
		$vt->arg($page_name, $page_title, $page_image, $page_content, $page_order, $page_status, $id)->sor();
		$islem_bilgisi = '<div class="successbox">Sayfa bilgisi güncellenmiştir.</div>';
		temizle_cache();
	}
}


$vt->sql('SELECT * FROM rss_page WHERE id = %u')->arg($duzenle)->sor();
$sonuc = $vt->alHepsi();

$id 			= $sonuc[0]->id;
$page_name 		= $sonuc[0]->page_name;
$page_title 	= $sonuc[0]->page_title;
$page_image 	= $sonuc[0]->page_image;
$page_content 	= $sonuc[0]->page_content;
$page_order 	= $sonuc[0]->page_order;
$page_status 	= $sonuc[0]->page_status;

$page_name 		= stripslashes($page_name);
$page_title 	= stripslashes($page_title);
$page_image 	= stripslashes($page_image);
$page_content 	= stripslashes($page_content);

$sayfalink = SITELINK.'/sayfalar.php?sayfa='.$page_name;

?>

<form name="uyeform" action="<?php echo $acp_sayfalarlink?>&amp;duzenle=<?php echo $id?>" method="POST">
<input type="hidden" name="menu" value="sayfalar">
<input type="hidden" name="duzenle" value="<?php echo $id?>">

<h1>Sayfa Düzenle &raquo; <a href="<?php echo $sayfalink?>"><?php echo $page_title?></a></h1>

<?php echo $islem_bilgisi ?>

<table valign="top" width="100%" cellspacing="3" border="0">
	<tr class="col1">
		<th colspan="2">
			
		</th>
		<th>
			<div><input class="button1" id="form1" name="form1" value="SAYFA GÜNCELLE" type="submit"></div>
		</th>
	</tr>

	<tr>
		<td height="30" width="200">Durum / Sıralama / Resim</td><td> : </td><td>
			<div>
				<select style="width: 155px;" name="page_status">
				<option value="<?php echo $page_status?>"><?php echo $array_page_status[$page_status]?></option>
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
