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
			$sql .= "`". strip_tags( $key ) ."`, ";	
	
					
	$sql .= ') VALUES ( ';	
	
		foreach( $data as $value )
				$sql .= " '". strip_tags( $value ) ."', ";	
		
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
	if( empty( $table ) ){ echo '<h1 class="error">Empty Table</h1>'; return false; }
			
	// Validation empty
	if( empty( $data ) ){ echo '<h1 class="error">Empty Data</h1>'; return false; }
	
	// Validation empty
	if( empty( $conditions ) ){ echo '<h1 class="error">Conditions Invalids</h1>'; return false; }
	
	 
	// Dynamic Query 
	$sql =	"UPDATE `". $table ."` 
			 SET";
	
	foreach( $data as $key => $value )							
		$sql .= ", `". strip_tags( $key ) ."`='". strip_tags( $value ) ."'" ;
	
	
	$condition = ' WHERE ';	
	
	foreach( $conditions as $key => $value ){
				
		if( $condition == ' WHERE ' ){
			$sql .= " WHERE `". strip_tags( $key ) ."`='". strip_tags( $value ) ."' ";
			$condition = ' AND ';
		
		}else if( $condition == ' AND ' )
			$sql .= " AND `". strip_tags( $key ) ."`='". strip_tags( $value ) ."' ";		
				
	}		
	
	
	
	$count = strlen( 'UPDATE `'. $table  .'` SET,      ');

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
	if( empty( $table ) ){ echo '<h1 class="error">Empty Table</h1>'; return false; }
	
	// Validation empty
	if( empty( $data ) ){ echo '<h1 class="error">Empty fields conditions</h1>'; return false; }
	
	
	// Dynamic query 
	$sql = ' DELETE FROM `'.$table.'` ';
	 
	$condition = ' WHERE ';	
	
	foreach( $data as $key => $value ){
					
		if( $condition == ' WHERE ' ){
			$sql .= " WHERE `". strip_tags( $key ) ."`='". strip_tags( $value ) ."' ";
			$condition = ' AND ';
			
		}else if( $condition == ' AND ' )
			$sql .= " AND `". strip_tags( $key ) ."`='". strip_tags( $value ) ."' ";		
					
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
	 
	 $this->query = ' SELECT  ' . $str;
	
	 
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
		
		if( !strpos( $this->query, ' WHERE ' )  ){
			$this->query .= " WHERE ". strip_tags( $key ) ."'". strip_tags( $value ) ."' ";
		}else
			$this->query .= " AND ". strip_tags( $key ) ."'". strip_tags( $value ) ."' ";
			
	  }	
		
  }

/*  
===========================  
  PRODUCES 
  OR key = value
=========================== */  
  public function or_where( $field = null, $value = null ){
  	
	// Validate Table
	if( empty( $field ) ){ echo '<h1 class="error">Empty Field </h1>'; return false; }
	
	if( empty( $value ) ){ echo '<h1 class="error">Empty Value </h1>'; return false; }
  	
	 $this->query .=  ' OR ' . strip_tags( $field ) ."'". strip_tags( $value ) ."' ";
			
  }

/*  
=====================================  
  PRODUCES 
  JOIN LEFT table ON conditions
===================================== */ 
  public function join( $table = null, $conditions = null ){
 
 	// Validate Table
	if( empty( $table ) ){ echo '<h1 class="error">Empty tables</h1>'; return false; }
	
	// Validate Conditions
	if( empty( $conditions ) ){ echo '<h1 class="error">Empty conditions</h1>'; return false; }
	
	$this->query .= ' JOIN `'. strip_tags( $table ) .'` ON '. strip_tags( $conditions ) .' ' ;	
    	
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
  	 	$this->query .= ' LIMIT '. strip_tags( $begin ) ;
	 else
	  	$this->query .= ' LIMIT '. strip_tags( $begin ) . ' , '. strip_tags( $end ) ;	
		
  	 	
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
    	
  }
  
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
	if( empty( $query ) ){ echo '<h1 class="error">Empty query</h1>'; return false; }
	
	$res = mysql_query( $query, $this->conection->con() );
	
	if( mysql_num_rows( $res ) == 0 ) return false;
	 
	while( $reg = mysql_fetch_assoc( $res ) )	 				
			$this->data[] = $reg;	
			
	return $this->data;
	 
  }



// RUNING A DINAMIC QUERY 
  public function get( $table = null ){
		 
	if( !empty( $table ) and empty( $this->query ) ){
		
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
	
	$this->query = null;
				
	return $this->data;
	 
  }
      
}
?>