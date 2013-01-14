<?php

// ==============================
// COMPRESS, SESSION, AND DEVICES
// ==============================
ob_start( 'ob_gzhandler' );
session_start();
include('devices.php');

$ref = '';

// ==============================
// FETCH THE ALBUM DATA FROM JSON
// ==============================
$data = json_decode(file_get_contents('albumdata.json'));
$albumdata = $data->{'albumdata'};
$album = $albumdata->{'name'};
$artist = $albumdata->{'artist'}->{'name'};
$title = $album . " | An album by " . $artist;
$key = strtolower($album . " lyrics, " . $artist . " lyrics, stream " . $album . ", stream" . $artist);

// ==============================
// IF LINKING TO A SPECIFIC TRACK
// ==============================
if (isset($_GET['track'])){
	$tracks = $albumdata->{'tracks'};
	foreach($tracks as $track){
		if ($track->{'uri'} == $_GET['track']){
			$song = $track->{'name'};
			$title = "\"" . $song . "\" by " . $artist . " | Lyrics and Streaming Audio";
			$key .= ", " . strtolower($song . " lyrics, " . $song . " streaming");
			break;
		}
	}
}

?>

<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6"> <![endif]--> 
<!--[if IE 7 ]>    <html lang="en" class="ie7"> <![endif]--> 
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]--> 
<!--[if IE 9 ]>    <html lang="en" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]--> 
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo $title; ?></title>
	<!--


	ALBUM APP BY DARLINGSIDE
	A FREE AND OPEN SOURCE VISUAL ALBUM COMPANION APP FOR WEB AND MOBILE


	-->
	<meta name="keywords" content="<?php echo $key; ?>">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />
	<!--<link rel="shortcut icon" href="/favicon.ico">-->
	<!--<link rel="apple-touch-icon" href="/apple-touch-icon.png">-->
	<link rel="stylesheet" href="css/supersized.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/style.css?v=5" type="text/css">

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body class='<?php if ($android) echo "android "; ?>'>

<div id='wrapper'>
	<div id='album'>
	</div>
</div>
<div id='player'>
	<p id='inplayer'>
		<a id='track-prev' class="entypo" href="#">&#9194;</a>
		<a class="jp-stop entypo" href="#">&#9632;</a>
		<a class="jp-play entypo" href="#">&#9654;</a>
		<a class="jp-pause entypo" href="#">&#8214;</a>
		<a id='track-next' class="entypo" href="#">&#9193;</a>
		<span class='track-info'>
			&quot;<span class="track-name"></span>&quot;
			<span class='xtra entypo' style='font-size:1.6em;'>&#9834;</span>
			<span class="xtra jp-current-time">00:00</span>
		</span>
	</p>
</div>
<div id='buttons'>
	<button id='more' class='hover'>More</button>
	<button id='buy' class='hover'>Buy</button>
</div>
<div id="jplayer"></div>
<div id='black'></div>

<?php include('album-template.html'); ?>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script type="text/javascript">window.jQuery || document.write('<script src="js/jquery-1.8.3.min.js"><\/script>')</script>
<script type="text/javascript" src="js/handlebars-1.0.rc.1.min.js"></script>
<script type="text/javascript" src="js/supersized.3.2.7.min.js"></script>
<script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
<!--<script type="text/javascript" src="js/jquery.easyModal.min.edit.js"></script>-->
<script type="text/javascript">

$(document).ready(function(){
	
	var rando = "<?php echo session_id(); ?>";
	var autoscroll = false;
	<?php if ($android || $firefox || isset($_GET['track'])){ ?>
	autoscroll = true;
	<?php } ?>
	var context = {};
	var jplayer = $("#jplayer");
	var album_source   = $("#album-template").html();
	// var sharemodal_source   = $("#sharemodal-template").html();
	var album_template = Handlebars.compile(album_source);
	// var sharemodal_template = Handlebars.compile(sharemodal_source);
	
	$.getJSON("albumdata.json", {}, function(result){
		context = result;
		var album_html = album_template(context);
		// var sharemodal_html = sharemodal_template(context);
		$("#album").html(album_html);
		// $("#sharemodal").html(sharemodal_html);

		init();
		
	});

	<?php require_once('albumapp.js.php'); ?>

});

// ******************************************************//
// GOOGLE ANALYTICS
// ******************************************************//

var _gaq = _gaq || [];
_gaq.push(['_setAccount', '<?php echo $albumdata->{'googleanalytics'}; ?>']);
_gaq.push(['_trackPageview']);

(function() {
	  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
	
</script>
<script type="text/javascript" src="js/respond.js"></script>
</body>
</html>