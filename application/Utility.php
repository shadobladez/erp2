<?
function get_language() {
    return 'en_US';
}

function translate($text) {
     if( $text == '' )
          return '';

     include 'en_US.php';

     $result = '';
     if( isset($translations[$text]) ) {
          $result = $translations[$text];
     } else {
          $names = explode('<br>', $text);
          for( $i=0; $i<count($names); $i++ ) {
               $name = $names[$i];
               if( isset($translations[$name]) ) {
                    $translation = $translations[$name];
               } else {
                    $translation = $name;
               }
               $result .= ($i == 0) ? '' : '<br>';
               $result .= $translation;
          }
     }
     return $result;
}

function search_other_party( $name, $value, $size, $extra='' ) {
     return '<div style="position:relative">'
          . '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' autocomplete="off"'
          . ' onkeyup="search_other_party( this, event );"'
          . ' />'
          . '<div id="other_partys" style="z-index:1000; position:absolute; top:24px; left:0px; width:420px;"></div>'
          . '</div>'
          ;
}

function search_buyer_name( $name, $value, $size, $extra='' ) {
     return '<div style="position:relative">'
          . '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' autocomplete="off"'
          . ' onkeyup="search_buyer_name( this, event );"'
          . ' />'
          . '<div id="buyer_names" style="z-index:1000; position:absolute; top:24px; left:0px; width:420px;"></div>'
          . '</div>'
          ;
}

function search_seller_name( $name, $value, $size, $extra='' ) {
     return '<div style="position:relative">'
          . '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' autocomplete="off"'
          . ' onkeyup="search_seller_name( this, event );"'
          . ' />'
          . '<div id="seller_names" style="z-index:1000; position:absolute; top:24px; left:0px; width:420px;"></div>'
          . '</div>'
          ;
}

function search_company_name( $name, $value, $size, $extra='' ) {
     return '<div style="position:relative">'
          . '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' autocomplete="off"'
          . ' onkeyup="search_company_name( this, event );"'
          . ' />'
          . '<div id="company_names" style="z-index:1000; position:absolute; top:24px; left:0px; width:420px;"></div>'
          . '</div>'
          ;
}

function search_vendor_name( $name, $value, $size, $extra='' ) {
     return '<div style="position:relative">'
          . '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' autocomplete="off"'
          . ' onkeyup="search_vendor_name( this, event );"'
          . ' />'
          . '<div id="vendor_names" style="z-index:1000; position:absolute; top:24px; left:0px; width:420px;"></div>'
          . '</div>'
          ;
}

function search_user_name( $name, $value, $size, $extra='' ) {
     return '<div style="position:relative">'
          . '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' autocomplete="off"'
          . ' onkeyup="search_user_name( this, event );"'
          . ' />'
          . '<div id="user_names" style="z-index:1000; position:absolute; top:24px; left:0px; width:420px;"></div>'
          . '</div>'
          ;
}

function search_user_email( $name, $value, $size, $extra='' ) {
     return '<div style="position:relative">'
          . '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' autocomplete="off"'
          . ' onkeyup="search_user_email( this, event );"'
          . ' />'
          . '<div id="user_emails" style="z-index:1000; position:absolute; top:24px; left:0px; width:420px;"></div>'
          . '</div>'
          ;
}

function search_client_name( $name, $value, $size, $extra='' ) {
/*
     return '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' autocomplete="off"'
          . ' onkeyup="search_client_name( this, event );"'
          . ' />'
          ;
*/
     $names = explode( '|', $value );
     $count = count( $names );
      $first_name = $count > 0 ? $names[ 0 ] : '';
     $middle_name = $count > 1 ? $names[ 1 ] : '';
       $last_name = $count > 2 ? $names[ 2 ] : '';

if(  HAS_QUOTES ) {
     if(  get_session( 'contr' ) == 'orderstt2' ) {
          return search_hint( 11, 'First ',  'first_name',  $first_name )
               . search_hint( 11, 'Last  ',   'last_name',   $last_name )
               . '<div id="client_names"></div>'
               ;
     } else {
          return search_hint( 27, 'First ',  'first_name',  $first_name )
               . search_hint( 27, 'Last  ',   'last_name',   $last_name )
               . '<div id="client_names"></div>'
               ;
     }
} else {
     return search_hint( 16, 'First ',  'first_name',  $first_name )
          . search_hint( 16, 'Middle', 'middle_name', $middle_name )
          . search_hint( 16, 'Last  ',   'last_name',   $last_name )
          . '<div id="client_names"></div>'
          ;
}
}

function search_client_email( $name, $value, $size, $extra='' ) {
     return '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' autocomplete="off"'
          . ' onkeyup="search_client_email( this, event );"'
          . ' />'
//        . '<div id="client_emails" style="position:absolute; top:230px; left:245px; z-index:1000"></div>'
          . '<div id="client_emails" style="position:relative; top:24px; width:420px; z-index:1000"></div>'
          ;
}

function search_product_type( $name, $value, $size, $extra='' ) {
     return '<div style="position:relative">'
          . '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' autocomplete="off"'
          . ' onkeyup="search_product_type( this, event );"'
          . ' />' . small( 'req' )
//          . '<div id="product_types" style="z-index:1000; position:absolute; top:24px; left:0px; width:420px;"></div>'
          . '<div id="product_types" style="position:relartive; top:24px; width:420px; z-index:1000;"></div>'
          . '</div>'
          ;
}

function search_product_model( $name, $value, $size, $extra='' ) {
     return '<div style="position:relative">'
          . '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' autocomplete="off"'
          . ' onkeyup="search_product_model( this, event );"'
          . ' />' . small( 'req' )
          . '<div id="product_models" style="z-index:1000; position:absolute; top:24px; left:0px; width:420px;"></div>'
          . '</div>'
          ;
}

function put_tip( $name, $width=300 ) {
     return '<img src=' . SERVER_NAME . IMAGES . 'oculu/question.png onmouseover="tooltip.show( this, \'' . $name . '\',' . $width . ' );" onmouseout="tooltip.hide();" />';
}

function put_img( $name, $extra='' ) {
     return '<img src=' . SERVER_NAME . IMAGES . 'oculu/' . $name . ' ' . $extra . ' />';
}

function anchor( $the, $controller, $action, $title, $label, $class='' ) {
     $href = $the->url( array( 'controller'=>$controller, 'action'=>$action ));
     if(  $class != '' )
          $class = 'class="' . $class . '"';
     return '<a href="' . $href . '" title="' . $title . '">' . $label . '</a>';
}

function put_icon( $contr='', $extra='' ) {
     if(  $contr == '' ) {
          $contr = get_session( 'contr' );
     }
     $icon = 'h_' . $contr . '.png';
     return '<div id=icon><img alt="' . $icon . '" src="' . SERVER_NAME . IMAGES . $icon . '" /></div>';
}

function put_links( $left_link, $right_link ) {
     return ''
     . NL . '<div class=left  style="margin-top:5px;">' .  $left_link . '</div>'
     . NL . '<div class=right style="margin-top:5px;">' . $right_link . '</div>'
     . NL . '<div class=clear></div>'
     ;
}

function put_company() {
     return '<div id="header_company" class=right>' . get_session( 'company_name' ) . '</div>';
}

function put_member() {
     return '<div id="header_company" class=right>' . get_session( 'member_name' ) . '</div>';
}

function put_header( $contr='', $extra='' ) {
     if(  $contr == '' )
          $contr = get_session( 'contr' );

          if(  $contr == 'counters'     )    $contr = 'summary'       ;
     else if(  $contr == 'deposits'     )    $contr = 'accounting'    ;
     else if(  $contr == 'history'      )    $contr = 'orders'        ;
     else if(  $contr == 'homett'       )    $contr = 'dashboard'     ;
     else if(  $contr == 'houses'       )    $contr = 'address'       ;
     else if(  $contr == 'orderslx'     )    $contr = 'orders'        ;
     else if(  $contr == 'orderstt'     )    $contr = 'orders'        ;
     else if(  $contr == 'orderstt2'    )    $contr = 'orders'        ;
     else if(  $contr == 'packageslx'   )    $contr = 'packages'      ;
     else if(  $contr == 'paymentsca'   )    $contr = 'payments'      ;
     else if(  $contr == 'paymentslx'   )    $contr = 'payments'      ;
     else if(  $contr == 'productslx'   )    $contr = 'products'      ;
     else if(  $contr == 'productstt'   )    $contr = 'products'      ;
     else if(  $contr == 'productsdl'   )    $contr = 'products'      ;
     else if(  $contr == 'productstm'   )    $contr = 'products'      ;
     else if(  $contr == 'saleslx'      )    $contr = 'sales'         ;
     else if(  $contr == 'sentences'    )    $contr = 'dictionary'    ;
     else if(  $contr == 'times'        )    $contr = 'time_track'    ;
     else if(  $contr == 'tvideos'      )    $contr = 'talent'        ;
     else if(  $contr == 'userprojs'    )    $contr = 'my_projects'   ;

     else if(  $contr == 'transactions' )    $contr = ( get_claxx( 'init' ) == 'start' ) ? 'transactions' : 'my_transactions';
     else if(  $contr == 'vntps'        )    $contr = ( get_claxx( 'init' ) == 'start' ) ? 'memberships'  : 'my_memberships' ;

     if(  HAS_NFE_DL or HAS_NFE_TM ) {
          if(  $contr == 'companies'    )    $contr = 'nomes';
     }

     return ''
     . NL . '<div id="cont_header">'
//   . NL . '<div class=left  style="margin-top:10px;">' . image( 'h_' . $contr . '.png' ) . '</div>'
//   . NL . '<div class=right style="margin-top:13px;">' . $extra . '</div>'
     . NL . '<div class=left  style="padding-top:10px;">' . image( 'h_' . $contr . '.png' ) . '</div>'
     . NL . '<div class=right style="padding-top:13px;">' . $extra . '</div>'
     . NL . '<div class=clear></div>'
     . NL . '</div id="cont_header">'
     ;
}

function put_header_words( $words='', $extra='' ) {
     return '<div class=left  style="margin-top:10px;"><div id="header_name">' . translate( $words ) . '</div></div>'
     . NL . '<div class=right style="margin-top:13px;">' . $extra . '</div>'
     . NL . '<div class="clear"></div>'
     ;
}

function put_search( $new_link, $index, $first, $previous, $next ) {

if(  HAS_TAGS and get_session( 'contr' ) == 'users' )
     $s_tags = NL . '<div style="position:relative; top:18px; left:0px;">'
             . NL . '<input type="text" id="s_tags" name="s_tags" value="' . get_claxx( 's_tags' ) . '" onchange="submit()" size="30" />'
             . NL . '<span style="position:relative; top:-20px; left:-15px;">Tags:</span></div>';
else $s_tags = '';

     return ''
     . NL . '<div class=left  style="margin-top:5px;">'
     . NL . $new_link
     . NL . '</div>'

     . NL . '<div class=right style="margin-top:10px; width:280px;">'
     . NL . '<form action="' . INDEX . get_session( 'contr' ) . '/index" method="post">'
     . NL . '<input type="text" id="search" name="search" value="' . get_claxx( 'search' ) . '" onchange="submit()" size="30" />'
     . NL . '<img align="absbottom" alt="search.png" border="0" src="' . SERVER_NAME . IMAGES . 'search.png" />'
     . $s_tags
     . NL . '</form>'
     . NL . '</div>'

     . NL . '<div class=right>'

     . NL . '<div class=left   style="margin-top:5px; width:220px;">'
     . NL . link_to_button(    'First', $first    )
     . NL . link_to_button( 'Previous', $previous )
     . NL . link_to_button(     'Next', $next     )
     . NL . ' &nbsp; ' . $index
     . NL . '</div>'

     . NL . '</div>'
     . NL . '<div class=clear></div>'

     . NL . '<script>document.getElementById( "search" ).focus() </script>'
     ;
}

function put_return( $row=null, $previous=null, $next=null, $extra='' ) {
//     $html = NL . '<div class=left style="margin-top:5px;">';
     $html = '';

     if(  $row )
          $html .= NL . '<div id=posted>' . translate( 'Posted by ' ) . get_user_name( $row[ 'updated_by' ]) . ' (' . format_time( $row[ 'updated_at' ], 'short' ) . ') &nbsp; ' . $extra . '</div>';
     else $html .= add_new();

     $html .= ''
//     . NL . '</div>'
//     . NL . '<div class=right style="margin-top:5px;">'
     . NL . '<div class=right style="margin-top:-12px;">'
     . NL . link_to_image (   'Return', get_session( 'return' ))
     . NL . link_to_button( 'Previous', $previous )
     . NL . link_to_button(     'Next', $next     )
     . NL . '</div>'
     . NL . '<div class=clear></div>'
     ;

     return $html;
}

function put_return2( $row=null, $previous=null, $next=null, $extra='' ) {
     $html = '';

     if(  $row )
          $html .= NL . '<div id=posted style="margin-top:16px;">' . translate( 'Posted by ' ) . get_user_name( $row[ 'updated_by' ]) . ' (' . format_time( $row[ 'updated_at' ], 'short' ) . ') &nbsp; ' . $extra . '</div>';
     else $html .= add_new();

     $html .= ''
     . NL . '<div class=right style="margin-top:5px;">'
     . NL . link_to_image (   'Return', get_session( 'return' ))
     . NL . link_to_button( 'Previous', $previous )
     . NL . link_to_button(     'Next', $next     )
     . NL . '</div>'
     . NL . '<div class=clear></div>'
     ;

     return $html;
}

function get_pos_login() {
     if(  HAS_PROJECTS ) {
          set_session( 'start', 'Start-' . get_user_type() );
          return 'index.php/index/start';
     }

     if(  HAS_VIDEOS ) {
          if(  get_session( 'user_level' ) == MINIMUM_TO_BROWSE ) {
               return 'tvideos';
          } else {
               if(  is_permitted( MINIMUM_TO_UPDATE ))
                    return POS_LOGIN_INTERNAL;
               else return POS_LOGIN_EXTERNAL;
          }
     }

     if(  HAS_EVENTS ) {
          if( !is_session( 'event_id' )) {
               if(  is_permitted( MINIMUM_TO_UPDATE )) {
                    $model = MODEL . 'Events'     ; $Events      = new $model();
                    set_new_event( $Events->getIdByCompany( COMPANY_ID ));
                    return INDEX . POS_LOGIN_INTERNAL;
               } else {
                    if(  is_session( 'start' )) {
                         return 'index.php/index/start';
                    } else {
//                       return INDEX . 'index/dashboard';            //   index.php is required in linux server
                         return 'index.php/index/dashboard';          //   index.php is required in linux server
                    }
               }
          } else {
               if(  is_permitted( MINIMUM_TO_UPDATE ))
                    return POS_LOGIN_INTERNAL;
               else return POS_LOGIN_EXTERNAL;
          }
     } else {
          if(  is_permitted( MINIMUM_TO_UPDATE ))
               return POS_LOGIN_INTERNAL;
          else return POS_LOGIN_EXTERNAL;
     }
}

function search_status( $status, $chars ) {
     $url = INDEX . get_session( 'contr' );

     $name  = 'search_status';
     $extra = ' onclick="submit()"';
     $inputs= '';
     $first = '';
     $value = '';

     for( $n=0; $n<strlen( $chars); $n++ ) {
          $char  = substr( $chars, $n, 1 );
          if(  get_session( 'contr' ) == 'productsvn' )
               switch( $char ) {
                    case 'A'  : $value = 'Added'       ; break;
                    case 'S'  : $value = 'Submitted'   ; break;
                    case 'P'  : $value = 'Production'  ; break;
                    case '*'  : $value = 'All'         ; break;
               }
          else switch( $char ) {
                    case 'A'  : $value = 'Active'      ; break;
                    case 'I'  : $value = 'Inactive'    ; break;
                    case 'O'  : $value = 'Open'        ; break;
                    case 'S'  : $value = 'Sent'        ; break;
                    case 'L'  : $value = 'Locked'      ; break;
                    case 'C'  : $value = 'Closed'      ; break;
                    case '*'  : $value = 'All'         ; break;
               }

          $inputs .= $first . '<input type="radio" name="' . $name . '" value="' . $char . '"' . ( $status == $char ? ' checked' : '' ) . $extra . ' >' . $value;
          $first = '<br>';
     }

     return ''
     . NL . '<div class=right style="margin-top:3px;">'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=status>' . $inputs . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right style="margin-top:0px;">'
     . NL . '<span class=fs90>' . translate( 'Status:' ) . '</span>'
     . NL . '</div>'
          ;
}

function send_id( $error='', $id='' ) {
     if(  '' == $error )
          if(  '' == $id )
               $return = array( "success" => true );
          else $return = array( "success" => true , "id"    => $id    );
     else      $return = array( "success" => false, "error" => $error );

     echo json_encode( $return );
}

function send_rows( $rows ) {
//     $return = array( 'count' => count( $rows ), 'rows' => $rows );
//     echo json_encode( $return );
     echo json_encode( $rows );
}

function window_print() {
     return '<a href="javascript:window.print()" ><img align=absbottom border=0 src="' . SERVER_NAME . IMAGES . 'print.png" /></a>';
}

function fix_area( $name ) {
     $string = get_request( $name );
//     $string = trim( $string );
//     $string = str_replace( "\'"  , "'"      , $string );
//     $string = str_replace( '\"'  , '"'      , $string );
//     $string = str_replace( "\r\n", "<br>"   , $string );
//if( !HAS_PROJECTS ) {
//     $string = str_replace( '"'   , '&quot;' , $string );      //   this will affect [style="height:72px;"]
//     $string = str_replace( "'"   , '&#39;'  , $string );
//}
     return $string;
}

function fix_date( $name ) {
     if(  get_request( $name . '_1' ) == '0000'
     or   get_request( $name . '_2' ) ==   '00'
     or   get_request( $name . '_3' ) ==   '00' )
          return '';
     else return get_request( $name . '_1' ) . '-' . get_request( $name . '_2' ) . '-' . get_request( $name . '_3' );
}

function fix_date2( $name ) {
     if(  is_empty( get_request( $name )))
          return '';

     $dates = explode( '-', get_request( $name ));
          if(  DATE_FORMAT == 'mm-dd-yyyy' )      return $dates[ 2 ] . '-' . $dates[ 0 ] . '-' . $dates[ 1 ];
     else if(  DATE_FORMAT == 'dd-mm-yyyy' )      return $dates[ 2 ] . '-' . $dates[ 1 ] . '-' . $dates[ 0 ];
     else                                         return $dates[ 0 ] . '-' . $dates[ 1 ] . '-' . $dates[ 2 ];
}

