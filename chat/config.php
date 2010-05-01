<?php
 /*
 //********************************************************************
 // == Author:			Afrob
 // == Contact:			afrob@spam-webdatabox.de
 //						please remove the "spam-"
 // == Description: 	Login Front End for the PJIRC Java Chat-Client
 // == Version:			4.1
 //********************************************************************
 // == Contain:			This file contains the most important settings
 // == Modifying:		Read the comments, and
 //						change the code in little pieces.
 //********************************************************************
 // 					If you want to change the layout:
 // 					edit the index.html and/or css.php
 //********************************************************************
 //						Freeware
 //********************************************************************
 // == Date:			07/28/2004
 //********************************************************************
 */
 	// input vars													: "default value";
	$page    = (isset($_REQUEST['page']))    ? $_REQUEST['page']    : "";
	$nick    = (isset($_REQUEST['nick']))    ? $_REQUEST['nick']    : "";
	$pass    = (isset($_REQUEST['pass']))    ? $_REQUEST['pass']    : "";
	$host    = (isset($_REQUEST['host']))    ? $_REQUEST['host']    : "irc.kde.org";
	$chan    = (isset($_REQUEST['chan']))    ? $_REQUEST['chan']    : "#kdevelop";
	$style   = (isset($_REQUEST['style']))   ? $_REQUEST['style']   : "Silver";	// available: Silver, Brown and Default
	$font    = (isset($_REQUEST['font']))    ? $_REQUEST['font']    : "SansSerif";
	$font2   = (isset($_REQUEST['font2']))   ? $_REQUEST['font2']   : "14";
	$smileys = (isset($_REQUEST['smileys'])) ? $_REQUEST['smileys'] : "";

	// not existing page chosen -> set page one
	if ($page != "advanced" && $page != "chat") $page = "";

	$nick = str_replace(" ", "_", $nick);	// replaces spaces on nicknames
	$nick = ereg_replace("^[0-9]*[0-9]", "", $nick);	// deletes numbers on the beginning of nicknames

	// replace umlauts on nicknames
