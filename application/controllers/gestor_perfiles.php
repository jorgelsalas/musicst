<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class gestor_perfiles extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('artista','',TRUE);   
		$this->load->model('usuario','',TRUE);   
		$this->load->model('album','',TRUE);   
		//$this->load->model('cancion','',TRUE);
		$this->load->model('reviews','',TRUE);
		$this->load->model('favoritos','',TRUE);
	}
	
	public function index() {
		$this->load->view('form_crear_cuenta');
	}
	
	//Crea un nuevo perfil en la base de datos
	
	public function crearPerfil(){
	
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password2', 'password2', 'trim|required|xss_clean');
		$this->form_validation->set_rules('nombre', 'nombre', 'trim|required|xss_clean');
		$this->form_validation->set_rules('apellido1', 'apellido1', 'trim|required|xss_clean');
		$this->form_validation->set_rules('apellido2', 'apellido2', 'trim|required|xss_clean');
		$this->form_validation->set_rules('residencia', 'residencia', 'trim|required|xss_clean');

		if($this->form_validation->run() == FALSE)
		{       
		   redirect('welcome/index', 'refresh');
		}
		else{
		
			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$nombre = $this->input->post('nombre');
			$apellido1 = $this->input->post('apellido1');
			$apellido2 = $this->input->post('apellido2');
			$residencia = $this->input->post('residencia');
			$perfil = 2;
			
			$resultado = $this->usuario->insertarUsuario($nombre, $apellido1, $apellido2,$residencia,$username,$password,$email,$perfil);
			$data['mensaje'] = NULL;
			if ($resultado){
				$data['mensaje'] = "Se ha creado su perfil existosamente";
				$this->load->view('form_crear_cuenta', $data);
			}
			else{
				$data['mensaje'] = "El nombre de usuario elegido no está disponible";
				$this->load->view('form_crear_cuenta', $data);
			}
		}
	}
	
	public function mostrarPerfilUsuario($id_usuario){
		$favoritos = $this->usuario->getFavoritos($id_usuario);
		$data['favoritos'] = $favoritos;
		$usuario = $this->usuario->getUsuario($id_usuario);
		$data['usuario'] = $usuario;
		$reviews = $this->reviews->getAllReviewsUsuario($id_usuario);
		$data['reviews'] = $reviews;
		$this->load->view('profile_usuario', $data);
	}

	public function agregarArtistaAFavoritos(){
		$id_artista = $this->input->post('id_artista');
		$id_usuario = $this->input->post('id_usuario');
		$data['mensaje'] = NULL;
		if($this->favoritos->estaEnFavoritos($id_artista, $id_usuario)){
			//Solo decir que estaba
			$data['mensaje'] = 'El artista ya se encontraba en su lista de favoritos';
		}
		else{
			//Agregar a favoritos
			$this->favoritos->agregarArtistaFavorito($id_artista, $id_usuario);
			$data['mensaje'] = 'Se ha agregado el artista a sus favoritos';
		}
		
		$datos_artista = $this->artista->getArtista($id_artista);
		$albums_artista = $this->album->getAlbumsDeArtista($id_artista);
		$data['datos_artista'] = $datos_artista;
		$data['albums_artista'] = $albums_artista; 
		
		$this->load->view('profile_artista', $data);
		
	}
	
	public function eliminarArtistaDeFavoritos($id_artista){
		
		$datosUsuario = $this->session->userdata('logged_in');
		$id_usuario = $datosUsuario['id_usuario'];
		
		$this->favoritos->eliminarArtistaFavorito($id_artista, $id_usuario);
		
		$this->mostrarPerfilUsuario($id_usuario);
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/gestor_perfiles.php */