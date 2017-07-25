<?php

	require_once "../includes/maincore.php";

	if (!checkrights("V") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

	include LOCALE . LOCALESET ."admin/videos.php";

	$settings['videos_per_page'] = 50;

	if ($_GET['action']!="order") {
		require_once THEMES ."templates/admin_header.php";
		require_once INCLUDES."photo_functions_include.php";
		if ($settings['tinymce_enabled']) {
			$_SESSION['tinymce_sess'] = 1;
			// echo "<script language='javascript' type='text/javascript'>advanced();</script>\n";
		} else {
			require_once INCLUDES."html_buttons_include.php";
		}

		opentable($locale['001']);
	} // Yesli action ne order


	if ($_GET['action']=="order") {

		if (isset($_GET['listItem']) && is_array($_GET['listItem'])) {
			foreach ($_GET['listItem'] as $position => $item) {
				if (isnum($position) && isnum($item)) {
					dbquery("UPDATE ". DB_VIDEOS ." SET video_order='". ($position+1) ."' WHERE video_id='". $item ."'");
				}
			}

			header("Content-Type: text/html; charset=". $locale['charset'] ."\n");
			echo "<div id='close-message'>\n";
			echo "	<div class='success'>". $locale['success_007'] ."</div>\n";
			echo "</div>\n";

		}


	} else if ($_GET['action']=="status") {

		$video_id = (INT)$_GET['id'];
		$video_status = (INT)$_GET['status'];
		$video_status = ($video_status ? 0 : 1);

		$result = dbquery("UPDATE ". DB_VIDEOS ." SET
														video_status='". $video_status ."'
		WHERE video_id='". $video_id ."'");

		redirect(FUSION_SELF . $aidlink."&status=". ($video_status ? "active" : "deactive") ."&id=". $video_id, false);

	} else if ($_GET['action']=="del") {

		$result = dbquery("SELECT video_image FROM ". DB_VIDEOS ." WHERE video_id='". (INT)$_GET['id'] ."'");
		if (dbrows($result)) {
				$data = dbarray($result);
			if (!empty($data['video_image']) && file_exists(IMAGES_V . $data['video_image'])) { unlink(IMAGES_V . $data['video_image']); }
			if (!empty($data['video_image']) && file_exists(IMAGES_V_T . $data['video_image'])) { unlink(IMAGES_V_T . $data['video_image']); }
		} // Tesli Yest DB query

		$result = dbquery("DELETE FROM ". DB_VIDEOS ." WHERE video_id='". (INT)$_GET['id'] ."'");
		$result = dbquery("DELETE FROM ". DB_COMMENTS ." WHERE comment_item_id='". (INT)$_GET['id'] ."' and comment_type='V'");
		$result = dbquery("DELETE FROM ". DB_RATINGS ." WHERE rating_item_id='". (INT)$_GET['id'] ."' and rating_type='V'");

		$viewcompanent = viewcompanent("videos", "name");
		$seourl_component = $viewcompanent['components_id'];
		$seourl_filedid = (INT)$_GET['id'];

		$result = dbquery("DELETE FROM ". DB_SEOURL ." WHERE seourl_component='". $seourl_component ."' AND seourl_filedid='". $seourl_filedid ."'");


		///////////////// POSITIONS /////////////////
		$position=1;
		$result_position = dbquery("SELECT video_id FROM ". DB_VIDEOS ." ORDER BY `video_order`");
		if (dbrows($result_position)) {
			while ($data_position = dbarray($result_position)) {
				$position++;
				dbquery("UPDATE ". DB_VIDEOS ." SET video_order='". $position ."' WHERE video_id='". $data_position['video_id'] ."'");
			} // db whille
		} // db query
		///////////////// POSITIONS /////////////////


		redirect(FUSION_SELF . $aidlink ."&status=del&id=". (INT)$_GET['id']);

	} else if ($_GET['action']=="add" || $_GET['action']=="edit") {

		if (isset($_POST['save'])) {

			$video_title = stripinput($_POST['video_title']);
			$video_description = stripinput($_POST['video_description']);
			$video_keywords = stripinput($_POST['video_keywords']);
			$video_name = stripinput($_POST['video_name']);
			$video_h1 = stripinput($_POST['video_h1']);
			$video_content = stripinput($_POST['video_content']);

			$video_image = $_FILES['video_image']['name'];
			$video_imagetmp  = $_FILES['video_image']['tmp_name'];
			$video_imagesize = $_FILES['video_image']['size'];
			$video_imagetype = $_FILES['video_image']['type'];

			$video_image_yest = stripinput($_POST['video_image_yest']);
			$video_image_del = (INT)$_POST['video_image_del'];


			$video_url = stripinput($_POST['video_url']);
			preg_match_all("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $video_url, $video_url_matches);
			// echo "<pre>";
			// print_r($video_url_matches);
			// echo "</pre>";
			// echo "<hr>";
			$video_url = $video_url_matches[0][0];

			$video_cat = stripinput($_POST['video_cat']);
			$video_user = stripinput($_POST['video_user']);
			if ($video_user==$userdata['user_id']) {
				$result_user_rand = dbquery("SELECT user_id FROM ". DB_USERS ." ORDER BY RAND() LIMIT 1");
				$data_user_rand = dbarray($result_user_rand);
				$video_user = $data_user_rand['user_id'];
			}

			$video_access = (INT)$_POST['video_access'];
			$video_status = (INT)$_POST['video_status'];

			// if ($_GET['action']=="edit") {
			// 	$video_order = (INT)$_POST['video_order'];
			// } else {
			// 	$result_order = dbquery(
			// 		"SELECT 
			// 									video_id,
			// 									video_order
			// 		FROM ". DB_VIDEOS ."
			// 		ORDER BY video_order DESC
			// 		LIMIT 1"
			// 	);
			// 	if (dbrows($result_order)) {
			// 		$data_order = dbarray($result_order);
			// 		$video_order = $data_order['video_order']+1;
			// 	} else {
			// 		$video_order = 1;
			// 	}
			// }

			// $video_date = FUSION_TODAY;
			$video_date = stripinput($_POST['video_date']);
			$video_date = strtotime( $video_date );
			$video_comments = (INT)$_POST['video_comments'];
			$video_ratings = (INT)$_POST['video_ratings'];
			if ($video_ratings<1) {
				$video_ratings = rand(1, 5);
			}
			$video_views = (INT)$_POST['video_views'];
			if ($video_views<1) {
				$video_views = rand(1, 10000);
			}

			$video_alias = stripinput($_POST['video_alias']);

		} else if ($_GET['action']=="edit") {

			$viewcompanent = viewcompanent("videos", "name");
			$seourl_component = $viewcompanent['components_id'];

			$result = dbquery(
				"SELECT 
											video_id,
											video_title,
											video_description,
											video_keywords,
											video_name,
											video_h1,
											video_content,
											video_image,
											video_url,
											video_cat,
											video_user,
											video_access,
											video_status,
											video_date,
											video_comments,
											video_ratings,
											video_views,
											seourl_url
				FROM ". DB_VIDEOS ."
				LEFT JOIN ". DB_SEOURL ." ON seourl_filedid=video_id AND seourl_component=". $seourl_component ."
				WHERE video_id='". (INT)$_GET['id'] ."' LIMIT 1"
			);
			if (dbrows($result)) {
				$data = dbarray($result);

				$video_title = unserialize($data['video_title']);
				$video_description = unserialize($data['video_description']);
				$video_keywords = unserialize($data['video_keywords']);
				$video_name = unserialize($data['video_name']);
				$video_h1 = unserialize($data['video_h1']);
				$video_content = unserialize($data['video_content']);
				$video_image = $data['video_image'];
				$video_url = $data['video_url'];
				$video_cat =  $data['video_cat'];
				$video_user =  $data['video_user'];
				$video_access = $data['video_access'];
				$video_status = $data['video_status'];
				// $video_order = $data['video_order'];
				$video_date = $data['video_date'];
				$video_comments = $data['video_comments'];
				$video_ratings = $data['video_ratings'];
				$video_views = $data['video_views'];

				$video_alias = $data['seourl_url'];

			} else {
				redirect(FUSION_SELF . $aidlink);
			}

		} else {

				$video_title = "";
				$video_description = "";
				$video_keywords = "";
				$video_name = "";
				$video_h1 = "";
				$video_content = "";
				$video_image = "";
				$video_url = "";
				$video_cat = 0;
				$video_user = $userdata['user_id'];
				$video_access = 0;
				$video_status = 1;
				// $video_order = 0;
				$video_date = FUSION_TODAY;
				$video_comments = "";
				$video_ratings = 0;
				$video_views = 0;
				$video_alias = "";

		} // Yesli POST


		########## SEO URL OPARATIONS ##########
		if ($settings['seourl_prefix']) {
			$seourl_prefix_strlen =  strlen($settings['seourl_prefix']);
			$seourl_prefix_alias = substr($video_alias, -$seourl_prefix_strlen);
			if ($seourl_prefix_alias==$settings['seourl_prefix']) {
				$video_alias = substr($video_alias, 0, -$seourl_prefix_strlen);
			}
		} // yesli yest seourl_prefix

		if ($video_cat!=0) {
			$viewcompanent = viewcompanent("video_cats", "name");
			$seourl_component = $viewcompanent['components_id'];

			foreach ($seourl as $seourl_key => $seourl_value) {
				if ($video_cat==$seourl_value['seourl_filedid'] && $seourl_component==$seourl_value['seourl_component']) {
					$cat_url = $seourl_value['seourl_url'];
				}
			}
			if ($settings['seourl_prefix']) {
				$seourl_prefix_strlen =  strlen($settings['seourl_prefix']);
				$seourl_prefix_alias = substr($cat_url, -$seourl_prefix_strlen);
				if ($seourl_prefix_alias==$settings['seourl_prefix']) {
					$cat_url = substr($cat_url, 0, -$seourl_prefix_strlen);
				}
			} // yesli yest seourl_prefix

			$video_alias = str_replace($cat_url ."/", "", $video_alias);
		} else {
			$video_alias = str_replace($settings['companent_root_url'], "", $video_alias);
		}
		########## //SEO URL OPARATIONS ##########



		if (isset($_POST['save'])) {


			foreach ($languages as $key => $value) {
				if (empty($video_name[$value['languages_short']])) { $error .= "<div class='error'>". $locale['error_001'] ." - ". $value['languages_name'] ."</div>\n"; }
			}
			if (!$video_cat) { $error .= "<div class='error'>". $locale['error_002'] ."</div>\n"; }
			if (!$video_url) { $error .= "<div class='error'>". $locale['error_003'] ."</div>\n"; }
			// if ( ($video_url) && (!eregi("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $video_url)) ) { $error .= "<div class='error'>". $locale['error_004'] ."</div>\n"; }

			if ($video_image) {
				// if (strlen($video_image) > 255) { $error .= "<div class='error'>". $locale['error_050'] ."</div>\n"; $video_image = ""; }
				// проверяем расширение файла
				$video_image_ext = strtolower(substr($video_image, 1 + strrpos($video_image, ".")));
				if (!in_array($video_image_ext, $photo_valid_types)) { $error .= "<div class='error'>". $locale['error_051'] ."</div>\n"; $video_image = ""; }
				// 1. считаем кол-во точек в выражении - если большей одной - СВОБОДЕН!
				$video_image_findtochka = substr_count($video_image, ".");
				if ($video_image_findtochka>1) { $error .= "<div class='error'>". $locale['error_052'] ."</div>\n"; $video_image = ""; }
				// 2. если в имени есть .php, .html, .htm - свободен! 
				if (preg_match("/\.php/i",$video_image))  { $error .= "<div class='error'>". $locale['error_053'] ."</div>\n"; $video_image = ""; }
				if (preg_match("/\.html/i",$video_image)) { $error .= "<div class='error'>". $locale['error_054'] ."</div>\n"; $video_image = ""; }
				if (preg_match("/\.htm/i",$video_image))  { $error .= "<div class='error'>". $locale['error_055'] ."</div>\n"; $video_image = ""; }
				// 5. Размер фото
				$video_image_fotosize = round($video_imagesize/10.24)/100; // размер ЗАГРУЖАЕМОГО ФОТО в Кб.
				$video_image_fotomax = round($settings['videos_photo_max_b']/10.24)/100; // максимальный размер фото в Кб.
				if ($video_image_fotosize>$video_image_fotomax) { $error .= "<div class='error'>". $locale['error_056'] ."<br />". $locale['error_057'] ." ". $video_image_fotosize ." Kb<br />". $locale['error_058'] ." ". $video_image_fotomax ." Kb</div>\n"; $video_image = ""; }
				// // 6. "Габариты" фото > $maxwidth х $maxheight - ДО свиданья! :-)
				$video_image_getsize = getimagesize($video_imagetmp);
				if ($video_image_getsize[0]>$settings['videos_photo_max_w'] or $video_image_getsize[1]>$settings['videos_photo_max_h']) { $error .= "<div class='error'>". $locale['error_059'] ."<br />". $locale['error_060'] ." ". $video_image_getsize[0] ."x". $video_image_getsize[1] ."<br />". $locale['error_061'] ." ". $settings['videos_photo_max_w'] ."x". $settings['videos_photo_max_h'] ."</div>\n"; $video_image = ""; }
				// // if ($video_image_getsize[0]<$video_image_getsize[1]) { $error .= "<div class='error'>". $locale['error_062'] ."</div>\n"; $video_image = ""; }
				// // Foto 0 Kb
				// if ($video_imagesize<0 and $video_imagesize>$settings['video_size']) { $error .= "<div class='error'>". $locale['error_063'] ."</div>\n"; $video_image = ""; }
			}


			if (isset($error)) {

				echo "	<div class='admin-message'>\n";
				echo "		<div id='close-message'>". $error ."</div>\n";
				echo "	</div>\n";

			} else {


				if ($video_image) {

					$video_image_ext = strrchr($video_image, ".");
					$video_image = FUSION_TODAY;
					$img_rand_key = mt_rand(100, 999);

					if ($video_image_ext == ".gif") {
						$video_image_filetype = 1;
					} elseif ($video_image_ext == ".jpg") {
						$video_image_filetype = 2;
					} elseif ($video_image_ext == ".png") {
						$video_image_filetype = 3;
					} else {
						$video_image_filetype = false; 
					}

					$video_image = image_exists(IMAGES_V, $video_image . $img_rand_key . $video_image_ext);

					move_uploaded_file($video_imagetmp, IMAGES_V . $video_image);
					// if (function_exists("chmod")) { chmod(IMAGES_V . $video_image, 0644); }

					$video_image_size = getimagesize(IMAGES_V . $video_image);
					$video_image_width = $video_image_size[0];
					$video_image_height = $video_image_size[1];

					if ($settings['videos_thumb_ratio']==0) {
						createthumbnail($video_image_filetype, IMAGES_V . $video_image, IMAGES_V_T . $video_image, ($video_image_width<$settings['videos_thumb_w'] ? $video_image_width : $settings['videos_thumb_w']), ($video_image_height<$settings['videos_thumb_h'] ? $video_image_height : $settings['videos_thumb_h']));
					} else {
						createsquarethumbnail($video_image_filetype, IMAGES_V . $video_image, IMAGES_V_T . $video_image, ($video_image_width<$settings['videos_thumb_w'] ? $video_image_width : $settings['videos_thumb_w']));
					}
					createthumbnail($video_image_filetype, IMAGES_V . $video_image, IMAGES_V . $video_image, ($video_image_width<$settings['videos_photo_w'] ? $video_image_width : $settings['videos_photo_w']));

				} else {
					$video_image = $video_image_yest;
				}



				if ($_GET['action']=="edit") {

					if ($video_image_del) {
						if ($video_image_yest && file_exists(IMAGES_V . $video_image_yest)) { unlink(IMAGES_V . $video_image_yest); }
						if ($video_image_yest && file_exists(IMAGES_V_T . $video_image_yest)) { unlink(IMAGES_V_T . $video_image_yest); }
						$video_image = "";
					}

					$result = dbquery(
						"UPDATE ". DB_VIDEOS ." SET
															video_title='". serialize($video_title) ."',
															video_description='". serialize($video_description) ."',
															video_keywords='". serialize($video_keywords) ."',
															video_name='". serialize($video_name) ."',
															video_h1='". serialize($video_h1) ."',
															video_content='". serialize($video_content) ."',
															video_image='". $video_image ."',
															video_url='". $video_url ."',
															video_cat='". $video_cat ."',
															video_user='". $video_user ."',
															video_access='". $video_access ."',
															video_status='". $video_status ."',
															video_date='". $video_date ."',
															video_comments='". $video_comments ."',
															video_ratings='". $video_ratings ."',
															video_views='". $video_views ."'
						WHERE video_id='". (INT)$_GET['id'] ."'"
					);
					$video_id = (INT)$_GET['id'];

				} else {

					$result = dbquery(
						"INSERT INTO ". DB_VIDEOS ." (
															video_title,
															video_description,
															video_keywords,
															video_name,
															video_h1,
															video_content,
															video_image,
															video_url,
															video_cat,
															video_user,
															video_access,
															video_status,
															video_date,
															video_comments,
															video_ratings,
															video_views
						) VALUES (
															'". serialize($video_title) ."',
															'". serialize($video_description) ."',
															'". serialize($video_keywords) ."',
															'". serialize($video_name) ."',
															'". serialize($video_h1) ."',
															'". serialize($video_content) ."',
															'". $video_image ."',
															'". $video_url ."',
															'". $video_cat ."',
															'". $video_user ."',
															'". $video_access ."',
															'". $video_status ."',
															'". $video_date ."',
															'". $video_comments ."',
															'". $video_ratings ."',
															'". $video_views ."'
						)"
					);
					$video_id = mysql_insert_id();

				} // UPDATE ILI INSERT


				$viewcompanent = viewcompanent("videos", "name");
				$seourl_component = $viewcompanent['components_id'];

				// $video_alias = str_replace($settings['companent_root_url'], "", $video_alias);
				if (empty($video_alias)) {
					$video_alias = autocrateseourls($video_name[LOCALESHORT]);
				} else {
					$video_alias = autocrateseourls($video_alias);
				}

				$seourl_url = (empty($video_alias) ? "video_". $video_id . $settings['seourl_prefix'] : $video_alias . $settings['seourl_prefix']);
				$seourl_filedid = $video_id;

				$viewseourl = viewseourl($seourl_url, "url");

				if ($viewseourl['seourl_url']==$seourl_url) {
					if (($viewseourl['seourl_filedid']==$seourl_filedid) && ($viewseourl['seourl_component']==$seourl_component)) {
						$seourl_url = $seourl_url;
					} else {
						$seourl_url = "video_". $video_id . $settings['seourl_prefix'];
					}
				}  // Yesli URL YEst


				if ($video_cat!=0) {
					$seourl_url = $cat_url ."/". $seourl_url;
				} else {
					$seourl_url = $settings['companent_root_url'] . $seourl_url;
				}
				$video_alias = $seourl_url;


				if ($_GET['action']=="edit") {
					$result = dbquery(
						"UPDATE ". DB_SEOURL ." SET
															seourl_url='". $seourl_url ."',
															seourl_lastmod='". date("Y-m-d") ."'
						WHERE seourl_filedid='". $seourl_filedid ."' AND seourl_component='". $seourl_component ."'"
					);
				} else {
					$result = dbquery(
									"INSERT INTO ". DB_SEOURL ." (
																	seourl_url,
																	seourl_component,
																	seourl_filedid,
																	seourl_lastmod
										) VALUES (
																	'". $seourl_url ."',
																	'". $seourl_component ."',
																	'". $seourl_filedid ."',
																	'". date("Y-m-d") ."'
										)"
									);
				} // Yesli action edit 


				///////////////// POSITIONS /////////////////
				if ( $_GET['action']=="add" ) {
					$position=1;
					dbquery("UPDATE ". DB_VIDEOS ." SET video_order='". $position ."' WHERE video_id='". $video_id ."'");
					$result_position = dbquery("SELECT video_id FROM ". DB_VIDEOS ." WHERE video_id!='". $video_id ."' ORDER BY `video_order`");
					if (dbrows($result_position)) {
						while ($data_position = dbarray($result_position)) {
							$position++;
							dbquery("UPDATE ". DB_VIDEOS ." SET video_order='". $position ."' WHERE video_id='". $data_position['video_id'] ."'");
						} // db whille
					} // db query
				} // Yesli action add
				///////////////// POSITIONS /////////////////


				////////// redirect
				if ($_GET['action']=="edit") {
					redirect(FUSION_SELF . $aidlink ."&status=edit&id=". $video_id ."&url=". $video_alias, false);
				} else {
					redirect(FUSION_SELF . $aidlink ."&status=add&id=". $video_id ."&url=". $video_alias, false);
				} ////////// redirect

			} // Yesli Error

		} // Yesli POST save


		$result_cats = dbquery(
							"SELECT
												video_cat_id,
												video_cat_name
							FROM ". DB_VIDEO_CATS ."
							WHERE video_cat_parent=0
							ORDER BY video_cat_name DESC");
		$catlist = "<option value='0'". ($video_cat==0 ? " selected='selected'" : "") .">". $locale['510_a'] ."</option>\n";

		if (dbrows($result_cats)) {

			$result_subcats = dbquery(
								"SELECT
													video_cat_id,
													video_cat_name,
													video_cat_parent
								FROM ". DB_VIDEO_CATS ."
								WHERE video_cat_parent!=0
								ORDER BY video_cat_name DESC");
			$subcatlist_arr = array();
			if (dbrows($result_subcats)) {
				while ($data_subcats = dbarray($result_subcats)) {
					$subcatlist_video_name = unserialize($data_subcats['video_cat_name']);
					$subcatlist_arr[$data_subcats['video_cat_id']]['video_cat_name'] = $subcatlist_video_name[LOCALESHORT];
					$subcatlist_arr[$data_subcats['video_cat_id']]['video_cat_parent'] = $data_subcats['video_cat_parent'];
				}
			}
			// echo "<pre>";
			// print_r($subcatlist_arr);
			// echo "</pre>";
			// echo "<hr>";

			while ($data_cats = dbarray($result_cats)) {
				$catlist_video_cat_name = unserialize($data_cats['video_cat_name']);

				$avaycatlist_arr = array();
				foreach ($subcatlist_arr as $subcatlist_key => $subcatlist_value) {
					if ($data_cats['video_cat_id']==$subcatlist_value['video_cat_parent']) {
						$avaycatlist_arr[$subcatlist_key] = $subcatlist_value['video_cat_name'];
					}
				}

				if ($avaycatlist_arr) {
					$catlist .= "<optgroup label='". $catlist_video_cat_name[LOCALESHORT] ."'>\n";
						foreach ($avaycatlist_arr as $avaycatlist_key => $avaycatlist_value) {
							$catlist .= "	<option value='". $avaycatlist_key ."'". ($video_cat==$avaycatlist_key ? " selected='selected'" : "") .">". $avaycatlist_value ."</option>\n";
						}
					$catlist .= "</optgroup>\n";
				} else {
					$catlist .= "<option value='". $data_cats['video_cat_id'] ."'". ($video_cat==$data_cats['video_cat_id'] ? " selected='selected'" : "") .">". $catlist_video_cat_name[LOCALESHORT] ."</option>\n";
				}

			} // db whille
		} // db query





		$user_groups = getusergroups();
		$access_opts = "";
		$sel = "";
		while (list($key, $user_group) = each($user_groups)) {
			$sel = ($cat_access == $user_group['0'] ? " selected='selected'" : "");
			$access_opts .= "<option value='". $user_group['0'] ."'$sel>". $user_group['1'] ."</option>\n";
		} // user_groups while


		$result_user = dbquery("SELECT user_id, user_name FROM ". DB_USERS ." ORDER BY user_name DESC");
		while ($data_user = dbarray($result_user)) {
			$users_opts .= "<option value='". $data_user['user_id'] ."'". ($video_user==$data_user['user_id'] ? " selected='selected'" : "") .">". $data_user['user_name'] ."</option>\n";
		} // while user



		$ratings_opts = "<option value='0'". ($video_ratings==0 ? " selected='selected'" : "") .">". $locale['517_a'] ."</option>\n";
		for ($ratings_i=1; $ratings_i <= 5; $ratings_i++) { 
			$ratings_opts .= "<option value='". $ratings_i ."'". ($video_ratings==$ratings_i ? " selected='selected'" : "") .">". $ratings_i . $locale['517_b'] ."</option>\n";
		}


		if ($video_url) { $video_url = "http://www.youtube.com/watch?v=". $video_url; }

?>

	<form name='inputform' method='POST' action='<?php echo FUSION_SELF . $aidlink; ?>&action=<?php echo $_GET['action'];?><?php echo (isset($_GET['id']) && isnum($_GET['id']) ? "&id=". (INT)$_GET['id'] : ""); ?>' enctype='multipart/form-data'>
		<input type="hidden" name="video_status" id="video_status" value="<?php echo $video_status; ?>" />
		<input type="hidden" name="video_order" id="video_order" value="<?php echo $video_order; ?>" />
		<table class='form_table'>
			<tr>
				<td colspan="2"><a href="#" id="seo_tr_button">SEO</a></td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2" class="seo_tr">
					<label for="video_title_<?php echo LOCALESHORT; ?>"><?php echo $locale['501']; ?></label>
					<?php foreach ($languages as $key => $value) { ?>
					<?php if ($languages_count>1) { ?><span class="local_name lang_<?php echo $value['languages_short']; ?>"><?php echo $value['languages_name']; ?></span><?php } ?>
					<input type="text" name="video_title[<?php echo $value['languages_short']; ?>]" id="video_title_<?php echo $value['languages_short']; ?>" value="<?php echo $video_title[$value['languages_short']]; ?>" class="textbox" style="width:98%;" /><br />
					<?php } // foreach languages ?>
				</td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2" class="seo_tr">
					<label for="video_description_<?php echo LOCALESHORT; ?>"><?php echo $locale['502']; ?></label>
					<?php foreach ($languages as $key => $value) { ?>
					<?php if ($languages_count>1) { ?><span class="local_name lang_<?php echo $value['languages_short']; ?>"><?php echo $value['languages_name']; ?></span><?php } ?>
					<input type="text" name="video_description[<?php echo $value['languages_short']; ?>]" id="video_description_<?php echo $value['languages_short']; ?>" value="<?php echo $video_description[$value['languages_short']]; ?>" class="textbox" style="width:98%;" /><br />
					<?php } // foreach languages ?>
				</td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2" class="seo_tr">
					<label for="video_keywords_<?php echo LOCALESHORT; ?>"><?php echo $locale['503']; ?></label>
					<?php foreach ($languages as $key => $value) { ?>
					<?php if ($languages_count>1) { ?><span class="local_name lang_<?php echo $value['languages_short']; ?>"><?php echo $value['languages_name']; ?></span><?php } ?>
					<input type="text" name="video_keywords[<?php echo $value['languages_short']; ?>]" id="video_keywords_<?php echo $value['languages_short']; ?>" value="<?php echo $video_keywords[$value['languages_short']]; ?>" class="textbox" style="width:98%;" /><br />
					<?php } // foreach languages ?>
				</td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2" class="seo_tr">
					<label for="video_h1_<?php echo LOCALESHORT; ?>"><?php echo $locale['505']; ?></label>
					<?php foreach ($languages as $key => $value) { ?>
					<?php if ($languages_count>1) { ?><span class="local_name lang_<?php echo $value['languages_short']; ?>"><?php echo $value['languages_name']; ?></span><?php } ?>
					<input type="text" name="video_h1[<?php echo $value['languages_short']; ?>]" id="video_h1_<?php echo $value['languages_short']; ?>" value="<?php echo $video_h1[$value['languages_short']]; ?>" class="textbox" style="width:98%;" /><br />
					<?php } // foreach languages ?>
				</td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2">
					<label for="video_alias"><?php echo $locale['506']; ?></label>
					<input readonly type="text" name="video_siteurl" id="video_siteurl" value="<?php echo $settings['siteurl'] . ($cat_url ? $cat_url ."/" : $settings['companent_root_url']); ?>" class="textbox" style="width:25%;" />
					<input type="text" name="video_alias" id="video_alias" value="<?php echo $video_alias; ?>" class="textbox" style="width:65%;" />
					<?php if ($settings['seourl_prefix']) { ?><input readonly type="text" name="seourl_prefix" id="seourl_prefix" value="<?php echo $settings['seourl_prefix']; ?>" class="textbox" style="width:5%;" /><?php } ?>
				</td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2"></td>
			</tr>


			<tr>
				<td colspan="2">
					<label for="video_name_<?php echo LOCALESHORT; ?>"><?php echo $locale['504']; ?> <span>*</span></label>
					<?php foreach ($languages as $key => $value) { ?>
					<?php if ($languages_count>1) { ?><span class="local_name lang_<?php echo $value['languages_short']; ?>"><?php echo $value['languages_name']; ?></span><?php } ?>
					<input type="text" name="video_name[<?php echo $value['languages_short']; ?>]" id="video_name_<?php echo $value['languages_short']; ?>" value="<?php echo $video_name[$value['languages_short']]; ?>" class="textbox" style="width:98%;" /><br />
					<?php } // foreach languages ?>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<label for="video_image"><?php echo $locale['507']; ?></label>
					<?php if ($video_image && file_exists(IMAGES_V_T . $video_image)) { ?>
					<label>
						<img src="<?php echo IMAGES_V_T . $video_image; ?>" alt="" style="height:100px;" /><br />
						<input type="checkbox" name="video_image_del" value="1" /> <?php echo $locale['507_b']; ?>
						<input type="hidden" name="video_image_yest" value="<?php echo $video_image; ?>" />
					</label>
					<?php } else { ?>
					<input type="file" name="video_image" id="video_image" class="filebox" style="width:98%;" accept="image/*" />
					<div id="video_image_preview"></div>
					<?php echo sprintf($locale['507_a'], parsebytesize($settings['videos_photo_max_b'], 3)); ?>
					<?php }	?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<label for="video_url"><?php echo $locale['508']; ?> <span>*</span></label>
					<input type="text" name="video_url" id="video_url" value="<?php echo $video_url; ?>" class="textbox" style="width:98%;" />
					<?php echo $locale['508_a']; ?>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<label for="video_content_<?php echo LOCALESHORT; ?>"><?php echo $locale['509']; ?></label>
					<?php foreach ($languages as $key => $value) { ?>
					<?php if ($languages_count>1) { ?><span class="local_name lang_<?php echo $value['languages_short']; ?>"><?php echo $value['languages_name']; ?></span><?php } ?>
					<textarea id="editor<?php echo $value['languages_id']; ?>" name="video_content[<?php echo $value['languages_short']; ?>]" id="video_content<?php echo $value['languages_short']; ?>" class="textareabox" cols="95" rows="15" style="width:100%"><?php echo $video_content[$value['languages_short']]; ?></textarea><br />
					<?php } // foreach languages ?>
				</td>
			</tr>
			<?php if (!$settings['tinymce_enabled']) { ?>
			<tr>
				<td colspan="2">
					<?php echo display_html("inputform", "video_content", true, true, true, IMAGES_N); ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td>
					<label for="video_access"><?php echo $locale['511']; ?></label>
					<select name="video_access" id="video_access" class="selectbox" style="width:25%;">
						<?php echo $access_opts; ?>
					</select>
				</td>
				<td>
					<label for="video_cat"><?php echo $locale['510']; ?> <span>*</span></label>
					<select name="video_cat" id="video_cat" class="selectbox" style="width:25%;">
						<?php echo $catlist; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<label for="video_user"><?php echo $locale['515']; ?></label>
					<select name="video_user" id="video_user" class="selectbox" style="width:25%;">
						<?php echo $users_opts; ?>
					</select>
				</td>
				<td>
					<label for="video_date"><?php echo $locale['516']; ?></label>
					<input type="text" name="video_date" id="video_date" value="<?php echo date("d.m.Y", $video_date); ?>" class="textbox" style="width:25%;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="video_ratings"><?php echo $locale['517']; ?></label>
					<select name="video_ratings" id="video_ratings" class="selectbox" style="width:25%;">
						<?php echo $ratings_opts; ?>
					</select>
				</td>
				<td>
					<label for="video_views"><?php echo $locale['518']; ?></label>
					<input type="text" name="video_views" id="video_views" value="<?php echo $video_views; ?>" class="textbox" style="width:25%;" />
				</td>
			</tr>

			<?php if ($settings['comments_enabled'] || $settings['ratings_enabled']) { ?>
			<tr>
				<td colspan="2">
					<?php if ($settings['comments_enabled']) { ?>
					<label><input type='checkbox' name='video_comments' value='1'<?php echo ($video_comments ? " checked='checked" : ""); ?> /> <?php echo $locale['510']; ?></label><br />
					<?php } ?>
					<?php if ($settings['ratings_enabled']) { ?>
					<label><input type='checkbox' name='video_ratings' value='1'<?php echo ($video_ratings ? " checked='checked" : ""); ?> /> <?php echo $locale['511']; ?></label><br />
					<?php } ?>
				</td>
			</tr>
			<?php } ?>

			<tr>
				<td colspan="2" class="form_buttons">
					<input type="submit" name="save" value="<?php echo $locale['520']; ?>" class="button" />
					<input type="button" name="cancel" value="<?php echo $locale['521']; ?>" class="button" onclick="location.href='<?php echo FUSION_SELF . $aidlink; ?>'" />
				</td>
			</tr>
		</table>
	</form>


		<script type='text/javascript'>
		<?php
		if ($settings['tinymce_enabled']) { 
			foreach ($languages as $key => $value) {
		?>
			var ckeditor<?php echo $value['languages_id']; ?> = CKEDITOR.replace('editor<?php echo $value['languages_id']; ?>');
			CKFinder.setupCKEditor( ckeditor<?php echo $value['languages_id']; ?>, '<?php echo INCLUDES; ?>jscripts/ckeditor/ckfinder/' );
		<?php
			} // foreach $languages
		} // Yesli Text Editor CKEDITOR
		?>
		</script>

		<?php

add_to_footer ("<script  type='text/javascript' src='". ADMINTHEME ."js/jquery.inputmask.js'></script>");
add_to_footer ("<script  type='text/javascript' src='". ADMINTHEME ."js/jquery.inputmask.date.js'></script>");
add_to_footer ("<script  type='text/javascript' src='". ADMINTHEME ."js/datepicker.js'></script>");
add_to_footer ("<script type='text/javascript'>
	<!--
	$(function() {
		$( '#video_date' ).inputmask( 'd.m.y' );
		$( '#video_date' ).datepicker({ dateFormat: 'dd.mm.yy' });
	});
	//-->
</script>");
add_to_head ("<link rel='stylesheet' href='". ADMINTHEME ."css/datepicker.css' type='text/css' media='screen' />");



	} else {

	if ($_GET['status']) {
		if ($_GET['status']=="add") {

			$message = "<div class='success'>". $locale['success_002'] ." ID: ". intval($_GET['id']) ."</div>\n";
			$message .= "<div class='success'>". $locale['success_001'] ."<a href='". $settings['siteurl'] . $_GET['url'] ."' target='_blank'>". $_GET['url'] ."</a></div>\n";

		} elseif ($_GET['status']=="edit") {

			$message = "<div class='success'>". $locale['success_003'] ." ID: ". intval($_GET['id']) ."</div>\n";
			$message .= "<div class='success'>". $locale['success_001'] ."<a href='". $settings['siteurl'] . $_GET['url'] ."' target='_blank'>". $settings['siteurl'] . $_GET['url'] ."</a></div>\n";

		} elseif ($_GET['status']=="del") {

			$message = "<div class='success'>". $locale['success_004'] ." ID: ". intval($_GET['id']) ."</div>\n";

		} elseif ($_GET['status']=="active") {

			$message = "<div class='success'>". $locale['success_005'] ." ID: ". intval($_GET['id']) ."</div>\n";

		} elseif ($_GET['status']=="deactive") {

			$message = "<div class='success'>". $locale['success_006'] ." ID: ". intval($_GET['id']) ."</div>\n";

		}

	} // status

	echo "	<div class='admin-message'>\n";
	if ($message) {
	echo "		<div id='close-message'>". $message ."</div>\n";
	} // message
	echo "	</div>\n";


add_to_head("<script type='text/javascript' src='". INCLUDES ."jquery/jquery-ui.js'></script>");
add_to_head("<script type='text/javascript'>
	<!--
	$(document).ready(function() {
		$('.spisok_stranic tbody').sortable({
			handle : '.handle',
			placeholder: 'state-highlight',
			connectWith: '.connected',
			scroll: true,
			axis: 'y',
			update: function () {
				var ul = $(this),
					order = ul.sortable('serialize'),
					i = 0;
				$('.admin-message').empty();
				$('.admin-message').load('". FUSION_SELF . $aidlink ."&action=order&'+ order);
				ul.find('.num').each(function(i) {
					$(this).text(i+1);
				});
				// ul.find('tr').removeClass('tbl2').removeClass('tbl1');
				// ul.find('tr:odd').addClass('tbl2');
				// ul.find('tr:even').addClass('tbl1');
				window.setTimeout('closeDiv();',2500);
			}
		});
	});
	//-->
</script>");
?>


<?php
	$viewcompanent = viewcompanent("videos", "name");
	$seourl_component = $viewcompanent['components_id'];

	$result = dbquery("SELECT 
								video_id,
								video_name,
								video_order,
								video_status,
								seourl_url
		FROM ". DB_VIDEOS ."
		LEFT JOIN ". DB_SEOURL ." ON seourl_filedid=video_id AND seourl_component=". $seourl_component ."
		WHERE ". groupaccess('video_access') ."
		ORDER BY video_order
		LIMIT ". (INT)$_GET['rowstart'] .", ". $settings['videos_per_page'] ."");

	echo "<a href='". FUSION_SELF . $aidlink ."&action=add' class='add_page'>". $locale['010'] ."</a><br />\n";
?>

	<table class="spisok_stranic">
		<thead>
			<tr>
				<td class="list"></td>
				<td class="name"><?php echo $locale['401']; ?></td>
				<td class="status"><?php echo $locale['402']; ?></td>
				<td class="num"><?php echo $locale['403']; ?></td>
				<td class="links"><?php echo $locale['404']; ?></td>
			</tr>
		</thead>
		<tbody class="connected ui-sortable">
	<?php
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$video_name = unserialize($data['video_name']);
	?>
			<tr id="listItem_<?php echo $data['video_id']; ?>">
				<td class="list"><img src="<?php echo IMAGES; ?>arrow.png" alt="<?php echo $locale['410']; ?>" class="handle" /></td>
				<td class="name"><a href="<?php echo FUSION_SELF . $aidlink; ?>&action=edit&id=<?php echo $data['video_id']; ?>" title="<?php echo $video_name[LOCALESHORT]; ?>"><?php echo $video_name[LOCALESHORT]; ?></a></td>
				<td class="status">
					<a href="<?php echo FUSION_SELF . $aidlink; ?>&action=status&id=<?php echo $data['video_id']; ?>&status=<?php echo $data['video_status']; ?>" title="<?php echo ($data['video_status'] ? $locale['411'] : $locale['412']); ?>"><img src="<?php echo IMAGES; ?>status/status_<?php echo $data['video_status']; ?>.png" alt="<?php echo ($data['video_id'] ? $locale['411'] : $locale['412']); ?>"></a>
				</td>
				<td class="num"><?php echo $data['video_order']; ?></td>
				<td class="links">
					<a href="<?php echo BASEDIR . $data['seourl_url']; ?>" target="_blank" title="<?php echo $locale['413']; ?>"><img src="<?php echo IMAGES; ?>view.png" alt="<?php echo $locale['413']; ?>"></a>
					<a href="<?php echo FUSION_SELF . $aidlink; ?>&action=edit&id=<?php echo $data['video_id']; ?>" title="<?php echo $locale['414']; ?>"><img src="<?php echo IMAGES; ?>edit.png" alt="<?php echo $locale['414']; ?>"></a>
					<a href="<?php echo FUSION_SELF . $aidlink; ?>&action=del&id=<?php echo $data['video_id']; ?>" title="<?php echo $locale['415']; ?>" onclick="return DeleteOk();"><img src="<?php echo IMAGES; ?>delete.png" alt="<?php echo $locale['415']; ?>"></a>
				</td>
			</tr>
	<?php
			} // db whille
		} else {
	?>
			<tr>
				<td colspan="5"><?php echo $locale['012']; ?></td>
			</tr>
	<?php
		} // db query
	?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5">
<?php 
	$rows = dbcount("(video_id)", DB_VIDEOS);
	if ($rows > $settings['videos_per_page']) { echo makepagenav((INT)$_GET['rowstart'], $settings['videos_per_page'], $rows, 3, ADMIN . FUSION_SELF . $aidlink ."&amp;") ."\n"; }
?>
				</td>
			</tr>
		</tfoot>
	</table>

	<script type='text/javascript'>
		function DeleteOk() {
			return confirm('<?php echo $locale['450']; ?>');
		}
	</script>

<?php
	} // action


	if ($_GET['action']!="order") {
		closetable();

		require_once THEMES."templates/footer.php";
	} // Yesli action ne order
?>