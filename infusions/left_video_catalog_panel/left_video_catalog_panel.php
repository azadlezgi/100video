<?php if (!defined("IN_FUSION")) { die("Access Denied"); }

//if ($component_id!=2) {
?>
<div class="left_catalog">
	<?php openside("Категории"); ?>
	<ul class="left_catalog_links">
<?php
		$result = dbquery("SELECT
												`id`,
												`name`,
												`alias`
										FROM ". DB_VIDEO_CATS ."
										ORDER BY `order`");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				echo "<li class='". str_replace("/", "_", $data['alias']) . (FUSION_URI=="/videos/". $data['alias'] ? " active" : "") ."'><a href='/videos/". $data['alias'] ."'>". $data['name'] ." <i class='fa fa-play-circle-o'></i></a></li>\n";
			} // while dbarray
		} // if dbrows

        unset($result);
        unset($data);

?>
	</ul>

	<?php closeside(); ?>
</div>
<?php //} ?>