<?php
if (!defined('yakusha')) die('...');
?>

<div id="menu">
<p><strong><?php echo $_SESSION[SES]["user_email"];?></strong> 
<br>[ <a href="<?php echo EXITLINK?>">Oturum Kapat</a> ]
<?php 
if ($_SESSION[SES]["ADMIN"]==1)
{
	echo '<br>[ <a href="'.ACPLINK.'">Yetkili Paneli</a> ]';
}
?>

<ul>
<li class="header">Hızlı Menü</li>
<li <?php if($id == 'bilgi') echo 'id="activemenu"'; ?>><a href="<?php echo $ucp_bilgi?>"><span>Üye Bilgilerim</span></a></li>
<li <?php if($id == 'parola') echo 'id="activemenu"'; ?>><a href="<?php echo $ucp_parola?>"><span>Parola Bilgilerim</span></a></li>
</ul>
</div>