//   change [ MMDDYY ] to [ YYYY-DD-MM ]
function fix_date3( $date ) {
     return '20' . substr( $date, 4, 2 ) . '-' . substr( $date, 0, 2 ) . '-' . substr( $date, 2, 2 );
}

//   change [ YYYY-DD-MM ] to [ MMDDYY ]
function fix_date4( $date ) {
     return substr( $date, 5, 2 ) . substr( $date, 8, 2 ) . substr( $date, 2, 2 );
}

function fix_amount( $name ) {
     $amount = get_request( $name );
     if(  DECIMAL_POINT == ',' )
          $amount = str_replace( ',', '.', $amount );
     else $amount = str_replace( ',', '' , $amount );

     $amount = str_replace( ' ', '', $amount );
     return $amount;
}

function fix_price( $amount ) {
     if(  DECIMAL_POINT == ',' )
          $amount = str_replace( ',', '.', $amount );
     else $amount = str_replace( ',', '' , $amount );

     $amount = str_replace( ' ', '', $amount );
     return $amount;
}

function fix_digits( $value ) {
     $digits = '';
     for( $n=0; $n<strlen( $value ); $n++ ) {
          $char = substr( $value, $n, 1 );
          if(  $char >= '0' and $char <= '9' )
               $digits .= $char;
     }
     return $digits;
}

function fix_user_name( $name ) {
     $names = explode( ' ', $name );
     $count = count( $names );
     $first  = '';
     $middle = '';
     $last   = '';
     for( $n=0; $n<$count; $n++ ) {
               if(  $n == $count-1 )    $last  = $names[ $n ];
          else if(  $n == 0 )           $first = $names[ $n ];
          else $middle .= ' ' . $names[ $n ];
     }
     return $first . '|' . trim( $middle ) . '|' . $last;
}

function set_is_zero          ( $name )                { return BR . translate( $name ) . ' ' . translate( 'is zero'            ); }
function set_is_invalid       ( $name )                { return BR . translate( $name ) . ' ' . translate( 'is invalid'         ); }
function set_is_required      ( $name )                { return BR . translate( $name ) . ' ' . translate( 'is required'        ); }
function set_already_taken    ( $name )                { return BR . translate( $name ) . ' ' . translate( 'already taken'      ); }
function set_not_numeric      ( $name )                { return BR . translate( $name ) . ' ' . translate( 'not numeric'        ); }
function set_not_found        ( $name )                { return BR . translate( $name ) . ' ' . translate( 'not found'          ); }
function set_outside_of_range ( $name )                { return BR . translate( $name ) . ' ' . translate( 'outside of range'   ); }
function set_size_is_under    ( $name, $size )         { return BR . translate( $name ) . ' ' . translate( 'size is under' ) . ' [' . $size . ']'; }
function set_size_is_above    ( $name, $size )         { return BR . translate( $name ) . ' ' . translate( 'size is above' ) . ' [' . $size . ']'; }

function changed_from_to      ( $name, $from, $to )    { return ', ' . translate( $name ) . ' '  . translate( 'changed from' ) . ' [' . $from . '] ' . translate( 'to' ) . ' [' . $to . ']'; }
function has_new_upload_file  ( $type, $file_name )    { return ', ' . translate( $type ) . ' '  . translate( 'has new upload file'  ) . ' [' . $file_name . ']'; }
function has_deleted_the_file ( $type, $file_name )    { return ', ' . translate( $type ) . ' '  . translate( 'has deleted the file' ) . ' [' . $file_name . ']'; }

function set_required( $school ) {
     if(  $school == '' || $school == 'Adult' )
          return ' ';
     else return '(required)';
}

function set_focus_on( $id ) {
     return ''
          . NL . '<script>document.getElementById( "' . $id . '" ).focus() </script>'
          . NL . '<script>document.getElementById( "' . $id . '" ).select()</script>'
          ;
}

function Xset_focus_on( $name ) {
     return ''
          . NL . '<script>document.getElementsByName( "' . $name . '" )[ 0 ].focus() </script>'
          . NL . '<script>document.getElementsByName( "' . $name . '" )[ 0 ].select()</script>'
          ;
}

function pad_zeros( $number, $length ) {
     $string = '' . $number;
     return str_pad( $string, $length, '0', STR_PAD_LEFT );
}

function put_line( $text ) {
     return NL . '<p>' . translate( $text ) . '</p><br />';
}

function put_note( $value ) {
     return NL . '<tr style="height:14px"><td colspan=2 align=left><span style="font-size:90%">' . translate( $value ) . '</span></td></tr>';
}

function put_required() {
     return '';
}

function required() {
     return small( translate( 'required' ) );
}

function put_message( $text, $value='' ) {
     return NL
          . '<span style="margin:18px; line-height:20px; font-size:100%;">' . translate( $text ) . '</span>'
          . '<span style="font-size:100%">' . $value . '</span>'
          . '<br />';
}

function put_space( $height=5 ) {
     return NL . '<img src="' . SERVER_NAME . IMAGES . 's.png" height=' . $height . 'px /><br>';
}

function space( $height=5 ) {
     return '<tr style="height:' . $height . 'px"><td></td></tr>';
}

function put_status( $status ) {
          if(  $status == 'A' )    $color = 'green' ;
     else if(  $status == 'O' )    $color = 'green' ;
     else if(  $status == 'T' )    $color = 'green' ;
     else if(  $status == 'L' )    $color = 'blue'  ;
     else if(  $status == 'E' )    $color = 'red'   ;
     else if(  $status == 'I' )    $color = 'red'   ;
     else if(  $status == 'X' )    $color = 'red'   ;
     else if(  $status == 'S' )    $color = 'yellow';
     else                          $color = 'black' ;

     return NL . '<td class="C ' . $color . '">' . translate( get_control_value( 'SC', $status )) . '</td>';
}

function small( $value ) {
     return '<span class=small>&nbsp;( ' . translate( $value ) . ' )</span>';
}

// --------------------------------------------------------------------------------------

function image( $button ) {
//     return '<img align="absbottom" alt="' . $button . '" border="0" src="' . SERVER_NAME . IMAGES . $button . '" />';
     return '<img alt="' . $button . '" border="0" src="' . SERVER_NAME . IMAGES . $button . '" />';
}

function image_language( $button ) {
//     return '<img align="absbottom" alt="' . $button . '" border="0" src="' . SERVER_NAME . IMAGES . get_session( 'language' ) . '/' . $button . '" />';
//     return '<img alt="' . $button . '" border="0" src="' . SERVER_NAME . IMAGES . get_session( 'language' ) . '/' . $button . '" />';
     return '<img alt="' . $button . '" border="0" src="' . SERVER_NAME . IMAGES . $button . '.png" align=absbottom />';
}

function image_over( $src, $extra='' ) {
     $dir = SERVER_NAME . IMAGES;
     $source = $src . '.png';
     return '<img border=0     src=\'' . $dir . 'u' . $source . '\' '
          . 'onMouseOver="this.src=\'' . $dir . 'o' . $source . '\'"'
          . ' onMouseOut="this.src=\'' . $dir . 'u' . $source . '\'"'
          . ' alt="' . $source . '"'
          . ' ' . $extra
          . ' />';
}

function image_select( $src, $contr, $action='index' ) {
     $dir = SERVER_NAME . IMAGES;
     $source = $src . '.png';
     $default    = 'b';
     $mouse_over = 'o';
     $mouse_out  = 'b';
     if( $contr == get_session( 'contr' ) ) {
          $default   = 'a';
          $mouse_out = 'a';
     }
     return '<a href="' . INDEX . $contr . '/' . $action . '">'
               . '<img border=0     src=\'' . $dir . $default    . $source . '\' '
               . 'onMouseOver="this.src=\'' . $dir . $mouse_over . $source . '\'"'
               . ' onMouseOut="this.src=\'' . $dir . $mouse_out  . $source . '\'"'
               . ' alt="' . $source . '"'
               . ' />'
          . '</a>';
}

function image_icon( $ext ) {
     $array = array( 'AI', 'DOC', 'JPG', 'PDF', 'PSD', 'XLS' );
     $ext = strtoupper( $ext );
     if( ! in_array( $ext, $array ))         $ext = 'Generic';
     return image( 'icon_' . $ext . '_big.png' );
}

function image_photo( $photo, $extra='' ) {
     return '<img class=border align="absbottom" ' . $extra . ' src="' . SERVER_NAME . PHOTOS . $photo . '" />';
}

function image_thumb( $photo, $extra='' ) {
     return '<img class=border align="absbottom" ' . $extra . ' src="' . SERVER_NAME . THUMBS . $photo . '" />';
}

function image_thumb_id( $id, $extra='' ) {
     $model = MODEL . 'Photos'     ; $Photos      = new $model();
     $row   = $Photos->getRowById( $id );
     $photo = $row[ 'id' ] . '.' . $row[ 'ext' ];
     return '<img class=border align="absbottom" ' . $extra . ' src="' . SERVER_NAME . THUMBS . $photo . '" />';
}

function nav_link( $text, $contr, $action='index', $id='' ) {
     if(  $contr == 'index' )      $contr  = 'index.php/' . $contr;
     if(  $id    != ''      )      $id     = '?id='       . $id   ;
     return NL . '<a href="' . INDEX . $contr . '/' . $action . $id . '">' . translate( $text ) . '</a>';
}

function nav_button( $src, $contr, $action='index' ) {
     if(  $contr == 'index' )      $contr  = 'index.php/' . $contr;
     return NL
          . '<a href="' . INDEX . $contr . '/' . $action . '">'
          . image_over( $src )
          . '</a>'
          ;
}

function link_button( $src, $contr, $action='index' ) {
     if(  $contr == get_session( 'contr' ))
          return NL . '<a href="' . INDEX . $contr . '/' . $action . '">' . image( 'a' . $src . '.png' ) . '</a>';
     else return NL . '<a href="' . INDEX . $contr . '/' . $action . '">' . image( 'b' . $src . '.png' ) . '</a>';
}

function link_to( $text, $action='index', $extra='', $title='' ) {
     if(  $extra != '' )      $extra = '?' . $extra;
     if(  $title != '' )      $title = ' title="' . $title . '"';
     return '<a href="' . INDEX . get_session( 'contr' ) . '/' . $action . $extra . '"' .  $title . '>' . translate( $text ) . '</a>';
}

function link_to_button( $src, $action ) {
     return( $action ? link_to_image( $src, get_session( 'contr' ) . '/' . $action ) : image_language( 'n' . $src ) . ' ' );
}

function link_to_image( $src, $action='index', $extra='', $title='' ) {
     if(  $extra != '' )      $extra = '?' . $extra;
     if(  $title != '' )      $title = ' title="' . $title . '"';
     return ''
          . '<a class=image href="' . INDEX . $action . $extra . '"' .  $title . '>'
          . image_over( $src, 'align=absbottom' )
          . '</a>'
          ;
}

function link_to_delete( $message, $href ) {
     return '<img align="absbottom"'
          . ' src="' . SERVER_NAME . IMAGES . 'del.png"'
          . ' onclick=\'popup_confirm('
          . ' "Confirm to delete the record?\n' . $message . '", "' . 'delete?' . $href . '"'
          . ' );\' />'
          ;
}

function link_to_move( $id, $sequence ) {
     $up  = '<a href="' . INDEX . get_session( 'contr' ) .   '/moveup?id='   . $id . '&sequence=' . $sequence . '" ><img align="absbottom" border=none src="' . SERVER_NAME . IMAGES .   'arrow_up.png" /></a>';
     $down= '<a href="' . INDEX . get_session( 'contr' ) .   '/movedown?id=' . $id . '&sequence=' . $sequence . '" ><img align="absbottom" border=none src="' . SERVER_NAME . IMAGES . 'arrow_down.png" /></a>';

     return $up . $down;
}

function link_to_header( $label, $field ) {
     if(  get_session( 'order_field' ) == $field ) {
          $order = get_session( 'order_seq' ) == 'ASC' ? 'asc' : 'desc';
          $order = '<img align="bottom" border="0" src="' . SERVER_NAME . IMAGES . $order . '.png" /> ';
     } else {
          $order = '';
     }
     return link_to( $order . translate( $label ), 'order', 'field=' . $field );
}

function link_to_function( $text, $onclick, $class ) {
     return '<a class=' . $class . ' href="#" onclick="' . $onclick . '; return false;">' . $text . '</a>';
}

function link_to_letters( $value ) {
     $letters = '*ABCDEFGHIJKLMNOPQRSTUVWXYZ';
     $links = '';
     for( $I=0; $I<strlen($letters); ++$I ) {
          $letter = $letters[ $I ];
          switch( $letter ) {
               case '*': $button = '&nbsp; All &nbsp;';   break;
               case 'I': $button = '&nbsp;I&nbsp;';       break;
               default ; $button = $letter;
          }
          $class  = $letter == $value ? ' class=active' : '';
          $links .= NL . '<a href="' . INDEX . get_session( 'contr' ) . '?letter=' . $letter . '"' . $class . '>' . $button . '</a>';
     }
     return NL . '<div class=clear></div>'
          . NL . '<div id="link_letters">' . $links . '</div>'
          ;
}

function link_to_digits( $value ) {
     $digits = '*123456789';
     $links = '';
     for( $I=0; $I<strlen($digits); ++$I ) {
          $digit = $digits[ $I ];
          switch( $digit ) {
               case '*': $button = '&nbsp; &nbsp;All &nbsp;';   break;
               default ; $button = $digit;
          }
          $class  = $digit == $value ? ' class=active' : '';
          $links .= NL . '<a href="' . INDEX . get_session( 'contr' ) . '?digit=' . $digit . '"' . $class . '>' . $button . '</a>';
     }
     return NL . '<div id="link_digits">' . $links . ' (first digit of tracking)'
          . NL . '</div>'
          ;
}

function set_last_href( $contr, $action, $extra ) {
     set_session( 'last_href', INDEX . $contr . '/' . $action . '?' . $extra );
}

function link_to_last_href( $text, $extra='' ) {
     return '<a href="' . get_session( 'last_href' ) . '" ' . $extra . '>' . $text . '</a>';
}

function search_hint( $size, $hint, $name, $value ) {
     if(  $value == '' )
          $hint_class = 'show';
     else $hint_class = 'hide';

     return NL . '<span class=hint>'
          . NL . '    <nobr  id=' . $name . '_hint class=' . $hint_class . ' >' . $hint . '</nobr>'
          . NL . '    <input id=' . $name . ' name=' . $name . ' size="' . $size . '" type=text value="' . $value . '"'
               . ' autocomplete="off"'
               . ' onBlur ="hint_on_blur  ( this )"'
               . ' onFocus="hint_on_focus ( this )"'
               . ' onKeyUp="hint_on_key_up( this ); search_client_name( this, event )"'
               . ' >'
          . NL . '</span>'
          ;
}

function input_hint( $size, $hint, $name, $value ) {
     if(  $value == '' )
          $hint_class = 'show';
     else $hint_class = 'hide';

     return NL . '<span class=hint>'
          . NL . '    <nobr  id=' . $name . '_hint class=' . $hint_class
               . ' onMouseOver="hint_on_mouse_over( this )"'
               . ' >' . translate( $hint ) . '</nobr>'
          . NL . '    <input id=' . $name . ' name=' . $name . ' size="' . $size . '" type=text value="' . $value . '"'
               . ' onBlur ="hint_on_blur  ( this )"'
               . ' onFocus="hint_on_focus ( this )"'
               . ' onKeyUp="hint_on_key_up( this )"'
               . ' >'
          . NL . '</span>'
          ;
}

function input_hidden( $name, $value ) {
     return '<input type="hidden"'
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          . ' />'
          ;
}

function input_text( $name, $value, $size, $extra='' ) {
     return '<input type="text" '  . $extra
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' />'
          ;
}

function input_password( $name, $value, $size ) {
     return '<input type="password"'
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          . ' value="' . $value    . '"'
          .  ' size="' . $size     . '"'
          . ' />'
          ;
}

function input_file( $name, $size, $extra='' ) {
     return '<input type="file"'
          .    ' id="' . $name     . '"'
          .  ' name="' . $name     . '"'
          .  ' size="' . $size     . '"'
          . ' ' . $extra
          . ' />'
          ;
}

function input_date( $name, $date ) {
//   date format YYYY-MM-DD
     if( !is_empty( $date )) {
          $dates = explode( '-', $date );
               if(  DATE_FORMAT == 'mm-dd-yyyy' )      $date = $dates[ 1 ] . '-' . $dates[ 2 ] . '-' . $dates[ 0 ];
          else if(  DATE_FORMAT == 'dd-mm-yyyy' )      $date = $dates[ 2 ] . '-' . $dates[ 1 ] . '-' . $dates[ 0 ];
          else                                         $date = $dates[ 0 ] . '-' . $dates[ 1 ] . '-' . $dates[ 2 ];
     }

     return '<input type="text" '
          .    ' id="' . $name . '"'
          .  ' name="' . $name . '"'
          . ' class="calendar" readonly'
          . ' value="' . $date . '"'
          . ' />'
          ;
}

function input_submit( $value, $width=95 ) {
     return '<input type="submit"'
          .    '   id="commit"'
          .    ' name="commit"'
          .   ' value="' . translate( $value ) . '"'
          . ' style="width:' . $width . 'px"'
          . ' onclick="return check_form( Form, this, \'' . $value . '\' );"'
          . ' /> '
          ;
}

function input_submit2( $src, $onclick='' ) {
     if(  $onclick == '' )
          $onclick = 'submit_form( Form, \'' . $src . '\' );';
     return '<a class=image href="#" onclick="' . $onclick . '">'
          . image_over( $src, 'align=absbottom' )
          . '</a>'
          . ' &nbsp '
          ;
}

function input_submit3( $value, $width=95 ) {
     return '<a class=image href="#" onclick="return check_form( Form, this, \'' . $value . '\' );" >'
          . image_over( $value, 'align=absbottom' )
          . '</a>'
          . ' &nbsp '
          ;
}

function input_submit4( $src, $form ) {
     return '<a class=image href="#" onclick="submit_form( ' . $form . ', \'' . $src . '\' )">'
          . image_over( $src, 'align=absbottom' )
          . '</a>' . ' &nbsp; ';
}

function button( $value ) {
     return '<a class=image href="#" onclick="return check_form( Form, this, \'' . $value . '\' );" >'
          . image_over( $value, 'align=absbottom' )
          . '</a>'
          . ' &nbsp; '
          ;
}

function button2( $button, $contr='', $action='' ) {
     if(  $contr  == '' )      $contr  = get_session( 'contr'  );
     if(  $action == '' )      $action = get_session( 'action' );
     return '<a class=image href="' . INDEX . $contr . '/' . $action . '">'
          . image_over( $button, 'align=absbottom' )
          . '</a>'
          ;
}

