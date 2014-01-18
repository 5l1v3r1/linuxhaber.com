<?php
if (!defined('yakusha')) die('...');

$endtime = microtime(true); 
$bitistime = substr(($endtime - $starttime),0,6); 

//$kullanim = memory_get_usage();
$kullanim = memory_get_peak_usage(true);
$kullanim = round($kullanim / 1024 / 1024, 2);
$sorgusayisi = $vt->sorguSayisi();
?>
<div style="clear: both;">&nbsp;</div>
</div>
</div>
</div>
<div id="footer-bgcontent">
<div id="footer">

<p class="right">
İletişim Eposta: <a href="mailto:<?php echo $YAKUSHA['site_eposta']?>"><?php echo $YAKUSHA['site_eposta']?></a>
<br>SÜS: <?php echo $bitistime?> Saniye. USG: <?php echo $kullanim?>  MB.  SQL: <?php echo $sorgusayisi?>
</p>
</div>
</div>
<!-- end #footer -->
</body>
</html>
