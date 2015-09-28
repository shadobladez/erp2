// Load the http module to create an http server.
var http = require('http');
debugger;
// Configure our HTTP server to respond with Hello World to all requests.
var server = http.createServer(function (request, response) {
    response.writeHead(200, {"Content-Type": "text/plain"});
	debugger;
    response.end("Hello World " + new Date() + "\n");
});

// Listen on port 8000, IP defaults to 127.0.0.1
server.listen(80);

// Put a friendly message on the terminal
console.log("Server running at http://127.0.0.1:80/");


/*

node-inspector

node --debug-brk hello_debug.js
node --debug     hello_debug.js

http://192.168.23.129:8080/debug?port=5858
*/