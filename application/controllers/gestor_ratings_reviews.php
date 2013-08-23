<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class gestor_ratings_reviews extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('artista','',TRUE);   
		$this->load->model('usuario','',TRUE);   
		$this->load->model('album','',TRUE);   
		$this->load->model('rating','',TRUE);
		$this->load->model('cancion','',TRUE);
		$this->load->model('reviews','',TRUE);
	}
	
	public function index() {
		//$this->load->view('form_crear_cuenta');
	}
	
	private function autenticado(){
		$auth = FALSE;
		if ($this->session->userdata('logged_in')){
			$auth = TRUE;
		}
		
		return $auth;
	}
	
	
	public function calificarAlbum(){
		$data['mensaje'] = NULL;
		if ($this->autenticado()){
			$data=NULL;
			$datosUsuario = $this->session->userdata('logged_in');
			$data['id_album'] = $this->input->get('id_album');
			$data['id_usuario'] = $datosUsuario['id_usuario'];
			$data['rating'] = $this->input->get('star2');
			
			$data['voto'] = $data['rating'];
			
			$data['hizoReview'] = $this->reviews->didReview($data['id_album'], $data['id_usuario']);
			
			if ($this->rating->didRate($data['id_album'], $data['id_usuario'])){
				$this->rating->updateRating($data['id_album'], $data['id_usuario'], $data['rating']);
				$data['mensaje'] = "Se ha modificado su voto";
			}
			else{
				$this->rating->agregarRating($data['id_album'], $data['id_usuario'], $data['rating']);
				$data['mensaje'] = "Se ha agregado su voto";
			}

			//Revisar cuales son necesarios
			$datos_album = $this->album->getAlbum($data['id_album']);
			$data['datos_album'] = $datos_album;
			$canciones = $this->cancion->getCancionesDeAlbum($data['id_album']);
			$data['canciones'] = $canciones;
			$artista = $this->artista->getArtistaDeAlbum($data['id_album']);
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
			
			$reviews = $this->reviews->getAllReviewsAlbum($data['id_album']);
			$data['reviews'] = $reviews;
			
			$this->load->view('detalle_album', $data);

			//redirect('browser/ver_album/'. $data['id_album'], 'refresh');
			//$this->load->view('dummy', $data);
			#$this->load->view('dummy', $data);
		}else{
			echo "Directory access is forbidden.";
		}
	}
	
	public function darReviewAlbum(){
		$data['mensaje'] = NULL;
		$data['voto'] = -1;	
		if ($this->autenticado()){
		
			$this->load->library('form_validation');
			$this->form_validation->set_rules('textarea_review', 'textarea_review', 'trim|required|xss_clean');
			$datosUsuario = $this->session->userdata('logged_in');
			$data['id_album'] = $this->input->post('id_album');
			$data['id_usuario'] = $datosUsuario['id_usuario'];		
			$data['review'] = $this->input->post('textarea_review');
			
			
			$data['voto'] = $this->rating->getRating($data['id_album'], $datosUsuario['id_usuario']);
			$data['hizoReview'] = $this->reviews->didReview($data['id_album'], $data['id_usuario']);
			
			if($this->form_validation->run() == FALSE){
				$data['mensaje'] = "Por favor verifique el texto de su review";
			}
			else{
				if ($data['hizoReview']){
					$this->reviews->updateReview($data['id_album'], $data['id_usuario'], $data['review']);
					$data['mensaje'] = "Se ha modificado su review";
				}
				else{
					$this->reviews->agregarReview($data['id_album'], $data['id_usuario'], $data['review']);
					$data['mensaje'] = "Se ha agregado su review";
					$data['hizoReview'] = TRUE;
				}
			}

			//Revisar cuales son necesarios
			$datos_album = $this->album->getAlbum($data['id_album']);
			$data['datos_album'] = $datos_album;
			$canciones = $this->cancion->getCancionesDeAlbum($data['id_album']);
			$data['canciones'] = $canciones;
			$artista = $this->artista->getArtistaDeAlbum($data['id_album']);
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
			
			$reviews = $this->reviews->getAllReviewsAlbum($data['id_album']);
			$data['reviews'] = $reviews;
			
			$this->load->view('detalle_album', $data);

			//redirect('browser/ver_album/'. $data['id_album'], 'refresh');
			//$this->load->view('dummy', $data);
			#$this->load->view('dummy', $data);
		}else{
			echo "Directory access is forbidden.";
		}		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/gestor_perfiles.php */