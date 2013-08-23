<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class browser extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('artista','',TRUE);   
		$this->load->model('album','',TRUE);   
		$this->load->model('cancion','',TRUE);
		$this->load->model('reviews','',TRUE);
		$this->load->model('rating','',TRUE);
	}
	
	public function index() {
		
		
		
		//$this->load->view('contenidos', $data);
		
	}
	
	private function autenticado(){
		$auth = FALSE;
		if ($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
			$auth = TRUE;
		}
		
		return $auth;
	}
	
	public function ver_profile_artista($id_artista){
		$datos_artista = $this->artista->getArtista($id_artista);
		$albums_artista = $this->album->getAlbumsDeArtista($id_artista);
		$data['datos_artista'] = $datos_artista;
		$data['albums_artista'] = $albums_artista; 
		$this->load->view('profile_artista', $data);
	}
	
	public function ver_album($id){
		
		$datosUsuario = NULL;
		$data['hizoReview'] = NULL;
		$data['voto'] = NULL;
		if ($this->autenticado()){
			$datosUsuario = $this->session->userdata('logged_in');
			$data['hizoReview'] = $this->reviews->didReview($id, $datosUsuario['id_usuario']);
			$data['voto'] = $this->rating->getRating($id, $datosUsuario['id_usuario']);
		}
		
		
		$datos_album = $this->album->getAlbum($id);
		$data['datos_album'] = $datos_album;
		$canciones = $this->cancion->getCancionesDeAlbum($id);
		$data['canciones'] = $canciones;
		$artista = $this->artista->getArtistaDeAlbum($id);
		
		
		
		foreach ($artista as $row){
			$nombre_artista = $row['nombre_artista'];
			$id_artista = $row['id_artista'];
		}
		if (isset($nombre_artista)){
			$data['nombre_artista'] = $nombre_artista;
		}
		if (isset($id_artista)){
			$data['id_artista'] = $id_artista;
		}
		
		$reviews = $this->reviews->getAllReviewsAlbum($id);
		$data['reviews'] = $reviews;
		
		$this->load->view('detalle_album', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/browser.php */