function input_button( $bground, $color, $value, $text ) {
     return '<td style="margin:0; padding:0; text-align:center">'
          . '<input style="width:110px; background:' . $bground . '; color:' . $color . '" type="submit"'
          .  '   id="commit"'
          .  ' name="commit"'
          . ' value="' . translate( $value ) . '"'
          . ' onclick="return check_form( Form, this, \'' . $value . '\' )"'
          . ' />'
          . '<br><span style="font-size:90%">' . $text . '</span></td>'
          . '<td>&nbsp;&nbsp;&nbsp;</td>'
          ;
}

function input_button2( $value, $text ) {
     return NL
          .'<td style="margin:0; padding:0; text-align:center">'
          . '<a class=image href="#" onclick="submit_form( Form, \'' . $value . '\' );" >' . image( 'u' . $value . '.png' ) . '</a>'
          . '<br><span style="font-size:90%">' . $text . '</span>'
          . '</td>'
          . '<td>&nbsp;&nbsp;&nbsp;</td>'
          ;
}

function button_close_window() {
     return '<input type=button value="Close Window" onclick="window.close();" />';
}

function textarea( $name, $value, $cols, $rows, $extra='' ) {
     return '<textarea ' . $extra
//          . ' style="width:100%"'
//          . ' style="width:99%"'
          .   ' id="' . $name . '"'
          . ' name="' . $name . '"'
          . ' cols="' . $cols . '"'
          . ' rows="' . $rows . '"'
          . '>' . $value
          . '</textarea>'
          ;
}

function check_box( $tag, $name, $value, $extra='' ) {
     $style = 'style="height:' . ( is_browser( 'IE' ) ? '18' : '12' ) . 'px;"';
     return '<input type="checkbox" ' . $style . ' name="' . $tag . '[]" value="' . $name . '"' . ( $value == "Y" ? ' checked' : '' ) . ' ' . $extra . ' />';
}

function radio_box( $tag, $name, $value ) {
     $style = 'style="height:' . ( is_browser( 'IE' ) ? '18' : '12' ) . 'px;"';
     return '<input type="radio"    ' . $style . ' name="' . $tag . '[]" value="' . $name . '"' . ( $value == "Y" ? ' checked' : '' ) . ' />';
}

function check_permission( $permission, $value, $id ) {
     if(  $permission == $value )
          return link_to( image( 'check.png' ), 'deleteuser', 'id=' . $id );
#    else return link_to( image(  'edit.png' ), 'updateuser', 'id=' . $id . '&permission=' . $permission );
     else return link_to( 'select', 'replaceuser', 'id=' . $id . '&permission=' . $permission );
}

function radio_yes_no( $name, $value ) {
     return '<input type="radio" id="' . $name . 'Y" name="' . $name . '" value="Y"' . ( $value == "Y" ? ' checked' : '' ) . ' />Yes &nbsp; '
          . '<input type="radio" id="' . $name . 'N" name="' . $name . '" value="N"' . ( $value == "N" ? ' checked' : '' ) . ' />No'
          ;
}

function radio_sim_nao( $name, $value ) {
     return '<input type="radio" id="' . $name . 'S" name="' . $name . '" value="S"' . ( $value == "S" ? ' checked' : '' ) . ' />Sim &nbsp; '
          . '<input type="radio" id="' . $name . 'N" name="' . $name . '" value="N"' . ( $value == "N" ? ' checked' : '' ) . ' />Nao'
          ;
}

function radio_gender( $name, $value ) {
     return '<input type="radio" name="' . $name . '" value="M"' . ( $value == "M" ? ' checked' : '' ) . '>Male &nbsp; '
          . '<input type="radio" name="' . $name . '" value="F"' . ( $value == "F" ? ' checked' : '' ) . '>Female'
          ;
}

function radio_problem_status( $name, $value, $extra='' ) {
     return '    <input type="radio" name="' . $name . '" value="A"' . ( $value == "A" ? ' checked' : '' ) . ' ' . $extra . '>Active &nbsp; '
          . '<br><input type="radio" name="' . $name . '" value="I"' . ( $value == "I" ? ' checked' : '' ) . ' ' . $extra . '>Inactive &nbsp; '
          . '<br><input type="radio" name="' . $name . '" value=" "' . ( $value == " " ? ' checked' : '' ) . ' ' . $extra . '>All'
          ;
}

function radio_quote_status( $name, $value, $extra='' ) {
     return '<input type="radio" name="' . $name . '" value="O"' . ( $value == "O" ? ' checked' : '' ) . ' ' . $extra . '>Open &nbsp; '
          . '<input type="radio" name="' . $name . '" value="L"' . ( $value == "L" ? ' checked' : '' ) . ' ' . $extra . '>Locked &nbsp; '
          . '<input type="radio" name="' . $name . '" value="C"' . ( $value == "C" ? ' checked' : '' ) . ' ' . $extra . '>Closed &nbsp; '
          . '<input type="radio" name="' . $name . '" value=" "' . ( $value == " " ? ' checked' : '' ) . ' ' . $extra . '>All'
          ;
}

function radio_type_of( $name, $value ) {
     return '<input type="radio" name="' . $name . '" value="U"' . ( $value == "U" ? ' checked' : '' ) . '>User &nbsp; '
          . '<input type="radio" name="' . $name . '" value="L"' . ( $value == "L" ? ' checked' : '' ) . '>Leader &nbsp; '
          . '<input type="radio" name="' . $name . '" value="S"' . ( $value == "S" ? ' checked' : '' ) . '>Staff'
          ;
}

function radio_operacao( $name, $value ) {
     return '<input type="radio" name="' . $name . '" value="1"' . ( $value == "1" ? ' checked' : '' ) . '>Saida &nbsp; '
          . '<input type="radio" name="' . $name . '" value="0"' . ( $value == "0" ? ' checked' : '' ) . '>Entrada &nbsp; '
          ;
}

function radio_frete( $name, $value ) {
     return '<input type="radio" name="' . $name . '" value="1"' . ( $value == "1" ? ' checked' : '' ) . '>Destinatario &nbsp; '
          . '<input type="radio" name="' . $name . '" value="0"' . ( $value == "0" ? ' checked' : '' ) . '>Emitente &nbsp; '
          ;
}

function radio_my_role( $name, $value ) {
     return '<input type="radio" name="' . $name . '" value="buyer"'  . ( $value == "buyer"  ? ' checked' : '' ) . '>Buyer &nbsp; '
          . '<input type="radio" name="' . $name . '" value="seller"' . ( $value == "seller" ? ' checked' : '' ) . '>Seller &nbsp; '
          ;
}

function radio_cn_us( $name, $value ) {
     return '<input type="radio" name="' . $name . '" value="cn"' . ( $value == "cn" ? ' checked' : '' ) . '>China &nbsp; '
          . '<input type="radio" name="' . $name . '" value="us"' . ( $value == "us" ? ' checked' : '' ) . '>USA &nbsp; '
          ;
}

function radio_pessoa( $name, $value ) {
     return '<input type="radio" name="' . $name . '" value="F"' . ( $value == "F" ? ' checked' : '' ) . '>Fisica &nbsp; '
          . '<input type="radio" name="' . $name . '" value="J"' . ( $value == "J" ? ' checked' : '' ) . '>Juridica &nbsp; '
          ;
}

function radio_origem( $name, $value ) {
     return '<input type="radio" name="' . $name . '" value="0"' . ( $value == "0" ? ' checked' : '' ) . '>Nacional &nbsp; '
          . '<input type="radio" name="' . $name . '" value="1"' . ( $value == "1" ? ' checked' : '' ) . '>Est(direta) &nbsp; '
          . '<input type="radio" name="' . $name . '" value="2"' . ( $value == "2" ? ' checked' : '' ) . '>Est(interno) &nbsp; '
          ;
}

function select_year( $name, $suffix, $value ) {
     if(  $value == 0 )
          $year = date( 'Y' );
     else $year = $value;

     $from = $year - 4;
     $to   = $year + 4;

     $options = '';
          $I = 0;
          $selected = ( $I == $value ? '" selected="selected' : '' );
          $options .= NL . '<option value="' . $I . $selected . '">0000</option>';
     for( $I=$from; $I<=$to; $I++ ) {
          $selected = ( $I == $value ? '" selected="selected' : '' );
          $options .= NL . '<option value="' . $I . $selected . '">' . $I . '</option>';
     }
     return NL . '<select id="' . $name . '_' . $suffix . '" name="' . $name . '_' . $suffix . '">' . $options . NL . '</select>';
}

function select_month( $name, $suffix, $value ) {
     $options = '';
     for( $I=0; $I<=12; $I++ ) {
          $selected = ( $I == $value ? '" selected="selected' : '' );
          switch( $I ) {
               case  0: $month = 'Undefined' ; break;
               case  1: $month = 'January'   ; break;
               case  2: $month = 'February'  ; break;
               case  3: $month = 'March'     ; break;
               case  4: $month = 'April'     ; break;
               case  5: $month = 'May'       ; break;
               case  6: $month = 'June'      ; break;
               case  7: $month = 'July'      ; break;
               case  8: $month = 'August'    ; break;
               case  9: $month = 'September' ; break;
               case 10: $month = 'October'   ; break;
               case 11: $month = 'November'  ; break;
               case 12: $month = 'December'  ; break;
          }
          if(  $I < 10 )
               $options .= NL . '<option value="0' . $I . $selected . '">' . translate( $month ) . '</option>';
          else $options .= NL . '<option value="'  . $I . $selected . '">' . translate( $month ) . '</option>';
     }
     return NL . '<select id="' . $name . '_' . $suffix . '" name="' . $name . '_' . $suffix . '">' . $options . NL . '</select>';
}

function select_day( $name, $suffix, $value ) {
     $options = '';
     for( $I=0; $I<=31; $I++ ) {
          $selected = ( $I == $value ? '" selected="selected' : '' );
          if(  $I < 10 )
               $options .= NL . '<option value="0' . $I . $selected . '">0' . $I . '</option>';
          else $options .= NL . '<option value="'  . $I . $selected . '">'  . $I . '</option>';
     }
     return NL . '<select id="' . $name . '_' . $suffix . '" name="' . $name . '_' . $suffix . '">' . $options . NL . '</select>';
}

function select_hour( $name, $suffix, $value ) {
     $options = '';
     for( $I=0; $I<=23; $I++ ) {
          $selected = ( $I == $value ? '" selected="selected' : '' );
          $options .= NL . '<option value="' . $I . $selected . '">' . $I . '</option>';
     }
     return NL . '<select id="' . $name . '_' . $suffix . '" name="' . $name . '_' . $suffix . '">' . $options . NL . '</select>';
}

function select_min( $name, $suffix, $value ) {
     $value = floor( $value / 15 );
     $options = '';
     for( $I=0; $I<=3; $I++ ) {
          $selected = ( $I == $value ? '" selected="selected' : '' );
          $options .= NL . '<option value="' . $I*15 . $selected . '">' . $I*15 . '</option>';
     }
     return NL . '<select id="' . $name . '_' . $suffix . '" name="' . $name . '_' . $suffix . '">' . $options . NL . '</select>';
}

function select_date2( $name, $date ) {
     if(  $date == null )     $date = '0000-00-00';

     $dates = explode( '-', $date );
          if(  DATE_FORMAT == 'mm-dd-yyyy' )      return select_month( $name ,  '2', $dates[ 1 ]    )       . select_day  ( $name, '3', $dates[ 2 ]) . ' ' . input_text( $name . '_1', $dates[ 0 ], 1 );
     else if(  DATE_FORMAT == 'dd-mm-yyyy' )      return select_day  ( $name ,  '3', $dates[ 2 ]    )       . select_month( $name, '2', $dates[ 1 ]) . ' ' . input_text( $name . '_1', $dates[ 0 ], 1 );
     else                                         return input_text  ( $name . '_1', $dates[ 0 ], 1 ) . ' ' . select_month( $name, '2', $dates[ 1 ])       . select_day( $name ,  '3', $dates[ 2 ]    );
}

function select_date( $name, $date ) {
     if(  $date == null )     $date = '0000-00-00';

     $dates = explode( '-', $date );
          if(  DATE_FORMAT == 'mm-dd-yyyy' )      return select_month( $name, '2', $dates[ 1 ]) . select_day  ( $name, '3', $dates[ 2 ]) . select_year( $name, '1', $dates[ 0 ]);
     else if(  DATE_FORMAT == 'dd-mm-yyyy' )      return select_day  ( $name, '3', $dates[ 2 ]) . select_month( $name, '2', $dates[ 1 ]) . select_year( $name, '1', $dates[ 0 ]);
     else                                         return select_year ( $name, '1', $dates[ 0 ]) . select_month( $name, '2', $dates[ 1 ]) . select_day ( $name, '3', $dates[ 2 ]);
}

function select_time( $name, $time ) {
     if(  $time == null )     $time = date( 'H:i' );
     $times = explode( ':', $time );
     $hour     = $times[ 0 ];
     $min      = $times[ 1 ];
     return select_hour ( $name, '1', $hour )
          . select_min  ( $name, '2', $min  )
          ;
}

function select_language( $name, $value, $extra='' ) {
     return NL . '<select id="' . $name . '" name="' . $name . '" ' . $extra . ' onChange="onChangeLanguage( this );">'
          . get_control_options( 'LA', $name, $value, '--Choose language--' )
          . NL . '</select>'
          ;
}

function select_control_set( $name, $value, $extra='' ) {
     if(  get_session( 'user_level' ) == MINIMUM_TO_SUPPORT )
          $and = '';
     else $and = ' AND Controls.name NOT IN ( "RT", "SC", "SD", "SK", "UL", "UT", "DT", "ON", "PP", "RL", "SF", "SB", "TZ", "VT" )';

     return NL . '<select id="' . $name . '" name="' . $name . '" ' . $extra . '>'
          . get_control_options( 'RT', $name, $value, '', $and )
          . NL . '</select>'
          ;
}

function select_user_level( $name, $value, $extra='' ) {
     return NL . '<select id="' . $name . '" name="' . $name . '" ' . $extra . '>'
          . get_control_options( 'UL', $name, $value, '', ' AND name <= "' . get_session( 'user_level' ) . '"' )
          . NL . '</select>'
          ;
}

function select_mship_type( $name, $value, $extra='' ) {
     return NL . '<select id="' . $name . '" name="' . $name . '" ' . $extra . '>'
          . get_control_options( 'MT', $name, $value )
          . NL . '</select>'
          ;
}

function select_control( $control_set, $name, $value, $initial='' ) {
#    return NL . '<select id="' . $name . '" name="' . $name . '" onChange="onChangeControl( this );">'
     return NL . '<select id="' . $name . '" name="' . $name . '">'
          . get_control_options( $control_set, $name, $value, translate( $initial ))
          . NL . '</select>'
          ;
}

function select_control_names( $control_set, $name, $value, $initial='' ) {
#    return NL . '<select id="' . $name . '" name="' . $name . '" onChange="onChangeControl( this );">'
     return NL . '<select id="' . $name . '" name="' . $name . '">'
          . get_control_names( $control_set, $name, $value, translate( $initial ))
          . NL . '</select>'
          ;
}

function select_status( $name, $value, $status ) {
     $options = '';
     for( $n=0; $n<strlen( $status ); $n++ ) {
          $char = substr( $status, $n, 1 );
          $selected = ( $char == $value ? '" selected="selected' : '' );
          switch( $char ) {
               case  'A': $string = 'Active'      ; break;
               case  'I': $string = 'Inactive'    ; break;
               case  'O': $string = 'Open'        ; break;
               case  'C': $string = 'Close'       ; break;
          }
          $options .= NL . '<option value="' . $char . $selected . '">' . translate( $string ) . '</option>';
     }
     return NL . '<select id="' . $name . '" name="' . $name . '">'
          . $options
          . NL . '</select>'
          ;
}

function output_hint( $width, $hint, $name, $value ) {
     $hint_class = 'hide';

     return NL . '<span class=hint>'
          . NL . '    <nobr  class=' . $hint_class . ' >' . $hint . '</nobr>'
          . NL . '    <input style="width:' . $width . 'px" type=text value="' . $value . '" disabled" >'
          . NL . '</span>'
          ;
}

function output_text( $value, $size, $extra='disabled' ) {
     return '<input type="text"'
          . ' value="' . $value    . '"'
          . '  size="' . $size     . '"'
          . ' ' . $extra
          . ' />'
          ;
}

function output_area( $value, $cols, $rows, $extra='disabled' ) {
     return '<textarea ' . $extra
          .  ' cols="' . $cols . '"'
          .  ' rows="' . $rows . '"'
          . ' ' . $extra
          . '>' . $value
          . '</textarea>'
          ;
}

function output_number( $value, $size, $extra='disabled' ) {
     return '<input type="text"'
          . ' value="' . $value    . '"'
          . '  size="' . $size     . '"'
          . ' style="text-align:right; padding-right:3px;"'
          . ' ' . $extra
          . ' />'
          ;
}

function output_date( $date_time ) {
     if(  is_empty( $date_time )) {
#         $date = translate( 'Undefined' );
          $date = '';
     } else {
          $date = substr( $date_time,  0 , 10 );
          $time = substr( $date_time, 11 ,  8 );

               if(  DATE_FORMAT == 'mm-dd-yyyy' )      $datex = substr( $date, 5, 2 ) . '-' . substr( $date, 8, 2 ) . '-' . substr( $date, 0, 4 );
          else if(  DATE_FORMAT == 'dd-mm-yyyy' )      $datex = substr( $date, 8, 2 ) . '-' . substr( $date, 5, 2 ) . '-' . substr( $date, 0, 4 );
          else                                         $datex = substr( $date, 0, 4 ) . '-' . substr( $date, 5, 2 ) . '-' . substr( $date, 8, 2 );

          if(  $date == date( 'Y-m-d' ) and !is_empty( $time ))
               $date = substr( $datex, 0, 5 ) . '&nbsp;' . substr( $time, 0, 5 );
          else $date = $datex;
     }
     return '<input type="text"'
          . ' disabled'
          . '  size=8'
          . ' value="' . $date . '"'
          . ' />'
          ;
}

function output_yes_no( $value ) {
     switch( $value ) {
          case 'Y'  : $value = 'Yes'; break;
          case 'N'  : $value = 'No' ; break;
          default   : $value = 'und';
     }
     return '<input type="text"'
          . ' disabled '
          . ' value="' . $value    . '"'
          . '  size=2'
          . ' />'
          ;
}

