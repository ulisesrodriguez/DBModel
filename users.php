<?php
// Include Files
require 'db.php';
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
  users.php
  
  DESCRIPTION:
  Class User Testing DBModel
  
  DEPENDENCIAS: 
  db.php
====================================================================================== */

/*
======================================================================================
 Class Users
====================================================================================== */
class Users extends Db{

// Atributes

// Table 
  private $table = 'tbl_usuario';

// Save return data 
  private $data = array();
  
   
// Methods  
  
// Construct Clena Vars 
  public function __construct(){ 
  	
// Clean data		
		unset( $this->data ); $this->data = array(); parent::__construct();
		
  }
  
  public function create( $data = array() ){
	 
	// Validation empty
	if( empty( $data ) ) return false;
	
	
	if( $this->insert( $this->table, $data ) == true )
		return true;
	else
		return false;	
	
	 
  }
  
  public function editar(  $data = array(), $conditions ){
	 
	// Validation empty
	if( empty( $data ) or empty( $conditions ) ) return false;
	
	if( $this->update( $this->table, $data, $conditions ) == true )
		return true;
	else
		return false;	
	
	 
  }
  
   public function eliminar( $data = array() ){
	 
	// Validation empty
	if( empty( $data ) ) return false;
	
	if( $this->delete( $this->table, $data ) == true )
		return true;
	else
		return false;	
	
	 
  }
 
  public function id( $id = null ){
  	
	// Validation empty
	if( empty( $id ) or !is_numeric( $id ) ) return false;
	
	$this->select(); $this->from( $this->table ); $this->where( array( 'id_usuario' => $id ) );
	
	return $this->get();
  
  }
  
  public function all(){
  		
	$this->select(); $this->from( $this->table );
	
	return $this->get();
  
  }
  
  
}
?>