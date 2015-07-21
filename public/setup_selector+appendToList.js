$(function(){
  $.get('/controls?group_set=Root', setup_selector);

function setup_selector(mycontrols) {

    $.each( mycontrols, function( key, value ) {
        console.log( key + ": " + value );
    });

    var mytrs = '';

    for (i = 0; i < mycontrols.length; i++) {
        var myrow= mycontrols[i];
        mytrs += ''
            +       '<option value = "'+myrow.name+'">'+myrow.name+'</option>'
    }

    $('#jky-app-select').html(mytrs);
}

});
function setup_group(){
    var mygroupset = $('#jky-app-select').find(":selected").text();
    $.get('/controls?group_set=' + mygroupset, appendToList);
};

function appendToList(mycontrols) {

    $.each( mycontrols, function( key, value ) {
        console.log( key + ": " + value );
    });

    var mytrs = '';

    for (i = 0; i < mycontrols.length; i++) {
        var myrow= mycontrols[i];
        mytrs += ''
            +    '<tr row_id="900064" onclick="JKY.App.display_form(this)">'
            +       '<td class="jky-td-checkbox"><input type="checkbox" onclick="JKY.App.set_checkbox(this)" row_id="900064"></td>'
            +       '<td class="jky-td-input">'+myrow.sequence+'</td>'
            +       '<td class="jky-td-normal">'+myrow.name+'</td>'
            +       '<td class="jky-td-normal">'+myrow.value+'</td>'
            +       '<td class="jky-td-status">'+myrow.status+'</td>'
            +     '</tr>';
    }

    $('#jky-table-body').html(mytrs);
}
