function init(){
	$(".hover").hover(function(){$(this).addClass("over");},function(){$(this).removeClass("over");});
	
	jplayer.jPlayer({
		ready: function () {
			<?php if (isset($_GET['track'])){ ?>
			$("#<?php echo $_GET['track']; ?>").click();
			<?php }else{ ?>
			$("li.track").first().click();
			<?php } ?>
			<?php if (!isset($_GET['play'])){ ?>
			$(this).jPlayer("stop");
			<?php } ?>
		},
		play: function(event) {
			if (autoscroll){
				var anchor = $("li.track.playing").attr('id');
				var scrollto = $('#' + anchor + '-title').offset().top - 8;
				$('html, body').stop().animate({scrollTop: scrollto}, 1000);
			}else autoscroll = true;
		},
		ended: function(event) {
			$("li.track.playing").next().click(); // TODO: last song case
		},
		swfPath: "jplayer",
		cssSelectorAncestor: "#inplayer",
		supplied: "mp3,ogg",
		<?php if ($android){ ?>
		solution:"flash,html",
		<?php } ?>
		wmode: "window"
	});
	
	$("li.track").click(function(){
		var songtitle = $(this).find('.trackname').html();
		var mp3link = "tracks/" + songtitle + ".mp3";
		var ogglink = "tracks/" + songtitle + ".ogg";
		jplayer.jPlayer("setMedia", {mp3: mp3link, ogg: ogglink});
		$(".track-name").text($(this).find(".trackname").text());
		$("li.track").removeClass("playing");
		$(this).addClass("playing");
		jplayer.jPlayer("play");
		$.post("/tracker.php", {type: "li", id: this.id, sess: rando});
	});
	
	$("#track-next").click(function(){
		$("li.track.playing").next().click();
		return false;
	});
	
	$("#track-prev").click(function(){
		$("li.track.playing").prev().click();
		return false;
	});
	
	/* $("#sharemodal").easyModal();
	
	$("button#share").click(function(){
		$("#sharemodal").trigger('openModal');
	}); */
	
	$("button#buy").click(function(){
		var scrollto = $('#store').offset().top - 8;
		$('html, body').stop().animate({scrollTop: scrollto}, 1000);
		autoscroll = false;
		$.post("/tracker.php", {type: "btn", id: this.id, sess: rando});
	});
	
	$("button#more").click(function(){
		var scrollto = $('#getmore').offset().top - 8;
		$('html, body').stop().animate({scrollTop: scrollto}, 1000);
		autoscroll = false;
		$.post("/tracker.php", {type: "btn", id: this.id, sess: rando});
	});
	
	/* $("select").change(){
		
	} */
	
	/* LINK LISTENER */
	function clickListener(e){
		var clickedElement = (window.event) ? window.event.srcElement : e.target;
		if (clickedElement.tagName == "A"){
			$.post("/tracker.php", {type: "a", id: clickedElement.id, sess: "<?php echo session_id(); ?>"});
		}
	}
	document.onclick = clickListener;
	
	$.post("/tracker.php", {type: "ref", id: "<?php echo $ref; ?>", sess: rando});
}