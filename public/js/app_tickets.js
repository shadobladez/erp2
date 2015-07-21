var app_table = require('./app_table.js');

var app_tickets = app_table;
app_tickets.set_table_name('Tickets');

module.exports = app_tickets;