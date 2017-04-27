var page = require('webpage').create();
var system = require('system');

var peak = system.args[1];
var height = system.args[2];
var path = system.args[3];

page.viewportSize = {
	width: 1000,
	height: 768,
};

page.open('https://www.mountain-forecast.com/peaks/'+peak+'/forecasts/'+height, function() {
	var filename = 'mountain.png';

	var dimensions = page.evaluate(function () {
		document.getElementById('metricradio').click();

		var table = document.getElementsByClassName('forecasts')[0];

		return {
			width: table.offsetWidth,
			height: table.offsetHeight,
			top: table.getBoundingClientRect().top,
			left: table.offsetLeft,
		};
	});

	page.clipRect = dimensions;

	page.render(path + filename);
	console.log(filename);

	phantom.exit();
});
