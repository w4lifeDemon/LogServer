var gc_Header = 'index_files/abox/header.png';
var gc_HeaderA = 'index_files/abox/header_a.png';
var gc_RowA = 'index_files/abox/row_a.png';
var gc_Row1 = 'index_files/abox/row_1.png';
var gc_Row2 = 'index_files/abox/row_2.png';
var gc_IconDelete = 'index_files/abox/icon_delete.png';
var gc_IconEdit = 'index_files/abox/icon_edit.png';
var gc_IconPub0 = 'index_files/abox/icon_pub_0.png';
var gc_IconPub1 = 'index_files/abox/icon_pub_1.png';

document.write('<img style="display: none"' + gc_HeaderA + '">');
document.write('<img style="display: none"' + gc_RowA + '">');

function CreateMegaABox() {
	for (var intABox in g_arrMegaABox) {
		CreateABox(intABox);
	}
}

function CreateABox(intABox) {
	strTable = CreateBoxTable(intABox);
	strDiv= '<div id="abox_' + intABox + '" width="100%" align="center">' + strTable + '</div>';
	document.write(strDiv);
}

function CreateBoxTable(intABox) {
	var strTable = '';
	
	strTable += "<table border='1' bordercolor='#222222' style='border-collapse: collapse' cellpadding='4' width='640'>";
	strTable += '<tr height="28">';
	strTable += '<td align="center" background="' + gc_Header + '" width="20"><font class="abox_text">' + '#' + '</font></td>';
	
	for (var j in g_arrMegaABox[intABox]['header']) {
		if (g_arrMegaABox[intABox]['visible'][j]) {
			if (g_arrMegaABox[intABox]['sort'][j])
				strTable += '<td align="center" width="' + g_arrMegaABox[intABox]['width'][j] + '" background="' + gc_Header + '" onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(' + intABox + ', ' + j + ')"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' + g_arrMegaABox[intABox]['header'][j] + '</font></td>';
			else
				strTable += '<td align="center" width="' + g_arrMegaABox[intABox]['width'][j] + '" background="' + gc_Header + '"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' + g_arrMegaABox[intABox]['header'][j] + '</font></td>';
		}
	}
	
	strTable += '</tr>';
	
	SortABox(intABox);
	
	var strRowBG = gc_Row1;
	for (var i = 0; i < (g_arrMegaABox[intABox]['data']).length; i++) {
		strTable += '<tr height="28" onmouseover="ActivateRow(this)" onmouseout="DeactivateRow(this)" src="' + strRowBG + '" background="' + strRowBG + '">';
        var Id = i + 1;
        strTable += '<td align="right"><font class="abox_text">' + Id + '</font></td>';
		for (var j in g_arrMegaABox[intABox]['data'][i]) {
			if (g_arrMegaABox[intABox]['visible'][j]) {
				if (g_arrMegaABox[intABox]['header'][j] == 'Date') {
					objDate = new Date(g_arrMegaABox[intABox]['data'][i][j] * 1000);
					if (objDate.getDate() < 10) strDay = '0' + objDate.getDate();
					else strDay = objDate.getDate();
					if (objDate.getMonth() + 1 < 10) strMonth = '0' + (objDate.getMonth() + 1);
					else strMonth = (objDate.getMonth() + 1);
					strYear = objDate.getFullYear().toString();
					strYear = strYear.substr(2, strYear.length);
					strValue = strDay + "/" + strMonth + "/" + strYear;
                    if (!g_arrMegaABox[intABox]['data'][i][j]){strValue = '';}
				}
				else
					strValue = g_arrMegaABox[intABox]['data'][i][j];
				
				strFull = '';
				if (strValue.toString().length > g_arrMegaABox[intABox]['maxlen'][j]) {
					strFull = strValue;
					strValue = strValue.substr(0, g_arrMegaABox[intABox]['maxlen'][j]) + '...';
				}
				
				switch (g_arrMegaABox[intABox]['header'][j]) {
					case 'Title':
						strValue = '<a href="index.php?id=' + g_arrMegaABox[intABox]['data'][i][0] + '" title="' + strFull + '" target="_blank"><font size="2">' + strValue + '</font></a>';
						break;
					case 'TitleWar':
						strValue = '<a href="index.php?warId=' + g_arrMegaABox[intABox]['data'][i][0] + '" title="' + strFull + '" target="_blank"><font size="2">' + strValue + '</font></a>';
						break;
					case 'Views':
						strClass = 'abox_text';
						if (strValue >= 100) strClass = 'abox_text_yellow';
						if (strValue >= 200) strClass = 'abox_text_green';
						if (strValue >= 400) strClass = 'abox_text_red';
						strValue = '<font class="' + strClass + '" title="' + strFull + '">' + strValue + '</font>';
						break;
					case 'Del':
						strValue = '<a href="javascript:DeleteLog(' + intABox + ', ' + i + ', \'' + g_arrMegaABox[intABox]['data'][i][j] + '\')" title="Delete" alt="Delete"><img src="' + gc_IconDelete + '" width="16" border="0"></a>';
						break;
					case 'DelWar':
						strValue = '<a href="javascript:DeleteLogWar(' + intABox + ', ' + i + ', \'' + g_arrMegaABox[intABox]['data'][i][j] + '\')" title="Delete" alt="Delete"><img src="' + gc_IconDelete + '" width="16" border="0"></a>';
						break;
					case 'Edit':
						if (strValue == 1)
							strValue = '<a href="index.php?show=edit&log_id=' + g_arrMegaABox[intABox]['data'][i][10] + '" title="Edit" alt="Delete"><img src="' + gc_IconEdit + '" width="16" border="0"></a>';
						else strValue = '';
						break;
					case 'Losses':
						strValue = NumberToString2(g_arrMegaABox[intABox]['data'][i][j]);
						break;
					case 'ProfitA':
						strValue = NumberToString2(g_arrMegaABox[intABox]['data'][i][j]);
						break;
					case 'ProfitD':
						strValue = NumberToString2(g_arrMegaABox[intABox]['data'][i][j]);
						break;
					case 'Pub':
						if (strValue == 1) {strIcon = gc_IconPub1; intPub = 0; strPub = "Make private"}
						else {strIcon = gc_IconPub0; intPub = 1; strPub = "Make public"};
						strValue = "";
						strValue = '<a href="javascript:ChangePub(' + intABox + ', ' + i + ', \'' + g_arrMegaABox[intABox]['data'][i][0] + '\', ' + intPub + ')" title="' + strPub + '"><img src="' + strIcon + '" width="16" border="0" alt="' + strValue + '"></a>';
						//strValue = '<font class="' + strClass + '" title="' + strFull + '">' + strValue + '</font>';
						break;
					default:
						strValue = '<font class="abox_text" title="' + strFull + '">' + strValue + '</font>';
				}
				
				strAlign = 'left';
				if (g_arrMegaABox[intABox]['align'][j] == 0) strAlign = 'left';
				if (g_arrMegaABox[intABox]['align'][j] == 1) strAlign = 'center';
				if (g_arrMegaABox[intABox]['align'][j] == 2) strAlign = 'right';
				strTable += '<td align="' + strAlign + '">' + strValue + '</td>';
			}
		}
		strTable += '</tr>';
		if (strRowBG == gc_Row1)
			strRowBG = gc_Row2;
		else
			strRowBG = gc_Row1;
	}
	strTable += '</table>';
	return strTable;
}

