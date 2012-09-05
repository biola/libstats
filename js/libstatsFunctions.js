// Searches this document's cookies for a key. null if there's no match.
function getValueFromCookie(key) {
	var allCookies = document.cookie.split(";");
	var fullKey = key+"=";
	var value = null;
	for (var i = 0; i < allCookies.length; i++) {
		var reg = /\s*/g;
		var myCookie = allCookies[i].replace(reg, "");
		if (myCookie.indexOf(fullKey) == 0) {
			value = myCookie.substring(fullKey.length, myCookie.length);
		}
		
	}
	return value;
}

// Checks for and creates required cookies.
function checkCookies() {
    var disp = getValueFromCookie("disp");
    var ret = new Array();
    ret["i"] = 0;
    ret["q"] = 0;
    if (disp) {
        var dOpts = disp.split("|");
        for (var i = 0; i < dOpts.length; i++) {
            var key = dOpts[i].substring(0,1);
            var val = dOpts[i].slice(-1);
            ret[key] = val;
        }
    }
    return ret;
}

function saveDisplayOpts(opts) {
    var optStr = "i:"+opts["i"]+"|"+"q:"+opts["q"];
    var expDate = new Date();
    expDate.setFullYear(2037);
    cookieStr = "disp="+optStr+"; expires="+expDate.toGMTString();

    document.cookie = cookieStr;
}

function setDisplayCheckboxes(opts) {
    var qBox = document.getElementById("qShow");
    var iBox = document.getElementById("iShow");
    
    if (opts["i"] == 1) { iBox.checked = true; }
    if (opts["q"] == 1) { qBox.checked = true; }
}

function getDisplayCheckboxes() {
    var qBox = document.getElementById("qShow");
    var iBox = document.getElementById("iShow");
    
    var opts = new Array();
    opts["i"] = 0;
    opts["q"] = 0;
    if (iBox.checked) { opts["i"] = 1; }
    if (qBox.checked) { opts["q"] = 1; }
    return opts;
}

function showInitials(show) {
    var spans = document.getElementsByTagName("span");
    var iSpans = new Array();
    for (var i = 0; i < spans.length; i++) {
        if (spans[i].className == "initials") {
            iSpans[iSpans.length] = spans[i];
        }
    }
    if (show+0) {
        setStyle(iSpans, "visibility", "visible");
    } else {
        setStyle(iSpans, "visibility", "hidden");
    }
}

function showEmptyQuestions(show) {
    var tableId = "questionTable";
    var table = document.getElementById(tableId);
    var trs = table.getElementsByTagName("tr");
    var eqTrs = new Array();
    for (var i = 0; i < trs.length; i++) {
        if (trs[i].className == "emptyRow") {
            eqTrs[eqTrs.length] = trs[i];
        }
    }
    if (show+0) {
        setStyle(eqTrs, "display", "");
    } else {
        setStyle(eqTrs, "display", "none");
    }

    stripe(tableId, '#fff', '#edf3fe');
}

function stripeTable(tableId) {
	stripe(tableId, '#fff', '#edf3fe');
}

function fixInitials() {
    var opts = getDisplayCheckboxes();
    showInitials(opts["i"]);
    saveDisplayOpts(opts);
}

function fixQuestions() {
    var opts = getDisplayCheckboxes();
    showEmptyQuestions(opts["q"]);
    saveDisplayOpts(opts);
}

function checkDate(el) {
    var date = encodeURIComponent(el.value);
    var url = "content/ajax_dateLookup.php?id="+el.id+"&date="+date;
    var req = new Ajax.Request(url, 
        {onComplete:function(request){eval(request.responseText)},
         evalScripts:true, asynchronous:true});
}

function showHelp(url){
	window.open(url, "example1", "width=400, height=300, location=no, menubar=no, status=no, toolbar=no, scrollbars=yes, resizable=yes");
}

