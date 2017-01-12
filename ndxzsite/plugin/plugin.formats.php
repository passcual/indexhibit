<?php if (!defined('SITE')) exit('No direct script access allowed');


function gimg($file, $width='', $height='')
{
	$OBJ =& get_instance();

	$size = @getimagesize(DIRNAME . GIMGS . '/' . $file);
	
	$width = ($width == '') ? "width='$size[0]'" : "width='$width'";
	
	// scaling: if only a width is specfified it will not return a height
	if ($height != '') { $height = ($height == '') ? "height='$size[1]'" : "height='$height'"; } else { $height = ''; }
	
	return "<img src='" . $OBJ->baseurl . GIMGS ."/$file' $width $height />";
}


function mp3($file, $text='')
{
	$OBJ =& get_instance();
	
	// we check if file exists first
	if (!file_exists(DIRNAME . "/files/$file")) return;

	$title = ($text == '') ? $file : $text;
	$s = "<span class='flat mp3'><a href='" . $OBJ->baseurl . "/files/$file' class='sm2_link'>$title</a></span>\n";	
	
	$OBJ->page->add_lib_js('soundmanager2-nodebug-jsmin.js', 50);
	$OBJ->page->add_lib_js('inlineplayer.js', 51);
	$OBJ->page->exhibit['lib_css'][] = "audio.css";

 	$OBJ->page->add_jquery_onready("soundManager.url = '" . $OBJ->baseurl . "/ndxzsite/img/';
soundManager.flashVersion = 9;
soundManager.useFlashBlock = false;", 6);
	
	return $s;
}


function mp4($file, $width='400', $height='300', $prv_img='', $text='', $wrap='div', $autoplay='false')
{	
	$OBJ =& get_instance();
	
	return flv($file, $width, $height, $prv_img);
	
	// we've cut this off here - revise later

	$rand = rand(0, 99999);
	
	// guess this wasn't loaded...???
	$OBJ->lib_class('front');
	
	// we check if file exists first
	if (!file_exists(DIRNAME . "/files/$file")) return;
	
	$OBJ->page->add_lib_js('jwplayer.js', 21);
	//$OBJ->page->add_lib_js('swfobject.js', 22);
	
	$out = "<video id='myplayer{$rand}' class='mp4' src='" . $OBJ->baseurl . "/files/$file' width='$width' height='$height' controls></video>\n";
	
	return $out;
}


function mov($file, $width='600', $height='400', $prv_img='', $autoplay='false', $screencolor='f3f3f3')
{
	$OBJ =& get_instance();
	
	// we check if file exists first
	if (!file_exists(DIRNAME . "/files/$file")) return;
	
	// our helper js files
	$OBJ->lib_class('page');
	$OBJ->page->add_lib_js('jwplayer.js', 21);
	
	$no = rand(1, 99999999999);

	$bgimg = ($prv_img != '') ? "'image': '$OBJ->baseurl/files/gimgs/$prv_img'," : '';
	
	$out = "<video id='mediaplayer-$no' class='mov' width='$width' height='$height' src='" . $OBJ->baseurl . "/files/$file' $bgimg></video>";

	$out .= "<script type='text/javascript'>
	  jwplayer('mediaplayer-$no').setup({
	     'flashplayer': '" . $OBJ->baseurl . "/ndxzsite/img/player.swf',
		 'screencolor': '$screencolor',
		 'autostart': $autoplay,
		 $bgimg 
		 'stretching': 'fill',
		 'controlbar.position': 'over',
		 'controlbar.idlehide': true
	  });
	</script>";
	
	return $out;
}


function swf($file, $width='400', $height='300', $prv_img='', $text='', $wrap='div')
{
	$OBJ =& get_instance();
	
	// we check if file exists first
	if (!file_exists(DIRNAME . "/files/$file")) return;
	
	// let's think about this
	//$OBJ->page->exhibit['lib_js'][] = 'swfobject.js';
	$OBJ->page->add_lib_js('swfobject.js', 22);
	
	$prv_img = ($prv_img != '') ? $prv_img : '';
	
	$no = rand(1, 1000);
	
	$out = ($wrap == 'div') ? "<div id=\"player-$no\" class='swf'></div>\n{$text}" : "<span id=\"player-$no\" class='swf'></span>\n{$text}";

	$out .= "<script type=\"text/javascript\">\n";
	$out .= "var so = new SWFObject('" . $OBJ->baseurl . "/files/$file', 'mymovie', '$width', '$height', '7');\n";
	$out .= "so.addParam('quality', 'low');\n";
	$out .= "so.addParam('wmode','transparent');\n";
	$out .= "so.addParam('salign','t');\n";
	$out .= "so.write('player-$no');\n";
	$out .= "</script>\n";
	
	return $out;
}


function flv($file, $width='400', $height='300', $prv_img='', $text='', $wrap='div', $autoplay='false', $repeat='false', $screencolor='f3f3f3')
{
	$OBJ =& get_instance();
	
	// we check if file exists first
	if (!file_exists(DIRNAME . "/files/$file")) return;
	
	// our helper js files
	// our helper js files
	if (!isset($OBJ->vars->exhibit['ajax']))
	{
		$OBJ->lib_class('page');
		$OBJ->page->add_lib_js('jwplayer.js', 21);
	}
	else
	{
		$OBJ->page->add_lib_js('jwplayer.js', 21);
	}
	
	$no = rand(1, 99999999999);
	
	// ???
	$OBJ->lib_class('page');
	$OBJ->page->add_lib_js('jwplayer.js', 21);
	//$OBJ->page->add_lib_js('swfobject.js', 22);
	
	//$out = "<video id='myplayer{$no}' src='" . $OBJ->baseurl . "/files/$file' width='$width' height='$height' controls></video>\n";
	
	$bgimg = ($prv_img != '') ? "'image': '" . $OBJ->baseurl . GIMGS . "/$prv_img'," : '';
	
	$out = "<div id='player-$no' class='flv'>This text will be replaced</div>
	<script type='text/javascript'>
	  jwplayer('player-$no').setup({
	    'flashplayer': '" . $OBJ->baseurl . "/ndxzsite/img/player.swf',
	    'file': '" . $OBJ->baseurl . "/files/$file',
	    'controlbar': 'over',
		$bgimg 
		'screencolor': '$screencolor',
		'autostart': '$autoplay',
	    'width': '$width',
	    'height': '$height'
	  });
	</script>";
	
	return $out;
}


function youtube($file, $width='425', $height='344', $prv_img='')
{
	$OBJ =& get_instance();

	if ($file == '') return;
	
	$file = str_replace('.youtube', '', $file);
	
	// if you want the native youtube player simply uncomment this
	// we'll add a plugin/hook to do this later
	//return "<div class='vimeo' style='width: {$width}px; height: {$height}px;'><iframe width='$width' height='$height' src='http://www.youtube.com/embed/$file' frameborder='0' allowfullscreen></iframe></div>";
	
	// our helper js files
	//$OBJ->lib_class('page');
	//$OBJ->page->add_lib_js('jwplayer.js', 21);
	
	$width = ($width == 0) ? '' : "width='$width'";
	$height = ($height == 0) ? '' : "height='$height'";
	
	return "<iframe $width $height src='https://www.youtube.com/embed/$file'></iframe>";

/*
	$out = "<div id='mplayer-$no' class='youtube'>This text will be replaced</div>\n";

	$out .= "<script type='text/javascript'>\n";
	$out .= "jwplayer('mplayer-$no').setup({\n";
	$out .= "'flashplayer': '" . $OBJ->baseurl . "/ndxzsite/img/player.swf',\n";
	$out .= "'file': 'http://www.youtube.com/watch?v=$file',\n";
	$out .= "'controlbar': 'over',\n";
	if ($prv_img != '') $out .= "'image': '" . $OBJ->baseurl . GIMGS . "/$prv_img',\n";
	$out .= "'screencolor': 'EEEEEE',\n";
	$out .= "'autostart': 'false',\n";
	$out .= "'provider': 'youtube',\n";
	$out .= "'width': '$width',\n";
	$out .= "'height': '$height',\n";
	$out .= "'stretching': 'fill',\n";
	$out .= "'controlbar.position': 'over',\n";
	$out .= "'controlbar.idlehide': true\n";
	$out .= "});\n";
	$out .= "</script>\n";
	*/
	
	//return $out;
}


function vimeo($file, $width='425', $height='315')
{
	if ($file == '') return;
	
	$file = str_replace('.vimeo', '', $file);
	
	return "<div class='vimeo' style='width: {$width}px; height: {$height}px;'><iframe src='http://player.vimeo.com/video/$file' width='{$width}' height='{$height}' frameborder='0'></iframe></div>\n";
}