<?php
if (!defined('yakusha')) die('...');
?>
<div id="main">

<h1>Parola Bilgilerim</h1>

<p>Bu formu kullanarak, Parola Bilgilerinizin denetimini gerçekleştirebilirsiniz.</p>

<?php echo $islem_bilgisi ?>

<form name="sifredegistirmeformu" action="<?php echo $ucp_parola?>" method="post">
<input type="hidden" name="menu" value="parola">
<table bgcolor="#F9F9F9" width="100%">
<tr>
<td>Yeni Parola </td>
<td> : </td>
<td>
<input type="password" name="parola1" style="width: 300px" maxlength="32"></b><font color="#FF0000">*</font>
</td>
</tr>
<tr>
<td>Yeni Parola (Tekrar) </td>
<td> : </td>
<td>
<input type="password" name="parola2" style="width: 300px" maxlength="32"><font color="#FF0000">*</font>
</td>
</tr>
<tr>
<td>Eski Parola </td>
<td> : </td>
<td>
<input type="password" name="parolam" style="width: 300px" maxlength="32" value=""><font color="#FF0000">*</font>
</td>
</tr>
<tr>
<td colspan="2"></td>
<td>
<div><input name="sifredegistirmeformu" class="button2" value="Yeni Parola Belirle" type="submit"></div>
</td>
</tr>
</table>
</form>
