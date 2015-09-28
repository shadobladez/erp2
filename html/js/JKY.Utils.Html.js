"use strict";
var JKY = JKY || {};

/*
 * get selector (jquery) for specific [id]
 * if [id] does not exist,
 *    then append specific [id] at end of [body]
 * @param	the_id
 * @return	selector
 */
JKY.get_selector = function(the_id) {
	var my_selector = $('#' + the_id);
	if (my_selector.length === 0) {
		$('body').append('<div id="' + the_id + '"></div>');
		my_selector = $('#' + the_id);
	}
	return my_selector;
}

/*
 * load file (html) from folder [/tandem/hb/]
 * and generate random [id] into first [jky-id] 
 * @param	file_name
 * @param	call_back
 * call_back(id, html)
 */
JKY.load_file = function(file_name, call_back) {
	var my_loader = JKY.get_selector('jky-loader');
	my_loader.load(file_name, function(response, status, xhr) {
		if (status === 'error') {
			alert('Error: ' + xhr.status + ' ' + xhr.statusText + ' on file: ' + file_name);
			return;
		}

		//	add random id [jky-99999999] into first jky-id
		var my_id = 'jky-' + Math.floor(Math.random() * 100000000);
		my_loader.find('div[jky-id]:first').attr('id', my_id);
		var my_html = my_loader.html();
		my_loader.html('');
		call_back(my_id, my_html);
	})
}

/**
 * load html into jky-dialog
 * translate t
 * @param	file_name
 */
JKY.load_dialog = function(file_name, the_height) {
	var the_id = 'jky-dialog';
	$('#' + the_id).load('../' + file_name + '.html?h=' + JKY.Session.get_value('version'));
	JKY.set_css('jky-company-logo', 'height', '45px');
	JKY.set_css('jky-log-form', 'height', the_height + 'px');
	JKY.set_company_logo(JKY.Session.get_value('company_logo'	));
	JKY.t_tag	(the_id, 'span');
	JKY.t_input	(the_id, 'placeholder');
	JKY.t_button(the_id, 'title');

//	it is to automatic start loaded program
	JKY.start_program();
};

/**
 * toggle icon specific [id]
 * @param	the_id
 */
JKY.toggle_icon = function(the_id) {
	var my_icon = $(the_id).find('.jky-section-icon img');
	if (my_icon.length === 0)	return;

	if (my_icon.attr('src') !== '/tandem/img/ArrowDownWhite.png') {
		my_icon.attr('src',		'/tandem/img/ArrowDownWhite.png');
	}else{	
		my_icon.attr('src',		'/tandem/img/ArrowUpWhite.png');
	}
}

/**
 * toggle section specific [id]
 * @param	the_id
 */
JKY.toggle_section = function(the_id) {
	JKY.toggle_icon(the_id);

	var my_id = $(the_id).next('.collapse');
	if (my_id.length === 0)		return;

	my_id.css('overflow', 'hidden');	//	this is needed to get css height
	var my_height = parseInt(my_id.css('height'));
	var my_elapse = JKY.isBrowser('msie') ? 100 : 100;	//	10 : 5
	var my_increment;
	var my_interval;

	if (my_height > 0) {
		my_increment = my_height / (100/my_elapse);
		my_interval  = setInterval(function() {
			my_height -= my_increment;
			if (my_height < 0) {
				my_id.css('overflow', 'hidden');
				my_id.css('height', '0px');
				clearInterval(my_interval);
			}else{
				my_id.css('height', my_height + 'px');
			}
		}, my_elapse);
	}else{
		var my_max_height = $(my_id)[0].scrollHeight;
		if (my_max_height === 0) {
			my_id.css('height', 'auto');
		}else{
			my_increment = my_max_height / (100/my_elapse);
			my_interval = setInterval(function() {
				my_height += my_increment;
				if (my_height > my_max_height) {
					my_id.css('overflow', 'visible');
					my_id.css('height', 'auto');
					clearInterval(my_interval);
				}else{
					my_id.css('height', my_height + 'px');
				}
			}, my_elapse * 5);
		}
	}
}

/*
 * append html to class [jky-body] of specific [id]
 * @param	the_id
 * @param	the_html
 */
JKY.append_body = function(the_id, the_html) {
	$('#' + the_id + ' .jky-body').html(the_html);
}	

