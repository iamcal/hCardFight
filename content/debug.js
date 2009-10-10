
function debug_obj(x){
	var buffer = '<table border="1">';

	buffer += "<tr valign=\"top\"><td><pre>Self</pre></td><td><pre>"+debug_value(x)+"</pre></td></tr>\n";

	for (i in x){
		var s = debug_value(x[i]);

		var s_out = escape_html(s);
		var i_out = escape_html(i);

		if (!s_out.length) s_out = '&nbsp;';

		buffer += "<tr valign=\"top\"><td><pre>"+i_out+"</pre></td><td><pre>"+s_out+"</pre></td></tr>\n";
	}

	buffer += "</table>";

	debug(buffer);
}

function debug_value(v){
	var s = '[Exception]';
	try {
		s = ''+v;
	}catch(e){
	}
	return s;
}

function escape_html(s){
	return s.replace('&', '&amp;').replace('"', '&quot;').replace('<', '&lt;').replace('>', '&gt;');
}

function debug(str){

	var debug_url = "about:blank";

	var appcontent = document.getElementById("appcontent");
	if (appcontent){
		var closure = function(aEvent){

			var doc = aEvent.originalTarget;
			if (doc.location.href == debug_url){

				appcontent.removeEventListener("load", closure, true);

				doc.documentElement.innerHTML += str;

				doc.title = 'Debug';
			}

		};

		appcontent.addEventListener("load", closure, true);
	}

	tab = gBrowser.addTab(debug_url);
	gBrowser.selectedTab = tab;
}

function myDump(aMessage) {
	var consoleService = Components.classes["@mozilla.org/consoleservice;1"].getService(Components.interfaces.nsIConsoleService);
	consoleService.logStringMessage("[DEBUG] " + aMessage);
}

function debug_elm(title, elm){

	var debug_url = "about:blank";

	var appcontent = document.getElementById("appcontent");
	if (appcontent){
		var closure = function(aEvent){

			var doc = aEvent.originalTarget;
			if (doc.location.href == debug_url){

				appcontent.removeEventListener("load", closure, true);

				doc.documentElement.appendChild(elm);

				doc.title = title;
			}

		};

		appcontent.addEventListener("load", closure, true);
	}

	tab = gBrowser.addTab(debug_url);
	gBrowser.selectedTab = tab;
}



function myDumpO(x, recurse_level){

	myDump(parse_obj("", x, recurse_level));
}

function parse_obj(prefix, x, recurse_level){

	//myDump('called with '+no_recurse+' and '+prefix.length);

	if (typeof(x)=='object'){

		if (!recurse_level && prefix != ''){

			return 'object';
		}

		var buffer = "{\n";
		for (var i in x){

			//myDump('found prop '+i);

			var value = '[Exception]';

			try {
				value = parse_obj(prefix+"\t", x[i], recurse_level-1);
			}catch(e){ }

			buffer += prefix+"\t"+i+": "+value+"\n";
		}

		buffer += prefix+"}";

		return buffer;

	}else if (typeof(x)=='function'){

		return 'function(){}';

		try { return x.toString(); }catch(e){ }
	}else{
		try { return x.toString(); }catch(e){ }
	}

	return '[Exception]';
}