/*
	$nick = str_replace("ä", "ae", $nick);
	$nick = str_replace("ö", "oe", $nick);
	$nick = str_replace("ü", "ue", $nick);
*/

	$pass         = "/msg nickserv identify ".$pass;	// nickserv identify syntax
	$channel      = "/join ".$chan;		// join chan syntax
	$name         = "PJIRC applet user on www.kdevelop.org";	// full name
	$quit_message = "KDevelop is the best!";

	$title    = "KDevelop - IRC Chat-Applet";	// page title
	$headline = "Welcome to the KDevelop PJIRC Chat-Applet";
	$chat_headline = "Support Live Chat";

	$language = "english";
	$langEx   = "lng";	// language files must be for example english.lng and pixx-english.lng

	$method = "POST";	// get or post: method to transfer the input vars of the Login Front

	// true or false
	$buttons = true;	// turn on/off the away/back buttons

	$imgpath = "img";	// image path
	$imgEx   = ".gif";	// image Extension
	$logo    = "../../graphics/theme3/KDevelopBanner.png";	// logo filename
	$link    = "/";		// logo link

	function logo() {
		global $logo, $imgpath, $link, $chat_headline;
		print "<div style=\"float: right;  font-size: 23px;\">".$chat_headline."</div>\n";
		$logo = $imgpath."/".$logo;
		$size = GetImageSize($logo);
		$alt  = "KDevelop homepage logo";
		print "<a href=\"".$link."\" target=\"_blank\">";
		print "<img src=\"".$logo."\" ".$size[3]." style=\"margin-bottom: 10px; margin-top: -5px; margin-left: -10px;\" border=\"0\" alt=\"".$alt."\"  title=\"back to KDevelop homepage\"></a>\n";
		print "<div class=\"color\" style=\"border: 1px solid black; padding: 5px; margin-bottom: 10px;\">This is a web based Java applet for the #kdevelop channel at KDE's IRC server. It only works with Java and Javascript enabled versions of <strong>Konqueror &gt;= 3.4</strong>, <strong>Mozilla</strong>, <strong>Netscape</strong>, <strong>IE</strong> and <strong>Opera</strong>. For those who would like to connect directly to the IRC server, the address is <em>irc.kde.org</em> port <em>6667</em> channel <em>#kdevelop</em>.</div>\n";
	}

 // ================================================================================== \\
 // == Smileys																		== \\
 // == Syntax: array("filename", "1st Smiley Definition", "2nd Smiley Definition")	== \\
 // == etc...																		== \\
 // == Filename must be without the extension (.gif)								== \\
 // ================================================================================== \\
	$smiley = array(
		array("sourire", ":)", ":-)"),
		array("content", ":D", ":-D"),
		array("OH-2", ":-O"),
		array("OH-1", ":o"),
		array("langue", ":P", ":-P"),
		array("clin-oeuil", ";)", ";-)"),
		array("triste", ":("),
		array("OH-3", ":|", ":-|"),
		array("pleure", ":\'("),
		array("rouge", ":$", ":-$"),
		array("cool", "(H)", "(h)"),
		array("enerve1", ":-@"),
		array("enerve2", ":@"),
		array("roll-eyes", ":s", ":-S")
	);

 // ========================================================================== \\
 // == Popupmenu															== \\
 // == Syntax: array("Command Name", "1st IRC command", "2nd IRC command")	== \\
 // == etc...																== \\
 // ========================================================================== \\
	$popupmenu = array(
		array("Whois", "/Whois %1"),
		array("Query", "/Query %1"),
		array("Ban", "/mode %2 -o %1", "/mode %2 +b %1"),
		array("Kick + Ban", "/mode %2 -o %1", "/mode %2 +b %1", "/kick %2 %1"),
		array("--"),
		array("Op", "/mode %2 +o %1"),
		array("DeOp", "/mode %2 -o %1"),
		array("HalfOp", "/mode %2 +h %1"),
		array("DeHalfOp", "/mode %2 -h %1"),
		array("Voice", "/mode %2 +v %1"),
		array("DeVoice", "/mode %2 -v %1"),
		array("--"),
		array("Ping", "/CTCP PING %1"),
		array("Version", "/CTCP VERSION %1"),
		array("Time", "/CTCP TIME %1"),
		array("Finger", "/CTCP FINGER %1"),
		array("--"),
		array("DCC Send", "/DCC SEND %1"),
		array("DCC Chat", "/DCC CHAT %1")
	);

 // ================================================================================================== \\
 // == Colorset																						== \\
 // == Syntax: array("color code of 1st colorset (silver)", "color code of 2nd colorset (brown)")	== \\
 // == etc...																						== \\
 // ================================================================================================== \\
	$colorset = array(
		//	silver [0]	brown [1]
		array("DEE3E7", "887766"),	// color 0
		array("000000", "000000"),	// color 1
		array("DEE3E7", "221100"),	// color 2
		array("DEE3E7", "221100"),	// color 3
		array("D1D7DC", "FFEEDD"),	// color 4
		array("DEE3E7", "D3CBBA"),	// color 5
		array("E5E5E5", "E4DDCC"),	// color 6
		array("D1D7DC", "FFEEDD"),	// color 7
		array("FFA34F", "FFA34F"),	// color 8
		array("000000", "D3CBBA"),	// color 9
		array("EFEFEF", "BBAA99"),	// color 10
		array("FFA34F", "D3CBBA"),	// color 11
		array("599FCB", "DD5555"),	// color 12
		array("DEE3E7", "D3CBBA"),	// color 13
		array("DEE3E7", "D3CBBA"),	// color 14
		array("DEE3E7", "EEDDCC")	// color 15
	);

	switch($style) {
		case "Silver":
			$which = 0;
			$color = $colorset[6][$which];
		break;
		case "Brown":
			$which = 1;
			$color = $colorset[6][$which];
		break;
		case "Default":
			$color = "084079";
		break;
	}

 // ================================== \\
 // == Comments (color definitions)	== \\
 // ================================== \\
	$comment = array(
		array("<!-- Button Highlight / Popup & Close Button Text & Higlight / Scrollbar Highlight -->"),
		array("<!-- Button Border & Text : ScrollBar Border & arrow : Popup & Close button Border : User List border & Text & icons -->"),
		array("<!-- Popup & Close button shadow -->"),
		array("<!-- Scrollbar shadow -->"),
		array("<!-- Scrollbar de-light (3D Dim colour) -->"),
		array("<!-- foreground : Buttons Face : Scrollbar Face -->"),
		array("<!-- background : Header : Scrollbar Track : Footer background -->"),
		array("<!-- selection : Status & Window button active colour -->"),
		array("<!-- event Color  -->"),
		array("<!-- close button -->"),
		array("<!-- voice icon  -->"),
		array("<!-- operator icon  -->"),
		array("<!-- halfoperator icon -->"),
		array("<!-- male ASL -->"),
		array("<!-- female ASL -->"),
		array("<!-- unknown ASL -->")
	);
?>
