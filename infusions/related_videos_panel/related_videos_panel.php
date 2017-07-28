<?php if (!defined("IN_FUSION")) { die("Access Denied"); }

if ($component=="videos") {

	$result_cat_rel = dbquery("SELECT 
											`id`,
											`cat`
					FROM ". DB_VIDEOS ."
					WHERE status='1'
					AND date<'". FUSION_TODAY ."'
					AND alias='". $alias ."'");
	if (dbrows($result_cat_rel)) {
		$data_cat_rel = dbarray($result_cat_rel);

		$menshe = dbcount("(id)", DB_VIDEOS, "status='1' AND date<'". FUSION_TODAY ."' AND cat='". $data_cat_rel['cat'] ."' AND id<'". $data_cat_rel['id'] ."'");
		$bolshe = dbcount("(id)", DB_VIDEOS, "status='1' AND date<'". FUSION_TODAY ."' AND cat='". $data_cat_rel['cat'] ."' AND id>'". $data_cat_rel['id'] ."'");
		// echo "cat: ". $data_cat['cat'] ."<br>\n";
		// echo "id: ". $filedid ."<br>\n";
		// echo "menshe: ". $menshe ."<br>\n";
		// echo "bolshe: ". $bolshe ."<br>\n";

		// echo "<pre>";
		// print_r($bolshe);
		// echo "</pre>";
		// echo "<hr>";



		$result_rel = dbquery("SELECT 
											`id`,
											`name`,
											`image`,
											`content`,
											`url`,
											`user`,
											`access`,
											`date`,
											`ratings`,
											`views`,
											`alias`
					FROM ". DB_VIDEOS ."
					WHERE status='1'
					AND date<'". FUSION_TODAY ."'
					AND cat='". $data_cat_rel['cat'] ."'
					AND ". ($menshe>=10 ? "id<'". $data_cat_rel['id'] ."'" : "id>'". $data_cat_rel['id'] ."'") ."
					LIMIT 0, 10");

		if (dbrows($result_rel)) {

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

            $say=0;
			while ($data_rel = dbarray($result_rel)) { $say++;

				$id_rel = $data_rel['id'];
				$name_rel = $data_rel['name'];
				$image_rel = $data_rel['image'];
				$url_rel = $data_rel['url'];
				$user_rel = $data_rel['user'];
				$access_rel = $data_rel['access'];
				$date_rel = $data_rel['date'];
				$ratings_rel = $data_rel['ratings'];
				$views_rel = $data_rel['views'];
				$alias_rel = $data_rel['alias'];

				if (checkgroup($access_rel)) {

					foreach ($all_user_arr as $key_user => $value_user) {
						if ($user_rel==$key_user) {
							$user_id = $key_user;
							$user_name = $value_user;
						}
					} // foreach all_user_arr
?>
		<li>
			<div class="videos_thumb">
				<a href="/videos/<?php echo $fusion_uri_arr[2] ."/". $alias_rel; ?>">
					<img src="<?php echo ($image_rel ? IMAGES_V_T . $image_rel : "https://i.ytimg.com/vi/". $url_rel ."/mqdefault.jpg"); ?>" alt="<?php echo $name_rel; ?>">
					<i class="fa fa-play"></i>
				</a>
			</div>
			<div class="videos_media"> 
				<h4><a href="/videos/<?php echo $fusion_uri_arr[2] ."/". $alias_rel; ?>"><?php echo $name_rel; ?></a></h4>
				<span class="user"><a href="/videouser/user_<?php echo $user_id; ?>"><i class="fa fa-user"></i></a> <p><?php echo $user_name; ?></p></span>
				<span class="calendar"><i class="fa fa-calendar"></i> <p><?php echo date("d.m.Y", $date_rel); ?></p></span>
				<span class="views"><i class="fa fa-eye"></i> <p><?php echo number_format($views_rel, 0, '.', ''); ?></p></span>
				<span class="raiting">
					<?php
						$ratings_o_rel = 5-$ratings_rel;
						echo str_repeat('<i class="fa fa-star"></i>', $ratings_rel);
						echo str_repeat('<i class="fa fa-star-o"></i>', $ratings_o_rel);
					?>
				</span>
			</div>
		</li>
<?php
				} // access
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