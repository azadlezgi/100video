<?php

if (!defined("IN_FUSION")) { die("Access Denied"); }

include LOCALE.LOCALESET."video_cats.php";

$c_result = dbquery("SELECT 
								`id`,
								`title`,
								`description`,
								`keywords`,
								`name`,
								`h1`,
								`image`,
								`access`,
								`content`,
								`alias`
FROM ". DB_VIDEO_CATS ."
WHERE alias='". $alias ."'
AND ". groupaccess('access') ."
AND status='1'
AND date<'". FUSION_TODAY ."'");
if (dbrows($c_result)) {
	$c_data = dbarray($c_result);

	$c_id = $c_data['id'];
	$c_title = $c_data['title'];
	$c_description = $c_data['description'];
	$c_keywords = $c_data['keywords'];
	$c_name = $c_data['name'];
    $c_h1 = $c_data['h1'];
    $c_image = $c_data['image'];
	$c_access = $c_data['access'];
	$c_content = $c_data['content'];
	$c_alias = $c_data['alias'];

		set_title( (!empty($c_title) ? $c_title : $locale['title'] ." ". $c_name) );
		if (!empty($c_description)) set_meta("description", $c_description);
		if (!empty($c_keywords)) set_meta("keywords", $c_keywords);
		// add_to_head ("<link rel='canonical' href='http://". FUSION_HOST ."/". ($settings['opening_page']!=$seourl_url ? $seourl_url : "") ."' />");
		// add_to_head ("<meta name='robots' content='index, follow' />");
		// add_to_head ("<meta name='author' content='IssoHost' />");

		// if (FUSION_URI!="/") {
		// echo "<div class='breadcrumb'>\n";
		// echo "	<ul>\n";
		// echo "		<li><a href='". BASEDIR ."'>". $locale['640'] ."</a></li>\n";
		// echo "		<li><span>". $name[LOCALESHORT] ."</span></li>\n";
		// echo "	</ul>\n";
		// echo "</div>\n";
		// }


    if (FUSION_URI!="/") {
        echo "<div class='breadcrumb'>\n";
        echo "	<ul>\n";
        echo "		<li><a href='/'>". $locale['640'] ."</a> <i class='fa fa-angle-double-right'></i></li>\n";
        echo "		<li><a href='/videos'>". $locale['641'] ."</a> <i class='fa fa-angle-double-right'></i></li>\n";
        echo "		<li><span>". $c_name ."</span></li>\n";
        echo "	</ul>\n";
        echo "</div>\n";
    }



		opentable( (isset($c_h1) && !empty($c_h1) ? $c_h1 : $c_name) );




//				$result_videocat = dbquery("SELECT 
//												id,
//												name,
//												image,
//												access,
//												seourl_url
//				FROM ". DB_CATS ."
//				LEFT JOIN ". DB_SEOURL ." ON seourl_filedid=id AND seourl_component=". $seourl_component ."
//				WHERE status='1'
//				AND parent='". $id ."'");
//				if (dbrows($result_videocat)) {
//					echo "<div class='videocats_list'>\n";
//					$videocat_say = 0;
//					while ($data_videocat = dbarray($result_videocat)) { $videocat_say++;
//
//						$videocat_id = $data_videocat['id'];
//						$videocat_name = unserialize($data_videocat['name']);
//						$videocat_image = $data_videocat['image'];
//						$videocat_access = $data_videocat['access'];
//						$videocat_url = $data_videocat['seourl_url'];
//
//						if (checkgroup($videocat_access)) {
//
//							echo "	<div class='videocats videocat'". $videocat_id ."'>\n" ;
//							echo "		<a href='". BASEDIR . $videocat_url ."' class='videocat_name'>". $videocat_name[LOCALESHORT] ."</a>\n";
//							echo "		<a href='". BASEDIR . $videocat_url ."' class='videocat_img'><img src='". ($videocat_image ? IMAGES_VC_T . $videocat_image : IMAGES ."imagenotfound.jpg") ."' alt='". $videocat_name[LOCALESHORT] ."'></a>\n";
//							echo "	</div>\n";
//
//						} // access
//					} // db while
//					echo "	<div class='clear'></div>\n";
//					echo "</div>\n";
//				} // db query





if (isset($_GET['page']) && $_GET['page']>0) { $pagesay = (INT)$_GET['page']; }
else { $pagesay = 1; }
$rowstvideo = $settings['videos_per_page']*($pagesay-1);




				$result = dbquery("SELECT 
											`id`,
											`name`,
											`image`,
											`content`,
											`url`,
											`duration`,
											`access`,
											`date`,
											`ratings`,
											`views`,
											`alias`
					FROM ". DB_VIDEOS ."
					WHERE status='1'
					AND date<'". FUSION_TODAY ."'
					AND cat='". $c_id ."'
					". (isset($_GET['user']) && $_GET['user']>0 ? " AND user=". (INT)$_GET['user'] : "") ."
					ORDER BY `order`
					LIMIT ". $rowstvideo .", ". $settings['videos_per_page'] ."");


				if (dbrows($result)) { $say = 0;

?>
<div class="videos_list">
	<div class="row clearfix">
<?php
					while ($data = dbarray($result)) {

						$id = $data['id'];
						$name = $data['name'];
						$image = $data['image'];
						$url = $data['url'];
						$duration = $data['duration'];
						$access = $data['access'];
						$date = $data['date'];
						$ratings = $data['ratings'];
						$views = $data['views'];
						$alias = $data['alias'];

						if (checkgroup($access)) { $say++;


?>
	<div class="col-sm-3">
		<div class="videos_thumb">
			<a href="/videos/<?php echo $c_alias ."/". $alias; ?>">
				<img src="<?php echo ($image ? IMAGES_V_T . $image : "https://i.ytimg.com/vi/". $url ."/mqdefault.jpg"); ?>" alt="<?php echo $name; ?>">
				<i class="fa fa-play"></i>
			</a>
		</div>
		<h4><a href="/videos/<?php echo $c_alias ."/". $alias; ?>"><?php echo $name; ?></a></h4>
		<a href="/videos/<?php echo $c_alias ."/". $alias; ?>" class="videos_media">
			<span class="duration"><i class="fa fa-clock-o"></i> <?php echo $duration; ?></span>
			<span class="calendar"><i class="fa fa-calendar"></i><?php echo date("d.m.Y", $date); ?></span>
			<span class="views"><i class="fa fa-eye"></i><?php echo number_format($views, 0, '.', ' '); ?></span>
			<span class="raiting">
				<?php
					$ratings_o = 5-$ratings;
					echo str_repeat('<i class="fa fa-star"></i>', $ratings);
					echo str_repeat('<i class="fa fa-star-o"></i>', $ratings_o);
				?>
			</span>
		</a>
	</div>
<?php
						} // access
					} // db whille
?>
	</div>
</div>
<div class="clear"></div>
<?php
	} else {
		echo "Нет ни одного видео ролика\n";
	} // db query

	echo navigation( (isset($_GET['page']) && $_GET['page']>0 ? (INT)$_GET['page'] : 0), $settings['videos_per_page'], "id", DB_VIDEOS, "status='1' AND date<'". FUSION_TODAY ."' AND cat='". $c_id ."' AND ". groupaccess('access') . (isset($_GET['user']) && $_GET['user']>0 ? " AND user=". (INT)$_GET['user'] : "") ."");



//				if ($c_image) {
//				    echo "<img src='". IMAGES_VC . $c_image ."' alt='' />\n";
//              }
                if (isset($c_content) && !empty($c_content)) {
                    echo "<div class='content_desc'>\n";
                    ob_start();
                    eval("?>" . htmlspecialchars_decode($c_content) . "<?php ");
                    $custompage = ob_get_contents();
                    ob_end_flush();
                    echo "</div>\n";
                }




		closetable();
} else {
	include COMPONENTS ."404.php";
} // db query




?>