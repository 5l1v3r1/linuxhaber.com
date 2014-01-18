<?php
if (!defined('yakusha')) die('...');
?>
<div id="main">

<h1>Üye Bilgilerim</h1>

<p>Bu formu kullanarak, Üye Bilgilerinizin denetimini gerçekleştirebilirsiniz.</p>

<?php echo $islem_bilgisi ?>

<form name="form1" action="<?php echo $ucp_bilgi?>" method="post">
<table width="100%">

<tr>
	<td style="width: 150px">E-Posta </td>
	<td> : </td>
	<td>
		<input type="text" name="user_email" style="width: 300px" maxlength="70" value="<?php echo $user_email?>">
	</td>
</tr>

<tr>
	<td>Kullanıcı Adı </td>
	<td> : </td>
	<td>
		<input type="text" name="user_name" style="width: 300px" maxlength="70" value="<?php echo $user_name?>">
	</td>
</tr>

<tr>
	<td>Gerçek Adı</td>
	<td> : </td>
	<td>
		<input type="text" name="user_username" style="width: 300px" maxlength="70" value="<?php echo $user_username?>">
	</td>
</tr>

<tr>
	<td>Üye Status</td>
	<td> : </td>
	<td>
		<?php echo $array_user_status[$user_status]?>
	</td>
</tr>

<tr>
	<td>Tweet Status</td>
	<td> : </td>
	<td>
		<?php echo $array_tweet_status[$user_tweet_status]?>
	</td>
</tr>


<tr>
	<td colspan="2"></td>
	<td align="left">
		<div>
			<input type="hidden" name="formuisle" value="1">
			<input type="hidden" name="menu" value="bilgi">
			<input name="form1" class="button2" value="Üye Bilgilerimi Güncelle" type="submit">
		</div>
	</td>
</tr>

</table>
</form>
