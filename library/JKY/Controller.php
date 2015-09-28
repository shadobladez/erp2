<?
class     JKY_Controller
extends   Zend_Controller_Action {

public $table;                //   table name
public $class;                //   class name
public $min_level;            //   minimum access level of each program
public $per_page;             //   select records or display lines per page

public $db;
public $messenger;

public function getWhere      ()        { return '' ; }
public function getKey        ()        { return '*'; }
public function getData       ()        { return '' ; }

public function posIndex      ()        { }

public function preInsert     ( $id )   { }
public function preUpdate     ( $id )   { }
public function preDelete     ( $id )   { }

public function posInsert     ( $id )   { }
public function posUpdate     ( $id )   { }
public function posDelete     ( $id )   { }
public function posReplace    ( $id )   { }
public function posShow       ( $id )   { }

public function init() {
	$table='';
	$class='';
	$min_level=0;
	$per_page=0;

     if(  is_request( 'event_id' ))     set_new_event( get_request( 'event_id' ));

     $this->table        = MODEL . $table;
     $this->class        = $class       ;
     $this->min_level    = $min_level   ;
     $this->per_page     = $per_page    ;

     $this->db           = Zend_Registry::get( 'db' );
     $this->messenger    = $this->_helper->_flashMessenger;      //   any session must be after flashMessenger

     if(  Zend_Version::compareVersion( '1.10.1' ))
          date_default_timezone_set( get_control_value( 'System Keys', 'Time Zone' ));
     else set_session( 'language', Zend_Registry::get( 'language' ));

     putenv( 'TZ=' . get_control_value( 'System Keys', 'Time Zone' ));
     $this->db->query( 'SET time_zone=' . preg_replace( '/([+-]\d{2})(\d{2})/', '\'\1:\2\'', date( 'O' )));

     $request  = Zend_Controller_Front::getInstance()->getRequest();
     $contr    = $request->getControllerName();
     $action   = $request->getActionName();

     set_session( 'table'     , $table  );
     set_session( 'class'     , $class  );
     set_session( 'contr'     , $contr  );
     set_session( 'action'    , $action );

     logger( $contr );

//   set control_company from url's request
     if(   is_request( 'control_company' ))
          set_session( 'control_company', get_request('control_company' ));

//   set control_company from url's subdomain
/*
     if( !is_session( 'control_company' )) {
          $http_host = $_SERVER[ 'HTTP_HOST' ];
          $names = explode( '.', $http_host );
          $model = MODEL . 'Companies';
          $Companies = new $model();
          $company_id = $Companies->getIdByName( $names[ 0 ]);
          if( !$company_id )
               $company_id = COMPANY_ID;
          set_session( 'control_company', $company_id );
     }
*/
     if( !is_logged() and is_request( 'user_key' ) and $action != 'confirm' )
          self::check_user_key();

     if(  $contr != 'homelx'
     and  $contr != 'index'
     and  $contr != 'uploads'
     and  $contr != 'user' ) {

//        memorize return_login ( entry point of the system )
#          if( ! is_session( 'return_login' ))
#               set_session( 'return_login', get_session( 'contr' ) . '/' . get_session( 'action' ));
          if(  $contr == 'orderstt2' and $action == 'show'     and is_request( 'id' ))     set_session( 'return_login', $contr . '/' . $action . '?id=' . get_request( 'id' ));
          if(  $contr == 'transvn'   and $action == 'response' and is_request( 'id' ))     set_session( 'return_login', $contr . '/' . $action . '?id=' . get_request( 'id' ) . '&updated_at=' . get_request( 'updated_at' ));
          if(  $contr == 'feesvn'    and $action == 'show'     and is_request( 'id' ))     set_session( 'return_login', $contr . '/' . $action . '?id=' . get_request( 'id' ));

//        required login
          if( !is_logged())
               $this->_redirect( 'user/login' );

//        check user_level against minimum access level of each program
          if(  !is_permitted( $this->min_level )) {
               $model = MODEL . 'Users';
               $Users = new $model();
               $Users->setLogout();
                unset_session( 'return_login' );       //   clean up previous return to avoid login looping
               $this->_redirect( 'user/login' );
          }
     }

     if(  is_request( 'search' )) {
          $search = get_request( 'search' );
          set_claxx( 'search', $search );      //   save [search] for returned page
          set_claxx( 'page'  , '1'     );      //   always starts from page 1
     }

     if(  is_request( 's_tags' )) {
          $s_tags = get_request( 's_tags' );
          set_claxx( 's_tags', $s_tags );      //   save [s_tags] for returned page
          set_claxx( 'page'  , '1'     );      //   always starts from page 1
     }
}

# -------------------------------------------------------------------------
#    login a user by user_key cookie if necessary
# -------------------------------------------------------------------------
public function check_user_key() {
          $user_key = get_cookie ( 'user_key' );

     if( !$user_key )
          $user_key = get_request( 'user_key' );

     if(  $user_key ) {
          $model = MODEL . 'Users';
          $Users = new $model();
          $row   = $Users->getRowByUserKey( $user_key );
          $Users->setLogin( $row );
     }
}

public function indexAction() {
     if( !is_request( 'id' ) and !is_request( 'page' ))       unset_claxx( 'search' );

     $order = $this->set_order();
     $where = $this->getWhere();

     $Table = new $this->table();
     $count = $Table->getCount( $where );
     $first_row = $this->set_page_control( $count, $this->per_page );
     $this->view->rows = $Table->getRows( $where, $order, $first_row, $this->per_page );
     set_session_ids( $this->view->rows );
     unset_session( 'id' );
     $this->posIndex();
}

# -------------------------------------------------------------------------
#    set page control & current_page
# -------------------------------------------------------------------------
public function set_page_control( $count, $per_page ) {
     $class_page    = is_claxx( 'page' ) ? get_claxx( 'page' ) : 1;
     $pages         = ceil( $count / $per_page );
     $current_page  = is_request( 'page' ) ? get_request( 'page' ) : $class_page;
     if(  $current_page > $pages and $pages > 0 )       $current_page = $pages;
     $first_row     = ( $current_page - 1 ) * $per_page;
     set_session( 'current_page', $current_page );
     set_claxx( 'page', $current_page );

     if(  $pages > 0 ) {
          $this->view->index   = '# ' . ( $first_row + 1 ) . '-' . min( $first_row + $per_page, $count ) . ' of ' . $count;
          $this->view->first   = $current_page == 1      ? null: get_session( 'action' ) . '?page=1';
          $this->view->previous= $current_page == 1      ? null: get_session( 'action' ) . '?page=' . ( $current_page - 1 );
          $this->view->next    = $current_page == $pages ? null: get_session( 'action' ) . '?page=' . ( $current_page + 1 );
          $this->view->page_of = $current_page . ' of ' . $pages;
     }
     return $first_row;
}

#    ----------------------------------------------------------------------
#    set order
#    ----------------------------------------------------------------------
public function set_order() {
     $names = func_get_args();

//   $order_field = get_session( 'order_field' );
     $order_field = get_claxx( 'field' );
     if( !in_array( $order_field, $names ))       $order_field = $names[ 0 ];
     set_session( 'order_field', $order_field );
     set_claxx( 'field', $order_field );

//   $order_seq = get_session( 'order_seq' );
     $order_seq = get_claxx( 'seq' );
     if(  $order_seq == '' )                      $order_seq = 'ASC';
     set_session( 'order_seq', $order_seq );
     set_claxx( 'seq', $order_seq );

     return $order_field . ' ' . $order_seq;
}

public function orderAction() {
     $field = get_request( 'field' );
     if(  get_claxx( 'field' ) != $field ) {
          set_claxx( 'field', $field );
     } else {
          if(  get_claxx( 'seq' ) != 'ASC' )
               set_claxx( 'seq', 'ASC'  );
          else set_claxx( 'seq', 'DESC' );
     }
     $this->_redirect( get_session( 'contr' ) . '/index?page=' . get_claxx( 'page' ));
}

public function showAction() {
     $id = get_request( 'id' );
     $this->view->row = $this->findRow( $id );
     $this->view->return = 'index?page=' . get_session( 'current_page' );
     set_previous_next( $this, $id );
     set_session( 'id', $id );

     $Table = new $this->table();
     $this->view->comments = $Table->getComments( $id );
     $this->posShow( $id );
}

public function insertAction() {
     if(  $this->getKey() == '' )       $this->_redirect( get_session( 'contr' ) . '/index?page=' . get_session( 'current_page' ));

     $id = null;
     $data  = $this->getData();
     $Table = new $this->table();
     $this->preInsert( $id );
     $id = $Table->insert( $data );
     $this->posInsert( $id );
#    $this->_redirect( get_session( 'contr' ) . '/show?id=' . $id );
     $this->_redirect( get_session( 'contr' ) . '/index' );
}

public function updateAction() {
     if( !is_request( 'commit' ))        return;
#    if( !is_request( 'commit' ))        $this->_redirect( get_session( 'contr' ) . '/index?page=' . get_session( 'current_page' ));

     $id = get_session( 'id' );
     $Table = new $this->table();

     if(  get_request( 'commit' ) == 'Delete' ) {
          $data[ 'status' ] = 'I';
          $this->preDelete( $id );
#         $table->delete  ( $id );
          $Table->update  ( $id, $data );
          $this->posDelete( $id );
     } else {
          if(  $this->getKey() == '' )       $this->_redirect( get_session( 'contr' ) . '/index?page=' . get_session( 'current_page' ));

          $data = $this->getData();
          $this->preUpdate( $id );
          $Table->update  ( $id, $data );
          $this->posUpdate( $id );
     }

#    $this->_redirect( get_session( 'contr' ) . '/show?id=' . $id );
     $this->_redirect( get_session( 'contr' ) . '/index?page=' . get_session( 'current_page' ));
}

public function deleteAction() {
     $id = get_request( 'id' );

#     $this->preDelete( $id );
     $Table = new $this->table();
#    $table->delete  ( $id );
     $data[ 'status' ] = 'I';
     $Table->update  ( $id, $data );
     $this->posDelete( $id );
     $this->_redirect( get_session( 'contr' ) . '/index?page=' . get_session( 'current_page' ));
}

public function addcommentAction() {
     $id = get_request( 'id' );
     $comment = get_request( 'comment' );
     if(  get_request( 'commit' ) == 'Add Comment' and $comment != '' ) {
          if(  $this->table == 'Clients' ) {
               $model = MODEL . 'Users';
               $Table = new $model();
          } else {
               $Table = new $this->table();
          }
          $Table->addComment( $id, $comment );
     }
     $this->_redirect( get_session( 'contr' ) . '/show?id=' . $id );
}

public function addnoteAction() {
     $id = get_request( 'id' );
     $note = get_request( 'note' );
     if(  get_request( 'commit' ) == 'Add Note' and $note != '' ) {
          if(  $this->class == 'Clients' ) {
               $model = MODEL . 'Users';
               $Table = new $model();
          } else {
               $Table = new $this->table();
          }
          $Table->addNote( $id, $note );
     }
     $this->_redirect( get_session( 'contr' ) . '/show?id=' . $id );
}

public function addimageAction() {
     $id = get_request( 'id' );
     $filename = get_request( 'filename' );
     if(  get_request( 'commit' ) == 'Add Image' and $filename != '' ) {
          $Table = new $this->table();
          $Table->addFileName( $id, $filename );
     }
     $this->_redirect( get_session( 'contr' ) . '/show?id=' . $id );
}

public function findRow( $id ) {
     if(  $id == 'undefined' )
          return array();

     $Table = new $this->table();
     $rows  = $Table->find( $id )->toArray();
     return $rows[ 0 ];
}

public function __call( $method, $args ) {
     if(  'Action' == substr( $method, -6 ))
          $this->_redirect( get_session( 'contr' ) . '/index' );
}

//  --------------------------------------------------------------------------

public $counters = array();

public function init_counters() {
    $this->counters = array();
}

public function add_counter($group_by, $group_key, $week_1, $week_2, $week_3) {
    if (!array_key_exists($group_by, $this->counters)) {
        $this->counters[$group_by] = array();
    }
    if (!array_key_exists($group_key, $this->counters[$group_by])) {
        $this->counters[$group_by][$group_key] = array(0,0,0);
    }
    $this->counters[$group_by][$group_key][0] += $week_1;
    $this->counters[$group_by][$group_key][1] += $week_2;
    $this->counters[$group_by][$group_key][2] += $week_3;
}

public function get_counters() {
    return $this->counters;
}

}
?>