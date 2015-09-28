"use strict";
var JKY = JKY || {};

/**
 * JKY.Translation - process all Translation interface
 *
 * method:	JKY.Translation.load_values()
 *			JKY.Translation.set_value(key, value)
 *			JKY.Translation.get_value(key)
 *
 * example:	JKY.Translation.load_values();
 *			JKY.Translation.set_value('language', 'taiwanese');
 *			JKY.Translation.get_value('language');		//	taiwanese
 *
 * require:	JKY.Utils.js(JKY.AJAX_URL)
 */
JKY.Translation = function() {
	var my_translation = [];

	/**
	 * translate
	 *
	 * @param	text
	 * @return	translated text
	 * @example JKY.t('Home')
	 */
	function translate(the_text) {
		if (typeof(the_text) === 'undefined' || the_text === '')		{return '';}

		var my_result = my_translation[the_text];
		if (typeof(my_result) === undefined) {
alert('JKY.Translation the_text: ' + the_text);
			my_result = '';
			var my_names = the_text.split('<br>');
			for(var i=0, max=my_names.length; i<max; i+=1) {
				var my_name = my_names[i];
				var my_word = my_translation[name];
				my_result += ( i === 0 ) ? '' : '<br>';
				if (typeof(my_word) === undefined) {
					my_result += my_name;
				}else{
					my_result += my_word;
				}
			}
		}
		return my_result;
	}

	return {
			set_translation	: function(the_array)	{		my_translation = the_array	;}
		,	translate		: function(the_text)	{return my_translate(the_text)		;}
	}
}()