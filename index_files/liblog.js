String.prototype.replaceAll = function(search, replace)
{
	return this.split(search).join(replace);
}

function FP_preloadImgs() {
	var d = document, a = arguments;
	if(!d.FP_imgs)
	d.FP_imgs = new Array();
	for(var i = 0; i < a.length; i++) {
		d.FP_imgs[i] = new Image;
		d.FP_imgs[i].src = a[i];
	}
}

function JS_ShowRounds() {
	var objUI = document.getElementById("rounds");
	var objLink = document.getElementById("link_UI");
	var strHTML = objLink.firstChild.innerHTML;
	
	if (objUI.style.display == "none") {
		objUI.style.display = "block";
		strHTML = strHTML.replaceAll("▼", "▲");
	}
	else {
		objUI.style.display = "none";
		strHTML = strHTML.replaceAll("▲", "▼");
	}
	
	objLink.firstChild.innerHTML = strHTML;
}

var cookies = new Object();
function extractCookies() {
	var name, value;
	var beginning, middle, end;
	for (name in cookies) { // if there are any entries currently, get rid of them 
		cookies = new Object();
		break;
	}
	beginning = 0;  // start at beginning of cookie string
	while (beginning < document.cookie.length) {
		middle = document.cookie.indexOf('=', beginning);  // find next =
		end = document.cookie.indexOf(';', beginning);  // find next ;
		
		if (end == -1)  // if no semicolon exists, it's the last cookie
			end = document.cookie.length;
		if ( (middle > end) || (middle == -1) ) { // if the cookie has no value... 
			name = document.cookie.substring(beginning, end);
			value = "";
		}
		else { // extract its value
			name = document.cookie.substring(beginning, middle);
			value = document.cookie.substring(middle + 1, end);
		}
		cookies[name] = unescape(value);  // add it to the associative array
		beginning = end + 2;  // step over space to beginning of next cookie
	}
}

function getElementsByNameIE(tag, name) {
     
     var elem = document.getElementsByTagName(tag);
     var arr = new Array();
     for(i = 0,iarr = 0; i < elem.length; i++) {
          att = elem[i].getAttribute("name");
          if(att == name) {
               arr[iarr] = elem[i];
               iarr++;
          }
     }
     return arr;
}


function ChangeIMG(button) {
	var arrImgs=getElementsByNameIE("div","img_");
	var arrNotImgs=getElementsByNameIE("div","_img");
	var y = (new Date()).getFullYear() + 1;
	var arrImgsOld=document.getElementsByName("img_");
	if (button.value=="Show images") {
		button.value="Hide images";
		document.cookie = "img=Show images; expires=Monday, 01-Sep-" + y + " 10:0:0 GMT";
		
		strVis="block";
		strNotVis="none";
	} 
	else {
		button.value="Show images";
		document.cookie = "img=Hide images; expires=Monday, 01-Sep-" + y + " 10:0:0 GMT";
		strVis="none";
		strNotVis="block";
	} 
	
	for (i=0; i<arrImgs.length; i++) {
		arrImgs[i].style.display=strVis;
	}
	
	for (i=0; i<arrNotImgs.length; i++) {
		arrNotImgs[i].style.display=strNotVis;
	}
	
	for (i=0; i<arrImgsOld.length; i++) {
		arrImgsOld[i].style.display=strVis;
	}
	
}

function ChangeStatType(button) {
	var arrStat=getElementsByNameIE("div","full_stat");
	arrStat = arrStat.concat(getElementsByNameIE("tr","full_stat"));
	var y = (new Date()).getFullYear() + 1;	
	if (button.value=="Show full statistics") {
		button.value="Hide full statistics";
		document.cookie = "full_stat=Show full statistics; expires=Monday, 01-Sep-" + y + " 10:0:0 GMT";
		
		strVis="";
	} 
	else {
		button.value="Show full statistics";
		document.cookie = "full_stat=Hide full statistics; expires=Monday, 01-Sep-" + y + " 10:0:0 GMT";
		strVis="none";
	} 
	
	for (i=0; i<arrStat.length; i++) {
		arrStat[i].style.display=strVis;
	}
}

function ChangeSkin(value) {
	document.getElementById("skin_css").href=value;
	//document.cookie = "skin="+value+"; expires=Monday, 01-Sep-2012 10:0:0 GMT";
}
		
function LoadLog() {
	var arrImgs=getElementsByNameIE("div","img_");
	var arrNotImgs=getElementsByNameIE("div","_img");
	var arrStat=getElementsByNameIE("div","full_stat");
	arrStat = arrStat.concat(getElementsByNameIE("tr","full_stat"));
	var arrImgsOld=document.getElementsByName("img_");
	var objSelectImgButton = document.getElementById("select_img");
	var objSelectStatTypeButton = document.getElementById("select_stat_type");
	var objSelectSkin = document.getElementById("select_skin");
	
	extractCookies();
	if (cookies["img"] == "Show images") {
		objSelectImgButton.value="Hide images";
		strVis="block";
		strNotVis="none";
	} 
	else {
		objSelectImgButton.value="Show images";
		strVis="none";
		strNotVis="block";
	} 
	
	if (cookies["full_stat"] == "Show full statistics") {
		objSelectStatTypeButton.value="Hide full statistics";
		strStatType="";
	} 
	else {
		objSelectStatTypeButton.value="Show full statistics";
		strStatType="none";
	} 
	
	for (i=0; i<arrImgs.length; i++) {
		arrImgs[i].style.display=strVis;
	}
	
	for (i=0; i<arrNotImgs.length; i++) {
		arrNotImgs[i].style.display=strNotVis;
	}
	
	for (i=0; i<arrImgsOld.length; i++) {
		arrImgsOld[i].style.display=strVis;
	}
	
	for (i=0; i<arrStat.length; i++) {
		arrStat[i].style.display=strStatType;
	}
	/*value = cookies["skin"]
	if (cookies["skin"]) {
		document.getElementById("skin_css").href=value;
		objSelectSkin.value = value;
	}*/
}