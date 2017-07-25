<?php

if (!defined("IN_FUSION")) { die("Access Denied"); }

if (iMEMBER) {
add_to_head ("<link rel='stylesheet' href='". THEME ."css/top-admin_panel.css' type='text/css' media='screen' />");
add_to_footer ("<script  type='text/javascript' src='". THEME ."js/top-admin_panel.js'></script>");

	echo "<div id='topadminpanel'>\n";
	echo "	<div class='main'>\n";
	echo "		<ul>\n";
	echo "			<li class='profile'><a href='".BASEDIR."edit_profile.php' class='side'>". $locale['global_120'] ."</a></li>\n";

	$msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0' AND message_folder='0'");
	echo "			<li class='messages'><a href='".BASEDIR."messages.php' class='side'>". $locale['global_121'] . ($msg_count ? " (". $msg_count .")" : "") ."</a></li>\n";
	echo "			<li class='members'><a href='".BASEDIR."members.php' class='side'>". $locale['global_122'] ."</a></li>\n";

	if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
		echo "		<li class='admin'><a href='".ADMIN."index.php".$aidlink."' class='side'>".$locale['global_123']."</a></li>\n";
	} // Yesli Admin

	// if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
	// 	$subm_count_cars = dbcount("(cars_id)", DB_CARS, "cars_aktiv='2'");
	// 	$subm_count_salon = dbcount("(salon_id)", DB_SALONS, "salon_aktiv='2'");
	// 	$subm_count_shop = dbcount("(shop_id)", DB_SHOPS, "shop_aktiv='2'");
	// 	$subm_count_service = dbcount("(service_id)", DB_SERVICES, "service_aktiv='2'");
	// 	$subm_count_buyacars = dbcount("(buyacars_id)", DB_BUYACARS, "buyacars_aktiv='2'");
	// 	$subm_count_rentalcar = dbcount("(rentalcar_id)", DB_RENTALCARS, "rentalcar_aktiv='2'");
	// 	$subm_count_parts = dbcount("(parts_id)", DB_PARTS, "parts_aktiv='2'");

	// 	$subm_count = $subm_count_cars+$subm_count_salon+$subm_count_shop+$subm_count_service+$subm_count_buyacars+$subm_count_rentalcar+$subm_count_parts;

	// 	//if ($subm_count) {
	// 		echo "			<li class='submissions'><a href='".ADMIN."index.php".$aidlink."' class='side'>".sprintf($locale['global_125'], $subm_count);
	// 		echo ($subm_count == 1 ? $locale['global_128'] : $locale['global_129'])."</a></li>\n";
	// 	//} // submissions count
	// } // Yesli submissions

	echo "			<li class='logout'><a href='".BASEDIR."index.php?logout=yes' class='side'>".$locale['global_124']."</a></li>\n";
	echo "			<li class='zakrepit'><a href='#' class='side' id='zakrepit'>Закрепить</a></li>\n";
	echo "		</ul>\n";
	echo "		<div class='yab'>&nbsp;</div>\n";
	echo "	</div>\n";
	echo "</div>";
} // Yesli Member