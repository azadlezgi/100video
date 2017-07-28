<?php

error_reporting(0);

if (!defined("IN_FUSION")) { die("Access Denied"); }

if ($_GET['pass']!="7872809u") { die("Access Denied"); }


if ($_GET['step']==1) {


    $atay_cat = 60;
    $atay_url = "https://ru.m.xhamster.com/categories/amateur";
    $zufile_name = $_SERVER['DOCUMENT_ROOT'] ."/xhamster_urls.txt";
    $lines_arr = file( $zufile_name );
    $autolist = 0;




    if (isset($_GET['page'])) {
        $zu_page_avay = (int)$_GET['page']+1;
    } else {
        $zu_page_avay = 1;
    }


    $url = $atay_url . (isset($_GET['page']) ? "/". (int)$_GET['page'] : "");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_COOKIE, 'fuid01=4b55eb3819e45ffc.GHz1qZGVLdiellfrdaV8oOurD-eyAQLruoiXkgwQlajZVIiK72GT1sl3vBlpr8MCD-dfUUrA7hZR_ahgXIXDZ-3EAqCx5Nfdnl4SSdbSbfPeOJCprMor9M0eB8hpEVX1;');
    $return = curl_exec($ch);
    // print $return;
    curl_close($ch);


    preg_match_all('/<img class="thumb rotator" src=".*" data-sprite=".*" data-current-screen=".*" id="([^\"]*)">/siU', $return, $return_ids);

    $return = str_replace("\n", "", $return);
    $return = str_replace("\r", "", $return);
    $return = str_replace(" ", "", $return);
    preg_match_all('/<divclass="item-container"><ahref="([^\"]*)"class="item(.*)">.*<\/a><\/div>/siU', $return, $return1);



    $say=0;
    foreach ($return1[1] as $res_key => $res_urls) { $say++;
        if ($say<50) {

            $res_urls_clear = explode("/videos/", $res_urls);
            $res_urls_clear = trim($res_urls_clear[1]);


            $item_id = (int)$return_ids[1][$res_key];

            if (!in_array($item_id, $lines_arr)) {


                $url = "https://ru.m.xhamster.com/videos/". $res_urls_clear;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_COOKIE, 'fuid01=4b55eb3819e45ffc.GHz1qZGVLdiellfrdaV8oOurD-eyAQLruoiXkgwQlajZVIiK72GT1sl3vBlpr8MCD-dfUUrA7hZR_ahgXIXDZ-3EAqCx5Nfdnl4SSdbSbfPeOJCprMor9M0eB8hpEVX1;');
                $return3 = curl_exec($ch);
                // print $return;
                curl_close($ch);

                preg_match_all('/<link itemprop="embedUrl" href="([^\"]*)">/siU', $return3, $return4);
                preg_match_all('/<link itemprop="thumbnailUrl" href="([^\"]*)">/siU', $return3, $return5);
                preg_match_all('/<h1 class="head" itemprop="name caption description">([^\"]*)<\/h1>/siU', $return3, $return6);
                preg_match_all('/<div class="time" itemprop="duration" content=".*">([^\"]*)<\/div>/siU', $return3, $return7);

                $atay_url = explode("?video=", $return4[1][0]);
                $atay_url = (int)trim($atay_url[1]);

                $atay_img = trim($return5[1][0]);

                $atay_name = trim($return6[1][0]);


                $xml_arr = simplexml_load_file( "https://translate.yandex.net/api/v1.5/tr/translate?key=trnsl.1.1.20170725T212718Z.852e11ed43af7ff6.9d8b7d03fd6d0e5030066dd5d36300a0a2b50b18&text=". $atay_name ."&lang=ru" );

                $atay_name = (!empty($xml_arr->text) ? $xml_arr->text : $atay_name);


                $atay_duration = trim($return7[1][0]);

                $atay_title = "Смотреть порно видео &quot;" . $atay_name . "&quot; без регистрации и смс на Pornovideo-HD.ru";

                $atay_desc = "Отличная подборка популярное порно видео &quot;" . $atay_name . "&quot; в HD качестве смотрите бесплатно и без регистрации на сайте Pornovideo-HD.ru";

                $atay_h1 = "Смотреть порно видео &quot;" . $atay_name . "&quot;";

                $atay_text = "Огромная, ежедневно обновляющаяся коллекция порно видео &quot;" . $atay_name . "&quot; – настоящая находка для любителя возбуждающих сцен и сексуальных сюжетов! Все виды секса, включая классику, анал, оральные ласки, мастурбацию, поливание спермой, инцест, групповуху и множество других вариантов порно видео вы можете смотреть бесплатно и даже без регистрации!";



                $alias = autocrateseourls($atay_name);




                if (!empty($atay_url) && !empty($atay_name) && !empty($atay_duration) && !empty($atay_img) && !empty($atay_img)) {

                    $get_headers = get_headers($atay_img);
                    if ($get_headers[0] == "HTTP/1.1 200 OK" || $get_headers[0] == "HTTP/1.0 200 OK") {


                        if (!mkdir(IMAGES_V . 'chastnoe', 0755, true)) {
                            // die('Не удалось создать директории...');
                        }
                        if(!file_exists(IMAGES_V . 'chastnoe' .'/index.php')) {
                            file_put_contents(IMAGES_V . 'chastnoe' .'/index.php', "");
                        }

                        if (!mkdir(IMAGES_V_T . 'chastnoe', 0755, true)) {
                            // die('Не удалось создать директории...');
                        }
                        if(!file_exists(IMAGES_V_T . 'chastnoe' .'/index.php')) {
                            file_put_contents(IMAGES_V_T . 'chastnoe' .'/index.php', "");
                        }

                        require_once INCLUDES . "photo_functions_include.php";

                        $image = $atay_img;
                        $image_ext = strrchr($image, ".");
                        $image_new_name = $alias;

                        if ($image_ext == ".gif") {
                            $image_filetype = 1;
                        } elseif ($image_ext == ".jpg") {
                            $image_filetype = 2;
                        } elseif ($image_ext == ".png") {
                            $image_filetype = 3;
                        } else {
                            $image_filetype = false;
                        }

                        $image = image_exists(IMAGES_V, 'chastnoe' ."/". $image_new_name . $image_ext);

//                    move_uploaded_file($atay_img, IMAGES_V . $image);
                        copy($atay_img, IMAGES_V . $image);
                        // if (function_exists("chmod")) { chmod(IMAGES_V . $image, 0644); }

                        $image_size = getimagesize(IMAGES_V . $image);
                        $image_width = $image_size[0];
                        $image_height = $image_size[1];

                        if ($settings['videos_thumb_ratio'] == 0) {
                            createthumbnail($image_filetype, IMAGES_V . $image, IMAGES_V_T . $image, $settings['videos_thumb_w'], $settings['videos_thumb_h']);
                        } else {
                            createsquarethumbnail($image_filetype, IMAGES_V . $image, IMAGES_V_T . $image, $settings['videos_thumb_']);
                        }
                        createthumbnail($image_filetype, IMAGES_V . $image, IMAGES_V . $image, ($image_width < $settings['videos_thumb_w'] ? $settings['videos_thumb_w'] : ($image_width > $settings['videos_photo_w'] ? $settings['videos_photo_w'] : $image_width)));


//                        debug($atay_img);
                    } // $get_headers 200 ok

                    $views = rand(1, 10000);
                    $date = FUSION_TODAY;
                    $ratings = rand(1, 5);


                    $result_user_rand = dbquery("SELECT user_id FROM ". DB_USERS ." ORDER BY RAND() LIMIT 1");
                    $data_user_rand = dbarray($result_user_rand);
                    $user = $data_user_rand['user_id'];

                    $result_insert = dbquery(
                        "INSERT INTO ". DB_VIDEOS ." (
															`title`,
															`description`,
															`name`,
															`h1`,
															`content`,
															`image`,
															`url`,
															`cat`,
															`user`,
															`access`,
															`status`,
															`date`,
															`comments`,
															`ratings`,
															`views`,
															`duration`,
															`alias`
						) VALUES (
															'". $atay_title ."',
															'". $atay_desc ."',
															'". $atay_name ."',
															'". $atay_h1 ."',
															'". $atay_text ."',
															'". $image ."',
															'". $atay_url ."',
															'". $atay_cat ."',
															'". $user ."',
															'0',
															'1',
															'". $date ."',
															'0',
															'". $ratings ."',
															'". $views ."',
															'". $atay_duration ."',
															'". $alias ."'
						)"
                    );
                    $id = _DB::$linkes->insert_id;


                } // if dont empty


                $fp_avash = fopen($zufile_name, "a");
                $test_avash = fwrite($fp_avash, $item_id ."\n");
                fclose($fp_avash);


                unset($atay_title);
                unset($atay_desc);
                unset($atay_name);
                unset($atay_h1);
                unset($atay_text);
                unset($image);
                unset($atay_url);
//                unset($atay_cat);
                unset($user);
                unset($date);
                unset($ratings);
                unset($views);
                unset($atay_duration);
                unset($alias);


                sleep(1);
            } // in_array
        } // if say
    } // foreach

    if($autolist == 1) {
    ?>
    <script type="text/javascript">
        <!--
        window.location.replace("parser?pass=7872809u&step=1&page=<?php echo $zu_page_avay; ?>");
        -->
    </script>
    <?php
    } // $autolist

    echo "Step1<br />\n";
} //if step












