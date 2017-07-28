<?php

	require_once "../includes/maincore.php";

	if (!checkrights("V") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

	include LOCALE . LOCALESET ."admin/videos.php";

    $settings['companent_root_url'] = "video/";

    if ( isset($_GET['action']) && $_GET['action']=="order") {

    } else {
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


	if (isset($_GET['action']) && $_GET['action']=="order") {

        if (isset($_GET['listItem']) && is_array($_GET['listItem'])) {
            foreach ($_GET['listItem'] as $position => $item) {
                if (isnum($position) && isnum($item)) {
                    dbquery("UPDATE ". DB_VIDEOS ." SET `order`='". ($position+1) ."' WHERE `id`='". $item ."'");
                }
            }

            header("Content-Type: text/html; charset=". $locale['charset'] ."\n");
            echo "<div id='close-message'>\n";
            echo "	<div class='success'>". $locale['success_007'] ."</div>\n";
            echo "</div>\n";

        }

	} else if (isset($_GET['action']) && $_GET['action']=="status") {

		$id = (INT)$_GET['id'];
		$status = (INT)$_GET['status'];
		$status = ($status ? 0 : 1);

		$result = dbquery("UPDATE ". DB_VIDEOS ." SET
														status='". $status ."'
		WHERE id='". $id ."'");

		redirect(FUSION_SELF . $aidlink."&status=". ($status ? "active" : "deactive") ."&id=". $id, false);

	} else if (isset($_GET['action']) && $_GET['action']=="del") {

		$result = dbquery("SELECT image FROM ". DB_VIDEOS ." WHERE id='". (INT)$_GET['id'] ."'");
		if (dbrows($result)) {
				$data = dbarray($result);
			if (!empty($data['image']) && file_exists(IMAGES_V . $data['image'])) { unlink(IMAGES_V . $data['image']); }
			if (!empty($data['image']) && file_exists(IMAGES_V_T . $data['image'])) { unlink(IMAGES_V_T . $data['image']); }
		} // Tesli Yest DB query

		$result = dbquery("DELETE FROM ". DB_VIDEOS ." WHERE id='". (INT)$_GET['id'] ."'");
		$result = dbquery("DELETE FROM ". DB_COMMENTS ." WHERE comment_item_id='". (INT)$_GET['id'] ."' and comment_type='V'");
		$result = dbquery("DELETE FROM ". DB_RATINGS ." WHERE rating_item_id='". (INT)$_GET['id'] ."' and rating_type='V'");


		///////////////// POSITIONS /////////////////
		$position=1;
		$result_position = dbquery("SELECT id FROM ". DB_VIDEOS ." ORDER BY `order`");
		if (dbrows($result_position)) {
			while ($data_position = dbarray($result_position)) {
				$position++;
				dbquery("UPDATE ". DB_VIDEOS ." SET order='". $position ."' WHERE id='". $data_position['id'] ."'");
			} // db whille
		} // db query
		///////////////// POSITIONS /////////////////


		redirect(FUSION_SELF . $aidlink ."&status=del&id=". (INT)$_GET['id']);

	} else if (isset($_GET['action']) && ($_GET['action']=="add" || $_GET['action']=="edit")) {

		if (isset($_POST['save'])) {

			$title = stripinput($_POST['title']);
			$description = stripinput($_POST['description']);
			$keywords = stripinput($_POST['keywords']);
			$name = stripinput($_POST['name']);
			$h1 = stripinput($_POST['h1']);
			$content = stripinput($_POST['content']);

			$image = $_FILES['image']['name'];
			$imagetmp  = $_FILES['image']['tmp_name'];
			$imagesize = $_FILES['image']['size'];
			$imagetype = $_FILES['image']['type'];

            $image_yest = (isset($_POST['image_yest']) ? stripinput($_POST['image_yest']) : "");
            $image_del = (isset($_POST['image_del']) ? (INT)$_POST['image_del'] : 0);


			$url = stripinput($_POST['url']);

            if ( preg_match_all("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $url, $url_matches) ) {
                $url = (is_array($url_matches) && !empty($url_matches[0][0]) ? $url_matches[0][0] : "");
            }

            if ( preg_match("/xhamster.com/", $url) ) {
                $url = explode("?video=", $url);
                if(isset($url[1])) {
                    $url = trim($url[1]);
                } else {
                    $url = explode("/movies/", $url[0]);
                    $url = explode("/", $url[1]);
                    $url = trim($url[0]);
                }
            } // if xhamster



			$cat = (INT)$_POST['cat'];
			$user = (INT)$_POST['user'];
			if ($user==$userdata['user_id']) {
				$result_user_rand = dbquery("SELECT user_id FROM ". DB_USERS ." ORDER BY RAND() LIMIT 1");
				$data_user_rand = dbarray($result_user_rand);
				$user = $data_user_rand['user_id'];
			}

			$access = (INT)$_POST['access'];
			$status = (INT)$_POST['status'];

            if (isset($_POST['date'])) {
                $date = stripinput($_POST['date']);
                $date = strtotime($date);
            } else {
                $date = FUSION_TODAY;
            }

            $comments = (isset($_POST['comments']) ? (INT)$_POST['comments'] : 0);
            $ratings = (isset($_POST['ratings']) ? (INT)$_POST['ratings'] : 0);
			if ($ratings<1) {
				$ratings = rand(1, 5);
			}
			$views = (INT)$_POST['views'];
			if ($views<1) {
				$views = rand(1, 10000);
			}

            $alias = autocrateseourls(($_POST['alias'] ? stripinput($_POST['alias']) : $name));

		} else if ($_GET['action']=="edit") {

			$result = dbquery(
				"SELECT 
											`id`,
											`title`,
											`description`,
											`keywords`,
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
											`alias`
				FROM ". DB_VIDEOS ."
				WHERE id='". (INT)$_GET['id'] ."' LIMIT 1"
			);
			if (dbrows($result)) {
				$data = dbarray($result);

				$title = $data['title'];
				$description = $data['description'];
				$keywords = $data['keywords'];
				$name = $data['name'];
				$h1 = $data['h1'];
				$content = $data['content'];
				$image = $data['image'];
				$url = $data['url'];
				$cat =  $data['cat'];
				$user =  $data['user'];
				$access = $data['access'];
				$status = $data['status'];
				// $order = $data['order'];
				$date = $data['date'];
				$comments = $data['comments'];
				$ratings = $data['ratings'];
				$views = $data['views'];

				$alias = $data['alias'];

			} else {
				redirect(FUSION_SELF . $aidlink);
			}

            unset($result);
            unset($data);

		} else {

				$title = "";
				$description = "";
				$keywords = "";
				$name = "";
				$h1 = "";
				$content = "";
				$image = "";
				$url = "";
				$cat = 0;
				$user = $userdata['user_id'];
				$access = 0;
				$status = 1;
				// $order = 0;
				$date = FUSION_TODAY;
				$comments = "";
				$ratings = 0;
				$views = 0;
				$alias = "";

		} // Yesli POST


//		########## SEO URL OPARATIONS ##########
//		if ($settings['seourl_prefix']) {
//			$seourl_prefix_strlen =  strlen($settings['seourl_prefix']);
//			$seourl_prefix_alias = substr($alias, -$seourl_prefix_strlen);
//			if ($seourl_prefix_alias==$settings['seourl_prefix']) {
//				$alias = substr($alias, 0, -$seourl_prefix_strlen);
//			}
//		} // yesli yest seourl_prefix
//
//		if ($cat!=0) {
////
//			foreach ($seourl as $seourl_key => $seourl_value) {
//				if ($cat==$seourl_value['seourl_filedid'] && $seourl_component==$seourl_value['seourl_component']) {
//					$cat_url = $seourl_value['seourl_url'];
//				}
//			}
//			if ($settings['seourl_prefix']) {
//				$seourl_prefix_strlen =  strlen($settings['seourl_prefix']);
//				$seourl_prefix_alias = substr($cat_url, -$seourl_prefix_strlen);
//				if ($seourl_prefix_alias==$settings['seourl_prefix']) {
//					$cat_url = substr($cat_url, 0, -$seourl_prefix_strlen);
//				}
//			} // yesli yest seourl_prefix
//
//			$alias = str_replace($cat_url ."/", "", $alias);
//		} else {
//			$alias = str_replace($settings['companent_root_url'], "", $alias);
//		}
//		########## //SEO URL OPARATIONS ##########



		if (isset($_POST['save'])) {

            $error = "";
            if (empty($name)) { $error .= "<div class='error'>". $locale['error_001'] ."</div>\n"; }
			if ($cat<1) { $error .= "<div class='error'>". $locale['error_002'] ."</div>\n"; }
			if (empty($url)) { $error .= "<div class='error'>". $locale['error_003'] ."</div>\n"; }
			// if ( ($url) && (!eregi("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $url)) ) { $error .= "<div class='error'>". $locale['error_004'] ."</div>\n"; }

            if ( $_GET['action']=="edit" ) {
                $where_error = " AND `id`!=". (INT)$_GET['id'];
            } // if edit
            $result_alias = dbquery(
                "SELECT 
											`id`
				FROM " . DB_VIDEOS . "
				WHERE `alias`='" . $alias . "'
				". (isset($where_error) ? $where_error : "")
            );
            if (dbrows($result_alias)) {
                $error .= "<div class='error'>" . $locale['error_005'] . "</div>\n";
            }

            if (isset($image) && !empty($image)) {
				// if (strlen($image) > 255) { $error .= "<div class='error'>". $locale['error_050'] ."</div>\n"; $image = ""; }
				// проверяем расширение файла
				$image_ext = strtolower(substr($image, 1 + strrpos($image, ".")));
				if (!in_array($image_ext, $photo_valid_types)) { $error .= "<div class='error'>". $locale['error_051'] ."</div>\n"; $image = ""; }
				// 1. считаем кол-во точек в выражении - если большей одной - СВОБОДЕН!
				$image_findtochka = substr_count($image, ".");
				if ($image_findtochka>1) { $error .= "<div class='error'>". $locale['error_052'] ."</div>\n"; $image = ""; }
				// 2. если в имени есть .php, .html, .htm - свободен!
				if (preg_match("/\.php/i",$image))  { $error .= "<div class='error'>". $locale['error_053'] ."</div>\n"; $image = ""; }
				if (preg_match("/\.html/i",$image)) { $error .= "<div class='error'>". $locale['error_054'] ."</div>\n"; $image = ""; }
				if (preg_match("/\.htm/i",$image))  { $error .= "<div class='error'>". $locale['error_055'] ."</div>\n"; $image = ""; }
				// 5. Размер фото
				$image_fotosize = round($imagesize/10.24)/100; // размер ЗАГРУЖАЕМОГО ФОТО в Кб.
				$image_fotomax = round($settings['videos_photo_max_b']/10.24)/100; // максимальный размер фото в Кб.
				if ($image_fotosize>$image_fotomax) { $error .= "<div class='error'>". $locale['error_056'] ."<br />". $locale['error_057'] ." ". $image_fotosize ." Kb<br />". $locale['error_058'] ." ". $image_fotomax ." Kb</div>\n"; $image = ""; }
				// // 6. "Габариты" фото > $maxwidth х $maxheight - ДО свиданья! :-)
				$image_getsize = getimagesize($imagetmp);
				if ($image_getsize[0]>$settings['videos_photo_max_w'] or $image_getsize[1]>$settings['videos_photo_max_h']) { $error .= "<div class='error'>". $locale['error_059'] ."<br />". $locale['error_060'] ." ". $image_getsize[0] ."x". $image_getsize[1] ."<br />". $locale['error_061'] ." ". $settings['videos_photo_max_w'] ."x". $settings['videos_photo_max_h'] ."</div>\n"; $image = ""; }
				// // if ($image_getsize[0]<$image_getsize[1]) { $error .= "<div class='error'>". $locale['error_062'] ."</div>\n"; $image = ""; }
				// // Foto 0 Kb
				// if ($imagesize<0 and $imagesize>$settings['size']) { $error .= "<div class='error'>". $locale['error_063'] ."</div>\n"; $image = ""; }
			}


			if (isset($error) && !empty($error) ) {

				echo "	<div class='admin-message'>\n";
				echo "		<div id='close-message'>". $error ."</div>\n";
				echo "	</div>\n";

			} else {


                if (isset($image) && !empty($image)) {

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

					$image = image_exists(IMAGES_V, $image_new_name . $image_ext);

					move_uploaded_file($imagetmp, IMAGES_V . $image);
					// if (function_exists("chmod")) { chmod(IMAGES_V . $image, 0644); }

					$image_size = getimagesize(IMAGES_V . $image);
					$image_width = $image_size[0];
					$image_height = $image_size[1];

					if ($settings['videos_thumb_ratio']==0) {
						createthumbnail($image_filetype, IMAGES_V . $image, IMAGES_V_T . $image, ($image_width<$settings['videos_thumb_w'] ? $image_width : $settings['videos_thumb_w']), ($image_height<$settings['videos_thumb_h'] ? $image_height : $settings['videos_thumb_h']));
					} else {
						createsquarethumbnail($image_filetype, IMAGES_V . $image, IMAGES_V_T . $image, ($image_width<$settings['videos_thumb_w'] ? $image_width : $settings['videos_thumb_w']));
					}
                    createthumbnail($image_filetype, IMAGES_V . $image, IMAGES_V . $image, ($image_width<$settings['videos_thumb_w'] ? $settings['videos_thumb_w'] : ($image_width>$settings['videos_photo_w'] ? $settings['videos_photo_w'] : $image_width)));

				} else {
					$image = $image_yest;
				}



				if ($_GET['action']=="edit") {

					if ($image_del) {
						if ($image_yest && file_exists(IMAGES_V . $image_yest)) { unlink(IMAGES_V . $image_yest); }
						if ($image_yest && file_exists(IMAGES_V_T . $image_yest)) { unlink(IMAGES_V_T . $image_yest); }
						$image = "";
					}

					$result = dbquery(
						"UPDATE ". DB_VIDEOS ." SET
															title='". $title ."',
															description='". $description ."',
															keywords='". $keywords ."',
															name='". $name ."',
															h1='". $h1 ."',
															content='". $content ."',
															image='". $image ."',
															url='". $url ."',
															cat='". $cat ."',
															user='". $user ."',
															access='". $access ."',
															status='". $status ."',
															date='". $date ."',
															comments='". $comments ."',
															ratings='". $ratings ."',
															views='". $views ."',
															alias='". $alias ."'
						WHERE id='". (INT)$_GET['id'] ."'"
					);
					$id = (INT)$_GET['id'];

				} else {

					$result = dbquery(
						"INSERT INTO ". DB_VIDEOS ." (
															`title`,
															`description`,
															`keywords`,
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
															`alias`
						) VALUES (
															'". $title ."',
															'". $description ."',
															'". $keywords ."',
															'". $name ."',
															'". $h1 ."',
															'". $content ."',
															'". $image ."',
															'". $url ."',
															'". $cat ."',
															'". $user ."',
															'". $access ."',
															'". $status ."',
															'". $date ."',
															'". $comments ."',
															'". $ratings ."',
															'". $views ."',
															'". $alias ."'
						)"
					);
                    $id = _DB::$linkes->insert_id;

				} // UPDATE ILI INSERT




                ///////////////// POSITIONS /////////////////
                if ( $_GET['action']=="add" ) {
                    $position=1;
                    dbquery("UPDATE ". DB_VIDEOS ." SET `order`='". $position ."' WHERE `id`='". $id ."'");
                    $result_position = dbquery("SELECT `id` FROM ". DB_VIDEOS ." WHERE `id`!='". $id ."' ORDER BY `order`");
                    if (dbrows($result_position)) {
                        while ($data_position = dbarray($result_position)) {
                            $position++;
                            dbquery("UPDATE ". DB_VIDEOS ." SET `order`='". $position ."' WHERE `id`='". $data_position['id'] ."'");
                        } // db whille
                    } // db query
                } // Yesli action add
                ///////////////// POSITIONS /////////////////


				////////// redirect
				if ($_GET['action']=="edit") {
					redirect(FUSION_SELF . $aidlink ."&status=edit&id=". $id ."&url=". $alias, false);
				} else {
					redirect(FUSION_SELF . $aidlink ."&status=add&id=". $id ."&url=". $alias, false);
				} ////////// redirect

			} // Yesli Error

		} // Yesli POST save


		$result_cats = dbquery(
							"SELECT
												id,
												name
							FROM ". DB_VIDEO_CATS ."
							WHERE parent=0
							ORDER BY name DESC");
		$catlist = "<option value='0'". ($cat==0 ? " selected='selected'" : "") .">". $locale['510_a'] ."</option>\n";

		if (dbrows($result_cats)) {

			$result_subcats = dbquery(
								"SELECT
													id,
													name,
													parent
								FROM ". DB_VIDEO_CATS ."
								WHERE parent!=0
								ORDER BY name DESC");
			$subcatlist_arr = array();
			if (dbrows($result_subcats)) {
				while ($data_subcats = dbarray($result_subcats)) {
					$subcatlist_arr[$data_subcats['id']]['name'] = $data_subcats['name'];
					$subcatlist_arr[$data_subcats['id']]['parent'] = $data_subcats['parent'];
				}
			}
			// echo "<pre>";
			// print_r($subcatlist_arr);
			// echo "</pre>";
			// echo "<hr>";

			while ($data_cats = dbarray($result_cats)) {

				$avaycatlist_arr = array();
				foreach ($subcatlist_arr as $subcatlist_key => $subcatlist_value) {
					if ($data_cats['cat_id']==$subcatlist_value['parent']) {
						$avaycatlist_arr[$subcatlist_key] = $subcatlist_value['name'];
					}
				}

				if ($avaycatlist_arr) {
					$catlist .= "<optgroup label='". $data_cats['name'] ."'>\n";
						foreach ($avaycatlist_arr as $avaycatlist_key => $avaycatlist_value) {
							$catlist .= "	<option value='". $avaycatlist_key ."'". ($cat==$avaycatlist_key ? " selected='selected'" : "") .">". $avaycatlist_value ."</option>\n";
						}
					$catlist .= "</optgroup>\n";
				} else {
					$catlist .= "<option value='". $data_cats['id'] ."'". ($cat==$data_cats['id'] ? " selected='selected'" : "") .">". $data_cats['name'] ."</option>\n";
				}

			} // db whille
		} // db query





		$user_groups = getusergroups();
		$access_opts = "";
		$sel = "";
		while (list($key, $user_group) = each($user_groups)) {
			$sel = ($access == $user_group['0'] ? " selected='selected'" : "");
			$access_opts .= "<option value='". $user_group['0'] ."'$sel>". $user_group['1'] ."</option>\n";
		} // user_groups while


		$result_user = dbquery("SELECT user_id, user_name FROM ". DB_USERS ." ORDER BY user_name DESC");
        $users_opts = "";
		while ($data_user = dbarray($result_user)) {
			$users_opts .= "<option value='". $data_user['user_id'] ."'". ($user==$data_user['user_id'] ? " selected='selected'" : "") .">". $data_user['user_name'] ."</option>\n";
		} // while user



		$ratings_opts = "<option value='0'". ($ratings==0 ? " selected='selected'" : "") .">". $locale['517_a'] ."</option>\n";
		for ($ratings_i=1; $ratings_i <= 5; $ratings_i++) {
			$ratings_opts .= "<option value='". $ratings_i ."'". ($ratings==$ratings_i ? " selected='selected'" : "") .">". $ratings_i . $locale['517_b'] ."</option>\n";
		}


		if ($url) { $url = "http://www.youtube.com/watch?v=". $url; }

?>

	<form name='inputform' method='POST' action='<?php echo FUSION_SELF . $aidlink; ?>&action=<?php echo $_GET['action'];?><?php echo (isset($_GET['id']) && isnum($_GET['id']) ? "&id=". (INT)$_GET['id'] : ""); ?>' enctype='multipart/form-data'>
		<input type="hidden" name="status" id="status" value="<?php echo $status; ?>" />
		<input type="hidden" name="order" id="order" value="<?php echo $order; ?>" />
		<table class='form_table'>
			<tr>
				<td colspan="2"><a href="#" id="seo_tr_button">SEO</a></td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2" class="seo_tr">
					<label for="title"><?php echo $locale['501']; ?></label>
					<input type="text" name="title" id="title" value="<?php echo $title; ?>" class="textbox" />
				</td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2" class="seo_tr">
					<label for="description"><?php echo $locale['502']; ?></label>
					<input type="text" name="description" id="description" value="<?php echo $description; ?>" class="textbox" />
				</td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2" class="seo_tr">
					<label for="keywords"><?php echo $locale['503']; ?></label>
					<input type="text" name="keywords" id="keywords" value="<?php echo $keywords; ?>" class="textbox" />
				</td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2" class="seo_tr">
					<label for="h1"><?php echo $locale['505']; ?></label>
					<input type="text" name="h1" id="h1" value="<?php echo $h1; ?>" class="textbox" />
				</td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2">
					<label for="alias"><?php echo $locale['506']; ?></label>
					<input type="text" name="alias" id="alias" value="<?php echo $alias; ?>" class="textbox" />
				</td>
			</tr>
			<tr class="seo_tr">
				<td colspan="2"></td>
			</tr>


			<tr>
				<td colspan="2">
					<label for="name"><?php echo $locale['504']; ?> <span>*</span></label>
					<input type="text" name="name" id="name" value="<?php echo $name; ?>" class="textbox" />
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<label for="image"><?php echo $locale['507']; ?></label>
					<?php if ($image && file_exists(IMAGES_V_T . $image)) { ?>
					<label>
						<img src="<?php echo IMAGES_V_T . $image; ?>" alt="" style="height:100px;" /><br />
						<input type="checkbox" name="image_del" value="1" /> <?php echo $locale['507_b']; ?>
						<input type="hidden" name="image_yest" value="<?php echo $image; ?>" />
					</label>
					<?php } else { ?>
					<input type="file" name="image" id="image" class="filebox" style="width:98%;" accept="image/*" />
					<div id="image_preview"></div>
					<?php echo sprintf($locale['507_a'], parsebytesize($settings['videos_photo_max_b'], 3)); ?>
					<?php }	?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<label for="url"><?php echo $locale['508']; ?> <span>*</span></label>
					<input type="text" name="url" id="url" value="<?php echo $url; ?>" class="textbox" style="width:98%;" />
					<?php echo $locale['508_a']; ?>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<label for="content"><?php echo $locale['509']; ?></label>
					<textarea id="editor" name="content" id="content" class="textareabox" cols="95" rows="15" style="width:100%"><?php echo $content; ?></textarea>
				</td>
			</tr>
			<?php if (!$settings['tinymce_enabled']) { ?>
			<tr>
				<td colspan="2">
					<?php echo display_html("inputform", "content", true, true, true, IMAGES_N); ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td>
					<label for="access"><?php echo $locale['511']; ?></label>
					<select name="access" id="access" class="selectbox" style="width:25%;">
						<?php echo $access_opts; ?>
					</select>
				</td>
				<td>
					<label for="cat"><?php echo $locale['510']; ?> <span>*</span></label>
					<select name="cat" id="cat" class="selectbox" style="width:25%;">
						<?php echo $catlist; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<label for="user"><?php echo $locale['515']; ?></label>
					<select name="user" id="user" class="selectbox" style="width:25%;">
						<?php echo $users_opts; ?>
					</select>
				</td>
				<td>
					<label for="date"><?php echo $locale['516']; ?></label>
					<input type="text" name="date" id="date" value="<?php echo date("d.m.Y", $date); ?>" class="textbox" style="width:25%;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="ratings"><?php echo $locale['517']; ?></label>
					<select name="ratings" id="ratings" class="selectbox" style="width:25%;">
						<?php echo $ratings_opts; ?>
					</select>
				</td>
				<td>
					<label for="views"><?php echo $locale['518']; ?></label>
					<input type="text" name="views" id="views" value="<?php echo $views; ?>" class="textbox" style="width:25%;" />
				</td>
			</tr>

			<?php if ($settings['comments_enabled'] || $settings['ratings_enabled']) { ?>
			<tr>
				<td colspan="2">
					<?php if ($settings['comments_enabled']) { ?>
					<label><input type='checkbox' name='comments' value='1'<?php echo ($comments ? " checked='checked" : ""); ?> /> <?php echo $locale['510']; ?></label><br />
					<?php } ?>
					<?php if ($settings['ratings_enabled']) { ?>
					<label><input type='checkbox' name='ratings' value='1'<?php echo ($ratings ? " checked='checked" : ""); ?> /> <?php echo $locale['511']; ?></label><br />
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
		$( '#date' ).inputmask( 'd.m.y' );
		$( '#date' ).datepicker({ dateFormat: 'dd.mm.yy' });
	});
	//-->
</script>");
add_to_head ("<link rel='stylesheet' href='". ADMINTHEME ."css/datepicker.css' type='text/css' media='screen' />");



	} else {

	if (isset($_GET['status'])) {
		if (isset($_GET['status']) && $_GET['status']=="add") {

			$message = "<div class='success'>". $locale['success_002'] ." ID: ". intval($_GET['id']) ."</div>\n";
			$message .= "<div class='success'>". $locale['success_001'] ."<a href='". $settings['siteurl'] . $_GET['url'] ."' target='_blank'>". $_GET['url'] ."</a></div>\n";

		} elseif (isset($_GET['status']) && $_GET['status']=="edit") {

			$message = "<div class='success'>". $locale['success_003'] ." ID: ". intval($_GET['id']) ."</div>\n";
			$message .= "<div class='success'>". $locale['success_001'] ."<a href='". $settings['siteurl'] . $_GET['url'] ."' target='_blank'>". $settings['siteurl'] . $_GET['url'] ."</a></div>\n";

		} elseif (isset($_GET['status']) && $_GET['status']=="del") {

			$message = "<div class='success'>". $locale['success_004'] ." ID: ". intval($_GET['id']) ."</div>\n";

		} elseif (isset($_GET['status']) && $_GET['status']=="active") {

			$message = "<div class='success'>". $locale['success_005'] ." ID: ". intval($_GET['id']) ."</div>\n";

		} elseif (isset($_GET['status']) && $_GET['status']=="deactive") {

			$message = "<div class='success'>". $locale['success_006'] ." ID: ". intval($_GET['id']) ."</div>\n";

		}

	} // status

	echo "	<div class='admin-message'>\n";
	if (isset($message)) {
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
//	$viewcompanent = viewcompanent("videos", "name");
//	$seourl_component = $viewcompanent['components_id'];

	$result = dbquery("SELECT 
								v.id,
								v.name,
								v.order,
								v.status,
								v.alias as v_alias,
								vc.alias as vc_alias
		FROM ". DB_VIDEOS ."  v
		LEFT JOIN ". DB_VIDEO_CATS ." vc ON vc.id=v.cat
		WHERE ". groupaccess('v.access') ."
		ORDER BY v.order
		LIMIT ". (isset($_GET['rowstart']) ? (INT)$_GET['rowstart'] : 0) .", ". $settings['videos_per_page']);

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
	?>
			<tr id="listItem_<?php echo $data['id']; ?>">
				<td class="list"><img src="<?php echo IMAGES; ?>arrow.png" alt="<?php echo $locale['410']; ?>" class="handle" /></td>
				<td class="name"><a href="<?php echo FUSION_SELF . $aidlink; ?>&action=edit&id=<?php echo $data['id']; ?>" title="<?php echo $data['name']; ?>"><?php echo $data['name']; ?></a></td>
				<td class="status">
					<a href="<?php echo FUSION_SELF . $aidlink; ?>&action=status&id=<?php echo $data['id']; ?>&status=<?php echo $data['status']; ?>" title="<?php echo ($data['status'] ? $locale['411'] : $locale['412']); ?>"><img src="<?php echo IMAGES; ?>status/status_<?php echo $data['status']; ?>.png" alt="<?php echo ($data['id'] ? $locale['411'] : $locale['412']); ?>"></a>
				</td>
				<td class="num"><?php echo $data['order']; ?></td>
				<td class="links">
					<a href="/videos/<?php echo $data['vc_alias'] ."/". $data['v_alias']; ?>" target="_blank" title="<?php echo $locale['413']; ?>"><img src="<?php echo IMAGES; ?>view.png" alt="<?php echo $locale['413']; ?>"></a>
					<a href="<?php echo FUSION_SELF . $aidlink; ?>&action=edit&id=<?php echo $data['id']; ?>" title="<?php echo $locale['414']; ?>"><img src="<?php echo IMAGES; ?>edit.png" alt="<?php echo $locale['414']; ?>"></a>
					<a href="<?php echo FUSION_SELF . $aidlink; ?>&action=del&id=<?php echo $data['id']; ?>" title="<?php echo $locale['415']; ?>" onclick="return DeleteOk();"><img src="<?php echo IMAGES; ?>delete.png" alt="<?php echo $locale['415']; ?>"></a>
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

    unset($result);
    unset($data);
	?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5">
<?php
	$rows = dbcount("(id)", DB_VIDEOS);
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


    if ( isset($_GET['action']) && $_GET['action']=="order") {

    } else {
		closetable();

		require_once THEMES."templates/footer.php";
	} // Yesli action ne order
?>