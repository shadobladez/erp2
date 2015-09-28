"use strict";
var JKY = JKY || {};
/**
 * JKY.Changes - process all changes during one transaction
 *				 control save into private array [my_appraisals]
 *
 * method:	reset()
 * 			incremet()
 *			decrement()
 *			has_changes(the_stay_on_page)
 *
 * require:	JKY.Utils.js(JKY.display_confirm)
 */
JKY.Changes = function() {
	var my_track	= true;		//	flag to track the changes or not
	var my_changes	= 0;		//	number of changes applied on current transaction

	/**
	 *
	 */
	function my_set_button_save() {
		if ($('#jky-action-save').css('display') == 'none')
			return;

		if (my_changes == 0) {
			JKY.disable_button('jky-action-save'	);
//			JKY.disable_button('jky-action-cancel'	);
		}else{
//JKY.set_html('jky-event-which', my_changes);
			JKY.enable_button('jky-action-save'		);
//			JKY.enable_button('jky-action-cancel'	);
		}
	}

	/**
	 *
	 */
	function my_reset() {
		my_changes = 0;
		my_set_button_save();
	}

	/**
	 * if has changes
	 *    then display confirmation layer
	 * 		   if reply = no
	 *			  then re-direct to the_stay_on_page
	 */
	function my_can_leave(the_function) {
		if (my_changes == 0) {
			the_function();
		}else {
			JKY.click_confirm = function(reply) {
				$('#jky-confirm').modal('hide');
				if (reply == 'Yes' && typeof(the_function) == 'function') {
					my_reset();
					the_function();
				}
			}

			var my_header = JKY.t('Leaving');
			var my_body   = ''
//				+ JKY.t('This page has') + ' <b>' + my_changes + '</b> ' + JKY.t('unsaved change(s).')
				+ JKY.t('This page has') + ' ' + JKY.t('unsaved change(s).')
				+ ' <br>' + JKY.t('Do you want to leave to new page')
				+ ' <br> <b>' + JKY.t('without') + '</b> ' + JKY.t('saving them?')
				;
			var my_label_yes = JKY.t('Leave Page');
			var my_label_no  = JKY.t('Stay on Page');
			JKY.set_html	('jky-confirm-header'	, my_header		);
			JKY.set_html	('jky-confirm-body'		, my_body		);
			JKY.set_html	('jky-confirm-yes'		, my_label_yes	);
			JKY.set_html	('jky-confirm-no'		, my_label_no	);
			JKY.show_modal	('jky-confirm');
		}
	}

//	$(function() {
//		my_changes = 0;
//	});

	return {
		  reset			: function()	{my_reset();}
		, increment		: function()	{if (my_track)	{my_changes += 1; my_set_button_save();}}
		, decrement		: function()	{if (my_track)	{my_changes -= 1; my_set_button_save();}}

		, track			: function(the_flag)		{		my_track = the_flag;}
		, can_leave		: function(the_function)	{return my_can_leave(the_function);}
	};
}();