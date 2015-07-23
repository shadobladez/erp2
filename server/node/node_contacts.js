var express = require('express');
var app_controls = express();

// var bootstrap = require('bootstrap');
var bodyParser = require('body-parser');
var urlencode = bodyParser.urlencoded({ extended: false });

var inspect = require('util').inspect;
var Client = require('mariasql');

var c = new Client(), qcnt = 0;

c.connect({
    host: '127.0.0.1',
    user: 'root',
    password: 'gundam8942'
    //multiStatements: true
});

c.on('connect', function() {
    console.log('Client connected');
})
    .on('error', function(err) {
        console.log('Client error: ' + err);
    })
    .on('close', function(hadError) {
        console.log('Client closed');
    });

//Select Table by group_set
app_controls.get_controls = function(request, response){
    console.log('SQL: begin');

    c.query('USE erp')

    rows = []

    c.query("SELECT * FROM controls WHERE group_set = '"+request.query.group_set+"';", false)
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
//  c.end();
};

//Select a Specific row by ID
app_controls.get_control = function(request, response){
    console.log('SQL: begin');

    c.query('USE erp')

    c.query("SELECT * FROM controls WHERE id = "+request.query.id+";", false)
        .on('result', function(res) {
            console.log('After SELECT');
            res.on ('row', function(row) {
                response.json(row);
            });
        });

    console.log('SQL: end');
//  c.end();
};

//Add new Button
app_controls.put_control = function(request, response){
    console.log('AddNew: begin');
    console.log(request.body);
    c.query('USE erp');

    c.query('INSERT controls'
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
app_controls.get_controlX = function(request, response){
    console.log('Filter: begin');

    c.query('USE erp')
    console.log(request.query.filter);
    var sql = "SELECT * FROM controls WHERE name LIKE '%" + request.query.filter + "%';";
    console.log(sql);
    c.query("SELECT * FROM controls WHERE name LIKE '%" + request.query.filter + "%';", false)
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
app_controls.post_control = function(request, response){
    console.log('SQL: begin');
    console.log(request.body);
    c.query('USE erp');

    c.query('UPDATE controls'
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
app_controls.delete_control = function(request, response){
    console.log('DELETE')
    c.query('USE erp');
    console.log('Query'+ request.query.id);
    c.query('DELETE FROM controls WHERE id= '+request.query.id)

        .on('result', function(res) {
            console.log('After DELETE');
            res.on ('end', function(info) {
                response.send('200');
            });
        });
};
module.exports = app_controls;
