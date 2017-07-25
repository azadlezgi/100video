<?php if (!defined("IN_FUSION")) { die("Access Denied"); }

if ($component_id==2) {

	$result_cat = dbquery("SELECT 
											video_cat
					FROM ". DB_VIDEOS ."
					WHERE video_status='1'
					AND video_date<'". FUSION_TODAY ."'
					AND video_id='". $filedid ."'");
	if (dbrows($result_cat)) {
		$data_cat = dbarray($result_cat);

		$video_menshe = dbcount("(video_id)", DB_VIDEOS, "video_status='1' AND video_date<'". FUSION_TODAY ."' AND video_cat='". $data_cat['video_cat'] ."' AND video_id<'". $filedid ."'");
		$video_bolshe = dbcount("(video_id)", DB_VIDEOS, "video_status='1' AND video_date<'". FUSION_TODAY ."' AND video_cat='". $data_cat['video_cat'] ."' AND video_id>'". $filedid ."'");
		// echo "video_cat: ". $data_cat['video_cat'] ."<br>\n";
		// echo "video_id: ". $filedid ."<br>\n";
		// echo "video_menshe: ". $video_menshe ."<br>\n";
		// echo "video_bolshe: ". $video_bolshe ."<br>\n";

		// echo "<pre>";
		// print_r($video_bolshe);
		// echo "</pre>";
		// echo "<hr>";



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
					AND video_cat='". $data_cat['video_cat'] ."'
					AND ". ($video_menshe>=10 ? "video_id<'". $filedid ."'" : "video_id>'". $filedid ."'") ."
					LIMIT 0, 10");

		if (dbrows($result)) {

			$all_user_arr = array();
			$result_user = dbquery("SELECT user_id, user_name FROM ". DB_USERS);
			if (dbrows($result_user)) {
				while ($data_user = dbarray($result_user)) {
					$all_user_arr[$data_user['user_id']] = $data_user['user_name'];
				} // db while
			} // db query
?>
<div class="related_videos">
	<?php openside("Похожее видео"); ?>
	<ul>
<?php


			while ($data = dbarray($result)) { $say++;

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

				if (checkgroup($video_access)) {

					foreach ($all_user_arr as $key_user => $value_user) {
						if ($video_user==$key_user) {
							$video_user_id = $key_user;
							$video_user_name = $value_user;
						}
					} // foreach all_user_arr
?>
		<li>
			<div class="videos_thumb">
				<a href="<?php echo BASEDIR . $seourl_url; ?>">
					<img src="<?php echo ($video_image ? IMAGES_V_T . $video_image : "https://i.ytimg.com/vi/". $video_url ."/mqdefault.jpg"); ?>" alt="<?php echo $video_name[LOCALESHORT]; ?>">
					<i class="fa fa-play"></i>
				</a>
			</div>
			<div class="videos_media"> 
				<h4><a href="<?php echo BASEDIR . $seourl_url; ?>"><?php echo $video_name[LOCALESHORT]; ?></a></h4>
				<span class="user"><a href="/videouser/user_<?php echo $video_user_id; ?>"><i class="fa fa-user"></i></a> <p><?php echo $video_user_name; ?></p></span>
				<span class="calendar"><i class="fa fa-calendar"></i> <p><?php echo date("d.m.Y", $video_date); ?></p></span>
				<span class="views"><i class="fa fa-eye"></i> <p><?php echo number_format($video_views, 0, '.', ''); ?></p></span>
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
				} // video_access
			} // db whille
?>
	</ul>
	<?php closeside(); ?>
</div>
<?php
		} // db query
	} // dbrows result_cat
} // component_id==2
?>