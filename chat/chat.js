var english = 0;
var german  = 1;

var lang = english;

var ltxt = new Array();

ltxt[ 0] = ["is away!","ist weg!"];
ltxt[ 1] = ["Reason:","Grund:"];
ltxt[ 2] = ["is back","ist zurück"];
ltxt[ 3] = ["from:","von:"];
ltxt[ 4] = ["Guest","Gast"];
ltxt[ 5] = ["away time","Abwesenheit"];
ltxt[ 6] = ["you are already away!","Du bist bereits abwesend!"];
ltxt[ 7] = ["you are not away!","Du bist nicht abwesend!"];

var mtime;
var jtime;
var lev = 0;
var isaway = false;

function chkForm() {
	if(document.login.chan.value == "") {
		alert("Please enter a channel!");
		document.login.chan.focus();
		return false;
	}
	if(document.login.host.value == "") {
		alert("Please enter an IRC server!");
   		document.login.host.focus();
   		return false;
  	}
  	RandomNick();
}

function RandomNick() {
	if(document.login.nick.value == "")  {
		document.login.nick.value = ltxt[4][lang] + Math.round(Math.random()*1000);
		return true;
	}
}

function smiley(symbol) {
	document.pjirc.setFieldText(document.pjirc.getFieldText()+' '+symbol);
	document.pjirc.requestSourceFocus();
}

function maway(away_reason,nick) {
	if (!isaway) {
		txt = "/me " + ltxt[0][lang];
		if (away_reason != "") txt += " " + ltxt[1][lang] + " " + away_reason;
		document.pjirc.sendString(txt);
		document.pjirc.sendString("/away " + away_reason);
		document.pjirc.sendString("/nick " + nick +"|away");
		document.pjirc.requestSourceFocus();
		mtime = new Date();
	} else alert(ltxt[6][lang]);
	isaway = true;
}

function mback(away_reason,nick) {
	if (isaway) {
		jtime = new Date(); lev = 0; mins = 0; hours = 0;
		secs = Math.round((Date.parse(jtime)-Date.parse(mtime))/1000);
		if (secs>59) {mins = parseInt(secs/60); secs %= 60; lev=1;}
		if (lev==1 && mins>59) {hours = parseInt(mins/60); mins %= 60; lev=2;}

		awaytxt = ". "+ltxt[5][lang] + ": ";
		if (lev==2) awaytxt += hours+"h, ";
		if (lev==1) awaytxt += mins+"min, ";
		awaytxt += secs+"sec";

		txt = "/me " + ltxt[2][lang];
		if (away_reason != "") txt += " " + ltxt[3][lang] + " " + away_reason;
		document.pjirc.sendString(txt + awaytxt + ".");
		document.pjirc.sendString("/away ");
		document.pjirc.sendString("/nick " + nick);
		document.pjirc.requestSourceFocus();
	} else alert(ltxt[7][lang]);
	isaway = false;
}