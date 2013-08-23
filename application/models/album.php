<?php


Class album extends CI_Model
{
  
  	var  $camposConcatenados = 'id_artista, id_album, nombre_album, fecha_publicacion, genero_album, rating, cantidad_votos, cantidad_downloads'; //TODO: Ojo campos default (no vienen llenos), por ejemplo rating.
  	var  $nombreCamposBD = array(
  		'id_artista' => 'id_artista',
	    'id_album' => 'id_album',
		'nombre_album' => 'nombre_album',
		'fecha_publicacion' => 'fecha_publicacion',
		'genero_album' => 'genero_album',
		'rating' => 'rating',
		'cantidad_votos' => 'cantidad_votos',
		'cantidad_downloads' => 'cantidad_downloads'
	);
	public $nombreTabla = 'album';
  
	function __construct() //TODO: Test
	{
	    parent::__construct();
	}
  
  
	function getAllAlbums(){ //TODO: Test
  		$this->db->select($this->$camposConcatenados);	
    	$this->db->from($this->$nombreTabla);
		$this->db->orderBy($this->$nombreCamposBD['fecha_publicacion'], 'desc'); //TODO: Revisar syntax.
    	$albums = $this->db->get()->result();
    	return $albums->result();
  	}

  	function getAlbum($idAlbum){ //TODO: Test
		
		$query = $this->db->query(
			"SELECT * FROM album WHERE id_album = ? ", $idAlbum
		); 
		return $query->result_array();

  	}

	
	function getAlbumPorNombre($nombreAlbum){ //TODO: Test
	
  		$this->db->select($this->camposConcatenados);	
    	$this->db->from($this->nombreTabla);
		$this->db->like($this->nombreCamposBD['nombre_album'],$nombreAlbum,'after');
		$this->db->order_by($this->nombreCamposBD['nombre_album'],'asc');
		//$this->db->where($this->nombreCamposBD['nombre_album'], $nombreAlbum);
		
	    $album = $this->db->get()->result();
		
   		return $album;
		
  	}
	
	function getAlbumsDeArtista($idArtista){ //TODO: Test
  		$query = $this->db->query(
			"SELECT * FROM album WHERE id_artista = ?", $idArtista
		);
	  	return $query->result_array();
	}
	
  	function getAlbumDeArtista($idArtista, $idAlbum){ //TODO: Test
	
  		$this->db->select($this->camposConcatenados);	
    	$this->db->from($this->nombreTabla);
		$this->db->where($this->nombreCamposBD['id_album'], $idAlbum);
		$this->db->where($this->nombreCamposBD['id_artista'], $idArtista);
	    $this->db->limit(1);
		
   		$query = $this->db->get();
    
	    if($query -> num_rows() == 1)
	    {
	      return $query->result_array();
	    }
		else{
			return false;
		}
  	}


	// Devuelve [idArtista, nombreArtista, columnasDeAlbum]
	function getAlbumsConNombreSimilarA($nombreAlbum){ //TODO: Test
		$this->load->model('artista', '', TRUE);
		
		$nombreAlbum = $nombreAlbum . '%';
		
		$query = $this->db->query( 
			'SELECT Q_ALBUM.id_artista, Q_ARTISTA.nombre_artista, Q_ALBUM.id_album , Q_ALBUM.nombre_album, Q_ALBUM.fecha_publicacion, 
		     Q_ALBUM.genero_album, Q_ALBUM.rating, Q_ALBUM.cantidad_downloads
			 FROM album AS Q_ALBUM JOIN artista AS Q_ARTISTA ON Q_ARTISTA.id_artista = Q_ALBUM.id_artista
			 WHERE nombre_album LIKE ?
			 ORDER BY Q_ARTISTA.nombre_artista, Q_ALBUM.fecha_publicacion', $nombreAlbum    
		);
		return $query->result_array();
	}
	
	//INSERT
	
	function insertarAlbum($idArtista, $nombre,$fechaPublicacion, $genero){ //TODO: Test
		$data = array(
			$this->nombreCamposBD['id_artista'] => $idArtista,
			$this->nombreCamposBD['nombre_album'] => $nombre,
			$this->nombreCamposBD['fecha_publicacion'] => $fechaPublicacion,
			$this->nombreCamposBD['genero_album'] => $genero
		); 
		if($this->db->insert($this->nombreTabla, $data)){
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	//UPDATE
	
	function actualizarAlbum($idArtista, $idAlbum, $nombre, $fechaPublicacion, $genero){
		$data = array(
			$this->nombreCamposBD['nombre_album'] => $nombre,
			$this->nombreCamposBD['fecha_publicacion'] => $fechaPublicacion,
			$this->nombreCamposBD['genero_album'] => $genero,
		); 
		$this->db->where($this->nombreCamposBD['id_artista'],$idArtista);
		$this->db->where($this->nombreCamposBD['id_album'],$idAlbum);
		
		if($this->db->update($this->nombreTabla, $data)){
			return TRUE;
		}
		else{
			return FALSE;
		}

	}
	
	//DELETE
	
	function borrarAlbum($idArtista,$idAlbum){
		$this->db->where($this->nombreCamposBD['id_artista'],$idArtista);
		$this->db->where($this->nombreCamposBD['id_album'],$idAlbum);
		$this->db->delete($this->nombreTabla); 
	}
	
	//Esta función debería encargarse adicionalmente de llamar para cada canción del album a la función
	//que aumenta su cantidad de downloads o de hacerlo manualmente? O lo haríamos por trigger?
	function agregarDownload($idAlbum){
		$this->load->model('cancion', '', TRUE);
		$this->db->set('cantidad_downloads', 'cantidad_downloads+1', FALSE);
		$this->db->where('id_album',$idAlbum); //agrega 1 download a cada cancion del album
		if($this->db->update('cancion')){
			$this->db->set('cantidad_downloads', 'cantidad_downloads+1', FALSE);
			$this->db->where('id_album',$idAlbum);
			$this->db->update($this->nombreTabla); //agrega 1 download al album en si
			
			$this->load->model('artista','', TRUE);
			$this->artista->agregarDownload($idAlbum, $this->getCantidadCanciones($idAlbum)); //agrega N downloads al artista
		}
		
	}
	
	/*
	* Devuelve una lista de los primeros $limit albumes ordenados descendentemente por rating
	*/
	function getTopAlbumes($limit){
		
		$this->load->model('artista', '', TRUE);
		
		$query = $this->db->query( 
			'SELECT Q_ALBUM.id_artista, Q_ARTISTA.nombre_artista, Q_ALBUM.id_album , Q_ALBUM.nombre_album, Q_ALBUM.fecha_publicacion, 
		     Q_ALBUM.genero_album, Q_ALBUM.rating, Q_ALBUM.cantidad_downloads
			 FROM album AS Q_ALBUM JOIN artista AS Q_ARTISTA ON Q_ARTISTA.id_artista = Q_ALBUM.id_artista
			 ORDER BY Q_ALBUM.rating DESC, Q_ALBUM.nombre_album ASC LIMIT 0,?', $limit    
		);
		return $query->result_array();
		
	}
	
	/*
	* Devuelve la cantidad de canciones guardadas para el album con id $idAlbum
	*/
	function getCantidadCanciones($idAlbum){
		
		$this->load->model('cancion', '', TRUE);
		
		$query = $this->db->query( 
			'SELECT COUNT(id_cancion) AS cantidad
			 FROM cancion
			 WHERE id_album = ? ', $idAlbum    
		);
		$salida = $query->result_array();
		if($salida == NULL){
			return 0;
		}
		else{
			return $salida[0]['cantidad'];
		}
		
	}
	
	/*
	* Devuelve una lista de los primeros $limit albumes ordenados descendentemente por fecha de publicación
	*/
	function getTopAlbumesPorFecha($limit){
		$this->load->model('artista', '', TRUE);
		
		
		$query = $this->db->query( 
			'SELECT Q_ALBUM.id_artista, Q_ARTISTA.nombre_artista, Q_ALBUM.id_album , Q_ALBUM.nombre_album, Q_ALBUM.fecha_publicacion, 
		     Q_ALBUM.genero_album, Q_ALBUM.rating, Q_ALBUM.cantidad_downloads
			 FROM album AS Q_ALBUM JOIN artista AS Q_ARTISTA ON Q_ARTISTA.id_artista = Q_ALBUM.id_artista
			 ORDER BY Q_ALBUM.fecha_publicacion DESC LIMIT 0,?', $limit    
		);
		return $query->result_array();
	
	}
	
	
}

?>
