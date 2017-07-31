<?php 
// header("HTTP/1.0 404 Not Found");
require_once "includes/maincore.php";

if (isset($_GET['row_url'])) {
    $fusion_uri = "/". stripinput($_GET['row_url']);
} else {
    $fusion_uri = stripinput(FUSION_URI);
    $fusion_uri = explode("?", $fusion_uri);
    $fusion_uri = $fusion_uri[0];
}
if ($fusion_uri=="/") { $fusion_uri = $settings['opening_page']; }
$fusion_uri_arr = explode("/", $fusion_uri);
$component = "custom_pages";
$exp = ".php";

//debug( $fusion_uri );
//debug( $fusion_uri_arr );


if (file_exists(COMPONENTS . $fusion_uri)) {
    $fusion_uri_exist = explode(".", $fusion_uri_arr[1]);
    $component = $fusion_uri_exist[0];
    $alias = "";
    $exp = ".". $fusion_uri_exist[1];
} else if ($fusion_uri=="/login") {
    $component = "login";
    $alias = "";
} else if ($fusion_uri=="/parser") {
    $component = "parser";
    $alias = "";
} else if ( isset($fusion_uri_arr[1]) && $fusion_uri_arr[1]=="videos" ) {
    $component = "video_index";
    if(isset($fusion_uri_arr[4])) {
        $component = "404";
        $alias = "";
    } else if(isset($fusion_uri_arr[3])) {
        $component = "videos";
        $alias = $fusion_uri_arr[3];
    } else if(isset($fusion_uri_arr[2])) {
        $component = "video_cats";
        $alias = $fusion_uri_arr[2];
    }
}



if ( ($fusion_uri!="/robots.txt") && ($fusion_uri!="/sitemap.xml") && ($fusion_uri!="/yandex_wdgt.xhtml") ) { require_once THEMES ."templates/header.php"; }
require_once COMPONENTS . $component . $exp;
if ( ($fusion_uri!="/robots.txt") && ($fusion_uri!="/sitemap.xml") && ($fusion_uri!="/yandex_wdgt.xhtml") ) { require_once THEMES ."templates/footer.php"; }


//debug( $component );
//debug( $alias );


//$component_url = "";
//if (isset($_GET['row_url'])) { $fusion_uri = "/". stripinput( $_GET['row_url'] ); }
//else { $fusion_uri = FUSION_URI; }
//$fusion_uri = explode("?", $fusion_uri);
//$fusion_uri = $fusion_uri[0];
//
//
//
//if ($fusion_uri=="/". $settings['opening_page']) {
//	header("HTTP/1.1 301 Moved Permanently");
//	header("Location: /");
//	exit();
//}
//
//
//if ($fusion_uri=="/") {
//	$url = $settings['opening_page'];
//	$url = explode("?", $url);
//	$url = $url[0];
//} else {
//	$url = $fusion_uri;
//	$url = substr($url, 1);
//	$url = explode("?", $url);
//	$url = $url[0];
//}
//
//
//if (file_exists(COMPONENTS . $url)) {
//	$component_url = COMPONENTS . $url;
//} else {
//
//	$viewseourl = viewseourl($url, "url");
//	$component_id = $viewseourl['seourl_component'];
//	$filedid = $viewseourl['seourl_filedid'];
//	$viewcompanent = viewcompanent($component_id, "id");
//	$component = $viewcompanent['components_name'];
//
//	if ($component) {
//		$component_url = COMPONENTS . $component .".php";
//	}
//
//
//	$page_404_url = "";
//	if ((!$component_id) && (!$filedid)) {
//		$component_url = COMPONENTS . "404.php";
//		$page_404_url = "page_404";
//	}
//
//} // Yesli URL Companent
//
//
//
//
//$cache_time = 1; // Время жизни кэша в секундах
//$cache_url = FUSION_URI;
//$cache_url = substr($cache_url, 1);
//if ( ($cache_url=="robots.txt") || ($cache_url=="sitemap.xml") || ($cache_url=="yandex_wdgt.xhtml") ) {
//	$cache_url = $cache_url;
//	$cache_file = BASEDIR ."sites". SITE ."/cache/". $cache_url; // Файл будет находиться, например, в /cache/a.php.html
//} else {
//	$cache_url = str_replace("/", "_", $cache_url);
//	$cache_url = str_replace(".", "_", $cache_url);
//	$cache_url = autocrateseourls( (FUSION_URI=="/" ? "index" : ($page_404_url ? $page_404_url : $cache_url)) );
//	$cache_file = BASEDIR ."sites". SITE ."/cache/". $cache_url ."_". LOCALESHORT; // Файл будет находиться, например, в /cache/a.php.html
//}
//if ( (file_exists($cache_file)) && ((time() - $cache_time) < filemtime($cache_file)) && (!iADMIN) ) {
//
//	if ($cache_url=="sitemap.xml") { header("Content-type: text/xml"); }
//	else if ($cache_url=="yandex_wdgt.xhtml") { header("Content-type: text/xhtml"); }
//	else if ($cache_url=="robots.txt") { header("Content-type: text/plain"); }
//
//	echo file_get_contents($cache_file); // Выводим содержимое файла
//} else {
//	ob_start(); // Открываем буфер для вывода, если кэша нет, или он устарел
//
//
//	if ( ($url!="robots.txt") && ($url!="sitemap.xml") && ($url!="yandex_wdgt.xhtml") ) { require_once THEMES ."templates/header.php"; }
//	require_once $component_url;
//	if ( ($url!="robots.txt") && ($url!="sitemap.xml") && ($url!="yandex_wdgt.xhtml") ) { require_once THEMES ."templates/footer.php"; }
//
//
//	if (!iADMIN) {
//		/*write_cache*/
//		$handle = fopen($cache_file, 'w'); // Открываем файл для записи и стираем его содержимое
//		fwrite($handle, ob_get_contents()); // Сохраняем всё содержимое буфера в файл
//		fclose($handle); // Закрываем файл
//		ob_end_flush(); // Выводим страницу в браузере
//		/*//write_cache*/
//	}
//
//} // Yesli Yest cache_file

?>