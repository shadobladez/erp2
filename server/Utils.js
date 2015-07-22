var JKY = JKY || {};

JKY.get_date = function() {
    var  my_today = new Date();
    var  my_year	= my_today.getFullYear();
    var  my_month	= my_today.getMonth()+1;	if (my_month < 10)	my_month= '0' + my_month;
    var  my_day		= my_today.getDate ();		if (my_day   < 10)	my_day	= '0' + my_day	;
    return my_year + '-' + my_month + '-' + my_day;
}

