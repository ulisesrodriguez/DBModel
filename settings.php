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
  settings.php
  
  DESCRIPTION:
  Setting conection to database

====================================================================================== */

/*
======================================================================================
 Class Settings
====================================================================================== */

class Settings{
	
 /**
  | @var name
  | @access -> private
  | @default: Settings
  **/	
  private $name = 'Settings';

 /**
  | @var server
  | @access -> private
  | @default: localhost
  **/	
  private $server = 'localhost';

 /**
  | @var user
  | @access -> private
  | @default: root
  **/	
  private $user = 'root';

 /**
  | @var password
  | @access -> private
  | @default: 
  **/	
  private $password = '';

 /**
  | @var database
  | @access -> private
  | @default: 
  **/	
  private $database = '';

 /**
  | @var con
  | @access -> private
  | @default: null
  **/	
  private $con = null;	
  
// Construct Setting null conection
  public function __construct(){
  		
  	  $this->con = null;	
  	    	    		
  }	

 /**
  | @function con
  | @access -> public
  | @return $con
  **/		
  public function con(){
  	  	
		$this->con = mysql_connect( $this->server, $this->user, $this->password ); 		
		mysql_select_db( $this->database, $this->con );	 		
 		
		if( !$this->con ){ echo '<h1 class="error">Can\'t connect database</h1>'; exit; }
		
 		if( $this -> con ){
 			return $this -> con;
 		}
				 
	 
  	
  }	
  
}
?>