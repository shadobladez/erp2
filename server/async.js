async = require('async');
  
async.each(items
	, function(item, callback) {
			item.someAsyncCall(function() {
				callback();
			});
		}
	, function(err) {
			doSomethingOnceAllAreDone();
		}
	);

	
	
async = require('async');
 
var asyncTasks = [];
 
items.forEach(function(item) {
	asyncTasks.push(function(callback) {
		item.someAsyncCall(function() {
			callback();
		});
	});
});
 
asyncTasks.push(function(callback) {
	setTimeout(function(){
		callback();
	}, 3000);
});
 
async.parallel(asyncTasks, function() {
	doSomethingOnceAllAreDone();
});
