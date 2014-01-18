<?php
if (!defined('yakusha')) die('...');

$vt->sql('SELECT bulten_id, bulten_name FROM rss_bulten WHERE bulten_status = 1 ORDER BY bulten_id DESC LIMIT 0,1')->sor($cachetime);
$son = $vt->alHepsi();
//sorgudan alınıyor
$id 			= $son[0]->bulten_id;
$bulten_name 	= $son[0]->bulten_name;
$link_bulten 	= BULTENLERLINK.'?bulten='.$id;
$sayfabilgisi = '
<div class="yellowbox"> Son Bültenimiz: 
	<br><a href="'.$link_bulten.'" title="'.$bulten_name.'" target="_blank">'.$bulten_name.'</a>
</div>';
echo $sayfabilgisi;
?>
