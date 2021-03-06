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
  CLEAN ANTI XSS
====================================================================================== */
 public function smart_quotes( $value = null ){ 
    
	if( empty( $value ) ) return false;

	 // Clean slashes
	if ( get_magic_quotes_gpc() )
        return stripslashes( $value );
   
    if ( !is_numeric( $value ) ) 
        return mysql_real_escape_string( $value, $this->conection->con() );
    
	return $value;
 }


/*
======================================================================================
  Create Record	
====================================================================================== */
  public function insert( $table = null, $data = array() ){
	
	// Validate Table
	if( empty( $table ) ) return false;
			
	// Validation empty
	if( empty( $data ) ) return false;
	
	// Dynamic query
	$sql =	"INSERT INTO `". $table ."`( "; 
		
		foreach( $data as $key => $value )			
			$sql .= "`". strip_tags( $key ) ."`, ";	
	
					
	$sql .= ') VALUES ( ';	
	
		foreach( $data as $value )
				$sql .= " '". $this->smart_quotes( strip_tags( $value )  )."', ";	
		
	$sql .= ');';	
	
	$sql = str_replace( ', )', ' )', $sql );	
		
	$res = mysql_query( $sql, $this->conection->con() );
		
	// Setting insert_id
	$this->insert_id = mysql_insert_id();
		
	if( mysql_affected_rows() > 0 )
		return true;
	else
		return false;
				
  }



/*
======================================================================================
  Update Record	
====================================================================================== */
  public function update( $table = null, $data = array(), $conditions = array() ){
	  
	 // Validate Table
	if( empty( $table ) ) return false;
			
	// Validation empty
	if( empty( $data ) ) return false;
	
	// Validation empty
	if( empty( $conditions ) )  return false;
	
	 
	// Dynamic Query 
	$sql =	"UPDATE `". $table ."` 
			   SET";
	
	foreach( $data as $key => $value )							
		$sql .= ", `". $this->smart_quotes( strip_tags( $key ) ) ."`='". $this->smart_quotes( strip_tags( $value ) ) ."'" ;
	
	
	$condition = ' WHERE ';	
	
	foreach( $conditions as $key => $value ){
				
		if( $condition == ' WHERE ' ){
			$sql .= " WHERE `". $this->smart_quotes( strip_tags( $key ) ) ."`='". $this->smart_quotes( strip_tags( $value ) ) ."' ";
			$condition = ' AND ';
		
		}else if( $condition == ' AND ' )
			$sql .= " AND `". $this->smart_quotes( strip_tags( $key ) ) ."`='". $this->smart_quotes( strip_tags( $value ) ) ."' ";		
				
	}		
	
	
	
	$count = strlen( 'UPDATE `'. $table  .'` SET,        ');

	$sql = substr_replace( $sql, 'UPDATE `'. $table .'` SET',0, $count );
	
	$sql .= '; ';
				
	$res = mysql_query( $sql, $this->conection->con() );
			
	if( mysql_affected_rows() > 0 )
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
	if( empty( $table ) ) return false;
	
	// Validation empty
	if( empty( $data ) )  return false; 
	
	
	// Dynamic query 
	$sql = ' DELETE FROM `'.$table.'` ';
	 
	$condition = ' WHERE ';	
	
	foreach( $data as $key => $value ){
					
		if( $condition == ' WHERE ' ){
			$sql .= " WHERE `". $this->smart_quotes( strip_tags( $key ) ) ."`='". $this->smart_quotes( strip_tags( $value ) ) ."' ";
			$condition = ' AND ';
			
		}else if( $condition == ' AND ' )
			$sql .= " AND `". $this->smart_quotes( strip_tags( $key ) ) ."`='". $this->smart_quotes( strip_tags( $value ) ) ."' ";		
					
	}		
	 
	 $sql .= ';';
	 
	 $res = mysql_query( $sql, $this->conection->con() );
	 
	 if( mysql_affected_rows() > 0 )
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
	 
	 $this->query = ' SELECT  ' . $this->smart_quotes( $str );
	
	 
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
	if( empty( $table ) ) return false;
		 
	$this->query .= ' FROM  ' . $this->smart_quotes( $table );
	
  }

/*  
===========================  
  PRODUCES 
  WHERE key = value
  AND key = value
=========================== */  
  public function where( $conditions = array() ){
  	
	// Validate Table
	if( empty( $conditions ) ) return false;
  	
	foreach( $conditions as $key => $value ){
		
		if( !strpos( $this->query, ' WHERE ' )  )
		
			$this->query .= " WHERE ". $this->smart_quotes( strip_tags( $key ) ) ."'". $this->smart_quotes( strip_tags( $value ) ) ."' ";
		
		else
		
			$this->query .= " AND ". $this->smart_quotes( strip_tags( $key ) ) ."'". $this->smart_quotes( strip_tags( $value ) ) ."' ";
			
	  }	
		
  }
 

