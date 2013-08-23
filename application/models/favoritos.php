<?php

Class favoritos extends CI_Model
{
	
	var $nombreTabla='favoritos';
	var $camposConcatenados = 'id_artista, id_usuario';
	var $nombreCamposBD = array(
	       'id_artista'=>'id_artista',
	       'id_usuario'=>'id_usuario'
	 );
 
   function __construct()
   {
       parent::__construct();
   }
   
   function estaEnFavoritos($idArtista, $idUsuario){
		$this->db->select($this->camposConcatenados);	
    	$this->db->from($this->nombreTabla);
		$this->db->where($this->nombreCamposBD['id_artista'], $idArtista);
		$this->db->where($this->nombreCamposBD['id_usuario'], $idUsuario);
	    $this->db->limit(1);
		
	    //$ratings = $this->db->get()->result();
		$ratings = $this->db->get();
		return $ratings->num_rows() >= 1;
   }


  function agregarArtistaFavorito($idArtista, $idUsuario)
  {
    $data = array(
      $this->nombreCamposBD['id_artista'] => $idArtista,
      $this->nombreCamposBD['id_usuario']  => $idUsuario,
    );    
    $this->db->insert($this->nombreTabla, $data);  
  }
  
  function eliminarArtistaFavorito($idArtista, $idUsuario)
  {
    $query = $this->db->query( 
		"DELETE FROM favoritos WHERE id_artista = ? AND id_usuario = ?", array($idArtista, $idUsuario)    
	);
	
  }
  
}

?>
