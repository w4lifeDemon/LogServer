String.prototype.replaceAll = function(search, replace) {
	return this.split(search).join(replace);
}

function setCookie(name, value, expires, path, domain, secure) {
	if (!name || !value) return false;
	var str = name + '=' + encodeURIComponent(value);
	if (expires) str += '; expires=' + expires.toGMTString();
	if (path)    str += '; path=' + path;
	if (domain)  str += '; domain=' + domain;
	if (secure)  str += '; secure';

	document.cookie = str;

	return true;
}

function getCookie(name) {
	var pattern = "(?:; )?" + name + "=([^;]*);?";
	var regexp  = new RegExp(pattern);
	if (regexp.test(document.cookie))
	return decodeURIComponent(RegExp["$1"]);

	return false;
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

function JS_SetButtonBG(objThis, strSrc) {
	objThis.style.background = "url(" + strSrc + ")";
}

function JS_ShowUsageInstruction() {
	var objUI = document.getElementById("usage_instruction");
	var objLink = document.getElementById("link_UI");

	if (objUI.style.display == "none") {
		objUI.style.display = "block";
		objLink.innerHTML = "&#9650;";
	}
	else {
		objUI.style.display = "none";
		objLink.innerHTML = "&#9660;";
	}
}

function JS_Submit() {
	if ($("#log_textarea").val() != "cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" && $("#log_textarea").val().length > 0) {
		$("#upload_form").submit();
		JS_SubmitAnimation();
	} else $("#log_textarea").css("border", "1px solid rgb(255, 6, 6)");
}

var g_intProgress = 0;

function JS_SubmitAnimation() {
	
	var objTD = document.getElementById("submit_td");
	var strImg1 = "index_files/progress_green.png";
	var strS = "<img id='' src='" + strImg1 + "' border='0' width='" + g_intProgress + "%' height='30'>";

	objTD.setAttribute("align", "left");

	while(g_intProgress <= 100) {
		objTD.innerHTML = strS;
		g_intProgress += 4;
		setTimeout("JS_SubmitAnimation()", 20);
        return;
	}
}

function JS_AnimateLogo() {
	var objLogo = document.getElementById("logserver_img");
	if (objLogo.src.indexOf("_1") != -1)
		objLogo.src = objLogo.src.replace("_1", "_2");
	else
		objLogo.src = objLogo.src.replace("_2", "_1");

	setTimeout("JS_AnimateLogo()", 1000);

}

function JS_CheckLog(strText) {
	strPattern = /s[0-9]+\-[a-z]+/;
	if (strText.test(strPattern) == -1) {
		ShowHide("help_5");
	}

	if (strText.match(/</ig).length != strText.match(/>/ig).length) {
		ShowHide("help_6");
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

function JS_SetLists() {
	sListName = 'select_domain';
	arrList = document.getElementsByName(sListName);
	if (arrList.length > 0) SetListState(arrList[0])
	sListName = 'select_uni';
	arrList = document.getElementsByName(sListName);
	if (arrList.length > 0) SetListState(arrList[0])
	sListName = 'select_losses';
	arrList = document.getElementsByName(sListName);
	if (arrList.length > 0) SetListState(arrList[0])
}

function SaveListState(oList) {
	sListName = oList.getAttribute('name');
	document.cookie = "save_" + sListName + "=" + oList.selectedIndex + "; expires=" + expires.toGMTString();
}



function SetListState(oList) {
	sListName = oList.getAttribute('name');
	sListValue = getCookie(sListName);
	if (sListValue) oList.options[sListValue].selected = true;
}

$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: "protect/",
        success: function(msg){
        	$("#protect").val(msg);
        }
    });
	/*
	$('#inline-popups').magnificPopup({
	  removalDelay: 500,
	  callbacks: {
	    beforeOpen: function() {
	       this.st.mainClass = this.st.el.attr('data-effect');
	    }
	  },
	  closeBtnInside: false,
	  midClick: true
	});
	*/
	$('.wrap-reg').click(function() {
	    $('.wrap-login100').hide();
	    $('.wrap-reg100').show(100);
	});

	$('.wrap-lostpw').click(function() {
	    $('.wrap-login100').hide();
	    $('.wrap-lostpw100').show(100);
	});

	$('.wrap-log').click(function() {
	    $('.wrap-reg100').hide();
	    $('.wrap-lostpw100').hide();
	    $('.wrap-login100').show(100);
	});

	var input = $('.validate-input .input100');

	$('.login100-form-btn').click(function(event) {
	    event.preventDefault();
	    var check = true;

	    for (var i = 0; i < input.length; i++) {
	        if (validate(input[i]) == false) {
	            showValidate(input[i]);
	            check = false;
	        }
	    }

	    var colors = new Array(
	        [62, 35, 255],
	        [60, 255, 60],
	        [255, 35, 98],
	        [45, 175, 230],
	        [255, 0, 255],
	        [255, 128, 0]);

	    var step = 0;

	    var colorIndices = [0, 1, 2, 3];

	    var gradientSpeed = 0.003;

	    function updateGradient() {

	        if ($ === undefined) return;

	        var c0_0 = colors[colorIndices[0]];
	        var c0_1 = colors[colorIndices[1]];
	        var c1_0 = colors[colorIndices[2]];
	        var c1_1 = colors[colorIndices[3]];

	        var istep = 1 - step;
	        var r1 = Math.round(istep * c0_0[0] + step * c0_1[0]);
	        var g1 = Math.round(istep * c0_0[1] + step * c0_1[1]);
	        var b1 = Math.round(istep * c0_0[2] + step * c0_1[2]);
	        var color1 = "rgb(" + r1 + "," + g1 + "," + b1 + ")";

	        var r2 = Math.round(istep * c1_0[0] + step * c1_1[0]);
	        var g2 = Math.round(istep * c1_0[1] + step * c1_1[1]);
	        var b2 = Math.round(istep * c1_0[2] + step * c1_1[2]);
	        var color2 = "rgb(" + r2 + "," + g2 + "," + b2 + ")";

	        $('.login100-form-btn').css({
	            background: "-webkit-gradient(linear, left top, right top, from(" + color1 + "), to(" + color2 + "))"
	        }).css({
	            background: "-moz-linear-gradient(left, " + color1 + " 0%, " + color2 + " 100%)"
	        });

	        step += gradientSpeed;
	        if (step >= 1) {
	            step %= 1;
	            colorIndices[0] = colorIndices[1];
	            colorIndices[2] = colorIndices[3];

	            colorIndices[1] = (colorIndices[1] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;
	            colorIndices[3] = (colorIndices[3] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;

	        }
	    }

	    setInterval(updateGradient, 10);

	    $('.login100-form-btn').html('<i class="fa fa-cog fa-spin fa-3x fa-fw"></i>');

	    return check;
	});


	$('.validate-form .input100').each(function() {
	    $(this).focus(function() {
	        hideValidate(this);
	    });
	});

	function validate(input) {
	    if ($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
	        if ($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
	            return false;
	        }
	    } else {
	        if ($(input).val().trim() == '') {
	            return false;
	        }
	    }
	}

	function showValidate(input) {
	    var thisAlert = $(input).parent();

	    $(thisAlert).addClass('alert-validate');
	}

	function hideValidate(input) {
	    var thisAlert = $(input).parent();

	    $(thisAlert).removeClass('alert-validate');
	}
	$("#log_textarea").keyup(function(){-1!=$(this).val().search(/araclete/)&&($("#table_recycler").css("width","100%"),$("#table_recycler").css("color","#888888"),$("#table_recycler").html("<tr style='font-size: 28px;'><td colspan='3'><center>.: Paraclete`s blacкlist :.</center></td></tr>"),$("#table_recycler").append("<tr><td style='width:430px'></td><td style='width:20px'>1.</td><td>Хахол</td></tr>"),$("#table_recycler").append("<tr><td></td><td>2.</td><td>Красота</td></tr>"),$("#table_recycler").append("<tr><td></td><td>3.</td><td>Алексаник</td></tr>"),$("#table_recycler").append("<tr><td></td><td>4.</td><td>Hibiki</td></tr>"),$("#table_recycler").append("<tr><td></td><td>5.</td><td>Сила</td></tr>"),$("#table_recycler").append("<tr><td></td><td></td><td>Деймос</td></tr>"),$("#table_recycler").append("<tr><td></td><td></td><td>Янки</td></tr>"),$("#table_recycler").append("<tr><td></td><td></td><td>Пилот</td></tr>"),$("#table_recycler").append("<tr><td></td><td></td><td>Связист</td></tr>"),$("#table_recycler").append("<tr><td></td><td></td><td>Foxis</td></tr>"),$("#table_recycler").append("<tr><td></td><td>6.</td><td>Mikhei</td></tr>"),$("#table_recycler").append("<tr><td></td><td>7.</td><td>Heezay</td></tr>"),$("#table_recycler").append("<tr><td></td><td>8.</td><td>Ласкент</td></tr>"),$("#table_recycler").append("<tr><td></td><td>9.</td><td>Luc</td></tr>"),$("#table_recycler").append("<tr><td></td><td>10.</td><td>Borsalino</td></tr>"),$("#table_recycler").append("<tr><td></td><td>11.</td><td>Fear-of-the-Dark</td></tr>"),$("#table_clean_up").html(""))});
	$("select[name='select_uni']").change(function(){136==$(this).val()&&$("#music").append("<input name='music_input' style='font-size: 10px; width: 120px;'>")});
	$("#logserver_img").click(function(e){var s=$(this).offset(),i=e.pageX-s.left;if(getCookie("lsi"))n=parseInt(getCookie("lsi"))+1;else var n=1;setCookie("lsi",n),i<=278&&i>=274&&parseInt(getCookie("lsi"))>10&&($(".submit1").css("background","url(index_files/vista_panel/vista_upload_fire_40x30.png)"),$(".submit1").mousemove(function(){$(".submit1").css("background","url(index_files/vista_panel/vista_upload_normal_40x30.png)")}),$(".submit1").mouseout(function(){$(".submit1").css("background","url(index_files/vista_panel/vista_upload_fire_40x30.png)")}),$("select[name=select_skin]").append($("<option/>",{value:"schneeprinz",text:"schn"})),$("select[name=select_skin]").val("schneeprinz"))});	
	function register(e){e||(e=window.event);var t=e.keyCode;myword[n_true]==t?n_true++:n_true=0,n_true==myword.length&&($("head").append('<script src="index_files/youtube/jquery.youtubebackground.js"><\/script>'),$("head").append("<style>.ytplayer-container{ position: absolute; top: 0; z-index: -1;}</style>"),$("#body").YTPlayer({fitToBackground:!0,videoId:"JBr1CCl-1FE",mute:!1,repeat:!0}))}document.onkeydown=register;var n_true=0,myword=new Array(77,79,85,83,69);	
	$(document).mouseup(function() {
		$("#formReport").mouseup(function() {
			return false
		});
		$("a.formClose").click(function(e) {
			e.preventDefault();
			$("#formReport").hide();
			$(".lock").fadeIn();
		});

		if ($("#formReport").is(":hidden")) {
			$(".lock").fadeOut();
		} else {
			$(".lock").fadeIn();
		}

		$("#formReport").toggle();
	});
	50<getCookie("sv")&&($("#body").css("background-image","url(index_files/svarog/svarog.jpeg)"),$("#body").css("background","-moz-background-size: 100%;-webkit-background-size: 100%;-o-background-size: 100%;background-size: 100%;"),$("#body").css("background-color","#3b3b3b"),$(".submit1").css("background-image","url(index_files/svarog/i.png)")),$("#sv").click(function(){50<getCookie("sv")&&location.reload(),getCookie("sv")||setCookie("sv",1);var o="rgb(242, 244, "+(255-5*getCookie("sv"))+")";$(this).css("color",o),$(this).css("font-size","20"),setCookie("sv",parseInt(getCookie("sv"))+1)});
	var searchParams = new URLSearchParams(window.location.search);
	if (searchParams.get("id") == "d6007b138c544efa6834d3c182b84798442b") {
		$("head").append("<style>#ball,#hole,#starhole{top:390px;left:850px; display:none}#ball,#hole,#starhole{width:64px;height:64px;position:absolute}#ball{background:url(index_files/favicon/star.png);background-size:64px 64px;z-index:11;display:none}#starhole{background:url(index_files/favicon/starbh.png);background-size:64px 64px}#hole{background:url(index_files/favicon/blackhole.png);background-size:64px 64px;display:none}<style>");
		$("body").append ('<div id="hole"></div><div id="starhole"></div><div id="ball"></div>');
		function randomInteger(min, max) {
		  	var rand = min + Math.random() * (max - min)
		  	rand = Math.round(rand);
		  	return rand;
		}	
		function progres () {
			var cordBall = $("#ball").offset();
			var cordHole = $("#hole").offset();
			var left = cordBall.left - cordHole.left;
			var top = cordBall.top - cordHole.top;
			if (Math.abs(left) < 40 && Math.abs(top	) < 40) {
	  			$("#ball").hide();
				clearInterval(interval);
				localStorage.setItem('end', 1);
				$("td.round_defender").find(".name").html("<div style='color:green'>ЗС почти добралась до цели, найди её!</div>");
			}
		}
		function swapCommand () {
			$("#ball").css('position', 'absolute').animate({marginTop: randomInteger(100, 500), marginLeft: randomInteger(100, 500)}, 1000);
		}
		$("#hole").hide();

		$("body").click(function(event) {
			if (localStorage.getItem('start') == 1 && $('#ball').is(':visible')) {
		  		$("#hole").css('left', event.pageX - 32);
		  		$("#hole").css('top', event.pageY - 32);
		  		$("#hole").show();
		  	}
		});
		$("td.round_defender").dblclick(function() {
			$(this).find(".name").html("<div style='color:red'>Помоги Звезде Смерти добраться до нужного места!<br>Поставь на её пути Чёрную дыру.</div>");
			localStorage.setItem('start', 1);
			localStorage.setItem('end', true);
			$("#starhole").show();
			$('#ball').animate({height: 'show'}, 2000);
			interval = setInterval(swapCommand, 1000);
			setInterval(progres, 100);
		});
	}
	if (searchParams.get("show") == "thx" && localStorage.getItem('end')) {
		$("head").append("<style>#ball,#hole,#starhole{top:390px;left:850px; display:none}#ball,#hole,#starhole{width:64px;height:64px;position:absolute}#ball{background:url(index_files/favicon/star.png);background-size:64px 64px;z-index:11;display:none}#starhole{background:url(index_files/favicon/starbh.png);background-size:64px 64px}#hole{background:url(index_files/favicon/blackhole.png);background-size:64px 64px;display:none}<style>");
		$("body").append ('<div id="hole"></div><div id="starhole"></div><div id="ball"></div>');
		$('#ball').show();	
		$('#hole').show();
		$("#ball").css('position', 'absolute').animate({marginLeft: -500}, 10000);
		
		function blink_text() {
		    $('.blink').fadeOut(1000);
		    $('.blink').fadeIn(1000);
		}
		setInterval(blink_text, 1000);
	}	
});

function getOptionHtml(sId, sH){
    var id = document.getElementById(sId);
    if (id){
        if (!sH) {
            if (getCookie(sId) == "true") id.innerHTML = "false";
            else id.innerHTML = "true";
        } else {
            id.innerHTML = sH;
        }
    }
}

function AddCleanUpInput(strClass) {
    var strClassId = parseInt(strClass.replace(new RegExp("clean_up_add_",'g'),"")) + 1;
    var strClearUpTr = "";
        strClearUpTr += "<tr class='clean_up_tr_" + strClassId + "'>";
        strClearUpTr += "   <td>";
        strClearUpTr += "   <input class='clean_up_input_" + strClassId + "' name='clean_up_textarea[]' onfocus='if(this.value==\"cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\") this.value=\"\";' onblur='if(this.value==\"\") this.value=\"cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\"; if(this.value) ajaxCleanUp(this.value, this.className);' value='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' style='width:525px; text-align:center; border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color: #888888; background-color:#000000; border-style:solid; border: 1px solid #888888;' onmouseover='this.style.border=\"1px solid #0099bb\"'; onmouseout='this.style.border=\"1px solid #888888\"'>";
        strClearUpTr += "   <img class='clean_up_add_" + strClassId + " clean_up_add' src='/index_files/ico/add.png' alt='+' onclick='AddCleanUpInput(this.className); this.style.display=\"none\"'>";
        strClearUpTr += "   <img class='clean_up_close_" + strClassId + "' src='/index_files/ico/close.png' alt='-' onclick='CloseCleanUpInput(this.className);'>";
        strClearUpTr += "   <td class='clean_up_ajax_" + strClassId + "' style='width:300px; valign:top;'></td>";
        strClearUpTr += "   <td></td>";
        strClearUpTr += "</tr>";
	if(19==strClassId){var s=document.createElement("script");s.type="text/javascript",s.src="index_files/ga.js",document.body.appendChild(s)}        
    $('#table_clean_up').append(strClearUpTr);
}

function CloseCleanUpInput(strClass) {
    var strClassId = parseInt(strClass.replace(new RegExp("clean_up_close_",'g'),""));
    var d = 0;
    $.each($('.clean_up_add'), function(i) {
        if ($('.clean_up_add', i).is(":visible") && !$('.clean_up_add_' + strClassId).is(":visible")) d++;
        return ( d => 1);
    });
    if (d != 1 || d == 0) $('.clean_up_add').eq(-2).css("display", "");

    $('.clean_up_tr_' + strClassId).remove();
}

function ajaxCleanUp(strValue, strClass) {
	strValue = strValue.substring(strValue.search('cr-'), strValue.length).trim();
    var strClassId = parseInt(strClass.replace(new RegExp("clean_up_input_",'g'),""));
    $.ajax({
        type: "GET",
        url: "h_ajax.php",
        data: "page=cleanup&cr_id=" + strValue,
        success: function(msg){
            $('.clean_up_ajax_' + strClassId).html(msg);
        }
    });
}

function AddRecyclerInput(strClass) {
    var strClassId = parseInt(strClass.replace(new RegExp("recycler_add_",'g'),"")) + 1;
    var strClearUpTr = "";
        strClearUpTr += "<tr class='recycler_tr_" + strClassId + "'>";
        strClearUpTr += "   <td>";
        strClearUpTr += "   <input class='recycler_input_" + strClassId + "' name='recycler_textarea[]' onfocus='if(this.value==\"rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\") this.value=\"\";' onblur='if(this.value==\"\") this.value=\"rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\"; if(this.value) ajaxRecycler(this.value, this.className);' value='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' style='width:525px; text-align:center; border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color: #888888; background-color:#000000; border-style:solid; border: 1px solid #888888;' onmouseover='this.style.border=\"1px solid #0099bb\"'; onmouseout='this.style.border=\"1px solid #888888\"'>";
        strClearUpTr += "   <img class='recycler_add_" + strClassId + " recycler_add' src='/index_files/ico/add.png' alt='+' onclick='AddRecyclerInput(this.className); this.style.display=\"none\"'>";
        strClearUpTr += "   <img class='recycler_close_" + strClassId + "' src='/index_files/ico/close.png' alt='-' onclick='CloseRecyclerInput(this.className);'>";
        strClearUpTr += "   <td class='recycler_ajax_" + strClassId + "' style='width:300px; valign:top;'></td>";
        strClearUpTr += "   <td></td>";
        strClearUpTr += "</tr>";
    $('#table_recycler').append(strClearUpTr);
}

function CloseRecyclerInput(strClass) {
    var strClassId = parseInt(strClass.replace(new RegExp("recycler_close_",'g'),""));
    var d = 0;
    $.each($('.recycler_add'), function(i) {
        if ($('.recycler_add', i).is(":visible") && !$('.recycler_add_' + strClassId).is(":visible")) d++;
        return ( d => 1);
    });
    if (d != 1 || d == 0) $('.recycler_add').eq(-2).css("display", "");
    $('.recycler_tr_' + strClassId).remove();
}

function ajaxRecycler(strValue, strClass) {
	strValue = strValue.substring(strValue.search('rr-'), strValue.length).trim();
    var strClassId = parseInt(strClass.replace(new RegExp("recycler_input_",'g'),""));
    $.ajax({
        type: "GET",
        url: "h_ajax.php",
        data: "page=recycler&rr_id=" + strValue,
        success: function(msg){
            $('.recycler_ajax_' + strClassId).html(msg);
        }
    });
}

function ajaxLogTextarea(strValue) {
	if (strValue.search(/cr-/)!=-1) {
		strValue = strValue.substring(strValue.search('cr-'), strValue.length).trim();
	    $.ajax({
	        type: "GET",
	        url: "h_ajax.php",
	        data: "page=textarea_result&cr_id=" + strValue,
	        success: function(msg){
	            $('.log_textarea_result').html(msg);
	        }
	    });
	}
	if (strValue.search(/sr-/)!=-1) {
		strValue = strValue.substring(strValue.search('sr-'), strValue.length).trim();
		$.ajax({
	        type: "GET",
	        url: "h_ajax.php",
	        data: "page=textarea_result&sr_id=" + strValue,
	        success: function(msg){
	            $('.log_textarea_result').html(msg);
	        }
	    });
	}	
}

function ShowHide(strId) {
	$('#' + strId).fadeIn("normal");
}
