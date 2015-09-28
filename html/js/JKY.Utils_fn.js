/**
 * delay time for Keyup Delay time
 */
$.fn.KeyUpDelay = function(cb, delay) {
	if (delay == null)	delay = 500;
	var timer = 0;
	return $(this).on('keyup', function() {
		clearTimeout(timer);
		timer = setTimeout(cb, delay);
	});
};

/**
 * numeric only control handler
 *
 * $('#yourTextBoxName').ForceIntegerOnly();
 */
$.fn.ForceIntegerOnly = function() {
	return this.each(function() {
		$(this).keydown(function(e) {
			// allow: backspace, tab, delete, arrows, numbers and keypad numbers ONLY
			// home, end and numpad decimal
			return (e.keyCode == 8
				||	e.keyCode == 9
				||	e.keyCode == 46
				|| (e.keyCode >= 35 && e.keyCode <=  40)
				|| (e.keyCode >= 48 && e.keyCode <=  57 && !e.shiftKey)
				|| (e.keyCode >= 96 && e.keyCode <= 105 && !e.shiftKey)
				);
		});
	});
};

/**
 * numeric only control handler
 * replace comma to point
 *
 * $('#yourTextBoxName').ForceNumericOnly();
 */
$.fn.ForceNumericOnly = function() {
	return this.each(function() {
		$(this).keydown(function(e) {
			// allow: backspace, tab, delete, arrows, numbers and keypad numbers ONLY
			// home, end, comma, period and numpad decimal
			return (e.keyCode == 8
				||	e.keyCode == 9
				||	e.keyCode == 46
				||	e.keyCode == 110
				||	e.keyCode == 188
				||	e.keyCode == 190
				|| (e.keyCode >= 35 && e.keyCode <=  40)
				|| (e.keyCode >= 48 && e.keyCode <=  57 && !e.shiftKey)
				|| (e.keyCode >= 96 && e.keyCode <= 105 && !e.shiftKey)
				);
		});
		$(this).keyup(function(e) {
//			var key = e.charCode || e.keyCode || 0;
//			if (key == 188) {
				$(this).val($(this).val().replace(/,/g, "."));
//			}
		});
	});
};

/**
 * numeric only and Hyphen
 *
 * $('#yourTextBoxName').ForceNumericHyphen();
 */
$.fn.ForceNumericHyphen = function() {
	return this.each(function() {
		$(this).keydown(function(e) {
			var key = e.charCode || e.keyCode || 0;
			// allow: backspace, tab, delete, arrows, numbers and keypad numbers ONLY
			// home, end, numpad decimal and Hyphen
			return (e.keyCode == 8
				||	e.keyCode == 9
				||	e.keyCode == 46
				||	e.keyCode == 109
				||	e.keyCode == 173
				|| (e.keyCode >= 35 && e.keyCode <=  40)
				|| (e.keyCode >= 48 && e.keyCode <=  57 && !e.shiftKey)
				|| (e.keyCode >= 96 && e.keyCode <= 105 && !e.shiftKey)
				);
		});
	});
};

/**
 * numeric and alpha
 *
 * $('#yourTextBoxName').ForceNumericAlpha();
 */
$.fn.ForceNumericAlpha = function() {
	return this.each(function() {
		$(this).keydown(function(e) {
			// allow backspace, tab, delete, numbers, keypad numbers
			// alphabetic a-z
			return (e.keyCode ==  8
				||	e.keyCode ==  9
				||	e.keyCode == 46
				|| (e.keyCode >= 48 && e.keyCode <=  57 && !e.shiftKey)
				|| (e.keyCode >= 65 && e.keyCode <=  90)
				|| (e.keyCode >= 96 && e.keyCode <= 105 && !e.shiftKey)
					);
		});
	});
};

/**
 * alpha, numeric, space
 *
 * $('#yourTextBoxName').ForceName();
 */
$.fn.ForceName = function() {
	return this.each(function() {
		$(this).keydown(function(e) {
			// allow backspace, tab, left, right, delete, numbers, alpha
			// alphabetic a-z
			return (e.keyCode ==  8
				||	e.keyCode ==  9
				||	e.keyCode == 32
				||	e.keyCode == 37
				||	e.keyCode == 39
				||	e.keyCode == 46
				|| (e.keyCode >= 48 && e.keyCode <=  57 && !e.shiftKey)
				|| (e.keyCode >= 65 && e.keyCode <=  90)
				|| (e.keyCode >= 96 && e.keyCode <= 105 && !e.shiftKey)
					);
		});
	});
};

//<input type="number" value="0"> 						float
//<input type="number" value="0" min="0"> 				positive
//<input type="number" value="0" min="0" step="1"> 		positive integer

/**
 * return checked value(s)
 */
$.fn.GetCheckedValues = function() {
	return $.map(this, function(elem) {
		return elem.value || '';
//	}).join( ',' );
	});
};

/**
 * intercept on enter key
 */
$.fn.OnEnterKey = function(closure) {
	$(this).keypress(
		function(event) {
			var code = event.keyCode ? event.keyCode : event.which;
			if (code == 13) {
				closure();
				return false;
			}
		}
	);
};