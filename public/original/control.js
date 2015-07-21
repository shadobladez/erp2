$(function(){
  $.get('/cities', appendToList);

  $('form').on('submit', function(event) {
    event.preventDefault();

    var form = $(this);
    var cityData = form.serialize();

    $('.alert').hide();

    $.ajax({
      type: 'POST', url: '/cities', data: cityData
    })
    .error(function() {
      $('.alert').show();
    })
    .success(function(cityName){
      appendToList([cityName]);
      form.trigger('reset');
    });
  });

  function appendToList(mycontrols) {

      $.each( mycontrols, function( key, value ) {
          console.log( key + ": " + value );
      });

      var mytrs = '';

      var Xmycontrols = [

            {sequence: '0', name: 'Company Types', value: '', status: 'active'}
          , {sequence: '0', name: 'Priorities', value: '', status: 'active'}
          , {sequence: '0', name: 'Languages', value: '', status: 'active'}
        ];
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
/*
    var list = [];
    var content, city;
    for(var i in cities){
      city = cities[i];
      content = '<a href="/cities/'+city+'">'+city+'</a>'+ // + // example on how to serve static images
        ' <a href="#" data-city="'+city+'">'+
        '<img src="delete.png" width="15px"></a>';
      list.push($('<li>', { html: content }));
    }
*/
    $('#jky-table-body').append(mytrs);
  }


  $('.city-list').on('click', 'a[data-city]', function (event) {
    if(!confirm('Are you sure ?')){
      return false;
    }

    var target = $(event.currentTarget);

    $.ajax({
      type: 'DELETE',
      url: '/cities/' + target.data('city')
    }).done(function () {
      target.parents('li').remove();
    });
  });

});
