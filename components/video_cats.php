<?php

if (!defined("IN_FUSION")) { die("Access Denied"); }

include LOCALE.LOCALESET."video_cats.php";

$viewcompanent = viewcompanent("video_cats", "name");
$seourl_component = $viewcompanent['components_id'];

$c_result = dbquery("SELECT 
								video_cat_id,
								video_cat_title,
								video_cat_description,
								video_cat_keywords,
								video_cat_name,
								video_cat_h1,
								video_cat_access,
								video_cat_content,
								seourl_url
FROM ". DB_VIDEO_CATS ."
LEFT JOIN ". DB_SEOURL ." ON seourl_filedid=video_cat_id AND seourl_component=". $seourl_component ."
WHERE video_cat_id='". $filedid ."'
AND ". groupaccess('video_cat_access') ."
AND video_cat_status='1'
AND video_cat_date<'". FUSION_TODAY ."'");
if (dbrows($c_result)) {
	$c_data = dbarray($c_result);

	$video_cat_id = $c_data['video_cat_id'];
	$video_cat_title = unserialize($c_data['video_cat_title']);
	$video_cat_description = unserialize($c_data['video_cat_description']);
	$video_cat_keywords = unserialize($c_data['video_cat_keywords']);
	$video_cat_name = unserialize($c_data['video_cat_name']);
	$video_cat_h1 = unserialize($c_data['video_cat_h1']);
	$video_cat_access = $c_data['video_cat_access'];
	$video_cat_content = unserialize($c_data['video_cat_content']);
	$video_cat_seourl_url = $c_data['seourl_url'];

		set_title( ($video_cat_title[LOCALESHORT] ? $video_cat_title[LOCALESHORT] : $locale['title'] ." ". $video_cat_name[LOCALESHORT]) );
		if (!empty($video_cat_description[LOCALESHORT])) set_meta("description", $video_cat_description[LOCALESHORT]);
		if (!empty($video_cat_keywords[LOCALESHORT])) set_meta("keywords", $video_cat_keywords[LOCALESHORT]);
		// add_to_head ("<link rel='canonical' href='http://". FUSION_HOST ."/". ($settings['opening_page']!=$video_cat_seourl_url ? $video_cat_seourl_url : "") ."' />");
		// add_to_head ("<meta name='robots' content='index, follow' />");
		// add_to_head ("<meta name='author' content='IssoHost' />");

		// if (FUSION_URI!="/") {
		// echo "<div class='breadcrumb'>\n";
		// echo "	<ul>\n";
		// echo "		<li><a href='". BASEDIR ."'>". $locale['640'] ."</a></li>\n";
		// echo "		<li><span>". $video_cat_name[LOCALESHORT] ."</span></li>\n";
		// echo "	</ul>\n";
		// echo "</div>\n";
		// }


		opentable( ($video_cat_h1[LOCALESHORT] ? $video_cat_h1[LOCALESHORT] : $video_cat_name[LOCALESHORT]) );

			if (checkgroup($video_cat_access)) {



				$result_videocat = dbquery("SELECT 
												video_cat_id,
												video_cat_name,
												video_cat_image,
												video_cat_access,
												seourl_url
				FROM ". DB_VIDEO_CATS ."
				LEFT JOIN ". DB_SEOURL ." ON seourl_filedid=video_cat_id AND seourl_component=". $seourl_component ."
				WHERE video_cat_status='1'
				AND video_cat_parent='". $video_cat_id ."'");
				if (dbrows($result_videocat)) {
					echo "<div class='videocats_list'>\n";
					$videocat_say = 0;
					while ($data_videocat = dbarray($result_videocat)) { $videocat_say++;

						$videocat_id = $data_videocat['video_cat_id'];
						$videocat_name = unserialize($data_videocat['video_cat_name']);
						$videocat_image = $data_videocat['video_cat_image'];
						$videocat_access = $data_videocat['video_cat_access'];
						$videocat_url = $data_videocat['seourl_url'];

						if (checkgroup($videocat_access)) {

							echo "	<div class='videocats videocat'". $videocat_id ."'>\n" ;
							echo "		<a href='". BASEDIR . $videocat_url ."' class='videocat_name'>". $videocat_name[LOCALESHORT] ."</a>\n";
							echo "		<a href='". BASEDIR . $videocat_url ."' class='videocat_img'><img src='". ($videocat_image ? IMAGES_VC_T . $videocat_image : IMAGES ."imagenotfound.jpg") ."' alt='". $videocat_name[LOCALESHORT] ."'></a>\n";
							echo "	</div>\n";

						} // video_cat_access
					} // db while
					echo "	<div class='clear'></div>\n";
					echo "</div>\n";
				} // db query





if ($_GET['page']>0) { $pagesay = (INT)$_GET['page']; }
else { $pagesay = 1; }
$rowstvideo = $settings['videos_per_page']*($pagesay-1);

				$viewcompanent = viewcompanent("videos", "name");
				$seourl_component = $viewcompanent['components_id'];

				$result = dbquery("SELECT 
											video_id,
											video_name,
											video_image,
											video_content,
											video_url,
											video_user,
											video_access,
											video_date,
											video_ratings,
											video_views,
											seourl_url
					FROM ". DB_VIDEOS ."
					LEFT JOIN ". DB_SEOURL ." ON seourl_filedid=video_id AND seourl_component=". $seourl_component ."
					WHERE video_status='1'
					AND video_date<'". FUSION_TODAY ."'
					AND video_cat='". $filedid ."'
					". ($_GET['user']>0 ? " AND video_user=". (INT)$_GET['user'] : "") ."
					ORDER BY video_order
					LIMIT ". $rowstvideo .", ". $settings['videos_per_page'] ."");


				if (dbrows($result)) { $say = 0;

					$all_user_arr = array();
					$result_user = dbquery("SELECT user_id, user_name FROM ". DB_USERS);
					if (dbrows($result_user)) {
						while ($data_user = dbarray($result_user)) {
							$all_user_arr[$data_user['user_id']] = $data_user['user_name'];
						} // db while
					} // db query
?>
<div class="videos_list">
	<ul>
<?php
					while ($data = dbarray($result)) {

						$video_id = $data['video_id'];
						$video_name = unserialize($data['video_name']);
						$video_image = $data['video_image'];
						$video_url = $data['video_url'];
						$video_user = $data['video_user'];
						$video_access = $data['video_access'];
						$video_date = $data['video_date'];
						$video_ratings = $data['video_ratings'];
						$video_views = $data['video_views'];
						$seourl_url = $data['seourl_url'];

						if (checkgroup($video_access)) { $say++;

							foreach ($all_user_arr as $key_user => $value_user) {
								if ($video_user==$key_user) {
									$video_user_id = $key_user;
									$video_user_name = $value_user;
								}
							} // foreach all_user_arr

?>
	<li<?php echo ($say==4 ? " class='last'" : ""); ?>>
		<div class="videos_thumb">
			<a href="<?php echo BASEDIR . $seourl_url; ?>">
				<img src="<?php echo ($video_image ? IMAGES_V_T . $video_image : "https://i.ytimg.com/vi/". $video_url ."/mqdefault.jpg"); ?>" alt="<?php echo $video_name[LOCALESHORT]; ?>">
				<i class="fa fa-play"></i>
			</a>
		</div>
		<h4><a href="<?php echo BASEDIR . $seourl_url; ?>"><?php echo $video_name[LOCALESHORT]; ?></a></h4>
		<div class="videos_media"> 
			<span class="user"><a href="/videouser/user_<?php echo $video_user_id; ?>"><i class="fa fa-user"></i> <?php echo $video_user_name; ?></a></span>
			<span class="calendar"><i class="fa fa-calendar"></i><?php echo date("d.m.Y", $video_date); ?></span>
			<span class="views"><i class="fa fa-eye"></i><?php echo number_format($video_views, 0, '.', ' '); ?></span>
			<span class="raiting">
				<?php
					$video_ratings_o = 5-$video_ratings;
					echo str_repeat('<i class="fa fa-star"></i>', $video_ratings);
					echo str_repeat('<i class="fa fa-star-o"></i>', $video_ratings_o);
				?>
			</span>
		</div>
	</li>
<?php

	if ($say==4) {
		$say = 0;
		echo "<div class='clear'></div>\n";
	} 
						} // video_access
					} // db whille
?>
	</ul>
</div>
<div class="clear"></div>
<?php
	} else {
		echo "Нет ни одного видео ролика\n";
	} // db query

	echo navigation( (INT)$_GET['page'], $settings['videos_per_page'], "video_id", DB_VIDEOS, "video_status='1' AND video_date<'". FUSION_TODAY ."' AND video_cat='". $filedid ."' AND ". groupaccess('video_access') . ($_GET['user']>0 ? " AND video_user=". (INT)$_GET['user'] : "") ."");


				ob_start();
				eval("?>".htmlspecialchars_decode($video_cat_content[LOCALESHORT])."<?php ");
				$custompage = ob_get_contents();
				ob_end_flush();
				$custompage = preg_split("/<!?--\s*pagebreak\s*-->/i", $custompage);
				$pagecount = count($custompage);
				echo $custompage[$_GET['rowstvideo']];

			} else {
				echo "<div class='admin-message' style='text-align:center'><br /><img style='border:0px; vertical-align:middle;' src ='".BASEDIR."images/warn.png' alt=''/><br /> ".$locale['400']."<br /><a href='index.php' onclick='javascript:history.back();return false;'>".$locale['403']."</a>\n<br /><br /></div>\n";
			}

		closetable();
} else {
	include COMPONENTS ."404.php";
} // db query




?>