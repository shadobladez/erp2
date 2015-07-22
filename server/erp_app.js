/*
 *	erp application
 */

var express		= require('express');
var redis		= require('redis');
//	var bootstrap = require('bootstrap');
var bodyParser	= require('body-parser');
var inspect		= require('util').inspect;

var app_controls = require('./controls.js'	);
var app_tickets  = require('./tickets.js'	);

var app = express();
app.set('view engine', 'jade');
app.use(express.static('public'));

//	Redis Connection
if (process.env.REDISTOGO_URL) {
    var rtg   = require('url').parse(process.env.REDISTOGO_URL);
    var redis = redis.createClient(rtg.port, rtg.hostname);
    client.auth(rtg.auth.split(':')[1]);
}else{
    var client = redis.createClient();
    client.select((process.env.NODE_ENV || 'development').length);
}
//	End Connection

var urlencode = bodyParser.urlencoded({ extended: false });

app.get		('/controls'			, function(request, response)   { app_controls.get_rows	(request, response) });
app.get		('/control'				, function(request, response)   { app_controls.get_row	(request, response) });
app.put		('/control'	, urlencode , function(request, response)   { app_controls.insert	(request, response) });
app.post	('/control' , urlencode , function(request, response)   { app_controls.update	(request, response) });
app.delete	('/control'				, function(request, response)   { app_controls.delete	(request, response) });

app.get		('/tickets'				, function(request, response)   { app_tickets.get_rows	(request, response) });
app.get		('/ticket'				, function(request, response)   { app_tickets.get_row	(request, response) });
app.put		('/ticket'	, urlencode , function(request, response)   { app_tickets.insert	(request, response) });
app.post	('/ticket'	, urlencode , function(request, response)   { app_tickets.update	(request, response) });
app.delete	('/ticket'				, function(request, response)   { app_tickets.delete	(request, response) });

module.exports = app;