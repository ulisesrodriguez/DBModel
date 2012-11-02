<?php
// Include Files
require 'settings.php';
require 'db.php';
require 'users.php';	

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
  index.php
  
  DESCRIPTION:
  Testing Generic DBModel
  
  DEPENDENCIAS: 
  users.php
====================================================================================== */

$user = new Users();	

/*
	
	CREATE TABLE `tbl_usuario` (
	  `id_usuario` bigint(20) NOT NULL AUTO_INCREMENT,
	  `nombre` varchar(255) NOT NULL,
	  `paterno` varchar(255) NOT NULL,
	  `clave` varchar(12) NOT NULL,
	  `mail_admin` varchar(255) NOT NULL,
	  `estado_usuario` int(11) NOT NULL,
	  `perfil` int(11) NOT NULL,
	  PRIMARY KEY (`id_usuario`)
	) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

	
*/

// Create Record
$record = array(
	
	'nombre' => 'Nuevo',
	'paterno' => 'Registro',
	'clave' => md5( '12345' ),
	'mail_admin' => 'mail@mail.com',
	'estado_usuario' => 1,
	'perfil' => 1

);

if( $user->create( $record ) == true ) echo '<br>Registro Creado'; else echo '<br> No se puede crear el registro';

/*
// Update Record
$record = array(
	
	'nombre' => 'New Updated',
	'paterno' => 'Record updated',
	'clave' => md5( '123456' ),

);

if( $user->editar( $record, array( 'perfil' => 1, 'id_usuario' => 1 ) ) == true ) echo '<br>Registro Actualizado'; else echo '<br> No se puede actualizar el registro';
*/


// Delete Record
//if( $user->eliminar( array( 'id_usuario' => 1 ) ) == true ) echo '<br>Registro Eliminado'; else echo '<br> No se puede eliminar el registro';


$info = $user->id( 1 );

print_r( $info );

echo '<br><br><br><br><br><br>';

$info = $user->all();


print_r( $info );
?>