/*  
===========================  
  PRODUCES 
  WHERE calle LIKE '%shflaf%'
  AND calle LIKE '%shflaf%'
=========================== */  
  public function where_like( $conditions = array() ){
  	
	// Validate values
	if( empty( $conditions ) ) return false;
  	
	foreach( $conditions as $key => $value ){
		
		if( !strpos( $this->query, ' WHERE ' )  )
			
			$this->query .= " WHERE ". $this->smart_quotes( strip_tags( $key ) ) ." LIKE '%". $this->smart_quotes( strip_tags( $value ) ) ."%' ";
						
		else
			$this->query .= " AND ". $this->smart_quotes( strip_tags( $key ) ) ." LIKE '%". $this->smart_quotes( strip_tags( $value ) ) ."%' ";
			
	}	
		
  }  

/*  
===========================  
  PRODUCES 
  OR key = value
=========================== */  
  public function or_where( $field = null, $value = null ){
  	
	// Validate Table
	if( empty( $field ) ) return false;
	
	if( empty( $value ) ) return false;
  	
	 $this->query .=  ' OR ' . $this->smart_quotes( strip_tags( $field ) ) ."'". $this->smart_quotes( strip_tags( $value ) ) ."' ";
			
  }


/*  
===========================  
  PRODUCES 
  AND condition BETWEEN 'value1' AND 'value2'
=========================== */  
//AND DATE(fecha) BETWEEN '2005-07-16' AND '2005-07-16'
 public function between( $condition = ' AND ', $field = null, $value = null, $value2 = null ){
  	
	// Validate Table
	if( empty( $field ) ) return false;
	
	if( empty( $value ) ) return false;
	
	if( empty( $value2 ) ) return false;
  	
	
	 $this->query .= $this->smart_quotes( strip_tags( $condition ) ) .' ' . $this->smart_quotes( strip_tags( $field ) ) . ' ' ."'". $this->smart_quotes( strip_tags( $value ) ) ."' AND " ."'" . $this->smart_quotes( strip_tags( $value2 ) ) ."'";
			
  }	


/*  
=====================================  
  PRODUCES 
  JOIN LEFT table ON conditions
===================================== */ 
  public function join( $table = null, $conditions = null ){
 
 	// Validate Table
	if( empty( $table ) )  return false;
	
	// Validate Conditions
	if( empty( $conditions ) ) return false;
	
	$this->query .= ' JOIN `'. $this->smart_quotes( strip_tags( $table ) ) .'` ON '. $this->smart_quotes( strip_tags( $conditions ) ) .' ' ;	
    	
  }

/*  
=====================================  
  PRODUCES 
  LIMIT begin
  OR
  LIMIT begin, end
===================================== */   
  public function limit( $begin = 0, $end = null ){
  	 
	 if( empty( $end ) )	
  	 	$this->query .= ' LIMIT '. $this->smart_quotes( strip_tags( $begin ) );
		
	 else
	  	$this->query .= ' LIMIT '. strip_tags( $begin ) . ' , '. $this->smart_quotes( strip_tags( $end ) );	
	
  	
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
	if( empty( $field ) )  return false;
	
	$this->query .= ' ORDER BY `'. $this->smart_quotes( strip_tags( $field ) ) .'` '. $this->smart_quotes( strip_tags( $condition ) ) .' ' ;	
    	
  }
  
/*  
===========================  
  RETURN SET CURREN QUERY
=========================== */  
  public function getQuery(){
  	
	 return $this->query;
  
  }

/*
======================================================================================
  GETTING QUERYS
====================================================================================== */ 

// RUNING A SIMPLE QUERY 
   public function querys( $query = null ){
	 
	// Validate Table
	if( empty( $query ) )  return false;
	
	// Clean /*$this->smart_quotes( $query )*/
	$res = mysql_query(  $query/*$this->smart_quotes( $query )*/, $this->conection->con() );
			
	if( mysql_num_rows( $res ) == 0 ) return false;
	
	unset( $this->data ); $this->data = array();	
	 
	while( $reg = mysql_fetch_assoc( $res ) )	 				
			$this->data[] = $reg;	
			
	return $this->data;
	 
  }



// RUNING A DINAMIC QUERY 
  public function get( $table = null ){
		 
	if( !empty( $table ) and empty( $this->query ) ){
		
		$res = mysql_query( $this->select()->from( $this->smart_quotes( $table ) ), $this->conection->con() );
		
		if( mysql_num_rows( $res ) == 0 ) return false;
		
		unset( $this->data ); $this->data = array();		
		 
		while( $reg = mysql_fetch_assoc( $res ) )	 				
				$this->data[] = $reg;	
				
		return $this->data;
		
		
	}
	
	$res = mysql_query( $this->query, $this->conection->con() );
			
	if( mysql_num_rows( $res ) == 0 ) return false;
	
	unset( $this->data ); $this->data = array();
	
	while( $reg = mysql_fetch_assoc( $res ) )	 				
			$this->data[] = $reg;	
	
	$this->query = null;
				
	return $this->data;
	 
  }
      
}
?>