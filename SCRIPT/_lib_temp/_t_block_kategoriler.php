<?php if (!defined('yakusha')) die('...'); ?>

<h2>Kategoriler</h2>
<table width="80%">
<?php
foreach ($array_mycat as $k => $v)
{
	$cat_name = $array_kategorilistesi[$k]["cat_name"];
	if($lang == L_EN)
	{ 
		$cat_name = $array_kategorilistesi[$k]["cat_name_en"];
	}
echo '
<tr>
<td width="2"><a href="'.SITELINK.'?cat='.$k.'"><img width="28" src="'.SITELINK.'/_img/_cat/'.$array_kategorilistesi[$k]["cat_image"].'"></a></td>
<td>&nbsp;<a href="'.SITELINK.'?cat='.$k.'">'.$cat_name.'</a></td>
</tr>';
}
?>
</table>
