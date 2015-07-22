var logger = new (winston.Logger)({
	transports: [
		new (winston.transports.Console)(),
		new (winston.transports.File)({ filename: 'somefile.log' })
	]
});
  
logger.log('info', 'Hello distributed log files!');
logger.info('Hello again distributed logs');