/*
 * set property
 * @param	the_data
 *				[{label:'x...x', name:'x...x', mask:'x...x', placeholder:'x...x'}
 *				,{label:'x...x', name:'x...x', mask:'x...x', placeholder:'x...x'}
 *				,{label:'x...x', name:'x...x', mask:'x...x', placeholder:'x...x'}
 *				]
 * @return	html
 *				<div><label>...</label><input ... /></div>
 *				<div><label>...</label><input ... /></div>
 *				<div><label>...</label><input ... /></div>
 */
JKY.Xset_property = function(the_data) {
	var my_html = '';
	the_data.forEach(function(my_line) {
		my_html += ''
			+ '<div class="jky-line">'
			+ '<label>' + my_line.label + ':</label>'
			+ '<input class="jky-' + my_line.name + '" placeholder="' + my_line.placeholder + '" />'
			+ '</div>'
			;
	})
	return my_html;
}

/*
 * set options
 * @param	the_label
 * @param	from_value		true | false	
 * @param	the_options
 *				[{key:'x...x', value:'x...'}
 *				,{key:'x...x', value:'x...'}
 *				,{key:'x...x', value:'x...'}
 *				]
 * @return	html
 *				<option>...</option>
 *				<option>...</option>
 *				<option>...</option>
 */
JKY.Xset_options = function(the_label, from_value, the_options) {
	var my_html = '<option value="">' + the_label + '</option>';
	the_options.forEach(function(my_option) {
		var my_value = from_value ? my_option.value : my_option.key;
		my_html += '<option value="' + my_option.key + '">' + my_value + '</option>';
	})
	return my_html;
}

/*
 * set select
 * @param	the_data
 *				{label:'x...x', name:'xxx-xxx', options:
 *					[{key:'x...x', value:'x...'}
 *					,{key:'x...x', value:'x...'}
 *					,{key:'x...x', value:'x...'}
 *					]
 *				}
 * @return	html
 *				<div>
 *					<label>...</label>
 *					<select>
 *						<option>...</option>
 *						<option>...</option>
 *						<option>...</option>
 *					</select>
 *				</div> 
 */
JKY.set_select = function(the_data) {
	var my_html = ''
		+ '<div class="jky-line">'
		+ '<label>' + the_data.label + ':</label>'
		+ '<select class="jky-' + the_data.name + '" name="jky-' + the_data.name + '">'
		+ '<option value="">Select ' + the_data.label + '</option>'
		;
	the_data.options.forEach(function(my_option) {
		my_html += '<option value="' + my_option.key + '">' + my_option.value + '</option>';
	})
	my_html += '</select></div><br>';
	return my_html;
}

/*
 * set checkbox
 * @param	the_data
 *				{label:'x...x', name:'xxx-xxx', options:
 *					[{key:'x...x', value:'x...'}
 *					,{key:'x...x', value:'x...'}
 *					,{key:'x...x', value:'x...'}
 *					]
 *				}
 * @return	html
 *				<div>
 *					<label>...</label>
 *					<input type="checkbox" name="jky-x...x" value="x...x"select>
 *						<option>...</option>
 *						<option>...</option>
 *						<option>...</option>
 *					</select>
 *				</div> 
 */
JKY.set_checkbox = function(the_data) {
	var my_html = ''
		+ '<div class="jky-line">'
		+ '<label>' + the_data.label + ':</label>'
		+ '<div class="jky-box">'
		;
	the_data.options.forEach(function(my_option) {
		my_html += '<input class="jky-checkbox" name="jky-' + the_data.name + '" type="checkbox" value="' + my_option.key + '" /> ' + my_option.value + '</br />';
	})
	my_html += '</div></div>';
	return my_html;
}

/*
 * set yes no
 * @param	the_data
 *				[{label:'x...x', name:'x...x'}
 *				,{label:'x...x', name:'x...x'}
 *				,{label:'x...x', name:'x...x'}
 *				]
 * @return	html
 *				<div><label>...</label><input ... /><span>Yes</span><input ... /><span>No</span></div>
 *				<div><label>...</label><input ... /><span>Yes</span><input ... /><span>No</span></div>
 *				<div><label>...</label><input ... /><span>Yes</span><input ... /><span>No</span></div>
 */
JKY.set_yes_no = function(the_data) {
	var my_html = '';
	the_data.forEach(function(my_line) {
		my_html += ''
			+ '<div class="jky-line">'
			+ '<label>' + my_line.label + ' ?</label>'
			+ '<input class="jky-boolean" name="jky-' + my_line.name + '" type="radio" value="Y"	/><span>Yes</span>'
			+ '<input class="jky-boolean" name="jky-' + my_line.name + '" type="radio" value="N"	/><span>No </span>'
			+ '</div>'
			+ '<br>'
			;
	})
	return my_html;
}