var g_intSortField = -1;
var g_intSortOrder = 1;
function BoxSort(arrA, arrB) {
	var intResult = 0;
	if (typeof(arrA[g_intSortField]) == 'number') {
		if ((arrA[g_intSortField]) < (arrB[g_intSortField])) intResult = 1 * g_intSortOrder;
		if ((arrA[g_intSortField]) > (arrB[g_intSortField])) intResult = -1 * g_intSortOrder;
	}
	else
		if (typeof(arrA[g_intSortField]) == 'string') {
			if (arrA[g_intSortField].toLowerCase() < arrB[g_intSortField].toLowerCase()) intResult = 1 * g_intSortOrder;
			if (arrA[g_intSortField].toLowerCase() > arrB[g_intSortField].toLowerCase()) intResult = -1 * g_intSortOrder;
		}
	return intResult;
}

function SortABox(intABox) {
	/*if (g_intSortField == -1) {
		g_intSortField = intSortBy;
		g_intSortOrder = 1;
	}
	else {
		g_intSortField = intSortBy;
		if (g_arrMegaABox[intABox]['sort_f'] == g_intSortField) g_intSortOrder *= -1;
		else g_intSortOrder = 1;
	}
	alert(g_intSortOrder);*/
	g_intSortField = g_arrMegaABox[intABox]['sort_f'];
	g_intSortOrder = g_arrMegaABox[intABox]['sort_o'];
	g_arrMegaABox[intABox]['data'] = g_arrMegaABox[intABox]['data'].sort(BoxSort);
}

