/*
 *	generic parent Table class
 *
 *	example:
 *		var controls = new Table();
 */

//var JKY = require('./Utils.js');
var JKY = JKY || {};

JKY.get_date = function() {
    var  my_today = new Date();
    var  my_year	= my_today.getFullYear();
    var  my_month	= my_today.getMonth()+1;	if (my_month < 10)	my_month= '0' + my_month;
    var  my_day		= my_today.getDate ();		if (my_day   < 10)	my_day	= '0' + my_day	;
    return my_year + '-' + my_month + '-' + my_day;
}

function Table() {
	var my_table_host	= '127.0.0.1';
	var my_table_user	= 'root';
	var my_password		= 'gundam8942';
	var my_database		= 'erp';

//	----------------------------------------------------------------------------
	var winston = require('winston');
	var logger = new (winston.Logger)({
		transports: [new (winston.transports.File)	({filename: 'logSql/' + JKY.get_date() + '.log'})]
	});
    logger.info("start table");

//	----------------------------------------------------------------------------
	var DataBase = require('mariasql');
	var database = new DataBase();
	database.connect(
		{ host		: my_table_host
		, user		: my_table_user
		, password	: my_password
//		, multiStatements: true
	});
    logger.info("after connect");


    database
	.on('connect'	, function()		{ console.log('DataBase connected'		)})
	.on('close'		, function()		{ console.log('DataBase closed'			)})
	.on('error'		, function(error)	{ console.log('DataBase error: ' + error)})
	;
    logger.info("database connect");
    database.query('USE ' + my_database);
    logger.info("after USE");

//	----------------------------------------------------------------------------
	/*
	 *	select one or more rows from specific table
	 */
	this.get_rows = function(the_sql, the_callback) {
		var my_rows = [];
		logger.info(the_sql);
		database
		.query(the_sql, false)
		.on('result', function(result) {
			result.on('row', function(the_row)	{my_rows.push(the_row)})
			result.on('end', function() 		{the_callback(my_rows)})
		});
	};

	/*
	 *	select one row from specific table
	 */
	this.get_row = function(the_sql, the_callback) {
		logger.info(the_sql);
		database
		.query(the_sql, false)
		.on('result', function(result) {
			result.on('row', function(the_row)	{the_callback(the_row)})
		});
	};

	/*
	 *	insert one row into specific table
	 */
	this.insert = function(the_sql, the_callback) {
		logger.info(the_sql);
		database
		.query(the_sql, false)
		.on('result', function(result) {
			result.on('end', function() 		{the_callback('200')})
		});
	};

	/*
	 *	update one row into specific table
	 */
	this.update = function(the_sql, the_callback) {
		logger.info(the_sql);
		database
		.query(the_sql, false)
		.on('result', function(result) {
			result.on('end', function() 		{the_callback('200')})
		});
	};

	/*
	 *	delete one row from specific table
	 */
	this.delete = function(the_sql, the_callback) {
		logger.info(the_sql);
		database
		.query(the_sql, false)
		.on('result', function(result) {
			result.on('end', function() 		{the_callback('200')})
		});
	};
};

module.exports = Table;
