$(function(){
    $.get('../controls?group_set=Root', setup_selector);

    $('#jky-app-select').click (function(){
        setup_group();
    })

    $('#jky-action-add-new').click (function(){
        process_addNew();
    });

    $('#jky-app-filter').change (function(){
        process_filter();
    });

    $('#jky-action-save').click(function() {
        process_save();
    });

    $('#jky-action-copy').click(function(){
       process_copy();
    });

    $('#jky-action-delete').click(function(){
        process_delete();
    });

    /*
    $('jky-action-cancel').click(function(){
       process_cancel();
    });
    */
});

function setup_selector(mycontrols) {

    var mytrs = '';

    for (i = 0; i < mycontrols.length; i++) {
        var myrow= mycontrols[i];
        mytrs += '<option value = "'+myrow.name+'">'+myrow.name+'</option>';
    }

    $('#jky-app-select').html(mytrs);
}

function setup_group(){
    var mygroupset = $('#jky-app-select').find(":selected").text();
    $.get('/controls?group_set=' + mygroupset, appendToList);
};

function appendToList(mycontrols) {

    var mytrs = '';

    for (i = 0; i < mycontrols.length; i++) {
        var myrow= mycontrols[i];
        mytrs += ''
            +    '<tr id='+ myrow.id +' onclick="select_row('+myrow.id+')">'
            +       '<td class="jky-td-checkbox"><input type="checkbox" onclick="JKY.App.set_checkbox(this)" row_id="900064"></td>'
            +       '<td class="jky-td-input">'+myrow.sequence+'</td>'
            +       '<td class="jky-td-normal">'+myrow.name+'</td>'
            +       '<td class="jky-td-normal">'+myrow.value+'</td>'
            +       '<td class="jky-td-status">'+myrow.status+'</td>'
            +     '</tr>';
    }

    $('#jky-table-body').html(mytrs);
}

function process_addNew () {

    var my_group_set =  $('#jky-app-select').val();
    var my_sequence =   $('#jky-sequence').val();
    var my_name =       $('#jky-name').val();
    var my_value =      $('#jky-value').val();
    var my_status =     $('#jky-status').val();

    var my_data =
        'group_set=' + my_group_set
        +'&sequence='+ my_sequence
        +'&name='+ my_name
        +'&value='+ my_value
        +'status='+ my_status;


    $.ajax({
        type: 'PUT',
        url:'/control',
        data: my_data
    }).done(function(){
            console.log('Added New');
            setup_group();
//                $('#jky-table-body tr[id='+my_id+']').add();
        });
};

function process_filter (the_id){
    var myfilter= $('#jky-app-filter').val();
    $.get('/controlX?filter=' +  myfilter, appendToList);
};

function select_row(the_id){
    $.get('/control?id=' + the_id, display_form);
};

function display_form (the_row){
    $('#jky-id').val(the_row.id);                            $('#jky-status').val(the_row.status);
    $('#jky-sequence').val(the_row.sequence);
    $('#jky-name').val(the_row.name);
    $('#jky-value').val(the_row.value);
};

function process_save (){
    var my_id =         $('#jky-id').val();
    var my_status =     $('#jky-status').val();
    var my_sequence =   $('#jky-sequence').val();
    var my_name =       $('#jky-name').val();
    var my_value =      $('#jky-value').val();

    var my_data = 'id=' + my_id
        +'&status='+ my_status
        +'&sequence='+ my_sequence
        +'&name='+ my_name
        +'&value='+ my_value;
    $.post('/control', my_data, setup_group);

};

function process_copy (){
    process_addNew();
};

/*
function process_cancel (){
};
*/

function process_delete (){
    if(!confirm('Are you sure?')){
        return false;
    }

    var my_id = $('#jky-id').val();

    $.ajax({
       type: 'DELETE', url:'/control?' + 'id=' + my_id
    }).done(function(){
        console.log('Deleted');
            $('#jky-table-body tr[id='+my_id+']').remove();
    });

};