echo "OK!";
exit;







//
//if ($_GET['step']==5) {
//
//	### СДЕЛАТЬ ОТЛОЖЕННОГО ПУБЛИКАЦИЮ
//
//	echo "Step 5<hr>";
//
//	$resultarticle = dbquery("SELECT
//											article_id,
//											article_name
//									FROM ". DB_ARTICLES ."");
//	if (dbrows($resultarticle)) {
//		$say = 0;
//		$data_array = array();
//		while ($dataarticle = dbarray($resultarticle)) { $say++;
//			$article_cat_name = unserialize($dataarticle['article_name']);
//			$data_array[$dataarticle['article_id']] = $article_cat_name[LOCALESHORT];
//		} // db whille
//	} // db query
//
//
//	$amay = round($say/100*20);
//	$say_amay = $say-$amay;
//	$say_yug = round($say_amay/180);
//
//	echo "<pre>";
//	print_r($say);
//	echo " - ";
//	print_r($amay);
//	echo " = ";
//	print_r($say_amay);
//	echo " / ";
//	print_r($say_yug);
//	echo "</pre>";
//	echo "<hr>";
//
//	$article_date = 0;
//	$article_date = mktime();
//
//	$result_update = dbquery(
//						"UPDATE ". DB_SEOURL ." SET
//															seourl_lastmod='". date("Y-m-d", $article_date) ."'"
//	);
//	$result_art = dbquery(
//						"UPDATE ". DB_ARTICLES ." SET
//															article_date='". $article_date ."'"
//	);
//	$result_seourl = dbquery(
//						"UPDATE ". DB_ARTICLE_CATS ." SET
//															article_cat_date='". $article_date ."'"
//	);
//
//
//
//	if ($data_array) {
//		$j_say = 0;
//		$j_yug = 0;
//		foreach ($data_array as $data_key => $data_value) { $j_say++;
//
//			if ($j_say>$amay) {
//				$j_yug++;
//				if ($say_yug==$j_yug) {
//					$j_yug = 0;
//					$article_date = $article_date+86400;
//				}
//
//
//				$result_art = dbquery(
//							"UPDATE ". DB_ARTICLES ." SET
//																article_date='". $article_date ."'
//							WHERE article_id='". $data_key ."'"
//				);
//				$result_seourl = dbquery(
//							"UPDATE ". DB_SEOURL ." SET
//																seourl_lastmod='". date("Y-m-d", $article_date) ."'
//							WHERE seourl_component='2'
//							AND seourl_filedid='". $data_key ."'"
//				);
//
//			}
//
//	// // echo "<pre>";
//	// print_r($j_say);
//	// echo " - ";
//	// print_r($article_date);
//	// // echo "</pre>";
//	// echo "<br>";
//		}
//	}
//
//	echo "OK";
//
//	### СДЕЛАТЬ ОТЛОЖЕННОГО ПУБЛИКАЦИЮ
//
//} else if ($_GET['step']==4) {
//
//	### ЗАМЕНИТЬ TITLE и H1
//
//	echo "Step 4<hr>";
//
//
//	$resultcat = dbquery("SELECT
//											article_cat_id,
//											article_cat_name
//									FROM ". DB_ARTICLE_CATS ."
//									WHERE article_cat_title=''");
//	if (dbrows($resultcat)) {
//		while ($datacat = dbarray($resultcat)) {
//			$article_cat_name = unserialize($datacat['article_cat_name']);
//			$article_cat_name = "Ремонт стиральных машин ". $article_cat_name[LOCALESHORT];
//			$article_cat_name = array(
//											"ru"=>$article_cat_name
//										);
//
//			$result_updatecat = dbquery(
//						"UPDATE ". DB_ARTICLE_CATS ." SET
//															article_cat_title='". serialize($article_cat_name) ."',
//															article_cat_h1='". serialize($article_cat_name) ."'
//						WHERE article_cat_id='". $datacat['article_cat_id'] ."'"
//			);
//
//		} // db whille
//	} // db query
//
//	$resultarticle = dbquery("SELECT
//											article_id,
//											article_name
//									FROM ". DB_ARTICLES ."
//									WHERE article_title=''");
//	if (dbrows($resultarticle)) {
//		while ($dataarticle = dbarray($resultarticle)) {
//			$article_name = unserialize($dataarticle['article_name']);
//			$article_name = "Ремонт стиральных машин ". $article_name[LOCALESHORT];
//			$article_name = array(
//											"ru"=>$article_name
//										);
//
//			$result_update = dbquery(
//						"UPDATE ". DB_ARTICLES ." SET
//															article_title='". serialize($article_name) ."',
//															article_h1='". serialize($article_name) ."'
//						WHERE article_id='". $dataarticle['article_id'] ."'"
//			);
//
//		} // db whille
//	} // db query
//
//	echo "OK";
//
//	### ЗАМЕНИТЬ TITLE и H1
//
//} else if ($_GET['step']==3) {
//
//	### ВЗЯТЬ ХАРАКТЕРИСТИКИ
//
//
//	echo "Step 3<hr>";
//
//
//	$article_array = array();
//	$say=0;
//	$resultarticle = dbquery("SELECT
//											article_id,
//											article_name,
//											article_content
//									FROM ". DB_ARTICLES ."");
//	if (dbrows($resultarticle)) {
//		while ($dataarticle = dbarray($resultarticle)) { $say++;
//			$article_name = unserialize($dataarticle['article_name']);
//			$article_array[$say]['article_id'] = $dataarticle['article_id'];
//			$article_array[$say]['article_name'] = $article_name[LOCALESHORT];
//			$article_array[$say]['article_content'] = $dataarticle['article_content'];
//		}
//	}
//	// echo "<pre>";
//	// print_r($article_array);
//	// echo "</pre>";
//	// echo "<hr>";
//
//	$article_j=0;
//	foreach ($article_array as $article_key => $article_value) {
//		if ( preg_match("/\/product\//", $article_value['article_content']) ) { $article_j++;
//			if ($article_j<=99999991) {
//
//				$article_url = $article_value['article_content'];
//				$article_prod = explode("?", $article_url);
//				$article_prod = $article_prod[0];
//				$article_hid = explode("?hid=", $article_url);
//				$article_hid = $article_hid[1];
//
//				// $url = "https://market.yandex.ru/product/510641/spec?hid=90566&track=char";
//				$url = "https://market.yandex.ru". $article_prod . "/spec?hid=". $article_hid ."&track=char";
//				$ch = curl_init();
//				curl_setopt($ch, CURLOPT_URL, $url);
//				curl_setopt($ch, CURLOPT_HEADER, 0);
//				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)");
//				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//				curl_setopt ($ch, CURLOPT_COOKIE, 'fuid01=4b55eb3819e45ffc.GHz1qZGVLdiellfrdaV8oOurD-eyAQLruoiXkgwQlajZVIiK72GT1sl3vBlpr8MCD-dfUUrA7hZR_ahgXIXDZ-3EAqCx5Nfdnl4SSdbSbfPeOJCprMor9M0eB8hpEVX1;');
//				$return = curl_exec($ch);
//				// print $return;
//				curl_close($ch);
//
//				// echo "<pre>";
//				// print_r($url);
//				// echo "</pre>";
//				// echo "<hr>\n\n";
//
//
//				$return3 = $return;
//				$return3 = str_replace("\n", "", $return3);
//				$return3 = str_replace('<div class="product-spec-hint i-bem" onclick="return {\'product-spec-hint\':{}}"><span class="link link_pseudo_yes product-spec-hint__handle i-bem" role="button" tabindex="0"><span class="link__inner">?</span></span><div class="popup popup_theme_hint popup_autoclosable_yes popup_adaptive_yes popup_animate_yes product-spec-hint__popup i-bem" onclick="return {\'popup\':{\'directions\':[{\'to\':\'bottom\',\'axis\':\'left\',\'offset\':{\'top\':4,\'left\':-30}}]}}"><iframe frameborder="0" tabindex="-1" src="about:blank" class="popup__under"></iframe><i class="popup__tail"></i><i class="popup__close"></i><div class="popup__content"><div class="product-spec-hint__inner">', "<div class='azad1'><div class='azad2'><div class='azad3'><div class='azad4'>", $return3);
//				$return3 = preg_replace("/<div class='azad1'><div class='azad2'><div class='azad3'><div class='azad4'>.*?<\/div><\/div><\/div><\/div>/i", "", $return3);
//				$return3 = preg_replace("/<div class='azad1'>.*?<\/div>/", "", $return3);
//				$return = $return3;
//
//				preg_match_all('/<div class="product-spec-wrap__body">(.*)<\/div>/siU', $return, $return4);
//
//				$article_content = "";
//				foreach ($return4[0] as $return2_key => $return2_value) {
//					$article_content .= $return2_value ."\n";
//				}
//
//				$article_content = str_replace('<div class="product-spec-wrap__body">', '<div class="product-char">'."\n", $article_content);
//				$article_content = str_replace('<h2 class="title title_size_22">', '	<h4>', $article_content);
//				$article_content = str_replace('</h2>', '</h4>'."\n", $article_content);
//				$article_content = str_replace('<dl id="product-spec-" class="product-spec">', '	<div class="param">'."\n", $article_content);
//				$article_content = str_replace('</dl>', '	</div>'."\n", $article_content);
//				$article_content = str_replace('<span class="product-spec__name-inner">', '', $article_content);
//				$article_content = str_replace('</span>', '', $article_content);
//				$article_content = str_replace('<dt class="product-spec__name">', '		<label>', $article_content);
//				$article_content = str_replace('</dt>', '</label>'."\n", $article_content);
//				$article_content = str_replace('<span class="product-spec__value-inner">', '', $article_content);
//				$article_content = str_replace('</span>', '', $article_content);
//				$article_content = str_replace('<dd class="product-spec__value">', '		<span>', $article_content);
//				$article_content = str_replace('</dd>', '</span>'."\n", $article_content);
//				$article_content = stripinput($article_content);
//
//
//				$article_content = array(
//											"ru"=>$article_content
//										);
//				// echo "<pre>";
//				// print_r($article_content);
//				// echo "</pre>";
//				// echo "<hr>\n\n";
//
//				$result_update = dbquery(
//						"UPDATE ". DB_ARTICLES ." SET
//															article_content='". serialize($article_content) ."'
//						WHERE article_id='". $article_value['article_id'] ."'"
//				);
//
//
//			} // if article_j<1
//		} // preg_match product
//	} // foreach article_array
//
//	### ВЗЯТЬ ХАРАКТРИСТИКИ
//
//} else if ($_GET['step']==2) {
//
//	### ВЗЯТЬ ФОТОС
//
//	require_once INCLUDES."photo_functions_include.php";
//
//	echo "Step 2<hr>";
//
//
//	$article_array = array();
//	$say=0;
//	$resultarticle = dbquery("SELECT
//											article_id,
//											article_name,
//											article_content,
//											article_image
//									FROM ". DB_ARTICLES ."");
//	if (dbrows($resultarticle)) {
//		while ($dataarticle = dbarray($resultarticle)) { $say++;
//			$article_name = unserialize($dataarticle['article_name']);
//			$article_array[$say]['article_id'] = $dataarticle['article_id'];
//			$article_array[$say]['article_name'] = $article_name[LOCALESHORT];
//			$article_array[$say]['article_content'] = $dataarticle['article_content'];
//			$article_array[$say]['article_image'] = $dataarticle['article_image'];
//		}
//	}
//	// echo "<pre>";
//	// print_r($article_array);
//	// echo "</pre>";
//	// echo "<hr>";
//
//	$article_j=0;
//	foreach ($article_array as $article_key => $article_value) {
//		// if (!file_exists(IMAGES_A . $article_value['article_image'])) { $article_j++;
//		if ( $article_value['article_image']=="" ) { $article_j++;
//			if ($article_j<=99991) {
//
//				$article_url = $article_value['article_content'];
//				$article_prod = explode("?", $article_url);
//				$article_prod = $article_prod[0];
//				$article_hid = explode("?hid=", $article_url);
//				$article_hid = $article_hid[1];
//
//				// $url = "https://market.yandex.ru/product/510641/spec?hid=90566&track=char";
//				// $url = "https://market.yandex.ru". $article_prod . "/spec?hid=". $article_hid ."&track=char";
//				$url = "https://market.yandex.ru". $article_prod;
//				$ch = curl_init();
//				curl_setopt($ch, CURLOPT_URL, $url);
//				curl_setopt($ch, CURLOPT_HEADER, 0);
//				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)");
//				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//				curl_setopt ($ch, CURLOPT_COOKIE, 'fuid01=4b55eb3819e45ffc.GHz1qZGVLdiellfrdaV8oOurD-eyAQLruoiXkgwQlajZVIiK72GT1sl3vBlpr8MCD-dfUUrA7hZR_ahgXIXDZ-3EAqCx5Nfdnl4SSdbSbfPeOJCprMor9M0eB8hpEVX1;');
//				$return = curl_exec($ch);
//				// print $return;
//				curl_close($ch);
//
//				preg_match_all('/<div class="product-card-gallery__image-container one"><img class="image" src="([^\"]*)".*><\/div>/siU', $return, $return1);
//				if ($return1[1][0]=="") {
//					preg_match_all('/<div class="product-card-gallery__image-container"><img class="image" src="([^\"]*)".*><\/div>/siU', $return, $return1);
//				}
//				// echo "<pre>";
//				// print_r($url);
//				// echo "<br />";
//				// print_r($article_value['article_image']);
//				// echo "<br />";
//				// print_r($return1);
//				// echo "</pre>";
//				// echo "<hr>";
//
//
//
//
//
//				$article_image = "http:". $return1[1][0];
//				if ($article_image) {
//
//					$article_image_size = getimagesize($article_image);
//					$article_image_width = $article_image_size[0];
//					$article_image_height = $article_image_size[1];
//
//					// echo "<pre>";
//					// print_r($article_image_size);
//					// echo "</pre>";
//					// echo "<hr>";
//
//					if ( preg_match("/.gif/", $article_image) ) { $article_image_filetype = 1; $article_image_file_ext = ".gif"; }
//					else if ( preg_match("/.jpg/", $article_image) ) { $article_image_filetype = 2; $article_image_file_ext = ".jpg"; }
//					else if ( preg_match("/.jpeg/", $article_image) ) { $article_image_filetype = 2; $article_image_file_ext = ".jpeg"; }
//					else if ( preg_match("/.png/", $article_image) ) { $article_image_filetype = 3; $article_image_file_ext = ".png"; }
//					else { $article_image_filetype = false; }
//
//					$article_image_prod = str_replace("/product/", "", $article_prod);
//					$article_image_name = $article_image_prod ."_image_". $article_value['article_id'] . $article_image_file_ext;
//
//					if (file_exists(IMAGES_A . $article_image_name)) {
//						echo "<font color='green'>Файл существует</font><br>";
//					} else {
//						if (!copy($article_image, IMAGES_A . $article_image_name)) {
//							echo "<font color='yellow'>Файл не скопирован</font><br>";
//						} else {
//							createthumbnail($article_image_filetype, IMAGES_A . $article_image_name, IMAGES_A_T . $article_image_name, ($article_image_width<$settings['articles_thumb_w'] ? $article_image_width : $settings['articles_thumb_w']), ($article_image_height<$settings['articles_thumb_h'] ? $article_image_height : $settings['articles_thumb_h']));
//							createthumbnail($article_image_filetype, IMAGES_A . $article_image_name, IMAGES_A . $article_image_name, ($article_image_width<$settings['articles_photo_w'] ? $article_image_width : $settings['articles_photo_w']), ($article_image_width<$settings['articles_photo_w'] ? $article_image_width : $settings['articles_photo_h']));
//						}
//						echo "<font color='red'>Файл не существует</font><br>";
//					}
//
//
//					$result_update = dbquery(
//							"UPDATE ". DB_ARTICLES ." SET
//																article_image='". $article_image_name ."'
//							WHERE article_id='". $article_value['article_id'] ."'"
//					);
//
//				} // Photo Upload
//
//
//
//
//			} // if article_j<1
//		} // preg_match product
//	} // foreach article_array
//
//	### ВЗЯТЬ ФОТОС
//
//} else {
//
//	$cat_parent = 0;
//	$catalog = 61335;
//	$seourl_url_nachala = "catalog/";
//	$url = "https://market.yandex.ru/vendors.xml?CAT_ID=12380443&hid=12385944&track=fr_cm_vendor";
//
//
//	echo "Step 1<hr>";
//
//	$viewcompanent = viewcompanent("article_cats", "name");
//	$seourl_component = $viewcompanent['components_id'];
//
//	// echo "<pre>";
//	// print_r($article_cat_array);
//	// echo "</pre>";
//	// echo "<hr>";
//
//
//	$ch = curl_init();
//	curl_setopt($ch, CURLOPT_URL, $url);
//	curl_setopt($ch, CURLOPT_HEADER, 0);
//	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)");
//	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//	curl_setopt ($ch, CURLOPT_COOKIE, 'fuid01=4b55eb3819e45ffc.GHz1qZGVLdiellfrdaV8oOurD-eyAQLruoiXkgwQlajZVIiK72GT1sl3vBlpr8MCD-dfUUrA7hZR_ahgXIXDZ-3EAqCx5Nfdnl4SSdbSbfPeOJCprMor9M0eB8hpEVX1;');
//	$return = curl_exec($ch);
//	// print $return;
//	curl_close($ch);
//
//	preg_match_all("/<a class=\"b-link\" href=\"([^\"]*)\">(.*)<\/a>/siU", $return, $return1);
//
//	$j_article_cat = 0;
//	$all_cats_urls = array();
//	foreach ($return1[2] as $return1_key => $return1_value) {
//		if ( preg_match("/\/guru.xml/", $return1[1][$return1_key]) ) {
//
//		$article_cat_array = array();
//		$resultarticle_cat = dbquery("SELECT
//												article_cat_id,
//												article_cat_name
//										FROM ". DB_ARTICLE_CATS ."
//										WHERE article_cat_parent='". $cat_parent ."'");
//		if (dbrows($resultarticle_cat)) {
//			while ($dataarticle_cat = dbarray($resultarticle_cat)) {
//				$article_cat_name = unserialize($dataarticle_cat['article_cat_name']);
//				$article_cat_array[$dataarticle_cat['article_cat_id']] = $article_cat_name[LOCALESHORT];
//			}
//		}
//
//
//
//		if ( !in_array($return1_value, $article_cat_array) ) { $j_article_cat++;
//
//
//			$return1_value_name = array(
//										"ru"=>$return1_value
//									);
//
//			$result_cat = dbquery(
//					"INSERT INTO ". DB_ARTICLE_CATS ." (
//													article_cat_title,
//													article_cat_description,
//													article_cat_keywords,
//													article_cat_name,
//													article_cat_h1,
//													article_cat_content,
//													article_cat_image,
//													article_cat_parent,
//													article_cat_access,
//													article_cat_status,
//													article_cat_order,
//													article_cat_date
//					) VALUES (
//													'',
//													'',
//													'',
//													'". serialize($return1_value_name) ."',
//													'',
//													'',
//													'',
//													". $cat_parent .",
//													0,
//													1,
//													". $j_article_cat .",
//													". FUSION_TODAY ."
//					)"
//				);
//				$result_cat_id = mysql_insert_id();
//
//
//
//				$seourl_url = autocrateseourls($return1_value);
//
//				$viewcompanent = viewcompanent("article_cats", "name");
//				$seourl_component = $viewcompanent['components_id'];
//
//				$result_url = dbquery(
//									"INSERT INTO ". DB_SEOURL ." (
//																	seourl_url,
//																	seourl_component,
//																	seourl_filedid
//										) VALUES (
//																	'". $seourl_url_nachala . $seourl_url ."',
//																	'". $seourl_component ."',
//																	'". $result_cat_id ."'
//										)"
//				);
//
//
//			// echo "<pre>";
//			// print_r($return1_value);
//			// echo "</pre>";
//			// echo "<hr>";
//
//		} else {
//			// echo "<pre>";
//			// print_r($return1_value . " УЖЕ ЕСТЬ");
//			// echo "</pre>";
//			// echo "<hr>";
//		} // if !in_array
//
//		$all_cats_urls[$return1_key]['all_cats_name'] = $return1_value;
//		$all_cats_urls[$return1_key]['all_cats_url'] = $return1[1][$return1_key];
//
//		} // preg_match guru.xml
//	} // foreach ($return1[2]
//
//	// echo "<pre>";
//	// print_r($all_cats_urls);
//	// echo "</pre>";
//	// echo "<hr>";
//
//
//
//	// exit;
//
//	sleep(2);
//
//
//
//	$viewcompanent = viewcompanent("article_cats", "name");
//	$seourl_component = $viewcompanent['components_id'];
//
//	$article_cat_array = array();
//	$article_cat_field_in = array();
//	$resultarticle_cat = dbquery("SELECT
//											article_cat_id,
//											article_cat_name,
//											seourl_url
//									FROM ". DB_ARTICLE_CATS ."
//									LEFT JOIN ". DB_SEOURL ." ON seourl_filedid=article_cat_id AND seourl_component=". $seourl_component ."
//									WHERE article_cat_parent='". $cat_parent ."'");
//	if (dbrows($resultarticle_cat)) {
//		while ($dataarticle_cat = dbarray($resultarticle_cat)) {
//			$article_cat_field_in[] = $dataarticle_cat['article_cat_id'];
//			$article_cat_name = unserialize($dataarticle_cat['article_cat_name']);
//			$article_cat_array[$dataarticle_cat['article_cat_id']]['article_cat_id'] = $dataarticle_cat['article_cat_id'];
//			$article_cat_array[$dataarticle_cat['article_cat_id']]['article_cat_name'] = $article_cat_name[LOCALESHORT];
//			$article_cat_array[$dataarticle_cat['article_cat_id']]['seourl_url'] = $dataarticle_cat['seourl_url'];
//		}
//	}
//
//
//	$article_cat_field_in = implode(",", $article_cat_field_in);
//	// echo "<pre>";
//	// print_r($article_cat_field_in);
//	// echo "</pre>";
//	// echo "<hr>";
//
//
//	$article_array = array();
//	$resultarticle = dbquery("SELECT
//																article_id,
//																article_name
//							FROM ". DB_ARTICLES ."
//							WHERE article_cat IN (". $article_cat_field_in .");
//							");
//	if (dbrows($resultarticle)) {
//		while ($dataarticle = dbarray($resultarticle)) {
//			$article_name = unserialize($dataarticle['article_name']);
//			$article_array[$dataarticle['article_id']] = $article_name[LOCALESHORT];
//		}
//	}
//	// echo "<pre>";
//	// print_r($article_array);
//	// echo "</pre>";
//	// echo "<hr>";
//
//
//	$product_urls_array = array();
//	$j_arts=0;
//	// $say_key=0;
//	foreach ($all_cats_urls as $return1_key => $return1_value) {
//		if (substr_count($return1_value['all_cats_url'], "guru.xml")) { $j_arts++;
//			if ($j_arts<=999991) {
//
//
//
//				$gfilter = explode("PF%3D", $return1_value['all_cats_url']);
//				$gfilter = explode("~EQ~sel~", $gfilter[1]);
//				$gfilter = $gfilter[0];
//
//				$markaid = explode("~EQ~sel~", $return1_value['all_cats_url']);
//				$markaid = explode("-VIS", $markaid[1]);
//				$markaid = $markaid[0];
//
//				$hid = explode("hid=", $return1_value['all_cats_url']);
//				$hid = $hid[1];
//
//
//				for ($i_page=1; $i_page <= 999991; $i_page++) {
//					$url = "https://market.yandex.ru/catalog/". $catalog ."/list?hid=". $hid ."&gfilter=". $gfilter ."%3A". $markaid ."&page=". $i_page;
//					// $url = "https://market.yandex.ru/catalog/". $catalog ."/list?hid=". $hid ."&page=". $i_page;
//					$ch = curl_init();
//					curl_setopt($ch, CURLOPT_URL, $url);
//					curl_setopt($ch, CURLOPT_HEADER, 0);
//					curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)");
//					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//					curl_setopt ($ch, CURLOPT_COOKIE, 'fuid01=4b55eb3819e45ffc.GHz1qZGVLdiellfrdaV8oOurD-eyAQLruoiXkgwQlajZVIiK72GT1sl3vBlpr8MCD-dfUUrA7hZR_ahgXIXDZ-3EAqCx5Nfdnl4SSdbSbfPeOJCprMor9M0eB8hpEVX1;');
//					$return10 = curl_exec($ch);
//					// print $return;
//					curl_close($ch);
//
//					preg_match_all("/<a class=\"snippet-card__header-link link\" href=\"([^\"]*)\"><span class=\"snippet-card__header-text\" title=\"(.*)\">(.*)<\/span><\/a>/siU", $return10, $return11);
//
//					preg_match_all("/<a class=\"button button_size_s button_theme_pseudo button_side_right button_type_arrow  i-bem \" role=\"button\" href=\"([^\"]*)\"><span class=\"button__text\">→<\/span><\/a>/siU", $return10, $return12);
//
//	// echo "<pre>";
//	// print_r($url);
//	// echo "</pre>";
//	// echo "<hr>";
//
//
//					$j_say_aa = 0;
//					foreach ($return11[1] as $return11_key => $return11_value) { $j_say_aa++; // $say_key++;
//						// $product_urls_array[$say_key]['cat_name_fey'] = $return1_value['all_cats_name'];
//						// $product_urls_array[$say_key]['prod_url'] = $return11_value;
//						// $product_urls_array[$say_key]['prod_name'] = $return11[2][$return11_key];
//
//
//						if ( !in_array($return11[3][$return11_key], $article_array) ) { $j_article_prod++;
//
//							$article_cat_value_seourl = "";
//							$article_cat_value_catid = "";
//							foreach ($article_cat_array as $article_cat_key => $article_cat_value) {
//								if ($article_cat_value['article_cat_name']==$return1_value['all_cats_name']) {
//									$article_cat_value_seourl = $article_cat_value['seourl_url'];
//									$article_cat_value_catid = $article_cat_value['article_cat_id'];
//								}
//							}
//
//							$return1_value_prod_name = array(
//														"ru"=>$return11[3][$return11_key]
//													);
//
//							$result_prod = dbquery(
//									"INSERT INTO ". DB_ARTICLES ." (
//																	article_title,
//																	article_description,
//																	article_keywords,
//																	article_name,
//																	article_h1,
//																	article_content,
//																	article_image,
//																	article_cat,
//																	article_access,
//																	article_status,
//																	article_order,
//																	article_date,
//																	article_comments,
//																	article_ratings
//									) VALUES (
//																	'',
//																	'',
//																	'',
//																	'". serialize($return1_value_prod_name) ."',
//																	'',
//																	'". $return11_value ."',
//																	'',
//																	". (INT)$article_cat_value_catid .",
//																	0,
//																	1,
//																	". $j_article_prod .",
//																	". FUSION_TODAY .",
//																	0,
//																	0
//									)"
//							);
//							$result_prod_id = mysql_insert_id();
//
//
//
//							$seourl_url = autocrateseourls( $return11[3][$return11_key] );
//							$seourl_url = $article_cat_value_seourl ."/". $seourl_url;
//
//							$viewcompanent = viewcompanent("articles", "name");
//							$seourl_component = $viewcompanent['components_id'];
//
//							$result_url = dbquery(
//													"INSERT INTO ". DB_SEOURL ." (
//																					seourl_url,
//																					seourl_component,
//																					seourl_filedid
//														) VALUES (
//																					'". $seourl_url ."',
//																					'". $seourl_component ."',
//																					'". $result_prod_id ."'
//														)"
//							);
//
//							// echo "<font color='red'>ADD: ID". $result_prod_id ." ". $return11[2][$return11_key] ."</font><br />\n";
//
//						} else {
//
//							// echo "<font color='green'>YES ". $return11[2][$return11_key] ."</font><br />\n";
//
//						} // if !in_array
//
//
//
//					} // foreach return11[1]
//
//					if ($return12[1][0]=="") {
//						break;
//					}
//
//
//					sleep(1);
//
//				} // for ($i=0; $i < 1000;
//
//
//			} // if j_arts<=1
//		} // substr_count guru.xml
//
//
//	} // foreach return1[1]
//
//} // if step
//
//echo "OK!";