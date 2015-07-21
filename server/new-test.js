var express = require('express');
var app = express();

var bodyParser = require('body-parser');
var urlencode = bodyParser.urlencoded({ extended: false });

var inspect = require('util').inspect;
var Client = require('mariasql');

var c = new Client(), qcnt = 0;
c.connect({
    host: '127.0.0.1',
    user: 'root',
    password: 'gundam8942',
    database: 'erp'
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

app.set('view engine', 'jade');


app.use(express.static('public'));


// Redis Connection
var redis = require('redis');
if (process.env.REDISTOGO_URL){
    var rtg   = require("url").parse(process.env.REDISTOGO_URL);
    var redis = redis.createClient(rtg.port, rtg.hostname);
    client.auth(rtg.auth.split(":")[1]);
}   else {
    var client = redis.createClient();
    client.select((process.env.NODE_ENV || 'development').length);
}

//End Connection

app.get('/cities', function(request, response){
    console.log('SQL: begin');
    c.query('SHOW DATABASES')
        .on('result', function(res) {
            res.on('row', function(row) {
                console.log('Result row: ' + inspect(row));
            })
                .on('error', function(err) {
                    console.log('Result error: ' + inspect(err));
                })
                .on('end', function(info) {
                    console.log('Result finished successfully');
                });
        })
        .on('end', function() {
            console.log('Done with all results');
        });

    c.query('USE erp')
    c.query('SHOW TABLES')
        .on('result', function(res) {
            console.log('USING Database: ERP');
            res.on('row', function(row) {
                console.log('Result table: ' + inspect(row));
            })
                .on('error', function(err) {
                    console.log('Result error: ' + inspect(err));
                })
                .on('end', function(info) {
                    console.log('Result finished successfully');
                });
        })
        .on('end', function() {
            console.log('Done with all results');
        });
    rows = [];
    c.query("SELECT * FROM controls WHERE group_set = 'Root';", true)
        .on('result', function(res) {
            console.log('After SELECT');
            res.on ('row', function(row) {
                rows.push(row);
//                console.log('The solution is: ', + row);
//                console.log(row);
            })
            res.on('end', function(){
               console.log('END');
                console.log(rows.length);
               response.json(rows);
            });
        });
    console.log('SQL: end');
//  c.end();
});

app.post('/cities', urlencode, function(request,response){
    var newCity = request.body;
    if(!newCity.name || !newCity.description){
        response.sendStatus(400);
        return false;
    }
    client.hset('cities', newCity.name, newCity.description, function(error){
        if(error) throw error;

        response.status(201).json(newCity.name);

    });

});

app.delete('/cities/:name', function(request, response){

    client.hdel('cities', request.params.name, function(error){
        if(error) throw error;
        response.sendStatus(204);
    });
});

app.get('/cities/:name', function(request, response){
   client.hget('cities', request.params.name, function(error, description){
       response.render('show.ejs',
           {city:
           { name: request.params.name, description: description}
           });
   });
});

module.exports = app;
