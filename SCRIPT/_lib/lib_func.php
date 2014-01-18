<?php
#-------------------------------------------------
# proje açık kaynak olacağı için mümkün olduğunca 
# açık kaynak kodlu fonksiyonlar kullanalım ve
# fonksiyonun kaynağını belirtelim
#-------------------------------------------------
# sabri ünal tarafından yazılmıştır
#-------------------------------------------------
function url_hazirla($url)
{
	#-------------------------------------------------
	# sabri ünal tarafından yazılmıştır
	#-------------------------------------------------

	$laststr = substr($url, -1);
	if($laststr == "/") $url = substr($url, 0, -1);
	$laststr = substr($url, -2);
	if($laststr == '__') $url = substr($url, 0, -2);
	$laststr = substr($url, -1);
	if($laststr == '_') $url = substr($url, 0, -1);
	return $url;
}

function yeni_satirla($metin)
{
	#-------------------------------------------------
	# sabri ünal tarafından uyarlanmıştır, aslını nerden almıştık hatırlamıyorum
	#-------------------------------------------------
	$metin = str_replace(array("\r\n","\r","<br><br>",), "\n<br>", $metin); // cross-platform newlines
	$metin = trim($metin);
	return $metin;
}

function tr_ucfirst($text)
{
	#-------------------------------------------------
	# şu adresten alınmıştır
	# http://www.php.net/manual/en/function.ucfirst.php#105435
	#-------------------------------------------------

	$text = str_replace("I","ı",$text);
	$text = mb_strtolower($text, 'UTF-8');

	if($text[0] == "i")
		$tr_text = "İ".substr($text, 1);
	else
		$tr_text = mb_convert_case($text, MB_CASE_TITLE, "UTF-8");

	return trim($tr_text);
}

function tr_ucwords($text)
{
	#-------------------------------------------------
	# şu adresten alınmıştır
	# http://www.php.net/manual/en/function.ucfirst.php#105435
	#-------------------------------------------------
    $p = explode(" ",$text);
    if(is_array($p))
    {
        $tr_text = "";
        foreach($p AS $item)
            $tr_text .= " ".tr_ucfirst($item);
           
        return trim($tr_text);
    }
    else
        return tr_ucfirst($text);
}

function label2str($label)
{
	#-------------------------------------------------
	# sabri ünal tarafından yazılmıştır
	#-------------------------------------------------
	$str = $label[6].''.$label[7].'-'.$label[4].''.$label[5].'-'.$label[0].''.$label[1].''.$label[2].''.$label[3];
	return $str;
}

function shortenUrl($longUrl)
{
	#-------------------------------------------------
	# şu adresten alınmıştır
	# http://www.phpriot.com/articles/google-url-shorening-api
	#-------------------------------------------------

	// initialize the cURL connection
	$ch = curl_init(
	sprintf('%s/url?key=%s', GOOGLE_ENDPOINT, GOOGLE_API_KEY)
	);

	// tell cURL to return the data rather than outputting it
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// create the data to be encoded into JSON
	$requestData = array(
	'longUrl' => $longUrl
	);

	// change the request type to POST
	curl_setopt($ch, CURLOPT_POST, true);

	// set the form content type for JSON data
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

	// set the post body to encoded JSON data
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));

	// perform the request
	$result = curl_exec($ch);
	curl_close($ch);

	// decode and return the JSON response
	$res = json_decode($result, true);
	//print_r($res);
	return $res['id'];
}

//google kısa url api'si
function longestUrl($url)
{
	#-------------------------------------------------
	# kaynağı belirtilmesi unutulmuş düzenlenmiş alıntı fonksiyon
	#-------------------------------------------------
	//api sağlayıcımız
	$link = 'https://www.googleapis.com/urlshortener/v1/url?shortUrl='.$url;
	//gönderelim
	$request  = file_get_contents($link);

	//geri alalım, dönüş json şeklinde malum
	$json = json_decode($request);
	//echo "<pre>";
	//print_r($json);
	//echo "</pre>";
	//echo $json->short_url;
	return $json->longUrl;
}

function temizle_cache()
{
	#-------------------------------------------------
	# sabri ünal tarafından yazılmıştır
	#-------------------------------------------------
	global $bellekyolu;
	$bellek = opendir($bellekyolu);
	if (!$bellek)
	{
		$mesaj =  '<div class="hata">Bellek Dizini Bulunamadı</div>';
	}

	while ($dosya = readdir($bellek))
	{
		unlink("_cache/".$dosya);
	}
	closedir($bellekyolu);
	$mesaj = '<div class="successbox">Bellek Dizini Temizlendi</div>';
	return $mesaj;
}