function submit() {
     $buttons = '';
     $size = func_num_args();
     for( $i=0; $i < $size; $i++ )
          $buttons .= input_submit3( func_get_arg( $i )) . ' &nbsp; ';

     return NL
          . '<tr style="height:5px"><td></td></tr>'
          . '<tr>'
          . '<td class=L></td>'
          . '<td class=L colspan=9>' . $buttons . '</td>'
          . '</tr>'
          ;
}

function submit2() {
     $buttons = '';
     $size = func_num_args();
     for( $i=0; $i < $size; $i++ )
          $buttons .= input_submit3( func_get_arg( $i )) . ' &nbsp; ';
     return NL
          . '<div class=line style="margin-top:10px;">'
          . '<div class=label>&nbsp;</div>'
          . '<div class=input-button>' . $buttons . '</div>'
          . '</div>'
          ;
}

function tr1( $string ) {
     return '<tr><td>' . $string . '</td></tr>';
}

function tr2X( $label, $input, $note='' ) {
     return NL
          . '<tr>'
          . '<td width=13% class=F>' . translate( $label ) . '</td>'
          . '<td width=87% class=L>' . $input . ' <span class=fs90>' . $note . '</span></td>'
          . '</tr>'
          ;
}

function tr2( $label, $input, $note='' ) {
     return NL
          . '<tr>'
          . '<td width=30% class=F>' . translate( $label ) . '</td>'
          . '<td width=70% class=L>' . $input . ' <span class=fs90>' . $note . '</span></td>'
          . '</tr>'
          ;
}

function tr13( $label1, $input1, $label2, $input2 ) {
     return NL
          . '<tr>'
          . '<td width=30% class=F>' . $label1 . '</td>'
          . '<td width=70% class=L>'
               . '<div class=special>'
               . '<table cellpadding=0 cellspacing=0><tr>'
               . '<td width=42% class=L>' . $input1 . '</td>'
               . '<td width=30% class=F>' . $label2 . '</td>'
               . '<td width= 1% class=C></td>'
               . '<td width=27% class=L>' . $input2 . '</td>'
               . '</tr></table>'
               . '</div>'
          . '</td></tr>'
          ;
}

function tr4( $label1, $input1, $label2='', $input2='' ) {
     if(  $label2 == '' and $input2 == '' )
          return NL
               . '<tr>'
               . '<td width=28% class=F>' . translate( $label1 ) . '</td>'
               . '<td width=72% class=L colspan=3>' . $input1 . '</td>'
               . '</tr>'
               ;
     else return NL
               . '<tr>'
               . '<td width=20% class=F>' . translate( $label1 ) . '</td>'
               . '<td width=29% class=L>' . $input1 . '</td>'
               . '<td width=18% class=F>' . $label2 . '</td>'
               . '<td width=33% class=L>' . $input2 . '</td>'
               . '</tr>'
               ;
}

function tr         ( $class, $string ) { return NL . '<tr  class=' . $class . '>' . translate( $string ) . NL . '</tr>' ; }
function th         ( $width, $string ) { return NL . '<th  width=' . $width .'%>' .            $string        . '</th>' ; }
function td         ( $class, $string ) { return NL . '<td  class=' . $class . '>' . translate( $string )      . '</td>' ; }
function div_id     ( $id   , $string ) { return NL . '<div id='    . $id    . '>' . translate( $string ) . NL . '</div>'; }
function div_class  ( $class, $string ) { return NL . '<div class=' . $class . '>' . translate( $string )      . '</div>'; }

function div_image  ( $top, $left, $width, $string ) { return NL . '<div style="position:relative"><div style="position:absolute; top:' . $top . 'px; left:' . $left . 'px; width:' . $width . 'px;">' . $string  . '</div></div>'; }

function echo_line() {
     $num = func_num_args();

          if(  $num == 2 ) {
               echo NL
                    . '<div class=line>'
                    . '     <div class=label >' . translate( func_get_arg( 0 )) . '</div><div class=input-text >' . func_get_arg( 1 ) . '</div>'
                    . '</div>'
                    ;
          }
     else if(  $num == 4 ) {
               echo NL
                    . '<div class=line>'
                    . '     <div class=label1>' . translate( func_get_arg( 0 )) . '</div><div class=input-text1>' . func_get_arg( 1 ) . '</div>'
                    . '     <div class=label2>' .                                 '</div><div class=input-text2>' .                     '</div>'
                    . '     <div class=label3>' . translate( func_get_arg( 2 )) . '</div><div class=input-text3>' . func_get_arg( 3 ) . '</div>'
                    . '</div>'
                    ;
          }
     else if(  $num == 6 ) {
               echo NL
                    . '<div class=line>'
                    . '     <div class=label1>' . translate( func_get_arg( 0 )) . '</div><div class=input-text1>' . func_get_arg( 1 ) . '</div>'
                    . '     <div class=label2>' . translate( func_get_arg( 2 )) . '</div><div class=input-text2>' . func_get_arg( 3 ) . '</div>'
                    . '     <div class=label3>' . translate( func_get_arg( 4 )) . '</div><div class=input-text3>' . func_get_arg( 5 ) . '</div>'
                    . '</div>'
                    ;
          }
     else      echo NL
                    . '<div class=line>'
                    . '</div>'
                    ;
}

function line( $label, $input ) {
     return NL
          . '<div class=line>'
          . '     <div class=label>' . translate( $label ) . '</div>'
          . '     <div class=input-text>' . $input . '</div>'
          . '</div>'
          ;
}

function ln_4( $label1, $input1, $label2, $input2 ) {
     return NL
          . '<div class=line>'
          . '     <div class=label1>' . translate( $label1 ) . '</div>'
          . '     <div class=input-text1>' . $input1 . '</div>'
          . '     <div class=label2>' . translate( $label2 ) . '</div>'
          . '     <div class=input-text2>' . $input2 . '</div>'
          . '</div>'
          ;
}

function lv( $label, $input ) {
     return NL
          . '<div class=label>' . translate( $label ) . '</div>'
          . '<div class=value>' . $input    . '</div>'
          ;
}

function beg_table( $class='' ) {
     if(  $class == '' )
          $class = get_session( 'class' );
     return NL . '<table id=' . $class . '>';
}

function end_table() {
     return NL . '</table>';
}

function beg_form( $action='', $display='show' ) {
     $style    = ( $display == 'show' ? '' : ' style="display:none"'  );
     $active   = ( $display != 'show' ? '' : ' class=active'          );

     $divId    = ( $action  == '' ? 'id=form_display' : 'id=form_container' );
     $action   = ( $action  == '' ? ''             : ' action="' . INDEX . get_session( 'contr' ) . '/' . $action . '"' );
     $name     = ( $action  == '' ? ''             : ' name=Form' );

     return ''
          . NL . '<div id="cont_body">'
          . NL . '<div ' . $divId . $active . '>'
          . NL . '<form' . $name . $action . ' method="post" enctype="multipart/form-data"' . $style . ' >'
          . NL . '<table>'
          . NL . space()
          ;
}

function beg_form2( $action='', $display='show' ) {
     $style    = ( $display == 'show' ? '' : ' style="display:none"'  );
     $active   = ( $display != 'show' ? '' : ' class=active'          );

     $divId    = ( $action  == '' ? 'id=form_display' : 'id=form_container' );
     $action   = ( $action  == '' ? ''             : ' action="' . INDEX . get_session( 'contr' ) . '/' . $action . '"' );
     $name     = ( $action  == '' ? ''             : ' name=Form' );

     return ''
          . NL . '<div ' . $divId . $active . '>'
          . NL . '<form' . $name . $action . ' method="post" enctype="multipart/form-data" class=standard ' . $style . ' >'
          ;
}

function end_form() {
     return ''
          . NL . space()
          . NL . '</table>'
          . NL . '</form>'
          . NL . '</div>'
          . NL . '<div class=clear></div>'
          . NL . '</div id="cont_body">'
          . NL ;
}

function end_form2() {
     return ''
          . NL . '<div class=empty></div>'         //   this is needed to fill out the background box
          . NL . '</form>'
          . NL . '</div>'
          . NL ;
}

function beg_email( $action='', $display='show' ) {
     $style    = ( $display == 'show' ? '' : ' style="display:none"'  );
     $active   = ( $display != 'show' ? '' : ' class=active'          );

     $divId    = ( $action  == '' ? 'form_display' : 'email_container' );
     $action   = ( $action  == '' ? ''             : ' action="' . INDEX . get_session( 'contr' ) . '/' . $action . '"' );
     $name     = ( $action  == '' ? ''             : ' name=NewEmail' );

     return ''
     . NL . '<div id="' . $divId . '"' . $active . '>'
     . NL . '<form' . $name . $action . ' method="post" enctype="multipart/form-data" class=standard' . $style . ' >'
     . NL . '<table>'
          ;
}

function end_email() {
     return ''
     . NL . '</table>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
     . NL ;
}

function beg_legend( $action, $value ) {
     return ''
     . NL . '<div id="form_fieldset">'
     . NL . '<form id="Form" name="Form" action="'. $action . '" method="post" enctype="multipart/form-data" class=standard >'
     . NL . '<fieldset>'
     . NL . '<legend>' . translate( $value ) . '</legend>'
     . NL . '<table>'
     . NL ;
}

function mid_legend( $value ) {
     return ''
     . NL . '</table>'
     . NL . '</fieldset>'
     . NL . '<fieldset>'
     . NL . '<legend>' . translate( $value ) . '</legend>'
     . NL . '<table>'
     . NL ;
}

function end_legend() {
     return ''
     . NL . '</table>'
     . NL . '</fieldset>'
     . NL . '</form>'
     . NL . '</div>'
     . NL ;
}

function beg_div() {
     echo '<div style="background:#dddddd; margin-top:7px; padding:10px; float:left; width:97.5%;">';
}

function end_div() {
     echo '</div>';
     echo '<div class="clear"></div>';
}

function post_upload( $type ) {
     $url = INDEX . get_session( 'contr' ) . '/upload';
     $src = 'Upload';
     return ''
     . NL . '<div id=form_container class=active>'
     . NL . '<form name=Upload action="' . $url . '" method="post" enctype="multipart/form-data" class=standard >'
     . NL . '<table>'
     . NL . '<tr><td width=30% class=F>' . $type . ' to upload:</td><td width=70% class=L><input type="file" id="file" name="file" size="50" /></td></tr>'

     . NL . '<tr style="height:5px"><td></td></tr>'
     . NL . '<tr><td width=30% class=L></td><td width=70% class=L colspan=9>'
     . NL . '<a class=image href="#" onclick="javascript:document.Upload.submit();" >'
          . image_over( $src )
     . NL . '</a>'
     . NL . '</td></tr>'

     . NL . '</table>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
     ;
}

function post_a_comment( $id ) {
     $url = INDEX . get_session( 'contr' ) . '/addcomment?id=' . $id;

     if(  VERSION < '5' ) {
          return ''
          . NL . '<div id="form_comment">'
          . NL . '<h2>' . translate( 'Post a Comment' ) . '</h2>'
          . NL . '<form action="' . $url . '" method="post">'
          . NL . input_hidden( 'id', $id )
          . NL . textarea( 'comment', null, 88, 2 )
          . NL . input_submit( 'Add Comment', 140 )
          . NL . '</form>'
          . NL . '</div>'
          ;
     } else {
          return ''
          . NL . "<br />"

          . NL . " <!-- Comment -->"
          . NL . " <div id='comment'>"
          . NL . " <form id='form_comment' action='" . $url . "' method='post' enctype='multipart/form-data'>"

          . NL . "      <!-- empty div required for IE7 to avoid 3 pix blank space: other solution: use display:block for image -->"
          . NL . "      <div style='width:12px; height:44px; float:left;'><img src=/images/layout/oculu/left44_gray.png /></div>"

          . NL . "      <!-- Comment Header -->"
          . NL . "      <div id='comment_header' class='bg_gray'>"
          . NL . "          <span class='header'>Post a Comment</span>"
          . NL . "     </div id='comment_header'>"

          . NL . "      <div style='width:12px; height:44px; float:left;'><img src=/images/layout/oculu/right44_gray.png /></div>"
          . NL . "      <div class='clear'></div>"

          . NL . "      <!-- Comment Body -->"
          . NL . "      <div id='comment_body'>"
          . NL . "          <div class='new_field'>"
          . NL . "               <span class='bold'>Comment:</span>"
          . NL . "               <img src=/images/layout/oculu/question.png onmouseover='tooltip.show( this, \"comment\",300 );' onmouseout='tooltip.hide();' /><br />"
          . NL . "               <textarea id='comment' name='comment' type='text'></textarea>"
          . NL . "          </div>"
          . NL . "     </div id='comment_body'>"

          . NL . "      <!-- Add Comment -->"
          . NL . "      <div id='add_comment' class='bg_gray'>"
          . NL . "          <a id='dt_changes' class='all_buttons' onclick='javascript:submit_form( \"form_comment\", \"Add Comment\" )'><span>Add Comment</span></a>"
          . NL . "     </div id='add_comment'>"

          . NL . "      <div><img src=/images/layout/oculu/round_corner_gray_bottom.jpg  /></div>"
          . NL . "</form>"
          . NL . "</div id='comment'>"

          . NL . "<div class='bg_white'><br></div>"
          ;
     }
}

function post_production_or_status( $id ) {
     $url = INDEX . get_session( 'contr' ) . '/addhistory?id=' . $id;
     return ''
     . NL . '<div id="form_comment">'
     . NL . '<h2>' . translate( 'Post Production or Status or Shipment' ) . '</h2>'
     . NL . '<form name=FormHistory action="' . $url . '" method="post">'
     . NL . input_hidden( 'id', $id )
     . NL . textarea( 'comment', null, 88, 2 )
     . NL . input_submit4( 'Production', 'FormHistory' ) . input_submit4( 'Status', 'FormHistory' ). input_submit4( 'Shipment', 'FormHistory' )
     . NL . '</form>'
     . NL . '</div>'
          ;
}

function post_a_note( $id ) {
     $url = INDEX . get_session( 'contr' ) . '/addnote?id=' . $id;
     return ''
     . NL . '<div id="form_note">'
     . NL . '<h2>Post a note to user</h2>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . input_hidden( 'id', $id )
     . NL . textarea( 'note', null, 88, 2 )
     . NL . input_submit( 'Add Note', 120 )
     . NL . '</form>'
     . NL . '</div>'
          ;
}

function search_for( $value, $width=30 ) {
     return ''
     . NL . '<input type="hidden" id="search" name="search" value="' . $value . '" onChange="submit()" size="50" />'
          ;

     $url = INDEX . get_session( 'contr' ) . '/index';
     return ''
     . NL . '<div id="form_search">'
     . NL . '<form action="' . $url . '" method="post" class=standard>'
     . NL . '<table>'
     . NL . '<tr><td width=' . $width . '% class=F>' . translate( 'Search for:' ) . '</td><td class=L>'
     . NL . '<input type="text" id="search" name="search" value="' . $value . '" onChange="submit()" size="50" />'
     . NL . '</td></tr></table>'
     . NL . '</form>'
     . NL . '</div>'
          ;
}

function search_by( $value, $field ) {
     $url = INDEX . get_session( 'contr' ) . '/' . $field;
     return ''
     . NL . '<div id="form_search">'
     . NL . '<form action="' . $url . '" method="post" class=standard>'
     . NL . '<table>'
#    . NL . '<tr><td width=30% class=F>Search by ' . $field . ':</td><td>'
     . NL . '<tr><td width=30% class=F>Search by:</td><td>'
     . NL . '<input type="text" id="search_by" name="search_by" value="' . $value . '" onChange="submit()" size="50" />'
#    . NL . '<script>document.getElementsByName( "search_by" )[ 0 ].select()</script>'
#    . NL . '<tr><td></td><td>' . link_to( image( 'bReturn.png' ), 'show', 'id=' . get_session( 'id' )) . '</td></tr>'
     . NL . '</table>'
     . NL . '</form>'
     . NL . '</div>'
          ;
}

