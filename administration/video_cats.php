<?php

	require_once "../includes/maincore.php";

	if (!checkrights("VC") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

	include LOCALE . LOCALESET ."admin/video_cats.php";


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
					dbquery("UPDATE ". DB_VIDEO_CATS ." SET `order`='". ($position+1) ."' WHERE `id`='". $item ."'");
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

		$result = dbquery("UPDATE ". DB_VIDEO_CATS ." SET
														status='". $status ."'
		WHERE id='". $id ."'");

		redirect(FUSION_SELF . $aidlink."&status=". ($status ? "active" : "deactive") ."&id=". $id, false);

	} else if (isset($_GET['action']) && $_GET['action']=="del") {

		$videos_count = dbcount("(id)", DB_VIDEO_CATS, "video_cat='". (INT)$_GET['id'] ."'");
		$video_cats_count = dbcount("(id)", DB_VIDEO_CATS, "parent='". (INT)$_GET['id'] ."'");
		if ($videos_count>0 || $video_cats_count>0) {

			redirect(FUSION_SELF . $aidlink ."&status=nodel&id=". (INT)$_GET['id']);

		} else {

			$result = dbquery("SELECT image FROM ". DB_VIDEO_CATS ." WHERE id='". (INT)$_GET['id'] ."'");
			if (dbrows($result)) {
				$data = dbarray($result);
				if (!empty($data['image']) && file_exists(IMAGES_VC . $data['image'])) { unlink(IMAGES_VC . $data['image']); }
				if (!empty($data['image']) && file_exists(IMAGES_VC_T . $data['image'])) { unlink(IMAGES_VC_T . $data['image']); }
			} // Tesli Yest DB query

			$result = dbquery("DELETE FROM ". DB_VIDEO_CATS ." WHERE id='". (INT)$_GET['id'] ."'");


			///////////////// POSITIONS /////////////////
			$position=1;
			$result_position = dbquery("SELECT id FROM ". DB_VIDEO_CATS ." ORDER BY `order`");
			if (dbrows($result_position)) {
				while ($data_position = dbarray($result_position)) {
					$position++;
					dbquery("UPDATE ". DB_VIDEO_CATS ." SET order='". $position ."' WHERE id='". $data_position['id'] ."'");
				} // db whille
			} // db query
			///////////////// POSITIONS /////////////////

			redirect(FUSION_SELF . $aidlink ."&status=del&id=". (INT)$_GET['id']);

		} // Yesli yest video

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

			$parent = stripinput($_POST['parent']);
			$access = (INT)$_POST['access'];
			$status = (INT)$_POST['status'];

            if (isset($_POST['date'])) {
                $date = stripinput($_POST['date']);
                $date = strtotime($date);
            } else {
                $date = FUSION_TODAY;
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
											`parent`,
											`access`,
											`status`,
											`date`,
											`alias`
				FROM ". DB_VIDEO_CATS ."
				WHERE `id`='". (INT)$_GET['id'] ."' LIMIT 1"
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
				$parent =  $data['parent'];
				$access = $data['access'];
				$status = $data['status'];
				$date = $data['date'];
				$alias = $data['alias'];

			} else {
				redirect(FUSION_SELF . $aidlink);
			}

		} else {

				$title = "";
				$description = "";
				$keywords = "";
				$name = "";
				$h1 = "";
				$content = "";
				$image = "";
				$parent = 0;
				$access = 0;
				$status = 1;
				$date = FUSION_TODAY;
				$alias = "";

		} // Yesli POST


		if (isset($_POST['save'])) {

            $error = "";

            if (empty($name)) { $error .= "<div class='error'>". $locale['error_001'] ."</div>\n"; }

            if ( $_GET['action']=="edit" ) {
                $where_error = " AND `id`!=". (INT)$_GET['id'];
            } // if edit
            $result_alias = dbquery(
                "SELECT 
											`id`
				FROM " . DB_VIDEO_CATS . "
				WHERE `alias`='" . $alias . "'
				". (isset($where_error) ? $where_error : "")
            );
            if (dbrows($result_alias)) {
                $error .= "<div class='error'>" . $locale['error_003'] . "</div>\n";
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
				$image_fotomax = round($settings['video_cats_photo_max_b']/10.24)/100; // максимальный размер фото в Кб.
				if ($image_fotosize>$image_fotomax) { $error .= "<div class='error'>". $locale['error_056'] ."<br />". $locale['error_057'] ." ". $image_fotosize ." Kb<br />". $locale['error_058'] ." ". $image_fotomax ." Kb</div>\n"; $image = ""; }
				// // 6. "Габариты" фото > $maxwidth х $maxheight - ДО свиданья! :-)
				$image_getsize = getimagesize($imagetmp);
				if ($image_getsize[0]>$settings['video_cats_photo_max_w'] or $image_getsize[1]>$settings['video_cats_photo_max_h']) { $error .= "<div class='error'>". $locale['error_059'] ."<br />". $locale['error_060'] ." ". $image_getsize[0] ."x". $image_getsize[1] ."<br />". $locale['error_061'] ." ". $settings['photo_max_w'] ."x". $settings['photo_max_h'] ."</div>\n"; $image = ""; }
				// // if ($image_getsize[0]<$image_getsize[1]) { $error .= "<div class='error'>". $locale['error_062'] ."</div>\n"; $image = ""; }
				// // Foto 0 Kb
				// if ($imagesize<0 and $imagesize>$settings['size']) { $error .= "<div class='error'>". $locale['error_063'] ."</div>\n"; $image = ""; }
			}


			if (isset($error) && !empty($error)) {

				echo "	<div class='admin-message'>\n";
				echo "		<div id='close-message'>". $error ."</div>\n";
				echo "	</div>\n";

				$video_image = "";

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

					$image = image_exists(IMAGES_VC, $image_new_name . $image_ext);

					move_uploaded_file($imagetmp, IMAGES_VC . $image);
					// if (function_exists("chmod")) { chmod(IMAGES_VC . $image, 0644); }

					$image_size = getimagesize(IMAGES_VC . $image);
					$image_width = $image_size[0];
					$image_height = $image_size[1];

					if ($settings['video_cats_thumb_ratio']==0) {
						createthumbnail($image_filetype, IMAGES_VC . $image, IMAGES_VC_T . $image, ($image_width<$settings['video_cats_thumb_w'] ? $image_width : $settings['video_cats_thumb_w']), ($image_height<$settings['video_cats_thumb_h'] ? $image_height : $settings['video_cats_thumb_h']));
					} else {
						createsquarethumbnail($image_filetype, IMAGES_VC . $image, IMAGES_VC_T . $image, ($image_width<$settings['video_cats_thumb_w'] ? $image_width : $settings['video_cats_thumb_w']));
					}
					createthumbnail($image_filetype, IMAGES_VC . $image, IMAGES_VC . $image, ($image_width<$settings['video_cats_thumb_w'] ? $settings['video_cats_thumb_w'] : ($image_width>$settings['video_cats_photo_w'] ? $settings['video_cats_photo_w'] : $image_width)));

				} else {
					$image = $image_yest;
				}



				if (isset($_GET['action']) && $_GET['action']=="edit") {

					if ($image_del) {
						if ($image_yest && file_exists(IMAGES_VC . $image_yest)) { unlink(IMAGES_VC . $image_yest); }
						if ($image_yest && file_exists(IMAGES_VC_T . $image_yest)) { unlink(IMAGES_VC_T . $image_yest); }
						$image = "";
					}

					$result = dbquery(
						"UPDATE ". DB_VIDEO_CATS ." SET
															title='". $title ."',
															description='". $description ."',
															keywords='". $keywords ."',
															name='". $name ."',
															h1='". $h1 ."',
															content='". $content ."',
															image='". $image ."',
															parent='". $parent ."',
															access='". $access ."',
															status='". $status ."',
															alias='". $alias ."'
						WHERE id='". (INT)$_GET['id'] ."'"
					);
					$id = (INT)$_GET['id'];

				} else {

					$result = dbquery(
						"INSERT INTO ". DB_VIDEO_CATS ." (
															`title`,
															`description`,
															`keywords`,
															`name`,
															`h1`,
															`content`,
															`image`,
															`parent`,
															`access`,
															`status`,
															`date`,
															`alias`
						) VALUES (
															'". $title ."',
															'". $description ."',
															'". $keywords ."',
															'". $name ."',
															'". $h1 ."',
															'". $content ."',
															'". $image ."',
															'". $parent ."',
															'". $access ."',
															'". $status ."',
															'". $date ."',
															'". $alias ."'
						)"
					);
					$id = _DB::$linkes->insert_id;

				} // UPDATE ILI INSERT




				///////////////// POSITIONS /////////////////
				if ( $_GET['action']=="add" ) {
					$position=1;
					dbquery("UPDATE ". DB_VIDEO_CATS ." SET `order`='". $position ."' WHERE `id`='". $id ."'");
					$result_position = dbquery("SELECT `id` FROM ". DB_VIDEO_CATS ." WHERE `id`!='". $id ."' ORDER BY `order`");
					if (dbrows($result_position)) {
						while ($data_position = dbarray($result_position)) {
							$position++;
							dbquery("UPDATE ". DB_VIDEO_CATS ." SET `order`='". $position ."' WHERE `id`='". $data_position['id'] ."'");
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


	$result_parent = dbquery(
							"SELECT
												id,
												name
							FROM ". DB_VIDEO_CATS ."
							WHERE parent=0
							ORDER BY name DESC");
	$parent_opts = "<option value='0'". ($parent==0 ? " selected='selected'" : "") .">". $locale['513'] ."</option>\n";
	while ($data_parent = dbarray($result_parent)) {
		$parent_opts .= "<option value='". $data_parent['id'] ."'". ($parent==$data_parent['id'] ? " selected='selected'" : "") .">". $data_parent['name'] ."</option>\n";
	}


	$user_groups = getusergroups();
	$access_opts = "";
	$sel = "";
	while (list($key, $user_group) = each($user_groups)) {
		$sel = ($access == $user_group['0'] ? " selected='selected'" : "");
		$access_opts .= "<option value='". $user_group['0'] ."'$sel>". $user_group['1'] ."</option>\n";
	}

//	echo "<a href='". FUSION_SELF.$aidlink ."' class='go_back'>". $locale['471'] ."</a><br />\n";
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
					<input type="text" name="keywords" id="keywords" value="<?php echo $keywords; ?>" class="textbox"  />
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
					<?php if ($image && file_exists(IMAGES_VC_T . $image)) { ?>
					<label>
						<img src="<?php echo IMAGES_VC_T . $image; ?>" alt="" style="height:100px;" /><br />
						<input type="checkbox" name="image_del" value="1" /> <?php echo $locale['507_b']; ?>
						<input type="hidden" name="image_yest" value="<?php echo $image; ?>" />
					</label>
					<?php } else { ?>
					<input type="file" name="image" id="image" class="filebox" style="width:100%;" accept="image/*" />
					<div id="video_image_preview"></div>
					<?php echo sprintf($locale['507_a'], parsebytesize($settings['photo_max_b'], 3)); ?>
					<?php }	?>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<label for="content"><?php echo $locale['509']; ?></label>
					<textarea id="editor" name="content" id="content" class="textareabox" cols="95" rows="15"><?php echo $content; ?></textarea>
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
					<select name="access" id="access" class="selectbox" style="width:200px;">
						<?php echo $access_opts; ?>
					</select>
				</td>
				<td>
					<label for="parent"><?php echo $locale['512']; ?></label>
					<select name="parent" id="access" class="selectbox" style="width:200px;">
						<?php echo $parent_opts; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="form_buttons">
					<input type="submit" name="save" value="<?php echo $locale['520']; ?>" class="button" />
					<input type="button" name="cancel" value="<?php echo $locale['521']; ?>" class="button" onclick="location.href='<?php echo FUSION_SELF . $aidlink; ?>'" />
				</td>
			</tr>
		</table>
	</form>


	<script language="javascript" type="text/javascript">
		<!--
		$(function () {
			$("#video_image").change(function () {
				if (typeof (FileReader) != "undefined") {
					var dvPreview = $("#video_image_preview");
					dvPreview.html("");
					// var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
					$($(this)[0].files).each(function () {
						var file = $(this);
						// if (regex.test(file[0].name.toLowerCase())) {
							var reader = new FileReader();
							reader.onload = function (e) {
								var img = $("<img />");
								img.attr("style", "height:100px");
								img.attr("src", e.target.result);
								// console.log( img );
								dvPreview.append(img);
							}
							reader.readAsDataURL(file[0]);
						// } else {
						//	 alert(file[0].name + " is not a valid image file.");
						//	 dvPreview.html("");
						//	 return false;
						// }
					});
				} else {
					alert("This browser does not support HTML5 FileReader.");
				}
			});
		});
		//-->
	</script>

	<script type='text/javascript'>
	<?php if ($settings['tinymce_enabled']==2) { ?>
		var ckeditor = CKEDITOR.replace('editor');
		CKFinder.setupCKEditor( ckeditor, '<?php echo INCLUDES; ?>jscripts/ckeditor/ckfinder/' );
	<?php } // Yesli Text Editor CKEDITOR ?>
	</script>

<?php

	} else {

	if (isset($_GET['status'])) {
		if ($_GET['status']=="add") {

			$message = "<div class='success'>". $locale['success_002'] ." ID: ". intval($_GET['id']) ."</div>\n";
			$message .= "<div class='success'>". $locale['success_001'] ."<a href='". $settings['siteurl'] . $_GET['url'] ."' target='_blank'>". $settings['siteurl'] . $_GET['url'] ."</a></div>\n";

		} elseif ($_GET['status']=="edit") {

			$message = "<div class='success'>". $locale['success_003'] ." ID: ". intval($_GET['id']) ."</div>\n";
			$message .= "<div class='success'>". $locale['success_001'] ."<a href='". $settings['siteurl'] . $_GET['url'] ."' target='_blank'>". $settings['siteurl'] . $_GET['url'] ."</a></div>\n";

		} elseif ($_GET['status']=="del") {

			$message = "<div class='success'>". $locale['success_004'] ." ID: ". intval($_GET['id']) ."</div>\n";

		} elseif ($_GET['status']=="nodel") {

			$message = "<div class='error'>". $locale['success_008'] ." ID: ". intval($_GET['id']) ."</div>\n";

		} elseif ($_GET['status']=="active") {

			$message = "<div class='success'>". $locale['success_005'] ." ID: ". intval($_GET['id']) ."</div>\n";

		} elseif ($_GET['status']=="deactive") {

			$message = "<div class='success'>". $locale['success_006'] ." ID: ". intval($_GET['id']) ."</div>\n";

		}

	} // status

	echo "	<div class='admin-message'>\n";
	if (isset($message)) {
	echo "		<div id='close-message'>". $message ."</div>\n";
	} // message
	echo "	</div>\n";


add_to_footer("<script type='text/javascript' src='". INCLUDES ."jquery/jquery-ui.js'></script>");
add_to_footer("<script type='text/javascript'>
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



    $result_all_arr = array();
	$result_all = dbquery("SELECT 
											parent
						FROM ". DB_VIDEO_CATS ."
						WHERE parent!=0");
	if (dbrows($result_all)) { $j_all = 0; $result_all_arr = array();
		while ($data_all = dbarray($result_all)) { $j_all++;
			if (!in_array($data_all['parent'], $result_all_arr)) {
				$result_all_arr[$j_all] = $data_all['parent'];
			}
		} // db while
	} // db query
	// echo "<pre>";
	// print_r($result_all_arr);
	// echo "</pre>";
	// echo "<hr>";


	if (isset($_GET['page'])) { $pagesay = (INT)$_GET['page']; }
	else { $pagesay = 1; }
	$rowstvideo = $settings['video_cats_per_page']*($pagesay-1);

//	$viewcompanent = viewcompanent("video_cats", "name");
//	$seourl_component = $viewcompanent['components_id'];


	$result_alter = dbquery("ALTER TABLE `". DB_VIDEO_CATS ."` ORDER BY `order` ASC");

	$result = dbquery("SELECT 
                                        `id`,
                                        `name`,
                                        `order`,
                                        `status`,
                                        `alias`
		FROM ". DB_VIDEO_CATS ."
		WHERE parent=0
		ORDER BY `order` ASC
		LIMIT ". $rowstvideo .", ". $settings['video_cats_per_page']);

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
				<td class="name">
					<?php if (in_array($data['id'], $result_all_arr)) { echo "<a href='#' id='views_parents". $data['id'] ."' class='views_parents' title='Покозать под категории'>+</a>\n"; } ?>
					<a href="<?php echo FUSION_SELF . $aidlink; ?>&action=edit&id=<?php echo $data['id']; ?>" title="<?php echo $data['name']; ?>"><?php echo $data['name']; ?></a>
				</td>
				<td class="status">
					<a href="<?php echo FUSION_SELF . $aidlink; ?>&action=status&id=<?php echo $data['id']; ?>&status=<?php echo $data['status']; ?>" title="<?php echo ($data['status'] ? $locale['411'] : $locale['412']); ?>"><img src="<?php echo IMAGES; ?>status/status_<?php echo $data['status']; ?>.png" alt="<?php echo ($data['status'] ? $locale['411'] : $locale['412']); ?>"></a>
				</td>
				<td class="num"><?php echo $data['order']; ?></td>
				<td class="links">
					<a href="/videos/<?php echo $data['alias']; ?>" target="_blank" title="<?php echo $locale['413']; ?>"><img src="<?php echo IMAGES; ?>view.png" alt="<?php echo $locale['413']; ?>"></a>
					<a href="<?php echo FUSION_SELF . $aidlink; ?>&action=edit&id=<?php echo $data['id']; ?>" title="<?php echo $locale['414']; ?>"><img src="<?php echo IMAGES; ?>edit.png" alt="<?php echo $locale['414']; ?>"></a>
					<a href="<?php echo FUSION_SELF . $aidlink; ?>&action=del&id=<?php echo $data['id']; ?>" title="<?php echo $locale['415']; ?>" onclick="return DeleteOk();"><img src="<?php echo IMAGES; ?>delete.png" alt="<?php echo $locale['415']; ?>"></a>
				</td>
			</tr>
	<?php
				if (is_array($result_all_arr) && in_array($data['id'], $result_all_arr)) {
	?>
		</tbody>
		<tbody id="parents_list_bottom<?php echo $data['id']; ?>" class="parents_list_bottom" style="display:none;">
			<tr>
				<td colspan="5"></td>
			</tr>
		</tbody>
		<tbody>
	<?php
				} // in_array

			} // db whille
	?>
	<script type="text/javascript">
		<!--
		$(document).ready(function() {
			$( '.spisok_stranic .views_parents' ).click(function() {
				var views_parents_id = $( this ).attr( 'id' );
				views_parents_id = views_parents_id.replace('views_parents', '');

				if ( $( this ).hasClass( "active_parents" ) ) {
					$( this ).removeClass( 'active_parents' );
					$( this ).text( '+' );
					$( '.spisok_stranic #parents_list_bottom'+ views_parents_id ).css( 'display', 'none' );

					html = '';
					html += '<tr>';
					html += '	<td colspan=\'5\'></td>';
					html += '</tr>';
					$( '.spisok_stranic #parents_list_bottom'+ views_parents_id +'' ).html( html );
				} else {
					$( this ).addClass( 'active_parents' );
					$( this ).text( '-' );
					$( '.spisok_stranic #parents_list_bottom'+ views_parents_id ).removeAttr( 'style' );

					$( '.spisok_stranic #parents_list_bottom'+ views_parents_id +' td' ).html('<img src=\'/<?php echo ADMINTHEME; ?>images/ajax-loader.gif\' alt=\'\' class=\'ajax-loader\' />');
					$.ajax({
						type: 'POST',
						url: '<?php echo INCLUDES; ?>Json/video_cats-parents.php',
						dataType: 'json',
						data: {parent: views_parents_id},
						success: function(data){
							var html = '';
							$.each(data,function(inx, item) {

								html += '<tr id=\'listItem_'+ item.id +'\'>';
								html += '	<td class=\'list\'><img src=\'<?php echo IMAGES; ?>arrow.png\' alt=\'<?php echo $locale['410']; ?>\' class=\'handle\' /></td>';
								html += '	<td class=\'name\'>';
								html += '		<a href=\'<?php echo FUSION_SELF . $aidlink; ?>&action=edit&id='+ item.id +'\' title=\''+ item.name +'\'>└ <i>'+ item.name +'</i></a>';
								html += '	</td>';
								html += '	<td class=\'status\'>';
								if (item.status>0) {
									html += '		<a href=\'<?php echo FUSION_SELF . $aidlink; ?>&action=status&id='+ item.id +'&status='+ item.status +'\' title=\'<?php echo $locale['411']; ?>\'><img src=\'<?php echo IMAGES; ?>status/status_'+ item.status +'.png\' alt=\'<?php echo $locale['411']; ?>\'></a>';
								} else {
									html += '		<a href=\'<?php echo FUSION_SELF . $aidlink; ?>&action=status&id='+ item.id +'&status='+ item.status +'\' title=\'<?php echo $locale['412']; ?>\'><img src=\'<?php echo IMAGES; ?>status/status_'+ item.status +'.png\' alt=\'<?php echo $locale['412']; ?>\'></a>';
								}
								html += '	</td>';
								html += '	<td class=\'num\'>'+ item.order +'</td>';
								html += '	<td class=\'links\'>';
								html += '		<a href=\'<?php echo BASEDIR; ?>'+ item.seourl_url +'\' target=\'_blank\' title=\'<?php echo $locale['413']; ?>\'><img src=\'<?php echo IMAGES; ?>view.png\' alt=\'<?php echo $locale['413']; ?>\'></a>';
								html += '		<a href=\'<?php echo FUSION_SELF . $aidlink; ?>&action=edit&id='+ item.id +'\' title=\'<?php echo $locale['414']; ?>\'><img src=\'<?php echo IMAGES; ?>edit.png\' alt=\'<?php echo $locale['414']; ?>\'></a>';
								html += '		<a href=\'<?php echo FUSION_SELF . $aidlink; ?>&action=del&id='+ item.id +'\' title=\'<?php echo $locale['415']; ?>\' onclick=\'return DeleteOk();\'><img src=\'<?php echo IMAGES; ?>delete.png\' alt=\'<?php echo $locale['415']; ?>\'></a>';
								html += '	</td>';
								html += '</tr>';

							});
							html += '<tr>';
							html += '	<td colspan=\'5\'></td>';
							html += '</tr>';

							$( '.spisok_stranic #parents_list_bottom'+ views_parents_id +'' ).html( html );
						}
					});

				}
				// console.log( views_parents_id );
			});
		});
		//-->
	</script>
	<?php
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
				<td colspan="5"></td>
			</tr>
		</tfoot>
	</table>

	<script type='text/javascript'>
		function DeleteOk() {
			return confirm('<?php echo $locale['450']; ?>');
		}
	</script>

<?php

		echo navigation( (isset($_GET['page']) ? (INT)$_GET['page'] : 0), $settings['video_cats_per_page'], "id", DB_VIDEO_CATS, "parent=0");

	} // action


	if ( isset($_GET['action']) && $_GET['action']=="order") {

    } else {
		closetable();

		require_once THEMES."templates/footer.php";
	} // Yesli action ne order
?>