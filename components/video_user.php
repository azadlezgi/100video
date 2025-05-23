<?php

if (!defined("IN_FUSION")) { die("Access Denied"); }

include LOCALE.LOCALESET."video_user.php";



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
					AND video_user=". (INT)$_GET['user'] . "
					ORDER BY video_order
					LIMIT ". $rowstvideo .", ". $settings['videos_per_page'] ."");


if (dbrows($result)) { $say = 0;


	$result_user = dbquery("SELECT user_id, user_name FROM ". DB_USERS ." WHERE user_id=". (INT)$_GET['user'] ."");
	if (dbrows($result_user)) {
		$data_user = dbarray($result_user);
	} // db query


	$viewcompanent = viewcompanent("video_user", "name");
	$seourl_component = $viewcompanent['components_id'];
	$viewseourl = viewseourl($seourl_component, "component");
	$viewseourl = $viewseourl['seourl_url'];

	set_title( $locale['title'] ." ". $data_user['user_name'] );
	set_meta("description", $locale['description'] );
	set_meta("keywords", $locale['keywords'] );
	// add_to_head ("<link rel='canonical' href='http://". FUSION_HOST ."/". ($settings['opening_page']!=$viewseourl ? $viewseourl ."/user_". (INT)$_GET['user'] : "") ."' />");
	// add_to_head ("<meta name='robots' content='index, follow' />");
	// add_to_head ("<meta name='author' content='100Video' />");

	// if (FUSION_URI!="/") {
	// 	echo "<div class='breadcrumb'>\n";
	// 	echo "	<ul>\n";
	// 	echo "		<li><a href='". BASEDIR ."'>". $locale['640'] ."</a></li>\n";
	// 	echo "		<li><span>". $video_cat_name[LOCALESHORT] ."</span></li>\n";
	// 	echo "	</ul>\n";
	// 	echo "</div>\n";
	// }

	opentable( $locale['h1'] ." ". $data_user['user_name'] );

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
			<span class="views"><i class="fa fa-eye"></i><?php echo $video_views; ?></span>
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
		closetable();

} else {
	include COMPONENTS ."404.php";
} // db query

?>