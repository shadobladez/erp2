<?

function set_new_company( $id ) {
     set_session( 'company_id'  , $id );
     set_session( 'company_name', max_size( get_table_value( 'Companies', 'name', $id ), 40 ));

//   clean up previous [Company] search sessions
     unset_session(   'contact_search'  );
     unset_session(    'domain_search'  );
     unset_session(  'playlist_search'  );
     unset_session(      'user_search'  );
     unset_session(     'video_search'  );

//   clean up previous [Company] page sessions
     unset_session(   'contact_page'    );
     unset_session(    'domain_page'    );
     unset_session(  'playlist_page'    );
     unset_session(      'user_page'    );
     unset_session(     'video_page'    );
}

function send_email_welcome( $user_id, $email ) {
     if(  '' == $email )
          return '';

     $src = 'Email';
     return ''
          . '<a class=image href="' . INDEX . get_session( 'contr' ) . '/email?id=' . $user_id . '"' . '>'
          . image_over( $src )
          . '</a>'
          ;
}

function send_email_letter( $user_id, $email ) {
     $src = 'Email';
     return ''
          . '<a class=image href="#" onClick="popup_emails( ' . $user_id . ' ); return false;" >'
          . image_over( $src )
          . '</a>'
          ;
}

function send_email_support( $video_id, $number ) {
     $src = 'Email';
     if(  $number )
          return ''
               . '<a class=image href="' . INDEX . get_session( 'contr' ) . '/email?id=' . $video_id . '"' . '>'
               . image_over( $src )
               . '</a>'
               ;
     else return '';
}

function see_video( $src, $row ) {
     if( !$row[ 'delivery_key' ])
          return '';

     if(  $row[ 'position' ] != 'EM' )     
          return ''
               . '<a class=image href="' . INDEX . get_session( 'contr' ) . '/preview' . get_session( 'action' ) . '?id=' . $row[ 'video_number' ] . '"' . '>'
               . image_over( $src, 'align=absmiddle' )
               . '</a>'
               ;

//   get swf name from Swfs
     $swf_name = 'OverAllClear';
     if(  $row[ 'swf_id' ]) {
          $swf_id = $row[ 'swf_id' ];
          $status = get_table_value( 'Swfs', 'status', $swf_id );
//        if unique swf, video id as swf name
          if(  'U' == $status )
               $swf_name = $row[ 'video_number' ];
          else $swf_name = get_table_value( 'Swfs', 'name', $swf_id );
     }

     return image_over( $src, 
            ' align=absmiddle'
          . ' onClick="javascript:popup_play_video('
          . '{ swf:\''        . $swf_name . '\''
          . ', flv:\''        . $row[ 'delivery_key'] . '\''
          . ', width:'        . $row[ 'width'       ]
          . ', height:'       . $row[ 'height'      ]
          . ', position:\''   . $row[ 'position'    ] . '\''
          . ', website:\''    . WEB_SITE . '\''
          . ' }); return false;"'
          );
}

function play_light_box( $src, $row ) {
     if( !$row[ 'delivery_key' ])
          return '';

     return ''
          . '<a href=# onclick="im_auto_lb({ video_id:' . $row[ 'video_number' ] . ' });">'
          . image_over( $src, 'align=absmiddle' )
          . '</a>'
          ;
}

function live_video( $src, $row ) {
     if( !$row[ 'delivery_key' ])
          return '';

     $live_url = get_session( 'company_url' );
     if(  $live_url == '' )
          $live_url = get_control_value( 'SK', 'SamplePreview' );

     if(  $row[ 'position' ] == 'EM' )     
          return '';
     else return ' &nbsp; '
               . '<a class=image href="#" onClick="popup_live( \'' . WEB_SITE . 'im_live.php?url=' . $live_url . '&id=' . $row[ 'video_number' ] . '\' ); return false;" >'
               . image_over( $src, 'align=absmiddle' )
               . '</a>'
               ;
}
/*
function sample_preview( $src, $row ) {
     if( !$row[ 'delivery_key' ])
          return '';

     if(  $row[ 'position' ] == 'EM' )     
          return '';
     else return ' &nbsp; '
               . '<a class=image href="#" onClick="popup_live( \'' . WEB_SITE . 'im_live.php?url=' . get_control_value( 'SK', 'SamplePreview' ) . '&id=' . $row[ 'video_number' ] . '\' ); return false;" >'
               . image_over( $src, 'align=absmiddle' )
               . '</a>'
               ;
}
*/

function sample_preview( $src, $company_number, $video_number, $video_type='VSP' ) {
     return '<a class=image href="#" onClick="popup_live( \''
          . WEB_SITE . 'im_live.php?type=' . $video_type . '&company=' . $company_number . '&id=' . $video_number . '&url=' . get_control_value( 'SK', 'SamplePreview' )
          . '\' );" >'
          . image_over( $src, 'align=absmiddle' )
          . '</a>'
          ;
}

function view_url( $url ) {
     $src = 'View';
     if(  $url )
          return ''
               . '<a class=image href="#" onClick="popup_live( \'' . WEB_SITE . 'im_live.php?url=' . $url . '\' ); return false;" >'
               . image_over( $src, 'align=absmiddle' )
               . '</a>'
               ;
     else return '';
}

function select_date_type( $name, $value, $extra='' ) {
     return NL . '<select id="' . $name . '" name="' . $name . '" ' . $extra . ' onchange="onChangeReport( this );">'
          . get_control_options( 'DT', $name, $value )
          . NL . '</select>'
          ;
}