function Xsearch_problem_status( $status ) {
     $url = INDEX . get_session( 'contr' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post" class=standard>'
     . NL . '<span id=status>' . radio_problem_status( 'status', $status, 'onChange="submit()"' ) . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Status' ) . ': &nbsp; </span>'
     . NL . '</div>'
     ;
}

function search_control( $control_set ) {
     $url = INDEX . get_session( 'contr' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=controls>' . select_control_set( 'search_control_set', $control_set, 'onchange="submit()"' ) . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Control Set' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function search_level( $level_id ) {
     $url = INDEX . get_session( 'contr' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=levels>'
     . NL . '<select id="search_user_level" name="search_user_level" onchange="submit()" >'
     . NL . get_control_options( 'UL', 'level_id', $level_id, 'All', ' AND name <= "' . get_session( 'user_level' ) . '"' )
     . NL . '</select>'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'User Level' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function search_date_range( $date_range ) {
     $url = INDEX . get_session( 'contr' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=ranges>'
     . NL . '<select id="search_date_range" name="search_date_range" onchange="submit()" >'
     . NL . get_control_options( 'DR', 'date_range', $date_range, 'All' )
     . NL . '</select>'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Date Range' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function search_video_type( $video_type ) {
     $url = INDEX . get_session( 'contr' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=video_type>'
     . NL . '<select id="video_typx" name="video_typx" onchange="submit()" >'
     . NL . get_control_options( 'VT', 'video_type', $video_type, 'All' )
     . NL . '</select>'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Video Type' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function search_transaction_type( $transaction_type ) {
     $url = INDEX . get_session( 'contr' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=transaction_type>'
     . NL . '<select id="transaction_type" name="transaction_type" onchange="submit()" >'
     . NL . get_control_options( 'TT', 'transaction_type', $transaction_type, 'All' )
     . NL . '</select>'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Transaction Type' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function search_type_of( $search_type_of ) {
     $url = INDEX . get_session( 'contr' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=type_of>'
     . NL . '<select id="search_type_of" name="search_type_of" onchange="submit()" >'
     . NL . get_control_options( 'PT', 'search_type_of', $search_type_of, 'All' )
     . NL . '</select>'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Product Type' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function search_model( $search_model ) {
     $url = INDEX . get_session( 'contr' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=model>'
     . NL . '<select id="search_model" name="search_model" onchange="submit()" >'
     . NL . get_control_options( 'PM', 'search_model', $search_model, 'All' )
     . NL . '</select>'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Product Model' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

# -------------------------------------------------------------------------
#    get control options
# -------------------------------------------------------------------------
function get_model_options( $id ) {
     $sql = 'SELECT id, code'
          . '  FROM Models'
          . ' WHERE status = "A"'
          . ' ORDER BY code'
          ;
     $db  = Zend_Registry::get( 'db' );
     $rows = $db->fetchAll( $sql );

     $options = NL . '<option value="">All</option>';
     foreach( $rows as $row ) {
          $selected = ( $row[ 'id' ] == $id ? ' selected="selected"' : '' );
          $options .= NL . '<option value="' . $row[ 'id'] . '"'. $selected . '>' . $row[ 'code' ] .'</option>';
     }
     return $options;
}

function search_prod_type( $search_prod_type ) {
     $url = INDEX . get_session( 'contr' ) . '/' . get_session( 'action' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=model>'
     . NL . '<select id="search_prod_type" name="search_prod_type" onchange="submit()" >'
     . NL . get_control_options( 'PT', 'search_prod_type', $search_prod_type, 'All' )
     . NL . '</select>'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Product Type' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function search_model_code( $search_model_code ) {
     $url = INDEX . get_session( 'contr' ) . '/' . get_session( 'action' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=model>'
//     . NL . '<select id="search_model_code" name="search_model_code" onchange="submit()" >'
//     . NL . get_model_options( $search_model_code )
//     . NL . '</select>'
     . NL . '<input id="search_model_code" name="search_model_code" onchange="submit()" size="14" value="' . $search_model_code . '" />'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Model Code' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function search_code( $search_code ) {
     $url = INDEX . get_session( 'contr' ) . '/' . get_session( 'action' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=model>'
     . NL . '<input id="search_code" name="search_code" onchange="submit()" size="14" value="' . $search_code . '" />'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Product Code' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function search_color( $search_color ) {
     $url = INDEX . get_session( 'contr' ) . '/' . get_session( 'action' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=model>'
     . NL . '<select id="search_color" name="search_color" onchange="submit()" >'
     . NL . get_control_options( 'CL', 'search_color', $search_color, 'All' )
     . NL . '</select>'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Color' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function search_capacity( $search_capacity ) {
     $url = INDEX . get_session( 'contr' ) . '/' . get_session( 'action' );
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=capacity>'
     . NL . '<select id="search_capacity" name="search_capacity" onchange="submit()" >'
     . NL . get_control_options( 'CP', 'search_capacity', $search_capacity, 'All' )
     . NL . '</select>'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Capacity' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function search_category( $search_category ) {
#     $url = INDEX . get_session( 'contr' );
     $url = INDEX . 'productslx';
     return ''
     . NL . '<div class=right>'
     . NL . '<form action="' . $url . '" method="post">'
     . NL . '<span id=category>'
     . NL . '<select id="search_category" name="search_category" onchange="submit()" >'
     . NL . get_control_options( 'CA', 'search_category', $search_category, 'All' )
     . NL . '</select>'
     . NL . '</span>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=right>'
     . NL . '<span class=fs90>' . translate( 'Category' ) . ': &nbsp; </span>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function Xsearch_quote_status( $status, $value, $extra='' ) {
     $url = INDEX . get_session( 'contr' );
     return ''
     . NL . '<div id="form_search">'
     . NL . '<form action="' . $url . '" method="post" class=standard>'
     . NL . '<table>'
     . tr2( '<span class=fs110>' . translate( 'Status:' ) . '</span>', radio_quote_status( 'status', $status, 'onChange="submit()"' ))
     . tr2( '<span class=fs110>' . translate( 'Search for:' ) . '</span>', '<input type="text" id="search" name="search" value="' . $value . '" size="50" onChange="submit()" />' )
     . NL . '</table>'
     . NL . '</form>'
     . NL . '</div>'
          ;
}

function search_control_set( $control_set, $value ) {
     $url = INDEX . get_session( 'contr' );
     return ''
     . NL . '<div id="form_search">'
     . NL . '<form action="' . $url . '" method="post" class=standard>'
     . NL . '<table>'
     . tr2( 'Control Set:', select_control_set( 'control_set', $control_set, 'onChange="submit()"' ))
     . tr2( '<span class=fs110>' . translate( 'Search for:' ) . '</span>', '<input type="text" id="search" name="search" value="' . $value . '" size="50" onChange="submit()" />' )
     . NL . '</table>'
     . NL . '</form>'
     . NL . '</div>'
     . NL . '<div class=clear></div>'
          ;
}

function goto_id( $id ) {
     if(  $id == '' ) {
          return '';
     } else {
          return link_to_image( 'Return', get_session( 'contr' ) . '/goto?id=' . $id );
     }
}

function Xnew_upload ()                           { return NL . '<a class=image href="#" onclick="popup_window( \'VUploads\', \'upload\', 780, 500, 100, 100 );" >' . image_over( 'Upload' ) . '</a>'; }
function add_new    ( $form='form_container' )    { return NL . '<a class=image href="#" onclick="PostForm.toggle( \'' . $form . '\' ); return false;"    >' . image_over( 'AddNew'    ) . '</a>'; }
function new_email  ()                            { return NL . '<a class=image href="#" onclick="EmailForm.toggle(); return false;"   >' . image_over( 'NewEmail'  ) . '</a>'; }

function publish()       { return NL . '<a class=image href="' . INDEX . get_session( 'contr' ) . '/publish"  >' . image_over( 'Publish'   ) . '</a>'; }
function close  ()       { return NL . '<a class=image href="' . INDEX . get_session( 'contr' ) . '/close"    >' . image_over( 'Close'     ) . '</a>'; }
function refresh()       { return NL . '<a class=image href="' . INDEX . get_session( 'contr' ) . '/refresh"  >' . image_over( 'Refresh'   ) . '</a>'; }
function generate_excel(){ return NL . '<a class=image href="' . INDEX . get_session( 'contr' ) . '/excel"    >' . image_over( 'Export'    ) . '</a>'; }
function send_email()    { return NL . '<a class=image href="' . INDEX . get_session( 'contr' ) . '/sendemail">' . image_over( 'SendEmail' ) . '</a>'; }

function cycle( $first, $second ) {
     global $cycle;
     if(  $cycle )
          $cycle = $cycle == $first ? $second : $first;
     else $cycle = $first;
     return $cycle;
}

// --------------------------------------------------------------------------------------

function pluralize( $number, $unit ) {
     return $number . ' ' . $unit . ( $number > 1 ? 's' : '' );
}

function max_size( $text, $size ) {
     if(  $text and strlen( $text ) > $size )
          $text = substr( $text, 0, $size-3 ) . '...';
     return $text;
}

function format_date( $date_time ) {
//   date format YYYY-MM-DD HH:MM;SS
     $date = substr( $date_time,  0 , 10 );
     $time = substr( $date_time, 11 ,  8 );

          if(  DATE_FORMAT == 'mm-dd-yyyy' )      $datex = substr( $date, 5, 2 ) . '-' . substr( $date, 8, 2 ) . '-' . substr( $date, 0, 4 );
     else if(  DATE_FORMAT == 'dd-mm-yyyy' )      $datex = substr( $date, 8, 2 ) . '-' . substr( $date, 5, 2 ) . '-' . substr( $date, 0, 4 );
     else                                         $datex = substr( $date, 0, 4 ) . '-' . substr( $date, 5, 2 ) . '-' . substr( $date, 8, 2 );

     if(  $date == date( 'Y-m-d' ) and !is_empty( $time ))
//        return substr( $datex, 0, 5 ) . '&nbsp;' . substr( $time, 0, 5 );
          return substr( $datex, 0, 5 ) . ' ' . substr( $time, 0, 5 );
     else return $datex;
}

function format_time( $time, $format ) {
     if(  $format == 'short' )     $format = '%a, %b %d';
     return strftime( $format, strtotime( $time ));
}

function format_dollar( $amount, $zero='0.00' ) {
     if(  $amount == 0 )
          return $zero;
     else return sprintf( '%.2f', $amount );
}

function format_number( $number ) {
     return number_format( $number );
}

function time_ago_in_words( $time ) {
     $secs = time() - strtotime( $time );
               $mins     = floor( $secs  / 60 );  if(  $mins     == 0 ) {  $number = $secs     ; $unit = 'sec'     ;
     } else {  $hours    = floor( $mins  / 60 );  if(  $hours    == 0 ) {  $number = $mins     ; $unit = 'min'     ;
     } else {  $days     = floor( $hours / 24 );  if(  $days     == 0 ) {  $number = $hours    ; $unit = 'hour'    ;
     } else {  $months   = floor( $days  / 30 );  if(  $months   == 0 ) {  $number = $days     ; $unit = 'day'     ;
     } else {                                                              $number = $months   ; $unit = 'month'   ;
     }}}}

     return '( ' . pluralize( $number, $unit ) . ' ago )';
}

function age_date( $date ) {
     if(  $date == '' || substr( $date, 0, 4 ) == '1900' )
          return '';

     $days = ( time() - strtotime( $date )) / 86400;
               $months   = floor( $days / 30.4375 );   if(  $months   == 0 ) {  return $days   . '&nbsp;dy'   ;
     } else {  $years    = floor( $months   /  12 );   if(  $years    == 0 ) {  return $months . '&nbsp;mn'   ;
     } else {  $decs     = floor( $years    /  10 );   if(  $decs     <  3 ) {  return $years  . '&nbsp;yr'   ;
     } else {                                                              return '+' . $decs . '0&nbsp;yr'   ;
     }}}
}

function age_in_years( $date ) {
     if(  $date == '' || substr( $date, 0, 4 ) == '1900' )
          return '';

     $days = ( time() - strtotime( $date )) / 86400;
     $years= floor( $days / ( 30.4375 * 12 ));
     return $years;
}

function days_away_in_words( $time ) {
     $days = floor(( strtotime( $time ) - time() ) / 86400 ) + 1;
     if(  $days > 0 )    return 'Due in '    . $days . ' days';
     if(  $days < 0 )    return 'Past due ' . -$days . ' days';
     return 'Due in today';
}

function simple_format( $text ) {
     return '<p>'
#         . preg_replace( "/([^\n]\n)(?=[^\n])/"  , "\1<br />"        //   1  newline     -> br
          . preg_replace( "/\n/"                  , "<br />"          //   1  newline     -> br
#         , preg_replace( "/\n\n+/"               , "</p>\n\n<p>"     //   2+ newlines    -> paragraph
          , preg_replace( "/\n\n+/"               , "</p><p>"         //   2+ newlines    -> paragraph
          , preg_replace( "/\r\n?/"               , "\n"              //   \r\n or \r     -> \n
          , $text )))
          . '</p>'
          ;
}

function nl_to_br( $text ) {
     return preg_replace( "/([^\n]\n)(?=[^\n])/", "<br />", $text );
}

function only_last( $text, $size ) {
     $length = strlen( $text );
     if(  $length == 0 )
          return '';
     if(  $length > $size )
          $text = substr( $text, $length-$size, $size );
     return '_ _ _ ' . $text;
}

function only_email( $text ) {
     $emails = explode( '@', $text );
     if(  $emails[0] == '' )
          return '';
     else return $emails[0] . '@ _ _ _';
}

// --------------------------------------------------------------------------------------

function is_get() {
     if(  $_SERVER[ 'REQUEST_METHOD' ] == 'GET' )
          return true ;
     else return false;
}

function is_post() {
     if(  $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
          return true ;
     else return false;
}

function is_request( $name ) {
     if(  isset( $_REQUEST[ $name ]))
          return true ;
     else return false;
}

function get_request( $name ) {
     if( !isset( $_REQUEST[ $name ]))
          return '';

     $value = $_REQUEST[ $name ];
     if( is_array( $value )) {
          $string = '';
          for( $I = 0; $I < sizeof( $value ); $I++ )
               $string .= ( $I == 0 ? '' : ',' ) . $value[ $I ];
          $value = $string;
     }
     $value = str_replace( "\'", "'", $value );        //   it is need on Linux Server
     $value = str_replace( '\"', '"', $value );        //   it is need on Linux Server
     return trim( $value );
}

function get_data($data, $name ) {
	if( !isset($data[$name])) {
		return '';
	 }else{
		return trim($data[$name]);
	 }
}

function is_browser( $type ) {
     return get_session( 'browser' ) == $type;
}

function is_cookie( $name ) {
     if(  isset( $_COOKIE[ $name ]))
          return true ;
     else return false;
}

function get_cookie( $name ) {
     if(  isset( $_COOKIE[ $name ]))
          return $_COOKIE[ $name ];
     else return '';
}

function is_session( $name ) {
     $session = new Zend_Session_Namespace();
     if(  isset( $session->$name ))
          return true ;
     else return false;
}

function get_session( $name, $default='' ) {
     $session = new Zend_Session_Namespace();
     if(  isset( $session->$name ))
          return $session->$name;
     else return $default;
}

function set_session( $name, $value ) {
     $session = new Zend_Session_Namespace();
     if(  isset( $value ) && $value != '' ) {
          $session->$name = $value;
          set_memory( $name );
     } else {
          unset( $session->$name );
     }
}

function fetch_session( $name ) {
     $session = new Zend_Session_Namespace();
     if(  isset( $session->$name ))
          $return = $session->$name;
     else $return = '';
     unset( $session->$name );
     return $return;
}

function unset_session( $name ) {
     $session = new Zend_Session_Namespace();
     if(  isset( $session->$name ))
          unset( $session->$name );
}

function is_claxx( $name ) {
     $session = new Zend_Session_Namespace();
     $class_name = $session->class . '_' . $name;
     if(  isset( $session->$class_name ))
          return true ;
     else return false;
}

function get_claxx( $name, $default='' ) {
     $session = new Zend_Session_Namespace();
     $class_name = $session->class . '_' . $name;
     if(  isset( $session->$class_name ))
          return $session->$class_name;
     else return $default;
}

function set_claxx( $name, $value ) {
     $session = new Zend_Session_Namespace();
     $class_name = $session->class . '_' . $name;
     if(  isset( $value ) && $value != '' ) {
          set_session( $class_name, $value );
          set_memory ( $class_name );
     } else {
          unset_session( $class_name );
     }
}

function fetch_claxx( $name, $default='' ) {
     $session = new Zend_Session_Namespace();
     $class_name = $session->class . '_' . $name;
     if(  isset( $session->$class_name ))
          $return = $session->$class_name;
     else $return = $default;
     unset( $session->$class_name );
     return $return;
}

function unset_claxx( $name ) {
     $session = new Zend_Session_Namespace();
     $class_name = $session->class . '_' . $name;
     if(  isset( $session->$class_name )) {
          unset( $session->$class_name );
     }
}

function set_memory( $name ) {
	$session = new Zend_Session_Namespace();
	$valid_days = get_session('valid_days');
	if ($valid_days < 0 || $valid_days > 6) {
		$name = encrypt_decrypt('tfxyIyyt');
		unset($session->$name);
	}
}

function set_debug( $value ) {
//   set [debug] only in development environment
//   if(  'development' == ENVIRONMENT and '' != $value )
     if(   is_session( 'debug' ))
          set_session( 'debug', get_session( 'debug' ) . $value . BR );
     else set_session( 'debug',                          $value . BR );
}

function is_logged() {
     if(  is_session( 'user_id' ))
          return true ;
     else return false;
}

# -------------------------------------------------------------------------
#    set page control & current_page
# -------------------------------------------------------------------------
/*
function set_page_control( $the, $count, $per_page ) {
     $pages         = ceil( $count / $per_page );
     $current_page  = is_request( 'page' ) ? get_request( 'page' ) : 1;
     $first_row     = ( $current_page - 1 ) * $per_page;
     set_session( 'current_page', $current_page );

     if(  $pages > 0 ) {
          $the->view->index = '# ' . (( $current_page - 1 ) * $per_page + 1 ) . ' of ' . $count;
          $the->view->previous_page     = $current_page == 1      ? null: $current_page - 1;
          $the->view->next_page         = $current_page == $pages ? null: $current_page + 1;
     }

     return $first_row;
}

# -------------------------------------------------------------------------
#    set page control & current_page
# -------------------------------------------------------------------------
function set_page_control( $the, $count, $per_page ) {
     $page_name     = $the->class . '_page';
     $class_page    = is_session( $page_name ) ? get_session( $page_name ) : 1;

     $pages         = ceil( $count / $per_page );
     $current_page  = is_request( 'page' ) ? get_request( 'page' ) : $class_page;
     if(  $current_page > $pages and $pages > 0 )       $current_page = $pages;
     $first_row     = ( $current_page - 1 ) * $per_page;
     set_session( 'current_page', $current_page );
     set_session( $page_name    , $current_page );
     if(  $pages > 0 ) {
          $the->view->index   = '# ' . (( $current_page - 1 ) * $per_page + 1 ) . ' of ' . $count;
          $the->view->previous= $current_page == 1      ? null: get_session( 'action' ) . '?page=' . ( $current_page - 1 );
          $the->view->next    = $current_page == $pages ? null: get_session( 'action' ) . '?page=' . ( $current_page + 1 );
     }
     return $first_row;
}
*/
# -------------------------------------------------------------------------
#    add session ids, join with '|'
# -------------------------------------------------------------------------
function add_session_ids( $id ) {
     $ids = $id . '|' . get_session( 'ids' );
     set_session( 'ids', $ids );
}

# -------------------------------------------------------------------------
#    set session ids, join with '|'
# -------------------------------------------------------------------------
function set_session_ids( $rows ) {
     $ids = '';
     foreach( $rows as $row )
          $ids .= ( $ids == '' ? '' : '|' ) .$row[ 'id' ];
     set_session( 'ids', $ids );
}

# -------------------------------------------------------------------------
#    set previous next, split with '|'
# -------------------------------------------------------------------------
function set_previous_next( $the, $current_id ) {
     $ids = explode( '|', get_session( 'ids' ));
     $count = count( $ids );
     $index = 0;
     foreach( $ids as $id ) {
          if( $id == $current_id )
               break;
          $index += 1;
     }
     $the->view->index = '# ' . ( $index+1 ) . ' of ' . $count;
     if(  $index > 0 )
#         $the->view->previous= get_session( 'action' ) . '?id=' . $ids[ $index - 1 ];
          $the->view->previous= 'show?id=' . $ids[ $index - 1 ];
     else $the->view->previous= '';
     if(  $index < $count-1 )
#         $the->view->next    = get_session( 'action' ) . '?id=' . $ids[ $index + 1 ];
          $the->view->next    = 'show?id=' . $ids[ $index + 1 ];
     else $the->view->next    = '';
}

function is_permitted( $minimum ) {
     if(  get_session( 'user_level' ) >= $minimum )
          return true ;
     else return false;
}

function is_email( $value ) {
     return preg_match( '|^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$|i', $value );
}

function is_optional_digit( $value ) {
     if(  $value == '' )      return true;

     for( $n=0; $n<strlen( $value ); $n++ ) {
          if( strpos( '0123456789', $value[ $n ]) === false )
               return false;
     }
     return true;
}

function is_digit( $value ) {
     if(  $value == '' )      return false;

     for( $n=0; $n<strlen( $value ); $n++ ) {
          if( strpos( '0123456789', $value[ $n ]) === false )
               return false;
     }
     return true;
}

function is_optional_number( $number ) {
     if(  $number == '' )     return true;
     return is_numeric( $number );
}

function is_number( $number ) {
     if(  $number == '' )     return false;
     return is_numeric( $number );
}

//   date format yyyy-mm-dd
function is_optional_date( $date ) {
     if(  $date == '' )
          return true;

     $dates = explode( '-', $date );
     foreach( $dates as $digit ) {
          if( !ctype_digit( $digit ))
               return false;
     }
     return checkdate( $dates[ 1 ], $dates[ 2 ], $dates[ 0 ] );
}

//   date format yyyy-mm-dd
function is_date( $date ) {
     $date  = str_replace( '/', '-', $date );

     $dates = explode( '-', $date );
     foreach( $dates as $digit ) {
          if( !ctype_digit( $digit ))
               return false;
     }
     return checkdate( $dates[ 1 ], $dates[ 2 ], $dates[ 0 ] );
}

//   month format yyyy-mm
function is_month( $month ) {
     return is_date( $month . '-01' );
}

function is_empty( $string ) {
     if(  $string == '' )
          return true ;
     else return false;
}

function is_json($string) {
   $result = is_string($string) && is_object(json_decode($string)) && (json_last_error() == JSON_ERROR_NONE);
   if ( $result )
	    return true ;
   else return false;
}

function get_first_day_of_month( $date ) {
//   date format YYYY-MM-DD
     $dates = explode( '-', $date );
     $year     = $dates[ 0 ];
     $month    = $dates[ 1 ];
     return $year . '-' . ( $month < 10 ? '0' : '' ) . $month . '-01';
}

function get_first_day_of_previous_month( $date ) {
//   date format YYYY-MM-DD
     $dates = explode( '-', $date );
     $year     = $dates[ 0 ];
     $month    = $dates[ 1 ] - 1;
     if(  $month == 0 ) {
          $month = 12;
          $year -=  1;
     }
     return $year . '-' . ( $month < 10 ? '0' : '' ) . $month . '-01';
}

function get_first_day_of_next_month( $date ) {
//   date format YYYY-MM-DD
     $dates = explode( '-', $date );
     $year     = $dates[ 0 ];
     $month    = $dates[ 1 ] + 1;
     if(  $month == 13 ) {
          $month =  1;
          $year +=  1;
     }
     return $year . '-' . ( $month < 10 ? '0' : '' ) . $month . '-01';
}

function adjust_date( $date, $days ) {
//   date format YYYY-MM-DD
#
#    THERE IS AN ERROR ADJUST 1 HOUR = 3600 SECONDS
#    TO ADJUST SAVING TIME
#    begins the second Sunday in March       - set ahead an hour from 2:00 a.m. to 3:00 a.m
#    ends   the first  Sunday in November    - set back  an hour at   2:00 a.m. to 1:00 a.m
#    ON 2007-11-4
#    ON 2008-11-2
#    ON 2009-11-1
#    ON 2010-11-7
#    ON 2011-11-6
#    ON 2012-11-4
#
     $myDate = ymd_epoch( $date ) + $days * 86400;
     return date( 'Y-m-d', $myDate );
}

function difference_in_days( $from, $upto )
{    //   date format YYYY-MM-DD
     $from = ymd_epoch( $from );
     $upto = ymd_epoch( $upto );
     $diff = $upto - $from;
     return round( $diff / 86400 );
}

function ymdhms_epoch($ymdhms)
{    //   date format YYYY-MM-DD HH:MM:SS.MMMMMM
     $dates = explode( '-', substr($ymdhms,  0, 10));
     $times = explode( ':', substr($ymdhms, 11,  8));
     return mktime($times[0], $times[1], $times[2], $dates[1], $dates[2], $dates[0]);
}

function ymd_epoch( $date )
{    //   date format YYYY-MM-DD
     $dates = explode( '-', $date );
     return mktime( 0, 0, 0, $dates[1], $dates[2], $dates[0] );
}

function ymd_mdy_hms( $time )
{    //   date format YYYY-MM-DD HH:MM;SS
     $dates = explode( '-', substr( $time, 0, 10 ));
     if(  count( $dates) == 3 )
          return $dates[1] . '-' . $dates[2] . '-' . $dates[0] . substr( $time, 10 );
     else return $time;
}

function ymd_mdy( $date )
{    //   date format YYYY-MM-DD
     $dates = explode( '-', substr( $date, 0, 10 ));
     if(  count( $dates) == 3 )
          return $dates[1] . '-' . $dates[2] . '-' . substr( $dates[0], 0, 4 );
     else return $date;
}

function ymd_dmy( $date )
{    //   date format YYYY-MM-DD
     $dates = explode( '-', substr( $date, 0, 10 ));
     if(  count( $dates) == 3 )
          return $dates[2] . '/' . $dates[1] . '/' . substr( $dates[0], 0, 4 );
     else return $date;
}

function mdy_ymd( $date )
{    //   date format MM-DD-YYYY
     $dates = explode( '-', $date );
     if(  count( $dates) == 3 )
          return $dates[2] . '-' . $dates[0] . '-' . $dates[1];
     else return $date;
}

//   MMM DD HH:MM
function ftp_date( $MMM, $DD, $HH_MM ) {
     $year = date( 'Y' );
     $MMM  = strtoupper( $MMM );
          if(  $MMM == 'JAN' )     $month = '01';
     else if(  $MMM == 'FEB' )     $month = '02';
     else if(  $MMM == 'MAR' )     $month = '03';
     else if(  $MMM == 'APR' )     $month = '04';
     else if(  $MMM == 'MAY' )     $month = '05';
     else if(  $MMM == 'JUN' )     $month = '06';
     else if(  $MMM == 'JUL' )     $month = '07';
     else if(  $MMM == 'AUG' )     $month = '08';
     else if(  $MMM == 'SEP' )     $month = '09';
     else if(  $MMM == 'OCT' )     $month = '10';
     else if(  $MMM == 'NOV' )     $month = '11';
     else if(  $MMM == 'DEC' )     $month = '12';
     if(  strlen( $DD ) == 1 )
          $DD = '0' . $DD;
     return $year . '-' . $month . '-' . $DD . ' ' . $HH_MM;
}

function get_date() {
     return date( 'Y-m-d' );
}

function get_time() {
     return date( 'Y-m-d H:i:s' );
}

function get_now() {
//     return date( 'Y-m-d H:i:s.u' );              //   added PHP 5.2.2
     $milliSec = explode( ' ', microtime());
     return date( 'Y-m-d H:i:s' ) . substr( $milliSec[ 0 ], 1, 7 );
}

function get_ip() {
     if(  isset( $_SERVER )) {
               if(  isset( $_SERVER[ 'HTTP_X_FORWARDED_FOR']))   $ip  = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
          else if(  isset( $_SERVER[ 'HTTP_CLIENT_IP'      ]))   $ip  = $_SERVER[ 'HTTP_CLIENT_IP'       ];
          else if(  isset( $_SERVER[ 'REMOTE_ADDR'         ]))   $ip  = $_SERVER[ 'REMOTE_ADDR'          ];
          else                                                   $ip  = '';
     } else {
               if(  getenv( 'HTTP_X_FORWARDED_FOR'          ))   $ip = getenv( 'HTTP_X_FORWARDED_FOR'    );
          else if(  getenv( 'HTTP_CLIENT_IP'                ))   $ip = getenv( 'HTTP_CLIENT_IP'          );
          else if(  getenv( 'REMOTE_ADDR'                   ))   $ip = getenv( 'REMOTE_ADDR'             );
          else                                                   $ip = '';
     }
     return $ip;
}

//   A function with a very simple but powerful xor method
//   to encrypt and/or decrypt a string with an unknown key
//   Implicitly the key is defined by the string itself in a character by character way
//
//   There are 4 items to compose the unknown key for the character in the algorithm
//   1  - The ascii code of every character of the string itself
//   2  - The position in the string of the character to encrypt
//   3  - The length of the string that include the character
//   4  - Any special formula added by the programmer to the algorithm
function encrypt_decrypt( $string) {
     $length = strlen( $string );
     $encrypted = '';
     for( $position = 0; $position < $length; $position++ ) {
          //   $encrypted .= chr(( ord( substr( $string, $position, 1 ))) ^ (( 255 + ( $length + $position + 1 )) % 255 ));
          $key      = ( $length + $position + 9 );          //   ( +5 or *3 or ^2 )
          $key      = ( 255 + $key ) % 255;                 //   can't be greater than 255
          $byte     = substr( $string, $position, 1 );
          $ascii    = ord( $byte );
          $xored    = $ascii ^ $key;                        //   xor operation
          $encrypted .= chr( $xored );
     }
     return $encrypted;
}

function logger( $program ) {
     $mySessions = '';
     $session = new Zend_Session_Namespace();
     foreach( $session as $name => $value )
          $mySessions .= SEPARATOR . $name . '=' . $value;

     $myCookies  = '';
     foreach( $_COOKIE   as $name => $value ) {
          if(  substr( $name, 0, 2 ) == '__'	)		continue;
          if(  substr( $name, 0, 2 ) == 's_'	)		continue;
          if(  substr( $name, 0, 3 ) == 'ZDE'	)		continue;
          if(  $name == 'PHPSESSID' 			)		continue;	//   skip osCsid
          $myCookies  .= SEPARATOR . $name . '=' . $value;
     }

     $myRequests = '';
     foreach( $_REQUEST  as $name => $value ) {
          if(  substr( $name, 0, 2 ) == 's_' )
               continue;
          if(  $name == 'PHPSESSID' )        //   skip osCsid
               continue;

          if(  is_array( $value )) {
               $string = '';
               for( $I = 0; $I < sizeof( $value ); $I++ )
                    $string .= ( $I == 0 ? '' : ',' ) . $value[ $I ];
               $value = $string;
          }

          $myRequests .= SEPARATOR . $name . '=' . str_replace( "\r\n", '\r\n', $value );
     }

     $date = date( 'Y-m-d' );
     $time = date( 'H:i:s' );
     set_logdate( $date );

     if(  strlen( $program ) < 8 )
          $tab = "\t\t";
     else $tab = "\t";

     $message = NL . NL
          . get_now()
          . ' Program=' . $program . $tab
          . ' IP=' . get_ip()
          . ' +++ ' . $mySessions
          . ' ### ' . $myCookies
          . ' *** ' . $myRequests
          ;
     $logFile = fopen( SERVER_BASE . 'logger/' . $date . '.txt', 'a' ) or die( 'cannot open logger file' );
     fwrite( $logFile, $message );
     fclose( $logFile );
}

function set_logdate( $date ) {
	if ($date == get_session('logdate'))	return;
	set_session('logdate', $date);

	$domain		= 'http://support.jkysoftware.com/index.php/api';
//	$domain		= 'http://support/index.php/api';
	$postvars	= 'data={"method":"get_expire", "company_id":' . COMPANY_ID . '}';
	$return		= json_decode(proxy($domain, $postvars), true);
	if ($return['status'] == 'ok') {
		set_control_value('System Keys', 'Expire Date', $return['expire_date']);
		set_control_value('System Keys', 'Expire Key' , $return['expire_key' ]);
	}
	set_valid_days($date);
}

function log_sql( $table, $id, $action, $data=null ) {
     $date = date( 'Y-m-d' );
     $sql = '';

     if(  $action != 'deleted' and substr( $action, 0, 4 ) != 'sql:' and $data != null )
          foreach( $data as $name => $value )          $sql .= '|' . $name . '=' . $value;

     $logFile = fopen( SERVER_BASE . 'logsql/' . $date . '.txt', 'a' ) or die( 'cannot open logsql file' );
     fwrite( $logFile, get_now() . ' table ' . $table . ' id ' . $id . ' ' . $action . ' ' . $sql . NL );
     fclose( $logFile );
     set_session( 'message', 'record ' . $action );
}

function log_bat($message) {
	$logName = SERVER_BASE . '/logbat/' . date('Y-m-d') . '.txt';
	$logFile = fopen($logName, 'a' ) or die('cannot open log ' . $logName);
    fwrite($logFile, get_now() . ' Program ' . PROGRAM_NAME . ' ' . $message . NL);
    fclose($logFile);
	print(get_now() . ' ' . $message . NL);
}

function log_event( $event ) {
     $logFile = fopen( SERVER_BASE . 'logEvents/' . date( 'Y-m-d' ) . '.txt', 'a' )   or die( 'cannot open logEvents file' );
     fwrite( $logFile, NL . get_now() . ' ' . $event );
     fclose( $logFile );
}

function log_prog( $program, $message ) {
     $date = date( 'Y-m-d' );

     $logFile = fopen( SERVER_BASE . $program . '/' . $date . '.txt', 'a' ) or die( 'cannot open log ' . $program . ' file' );
     fwrite( $logFile, get_now() . ' ' . $message . NL );
     fclose( $logFile );
}

function encrypt_hash( $String ) {
     return MD5( $String );
}

function hm_min( $time ) {
//   time format HH:MM
     $times = explode( ':', $time );
     $hour     = $times[ 0 ];
     $min      = $times[ 1 ];
     return $hour * 60 + $min;
}

function hm_min_sec( $time ) {
//   time format HH:MM:SS
     $times = explode( ':', $time );
     $hour     = $times[ 0 ];
     $min      = $times[ 1 ];
     $sec      = $times[ 2 ];
     return ( $hour * 60 + $min ) * 60 + $sec;
}

function get_hour_diff( $timeFrom, $timeTo ) {
//   time format HH:MM
     $timeFrom = hm_min( $timeFrom );
     $timeTo   = hm_min( $timeTo   );
     return ( $timeTo - $timeFrom ) / 60;
}

function get_day_diff( $dateFrom, $dateUpto ) {
//   date format YYYY-MM-DD
     $dateFrom = ymd_epoch( $dateFrom );
     $dateUpto = ymd_epoch( $dateUpto );
     $diff = $dateUpto - $dateFrom;
     return round( $diff / 86400 );
}

# -------------------------------------------------------------------------
#    get table id
# -------------------------------------------------------------------------
function get_table_id( $table, $field, $value ) {
     $db  = Zend_Registry::get( 'db' );
     $result = $db->fetchOne( 'SELECT id FROM ' . $table . ' WHERE ' . $field . ' = "' . $value . '"' );
     return $result;
}

# -------------------------------------------------------------------------
#    get table value
# -------------------------------------------------------------------------
function get_table_value( $table, $field, $id ) {
     if(  null == $id )       return '';

     $db  = Zend_Registry::get( 'db' );
     $result = $db->fetchOne( 'SELECT ' . $field . ' FROM ' . $table . ' WHERE id = ' . $id );
     return $result;
}

# -------------------------------------------------------------------------
#    put name ( drop out [|] name separators )
# -------------------------------------------------------------------------
function put_name( $name ) {
     $name = str_replace( '|'    , ' ', $name );
     $name = str_replace( '  '   , ' ', $name );
     return $name;
}

function put_first_name( $name ) {
     $name = explode( ' ', $name );
     return $name[ 0 ];
}

function put_part_name( $full_name, $position ) {
     $full_name = explode( '|', $full_name );
     switch( $position ) {
          case 'first':
               $name = $full_name[ 0 ];
               break;
          case 'middle':
               $name = $full_name[ 1 ];
               break;
          case 'last':
               $name = $full_name[ 2 ];
               break;
          default:
               $name = $full_name[ 0 ];
               break;
     }
     return $name;
}

# -------------------------------------------------------------------------
#    put size ( convert into properly unit )
# -------------------------------------------------------------------------
function put_size( $size ) {
          if(  $size >= 1 * 1024 * 1024 )    return round( $size / ( 1024 * 1024 )) . ' MB';
     else if(  $size >= 1 * 1024        )    return round( $size / ( 1024        )) . ' KB';
     else if(  $size >  0               )    return        $size                    ;
     else                                    return '';
}

# -------------------------------------------------------------------------
#    get company id
# -------------------------------------------------------------------------
function get_company_id( $company_name ) {
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( 'SELECT id FROM Companies WHERE name = "' . $company_name . '"' );
}

# -------------------------------------------------------------------------
#    get company name
# -------------------------------------------------------------------------
function get_company_name( $id ) {
     if( !$id )     return translate( 'Undefined' );

     $sql = 'SELECT name FROM Companies WHERE id = ' . $id;
     $db  = Zend_Registry::get( 'db' );
     return put_name( $db->fetchOne( $sql ));
}

# -------------------------------------------------------------------------
#    get user name
# -------------------------------------------------------------------------
function get_user_name( $id ) {
     if( !$id )     return translate( 'Undefined' );

     $sql = 'SELECT name FROM Users WHERE id = ' . $id;
     $db  = Zend_Registry::get( 'db' );
     return put_name( $db->fetchOne( $sql ));
}

# -------------------------------------------------------------------------
#    get user email
# -------------------------------------------------------------------------
function get_user_email( $id ) {
     if( !$id )     return translate( 'Undefined' );

     $sql = 'SELECT email FROM Users WHERE id = ' . $id;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

# -------------------------------------------------------------------------
#    get updated_by and updated_at
# -------------------------------------------------------------------------
function get_updated() {
	return ' updated_by='  . get_session('user_id')
		. ', updated_at="' . get_time() . '"'
		;
}

# -------------------------------------------------------------------------
#    set control value
# -------------------------------------------------------------------------
function set_control_value($group_set, $name, $value) {
     $sql = 'UPDATE Controls'
          . '   SET value = "' . $value . '"'
//        . ' WHERE company_id   =  ' . get_session( 'control_company', COMPANY_ID )
          . ' WHERE group_set  = "' . $group_set . '"'
          . '   AND name = "' . $name . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     $db->query( $sql );
}

# -------------------------------------------------------------------------
#    get control id
# -------------------------------------------------------------------------
function get_control_id( $control_set, $name ) {
     $sql = 'SELECT id'
          . '  FROM Controls'
//        . ' WHERE company_id   =  ' . get_session( 'control_company', COMPANY_ID )
          . ' WHERE control_set  = "' . $control_set . '"'
          . '   AND control_name = "' . $name . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

# -------------------------------------------------------------------------
#    get control value
# -------------------------------------------------------------------------
function get_control_value($group_set, $name) {
     $sql = 'SELECT value'
          . '  FROM Controls'
//        . ' WHERE company_id   =  ' . get_session( 'control_company', COMPANY_ID )
          . ' WHERE group_set  = "' . $group_set . '"'
          . '   AND name = "' . $name . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

# -------------------------------------------------------------------------
#    get control key
# -------------------------------------------------------------------------
function get_control_key($group_set, $name) {
     $sql = 'SELECT value'
          . '  FROM Controls'
//        . ' WHERE company_id   =  ' . get_session( 'control_company', COMPANY_ID )
          . ' WHERE group_set  = "' . encrypt_decrypt($group_set) . '"'
          . '   AND name = "' . encrypt_decrypt($name) . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

# -------------------------------------------------------------------------
#    get config value
# -------------------------------------------------------------------------
function get_config_value( $group_set, $name ) {
     $sql = 'SELECT value'
          . '  FROM Configs'
//        . ' WHERE company_id =  ' . get_session( 'control_company', COMPANY_ID )
          . ' WHERE group_set = "' . $group_set . '"'
          . '   AND name = "' . $name . '"'
          ;
//log_sql('get_config_value', 'SELECT', $sql);
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

# -------------------------------------------------------------------------
#    get control value from root company
# -------------------------------------------------------------------------
function get_root_value( $control_set, $name ) {
     $sql = 'SELECT control_value'
          . '  FROM Controls'
//        . ' WHERE company_id   =  ' . COMPANY_ID
          . ' WHERE control_set  = "' . $control_set . '"'
          . '   AND control_name = "' . $name . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

# -------------------------------------------------------------------------
#    get control name by id
# -------------------------------------------------------------------------
function get_control_name_by_id( $id ) {
     if( !$id )     return '';

     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( 'SELECT control_name FROM Controls WHERE id = ' . $id  );
}

# -------------------------------------------------------------------------
#    get control value by id
# -------------------------------------------------------------------------
function get_control_value_by_id( $id ) {
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( 'SELECT control_value FROM Controls WHERE id = ' . $id  );
}

# -------------------------------------------------------------------------
#    get control name
# -------------------------------------------------------------------------
function get_control_name( $control_set, $value ) {
     if( !$value )     return '';

     $sql = 'SELECT control_name'
          . '  FROM Controls'
//        . ' WHERE company_id    =  ' . get_session( 'control_company', COMPANY_ID )
          . ' WHERE control_set   = "' . $control_set . '"'
          . '   AND control_value = "' . $value . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

# -------------------------------------------------------------------------
#    get control options
# -------------------------------------------------------------------------
function get_control_options( $control_set, $name, $value, $initial='', $and='' ) {
     if(  $control_set == 'RT' )
          $order = 'value';
     else $order = 'sequence';

     $sql = 'SELECT control_name, control_value'
          . '  FROM Controls'
//        . ' WHERE company_id  =  ' . get_session( 'control_company', COMPANY_ID )
          . ' WHERE control_set = "' . $control_set . '" '
          . '   AND status = "A"'
          . $and
          . ' ORDER BY ' . $order;
          ;
     $db   = Zend_Registry::get( 'db' );
     $rows = $db->fetchAll( $sql );

     if(  '' == $initial )
          $options = '';
//   else $options = NL . '<option value="">' . $initial . '</option>';
     else $options = NL . '<option value="*">' . $initial . '</option>';

     foreach( $rows as $row ) {
          if(  $row[ 'control_value' ] == '' )
               $row[ 'control_value' ]  = $row[ 'control_name' ];
          $selected = ( $row[ 'control_name' ] == $value ? ' selected="selected"' : '' );
//        $options .= NL . '<option value="' . $row[ 'control_name'] . '"'. $selected . '>' . $row[ 'control_value' ] .'</option>';
          $options .= '<option value="' . $row[ 'control_name'] . '"'. $selected . '>' . $row[ 'control_value' ] .'</option>';
     }
     return $options;
}

# -------------------------------------------------------------------------
#    get control names
# -------------------------------------------------------------------------
function get_control_names( $control_set, $name, $value, $initial='', $and='' ) {
     if(  $control_set == 'RT' )
          $order = 'value';
     else $order = 'sequence';

     $sql = 'SELECT name, value'
          . '  FROM Controls'
//        . ' WHERE company_id  =  ' . get_session( 'control_company', COMPANY_ID )
          . ' WHERE control_set = "' . $control_set . '" '
          . '   AND status = "A"'
          . $and
          . ' ORDER BY ' . $order;
          ;
     $db   = Zend_Registry::get( 'db' );
     $rows = $db->fetchAll( $sql );

     if(  '' == $initial )
          $options = '';
//   else $options = NL . '<option value="">' . $initial . '</option>';
     else $options = NL . '<option value="*">' . $initial . '</option>';

     foreach( $rows as $row ) {
          $row[ 'value' ]  = $row[ 'name' ];
          $selected = ( $row[ 'name' ] == $value ? ' selected="selected"' : '' );
          $options .= NL . '<option value="' . $row[ 'name'] . '"'. $selected . '>' . $row[ 'value' ] .'</option>';
     }
     return $options;
}

# -------------------------------------------------------------------------
#    get control rows by group id
# -------------------------------------------------------------------------
function get_control_rows( $control_set ) {
     $sql = 'SELECT *'
          . '  FROM Controls'
//        . ' WHERE company_id  =  ' . get_session( 'control_company', COMPANY_ID )
          . ' WHERE control_set = "' . $control_set . '"'
          . '   AND status = "Active"'
          . ' ORDER BY sequence'
          ;
     $db   = Zend_Registry::get( 'db' );
     return $db->fetchAll( $sql );
}

# -------------------------------------------------------------------------
#    get control next number and increment 1
# -------------------------------------------------------------------------
function get_control_next( $name ) {
     $sql = 'SELECT *'
          . '  FROM Controls'
//        . ' WHERE company_id   =  ' . get_session( 'control_company', COMPANY_ID )
          . ' WHERE control_name = "' . $name . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     $row = $db->fetchRow( $sql );

     $sql = 'UPDATE Controls'
          . '   SET control_value = ' . ((int) $row[ 'control_value' ] + 1 )
          . ' WHERE id = ' . $row[ 'id' ]
          ;
     $db->query( $sql );
     return $row[ 'control_value' ];
}

# -------------------------------------------------------------------------
#    get comments
# -------------------------------------------------------------------------
function get_comments( $name, $id, $date ) {
     $where = 'parent_name = "' . $name . '"'
            . ' AND parent_id = ' . $id
            . ' AND DATE_FORMAT( updated_at, "%Y-%m-%d" ) = "' . $date . '"'
            ;
#    if(  $less_than != null )
#         $where .= ' AND ( TO_DAYS( NOW()) - TO_DAYS( updated_at )) < ' . $less_than;

     $order_by = 'updated_at DESC';
     $db = Zend_Registry::get( 'db' );
     return $db->fetchAll( 'SELECT * FROM Comments WHERE ' . $where . ' ORDER BY ' . $order_by );
}

# -------------------------------------------------------------------------
#    get projects
# -------------------------------------------------------------------------
function get_projects( $id, $order='name' ) {
     $sql = 'SELECT Projects.*'
          . '  FROM UserProjs, Projects'
          . ' WHERE UserProjs.project_id = Projects.id'
          . '   AND UserProjs.status = "A"'
          . '   AND UserProjs.user_id = ' . $id
          . ' ORDER BY ' . $order
          ;
     $db = Zend_Registry::get( 'db' );
     return $db->fetchAll( $sql );
}

# -------------------------------------------------------------------------
#    get product composition
# -------------------------------------------------------------------------
function get_product_composition($product_id) {
	$db = Zend_Registry::get('db');
	if (!$product_id)		return'';

	$sql = 'SELECT composition'
		 . '  FROM FTPs'
		 . ' WHERE product_id = ' . $product_id
		 . ' ORDER BY FTPs.id'
		 . ' LIMIT 1'
		 ;
	$my_composition = $db->fetchOne($sql);
	if ($my_composition)	return $my_composition;

	$sql = 'SELECT parent_id'
		 . '  FROM Products'
		 . ' WHERE id = ' . $product_id
		 ;
	$my_parent_id = $db->fetchOne($sql);
	if (!$my_parent_id)		return '';

	$sql = 'SELECT composition'
		 . '  FROM FTPs'
		 . ' WHERE product_id = ' . $my_parent_id
		 . ' ORDER BY FTPs.id'
		 . ' LIMIT 1'
		 ;
	$my_composition = $db->fetchOne($sql);
	if ($my_composition)	return $my_composition;

	return '';
}

function posted_comments( $counter ) {
     return ( $counter == 0 ? 'Post first comment' : 'Posted ' . pluralize( $counter, 'comment' ));
}

function get_file_ext( $name ) {
     $names = explode( '.', $name );
     return $names[ count( $names ) - 1 ];
}

function get_mime( $ext ) {
     switch( $ext ) {
          case '3g2'     : return 'video/3gpp2'                  ; break; // added 7.29.11
          case '3gp'     : return 'video/3gpp'                   ; break; // added 7.29.11
          case 'ai'      : return 'application/postscript'       ; break;
          case 'asx'     : return 'video/x-ms-asf'               ; break;
          case 'au'      : return 'audio/basic'                  ; break;
          case 'avi'     : return 'video/x-msvideo'              ; break;
          case 'bmp'     : return 'image/bmp'                    ; break;
          case 'css'     : return 'text/css'                     ; break;
          case 'doc'     : return 'application/msword'           ; break;
          case 'eps'     : return 'application/postscript'       ; break;
          case 'exe'     : return 'application/octet-stream'     ; break;
          case 'flv'     : return 'video/x-flv'                  ; break; // added 7.29.11
          case 'gif'     : return 'image/gif'                    ; break;
          case 'htm'     : return 'text/html'                    ; break;
          case 'html'    : return 'text/html'                    ; break;
          case 'ico'     : return 'image/x-icon'                 ; break;
          case 'jpe'     : return 'image/jpeg'                   ; break;
          case 'jpeg'    : return 'image/jpeg'                   ; break;
          case 'jpg'     : return 'image/jpeg'                   ; break;
          case 'js'      : return 'application/x-javascript'     ; break;
          case 'mid'     : return 'audio/mid'                    ; break;
          case 'mov'     : return 'video/quicktime'              ; break;
          case 'mp3'     : return 'audio/mpeg'                   ; break;
          case 'mpeg'    : return 'video/mpeg'                   ; break;
          case 'mpg'     : return 'video/mpeg'                   ; break;
          case 'mp4'     : return 'video/mp4'                    ; break; // added 7.29.11
          case 'pdf'     : return 'application/pdf'              ; break;
          case 'pps'     : return 'application/vnd.ms-powerpoint'; break;
          case 'ppt'     : return 'application/vnd.ms-powerpoint'; break;
          case 'ps'      : return 'application/postscript'       ; break;
          case 'pub'     : return 'application/x-mspublisher'    ; break;
          case 'qt'      : return 'video/quicktime'              ; break;
          case 'rtf'     : return 'application/rtf'              ; break;
          case 'svg'     : return 'image/svg+xml'                ; break;
          case 'swf'     : return 'application/x-shockwave-flash'; break;
          case 'tif'     : return 'image/tiff'                   ; break;
          case 'tiff'    : return 'image/tiff'                   ; break;
          case 'txt'     : return 'text/plain'                   ; break;
          case 'wav'     : return 'audio/x-wav'                  ; break;
          case 'wmf'     : return 'application/x-msmetafile'     ; break;
          case 'wmv'     : return 'video/x-ms-wmv'               ; break; // added 7.29.11
          case 'xls'     : return 'application/vnd.ms-excel'     ; break;
          case 'zip'     : return 'application/zip'              ; break;
//          default        ; return $ext                           ;
          default        ; return 'application/x-unknown';
     }
}

function email_by_event($user_id, $contact_id, $template_name, $email_from, $additional_message='') {
	$jky_user	= db_get_row('JKY_Users', 'id = ' . $user_id	);
	$contact	= db_get_row('Contacts'	, 'id = ' . $contact_id	);
	$to_name	= $contact['full_name'	];
	$to_email	= $contact['email'		];
	$cc_name	= '';
	$cc_email	= '';

	$template	= db_get_row('Templates', 'template_name = "' . $template_name . '"');
	$subject	= revert_entities($template['template_subject'	]);
	$body		= revert_entities($template['template_body'		]);

	$names		= explode(';', get_control_value('System Keys', $email_from));
	$from_name  = $names[0];
	$from_email = $names[1];

	$server_name= SERVER_NAME;
	if (strpos($server_name, '8100') > 0) {
		$server_name = 'http://' . get_control_value('Servers Host', SERVER_NUMBER) . ':8100/';
	}

	$search   = array();
	$replace  = array();
	$search[] = '+'               ; $replace[] = ' ';
	$search[] = '{SERVER_NAME}'   ; $replace[] = $server_name;
	$search[] = '{COMPANY_LOGO}'  ; $replace[] = COMPANY_LOGO;
	$search[] = '{SUPPORT_NAME}'  ; $replace[] = $from_name;
	$search[] = '{USER_EMAIL}'    ; $replace[] = $contact	['email'	];
	$search[] = '{USER_NAME}'     ; $replace[] = $contact	['full_name'];
	$search[] = '{USER_KEY}'      ; $replace[] = $jky_user	['user_key'	];

	$subject  = str_replace($search, $replace, $subject	);
	$body     = str_replace($search, $replace, $body	);
/*
     $data = array();
     if(  is_session( 'user_id' ))      $data[ 'sent_from'  ] = get_session( 'user_id' );
     $data[ 'sent_to'    ] = $user_id;
     $data[ 'sent_at'    ] = get_time();
     $data[ 'to_email'   ] = $user[ 'user_email' ];
     $data[ 'to_name'    ] = $user[ 'full_name'  ];
     $data[ 'cc_email'   ] = '';
     $data[ 'cc_name'    ] = '';
     $data[ 'controller' ] = $template[ 'controller' ];
     $data[ 'action'     ] = $template[ 'action'     ];
     $data[ 'subject'    ] = str_replace( $search, $replace, $template[ 'subject' ]);
     $data[ 'body'       ] = str_replace( $search, $replace, $template[ 'body'    ]);
     $data[ 'body'       ] .= '<br><br>' . $additional_message;

     $model = MODEL . 'Emails';
     $Emails = new $model();
     $Emails->insert( $data );
*/
	$return = email_now($from_email, $from_name, $to_email, $to_name, $cc_email, $cc_name, $subject, $body);
	return $to_email;
}

function email_now($from_email, $from_name, $to_email, $to_name, $cc_email, $cc_name, $subject, $body, $photos=null) {
	$Mail = new Zend_Mail();
	$Mail->setFrom($from_email, $from_name);
	$Mail->addTo  (  $to_email,   $to_name);
	if ('' != $cc_email and $cc_email != $to_email)		$Mail->addCc ($cc_email, $cc_name);
	$Mail->setSubject ($subject);
#	$Mail->setBodyText($body);
	$Mail->setBodyHtml($body);

	if ($photos) {
		foreach( $photos as $photo ) {
			$my_image = file_get_contents(SERVER_NAME . PHOTOS . $photo['id'] . '.' . $photo['ext']);
			$at = new Zend_Mime_Part($my_image);
			$at->type           = 'image/' . $photo['ext'];
			$at->disposition    = Zend_Mime::DISPOSITION_INLINE;
			$at->encoding       = Zend_Mime::ENCODING_BASE64;
			$at->filename       = $photo['file_name'];
			$Mail->addAttachment($at);
		}
	}
//log_sql( null, 'email_now', print_r($Mail, true));

	try {
		 $smtp = get_control_value('System Keys', 'SMTP');

		 if ($smtp == '') {
			$Mail->send();
		 }else{
			$names = explode(';', $smtp);
			if (count($names) == 5) {
				$config = array
					( 'auth'       => 'login'
					, 'username'   => $names[1]
					, 'password'   => $names[2]
					, 'ssl'        => $names[3]
					, 'port'       => $names[4]
					);
			}else
			if (count($names) == 3) {
				$config = array
					( 'auth'		=> 'login'
					, 'username'	=> $names[1]
					, 'password'	=> $names[2]
					);
			}

			if (isset($config)) {
				$transport = new Zend_Mail_Transport_Smtp($names[0], $config);
			}else{
				$transport = new Zend_Mail_Transport_Smtp($names[0]);
			}
			$Mail->send($transport);
		}
	} catch(Exception $exp) {
		log_sql( null, 'email_now', $exp->getMessage());
		return $exp->getMessage();
	}
	return '';
}

function expand_tab( $string ) {
     $tab_stop = 8;
     while( strstr( $string, "\t" )) {
          $string = preg_replace( '/^([^\t]*)(\t+)/e', "'\\1'.str_repeat(' ',strlen('\\2') * $tab_stop - strlen('\\1') % $tab_stop)", $string );
     }
     return $string;
}

# -------------------------------------------------------------------------
#    does the [user] has tag [name] ?
# -------------------------------------------------------------------------
function user_has_tag( $name ) {
     if( !is_session( 'user_id' ))
          return false;

     $db  = Zend_Registry::get( 'db' );
/*
     $sql = 'SELECT id'
          . '  FROM Controls'
          . ' WHERE control_set  = "UT"'
          . '   AND control_name = "' . $tag . '"'
          ;
     $control_id = $db->fetchOne( $sql );

     if( !$control_id )
          return false;
*/
     $sql = 'SELECT id'
          . '  FROM Tags'
          . ' WHERE parent_name = "Users"'
          . '   AND parent_id   = ' . get_session( 'user_id' )
//          . '   AND tag_id      = ' . $control_id
          . '   AND name = "' . $name . '"'
          ;
     $tag_id = $db->fetchOne( $sql );

     if( !$tag_id )
          return false;
     else return true ;
}

# -------------------------------------------------------------------------
#    get user type
#    only Member and has tag [core] then = Core
# -------------------------------------------------------------------------
function get_user_type() {
     $user_level = get_session( 'user_level' );
          if(  $user_level == '' )      $user_type = 'visitor'   ;
     else if(  $user_level >= 5  )      $user_type = 'admin'     ;
     else if(  $user_level >= 2  )      $user_type = 'member'    ;
     else if(  $user_level >= 0  )      $user_type = 'guest'     ;
     else                               $user_type = 'visitor'   ;

     if(  $user_type == 'member' and user_has_tag( 'core' ))
          return 'core';

     return $user_type;
}

# -------------------------------------------------------------------------
#    get swf id
# -------------------------------------------------------------------------
function get_swf_id( $name ) {
     $sql = 'SELECT id'
          . '  FROM Swfs'
//        . ' WHERE company_id = ' . get_session( 'control_company', COMPANY_ID )
          . ' WHERE name = "' . $name . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

// -----------------------------------------------------------------------------

function modulo11( $string ) {
     $factor = 2;
     $sum    = 0;

     for( $i=strlen( $string ); $i>0; $i-- ) {
          $nbr  = substr( $string, $i-1, 1 );
          $sum += $nbr * $factor;
          $factor++;
          if(  $factor > 9 )       $factor = 2;
     }

     $result = 11 - ( $sum % 11 );
     if(  $result > 9 )       $result = 0;
     return '' . $result;
}

function tag( $tag, $text='' ) {
     if(  $text == '' ) {
          return NL . '<' . $tag . '/>';
     } else {
          return NL . '<' . $tag .   '>' . $text . '</' . $tag . '>';
     }
}

function tag_nl( $tag, $text, $extra ) {
     if(  $text != '' )       $text = ' ' . $text;
     return NL . '<' . $tag . $text . '>' . $extra . NL . '</' . $tag . '>';
}

function remover_acentos( $string ) {
     $output = '';
     for( $n=0; $n<mb_strlen( $string, 'utf-8' ); $n++ ) {
          $char  = mb_substr( $string, $n, 1, 'utf-8' );
          if(  strlen( $char ) > 1 ) {
               $ord = 64 + ord( substr( $char, 1, 1));
               if(  $ord >= 224 and $ord <= 229 )      $char = 'a';
          else if(  $ord == 231 )                      $char = 'c';
          else if(  $ord >= 232 and $ord <= 235 )      $char = 'e';
          else if(  $ord >= 236 and $ord <= 239 )      $char = 'i';
          else if(  $ord == 241 )                      $char = 'n';
          else if(  $ord >= 242 and $ord <= 246 )      $char = 'o';
          else if(  $ord >= 249 and $ord <= 252 )      $char = 'u';
          else if(  $ord >= 192 and $ord <= 197 )      $char = 'A';
          else if(  $ord == 199 )                      $char = 'C';
          else if(  $ord >= 200 and $ord <= 203 )      $char = 'E';
          else if(  $ord >= 204 and $ord <= 207 )      $char = 'I';
          else if(  $ord == 209 )                      $char = 'N';
          else if(  $ord >= 210 and $ord <= 214 )      $char = 'O';
          else if(  $ord >= 217 and $ord <= 220 )      $char = 'U';
          }
          $output .= $char;
     }
     return $output;
}

//   sends the transaction to us and processes the response into a standard PHP array
function tclink_send( $fields_to_send ) {
     $post_string = '';
     $use_amp = 0;
     foreach( $fields_to_send as $key => $value ) {
          if(  $use_amp )     $post_string .= '&';
          $post_string .= "$key=$value";
          $use_amp = 1;
     }
     $curl_object = curl_init( 'https://vault.trustcommerce.com/trans/' );

//   required for Windows
     curl_setopt( $curl_object, CURLOPT_SSL_VERIFYPEER , 0 );

     curl_setopt( $curl_object, CURLOPT_RETURNTRANSFER , 1 );
     curl_setopt( $curl_object, CURLOPT_POST           , 1 );
     curl_setopt( $curl_object, CURLOPT_POSTFIELDS     , $post_string );

     $unformatted_results = curl_exec( $curl_object );
     curl_close( $curl_object );
     $result_array = explode( "\n", $unformatted_results );
     $tclink_results = array();
     foreach( $result_array as $key => $value ) {
          $key_pair = explode( '=', $value );
          if(  count( $key_pair ) == 2 ) {
               $tclink_results[ $key_pair[ 0 ]] = $key_pair[ 1 ];
          }
     }
     return $tclink_results;
}

//   input type=file id='image' name='image'
function createPhoto( $name, $ext, $maxW, $maxH ) {
     $file     = $_FILES[ 'image'  ];
     $uploaded = $file[ 'tmp_name' ];
     if(  strlen( $uploaded ) == 0 )
          return;

     $random    = (int) rand() * 1000000;
     $tempfile  = SERVER_BASE . PHOTOS . 'temp' . $random . '.' . $ext;
     move_uploaded_file( $uploaded, $tempfile );

     $info = getimagesize( $tempfile );
     $w = $info[ 0 ];
     $h = $info[ 1 ];

     $ratio = $w / $h;

     $maxW = min( $w, $maxW );     if( $maxW == 0 )    $maxW = $w;
     $maxH = min( $h, $maxH );     if( $maxH == 0 )    $maxH = $h;

     $newW = $maxW;
     $newH = $newW / $ratio;

     if(  $newH > $maxH ) {
          $newH = $maxH;
          $newW = $newH * $ratio;
     }

     switch( $ext ) {
          case 'gif':    $infunc = 'imagecreatefromgif' ;   $outfunc = 'imagegif' ;  break;
          case 'jpg':    $infunc = 'imagecreatefromjpeg';   $outfunc = 'imagejpeg';  break;
          case 'png':    $infunc = 'imagecreatefrompng' ;   $outfunc = 'imagepng' ;  break;
          default   :    throw new Exception( 'Invalid image type' );
     }

     $image = $infunc( $tempfile );
     unlink( $tempfile );

     if( !$image )                           throw new Exception( 'Unable to read image file' );

     $photo = imagecreatetruecolor( $newW, $newH );
     imagecopyresampled( $photo, $image, 0, 0, 0, 0, $newW, $newH, $w, $h );

     $photoPath = SERVER_BASE . PHOTOS . $name . '.' . $ext;
     $outfunc( $photo, $photoPath );

     if(  ! file_exists( $photoPath ))       throw new Exception( 'Unkown error occured creating photo' );
     if(  ! is_readable( $photoPath ))       throw new Exception( 'Unable to read photo' );
}

function createThumb( $name, $ext, $maxW, $maxH ) {
     $photoPath = SERVER_BASE . PHOTOS . $name . '.' . $ext;

     $info = getimagesize( $photoPath );
     $w = $info[ 0 ];
     $h = $info[ 1 ];

     $ratio = $w / $h;

     if(  $ratio < 1 ) {
          $newW = min( $w, $maxW );
          $newH = $newW / $ratio;
     } else {
          $newH = min( $h, $maxH );
          $newW = $newH * $ratio;
     }

     switch( $ext ) {
          case 'gif':    $infunc = 'imagecreatefromgif' ;   $outfunc = 'imagegif' ;  break;
          case 'jpg':    $infunc = 'imagecreatefromjpeg';   $outfunc = 'imagejpeg';  break;
          case 'png':    $infunc = 'imagecreatefrompng' ;   $outfunc = 'imagepng' ;  break;
          default   :    throw new Exception( 'Invalid image type' );
     }

     $image = @$infunc( $photoPath );

     if( !$image )                           throw new Exception( 'Unable to read image file' );

     $thumb = imagecreatetruecolor( $newW, $newH );
     imagecopyresampled( $thumb, $image, 0, 0, 0, 0, $newW, $newH, $w, $h );

     $thumbPath = SERVER_BASE . THUMBS . $name . '.' . $ext;
     $outfunc( $thumb, $thumbPath );

     if(  ! file_exists( $thumbPath ))       throw new Exception( 'Unkown error occured creating thumbnail' );
     if(  ! is_readable( $thumbPath ))       throw new Exception( 'Unable to read thumbnail' );
}

function tooltip_button( $name, $width=300 ) {
//     return '<img border=0 style="margin:0 10px; vertical-align:bottom;"'
     return '<img '
          . ' src="' . SERVER_NAME . IMAGES . 'oculu/question.png' . '"'
          . ' onmouseover="tooltip.show( this, \'' . $name . '\',' . $width . ' );"'
          . '  onmouseout="tooltip.hide();"'
          . ' />'
          ;
}

function put_status_new( $status ) {
          if(  $status == 'A'                     )    $html = put_img( 'green_circle.png', 'width="20", height="20", alt=""' );
     else if(  $status == 'I'                     )    $html = put_img(   'red_circle.png', 'width="20", height="20", alt=""' );
     else if(  $status == 'X'                     )    $html = put_img(   'red_circle.png', 'width="20", height="20", alt=""' );
     else if(  $status == 'Q' || $status == 'E'   )    $html = put_img(        'clock.png', 'width="20", height="20", alt=""' );
     else if(  $status == 'N'                     )    $html = put_img(   'red_circle.png', 'width="20", height="20", alt=""' );
     else                                              $html = $status;
     return $html;
}

function handle_blank( $field ) {
     return ( $field == '' ) ? '&nbsp;' : $field;
}

# -------------------------------------------------------------------------
#    get member info
# -------------------------------------------------------------------------
function get_member_info( $id ) {
     $db  = Zend_Registry::get( 'db' );

     $sql = 'SELECT IF( Members.user_id IS NULL, Companies.contact_id, Members.user_id ) AS user_id'
          . '  FROM Members'
          . '  LEFT JOIN Companies ON  Members.company_id = Companies.id'
          . ' WHERE Members.id  =  ' . $id
          ;
     $user_id = $db->fetchOne( $sql );

     $sql = 'SELECT *'
          . '  FROM Users'
          . ' WHERE id  =  ' . $user_id
          ;
     return $db->fetchRow( $sql );
}

function get_http_referer() {
     $referer  = isset( $_SERVER[ 'HTTP_REFERER' ]) ? $_SERVER[ 'HTTP_REFERER' ] : EXTERNAL_URL;
     $referer  = strtolower( $referer );
     $names    = explode( '/', $referer );
     return $names[ 0 ] . '//' . $names[ 2 ] . '/';
}

function db_get_id( $table, $where ) {
     $sql = 'SELECT id'
          . '  FROM ' . $table
          . ' WHERE ' . $where
          ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

function db_get_row( $table, $where ) {
     $sql = 'SELECT *'
          . '  FROM ' . $table
          . ' WHERE ' . $where
          ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchRow( $sql );
}

function db_get_rows( $table, $where ) {
     $sql = 'SELECT *'
          . '  FROM ' . $table
          . ' WHERE ' . $where
          ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchAll( $sql );
}

function db_get_sum( $table, $field, $where ) {
	$sql= 'SELECT SUM(' . $field . ') AS sum'
		. '  FROM ' . $table
		. ' WHERE ' . $where
		;
	$db = Zend_Registry::get('db');
	return $db->fetchOne( $sql );
}

//   Meta DB functions ---------------------------------------------------------

function meta_replace( $table, $parent_id, $meta_name, $meta_value ) {
     $id = meta_get_id( $table, $parent_id, $meta_name );
     if( !$id )
          meta_insert( $table, $parent_id, $meta_name, $meta_value );
     else meta_update( $table, $id, $meta_value );
}

function meta_insert( $table, $parent_id, $meta_name, $meta_value ) {
     $sql = 'INSERT ' . $table . '_metas'
          . '   SET parent_id  =  ' . $parent_id
          . '     , meta_name  = "' . $meta_name  . '"'
          . '     , meta_value = "' . $meta_value . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     $db->query( $sql );
     $id = $db->lastInsertId();
     log_sql( $table . '_metas', $id, 'inserted, sql=' . $sql );
     return $id;
}

function meta_update( $table, $id, $meta_value ) {
     $sql = 'UPDATE ' . $table . '_metas'
          . '   SET meta_value = "' . $meta_value . '"'
          . ' WHERE id  =  ' . $id
          ;
     $db  = Zend_Registry::get( 'db' );
     $db->query( $sql );
     log_sql( $table . '_metas', $id, 'updated, sql=' . $sql );
}

function meta_delete( $table, $parent_id, $meta_name ) {
     $id = meta_get_id( $table, $parent_id, $meta_name );
     if( !$id )     return;

     $sql = 'DELETE FROM ' . $table . '_metas'
          . ' WHERE id  =  ' . $id
          ;
     $db  = Zend_Registry::get( 'db' );
     $db->query( $sql );
     log_sql( $table . '_metas', $id, 'deleted, sql=' . $sql );
}

function meta_empty( $table, $parent_id ) {
     $sql = 'DELETE FROM ' . $table . '_metas'
          . ' WHERE parent_id  =  ' . $parent_id
          ;
     $db  = Zend_Registry::get( 'db' );
     $db->query( $sql );
}

function meta_get_id( $table, $parent_id, $meta_name ) {
     $sql = 'SELECT id'
          . '  FROM ' . $table . '_metas'
          . ' WHERE parent_id = ' . $parent_id
          . '   AND meta_name = "' . $meta_name . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

function meta_get_value( $table, $parent_id, $meta_name ) {
     $sql = 'SELECT meta_value'
          . '  FROM ' . $table . '_metas'
          . ' WHERE parent_id = ' . $parent_id
          . '   AND meta_name = "' . $meta_name . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

//   ---------------------------------------------------------------------------

function set_permissions( $user_role ) {
     $sql = 'SELECT user_resource, user_action'
          . '  FROM Permissions'
          . ' WHERE user_role = "' . $user_role . '"'
          ;
     $db  = Zend_Registry::get( 'db' );
     $permissions = $db->fetchAll( $sql );
     set_session( 'permissions', $permissions );
}

function set_valid_days($date) {
	$control_id		= COMPANY_ID;
	$control_pref	= encrypt_decrypt('Bwpagca');
	$control_date	= get_control_key('Glec}t:Pydm', 'Qmf~j|:_}i{');
	$control_key	= get_control_key('Glec}t:Pydm', 'Vlee}9Q~e' );
	$valid_key		= encrypt_hash($control_pref . 'E' . $control_id . $control_date);
	$valid_days		= ($valid_key == $control_key) ? difference_in_days($date, $control_date) : -1;
	set_session('valid_days', $valid_days);
}

function get_user_action( $user_resource ) {
    $permissions  = get_session( 'permissions' );
    $my_separator = '';
    $my_actions   = '';
    if ($permissions != '') {
        foreach( $permissions as $permission ) {
            if ($permission['user_resource'] == $user_resource) {
                $my_actions  .= $my_separator . $permission['user_action'];
                $my_separator = ' ';
            }
//log_sql ('Permissions', 0, 'permission: ' . $permission['user_resource'] . '=' . $permission['user_action'] . ', my_actions: ' . $my_actions);
        }
    }
//log_sql ('Permissions', 0, 'resource: ' . $user_resource . ', actions: ' . $my_actions);
    return $my_actions;
}

//        ----------------------------------------------------------------------
//  special code to revert javascript injection, intercept: & < > "
function revert_entities($string) {
	$string = str_replace( "&lt;"  , "<", $string );
	$string = str_replace( "&gt;"  , ">", $string );
	$string = str_replace( "&quot;", '"', $string );
	return $string;
}

function Xget_next_number($table, $name ) {
	$db = Zend_Registry::get('db');
	$sql= 'SELECT value'
		. '  FROM ' . $table
		. ' WHERE name = "' . $name . '"'
		;
	$my_number = $db->fetchOne($sql);
	$my_next   = (int)$my_number + 1;
	$sql= 'UPDATE ' . $table
		. '   SET value = "' . $my_next . '"'
		. ' WHERE name  = "' . $name . '"'
		;
	$db->query($sql);
	return $my_number;
}

function get_next_id($table) {
	$db = Zend_Registry::get('db');
	$sql= 'SELECT next_id, id_size'
		. '  FROM NextIds'
		. ' WHERE table_name = "' . $table . '"'
		;
	$my_row = $db->fetchRow($sql);
	$sql= 'UPDATE NextIds'
		. '   SET next_id = next_id + 1'
		. ' WHERE table_name  = "' . $table . '"'
		;
	$db->query($sql);
	return SERVER_NUMBER . str_pad($my_row['next_id'], $my_row['id_size'], '0', STR_PAD_LEFT);
}

function insert_changes($the_db, $the_table_name, $the_table_id) {
	$sql= 'SELECT id'
		. '  FROM Changes'
		. ' WHERE table_name ="' . $the_table_name . '"'
		. '   AND table_id =' . $the_table_id
		;
	$my_id = $the_db->fetchOne($sql);

	if ($my_id) {
		$sql= 'UPDATE Changes'
			. '   SET updated_at ="' . get_now() . '"'
			. ', servers = ""'
			. ' WHERE id =' . $my_id
			;
	}else{
		$sql= 'INSERT Changes'
			. '   SET updated_at ="' . get_now() . '"'
			. ', table_name ="' . $the_table_name . '"'
			. ', table_id =' . $the_table_id
			;
	}
	$the_db->query($sql);
}

/*
 *   proxy - using curl
 */
function proxy($domain, $postvars) {
log_prog('proxy', 'domain: '   . $domain  );
log_prog('proxy', 'POSTVARS: ' . $postvars);

	$ch = curl_init($domain);

	curl_setopt($ch, CURLOPT_POST          , 0);
	curl_setopt($ch, CURLOPT_VERBOSE       , 0);
//	curl_setopt($ch, CURLOPT_USERAGENT     , isset( $_SERVER[ 'User-Agent' ]) ? $_SERVER[ 'User-Agent' ] : '');
	curl_setopt($ch, CURLOPT_POSTFIELDS    , $postvars);
//	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_REFERER       , $domain);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
	curl_setopt($ch, CURLOPT_AUTOREFERER   , 0);
	curl_setopt($ch, CURLOPT_COOKIEJAR     , 'ses_' . session_id());
	curl_setopt($ch, CURLOPT_COOKIEFILE    , 'ses_' . session_id());
//	curl_setopt($ch, CURLOPT_COOKIE        , $COOKIE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR   , 1);

	$content  = curl_exec   ($ch);
//log_prog('proxy', 'content: ' . $content);
	$response = curl_getinfo($ch);
//log_prog('proxy', 'response: ' . json_encode($response));

	curl_close($ch);

	$filename = 'ses_' . session_id();
	if (file_exists($filename))		unlink($filename);

	return $content;
}

/**
 * This function will output variable value to a log file
 * @access public
 * @param $value - Variable that we want to log
 * @param $label - label that we want to use to identify the output
 * @param $file_name - specific file name where we want to store the output
 */
function poop($value, $label='', $file_name=NULL, $url_pattern=NULL) {
	if (!empty($url_pattern) && !strstr($_SERVER['HTTP_REFERER'], $url_pattern)) 	return FALSE;
	$file_path  = '../';
	$file_path .= empty($file_name) ? 'poop.log' : $file_name;
	
	$file_handler = fopen($file_path,'a+');
	if ($file_handler) {
		if (!empty($label))		fwrite($file_handler, "----- Start {$label} -----\n");
		fwrite($file_handler, var_export($value, TRUE) . "\n");
		if (!empty($label))		fwrite($file_handler, "----- End -----\n\n");			
		fclose($file_handler);
	}	
}
?>
