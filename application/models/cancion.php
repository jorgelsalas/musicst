<?php


Class cancion extends CI_Model
{
  
  	var $camposConcatenados = 'id_album, id_cancion, nombre_cancion,numero_track, duracion, cantidad_downloads';
  	var $nombreCamposBD = array(
	    'id_album' => 'id_album',
		'id_cancion' => 'id_cancion',
		'nombre_cancion' => 'nombre_cancion',
		'numero_track' => 'numero_track',
		'duracion' => 'duracion',
		'cantidad_downloads' => 'cantidad_downloads'
	);
	var $nombreTabla = 'cancion';
  
	function __construct()
	{
	    parent::__construct();
		//foreach($nombreCamposBD as $campoBD){
			//$camposConcatenados = $camposConcatenados . $campoBD . ',';
			//}
			//$camposConcatenedos = substr($camposConcetenados, 0, -1);
	}
  
	function getAllCanciones(){ //TODO: Test
  		$this->db->select($this->$camposConcatenados);	
    	$this->db->from($this->$nombreTabla);
		$this->db->orderBy($this->$nombreCamposBD['numero_track'], 'asc'); //TODO: Revisar syntax.
    	$albums = $this->db->get()->result();
    	return $albums->result();
  	}

  	function getCancion($idCancion){ //TODO: Test
	
  		$this->db->select($this->$camposConcatenados);	
    	$this->db->from($this->$nombreTabla);
		$this->db->where($this->$nombreCamposBD['id_cancion'], $idCancion);
	    $this->db->limit(1);
		
	    $album = $this->db->get()->result();
		if($album -> num_rows() == 1){
   			return $album->result();
		}
		else{
			return false;
		}
  	}

	function getCancionesDeAlbum($idAlbum){ //TODO: Test
		$query = $this->db->query(
			"SELECT * FROM cancion WHERE id_album = ? ORDER BY numero_track asc", $idAlbum
		); 
		return $query->result_array();

	}

	// Devuelve [idArtista, nombreArtista, columnasDeAlbum]
	function getCancionesConNombreSimilarA($nombreCancion){ //TODO: Test
		$this->load->model('artista', '', TRUE);
		$this->load->model('album', '', TRUE);
	
		$nombreCancion = $nombreCancion . '%';
	
		/*	'id_cancion' => 'id_cancion',
		'nombre_cancion' => 'nombre_cancion',
		'numero_track' => 'numero_track',
		'duracion' => 'duracion',
		'cantidad_downloads' => 'cantidad_downloads' */
		$query = $this->db->query( 
			'SELECT Q_ARTISTA.id_artista, Q_ARTISTA.nombre_artista, Q_ALBUM.id_album, Q_ALBUM.nombre_album,
		  	 Q_CANCION.nombre_cancion, Q_CANCION.numero_track, Q_CANCION.duracion, Q_CANCION.cantidad_downloads
			 FROM cancion AS Q_CANCION JOIN album AS Q_ALBUM ON Q_CANCION.id_album = Q_ALBUM.id_album JOIN artista AS Q_ARTISTA ON Q_ARTISTA.id_artista = Q_ALBUM.id_artista
			 WHERE Q_CANCION.nombre_cancion LIKE ?
			 ORDER BY Q_ARTISTA.nombre_artista, Q_ALBUM.fecha_publicacion', $nombreCancion    
		);
		return $query->result_array();
	}
	
	
	function getCancionPorNombre($nombreCancion){ //TODO: Test
	
  		$this->db->select($this->camposConcatenados);	
    	$this->db->from($this->nombreTabla);
	    $this->db->like($this->nombreCamposBD['nombre_cancion'],$nombreCancion,'after');
		$this->db->order_by($this->nombreCamposBD['nombre_cancion'],'asc');
    
		
		
	    $album = $this->db->get()->result();
		
   		return $album;
		
  	}
	
	//INSERT
	
	function insertarCancion($idAlbum, $nombre,$duracion){ //TODO: Test
		$this->load->model('album', '', TRUE);

		$data = array(
			$this->nombreCamposBD['id_album'] => $idAlbum,
			$this->nombreCamposBD['nombre_cancion'] => $nombre,
			$this->nombreCamposBD['duracion'] => $duracion,
			$this->nombreCamposBD['numero_track'] => $this->album->getCantidadCanciones($idAlbum) + 1
		); 
		if($this->db->insert($this->nombreTabla, $data)){
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	//UPDATE
	
	function actualizarCancion($idAlbum, $idCancion, $nombre, $numeroTrack){
		$data = array(
			$this->nombreCamposBD['nombre_cancion'] => $nombre,
			$this->nombreCamposBD['numero_track'] => $numeroTrack,
		); 
		$this->db->where($this->nombreCamposBD['id_album'],$idAlbum);
		$this->db->where($this->nombreCamposBD['id_cancion'],$idCancion);
		
		if($this->db->update($this->nombreTabla, $data)){
			return TRUE;
		}
		else{
			return FALSE;
		}

	}
	
	//DELETE
	
	function borrarCancion($idAlbum,$idCancion){
		$this->db->where($this->nombreCamposBD['id_album'],$idAlbum);
		$this->db->where($this->nombreCamposBD['id_cancion'],$idCancion);
		$this->db->delete($this->nombreTabla); 
	}
	
	function agregarDownload($idAlbum, $idCancion){
		
		$this->db->set($this->nombreCamposBD['cantidad_downloads'], $this->nombreCamposBD['cantidad_downloads'].'+1', FALSE);
		$this->db->where($this->nombreCamposBD['id_cancion'],$idCancion);
		
		if($this->db->update($this->nombreTabla)){
			$this->load->model('artista', '', TRUE);
			$this->artista->agregarDownload($idAlbum, 1);
		}
		else{
			return false;
		}
		
	}
	
   //Obtiene la canciones ordenadas por cantidad de downloads
  function getTopCanciones($limit){
  	$this->db->select($this->camposConcatenados);
    $this->db->from($this->nombreTabla);
	$this->db->order_by($this->nombreCamposBD['cantidad_downloads'], 'desc');
	$this->db->limit($limit);
	
    $canciones = $this->db->get()->result_array();
    return $canciones;
  }
	
	
	
	
}

?>
