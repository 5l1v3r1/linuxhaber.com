<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

if ($_SESSION[SES]["user_status"] < 10)
{
	$islem_bilgisi = '<div class="errorbox">Sadece Yetkililer yeni üye ekleyebilir.</div>';
}
else
{
	if (isset($_REQUEST["form1"]))
	{
		//metin gelmesi gereken alanlar
		$user_email 		= addslashes(trim(strip_tags($_REQUEST["user_email"])));
		$user_name 			= addslashes(trim(strip_tags($_REQUEST["user_name"])));
		$user_username 		= addslashes(trim(strip_tags($_REQUEST["user_username"])));
		$user_status 		= addslashes(trim(strip_tags($_REQUEST["user_status"])));
		$user_tweet_status 	= addslashes(trim(strip_tags($_REQUEST["user_tweet_status"])));

		$user_password 		= addslashes(trim(strip_tags($_REQUEST["user_password"])));

		//HATA KONTROLÜ
		if ( strlen($user_email) < 2 or !eregi("[[:alpha:]]",$user_email) )
		$islem_bilgisi = '<div class="errorbox">Üye Eposta alanını boş bırıkmayınız.</div>';

		//üye adı ve eposta adresi başkasına ait olmasın
		$vt->sql('SELECT count(user_id) FROM rss_user WHERE (user_email = %s OR user_name = %s)')->arg($user_email, $user_name)->sor();
		$varolan = $vt->alTek();
		if($varolan) $islem_bilgisi = '<div class="errorbox">Bu eposta adresi veya kullanıcı adı başka bir kullanıcı adına kayıtlıdır</div>';

		if ($islem_bilgisi == '')
		{
			$user_password = md5($user_password);
	
			$vt->sql('INSERT INTO rss_user ( user_email, user_name, user_username, user_status, user_tweet_status, user_password)
						VALUES ( %s, %s, %s, %u, %u, %s)');
			$vt->arg($user_email, $user_name, $user_username, $user_status, $user_tweet_status, $user_password)->sor();
			if($vt->affRows())
			{
				$islem_bilgisi = '<div class="successbox">'.$user_username.' isimli üye sisteme eklenmiştir.</div>';
				$user_email = $user_name = $user_username = $user_status = $user_tweet_status = $user_password = '';
			}
		}
	}
}
?>

<form name="urunform" action="<?php echo $acp_uyelerlink?>&ekle=1" method="POST">
<input type="hidden" name="menu" value="uyeler">
<input type="hidden" name="islem" value="ekle">

<h1>Yeni Üye Ekle</h1>

<?php echo $islem_bilgisi ?>

<table valign="top" width="100%" cellspacing="3" border="0">
<tr>
<th height="25" colspan="2">
</th>
<th>
<div>
<input class="button1" id="form1" name="form1" value="ÜYE EKLE" type="submit">
</div>
</th>
</tr>


<tr>
<td height="30" width="200">Üyelik Seviyesi</td><td> : </td><td>
	<div>
		<select style="width: 150px;" name="user_status">
			<?php
				foreach ($array_user_status as $k => $v) echo '<option value="'.$k.'">'.$v.'</option>'. "\r\n";
			?>
		</select>
		<select style="width: 150px;" name="user_tweet_status">
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

<tr>
<td height="30">Üye Parola</td>
<td> : </td>
<td>
<div>
<input type="password" name="user_password" style="width: 300px" maxlength="70" value="<?php echo $user_password?>">
</div>
</td>
</tr>

</table>
</form>
