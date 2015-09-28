"use strict";

/**
 * JKY.Purchase - process all changes during one transaction
 */
JKY.Purchase = function() {
	var my_expected	= 0;
	var my_received	= 0;

	function my_add_expected(the_weight) {
		if (the_weight != null) {
			my_expected += parseFloat(the_weight);
			my_expected  = Math.round(my_expected);
		}
	}

	function my_add_received(the_weight) {
		if (the_weight != null) {
			my_received += parseFloat(the_weight);
			my_received  = Math.round(my_received * 100) / 100

		}
	}

	function my_update_expected_weight(the_id) {
		var my_data =
			{ method	: 'update'
			, table		: 'Purchases'
			, where		: 'id =' + the_id
			, set		: 'expected_weight=' + my_expected
			};
		JKY.ajax(true, my_data);
	}

	function my_update_received_weight(the_id) {
		var my_data =
			{ method	: 'update'
			, table		: 'Purchases'
			, where		: 'id =' + the_id
			, set		: 'received_weight=' + my_received
			};
		JKY.ajax(true, my_data);
	}

	$(function() {
	});

	return {
		  set_expected	: function(the_amount)	{my_expected = the_amount;}
		, set_received	: function(the_amount)	{my_received = the_amount;}

		, add_expected	: function(the_weight)	{my_add_expected(the_weight);}
		, add_received	: function(the_weight)	{my_add_received(the_weight);}

		, get_expected	: function()			{return my_expected;}
		, get_received	: function()			{return my_received;}

		, update_expected_weight: function(the_id)		{my_update_expected_weight(the_id);}
		, update_received_weight: function(the_id)		{my_update_received_weight(the_id);}
	};
}();