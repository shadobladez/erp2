$(function(){
    $.get('/controls?group_set=Status+Codes', setup_selector);

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

function setup_selector(mytickets) {

    var mytrs = '';

    for (i = 0; i < mytickets.length; i++) {
        var myrow= mytickets[i];
        mytrs += '<option value = "'+myrow.name+'">'+myrow.name+'</option>';
    }

    $('#jky-app-select').html(mytrs);
}

function setup_group(){
    var mystatus = $('#jky-app-select').find(":selected").text();
    $.get('/tickets?status=' + mystatus, appendToList);
};

function appendToList(mytickets) {

    var mytrs = '';

    for (i = 0; i < mytickets.length; i++) {
        var myrow= mytickets[i];
        mytrs += ''
            +    '<tr id='+ myrow.id +' onclick="select_row('+myrow.id+')">'
            +       '<td class="jky-td-checkbox"><input type="checkbox" onclick="JKY.App.set_checkbox(this)" row_id="900064"></td>'
            +       '<td class="jky-td-input">'+myrow.opened_at+'</td>'
            +       '<td class="jky-td-normal">'+myrow.worked+'</td>'
            +       '<td class="jky-td-normal">'+myrow.priority+'</td>'
            +       '<td class="jky-td-status">'+myrow.category+'</td>'
            +       '<td class="jky-td-status">'+myrow.description+'</td>'
            +     '</tr>';
    }

    $('#jky-table-body').html(mytrs);
}

function process_addNew () {

    var my_group_set =        $('#jky-app-select').val();
    var my_opened_at =        $('#jky-opened-at').val();
    var my_opened_by =        $('#jky-opened-by').val();
    var my_assigned_at =      $('#jky-assigned-at').val();
    var my_assigned_by =      $('#jky-assigned-by').val();
    var my_closed_at =        $('#jky-closed-at').val();
    var my_closed_by =        $('#jky-closed-by').val();
    var my_worked=            $('#jky-worked').val;
    var my_priority =         $('#jky-priority').val();
    var my_category =         $('#jky-category').val();
    var my_description =      $('#jky-description').val();
    var my_resolution =       $('#jky-resolution').val();

    var my_data =
            'group_set=' + my_group_set
            +'&opened_at='+ my_opened_at
            +'&opened_by='+ my_opened_by
            +'&assigned_at='+ my_assigned_at
            +'&assigned_by='+ my_assigned_by
            +'&closed_at='+ my_closed_at
            +'&closed_by='+ my_closed_by
            +'&worked='+ my_worked
            +'&priority='+ my_priority
            +'&category='+ my_category
            +'&description='+ my_description
            +'&resolution='+ my_resolution;



    $.ajax({
        type: 'PUT',
        url:'/ticket',
        data: my_data
    }).done(function(){
            console.log('Added New');
            setup_group();
//                $('#jky-table-body tr[id='+my_id+']').add();
        });
};

function process_filter (the_id){
    var myfilter= $('#jky-app-filter').val();
    $.get('/ticketX?filter=' +  myfilter, appendToList);
};

function select_row(the_id){
    $.get('/ticket?id=' + the_id, display_form);
};

function display_form (the_row){
    $('#jky-id').val(the_row.id);
    $('#jky-opened-at').val(the_row.opened_at);
    $('#jky-opened-by').val(the_row.opened_by);
    $('#jky-assigned-at').val(the_row.assigned_at);
    $('#jky-assigned-by').val(the_row.assigned_by);
    $('#jky-closed-at').val(the_row.closed_at);
    $('#jky-closed-by').val(the_row.closed_by);
    $('#jky-priority').val(the_row.priority);
    $('#jky-category').val(the_row.category);
    $('#jky-worked').val(the_row.worked);
    $('#jky-description').val(the_row.description);
    $('#jky-resolution').val(the_row.resolution);
};

function process_save (){
    var my_id =           $('#jky-id').val();
    var my_opened_at =    $('#jky-opened-at').val();
    var my_opened_by =    $('#jky-opened-by').val();
    var my_assigned_at =  $('#jky-assigned-at').val();
    var my_assigned_by =  $('#jky-assigned-by').val();
    var my_closed_at =    $('#jky-closed-at').val();
    var my_closed_by =    $('#jky-closed-by').val();
    var my_priority =     $('#jky-priority').val();
    var my_category =     $('#jky-category').val();
    var my_worked =       $('#jky-worked').val();
    var my_description =  $('#jky-description').val();
    var my_resolution =   $('#jky-resolution').val();

    var my_data = 'id=' + my_id
        +'&category='+ my_category
        +'&opened_at='+ my_opened_at
        +'&opened_by='+ my_opened_by
        +'&assigned_at='+ my_assigned_at
        +'&assigned_by='+ my_assigned_by
        +'&closed_at='+ my_closed_at
        +'&closed_by='+ my_closed_by
        +'&priority='+ my_priority
        +'&category='+ my_category
        +'&worked='+ my_worked
        +'&description='+ my_description
        +'&resolution='+ my_resolution;
    $.post('/ticket', my_data, setup_group);

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
        type: 'DELETE', url:'/ticket?' + 'id=' + my_id
    }).done(function(){
            console.log('Deleted');
            $('#jky-table-body tr[id='+my_id+']').remove();
        });

};
