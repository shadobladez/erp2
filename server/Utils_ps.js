/*
 *	Utils - pub / sub functions
 *	
 *	logger
 *	logsql
 *
 *	display 
 */

//	----------------------------------------------------------------------------
var winston = require('winston');
var logger = new (winston.Logger)({	transports : [new (winston.transports.File)({ filename: 'logger.log' })] });
var logsql = new (winston.Logger)({	transports : [new (winston.transports.File)({ filename: 'logsql.log' })] });

//	----------------------------------------------------------------------------
function display(the_message) {
	$('#jky-message').append('<br>' + the_message);
}
