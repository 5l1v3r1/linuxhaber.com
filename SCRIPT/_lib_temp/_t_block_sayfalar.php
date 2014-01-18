<?php
if (!defined('yakusha')) die('...');

//sayfalar menüsünü de burdan oluşturalım
$vt->sql('SELECT page_name, page_title, page_image FROM rss_page WHERE page_status = 1 ORDER BY page_order ASC')->sor($cachetime);
$sonuc = $vt->alHepsi();
$adet = $vt->numRows();
if ($adet)
{
	$sayfabilgisi = '
	<li>
	<h2>Sayfalar</h2>
	<table width="80%">
	';

	if($_SESSION[SES]["giris"] == 1)
	{
		$sayfabilgisi.= '
		<tr>
			<td width="1"><img width="30" src="'.SITELINK.'/_img/_xcp/user_user.png"></td>
			<td valign="center">&nbsp;<a href="'.SITELINK.'?user='.$_SESSION[SES]["user_id"].'">Tweetlerim</a></td>
		</tr>
		';
	}

	$sayfabilgisi.= '
	<tr>
		<td width="1"><img width="30" src="'.SITELINK.'/_img/_cat/dosya.png"></td>
		<td valign="center">&nbsp;<a href="'.BULTENLERLINK.'">Bültenler</a></td>
	</tr>
	';
	for ( $i = 0; $i < $adet; $i++)
	{
		$page_name 	= $sonuc[$i]->page_name;
		$page_title = $sonuc[$i]->page_title;
		$page_image = $sonuc[$i]->page_image;
		$page_link 	= SAYFALARLINK.'?sayfa='.$page_name;		
		
		$sayfa = $_REQUEST["sayfa"];
		$sayfabilgisi.= '
		<tr>
			<td width="1"><img width="30" src="'.SITELINK.'/_img/_cat/'.$page_image.'"></td>
			<td valign="center">&nbsp;<a href="'.$page_link.'">'.$page_title.'</a></td>
		</tr>';
	}
	$sayfabilgisi.= '
	<tr>
		<td width="1"><img width="30" src="'.SITELINK.'/_img/_cat/turkiye.png"></td>
		<td valign="center">&nbsp;<a href="http://blog.linuxhaber.com">blog.linuxhaber.com</a></td>
	</tr>
	</table>
	</li>';
}
echo $sayfabilgisi;
?>
