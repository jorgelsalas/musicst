<?php

Class artista extends CI_Model
{

	 var $nombreTabla='artista';
	 var $camposConcatenados = 'id_artista,nombre_artista,genero_artista,cantidad_downloads';
	 var $nombreCamposBD = array(
	       'id_artista'=>'id_artista',
	       'nombre_artista'=>'nombre_artista',
	       'genero_artista'=>'genero_artista',
	       'cantidad_downloads'=>'cantidad_downloads'
	 );
 
   function __construct()
   {
       parent::__construct();
      // --foreach($this->nombreCamposBD as $campoBD){
             //  $this->camposConcatenados = $this->camposConcatenados . $campoBD . ',';
      // }
       //$this->camposConcatenedos = substr($this->camposConcetenados, 0, -1);
   }				



//Devuelve un �nico artista por Id
  function getArtista($id)
  {
    $this->db->select($this->camposConcatenados);
    $this->db->from($this->nombreTabla);
    $this->db->where($this->nombreCamposBD['id_artista'],$id);
    $this->db->limit(1);
    
    $query = $this->db->get();
    
    if($query -> num_rows() == 1)
    {
      return $query->result_array();
    }
    else
    {
      return false;
    }    
  }
  
  //Devuelve todos los artistas ordenados por nombre
  function getAllArtistas(){
    $this->db->select($this->camposConcatenados);
    $this->db->from($this->nombreTabla);
	$this->db->orderby($this->nombreCamposBD['nombre_artista'],'asc');
    $artistas = $this->db->get()->result();
    return $artistas;
  }
  
  function getArtistaDeAlbum($idAlbum){ //TODO: Test
		$query = $this->db->query(
			"SELECT * FROM  artista AS AR, album AS AL WHERE AL.id_album = ? AND AR.id_artista = AL.id_artista LIMIT 0,1", $idAlbum
		); 
		return $query->result_array();
  	}
  
  function getTopArtistas($limit){
  	$this->db->select($this->camposConcatenados);
    $this->db->from($this->nombreTabla);
	$this->db->order_by($this->nombreCamposBD['cantidad_downloads'], 'desc');
	$this->db->limit($limit);
	
    $artistas = $this->db->get()->result_array();
    return $artistas;
  }
  
  //Devuelve todos los artistas cuyo nombre comience con el parametro $nom
  function getArtistasPorNombre($nombre){
    $this->db->select($this->camposConcatenados);
    $this->db->from($this->nombreTabla);
	$this->db->like($this->nombreCamposBD['nombre_artista'],$nombre,'after');
	$this->db->order_by($this->nombreCamposBD['nombre_artista'],'asc');
    $artistas = $this->db->get()->result_array();
    return $artistas;
  }
  
  /*
	Devuelve un id > 0 si es posible actualizar al artista con identificador $id, en caso contrario, devuelve false
  */
  function insertarArtista($nombre, $genero){
    $data = array(
      $this->nombreCamposBD['nombre_artista']  => $nombre,
      $this->nombreCamposBD['genero_artista'] => $genero,
    );    
    if($this->db->insert($this->nombreTabla, $data)){
	
		return $this->db->insert_id();
	}
	else{
		return FALSE;
	}
  }
  
  /*
	Devuelve true si es posible actualizar al artista con identificador $id
  */
  function actualizarArtista($id, $nombre, $genero){
    $data = array(
      $this->nombreCamposBD['nombre_artista']  => $nombre,
      $this->nombreCamposBD['genero_artista'] => $genero,
    );    
	$this->db->where($this->nombreCamposBD['id_artista'],$id);
	if($this->db->update($this->nombreTabla, $data)){
		return TRUE;
	}
	else{
		return FALSE;
	}
  }
  
  function borrarArtista($idArtista){
	$this->db->where($this->nombreCamposBD['id_artista'],$idArtista);
	$this->db->delete($this->nombreTabla); 
  }
  
	function agregarDownload($idAlbum, $cantidad){
		$query = $this->db->query( 
			'UPDATE artista as artista_update
			 SET artista_update.cantidad_downloads = artista_update.cantidad_downloads + ?
			 WHERE artista_update.id_artista IN (
				SELECT Q_ALBUM.id_artista
				FROM album AS Q_ALBUM
				WHERE Q_ALBUM.id_album = ? ) ', array($cantidad, $idAlbum)
		);
	}
  
  
}





?>
