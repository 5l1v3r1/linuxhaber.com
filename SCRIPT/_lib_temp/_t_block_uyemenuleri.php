<?php if (!defined('yakusha')) die('...');
if ($_SESSION[SES]["giris"]==1) { 
?>
<h2>Yönetim Araçları</h2>
<table width="80%">
<tr>
<td colspan="2">
<font color="#cc0000" face="trebuchet ms, Arial, Helvetica" size="2"><?php echo $_SESSION[SES]["user_email"];?></font>
</td>
</tr>
<?php if ($_SESSION[SES]["ADMIN"]==1) { ?>
<tr>
<td><img width="30" src="<?php echo SITELINK?>/_img/_xcp/user_root.png"></td>
<td valign="center"><a href="<?php echo ACPLINK?>">Yönetici Paneli</a></td>
</tr>
<?php } ?>
<tr>
<td><img width="30" src="<?php echo SITELINK?>/_img/_xcp/user_user.png"></td>
<td valign="center"><a href="<?php echo UCPLINK?>">Üye Paneli</a></td>
</tr>
<tr>
<td><img width="30" src="<?php echo SITELINK?>/_img/_xcp/user_logout.png"></td>
<td valign="center"><a href="<?php echo EXITLINK?>">Oturumu Kapat</a></td>
</tr>
</table>
<?php } ?>

