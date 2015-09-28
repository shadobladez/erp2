var fs = require('fs');
var path = require('path');
//var file_name = 'customer.cvs';
var file_name = 'Controls.json';
fs.readdir(source, function(err, files) {
	if (err) {
		console.log('Error finding files: ' + err);
		return;
	}
	files.forEach(function(fileName, fileIndex) {
		console.log(fileName);
		gm(source + fileName).size(function(err, values) {
			if  (err) {
				console.log('Error identifying file size: + err);
				return;
			}
			console.log(fileName + ': ' + values);
			aspect = (values.width / values.height);
			
		})
	})
});