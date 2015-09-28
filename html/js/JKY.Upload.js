"use strict";
var JKY = JKY || {};
/**
	program:	JKY.Upload.js - process all upload functions

	require:	Uploader.js
*/
/**
	JKY.Photo = JKY.Upload(
		{ object_name	: 'JKY.Photo'
		, table_name	: 'Contacts'
		, directory		: 'contacts'
		, field_name	: 'photo'
		, title			: 'Photo files'
		, extensions	: 'jpg,gif,png'
		, button_id		: 'jky-upload-photo'
		, filename_id	: 'jky-upload-name'
		, percent_id	: 'jky-upload-percent'
		, progress_id	: 'jky-upload-progress'
		, img_id		: 'jky-photo-img'
		, download_id	: 'jky-download-photo'
		});
*/
JKY.Upload = function(the_args) {
	var PROGRAM_NAME	= 'JKY.Upload.js';
	var PROGRAM_VERSION	= '1.0.0';

	var my_args = the_args;
	var my_row_id;
	var my_saved_name;

	var my_out_photo = function(the_photo) {
		var my_html = '';
		if (the_photo == null) {
			my_html = '<img id="' + my_args.img_id + '" class="jky-thumb" src="/img/placeholder.png" />';
		}else{
//			the_photo = file_name,file_time,file_size
			var my_names = the_photo.split(',');
			var my_extension = JKY.get_file_type(my_names[0]);
			my_html = '<a href="' + 'jky_download.php?file_name=' + my_args.directory + '/' + my_row_id + '.' + my_extension + '">'
				  if (my_extension == 'doc' ) {my_html += '<img id="' + my_args.img_id + '" class="jky-thumb" src="/img/doc.png" />';
			}else if (my_extension == 'docx') {my_html += '<img id="' + my_args.img_id + '" class="jky-thumb" src="/img/doc.png" />';
			}else if (my_extension == 'pdf' ) {my_html += '<img id="' + my_args.img_id + '" class="jky-thumb" src="/img/pdf.png" />';
			}else if (my_extension == 'sql' ) {my_html += '<img id="' + my_args.img_id + '" class="jky-thumb" src="/img/sql.png" />';
			}else if (my_extension == 'xml' ) {my_html += '<img id="' + my_args.img_id + '" class="jky-thumb" src="/img/xml.png" />';
			}else if (my_extension == 'xls' ) {my_html += '<img id="' + my_args.img_id + '" class="jky-thumb" src="/img/xls.png" />';
			}else if (my_extension == 'xps' ) {my_html += '<img id="' + my_args.img_id + '" class="jky-thumb" src="/img/xps.png" />';
			}else if (my_extension == 'zip' ) {my_html += '<img id="' + my_args.img_id + '" class="jky-thumb" src="/img/zip.png" />';
			}else{
				var my_time = my_names[1];
				my_html += '<img id="' + my_args.img_id + '" class="jky-thumb" src="/thumbs/' + my_args.directory + '/' + my_row_id + '.png?time=' + my_time + '" />';
			}
			my_html += '</a>';
		}
		return my_html;
	}

	var my_photo = new plupload.Uploader(
		{ browse_button	: my_args.button_id
		, runtimes		: 'html5,flash'
		, url			: 'plupload.php'
		, flash_swf_url	: 'swf/plupload.flash.swf'
		, filters		: [{title:my_args.title, extensions:my_args.extensions}]
		});

	my_photo.bind('Init', function(up, params) {});

	my_photo.bind('FilesAdded', function(up, files) {
		JKY.show('jky_loading');
		$.each(files, function(i, file) {
			JKY.set_html(my_args.filename_id, file.name);
			my_saved_name = file.name.toLowerCase();
			file.name = my_args.directory + '.' + my_row_id + '.' + my_saved_name;
		});
		up.refresh();	//	reposition Flash/Silverlight
		setTimeout(my_args.object_name + '.start_upload()', 100);
	});

	my_photo.bind('UploadProgress', function(up, file) {
		JKY.set_html(my_args.percent_id, file.percent + '%');
		JKY.set_css (my_args.progress_id, 'width', file.percent + '%');
	});

	my_photo.bind('FileUploaded', function(up, file) {
		JKY.display_message('File ' + my_saved_name + ' uploaded');
		JKY.set_html(my_args.percent_id, '100%');

		var my_time = JKY.get_now();
		var my_photo = my_saved_name + ',' + my_time + ',' + file.size;
		JKY.set_html(my_args.download_id, my_out_photo(my_photo));

		var my_data =
			{ method: 'update'
			, table :  my_args.table_name
			, set	:  my_args.field_name + '=\'' + my_photo + '\''
			, where :  'id=' + my_row_id
			};
		JKY.ajax(false, my_data);

		JKY.hide('jky_loading');
	});

	my_photo.bind('Error', function(up, error) {
		JKY.show('jky_loading');
		JKY.display_message('error: ' + error.code + '<br>message: ' + error.message + (error.file ? '<br> file: ' + error.file.name : ''));
		up.refresh();	//	reposition Flash/Silverlight
	});

	my_photo.init();

	return {
		  set_row_id	: function(the_row_id)	{		my_row_id = the_row_id;}
		, out_photo		: function(the_photo)	{return my_out_photo(the_photo);}
		, start_upload	: function()			{		my_photo.start();}
	};
};