<?php

// echo "url: ". FUSION_URI ."<br />\n";
// echo "row: ". $_GET['row_url'] ."<br />\n";
// echo "page: ". $_GET['page'] ."<br />\n";
// echo "user: ". $_GET['user'] ."<br />\n";

if (!defined("IN_FUSION")) { die("Access Denied"); }

define("THEME_BULLET", "<span class='bullet'></span>");

require_once INCLUDES."theme_functions_include.php";

function render_page($license = false) {
	
global $settings, $main_style, $locale, $mysql_queries_time, $languages, $currency, $aidlink, $userdata;



add_to_head ("<meta name='viewport' content='width=device-width, initial-scale=1'>");


//add_to_head ("<meta name='advmaker-verification' content='90c7d23e23e4b6126499a9b125fe2422'/>");


//add_to_footer ("<script type='text/javascript' src='". THEME ."js/lightbox.js'></script>");
//add_to_head ("<link rel='stylesheet' href='". THEME ."css/lightbox.css' type='text/css' media='screen' />");

//add_to_head ("<meta name='yandex-verification' content='4b3c2a42a7daa134'/>");



//add_to_footer ("<script type='text/javascript' src='". THEME ."js/share42.js'></script>");

add_to_head ("<link rel='stylesheet' href='". THEME ."css/font-awesome.css' type='text/css' media='screen' />");

add_to_footer ("<script  type='text/javascript' src='". THEME ."js/jquery.cookie.js'></script>");

add_to_footer ("<script  type='text/javascript' src='". THEME ."js/bootstrap.js'></script>");
add_to_head ("<link rel='stylesheet' href='". THEME ."css/bootstrap.css' type='text/css' media='screen' />");

add_to_head ("<link rel='stylesheet' href='". THEME ."styles.css' type='text/css' media='screen' />");

?>

<?php include "includes/top-admin_panel.php"; ?>



<div class="wrapper">
<header>
	<div class="main">
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<a href="#" id="logo"><i class="fa fa-film"></i>Mine<span>Craft</span></a>
				</div>
				<div class="col-sm-9 text-center">
					<div class="header_search">
						<form action="/search" method="GET" class="search-form" role="search">
							<input id="search-field" name="q" type="text" placeholder="" class="hint">
							<button id="search-submit" type="submit"><i class="fa fa-search"></i></button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<section>
	<div class="container">
		<div class="row">

			<div class="share42init" data-top1="123" data-top2="20" data-margin="-15"></div>

			<?php if (U_CENTER) { ?>
			<?php echo U_CENTER; ?>
			<?php } ?>
			<?php if (LEFT) { ?>
			<div class="col-sm-3">
				<aside>
					<?php echo LEFT; ?>
				</aside>
			</div>
			<?php } ?>
			<?php if (LEFT && RIGHT) { ?>
			<div class="col-sm-6">
			<?php } else if (LEFT) { ?>
			<div class="col-sm-9 clearfix">
			<?php } else if (RIGHT) { ?>
			<div class="col-sm-9">
			<?php } else { ?>
			<div class="col-sm-12 clearfix">
			<?php } ?>
				<article>
					<?php echo CONTENT; ?>
				</article>
			</div>
			<?php if (RIGHT) { ?>
			<div class="col-sm-3 clearfix">
				<aside>
					<?php echo RIGHT; ?>
				</aside>
			</div>
			<?php } ?>
			<?php if (L_CENTER) { ?>
			<?php echo L_CENTER; ?>
			<?php } ?>


		</div>
	</div>
</section>
<footer>
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<div class="copy">&copy; 2016 minecraftvideotut.ru. Все права защищены.</div>
			</div>
			<div class="col-sm-6 clearfix">
				<div class="counters text-right">
							<!--LiveInternet counter--><script type="text/javascript"><!--
                            document.write("<a href='//www.liveinternet.ru/click' "+
                            "target=_blank><img src='//counter.yadro.ru/hit?t44.1;r"+
                             escape(document.referrer)+((typeof(screen)=="undefined")?"":
                            ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                            screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
                            ";"+Math.random()+
                            "' alt='' title='LiveInternet' "+
                            "border='0' width='31' height='31'><\/a>")
                            //--></script><!--/LiveInternet-->
                            <!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter35437755 = new Ya.Metrika({
                    id:35437755,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true,
                    trackHash:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/35437755" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-16650808-9', 'auto');
  ga('send', 'pageview');

</script>
				</div>
			</div>
		</div>
	</div>
</footer>
</div>



<?php
	/*foreach ($mysql_queries_time as $query) {
		echo $query[0]." QUERY: ".$query[1]."<br />";
	}*/
} // function render_page

/* New in v7.02 - render comments */
function render_comments($c_data, $c_info){
	global $locale, $settings;
	opentable($locale['c100']);
	if (!empty($c_data)){
		echo "<div class='comments floatfix'>\n";
			$c_makepagenav = '';
			if ($c_info['c_makepagenav'] !== FALSE) { 
			echo $c_makepagenav = "<div style='text-align:center;margin-bottom:5px;'>".$c_info['c_makepagenav']."</div>\n"; 
		}
			foreach($c_data as $data) {
			$comm_count = "<a href='".FUSION_REQUEST."#c".$data['comment_id']."' id='c".$data['comment_id']."' name='c".$data['comment_id']."'>#".$data['i']."</a>";
			echo "<div class='tbl2 clearfix floatfix'>\n";
			if ($settings['comments_avatar'] == "1") { echo "<span class='comment-avatar'>".$data['user_avatar']."</span>\n"; }
			echo "<span style='float:right' class='comment_actions'>".$comm_count."\n</span>\n";
			echo "<span class='comment-name'>".$data['comment_name']."</span>\n<br />\n";
			echo "<span class='small'>".$data['comment_datestamp']."</span>\n";
	if ($data['edit_dell'] !== false) { echo "<br />\n<span class='comment_actions'>".$data['edit_dell']."\n</span>\n"; }
			echo "</div>\n<div class='tbl1 comment_message'>".$data['comment_message']."</div>\n";
		}
		echo $c_makepagenav;
		if ($c_info['admin_link'] !== FALSE) {
			echo "<div style='float:right' class='comment_admin'>".$c_info['admin_link']."</div>\n";
		}
		echo "</div>\n";
	} else {
		echo $locale['c101']."\n";
	}
	closetable();   
} // function render_comments

function render_news($subject, $news, $info) {

	echo "<div class='render_news'>\n";
	echo "<h1>". $subject ."</h1>\n";
	echo U_CONTENT;
	echo "<div class='main-body'>". $info['cat_image'] . $news ."</div>\n";
	echo "<div class='newsposter'>";
	echo newsposter($info," &middot;").newscat($info," &middot;").newsopts($info,"&middot;").itemoptions("N",$info['news_id']);
	echo L_CONTENT;
	echo "</div>\n";
	echo "</div>\n";

} // function render_news

function render_article($subject, $article, $info) {
	
	echo "<div class='render_article'>\n";
	echo "<h1>". $subject ."</h1>\n";
	echo U_CONTENT;
	echo "<div class='main-body'>". ($info['article_breaks'] == "y" ? nl2br($article) : $article)."</div>\n";
	echo "<div class='articleposter'>";
	echo articleposter($info," &middot;").articlecat($info," &middot;").articleopts($info,"&middot;").itemoptions("A",$info['article_id']);
	echo L_CONTENT;
	echo "</div>\n";
	echo "</div>\n";

} // function render_article

function opentable($title) {

	echo "<div class='render_page'>\n";
	echo "<h1>". $title ."</h1>\n";
	echo U_CONTENT;
	echo "<div class='main-body'>\n";

} // function opentable

function closetable() {

	echo L_CONTENT;
	echo "</div>\n";
	echo "</div>\n";

} // function closetable

function openside($title, $collapse = false, $state = "on") {

	global $panel_collapse; $panel_collapse = $collapse;
	

	echo "<div class='openside'>\n";
	if ($title) {
		echo "<div class='side-title'>". $title ."</div>\n";
	}
	if ($collapse == true) {
		if ($title) {
			echo "<div class='side-title'>". panelbutton($state, str_replace(" ", "", $title)) ."</div>\n";
		}
	}
	echo "<div class='side-body'>\n";	
	if ($collapse == true) { echo panelstate($state, $boxname); }

} // function openside

function closeside() {
	
	global $panel_collapse;

	if ($panel_collapse == true) { echo "</div>\n"; }
	echo "</div>\n";
	echo "</div>\n";

} // function closeside
?>