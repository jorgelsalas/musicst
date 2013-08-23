<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['camposConcatenadosAlbum'] = 'id_artista,id_album,nombre_album,fecha_publicacion,genero_album,rating,cantidad_votos,cantidad_downloads';
$config['nombreCamposBDAlbum'] = array(
  		'id_artista' => 'id_artista',
	    'id_album' => 'id_album',
		'nombre_album' => 'nombre_album',
		'fecha_publicacion' => 'fecha_publicacion',
		'genero_album' => 'genero_album',
		'rating' => 'rating',
		'cantidad_votos' => 'cantidad_votos',
		'cantidad_downloads' => 'cantidad_downloads' 
	);
	
$config['camposConcatenadosArtista'] = 'id_artista,nombre_artista,genero_artista,cantidad_downloads';
$config['nombreCamposBDArtista'] = array(
	    'id_artista'=>'id_artista',
	    'nombre_artista'=>'nombre_artista',
	    'genero_artista'=>'genero_artista',
	    'cantidad_downloads'=>'cantidad_downloads'
	);
	
$config['camposConcatenadosCancion'] = 'id_album,id_cancion,nombre_cancion,numero_track,duracion,cantidad_downloads';
$config['nombreCamposBDCancion'] = array(
	    'id_album' => 'id_album',
		'id_cancion' => 'id_cancion',
		'nombre_cancion' => 'nombre_cancion',
		'numero_track' => 'numero_track',
		'duracion' => 'duracion',
		'cantidad_downloads' => 'cantidad_downloads'
	);


$config['camposConcatenadosFavoritos'] = 'id_artista,id_usuario';
$config['nombreCamposBDFavoritos'] = array(
	    'id_artista'=>'id_artista',
	    'id_usuario'=>'id_usuario'
	);


$config['camposConcatenadosRatingAlbum'] = 'id_album,id_usuario,rating';
$config['nombreCamposBDRatingAlbum'] = array(
		'id_album' => 'id_album',
	    'id_usuario' => 'id_usuario',
		'rating' => 'rating'
	);


$config['camposConcatenadosUsuario'] = 'id_usuario,nombre,apellido1,apellido2,pais,username,password,id_perfil';
$config['nombreCamposBDUsuario'] = array(
       'id_usuario'=>'id_usuario',
       'nombre_usuario'=>'nombre',
       'apellido1_usuario'=>'apellido1',
	   'apellido2_usuario'=>'apellido2',
	   'pais'=>'pais',
	   'username'=>'username',
       'password'=>'password',
	   'id_perfil'=>'id_perfil'
	);

/* End of file custom.php */
/* Location: ./application/config/custom.php */