function select_sort_by( $name, $value, $extra='' ) {
     return NL . '<select id="' . $name . '" name="' . $name . '" ' . $extra . ' onchange="onChangeReport( this );">'
          . get_control_options( 'SB', $name, $value )
          . NL . '</select>'
          ;
}

function select_video_type( $name, $value, $extra='' ) {
     return NL . '<select id="' . $name . '" name="' . $name . '" ' . $extra . ' onchange="onChangeReport( this );">'
          . get_control_options( 'VT', $name, $value )
          . NL . '</select>'
          ;
}

function select_payment( $name, $value, $extra='' ) {
     return NL . '<select id="' . $name . '" name="' . $name . '" ' . $extra . ' onchange="onChangePayment( this );">'
          . get_control_options( 'PM', $name, $value )
          . NL . '</select>'
          ;
}

function select_swf( $name, $value ) {
     if( !is_permitted( MINIMUM_TO_UPDATE )
     and  $value == 1 )
          return output_text( 'Custom Nav', 10 );

     $sql = 'SELECT id, name, thumb'
          . '  FROM Swfs'
          . ' WHERE company_id = ' . get_session( 'control_company', COMPANY_ID )
          . '   AND ( status = "A" or status = "U" )'
          . ' ORDER BY name'
          ;
     $db   = Zend_Registry::get( 'db' );
     $rows = $db->fetchAll( $sql );

     if(  $value == '' )      $value = 1160;

     $options = '';
          $selected = ( '' == $value ? '" selected="selected' : '' );
//        $options .= NL . '<option value="' . '' . $selected . '">' . 'Undefined' . '</option>';
     foreach( $rows as $row ) {
          $selected = ( $row[ 'id' ] == $value ? ' selected="selected"' : '' );
          $options .= NL . '<option value="' . $row[ 'id'] . '"'. $selected . '>' . $row[ 'name' ] . '</option>';
     }
     return NL . '<select id="' . $name . '" name="' . $name . '" onchange="onChangeSwf( this );">'
          . $options
          . NL . '</select>'
          ;
}

function select_talent( $name, $value ) {
     $model = MODEL . 'Users'      ; $Users       = new $model();
     $rows  = $Users->getRows( 'Users.status = "A" AND Users.user_type = "talent"', 'name' );     

     $options = '';
          $selected = ( '' == $value ? '" selected="selected' : '' );
          $options .= NL . '<option value="' . '' . $selected . '">' . 'Undefined' . '</option>';
     foreach( $rows as $row ) {
          $selected = ( $row[ 'id' ] == $value ? '" selected="selected' : '' );
          $options .= NL . '<option value="' . $row[ 'id' ] . $selected . '">' . put_name( $row[ 'name' ]) . '</option>';
     }
     return NL . '<select id="' . $name . '" name="' . $name . '">' . $options . NL . '</select>';
}

function select_contact( $name, $value ) {
     $company_id = get_session( 'company_id' );
     if( !$company_id )       return '';

     $model = MODEL . 'Users'      ; $Users       = new $model();
     $rows  = $Users->getRows( 'Users.status = "A" AND Users.company_id = ' . $company_id, 'name' );

     $options = '';
          $selected = ( '' == $value ? '" selected="selected' : '' );
//          $options .= NL . '<option value="' . '' . $selected . '">' . translate( 'Undefined' ) . '</option>';
     foreach( $rows as $row ) {
          $selected = ( $row[ 'id' ] == $value ? '" selected="selected' : '' );
          $options .= NL . '<option value="' . $row[ 'id' ] . $selected . '">' . put_name( $row[ 'name' ]) . '</option>';
     }
     return NL . '<select id="' . $name . '" name="' . $name . '">' . $options . NL . '</select>';
}

function select_support( $name, $value ) {
     $model = MODEL . 'Users'      ; $Users       = new $model();
#    $rows  = $Users->getRows( 'Users.status = "A" AND Users.company_id = ' . COMPANY_ID, 'name' );     
     $rows  = $Users->getRows( 'Users.status = "A" AND Users.company_id = ' . get_session( 'user_company' ) . ' AND Users.user_level >= ' . MINIMUM_TO_UPDATE, 'name' );     

     $options = '';
          $selected = ( '' == $value ? '" selected="selected' : '' );
          $options .= NL . '<option value="' . '' . $selected . '">' . translate( 'Undefined' ) . '</option>';
     foreach( $rows as $row ) {
          $selected = ( $row[ 'id' ] == $value ? '" selected="selected' : '' );
          $options .= NL . '<option value="' . $row[ 'id' ] . $selected . '">' . put_name( $row[ 'name' ]) . '</option>';
     }
     return NL . '<select id="' . $name . '" name="' . $name . '">' . $options . NL . '</select>';
}

function select_company( $name, $value ) {
     $model = MODEL . 'Companies'  ; $Companies   = new $model();
     $rows  = $Companies->getRows( 'Companies.status = "A"', 'name' );     

     $options = '';
          $selected = ( '' == $value ? '" selected="selected' : '' );
#         $options .= NL . '<option value="' . '' . $selected . '">' . translate( 'Undefined' ) . '</option>';
     foreach( $rows as $row ) {
          $selected = ( $row[ 'id' ] == $value ? '" selected="selected' : '' );
          $options .= NL . '<option value="' . $row[ 'id' ] . $selected . '">' . $row[ 'name' ] . '</option>';
     }
     return NL . '<select id="' . $name . '" name="' . $name . '" size=10 style="height:160px">' . $options . NL . '</select>';
}

?>