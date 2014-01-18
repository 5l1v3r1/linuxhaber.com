<?php if (!defined('yakusha')) die('...'); ?>

<?php if(!$tweetid) { ?>
<div id="load">
<div class="tweetit<?php echo $class?>">
<textarea style="width:625px; height:90px" id="tweet" name="tweet"><?php echo stripslashes($get_tweet)?></textarea>
<br>
<div class="layertweetnotes">Önce <strong>Metin</strong> sonra <strong>Link</strong> en son <strong>Yorum</strong> ekleyin. 
&nbsp;&nbsp;&nbsp;<a target="_blank" href="<?php echo SITELINK?>/_img/manuel.png">(?)</a>
</div>
<div class="layertweetbutton">
<select style="width:250px" id="cat" name="cat">
<option value="0">- seçiniz -</option>
<?php echo $kategoriler_options?>
</select>
<input type="button" value="Tweetle" onclick="tweet_it()">
</div>
</div>
</div>
<? } ?>