/*
 * set amount
 * @param	the_data
 *				[{label:'x...x', name:'x...x'}
 *				,{label:'x...x', name:'x...x'}
 *				,{label:'x...x', name:'x...x'}
 *				]
 * @return	html
 *				<div><label>...</label><input ... /><span>Yes</span><input ... /><span>No</span></div>
 *				<div><label>...</label><input ... /><span>Yes</span><input ... /><span>No</span></div>
 *				<div><label>...</label><input ... /><span>Yes</span><input ... /><span>No</span></div>
 */
JKY.set_amount = function(the_data) {
	var my_html = '';
	the_data.forEach(function(my_line) {
		my_html += ''
			+ '<div class="jky-line">'
			+ '<label>' + my_line.label + '</label>'
			+ '<div class="jky-amount"> $' + my_line.amount + '</div>'
			+ '</div>'
			+ '<br>'
			;
	})
	return my_html;
}

/*
 * set tags ...
 */
JKY.set_tag_amount = function(the_line) {
	return '<input type="number"'
		 + ' class="jky-' + the_line.name + '"'
		 + ' placeholder="' + the_line.placeholder + '"'
		 + ' />'
		 ;	
}

JKY.set_tag_checkbox = function(the_field) {
	var my_html = '<div class="jky-box">';
	the_field.field_options.forEach(function(the_option) {
		my_html += '<input type="checkbox" class="jky-checkbox"'
				+  ' name="jky-' + the_field.field_name + '"'
				+  ' value="' + the_option.key + '" />'
				+  '<span>' + the_option.value + '</span>'
				+  '<br>'
				;
	})
	my_html += '</div>';
	return my_html;
}

JKY.set_tag_date = function(the_line) {
	return '<div class="input-append date">'
		 + '<input type="text"'
		 + ' class="jky-' + the_line.name + '"'
		 + ' data-format="MM-dd-yyyy"'
		 + ' class="hasDatepicker"'
		 + ' />'
		 + '</div>'
		 ;
}

JKY.set_tag_select = function(the_field) {
	var my_html = '';
	my_html += '<select class="jky-' + the_field.field_name + '">';
	my_html += '<option value="">--Select ' +  the_field.field_label + '--</option>';
	the_field.field_options.forEach(function(the_option) {
		my_html += '<option value="' + the_option.key + '">' + the_option.value + '</option>';
	})
	my_html += '</select>';
	return my_html;
}

JKY.set_tag_textarea = function(the_line) {
	return '<textarea'
		 + ' class="jky-' + the_line.name + '"'
		 + '>'
		 + '</textarea>'
		 ;
}

JKY.set_tag_text = function(the_line) {
	return '<input type="text"'
		 + ' class="jky-' + the_line.name + '"'
		 + ' placeholder="' + the_line.placeholder + '"'
		 + ' />'
		 ;
}

/*
 * set form
 * @param	the_fields
 *				[{field_label:'x...x', field_name:'x...x', field_renderer:'amount'	}
 *				,{field_label:'x...x', field_name:'x...x', field_renderer:'checkbox'					, field_options:[{key:'x...x', value:'x...x'}]}
 *				,{field_label:'x...x', field_name:'x...x', field_renderer:'date'		}
 *				,{field_label:'x...x', field_name:'x...x', field_renderer:'single_select_dropdown'	, field_options:[{key:'x...x', value:'x...x'}]}
 *				,{field_label:'x...x', field_name:'x...x', field_renderer:'textarea'	}
 *				,{field_label:'x...x', field_name:'x...x', field_renderer:'text'		}
 *				]
 *   * @return	html
 */
JKY.set_form = function(the_fields) {
	var my_html = '';
	the_fields.forEach(function(my_field) {
		my_html += ''
			+ '<div class="jky-line">'
			+ '<label>' + my_field.field_label + '</label>'
			;
		switch(my_field.field_renderer) {
			case 'amount'					: my_html  += JKY.set_tag_amount		(my_field); break;
			case 'checkbox'					: my_html  += JKY.set_tag_checkbox	(my_field); break;
			case 'date'						: my_html  += JKY.set_tag_date		(my_field); break;
			case 'single_select_dropdown'	: my_html  += JKY.set_tag_select		(my_field); break;
			case 'textarea'					: my_html  += JKY.set_tag_textarea	(my_field); break;
			default							: my_html  += JKY.set_tag_text		(my_field);
		}
		
		my_html += '</div><br>';
	})
	return my_html;
}
