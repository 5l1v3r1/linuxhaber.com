<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

$duzenle 	= $_REQUEST["duzenle"];
$delete 	= $_REQUEST["delete"];
$user_id 	= $duzenle;

//üye seviyesi yönetici seviyesinde değil ise ikaz ediyoruz
//
if ($_SESSION[SES]["user_status"] < 10)
{
	$islem_bilgisi = '<div class="errorbox">Sadece Yetkililer üye bilgilerini güncelleyebilir.</div>';
}
else
{
	if (isset($_REQUEST["form1"]))
	{
		//metin gelmesi gereken alanlar
		$user_id 			= addslashes(trim(strip_tags($_REQUEST["duzenle"])));
		$user_email 		= addslashes(trim(strip_tags($_REQUEST["user_email"])));
		$user_name 			= addslashes(trim(strip_tags($_REQUEST["user_name"])));
		$user_username 		= addslashes(trim(strip_tags($_REQUEST["user_username"])));
		$user_status 		= addslashes(trim(strip_tags($_REQUEST["user_status"])));
		$user_tweet_status 	= addslashes(trim(strip_tags($_REQUEST["user_tweet_status"])));

		//HATA KONTROLÜ
		if ( strlen($user_email) < 2 or !eregi("[[:alpha:]]",$user_email) )
		$islem_bilgisi = '<div class="errorbox">Üye Eposta alanını boş bırıkmayınız.</div>';

		//üye adı ve eposta adresi başkasına ait olmasın
		$vt->sql('SELECT count(user_id) FROM rss_user WHERE (user_email = %s OR user_name = %s) AND user_id <> %u');
		$vt->arg($user_email, $user_name, $user_id)->sor();
		$varolan = $vt->alTek();
		if($varolan) $islem_bilgisi = '<div class="errorbox">Bu eposta adresi veya kullanıcı adı başka bir kullanıcı adına kayıtlıdır</div>';

		if ($islem_bilgisi == '')
		{
			$vt->sql('UPDATE rss_user SET user_email = %s, user_name = %s, user_username = %s, 
					user_status = %s, user_tweet_status = %s WHERE user_id = %u');
			$vt->arg($user_email, $user_name, $user_username, $user_status, $user_tweet_status, $user_id)->sor();
			$islem_bilgisi = '<div class="successbox">Üye bilgileri güncellenmiştir.</div>';
		}
	}
}

$vt->sql('SELECT * FROM rss_user WHERE user_id = %u')->arg($duzenle)->sor();
$sonuc = $vt->alHepsi();

$user_id 			= $sonuc[0]->user_id;
$user_name 			= $sonuc[0]->user_name;
$user_username 		= $sonuc[0]->user_username;
$user_email 		= $sonuc[0]->user_email;
$user_status 		= $sonuc[0]->user_status;
$user_tweet_status	= $sonuc[0]->user_tweet_status;

?>

<script>
function confirmDelete(delUrl)
{
	if (confirm("Üyeyi sistemden silmek istediğinize emin misiniz?"))
	{
		document.location = delUrl;
	}
}
</script>

<form name="form1" action="<?php echo $acp_uyelerlink?>&amp;duzenle=<?php echo $user_id?>" method="POST">
<input type="hidden" name="menu" value="uyeler">
<input type="hidden" name="islem" value="guncelle">
<input type="hidden" name="duzenle" value="<?php echo $user_id?>">

<h1>Üye Düzenle &raquo; <?php echo $user_username?></h1>

<?php echo $islem_bilgisi ?>

<table valign="top" width="100%" cellspacing="3" border="0">
<tr>
<th height="25" colspan="2">
<div>
<a class="button1" href="javascript:confirmDelete('<?php echo $acp_uyelerlink?>&amp;delete=<?php echo $user_id?>')">ÜYE SİL</a>
</div>
</th>
<th>
<div>
<input class="button1" id="form1" name="form1" value="ÜYE BİLGİLERİNİ DÜZENLE" type="submit">
</div>
</th>
</tr>


<tr>
<td height="30" width="200">Üyelik Seviyesi</td><td> : </td><td>
	<div>
		<select style="width: 150px;" name="user_status">
			<option value="<?php echo $user_status?>"><?php echo $array_user_status[$user_status]?></option>
			<?php
				foreach ($array_user_status as $k => $v) echo '<option value="'.$k.'">'.$v.'</option>'. "\r\n";
			?>
		</select>
		<select style="width: 150px;" name="user_tweet_status">
			<option value="<?php echo $user_tweet_status?>"><?php echo $array_tweet_status[$user_tweet_status]?></option>
			<?php
				foreach ($array_tweet_status as $k => $v) echo '<option value="'.$k.'">'.$v.'</option>'. "\r\n";
			?>
		</select>
	</div>
</td>
</tr>

<tr>
<td height="30">Üye Eposta </td>
<td> : </td>
<td>
<div>
<input type="text" name="user_email" style="width:300px" maxlength="70" value="<?php echo $user_email?>"> 
</div>
</td>
</tr>

<tr>
<td height="30">Kullanıcı Adı </td>
<td> : </td>
<td>
<div>
<input type="text" name="user_name" style="width: 300px" maxlength="70" value="<?php echo $user_name?>">
</div>
</td>
</tr>

<tr>
<td height="30">Gerçek İsim</td>
<td> : </td>
<td>
<div>
<input type="text" name="user_username" style="width: 300px" maxlength="70" value="<?php echo $user_username?>">
</div>
</td>
</tr>

</table>
</form>
