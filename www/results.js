//console.log('loaded the insert');


var data = document.getElementById('hCardFightData').href.substr(5);

var d = document.createElement('div');
d.style.position = 'absolute';
d.style.left = '10px';
d.style.top = '10px';
d.style.zIndex = 100000;

d.appendChild(document.createTextNode(data));

var body = document.getElementsByTagName("body")[0];
body.appendChild(d);
