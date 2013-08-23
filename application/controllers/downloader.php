<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class downloader extends CI_Controller {
  

  function __construct()
  {
   parent::__construct();   
   		$this->load->model('artista','',TRUE);   
		$this->load->model('album','',TRUE);   
		$this->load->model('cancion','',TRUE);   
  }

  
  private function autenticado(){
		$auth = FALSE;
		if ($this->session->userdata('logged_in')){
			$auth = TRUE;
		}
		
		return $auth;
}
  
  

  public function descargarCancion($id_cancion, $nombre_cancion, $id_album)
    {	
	 
		if($this->autenticado()){
	
			$this->load->helper('download');
			
			$file_path = "songs/" . $id_cancion . ".mp3";
			if(file_exists($file_path)){
			$data = file_get_contents($file_path); // Read the file's contents
			
			$name = $nombre_cancion . ".mp3";
			$this->cancion->agregarDownload($id_album, $id_cancion);

			force_download($name, $data);
			}else{
				redirect('browser/ver_album/' . $id_album, 'refresh');
			}
		}else{
			echo "Directory access is forbidden.";
		}
    }        
  
  
	public function descargarAlbum($id_album, $nombre_album)
    {	
		if($this->autenticado()){
			
			$this->load->model('cancion','',TRUE); 
			
			$result = $this->cancion->getCancionesDeAlbum($id_album);
			$this->album->agregarDownload($id_album);
			
			$this->load->library('zip');
			
			if($result)
			   {
				 foreach($result as $row)
				 {				 
				   $path = "songs/" . $row['id_cancion'] . ".mp3";
				   if (file_exists($path)){
					$this->zip->read_file($path, FALSE, $row['nombre_cancion'] . ".mp3"); 
				   }
				 }
			   }			
						
			$this->zip->download($nombre_album . ".zip");
						
			redirect('browser/ver_album/' . $id_album, 'refresh');
		}else{
			echo "Directory access is forbidden.";
		}
    } 
	
  
  
}