<?php

Class reviews extends CI_Model
{

var $nombreTabla='review';
var $camposConcatenados = 'id_review, id_album, id_usuario, review, fecha_hora_review';

var $nombreCamposBD = array(
       'id_review'=>'id_review',
       'id_album'=>'id_album',
       'id_usuario'=>'id_usuario',
	   'review'=>'review',
	   'fecha_hora_review'=>'fecha_hora_review'
 );
 
   function __construct()
   {
       parent::__construct();
   }



//Devuelve todos los reviews de un album
  function getAllReviewsAlbum($idAlbum) {
  	/*
    $this->db->select($this->camposConcatenados);
    $this->db->from($this->nombreTabla);
    $this->db->where($this->nombreCamposBD['id_album'],$idAlbum);
    
    $query = $this->db->get();
    
    if($query -> num_rows() >= 1)
    {
      return $query->result_array();
    }
    else
    {
      return false;
    }*/
    
    $query = $this->db->query( 
		'SELECT A.nombre_album, R.id_review, R.id_album, R.id_usuario, R.review, R.fecha_hora_review, U.username
		 FROM album AS A, review AS R, usuario AS U
		 WHERE R.id_album = ?
		 AND A.id_album = R.id_album
		 AND U.id_usuario = R.id_usuario
		 ORDER BY fecha_hora_review DESC', $idAlbum    
	);
	if ( count($query->result_array()) >= 1 ){
		return $query->result_array();
	}
	else {
		return false;
	}
        
  }
  
  //Devuelve todos los reviews creados por un usuario
  function getAllReviewsUsuario($idUsuario) {
  	/*
    $this->db->select($this->camposConcatenados);
    $this->db->from($this->nombreTabla);
    $this->db->where($this->nombreCamposBD['id_usuario'],$idUsuario);
    
    $query = $this->db->get();
    
    if($query -> num_rows() >= 1)
    {
      return $query->result();
    }
    else
    {
      return false;
    }
	*/
	
    $query = $this->db->query( 
		'SELECT A.nombre_album, R.id_review, R.id_album, R.id_usuario, R.review, R.fecha_hora_review
		 FROM album AS A, review AS R
		 WHERE R.id_usuario = ?
		 AND A.id_album = R.id_album', $idUsuario    
	);
	if ( count($query->result_array()) >= 1 ){
		return $query->result_array();
	}
	else {
		return false;
	}
	  
  }
  
  	//	Devuelve true si el usuario con id=$idUsuario ya dio rating al album con id=$idAlbum
	function didReview($idAlbum, $idUsuario){
		$this->db->select($this->camposConcatenados);	
    	$this->db->from($this->nombreTabla);
		$this->db->where($this->nombreCamposBD['id_album'], $idAlbum);
		$this->db->where($this->nombreCamposBD['id_usuario'], $idUsuario);
	    $this->db->limit(1);
		
	    //$ratings = $this->db->get()->result();
		$ratings = $this->db->get();
		return $ratings->num_rows() >= 1;
	}
		
	// 	Rating dado por el usuario al album. 0 <= $rating <= 5  (rating = int)
	function agregarReview($idAlbum, $idUsuario, $review){
		$data = array(
			$this->nombreCamposBD['id_album'] => $idAlbum,
			$this->nombreCamposBD['id_usuario'] => $idUsuario,
			$this->nombreCamposBD['review'] => $review
		); 
		$this->db->insert('review', $data);
	}
	
	
	function updateReview($idAlbum, $idUsuario, $review){
		
		$query = $this->db->query( 
			"UPDATE review SET review = ? WHERE id_album = ? AND id_usuario = ?", array($review, $idAlbum, $idUsuario)    
		);
		#return $query->result_array();
	}
  
}

?>
