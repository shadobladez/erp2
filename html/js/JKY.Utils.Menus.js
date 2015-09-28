"use strict";
var JKY = JKY || {};

/**
 * JKY.Util.Menus.js
 * generic functions for the JKY Utils Menus
 */

JKY.side_bars =
	{ sales: {name:'sales', action:'quotations', programs:
			[{label:'Files'					, id:''				, class:''	}
			,{label:'Customers'				, id:'customers'	, class:''	}
			,{label:'Products'				, id:'products'		, class:''	}
			,{label:'Colors'				, id:'colors'		, class:''	}
			,{label:'Sales'					, id:''				, class:''	}
			,{label:'Quotations'			, id:'quotations'	, class:''	}
			,{label:'Products Quoted'		, id:'quot-products', class:''	}
			,{label:''						, id:''				, class:''	}
			,{label:'Aggregate Load Outs'	, id:'loadouts'		, class:''	}
			,{label:''						, id:''				, class:''	}
			,{label:'Sales Orders'			, id:'sales'		, class:''	}
			]
		}
	, planning: {name:'planning', action:'osas', programs:
			[{label:'Files'					, id:''				, class:''	}
			,{label:'Threads'				, id:'customers'	, class:''	}
			,{label:'Machines'				, id:'machines'		, class:''	}
			,{label:'Products'				, id:'products'		, class:''	}
			,{label:'Suppliers'				, id:'suppliers'	, class:''	}
			,{label:'Planning'				, id:''				, class:''	}
			,{label:'OSAs'					, id:'osas'			, class:''	}
			,{label:'Orders'				, id:'orders'		, class:''	}
			,{label:'Thread Dyers'			, id:'tdyers'		, class:''	}
			,{label:'Pieces'				, id:'pieces'		, class:''	}
			]
		}
	, purchases: {name:'purchases', action:'purchases', programs:
			[{label:'Purchases'				, id:''				, class:''	}
			,{label:'Quotations'			, id:'quotations'	, class:''	}
			,{label:'Purchases'				, id:'purchases'	, class:''	}
			,{label:'Invoice Controls'		, id:''				, class:''	}
			,{label:'On Draft Invoices'		, id:'on-drafts'	, class:''	}
			,{label:'On Order Lines'		, id:'on-order'		, class:''	}
			,{label:'On Incomings'			, id:'on-incomings'	, class:''	}
			,{label:'Products'				, id:''				, class:''	}
			,{label:'By Category'			, id:'by-category'	, class:''	}
			,{label:'Products'				, id:'products'		, class:''	}
			]
		}
	, production: {name:'production', action:'ftps', programs:
			[{label:'Files'					, id:''				, class:''	}
			,{label:'Threads'				, id:'threads'		, class:''	}
			,{label:'Machines'				, id:'machines'		, class:''	}
			,{label:'Products'				, id:'products'		, class:''	}
			,{label:'Suppliers'				, id:'suppliers'	, class:''	}
			,{label:'Dyers'					, id:'dyers'		, class:''	}
			,{label:'Partners'				, id:'partners'		, class:''	}
			,{label:'FTPs'					, id:'ftps'			, class:''	}
			,{label:'Production'			, id:''				, class:''	}
			,{label:'Pieces'				, id:'pieces'		, class:''	}
			]
		}

	, maintencance	: {name:'maintenance'	, action:'', programs:[]}
	, inventory		: {name:'inventory'		, action:'', programs:[]}
	, invoicing		: {name:'invoicing'		, action:'', programs:[]}
	, receivable	: {name:'receivable'	, action:'', programs:[]}

	, threads: {name:'threads', action:'purchases', programs:
			[{label:'Files'					, id:''				, class:''	}
			,{label:'Threads'				, id:'threads'		, class:''	}
			,{label:'Machines'				, id:'machines'		, class:''	}
			,{label:'Suppliers'				, id:'suppliers'	, class:''	}
			,{label:'Purchases'				, id:''				, class:''	}
			,{label:'Purchase Orders'		, id:'purchases'	, class:''	}
			,{label:'Purchase Lines'		, id:'purc-lines'	, class:''	}
			,{label:'Incomings'				, id:''				, class:''	}
			,{label:'Incoming Purchases'	, id:'incomings'	, class:''	}
			,{label:'Incoming Batches'		, id:'batches'		, class:''	}
			,{label:'Outputs'				, id:''				, class:''	}
			,{label:'Check Out Orders'		, id:'checkouts'	, class:''	}
			,{label:'Check Out Batches'		, id:'batchouts'	, class:''	}
			,{label:'Others'				, id:''				, class:''	}
			,{label:'Incoming Boxes'		, id:'boxes'		, class:''	}
			,{label:'Forecast'				, id:'forecast'		, class:''	}
			,{label:'Inventory'				, id:'inventory'	, class:''	}
			]
		}
	, boxes: {name:'boxes', action:'boxes_checkin', programs:
			[{label:'Boxes'					, id:''				, class:''	}
			,{label:'Check In'				, id:'checkin'		, class:''	}
			,{label:'Return'				, id:'return'		, class:''	}
			,{label:'Check Out'				, id:'checkout'		, class:''	}
			,{label:'Info'					, id:'info'			, class:''	}
			,{label:'Count'					, id:'count'		, class:''	}
			]
		}
	, dyers: {name:'dyers', action:'loadsales', programs:
			[{label:'Files'					, id:''				, class:''	}
			,{label:'Pieces'				, id:'pieces'		, class:''	}
			,{label:'Machines'				, id:'machines'		, class:''	}
			,{label:'Dyers'					, id:'dyers'		, class:''	}
			,{label:'Transports'			, id:'transports'	, class:''	}
			,{label:'Loads'					, id:''				, class:''	}
			,{label:'Load Out Sales'		, id:'loadsales'	, class:''	}
			,{label:'Shipments'				, id:''				, class:''	}
			,{label:'Ship Dyers'			, id:'shipdyers'	, class:''	}
			]
		}
	, pieces: {name:'pieces', action:'pieces_checkin', programs:
			[{label:'Pieces'				, id:''				, class:''	}
			,{label:'Check In'				, id:'checkin'		, class:''	}
			,{label:'Reviser'				, id:'reviser'		, class:''	}
			,{label:'Weigher'				, id:'weigher'		, class:''	}
			,{label:''						, id:''				, class:''	}
			,{label:'Return'				, id:'return'		, class:''	}
			,{label:'Check Out'				, id:'checkout'		, class:''	}
			,{label:''						, id:''				, class:''	}
			,{label:'Rejected'				, id:'rejected'		, class:''	}
			,{label:'Info'					, id:'info'			, class:''	}
			,{label:'Count'					, id:'count'		, class:''	}
			]
		}
	, receiving: {name:'receiving', action:'receive-nfes', programs:
			[{label:'Files'					, id:''				, class:''	}
			,{label:'Fabrics'				, id:'fabrics'		, class:''	}
			,{label:'Products'				, id:'products'		, class:''	}
			,{label:'Customers'				, id:'customers'	, class:''	}
			,{label:'Dyers'					, id:'dyers'		, class:''	}
			,{label:'Transports'			, id:'transports'	, class:''	}
			,{label:'Receiving'				, id:''				, class:''	}
			,{label:'Receive NFEs'			, id:'receive-nfes'	, class:''	}
			,{label:'Receive Dyers'			, id:'receive-dyers', class:''	}
			,{label:'Load Ins'				, id:'loadins'		, class:''	}
			]
		}
	, fabrics: {name:'fabrics', action:'fabrics_checkin', programs:
			[{label:'Fabrics'				, id:''				, class:''	}
			,{label:'Check In'				, id:'checkin'		, class:''	}
			,{label:'Return'				, id:'return'		, class:''	}
			,{label:'Check Out'				, id:'checkout'		, class:''	}
			,{label:'Info'					, id:'info'			, class:''	}
			,{label:'Count'					, id:'count'		, class:''	}
			]
		}
	, help: {name:'help', action:'tickets', programs:
			[{label:'Help'					, id:''				, class:''	}
			,{label:'Tickets'				, id:'tickets'		, class:''	}
			]
		}
	, admin: {name:'admin', action:'contacts', programs:
			[{label:'Admin'					, id:''				, class:''	}
			,{label:'Configs'				, id:'configs'		, class:''	}
			,{label:'Contacts'				, id:'contacts'		, class:''	}
			,{label:'Companies'				, id:'companies'	, class:''	}
			,{label:'History'				, id:'history'		, class:''	}
			]
		}
	, support: {name:'support', action:'controls', programs:
			[{label:'Support'				, id:''				, class:''	}
			,{label:'Controls'				, id:'controls'		, class:''	}
			,{label:'Permissions'			, id:'products'		, class:''	}
			,{label:'Templates'				, id:'customers'	, class:''	}
			,{label:'Translations'			, id:'translations'	, class:''	}
			]
		}
	};

/**
 * set side bar
 * @param	the_menu
 */
JKY.set_side_bar = function(the_menu, call_back) {
	var my_menu	= JKY.side_bars[the_menu];
	var my_html = '<div id="jky-side-' + my_menu.name + '">';
	my_menu.programs.forEach(function(my_program) {
		var my_id		= my_program.id ? ' id="jky-' + my_menu.name + '-' + my_program.id + '"' : '';
		var my_class	= my_program.id ? 'item' : 'set';
		my_html += ''
			+ '<div' + my_id + ' class="jky-side-' + my_class + ' ' + my_program.class + '">'
			+ '<span>' + my_program.label + '</span>'
			+ '</div>'
			;
	})
	my_html += '</div>';
	return call_back(my_html, my_menu.action);
};

