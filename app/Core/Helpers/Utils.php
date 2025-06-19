<?php
class Util{
	
	
	
	/*************************************
	GET CODE FOR ACTIONS FROM JSON FILE
	**************************************/	
	public static function getActionByCode($lang = null, $code = null){
		$returned = array();
		
		if($lang !== null && $code !== null){
			$string = file_get_contents(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."actions".DIRECTORY_SEPARATOR.$lang.".json");
		}

		
		$json = json_decode($string, true);
		$returned = (isset($json[$code])?  $json[$code]: array());
		return $returned;
	}
	
	public function format($number = 0, $show_currency=true){
		if($show_currency){
			return number_format($number,2,",",".")." Dh";
		}else{
			return number_format($number,2,",",".");
		}
	}
	
	public static function normalizeChars($s) {
		$replace = array(
			'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
			'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'Ae',
			'Þ'=>'B',
			'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
			'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
			'Ğ'=>'G',
			'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
			'Ł'=>'L',
			'Ñ'=>'N', 'Ń'=>'N',
			'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
			'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
			'Ț'=>'T',
			'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'Ue',
			'Ý'=>'Y',
			'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
			'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'ae', 'æ'=>'ae', 'Ǽ'=>'ae', 'ǽ'=>'ae',
			'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
			'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'ch', 'ч'=>'ch',
			'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'D', 'ð'=>'d',
			'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
			'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
			'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
			'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
			'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'ij', 'Ĳ'=>'ij',
			'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'ja', 'Я'=>'ja', 'Э'=>'je', 'э'=>'je', 'ё'=>'jo', 'Ё'=>'jo', 'ю'=>'ju', 'Ю'=>'ju',
			'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
			'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
			'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
			'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
			'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'oe', 'Œ'=>'oe', 'ö'=>'oe',
			'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
			'ק'=>'q',
			'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
			'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'sch', 'щ'=>'sch', 'ш'=>'sh', 'Ш'=>'sh', 'ß'=>'ss',
			'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'tm',
			'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'ue',
			'в'=>'v', 'ו'=>'v', 'В'=>'v',
			'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
			'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
			'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'zh', 'ж'=>'zh'
		);
    return strtr($s, $replace);
	}
	
	public static function shortSentence($sentence, $nbr){
		$words = explode(" ",$sentence);
		$returned = "";
		for($i=0;$i<$nbr;$i++){
			$returned .= $words[$i]." ";
		}
		return $returned." ...";
	}
	
	
	/*************************************
	Get CLEAN STRING WITHOUT STANGE CHARACTERS
	**************************************/
	public static function getCleanURL($txt){
		return rtrim(preg_replace("/[^a-zA-Z]+/", "-", Util::normalizeChars($txt)),"-");	
	}
	
	
	/*************************************
	FORMAT BITES
	**************************************/	
	public static function formatBytes($size, $precision = 2) { 
		$base = log($size, 1024);
    	$suffixes = array('', 'K', 'M', 'G', 'T');   

    	return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	} 

	// GET PAGINATION BY GIVEN NUMBER OF ITEMS
	public static function getPagination($totalRecord, $linePearPage, $recordPearLine, $current ){
		
		$pagination='<div class="paginator"><ul>';
		$totalLine = ceil($totalRecord/$recordPearLine);
		$nbrButtons = ceil($totalLine/$linePearPage);
		$i=1;
		$prec = $current==1?1:$current-1;
		$suiv = $current==$nbrButtons?$nbrButtons:$current+1;
		$url = "#";
		if($prec == $current)		
		$pagination .= '<li value="'.$prec.'" class="button"><a class="disabled" href="'.$url.'current='.$suiv.'">Préc.</a> </li>';	
		else
		$pagination .= '<li value="'.$prec.'" class="button"><a href="'.$url.'current='.$prec.'">Préc.</a> </li>';	

		for($i=1;$i<=$nbrButtons; $i++){
			if($current == $i){
				$pagination .= '<li><a class="current tt">'.$i.'</a> </li>';
			}else{
				$pagination .= '<li  value="'.$i.'" ><a href="'.$url.'current='.$i.'">'.$i.'</a> </li>';	
			}
		}
		
		if($suiv == $current || $current==0)
				$pagination .= '<li  value="'.$suiv.'" ><a class="button disabled" href="'.$url.'current='.$suiv.'">Suiv.</a> </li>';
		else
			$pagination .= '<li  value="'.$suiv.'" ><a class="button" href="'.$url.'current='.$suiv.'">Suiv.</a> </li>';
		
		$pagination .='</ul></div>';
		if($nbrButtons>1)return $pagination;
		
	}
	
	/*************************************
	Get the IP_ADDRESS
	**************************************/
	public static function getIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
	}
	
	
	/*************************************
	SOCIAL MEDIAS
	**************************************/
	/**
	$values = array("key" => value);
	$socialName = "facebook" | "twitter"
	
	**/
	public static function generateOG($socialName = null, $values = null){
		$returned = "";
		
		if ($socialName == "facebook"){
			
			$og = array("og:url","og:type","og:title","og:description","og:image","og:site_name","fb:pages","og:image:alt","fb:app_id");
			
			
			foreach($values as $k => $v){
				if(in_array($k,$og)){
					$returned .= '<meta property="' . $k . '" content="' . $v . '" />';
				}
				
			}
			$returned .= '<meta name="twitter:card" content="summary">';
			$returned .= '<meta name="twitter:description" content="' . $values["og:description"] . '">';
			$returned .= '<meta name="twitter:title" content="' . $values["og:title"] . '">';
			$returned .= '<meta name="twitter:site" content="@gestore.ma">';
			$returned .= '<meta name="twitter:image" content="' . $values["og:image"] . '">';
		}
		return $returned;
	}

	
	/*************************************
	READ LANGUAGE
	**************************************/
	/**
	$values = array("key" => value);
	$socialName = "facebook" | "twitter"
	
	**/
	public static function getLanguageContent($lang = null, $label = null){
		$returned = "";
		
		if($lang == null){
			$string = file_get_contents(HTTP.HOST."templates/".APP_TEMPLATE."/lang/fr.json");
		}else{
			$string = file_get_contents(HTTP.HOST."templates/".APP_TEMPLATE."/lang/".$lang.".json");
		}
		
		$json = json_decode($string, true);
		$returned = (isset($json[$label])?  $json[$label]: array());
		return $returned;
	}


	
}
