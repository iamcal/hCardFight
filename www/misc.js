var g_api_sync_mode = 0;

function ajaxify(url, args, handler){

	var req = new XMLHttpRequest();
	var done_func = function(){

		var l_f = handler;

		if (req.readyState == 4){
			if (req.status == 200){

				this.onreadystatechange = null;
				eval('var obj = '+req.responseText);
				l_f(obj);
			}else{
				l_f({
					'ok'	: 0,
					'error'	: "Non-200 HTTP status: "+req.status,
					'debug'	: req.responseText
				});
			}
		}
	}

	if (!g_api_sync_mode){
		req.onreadystatechange = done_func;
	}

	req.open('POST', url, g_api_sync_mode ? 0 : 1);
	//req.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	var args2 = [];
	for (i in args){
		args2[args2.length] = escape(i)+'='+encodeURIComponent(args[i]);
	}

	req.send(args2.join('&'));

	if (g_api_sync_mode){
		done_func();
	}
}
