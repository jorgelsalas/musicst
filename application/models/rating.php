<?php


Class rating extends CI_Model
{
  
  	public  $camposConcatenados = 'id_album, id_usuario, rating';
  	public  $nombreCamposBD = array(
	    'id_album' => 'id_album',
	    'id_usuario' => 'id_usuario',
		'rating' => 'rating'
	);
	public $nombreTabla = 'rating_album';
  
	function __construct() //TODO: Test
	{
	    parent::__construct();
		
	}
  
	//	Devuelve true si el usuario con id=$idUsuario ya dio rating al album con id=$idAlbum
	function didRate($idAlbum, $idUsuario){
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
	function agregarRating($idAlbum, $idUsuario, $rating){
		$data = array(
			$this->nombreCamposBD['id_album'] => $idAlbum,
			$this->nombreCamposBD['id_usuario'] => $idUsuario,
			$this->nombreCamposBD['rating'] => $rating
		); 
		
		if($this->db->insert('rating_album', $data)){
			$this->calcularRatingTotal($idAlbum);
		}
		
		
	}
	
	
	function updateRating($idAlbum, $idUsuario, $rating){
		
		$query = $this->db->query( 
			"UPDATE rating_album SET rating = ? WHERE id_album = ? AND id_usuario = ?", array($rating, $idAlbum, $idUsuario)    
		);
		$this->calcularRatingTotal($idAlbum);
		#return $query->result_array();
	}
	
	function calcularRatingTotal($idAlbum){
		$query = $this->db->query(
			'UPDATE album 
			 SET cantidad_votos = ( SELECT COUNT(*) FROM rating_album q_cantidad WHERE q_cantidad.id_album = ? ),
			 rating = (SELECT AVG(q_rating.rating) FROM rating_album AS q_rating WHERE q_rating.id_album = ? )
			 WHERE id_album = ? ', array($idAlbum, $idAlbum, $idAlbum)
				
		);
	}
	
	function getRating($idAlbum, $idUsuario){
		
		$query = $this->db->query( 
			"SELECT rating FROM rating_album WHERE id_album = ? AND id_usuario = ?", array($idAlbum, $idUsuario)    
		);
		$resultado = $query->result_array();
		if ( count($resultado) >= 1){
			return $resultado[0]['rating'];
		}
		else{
			return -1;
		}
		
	}
	


}

?>