function StartSort(intABox, intSortBy) {
	g_arrMegaABox[intABox]['sort_f'] = intSortBy;
	(g_arrMegaABox[intABox]['sort_f'] == g_intSortField) ? (g_arrMegaABox[intABox]['sort_o'] *= -1) : (true);
	document.getElementById('abox_' + intABox).innerHTML = CreateBoxTable(intABox);
}

function ActivateHeader(objHeader) {
	objHeader.setAttribute('background', gc_HeaderA);
}

function DeactivateHeader(objHeader) {
	objHeader.setAttribute('background', gc_Header);
}

function ActivateRow(objHeader) {
	objHeader.setAttribute('background', gc_RowA);
}

function DeactivateRow(objHeader) {
	objHeader.setAttribute('background', objHeader.getAttribute('src'));
}

function DeleteLog(strId) {
    if (confirm('Delete Log?')) {
    	SRAX.XSS.post("index.php", "xss=1&del=" + strId, function(strResult){
    		if (strResult == strId) {
    			document.getElementById('abox_' + strId).style.display = "none";
    		}
    		else
    			alert("Error: can't delete log");
    	})
    }
}

function DeleteLogWar(intABox, intLogIndex, strId) {
    if (confirm('Delete War?')) {
    	SRAX.XSS.post("index.php", "xss=2&del=" + strId, function(strResult){
    		if (strResult == strId) {
    			g_arrMegaABox[intABox]['data'].splice(intLogIndex, 1);
    			document.getElementById('abox_' + strId).style.display = "none";
    		}
    		else
    			alert("Error: can't delete war");
    	})
    }
}

function DeleteSpyLog(strId) {
    if (confirm('Delete Spy?')) {
    	SRAX.XSS.post("index.php", "xss=3&del=" + strId, function(strResult){
    		if (strResult == strId) {
    			document.getElementById('abox_' + strId).style.display = "none";
    		}
    		else
    			alert("Error: can't delete log");
    	})
    }
}

function ChangePub(strId) {
    if (document.getElementById('img_pub_' + strId).src.indexOf('icon_pub_1.png') > 1) {
        var intPub = 0;
    } else {
        var intPub = 1;
    }
	SRAX.XSS.post("index.php", "xss=1&cpub=" + strId + "&val=" + intPub, function(strResult){
		if (strResult == strId) {
		    document.getElementById('img_pub_' + strId).src = 'index_files/abox/icon_pub_' + intPub + '.png';
		}
		else
			alert("Error: can't change public attr.");
	})
}

function NumberToString(intNumber) {
	strTmp = intNumber.toString();
	strResult = '';
	strMinus = '';

	if (strTmp.charAt(0) == '-') {
		strTmp = strTmp.replace('-', '');
		strMinus = '-';
	}

	if (strTmp.length > 3) {
		while (strTmp.length > 3) {
			if (strResult == '')
				strResult = strTmp.substr(strTmp.length - 3, 3);
			else
				strResult = strTmp.substr(strTmp.length - 3, 3) + '.' + strResult;
			strTmp = strTmp.substr(0, strTmp.length - 3);
		}
		strResult = strTmp + '.' + strResult;
		return strMinus + strResult;
	}
	else {
		strResult = strTmp;
		return strMinus + strResult;
	}
}

function NumberToString2(intNumber) {
	var strValue;
	var Znak = '';
    if (intNumber < 0) {
        intNumber = intNumber * -1;
        Znak = '-';
    }
	if (intNumber < Math.pow(1000, 1)) {
		strValue = intNumber;
		strClass = 'abox_text';
	}
	else
	if (intNumber < Math.pow(1000, 2)) {
		strValue = (Math.round(intNumber / Math.pow(1000, 1) * 10) / 10) + "K";
		strClass = 'abox_text_yellow';
	}
	else
	if (intNumber < Math.pow(1000, 3)) {
		strValue = (Math.round(intNumber / Math.pow(1000, 2) * 10) / 10) + "KK";
		strClass = 'abox_text_green';
	}
	else
	if (intNumber < Math.pow(1000, 4)) {
		strValue = (Math.round(intNumber / Math.pow(1000, 3) * 10) / 10) + "KKK";
		strClass = 'abox_text_red';
	}

	if (intNumber < 1000000) {
		strClass = 'abox_text';
	}
	else
	if (intNumber < 100000000) {
		strClass = 'abox_text_yellow';
	}
	else
	if (intNumber < 1000000000) {
		strClass = 'abox_text_green';
	}
	else
		strClass = 'abox_text_red';

	return '<font class="' + strClass + '">' + Znak + '' + strValue + '</font>';
}
