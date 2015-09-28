"use strict";
var JKY = JKY || {};

/**
 * home
 */
var jky_program	= 'home';

$(function() {
	JKY.start_program();
});

/**
 * start program
 */
JKY.start_program = function() {
	JKY.display_trace('start_program - ' + jky_program);

//	if user has not login
//	then load login.html
	if (!JKY.Session.has('full_name')) {

//		important, pre-load specific [language] for translations  		
		JKY.append_html('jky-utils', '<scr' + 'ipt src="js/translations/' + JKY.Session.get_value('locale') + '.js"></scr' + 'ipt>');
		JKY.load_dialog('log_in', 320);
	}

	JKY.set_company_name(JKY.Session.get_value('company_name'	));
	JKY.set_company_logo(JKY.Session.get_value('company_logo'	));
	JKY.set_copyright	(JKY.Session.get_value('copyright'		));
//	JKY.set_contact_us	(JKY.Session.get_value('contact_us'		));
//	JKY.set_buttons_control([], JKY.Session.get_value('language'), JKY.Session.get_value('languages'));
	JKY.set_all_events_home();
	JKY.set_initial_values_home();

	if (JKY.Session.has('full_name')) {
//		http://erp/home.html?Production/FTPs/All/200460
		var my_params = window.location.href.split('?');
		JKY.params = (my_params.length > 1) ? my_params[1] : '';
		if (JKY.params === '') {
//			JKY.set_initial_values_home(jky_program);
			JKY.process_start_page();
		}else{
			var my_keys = JKY.params.split('/');
			var my_action	= my_keys[1];
			var my_id		= my_keys[2];

//			display browser tab with new Title + id 
			var my_title = JKY.t(my_action.substr(0, my_action.length-1));
			$('title').html(my_title + ' ' + my_id);

			JKY.load_html('jky-app-body', my_action.toLowerCase() + '.html');
			JKY.App.set_prop('select', 'All');
			JKY.App.set_prop('filter', my_id);
			JKY.display_one();
		}
	}
};

JKY.display_one = function(the_counter) {
	if ($('#jky-table-body tr').length > 0) {
		$('#jky-table-body tr').click();
	}else{
		the_counter = the_counter || 10;
		the_counter -= 1;
		if (the_counter) {
			setTimeout(function() {JKY.display_one(the_counter);}, 100);
		}
	}
};

JKY.set_menu = function(the_keys, the_counter) {
	var my_menus = $('#jky-menus');
	if (my_menus) {
		var my_menu = $('#jky-menus a:contains("' + the_keys[0] + '")');
		if (my_menu) {
			my_menu.click();
			JKY.set_side_bar(the_keys);
		}
	}else{
		the_counter = the_counter || 10;
		the_counter -= 1; 
		if (the_counter) {
			setTimeout(function() {JKY.set_menu(the_keys, the_counter);}, 100);
		}
	}
};

/**
 *	set all events (run only once per load)
 */
