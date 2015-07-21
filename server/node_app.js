var express = require('express');
var app = express();

//var bootstrap = require('bootstrap');
var bodyParser = require('body-parser');
var urlencode = bodyParser.urlencoded({ extended: false });

//var inspect = require('util').inspect;
app.set('view engine', 'jade');
app.use(express.static('public'));

// Redis Connection
var redis = require('redis');

if (process.env.REDISTOGO_URL) {
    var rtg   = require('url').parse(process.env.REDISTOGO_URL);
    var redis = redis.createClient(rtg.port, rtg.hostname);
    client.auth(rtg.auth.split(':')[1]);
}else{
    var client = redis.createClient();
    client.select((process.env.NODE_ENV || 'development').length);
}
//End Connection

app.get		('/controlX'			, function(request, response)   { app_controls.filter	(request, response) });
app.get		('/controls'			, function(request, response)   { app_controls.get_rows	(request, response) });
app.get		('/control'				, function(request, response)   { app_controls.get_row	(request, response) });
app.put		('/control'	, urlencode , function(request, response)   { app_controls.insert	(request, response) });
app.post	('/control' , urlencode , function(request, response)   { app_controls.update	(request, response) });
app.delete	('/control'				, function(request, response)   { app_controls.delete	(request, response) });

app.get		('/ticketX'				, function(request, response)   { app_tickets.filter	(request, response) });
app.get		('/tickets'				, function(request, response)   { app_tickets.get_rows	(request, response) });
app.get		('/ticket'				, function(request, response)   { app_tickets.get_row	(request, response) });
app.put		('/ticket'	, urlencode , function(request, response)   { app_tickets.insert	(request, response) });
app.post	('/ticket'	, urlencode , function(request, response)   { app_tickets.update	(request, response) });
app.delete	('/ticket'				, function(request, response)   { app_tickets.delete	(request, response) });

app.get		('/contactsX'			, function(request, response)   {app_contacts.get_contactsX   (request, response)});
app.get		('/contacts'			, function(request, response)   {app_contacts.get_contacts   (request, response)});
app.get		('/contacts'			, function(request, response)   {app_contacts.get_contacts    (request, response)});
app.put		('/contacts', urlencode , function(request, response)   {app_contacts.put_contacts    (request, response)});
app.post	('/contacts', urlencode , function(request, response)   {app_contacts.post_contacts   (request, response)});
app.delete	('/contacts'			, function(request, response)   {app_contacts.delete_contacts (request, response)});

//var app_table = require('./../public/js/app_table.js');
var app_controls = require('./../server/node_controls.js');
var app_tickets  = require('./../server/node_tickets.js');

module.exports = app;