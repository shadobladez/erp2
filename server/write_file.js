var fs = require('fs');
var path = require('path');
var file_name = 'message.txt';
fs.writeFile(path.join(__dirname, '../data/' + file_name), 'Hello world!', function(err) {
	if (err) throw err;
	console.log('Writing is done.');
});