JKY.set_all_events_home = function() {
	if (JKY.is_loaded('jky')) {
		JKY.display_trace('set_all_events - '  + jky_program);
		JKY.set_html('jky-loaded', '<scr' + 'ipt src="js/translations/' + JKY.Session.get_value('locale') + '.js"></scr' + 'ipt>');
		JKY.t_tag	('jky-wrapper', 'span');
		JKY.t_input	('jky-wrapper', 'placeholder');

		$('#jky-sales-customers'		).click (function() {JKY.process_action('customers'			);});
		$('#jky-sales-products'			).click (function() {JKY.process_action('products'			);});
		$('#jky-sales-colors'			).click (function() {JKY.process_action('colors'			);});
		$('#jky-sales-quotations'		).click (function() {JKY.process_action('quotations'		);});
		$('#jky-sales-quot-products'	).click (function() {JKY.process_action('quot_products'		);});
		$('#jky-sales-loadouts'			).click (function() {JKY.process_action('loadouts'			);});
		$('#jky-sales-sales'			).click (function() {JKY.process_action('sales'				);});

		$('#jky-planning-threads'		).click (function() {JKY.process_action('threads'			);});
		$('#jky-planning-machines'		).click (function() {JKY.process_action('machines'			);});
		$('#jky-planning-products'		).click (function() {JKY.process_action('products'			);});
		$('#jky-planning-suppliers'		).click (function() {JKY.process_action('suppliers'			);});
		$('#jky-planning-osas'			).click (function() {JKY.process_action('osas'				);});
		$('#jky-planning-orders'		).click (function() {JKY.process_action('orders'			);});
		$('#jky-planning-tdyers'		).click (function() {JKY.process_action('tdyers'			);});
		$('#jky-planning-pieces'		).click (function() {JKY.process_action('pieces'			);});
//		$('#jky-planning-requests'		).click (function() {JKY.process_action('requests'			);});
//		$('#jky-planning-reqlines'		).click (function() {JKY.process_action('reqlines'			);});

		$('#jky-production-threads'		).click (function() {JKY.process_action('threads'			);});
		$('#jky-production-machines'	).click (function() {JKY.process_action('machines'			);});
		$('#jky-production-products'	).click (function() {JKY.process_action('products'			);});
		$('#jky-production-suppliers'	).click (function() {JKY.process_action('suppliers'			);});
		$('#jky-production-dyers'		).click (function() {JKY.process_action('dyers'				);});
		$('#jky-production-partners'	).click (function() {JKY.process_action('partners'			);});
		$('#jky-production-ftps'		).click (function() {JKY.process_action('ftps'				);});
		$('#jky-production-pieces'		).click (function() {JKY.process_action('pieces'			);});

		$('#jky-threads-threads'		).click (function() {JKY.process_action('threads'			);});
		$('#jky-threads-machines'		).click (function() {JKY.process_action('machines'			);});
		$('#jky-threads-suppliers'		).click (function() {JKY.process_action('suppliers'			);});
		$('#jky-threads-purchases'		).click (function() {JKY.process_action('purchases'			);});
		$('#jky-threads-purc-lines'		).click (function() {JKY.process_action('purc_lines'		);});
		$('#jky-threads-incomings'		).click (function() {JKY.process_action('incomings'			);});
		$('#jky-threads-batches'		).click (function() {JKY.process_action('batches'			);});
		$('#jky-threads-checkouts'		).click (function() {JKY.process_action('checkouts'			);});
		$('#jky-threads-batchouts'		).click (function() {JKY.process_action('batchouts'			);});
		$('#jky-threads-boxes'			).click (function() {JKY.process_action('boxes'				);});
		$('#jky-threads-forecast'		).click (function() {JKY.process_action('threadforecast'	);});

		$('#jky-boxes-checkin'			).click (function() {JKY.process_action('boxes_checkin'		);});
		$('#jky-boxes-return'			).click (function() {JKY.process_action('boxes_return'		);});
		$('#jky-boxes-checkout'			).click (function() {JKY.process_action('boxes_checkout'	);});
		$('#jky-boxes-info'				).click (function() {JKY.process_action('boxes_info'		);});

		$('#jky-dyers-pieces'			).click (function() {JKY.process_action('pieces'			);});
		$('#jky-dyers-machines'			).click (function() {JKY.process_action('machines'			);});
		$('#jky-dyers-dyers'			).click (function() {JKY.process_action('dyers'				);});
		$('#jky-dyers-transports'		).click (function() {JKY.process_action('transports'		);});
		$('#jky-dyers-loadsales'		).click (function() {JKY.process_action('loadsales'			);});
		$('#jky-dyers-shipdyers'		).click (function() {JKY.process_action('shipdyers'			);});

		$('#jky-pieces-checkin'			).click (function() {JKY.process_action('pieces_checkin'	);});
		$('#jky-pieces-reviser'			).click (function() {JKY.process_action('pieces_reviser'	);});
		$('#jky-pieces-weigher'			).click (function() {JKY.process_action('pieces_weigher'	);});
//		$('#jky-pieces-return'			).click (function() {JKY.process_action('pieces_return'		);});
		$('#jky-pieces-checkout'		).click (function() {JKY.process_action('pieces_checkout'	);});
		$('#jky-pieces-rejected'		).click (function() {JKY.process_action('pieces_rejected'	);});
//		$('#jky-pieces-info'			).click (function() {JKY.process_action('pieces_info'		);});

//		$('#jky-receiving-fabrics'		).click (function() {JKY.process_action('fabrics'			);});
		$('#jky-receiving-products'		).click (function() {JKY.process_action('products'			);});
		$('#jky-receiving-customers'	).click (function() {JKY.process_action('customers'			);});
		$('#jky-receiving-dyers'		).click (function() {JKY.process_action('dyers'				);});
		$('#jky-receiving-transports'	).click (function() {JKY.process_action('transports'		);});
		$('#jky-receiving-receive-nfes'	).click (function() {JKY.process_action('receive_nfes'		);});
		$('#jky-receiving-receive-dyers').click (function() {JKY.process_action('receive_dyers'		);});
//		$('#jky-receiving-loadins'		).click (function() {JKY.process_action('loadins'			);});

		$('#jky-fabrics-checkin'		).click (function() {JKY.process_action('fabrics_checkin'	);});
//		$('#jky-fabrics-return'			).click (function() {JKY.process_action('fabrics_return'	);});
//		$('#jky-fabrics-checkout'		).click (function() {JKY.process_action('fabrics_checkout'	);});
//		$('#jky-fabrics-info'			).click (function() {JKY.process_action('fabrics_info'		);});

		$('#jky-help-tickets'			).click (function() {JKY.process_action('tickets'			);});

		$('#jky-admin-configs'			).click (function() {JKY.process_action('configs'			);});
		$('#jky-admin-contacts'			).click (function() {JKY.process_action('contacts'			);});
		$('#jky-admin-companies'		).click (function() {JKY.process_action('companies'			);});
		$('#jky-admin-history'			).click (function() {JKY.process_action('history'			);});

		$('#jky-support-controls'		).click (function() {JKY.process_action('controls'			);});
		$('#jky-support-permissions'	).click (function() {JKY.process_action('permissions'		);});
		$('#jky-support-templates'		).click (function() {JKY.process_action('templates'			);});
		$('#jky-support-translations'	).click (function() {JKY.process_action('translations'		);});
	}else{
		setTimeout(function() {JKY.set_all_events_home();}, 100);
	}
};

