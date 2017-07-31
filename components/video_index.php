<?php

if (!defined("IN_FUSION")) { die("Access Denied"); }

include LOCALE.LOCALESET."video_index.php";


set_title( ($settings['sitename'] ? $settings['sitename'] : $locale['title']) );
set_meta("description", ($settings['description'] ? $settings['description'] : $locale['description']) );
set_meta("keywords", ($settings['keywords'] ? $settings['keywords'] : $locale['keywords']) );
// add_to_head ("<link rel='canonical' href='http://". FUSION_HOST ."/". ($settings['opening_page']!=$video_cat_seourl_url ? "video_index.php" : "") ."' />");
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


if (FUSION_URI!="/") {
    echo "<div class='breadcrumb'>\n";
    echo "	<ul>\n";
    echo "		<li><a href='/'>". $locale['640'] ."</a> <i class='fa fa-angle-double-right'></i></li>\n";
    echo "		<li><span>". $locale['641'] ."</span></li>\n";
    echo "	</ul>\n";
    echo "</div>\n";
}

opentable( ($settings['sitename'] ? $settings['sitename'] : $locale['h1']) );


$result = dbquery("SELECT 
								`id`,
								`name`,
								`image`,
								`alias`
FROM ". DB_VIDEO_CATS ."
WHERE ". groupaccess('access') ."
AND status='1'
AND date<'". FUSION_TODAY ."'");
if (dbrows($result)) {

?>
<div id="videos_cats_gride">
	<div class="row clearfix">
<?php
	while ($data = dbarray($result)) {

        $id = $data['id'];
        $name = $data['name'];
        $image = $data['image'];
        $alias = $data['alias'];

?>
	<div class="col-sm-4">
		<figure>
			<a href="/videos/<?php echo $alias; ?>">
				<img src="/<?php echo (isset($image) && !empty($image) ? IMAGES_VC_T . $image : IMAGES ."imagenotfound360x270.jpg"); ?>" alt="<?php echo $name; ?>">
				<i class="fa fa-play"></i>
			</a>
		</figure>
		<h4><a href="/videos/<?php echo $alias; ?>"><?php echo $name; ?></a></h4>
	</div>
	<?php
	} // db whille
} // db query
?>
	</div>
</div>
<?php
		closetable();
?>