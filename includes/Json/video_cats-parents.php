<?php
	
	include "../maincore.php";

	if ($_POST['parent']) {

		$viewcompanent = viewcompanent("video_cats", "name");
		$seourl_component = $viewcompanent['components_id'];

		$result = dbquery("SELECT 
									video_cat_id,
									video_cat_name,
									video_cat_order,
									video_cat_status,
									seourl_url
			FROM ". DB_VIDEO_CATS ."
			LEFT JOIN ". DB_SEOURL ." ON seourl_filedid=video_cat_id AND seourl_component=". $seourl_component ."
			WHERE video_cat_parent=". (INT)$_POST['parent']);
		if (dbrows($result)) {
			$j=0;
			$parent_array = array();
			while ($data = dbarray($result)) { $j++;
				$video_cat_name = unserialize($data['video_cat_name']);

				$parent_array[$j]['video_cat_id'] = $data['video_cat_id'];
				$parent_array[$j]['video_cat_name'] = $video_cat_name[LOCALESHORT];
				$parent_array[$j]['video_cat_order'] = $data['video_cat_order'];
				$parent_array[$j]['video_cat_status'] = $data['video_cat_status'];
				$parent_array[$j]['seourl_url'] = $data['seourl_url'];
			} // db whille
		} // db query

		echo json_encode($parent_array);
		// echo "<pre>";
		// print_r($parent_array);
		// echo "</pre>";
		// echo "<hr>";

	}

?>