var JKY = JKY || {};

JKY.get_date = function() {
    var  my_today = new Date();
    var  my_year	= my_today.getFullYear();
    var  my_month	= my_today.getMonth()+1;	if (my_month < 10)	my_month= '0' + my_month;
    var  my_day		= my_today.getDate ();		if (my_day   < 10)	my_day	= '0' + my_day	;
    return my_year + '-' + my_month + '-' + my_day;
}

function Table(the_table_name) {
	var my_table_name = the_table_name;
	var my_database   = 'erp';
    var winston = require('winston');

    var logSql = new (winston.Logger)({
        transports: [
            new (winston.transports.File)({ filename: 'logSql/' + JKY.get_date() + '.log' })
        ]
    });

    var DataBase = require('mariasql');
	var database = new DataBase();

	database.connect(
		{ host: '192.168.1.68'
		, user: 'root'
		, password: 'Brazil.18781'
//		, multiStatements: true
	});

	database.on('connect'	, function()		{ console.log('DataBase connected ' + my_database 	); })
			.on('close'		, function()		{ console.log('DataBase closed'			); })
			.on('error'		, function(error)	{ console.log('DataBase error: ' + error); })
			;
	database.query('USE ' + my_database);

	this.get_table_name = function()			{ return my_table_name; }
	
	this.get_row = function(the_error, the_where, the_success) {
		var my_sql	= 'SELECT *'
					+ '  FROM ' + this.get_table_name()
					+ ' WHERE ' + the_where
					+ ';';
        logSql.info(my_sql);
		database.query(my_sql, false)
				.on('result', function(result) {
					result.on('row', function(the_row)	{ the_success(the_row); })
				});
	};

	this.get_rows = function(the_error, the_where, the_success) {
		var my_rows = [];
		var my_sql	= 'SELECT *'
					+ '  FROM ' + this.get_table_name()
					+ ' WHERE ' + the_where
					+ ';';
        logSql.info(my_sql);
		database.query(my_sql, false)
				.on('result', function(result) {
					result.on('row', function(the_row)	{ my_rows.push(the_row); })
					result.on('end', function() 		{ the_success (my_rows); })
				});
	};

	this.insert = function(the_error, the_set, the_success) {
		var my_sql	= 'INSERT ' + this.get_table_name()
					+ '   SET ' + the_set
					+ ';';
        logSql.info(my_sql);
		database.query(my_sql, false)
				.on('result', function(result) {
					result.on('end', function() 		{ the_success('200'); })
				});
	};

	this.update = function(the_error, the_set, the_where, the_success) {
		var my_sql	= 'UPDATE ' + this.get_table_name()
					+ '   SET ' + the_set
					+ ' WHERE ' + the_where
					+ ';';
        logSql.info(my_sql);
		database.query(my_sql, false)
				.on('result', function(result) {
					result.on('end', function() 		{ the_success('200'); })
				});
	};

	this.delete = function(the_error, the_where, the_success) {
		var my_sql	= 'DELETE '
					+ '  FROM ' + this.get_table_name()
					+ ' WHERE ' + the_where
					+ ';';
        logSql.info(my_sql);
		database.query(my_sql, false)
				.on('result', function(result) {
					result.on('end', function() 		{ the_success('200'); })
				});
	};
};

module.exports = Table;
