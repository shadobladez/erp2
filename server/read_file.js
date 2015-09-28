var fs = require('fs');
var path = require('path');
//var file_name = 'customer.cvs';
var file_name = 'Controls.json';
fs.readFile(path.join(__dirname, '../data/' + file_name), {encoding: 'utf-8'}, function(err, data) {
	if (err) throw err;
	console.log(data);
});