/**
 *	set initial values (run only once per load)
 */
JKY.set_initial_values_home = function() {
	if (JKY.is_loaded('jky')) {
		JKY.display_trace('set_initial_values - '  + jky_program);
		JKY.set_html('jky-version', JKY.Session.get_value('version') + '-' + JKY.Session.get_value('control_company'));
/*
		$('#jky-home'				).click (function() {JKY.process_home		()		;});
		$('#jky-help'				).click (function() {JKY.process_help		()		;});
		$('#jky-my-info'			).click (function() {JKY.process_my_info	()		;});
		$('#jky-control-language'	).change(function() {JKY.change_language	(this)	;});
		JKY.set_html('jky-app-breadcrumb', JKY.t(jky_program));
*/
	}else{
		setTimeout(function() {JKY.set_initial_values_home();}, 100);
	}
};

/** ------------------------------------------------------------------------ **/
JKY.display_wordpress = function() {
	JKY.display_trace('display_wordpress');
};

JKY.display_company = function(company_name) {
	var my_company_name = $(company_name).text();
	JKY.display_trace('display_company: ' + my_company_name);
};

JKY.display_event = function(event_name) {
	var my_event_name = $(event_name).text();
	JKY.display_trace('display_event: ' + my_event_name);
};

JKY.display_copyright = function() {
	JKY.display_trace('display_copyright');
};

JKY.display_contact_us = function() {
	JKY.display_trace('display_contact_us');
};

JKY.change_language = function(language) {
	var my_language = language.options[language.selectedIndex].value;
	JKY.display_trace('language: ' + my_language);
};

/** ------------------------------------------------------------------------ **/

JKY.process_home = function() {
	JKY.display_trace('process_home');
	JKY.process_action('welcome');
};

JKY.process_help = function() {
	JKY.display_trace('process_help');
};

JKY.process_my_info = function() {
	JKY.display_trace('process_my_info');
};

JKY.process_start_page = function() {
	JKY.display_trace('process_start_page - '  + jky_program);
	var my_menus =
		[{id:'sales'		, icon:'plus', label:'Sales'		}
		,{id:'planning'		, icon:'plus', label:'Planning'		}
		,{id:'production'	, icon:'plus', label:'Production'	}
		,{id:'threads'		, icon:'plus', label:'Threads'		}
		,{id:'boxes'		, icon:'plus', label:'Boxes'		}
		,{id:'dyers'		, icon:'plus', label:'Dyers'		}
		,{id:'pieces'		, icon:'plus', label:'Pieces'		}
		,{id:'receiving'	, icon:'plus', label:'Receiving'	}
		,{id:'fabrics'		, icon:'plus', label:'Fabrics'		}
		,{id:'help'			, icon:'plus', label:'Help'			}
		,{id:'admin'		, icon:'plus', label:'Admin'		}
		,{id:'support'		, icon:'plus', label:'Support'		}
		];
	JKY.set_buttons_menus(my_menus);
	JKY.set_user_info(JKY.Session.get_value('full_name'), JKY.Session.get_value('contact_id'));

	var my_start_page = JKY.Session.get_value('start_page');
//	JKY.load_html('jky-app-body', my_start_page + '.html');
	JKY.show('jky-side-bar');
	var my_id = $('#jky-side-bar');
	$('#jky-collapsible-icon').removeClass('icon-step-forward').addClass('icon-step-backward');
	my_id.css('display' , 'table-cell');
	my_id.css('margin-left' , '0px');
	JKY.process_menu(my_start_page);

	var my_overlay_page	= JKY.Session.get_value('overlay_page');
	if (my_overlay_page === 'reset') {
//		setTimeout(function()	{JKY.process_reset();}, 1000);
		JKY.process_reset();
		var my_data =
			{ method	: 'set_session'
			, action	: 'reset'
			};
		JKY.ajax(false, my_data);
	}
};

/** ------------------------------------------------------------------------ **/
JKY.process_menu = function(menu) {
	JKY.display_trace('process_menu - '  + jky_program + ': ' + menu);
	JKY.set_menu_active(menu);

	JKY.set_side_bar(menu, function(the_html, the_action) {
		$('#jky-side-body').html(the_html);
		JKY.t_tag('jky-side-body', 'span');
		JKY.process_action(the_action);
	});
};
