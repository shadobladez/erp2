<?
class     JKY_DbTable
extends   Zend_Db_Table {

protected $_name    ;
protected $_primary ;
//protected $_identity;                           //   Undefined offset: 0 in C:\htdocs\zf\library\Zend\Db\Table\Abstract.php  on line 900
protected $_last_id ;

public function init( $name, $primary ) {
     $this->_name        = $name   ;
     $this->_primary     = $primary;
//     $this->_identity    = 'id';                //   Undefined offset: 0 in C:\htdocs\zf\library\Zend\Db\Table\Abstract.php  on line 900
}

public function insert( $data, $log=true ) {
     $data[ 'updted_at' ] = get_time();
     if(  is_session( 'user_id' ))      $data[ 'updated_by' ] = get_session( 'user_id' );
     parent::insert( $data );

     $id = $this->getAdapter()->lastInsertId();
     if(  $log ) {
          log_sql( $this->_name, $id, 'inserted', $data );
          set_session( 'notice', 'new record added' );
     }
     return $id;
}

public function update( $id, $data, $log=true ) {
     $data[ 'updated_at' ] = get_time();
     if(  is_session( 'user_id' ))      $data[ 'updated_by' ] = get_session( 'user_id' );

     $where = $this->getAdapter()->quoteInto( 'id = ?', $id );
     parent::update( $data, $where );

     if(  $log ) {
          log_sql( $this->_name, $id, 'updated', $data );
          set_session( 'notice', 'current record saved' );
     }
}

public function delete( $id, $log=true ) {
     $where = $this->getAdapter()->quoteInto( 'id = ?', $id );
     parent::delete( $where );

     if(  $log ) {
          log_sql( $this->_name, $id, 'deleted' );
          set_session( 'notice', 'last record deleted' );
     }
}

public function query( $sql ) {
     $this->getAdapter()->query( $sql );
     log_sql( $this->_name, $sql );
}

public function activate( $id ) {
     $data[ 'status' ] = 'A';
     $this->update( $id, $data );
     set_session( 'notice', 'last record activated' );
}

public function inactivate( $id ) {
     $data[ 'status' ] = 'I';
     $this->update( $id, $data );
     set_session( 'notice', 'last record inactivated' );
}

public function close( $id ) {
     $data[ 'status' ] = 'C';
     $this->update( $id, $data );
     set_session( 'notice', 'last record closed' );
}

public function open( $id ) {
     $data[ 'status' ] = 'O';
     $this->update( $id, $data );
     set_session( 'notice', 'last record opened' );
}

public function getLastId() {
     return $this->_last_id;
}

public function getRowById( $id='' ) {
     if(  '' == $id )    return false;

     $sql = 'SELECT *'
          . '  FROM ' . $this->_name
          . ' WHERE id = ' . $id
          ;
     return $this->getAdapter()->fetchRow( $sql );
}

public function get_row_by_id( $id='' ) {
     if(  '' == $id )    return false;

     $sql = 'SELECT *'
          . '  FROM ' . $this->_name
          . ' WHERE id = ' . $id
          ;
     return $this->getAdapter()->fetchRow( $sql );
}

public function getRow( $where ) {
     return $this->getAdapter()->fetchRow( 'SELECT *  FROM ' . $this->_name . ' WHERE ' . $where );
}

public function getValue( $field, $id ) {
     return $this->getAdapter()->fetchOne( 'SELECT ' . $field . ' FROM ' . $this->_name . ' WHERE id = ' . $id );
}

public function getId( $where ) {
     $result = $this->getAdapter()->fetchCol( 'SELECT id FROM ' . $this->_name . ' WHERE ' . $where );
     if(  $result )
          return $result[ 0 ];
     else return null;
}

public function getIdByName( $name ) {
     $result = $this->getAdapter()->fetchCol( 'SELECT id FROM ' . $this->_name . ' WHERE name = "' . $name . '"' );
     if(  $result )
          return $result[ 0 ];
     else return null;
}

public function getIdByParent( $parent_name, $parent_id ) {
     $result = $this->getAdapter()->fetchCol( 'SELECT id FROM ' . $this->_name . ' WHERE parent_name = "' . $parent_name . '" AND parent_id = ' . $parent_id );
     if(  $result )
          return $result[ 0 ];
     else return null;
}

public function getRowByParent( $parent_name, $parent_id ) {
     $result = $this->getAdapter()->fetchRow( 'SELECT *  FROM ' . $this->_name . ' WHERE parent_name = "' . $parent_name . '" AND parent_id = ' . $parent_id );
     if(  $result )
          return $result;
     else return null;
}

public function getCount( $where ) {
     $sql = 'SELECT COUNT(*)'
          . '  FROM ' . $this->_name
          . ' WHERE ' . $where
          ;
     return $this->getAdapter()->fetchOne( $sql );
}

public function getRows( $where, $order_by='id', $first_row=0, $row_count=9999 ) {
     $sql = 'SELECT *'
          . '  FROM ' . $this->_name
          . ' WHERE ' . $where . ' ORDER BY ' . $order_by . ' LIMIT ' . $first_row . ', ' . $row_count
          ;
     return $this->getAdapter()->fetchAll( $sql );
}

public function getAddresses( $id ) {
     $sql   = 'SELECT *'
            . '  FROM Addresses'
            . ' WHERE status = "A"'
            . '   AND parent_name = "' . $this->_name . '"'
            . '   AND parent_id = ' . $id
            . ' ORDER BY updated_at DESC'
            ;
     return $this->getAdapter()->fetchAll( $sql );
}

public function addComment( $id, $comment ) {
     $data[ 'parent_name'] = $this->_name;
     $data[ 'parent_id'  ] = $id;
     $data[ 'comment'    ] = $comment;

     $Table = new Model_Comments();
     $Table->insert( $data );

     $this->getAdapter()->query( 'UPDATE ' . $this->_name . ' SET counter = counter + 1  WHERE id = ' . $id );
}

//public function addTag( $id, $tag_id ) {
public function addTag( $id, $name ) {
     $data[ 'parent_name'] = $this->_name;
     $data[ 'parent_id'  ] = $id;
//   $data[ 'tag_id'     ] = $tag_id;
     $data[ 'name'       ] = $name;

     $Table = new Tags();
     $Table->insert( $data );
}

public function addNote( $id, $note ) {
     $data[ 'parent_name'] = $this->_name;
     $data[ 'parent_id'  ] = $id;
     $data[ 'note'       ] = $note;

     $Table = new Notes();
     $Table->insert( $data );
}

public function getComments( $id ) {
     $sql   = 'SELECT *'
            . '  FROM Comments'
            . ' WHERE parent_name = "' . $this->_name . '"'
            . '   AND parent_id = ' . $id
            . ' ORDER BY updated_at DESC'
            ;
     return $this->getAdapter()->fetchAll( $sql );
}

public function getTags( $id ) {
     if(  $id == null )    return null;
/*
     $sql   = 'SELECT Tags.*, Controls.control_name'
            . '  FROM Tags, Controls'
            . ' WHERE Tags.parent_name = "' . $this->_name . '"'
            . '   AND Tags.parent_id = ' . $id
            . '   AND Tags.tag_id = Controls.id'
            . ' ORDER BY updated_at DESC'
            ;
*/
     $sql   = 'SELECT Tags.*'
            . '  FROM Tags'
            . ' WHERE Tags.parent_name = "' . $this->_name . '"'
            . '   AND Tags.parent_id = ' . $id
            . ' ORDER BY name'
            ;
     return $this->getAdapter()->fetchAll( $sql );
}

public function getNotes( $id ) {
     $sql   = 'SELECT *'
            . '  FROM Notes'
            . ' WHERE status = "A"'
            . '   AND parent_name = "' . $this->_name . '"'
            . '   AND parent_id = ' . $id
            . ' ORDER BY updated_at DESC'
            ;
     return $this->getAdapter()->fetchAll( $sql );
}

public function addHistory( $parent_name, $parent_id, $comment ) {
     $data = array();
     $data[ 'parent_name'] = $parent_name;
     $data[ 'parent_id'  ] = $parent_id;
     $data[ 'comment'    ] = substr( $comment, 2 );
     $Comments = new Comments();
     $Comments->insert( $data );
}

public function getHistory( $parent_name, $parent_id ) {
     $sql   = 'SELECT *'
            . '  FROM Comments'
            . ' WHERE parent_name = "' . $parent_name . '"'
            . '   AND parent_id = ' . $parent_id
            . ' ORDER BY updated_at DESC'
            ;
     return $this->getAdapter()->fetchAll( $sql );
}

public function matchId( $id ) {
     $sql = 'SELECT id'
          . '  FROM ' . $this->_name
          . ' WHERE id = ' . $id
          ;
     $result = $this->getAdapter()->fetchOne( $sql );
     return $result ? true : false;
}

public function matchName( $name, $id='' ) {
     $sql = 'SELECT id'
          . '  FROM ' . $this->_name
          . ' WHERE name = "' . $name . '"'
          ;
     $result = $this->getAdapter()->fetchOne( $sql );

     if( !$result   )    return false;
     if(  '' == $id )    return true ;
     return $result != $id ? true : false;
}

public function increment( $id, $field_name ) {
     $sql = 'UPDATE ' . $this->_name
          . '   SET ' . $field_name . ' = ' . $field_name . ' + 1'
          . ' WHERE id = ' . $id
          ;
     $this->getAdapter()->query( $sql );
}

//   Meta DB functions ---------------------------------------------------------

public function meta_replace( $parent_id, $meta_name, $meta_value ) {
     $id = $this->meta_get_id( $parent_id, $meta_name );
     if( !$id )
          $this->meta_insert( $parent_id, $meta_name, $meta_value );
     else $this->meta_update( $id, $meta_value );
}

public function meta_insert( $parent_id, $meta_name, $meta_value ) {
     $sql = 'INSERT ' . $this->_name . '_Meta'
          . '   SET parent_id  =  ' . $parent_id
          . '     , meta_name  = "' . $meta_name  . '"'
          . '     , meta_value = "' . $meta_value . '"'
          ;
     $this->getAdapter()->query( $sql );
     $id = $this->getAdapter()->lastInsertId();
     log_sql( $this->_name . '_Meta', $id, 'inserted, sql=' . $sql );
     return $id;
}

public function meta_update( $id, $meta_value ) {
     $sql = 'UPDATE ' . $this->_name . '_Meta'
          . '   SET meta_value = "' . $meta_value . '"'
          . ' WHERE id  =  ' . $id
          ;
     $this->getAdapter()->query( $sql );
     log_sql( $this->_name . '_Meta', $id, 'updated, sql=' . $sql );
}

public function meta_delete( $parent_id, $meta_name ) {
     $id = $this->meta_get_id( $parent_id, $meta_name );
     if( !$id )     return;

     $sql = 'DELETE FROM ' . $this->_name . '_Meta'
          . ' WHERE id  =  ' . $id
          ;
     $this->getAdapter()->query( $sql );
     log_sql( $this->_name . '_Meta', $id, 'deleted, sql=' . $sql );
}

public function meta_empty( $parent_id ) {
     $sql = 'DELETE FROM ' . $this->_name . '_Meta'
          . ' WHERE parent_id  =  ' . $parent_id
          ;
     $this->getAdapter()->query( $sql );
}

public function meta_get_id( $parent_id, $meta_name ) {
     $sql = 'SELECT id'
          . '  FROM ' . $this->_name . '_Meta'
          . ' WHERE parent_id = ' . $parent_id
          . '   AND meta_name = "' . $meta_name . '"'
          ;
     return $this->getAdapter()->fetchOne( $sql );
}

public function meta_get_value( $parent_id, $meta_name ) {
     $sql = 'SELECT meta_value'
          . '  FROM ' . $this->_name . '_Meta'
          . ' WHERE parent_id = ' . $parent_id
          . '   AND meta_name = "' . $meta_name . '"'
          ;
     return $this->getAdapter()->fetchOne( $sql );
}

}
?>