function f_secure_search($f_aranacak) 
{
	#-------------------------------------------------
	# mentis bilişim, bayram atmaca tarafından yazılmıştır
	#-------------------------------------------------

	# Aranacak ifade SQL sorgusu için güvenli hale getiriliyor.
	$aranacak = trim( strip_tags(substr( ereg_replace("%","",$f_aranacak),0,120) ) ); 
	$aranacak = trim( ereg_replace ("https://","",$aranacak) );
	$aranacak = trim( ereg_replace ("http://","",$aranacak) );
	$aranacak = trim( ereg_replace ("www.","",$aranacak) );
	$aranacak = trim( ereg_replace ("<","",$aranacak) );
	$aranacak = trim( ereg_replace (">","",$aranacak) );
	$aranacak = trim( ereg_replace ("\"","",$aranacak) );
	$aranacak = trim( ereg_replace ("'","",$aranacak) );
	$aranacak = trim( ereg_replace ("&","",$aranacak) );
	$aranacak = trim( ereg_replace ("#","",$aranacak) );
	$aranacak = trim( ereg_replace ("\*","",$aranacak) );
	$aranacak = trim( ereg_replace ("\?","",$aranacak) );
	$aranacak = trim( ereg_replace ("\+","",$aranacak) );
	$aranacak = trim( ereg_replace ("\(","",$aranacak) );
	$aranacak = trim( ereg_replace ("\)","",$aranacak) );
	$aranacak = trim( ereg_replace ("\[","",$aranacak) );
	$aranacak = trim( ereg_replace ("\]","",$aranacak) );
	$aranacak = trim( ereg_replace ("\{","",$aranacak) );
	$aranacak = trim( ereg_replace ("\}","",$aranacak) );
	$aranacak = trim( ereg_replace ("\|","",$aranacak) );

	$char = htmlentities($aranacak);
	$c = strlen($char);

	$char = str_replace("&eth;","&ETH;",$char);
	$char = str_replace("&uuml;","&Uuml;",$char);
	$char = str_replace("&thorn;","&THORN;",$char);
	$char = str_replace("&ccedil;","_",$char);
	$char = str_replace("&yacute;","_",$char);
	$char = str_replace("i","_",$char);
	$char = str_replace("İ","_",$char);
	$char = str_replace("ı","_",$char);
	$char = str_replace("&ouml;","&Ouml;",$char);
	//$char = str_replace("Ç","_",$char);
	//$char = str_replace("ç","_",$char);
	return html_entity_decode($char);
}

function format_url($text)
{
	#-------------------------------------------------
	# phpBB Turkiye ekibi Alexis tarafından 2007 yılında yazılmıştır
	#-------------------------------------------------

	$text = trim($text);
    $text = str_replace("I","ı",$text);
    $text = mb_strtolower($text, 'UTF-8');

	$find = array(' ', '&quot;', '&amp;', '&', '\r\n', '\n', '/', '\\', '+', '<', '>');
	$text = str_replace ($find, '-', $text);

	$find = array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ë', 'Ê');
	$text = str_replace ($find, 'e', $text);

	$find = array('í', 'ı', 'ì', 'î', 'ï', 'I', 'İ', 'Í', 'Ì', 'Î', 'Ï');
	$text = str_replace ($find, 'i', $text);

	$find = array('ó', 'ö', 'Ö', 'ò', 'ô', 'Ó', 'Ò', 'Ô');
	$text = str_replace ($find, 'o', $text);

	$find = array('á', 'ä', 'â', 'à', 'â', 'Ä', 'Â', 'Á', 'À', 'Â');
	$text = str_replace ($find, 'a', $text);

	$find = array('ú', 'ü', 'Ü', 'ù', 'û', 'Ú', 'Ù', 'Û');
	$text = str_replace ($find, 'u', $text);

	$find = array('ç', 'Ç');
	$text = str_replace ($find, 'c', $text);

	$find = array('ş', 'Ş');
	$text = str_replace ($find, 's', $text);

	$find = array('ğ', 'Ğ');
	$text = str_replace ($find, 'g', $text);

	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');

	$repl = array('', '-', '');

	$text = preg_replace ($find, $repl, $text);
	$text = str_replace ('--', '-', $text);

	$text = $text;

	return $text;
} 

?>
