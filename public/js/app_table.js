var express = require('express');
var app_table = express();

// var bootstrap = require('bootstrap');
var bodyParser = require('body-parser');
var urlencode = bodyParser.urlencoded({ extended: false });

var inspect = require('util').inspect;
var Client = require('mariasql');

var c = new Client();

c.connect({
    host: '127.0.0.1',
    user: 'root',
    password: 'gundam8942'
    //multiStatements: true
});

c.on('connect'	, function()			{ console.log('Client connected'	); });
c.on('error'	, function(err)			{ console.log('Client error: ' + err); });
c.on('close'	, function(hadError)	{ console.log('Client closed'		); });

c.query('USE erp')

var table_name = '';

app_table.set_table_name = function(the_table_name) {
	table_name = the_table_name;
};

//Select Table by group_set
app_table.get_tickets = function(request, response){
    console.log('SQL: begin');

    rows = []

    c.query("SELECT * FROM " + table_name + " WHERE status = '" + request.query.status + "';", false)
        .on('result', function(res) {
            console.log('After SELECT');
            res.on ('row', function(row) {
                rows.push(row);
            })
            res.on('end', function(){
                console.log('END');
                console.log(rows.length);
                response.json(rows);
            });
        });

    console.log('SQL: end');
};

//Select a Specific row by ID
app_table.get_ticket = function(request, response){
    console.log('SQL: begin');

    c.query("SELECT * FROM " + table_NAME + " WHERE id = " + request.query.id + ";", false)
        .on('result', function(res) {
            console.log('After SELECT');
            res.on ('row', function(row) {
                response.json(row);
            });
        });

    console.log('SQL: end');
};

//Add new Button
app_table.put_ticket = function(request, response){
    console.log('AddNew: begin');
    console.log(request.body);

    c.query('INSERT ' + table_name
            +' SET status = "'+request.body.status + '"'
            +', group_set= "'+request.body.group_set + '"'
            +', sequence='+request.body.sequence
            +', name="'+ request.body.name + '"'
            +', value="'+ request.body.value + '"')
        .on('result', function(res) {
            console.log('After INSERT');
            res.on ('end', function(info) {
                response.send('200');
            });
        });
    console.log('AddNew: end');
};

//Search Filter
app_table.get_ticketsX = function(request, response){
    console.log('Filter: begin');

    console.log(request.query.filter);
    var sql = "SELECT * FROM " + table_name + " WHERE name LIKE '%" + request.query.filter + "%';";
    console.log(sql);
    c.query("SELECT * FROM " + table_name + " WHERE name LIKE '%" + request.query.filter + "%';", false)
        .on('result', function(res) {
//            console.log('res:' + JSON.stringify(res));

            rows = []

            res.on ('row', function(row) {
                rows.push(row);
                console.log('row:'+ JSON.stringify(row));
            });

            res.on('end', function(){
                console.log('JSON')
                response.json(rows);
            });
        });

    console.log('Filter: end');
};

//Update One Row
app_table.post_ticket = function(request, response){
    console.log('SQL: begin');
    console.log(request.body);

    c.query('UPDATE ' + table_name
            +' SET status = "'+request.body.status + '"'
            +', sequence='+request.body.sequence
            +', name="'+ request.body.name + '"'
            +', value="'+ request.body.value + '"'
            +' WHERE id='+ request.body.id, false)
        .on('result', function(res) {
            console.log('After UPDATE');
            res.on ('end', function(info) {
                response.send('200');
            });
        });

    console.log('SQL: end');
//  c.end();
};

//Delete One Row
app_table.delete_ticket = function(request, response){
    console.log('DELETE')
    console.log('Query'+ request.query.id);
    c.query('DELETE FROM ' + table_name + ' WHERE id= '+request.query.id)

        .on('result', function(res) {
            console.log('After DELETE');
            res.on ('end', function(info) {
                response.send('200');
            });
        });
};
module.exports = app_table;