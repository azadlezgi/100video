<?php

if (!defined("IN_FUSION")) { die("Access Denied"); }

require_once INCLUDES."comments_include.php";
require_once INCLUDES."ratings_include.php";
include LOCALE.LOCALESET."videos.php";


$viewcompanent = viewcompanent("videos", "name");
$seourl_component = $viewcompanent['components_id'];

$result = dbquery("SELECT 
								title,
								description,
								keywords,
								name,
								url,
								cat,
								h1,
								access,
								content,
								comments,
								ratings,
								views,
								user,
								date,
								url
FROM ". DB_VIDEOS ."
LEFT JOIN ". DB_SEOURL ." ON seourl_filedid=id AND seourl_component=". $seourl_component ."
WHERE id='". $filedid ."'
AND ". groupaccess('access') ."
AND status='1'
AND date<'". FUSION_TODAY ."'");
if (dbrows($result)) {
	$data = dbarray($result);

	$title = unserialize($data['title']);
	$description = unserialize($data['description']);
	$keywords = unserialize($data['keywords']);
	$name = unserialize($data['name']);
	$url = $data['url'];
	$cat = $data['cat'];
	$h1 = unserialize($data['h1']);
	$access = $data['access'];
	$content = unserialize($data['content']);
	$comments = $data['comments'];
	$ratings = $data['ratings'];
	$date = $data['date'];
	$views = $data['views'];
	$user = $data['user'];
	$seourl_url = $data['seourl_url'];

	set_title( ($title[LOCALESHORT] ? $title[LOCALESHORT] : $name[LOCALESHORT]) );
	set_meta("description",  ($description[LOCALESHORT] ? $description[LOCALESHORT] : "") );
	set_meta("keywords",  ($keywords[LOCALESHORT] ? $keywords[LOCALESHORT] : "") );
	add_to_head ("<link rel='canonical' href='http://". FUSION_HOST ."/". ($settings['opening_page']!=$seourl_url ? $seourl_url : "") ."' />");
	add_to_head ("<meta name='robots' content='index, follow' />");
	add_to_head ("<meta name='author' content='IssoHost' />");



	// $viewcompanent = viewcompanent("cats", "name");
	// $seourl_component = $viewcompanent['components_id'];

	// $c_result = dbquery("SELECT 
	// 								cat_name,
	// 								seourl_url
	// FROM ". DB_CATS ."
	// LEFT JOIN ". DB_SEOURL ." ON seourl_filedid=cat_id AND seourl_component=". $seourl_component ."
	// WHERE cat_id='". $cat ."'");
	// if (dbrows($c_result)) {
	// 	$c_data = dbarray($c_result);
	// 	$cat_name = unserialize($c_data['cat_name']);
	// } // db query 
		


	// if (FUSION_URI!="/") {
	// echo "<div class='breadcrumb'>\n";
	// echo "	<ul>\n";
	// echo "		<li><a href='". BASEDIR ."'>". $locale['640'] ."</a></li>\n";
	// echo "		<li><a href='". BASEDIR . $c_data['seourl_url'] ."'>". $cat_name[LOCALESHORT] ."</a></li>\n";
	// echo "		<li><span>". $name[LOCALESHORT] ."</span></li>\n";
	// echo "	</ul>\n";
	// echo "</div>\n";
	// }


	if ($h1[LOCALESHORT]) {
		opentable($h1[LOCALESHORT]);
	} else {
		opentable($name[LOCALESHORT]);
	}

	if (checkgroup($access)) {

		$result_user = dbquery("SELECT user_id, user_name FROM ". DB_USERS ." WHERE user_id=". $user);
		if (dbrows($result_user)) {
			$data_user = dbarray($result_user);
			$user_id = $data_user['user_id'];
			$user_name = $data_user['user_name'];
		}

	add_to_footer ('<script type="text/javascript" src="'. INCLUDES.'player/swfobject.js"></script>');
add_to_footer ('
<script type="text/javascript"> 
	var flashvars = { "file":"http://www.youtube.com/watch?v='. $url .'","poster":"http://i3.ytimg.com/vi/'. $url .'/hqdefault.jpg","st":"'. INCLUDES. 'player/uppod.txt"}; 
	var params = {bgcolor:"#ffffff", wmode:"window", allowFullScreen:"true", allowScriptAccess:"always"}; 
	swfobject.embedSWF("'. INCLUDES. 'player/uppod.swf", "videoplayer", "100%", "450", "10.0.0.0", false, flashvars, params); 
</script>
');

	// add_to_footer ("<script type='text/javascript' src='". INCLUDES. "player/uppod.js'></script>");
	// add_to_footer ('<script type="text/javascript">this.player = new Uppod({m:"video",uid:"videoplayer",file:"https://www.youtube.com/embed/'. $url .'",poster:"http://i3.ytimg.com/vi/'. $url .'/hqdefault.jpg",st:"'. INCLUDES. 'player/uppod.txt"});</script>');
?>



<div class="page">
	<div class="player" id="videoplayer"></div>

	<!-- <iframe src="https://www.youtube.com/embed/<?php echo $url; ?>" width="100%" height="450" frameborder="0" allowfullscreen></iframe> -->
	<div class="videos_media"> 
		<span class="user"><a href="/videouser/user_<?php echo $user_id; ?>"><i class="fa fa-user"></i> <?php echo $user_name; ?></a></span>
		<span class="calendar"><i class="fa fa-calendar"></i> <?php echo date("d.m.Y", $date); ?></span>
		<span class="views"><i class="fa fa-eye"></i> <?php echo number_format($views, 0, '.', ' '); ?></span>
		<span class="raiting">
			<?php
				$ratings_o = 5-$ratings;
				echo str_repeat('<i class="fa fa-star"></i>', $ratings);
				echo str_repeat('<i class="fa fa-star-o"></i>', $ratings_o);
			?>
		</span>
	</div>
	<div class="content">
		<p><?php echo str_replace("\n", "<br />", strip_tags(htmlspecialchars_decode($content[LOCALESHORT]))); ?></p>
	</div>
	<div id="vk_comments"></div>
</div>


<?php
		$update_views = $views+1;
		$result_views = dbquery(
						"UPDATE ". DB_VIDEOS ." SET
															views='". $update_views ."'
						WHERE id='". $filedid ."'"
		);

	} else {
		echo "<div class='admin-message' style='text-align:center'><br /><img style='border:0px; vertical-align:middle;' src ='".BASEDIR."images/warn.png' alt=''/><br /> ".$locale['400']."<br /><a href='index.php' onclick='javascript:history.back();return false;'>".$locale['403']."</a>\n<br /><br /></div>\n";
	} // checkgroup access

	closetable();



	if (isset($pagecount) && $pagecount > 1) {
	    echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 1, $pagecount, 3, FUSION_SELF."?id=". $filedid ."&amp;")."\n</div>\n";
	}
	echo "<!--custompages-after-content-->\n";
	if (dbrows($result) && checkgroup($data['access'])) {
		if ($cp_data['allow_comments']) { showcomments("V", DB_VIDEOS, "id", $filedid,FUSION_SELF."?id=". $filedid); }
		if ($cp_data['allow_ratings']) { showratings("V", $filedid, FUSION_SELF."?id=". $filedid); }
	}

} else {
	include COMPONENTS ."404.php";
} // db query

?>