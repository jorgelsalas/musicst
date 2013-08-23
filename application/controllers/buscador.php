<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class buscador extends CI_Controller {

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
	 * @see http://codeigjjniter.com/user_guide/general/urls.html
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
	 
	public function buscar()
	{	
		$key_word = $this->input->get('campo_busqueda'); 
		
		$data['key_word'] = $key_word;
		
		//Buscar artistas
			$resultados_artista = $this->artista->getArtistasPorNombre($key_word);
			$data['header_tabla_artista'] = "Artistas";
			$data['resultados_artistas']=$resultados_artista;

			
		//Buscar albumes
			$resultados_album = $this->album->getAlbumsConNombreSimilarA($key_word);	
			$data['resultados_albumes'] = $resultados_album;
			$data['header_tabla_albumes'] = "Álbumes";
			
		//Buscar canciones
			$resultados_cancion = $this->cancion->getCancionesConNombreSimilarA($key_word);
			$data['header_tabla_cancion'] = "Canciones";
			$data['resultados_cancion']=$resultados_cancion;
			
			$this->load->view('resultados_busqueda', $data);
		
	}
	
	
	
	
}
