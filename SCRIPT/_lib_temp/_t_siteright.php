<?php
if (!defined('yakusha')) die('...');
?>

<div id="sidebar">
  <ul>
	<li><?php include($siteyolu."/_lib_temp/_t_block_uyemenuleri.php"); ?></li>
	<li><?php include($siteyolu."/_lib_temp/_t_block_duyuru.php"); ?></li>
	<li><?php include($siteyolu."/_lib_temp/_t_block_bultenler.php"); ?></li>
	<li><?php include($siteyolu."/_lib_temp/_t_block_arama.php"); ?></li>
	<li><?php include($siteyolu."/_lib_temp/_t_block_sayfalar.php"); ?></li>
	<li><?php if(!$tweet) include($siteyolu."/_lib_temp/_t_block_kategoriler.php"); ?></li>
  </ul>
</div>

