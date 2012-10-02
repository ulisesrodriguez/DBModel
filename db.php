<?php
/*
======================================================================================

  Author		Ulises Rodríguez
  Site:			http://www.ulisesrodriguez.com	
  Twitter:		https://twitter.com/#!/isc_ulises
  Facebook:		http://www.facebook.com/ISC.Ulises
  Github:		https://github.com/ulisesrodriguez
  Email:		ing.ulisesrodriguez@gmail.com
  Skype:		systemonlinesoftware
  Location:		Guadalajara Jalisco Mexíco
  
  FILE
  db.php
  
  DESCRIPTION:
  Generic file to runing querys
  
  DEPENDENCIAS: 
  settings.php
====================================================================================== */


// Include Files
require 'settings.php';

/*
======================================================================================
 Class DB
====================================================================================== */

class Db{
	
/*
======================================================================================
  ATRIBUTES	
====================================================================================== */
// privetae query 
  private $query = null; 

// private data 
  private $data = array();
  
// private conection
  private $conection = null;

// private insert_id 
  private $insert_id = null;	
  
  
/*
======================================================================================
  CLEAN DATA AND OPEN CONECTION	
====================================================================================== */
  public function __construct(){ unset( $this->data ); $this->data = array(); $this->conection = new Settings(); }

/*
======================================================================================
  Create Record	
====================================================================================== */
  public function insert( $table = null, $data = array() ){
	
	// Validate Table
	if( empty( $table ) ){ echo '<h1 class="error">Empty Table</h1>'; return false; }
			
	// Validation empty
	if( empty( $data ) ){ echo '<h1 class="error">Empty Data</h1>'; return false; }
	
	// Dynamic query
	$sql =	"INSERT INTO `". $table ."`( "; 
		
		foreach( $data as $key => $value )			
			$sql .= "". strip_tags( $key ) .", ";	
				
	$sql .= ') VALUES (null';	
	
	$sql = str_replace( ',)', ' )', $sql );	
		 
		foreach( $data as $value )
				$sql .= ",". strip_tags( $value ) ."";	
		
	$sql .= ');';	
	
	$res = mysql_query( $sql, $this->conection->con() );
	
	// Setting insert_id
	$this->insert_id = mysql_insert_id( $res );
		
	if( mysql_num_rows( $res ) > 0 )
		return true;
	else
		return false;
				
  }



/*
======================================================================================
  Update Record	
====================================================================================== */
  public function update( $table = null, $data = array(), $id = null ){
	  
	 // Validate Table
	if( empty( $table ) ){ echo '<h1 class="error">Empty Table</h1>'; return false; }
			
	// Validation empty
	if( empty( $data ) ){ echo '<h1 class="error">Empty Data</h1>'; return false; }
	
	// Validation empty
	if( empty( $id ) or !is_numeric( $id ) ){ echo '<h1 class="error">ID Invalido</h1>'; return false; }
	
	 
	// Dynamic Query 
	$sql =	"UPDATE `". $table ."` 
			 SET";
				
	foreach( $data as $key => $value )							
		$sql .= ", `". strip_tags( $key ) ."`='". strip_tags( $value ) ."'" ;
	
	$sql .= " WHERE `id`=". strip_tags( $id ) .';';
	
	$count = strlen( 'UPDATE `'. $table  .'` SET,      ');

	$sql = substr_replace( $sql, 'UPDATE `'. $table .'` SET',0, $count );
	
	if( mysql_num_rows( $res ) > 0 )
		return true;
	else
		return false;
	 
	 
  }
  
/*
======================================================================================
  Delete Record	
====================================================================================== */
  public function delete( $table = null, $data = null ){
	 
	  // Validate Table
	if( empty( $table ) ){ echo '<h1 class="error">Empty Table</h1>'; return false; }
	
	// Validation empty
	if( empty( $data ) ){ echo '<h1 class="error">Empty fields conditions</h1>'; return false; }
	
	
	// Dynamic query 
	 $sql = ' DELETE FROM `'.$table.'` ';
	 
	 foreach( $data as $key => $value ){
		
		$condition = ' WHERE ';	
		
		if( $condition == ' WHERE ' ){
			$sql .= " WHERE `". strip_tags( $key ) ."`='". strip_tags( $value ) ."' ";
			$condition = ' AND ';
		}else
			$sql .= " AND `". strip_tags( $key ) ."`='". strip_tags( $value ) ."' ";		
				
	  }
	 
	 $sql .= ';';
	 
	 if( mysql_num_rows( $res ) > 0 )
		return true;
	else
		return false; 	  
  
  }


/*
======================================================================================
  SETTING QUERYS
====================================================================================== */


/*  
===========================  
  PRODUCES 
  SELECT *
  OR
  SELECT field1, field2
=========================== */  
  public function select( $str = null ){
	 
	 if( empty( $str ) ){ $this->query = ' SELECT * '; return $this->query; }
	 
	 $this->query = '';
	 
	 $this->query = ' SELECT  ' . $str;
	
	return $this->query;	 
	 
  }

/*  
===========================  
  PRODUCES 
  FROM table 
  OR
  FROM table1, table2
=========================== */    
  public function from( $table = null ){
	 
	// Validate Table
	if( empty( $table ) ){ echo '<h1 class="error">Empty Table</h1>'; return false; }
		 
	$this->query .= ' FROM  ' . $table;
	
	return $this->query;	 
	 
  }

/*  
===========================  
  PRODUCES 
  WHERE key = value
  AND key = value
=========================== */  
  public function where( $conditions = array() ){
  	
	// Validate Table
	if( empty( $conditions ) ){ echo '<h1 class="error">Empty conditions </h1>'; return false; }
  	
	foreach( $conditions as $key => $value ){
		
		$condition = ' WHERE ';	
		
		if( $condition == ' WHERE ' ){
			$this->query .= " WHERE `". strip_tags( $key ) ."`='". strip_tags( $value ) ."' ";
			$condition = ' AND ';
		}else
			$this->query .= " AND `". strip_tags( $key ) ."`='". strip_tags( $value ) ."' ";		
				
	  }	
	
	 return $this->query;
	
  }

/*  
=====================================  
  PRODUCES 
  JOIN LEFT table ON conditions
===================================== */ 
  public function join( $table = null, $conditions = null ){
 
 	// Validate Table
	if( empty( $tables ) ){ echo '<h1 class="error">Empty tables</h1>'; return false; }
	
	// Validate Conditions
	if( empty( $conditions ) ){ echo '<h1 class="error">Empty conditions</h1>'; return false; }
	
	$this->query .= ' JOIN LEFT `'. strip_tags( $table ) .'` ON '. strip_tags( $conditions ) .' ' ;	
    
	return $this->query;
	
  }

/*  
=====================================  
  PRODUCES 
  LIMIT begin, end
===================================== */   
  public function limit( $begin = 0, $end = 10 ){
  
  	 $this->query .= ' LIMIT '. strip_tags( $begin ) . ' , '. strip_tags( $end ) ;
  	 
	 return $this->query;
	
  }

/*  
===========================  
  PRODUCES 
  ORDER BY field desc
  OR
  ORDER BY field asc
=========================== */ 
  public function order_by( $field = null, $condition = 'asc' ){
 
 	// Validate Table
	if( empty( $field ) ){ echo '<h1 class="error">Empty field</h1>'; return false; }
	
	$this->query .= ' ORDER BY `'. strip_tags( $field ) .'` '. strip_tags( $condition ) .' ' ;	
    
	return $this->query;
	
  }
 

/*
======================================================================================
  GETTING QUERYS
====================================================================================== */ 

// RUNING A SIMPLE QUERY 
   public function querys( $query = null ){
	 
	// Validate Table
	if( empty( $tables ) ){ echo '<h1 class="error">Empty query</h1>'; return false; }
	
	$res = mysql_query( $query, $this->conection->con() );
	
	if( mysql_num_rows( $res ) == 0 ) return false;
	 
	while( $reg = mysql_fetch_assoc( $res ) )	 				
			$this->data[] = $reg;	
			
	return $this->data;
	 
  }



// RUNING A DINAMIC QUERY 
  public function get( $table = null ){
	 
	 // Validate Table
	if( empty( $tables ) ){ echo '<h1 class="error">Empty table</h1>'; return false; }
	
	if( empty( $this->query ) ){ echo '<h1 class="error">Empty query</h1>'; return false;  }
	
	if( !empty( $table ) ){
		
		$res = mysql_query( $this->select()->from( $table ), $this->conection->con() );
		
		if( mysql_num_rows( $res ) == 0 ) return false;
		 
		while( $reg = mysql_fetch_assoc( $res ) )	 				
				$this->data[] = $reg;	
				
		return $this->data;
		
		
	}
	
	$res = mysql_query( $this->query, $this->conection->con() );
		
	if( mysql_num_rows( $res ) == 0 ) return false;
		 
	while( $reg = mysql_fetch_assoc( $res ) )	 				
			$this->data[] = $reg;	
				
	return $this->data;
	 
  }
      
}
?>