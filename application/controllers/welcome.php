<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
		 function __construct()
	  {
	   parent::__construct();
	   
	   //User queda disponible en todo
	   // el controlador
	   $this->load->model('artista','',TRUE);   
	   $this->load->model('album','',TRUE);   
	   $this->load->model('cancion','',TRUE);   
	  } 
	 
	 
	public function index()
	{
		
		$resultado = $this->album->getTopAlbumes(10);
		$data['resultados_mas_populares']=$resultado;
		$data['header_tabla_mas_populares']="Top 10";
		
		
		$resultado = $this->album->getTopAlbumesPorFecha(10);

		$data['resultados_mas_nuevos']=$resultado;
		$data['header_tabla_mas_nuevos']="Agregados recientemente";
		
		$this->load->view('home', $data);
	
	}
	
	public function acercaDe(){
		$this->load->view('acerca_de');
	}
	
	public function contactenos(){
		$this->load->view('contactenos');
	}
	
	public function browse()
	{	
							
		$resultado = $this->album->getTopAlbumes(10);
		$data['resultados_mas_populares']=$resultado;
		$data['header_tabla_mas_populares']="Lo mas popular";
		$data['desc_tabla_mas_populares']="Albumes con mas rating";
		$data['imagen_tabla_mas_populares']="imagenes/viewAlbum/" . $resultado[0]['id_album'];
		
		$resultado = $this->album->getTopAlbumesPorFecha(10);
		$data['resultados_mas_nuevos']=$resultado;
		$data['header_tabla_mas_nuevos']="Lo mas nuevo";
		$data['desc_tabla_mas_nuevos']="Albunes mas recientes";
		$data['imagen_tabla_mas_nuevos']="imagenes/viewAlbum/" . $resultado[0]['id_album'];
		
		$resultado = $this->artista->getTopArtistas(10);
		$data['resultados_artistas_mas_populares']=$resultado;
		$data['header_tabla_artistas_mas_populares']="Los mas escuchados";
		$data['desc_tabla_artistas_mas_populares']="Artistas mas populares";
		$data['imagen_tabla_artistas_mas_populares']="imagenes/viewArtista/" . $resultado[0]['id_artista'];
		
		$resultado = $this->cancion->getTopCanciones(10);
		$data['resultados_canciones_mas_populares']=$resultado;
		$data['header_tabla_canciones_mas_populares']="Las mas descargadas";
		$data['desc_tabla_canciones_mas_populares']="Musica mas descargada";
		$data['imagen_tabla_canciones_mas_populares']="imagenes/viewAlbum/" . $resultado[0]['id_album'];
		
		$this->load->view('browse', $data);
		
			
		
	}
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */