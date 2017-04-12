var page = require('webpage').create();
var system = require('system');

var peak = system.args[1];
var height = system.args[2];
var path = system.args[3];

page.viewportSize = {
	width: 1000,
	height: 768,
};

page.clipRect = {
	top: 775,
	left: 30,
	width: 770,
	height: 912,
};

page.open('https://www.mountain-forecast.com/peaks/'+peak+'/forecasts/'+height, function() {
	var filename = peak + '.png';

	page.render(path + filename);
	console.log(filename);

	phantom.exit();
});
