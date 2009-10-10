Ci = Components.interfaces;
Cc = Components.classes;

var cardScannerOn = 0;

function toggleScanner(){

	if (cardScannerOn){
		cardScannerOn = 0;
		stopScanner();
		document.getElementById('imgTimerStatusIcon').src = 'chrome://hcardfight/skin/tray_off.gif';
	}else{
		cardScannerOn = 1;
		startScanner();
		document.getElementById('imgTimerStatusIcon').src = 'chrome://hcardfight/skin/tray_on.gif';
	}
}

function startScanner(){

	if (document.getElementById("appcontent")){
		document.getElementById("appcontent").addEventListener("DOMContentLoaded", onStartDocLoad, true);
	}
}

function stopScanner(){

	if (document.getElementById("appcontent")){
		document.getElementById("appcontent").removeEventListener("DOMContentLoaded", onStartDocLoad, true);
	}
}

function onStartDocLoad(event){
	if (!event) return;
	var page = event.originalTarget;
	if (!page) return;
	if (!page.location) return;
	if (page.location == 'about:blank') return;

	get_formats(page);
}

function get_formats(doc){

	var objScriptLoader = Cc["@mozilla.org/moz/jssubscript-loader;1"].getService(Ci.mozIJSSubScriptLoader);
	if (typeof(Microformats) == "undefined"){
		objScriptLoader.loadSubScript("chrome://hcardfight/content/Microformats.js");
	}

	var formats = Microformats.get('hCard', doc, {showHidden: true, debug: false});

	formats = sortUnique(formats, true, true);

	var hasKeys = 0;
	for (var i in formats) hasKeys = 1;
	if (!hasKeys) return;


	var nativeJSON = Cc["@mozilla.org/dom/json;1"].createInstance(Ci.nsIJSON);  
	var j = nativeJSON.encode(cleanUpData(formats)); 


	// insert stuff into the doc

	var a = doc.createElement('a');
	a.id = 'hCardFightData';
	a.style.display = 'none';
	a.href = 'data:'+Base64.encode(j);

	var s = doc.createElement('script');
	s.src = 'http://www.hcardfight.com/results.php?cb='+(new Date().getTime());

	var body = doc.getElementsByTagName("body")[0];
	body.appendChild(a);
	body.appendChild(s);
}


// This function sorts semantic object nodes and also removes duplicates

function sortUnique(semanticObjects, sort, unique){

    if (semanticObjects.length == 1) {
      return semanticObjects;
    }
    if (unique) {
      var serializer = new XMLSerializer();
      for (var i=0; i < semanticObjects.length; i++) {
        for (var j=i+1; j < semanticObjects.length; j++) {
          /* If we aren't already a duplicate, check to see if */
          /* j is the same as i */
          if (!semanticObjects[j].duplicate) {
            if (semanticObjects[j].displayName && (semanticObjects[j].displayName == semanticObjects[i].displayName)) {
              if ((Microformats[semanticObjects[i].semanticType].className) && (semanticObjects[i].node.innerHTML == semanticObjects[j].node.innerHTML)) {
                  semanticObjects[j].duplicate = true;
              } else if (serializer.serializeToString(semanticObjects[i].node) == serializer.serializeToString(semanticObjects[j].node)) {
                semanticObjects[j].duplicate = true;
              } else if (Operator.areEqualObjects(semanticObjects[j], semanticObjects[i])) {
                semanticObjects[j].duplicate = true;
              }
            }
          }
        }
      }
    }
    if (sort) {
      return semanticObjects.sort(
        function (a,b) {
          if (!a.displayName || !b.displayName) {
            if (!a.displayName && !b.displayName) {
              return 0;
            }
            if (!a.displayName) {
              return -1;
            }
            if (!b.displayName) {
              return 1;
            }
          }
          if (a.displayName.toLowerCase() < b.displayName.toLowerCase()) {
            return -1;
          }
          if (a.displayName.toLowerCase() > b.displayName.toLowerCase()) {
            return 1;
          }
          return 0;
        }
      );
    }
    return semanticObjects;
}


function cleanUpData(x, depth){

	if (typeof(x)=='object'){

		var out = {};

		for (var i in x){

			if (i != 'node' && i != 'resolvedNode'){

				var v = null;

				try {
					v = cleanUpData(x[i], 1 + depth);
				}catch(e){ }

				if (v != null) out[i] = v;
			}
		}

		return out;

	}else if (typeof(x)=='function'){

		return null;

	}else{
		try {
			var temp = x.toString();
			return x;
		}catch(e){ }
	}

	return null;
}

