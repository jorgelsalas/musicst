<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_edit extends CI_Controller {

	
	function __construct()
	{
		parent::__construct();
		   
		//User queda disponible en todo
		// el controlador
		$this->load->model('artista','',TRUE);   
		$this->load->model('album','',TRUE);   
		$this->load->model('cancion','',TRUE);   
	} 

	//TODO: recibe parametro?
	private function autenticado(){
		$auth = FALSE;
		if ($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
			if ($session_data['id_perfil'] == 1){
				$auth = TRUE;
			}

		}
		
		return $auth;
	}
	

	/*
		Muestra el view para insertar un artista
	*/
	public function insertArtista(){
		if($this->autenticado(/* parametro? */)){
			$this->actualizarArtista($this->getCamposArtista(NULL), true, false, '');
		}else{
			echo "Directory access is forbidden.";
		}
	}
	
	/*
		Muestra el view para hacer update a un artista
	*/
	public function updateArtista($idArtista = NULL){
		if($this->autenticado(/* parametro? */)){
			$camposArtista = $this->getCamposArtista($idArtista);
			$this->actualizarArtista($camposArtista, false, false,'');
		}else{
			echo "Directory access is forbidden.";
		}
	}	
	
	public function deleteArtista($idArtista = NULL){
		if($this->autenticado()){
			$this->artista->borrarArtista($idArtista);
			$this->actualizarArtista($this->getCamposArtista(NULL), true, false, 'Artista borrado exitósamente. Agregue uno nuevo si desea.');
		}else{
			echo "Directory access is forbidden.";
		}
	}
	
	/*
		Muestra el view para insertar un album
	*/
	public function insertAlbum($idArtista = NULL){
		if($this->autenticado(/* parametro? */)){
			$camposAlbum = $this->getCamposAlbum($idArtista, NULL);
			$this->actualizarAlbum($camposAlbum, true, false, '');
		}else{
			echo "Directory access is forbidden.";
		}
	}
	
	/*
		Muestra el view para update un album
	*/
	public function updateAlbum($idArtista = NULL,$idAlbum = NULL){
		if($this->autenticado(/* parametro? */)){
			$camposAlbum = $this->getCamposAlbum($idArtista, $idAlbum);
			$this->actualizarAlbum($camposAlbum, false, false, '');
		}else{
			echo "Directory access is forbidden.";
		}
	}	
	
	public function deleteAlbum($idArtista = NULL,$idAlbum = NULL){
		if($this->autenticado()){
			$this->album->borrarAlbum($idArtista,$idAlbum);
			$this->actualizarAlbum($this->getCamposAlbum($idArtista, false), true, false, 'Album borrado exitósamente. Agregue uno nuevo si desea.');
		}else{
			echo "Directory access is forbidden.";
		}
	}
	
	public function insertCancion($idAlbum = NULL){
		if($this->autenticado(/* parametro? */)){
			$this->insertarCancion($idAlbum, false, '');
		}else{
			echo "Directory access is forbidden.";
		}	
	}
	
	/*
		Se encarga de la inserción o actualización de un artista en la base de datos. Recibe los parámetros por post.
		Si el parámetro de post id_artista es igual a -1 o es nulo, asume que se va a hacer una inserción, en caso contrario
		asume que va a hacer un update al artista con el id especificado.
	*/
	public function insertBDArtista(){
		//TODO: get parametros de post, insertar a base de datos. Manejar la imagen adecuadamente.
		if($this->autenticado(/* parametro? */)){
	
			$this->load->library('form_validation');

    		$this->form_validation->set_rules('nombre_artista', 'nombre', 'trim|required|xss_clean');
   			$this->form_validation->set_rules('genero_artista', 'genero', 'trim|required|xss_clean');

			if($this->form_validation->run() == FALSE)
			{       
			   redirect('welcome/index', 'refresh');
			}
			else
			{			
				$idArtista = $this->input->post('id_artista');
				$nombre = $this->input->post('nombre_artista');
				$genero = $this->input->post('genero_artista');
				$exito = false;	
				if($idArtista == NULL || $idArtista == -1){ //es un insert
					$this->agregarArtistaABaseDeDatos($nombre,$genero);
				}
				else{ //es un update
					$this->actualizarArtistaEnBaseDeDatos($idArtista,$nombre,$genero);			
				}
				
			}
		}else{
			echo "Directory access is forbidden.";
		}
				
	}
	
	/*
		Se encarga de la inserción o actualización de un album en la base de datos. Recibe los parámetros por post.
		Si el parámetro de post id_album es igual a -1 o es nulo, asume que se va a hacer una inserción, en caso contrario
		asume que va a hacer un update al album con el id especificado.
	*/
	public function insertBDAlbum(){
		if($this->autenticado(/* parametro? */)){
	
			$this->load->library('form_validation');
			
    		$this->form_validation->set_rules('nombre_album', 'nombre', 'trim|required|xss_clean');
   			$this->form_validation->set_rules('genero_album', 'genero', 'trim|required|xss_clean');
   			$this->form_validation->set_rules('fecha_publicacion', 'fecha de publicación', 'trim|required|xss_clean');

			if($this->form_validation->run() == FALSE) {  
				//si la validación en el servidor falla no se le notifica al usuario. Se redirige a la página principal.
				redirect('welcome/index', 'refresh');
			}
			else{			
				
				$idArtista = $this->input->post('id_artista');
				$idAlbum = $this->input->post('id_album');
				
				$nombre = $this->input->post('nombre_album');
				$genero = $this->input->post('genero_album');				

				$fechaPublicacion = $this->input->post('fecha_publicacion');
				$arrayFechaPublicacion = explode('-',$fechaPublicacion);
				$fechaPublicacion = $arrayFechaPublicacion[2] . "-".$arrayFechaPublicacion[1] . "-".$arrayFechaPublicacion[0];		
				
				if($idAlbum == NULL || $idAlbum == -1){ //es un insert
					$this->agregarAlbumABaseDeDatos($idArtista, $nombre,$genero,$fechaPublicacion);
				}
				else{ //es un update
					$idCanciones = $this->input->post('id_cancion');
					$nombreCanciones = $this->input->post('nombre_cancion');	
					$deleteCanciones = $this->input->post('delete_cancion');
					$this->actualizarAlbumEnBaseDeDatos($idArtista,$idAlbum,$nombre,$genero,$fechaPublicacion,$idCanciones,$nombreCanciones,$deleteCanciones);		

				}
				
			}
		}else{
			echo "Directory access is forbidden.";
		}
	}
	
	public function insertBDCancion(){
		if($this->autenticado(/* parametro? */)){
	
			$this->load->library('form_validation');
			
    		$this->form_validation->set_rules('nombre_cancion', 'nombre', 'trim|required|xss_clean');
   			$this->form_validation->set_rules('duracion_cancion', 'duración', 'trim|required|xss_clean');

			if($this->form_validation->run() == FALSE) {  
				//si la validación en el servidor falla no se le notifica al usuario. Se redirige a la página principal.
				redirect('welcome/index', 'refresh');
			}
			else{			
				
				$idAlbum = $this->input->post('id_album');
				
				$nombre = $this->input->post('nombre_cancion');
				$duracion = $this->input->post('duracion_cancion');				

				$this->agregarCancionABaseDeDatos($idAlbum, $nombre,$duracion);
				
			}
		}else{
			echo "Directory access is forbidden.";
		}
	}
	
	/*
		Este método se encarga de insertar un artista en la base de datos.
		Si se agrega correctamente, se redirige al usuario a la página de inserción, indicándole que la inserción se realizó sin problemas.
		Caso contrario se le indica al usuario que hubo un problema al insertar el artista
	*/
	private function agregarArtistaABaseDeDatos($nombre, $genero){
		$idArtista = $this->artista->insertarArtista($nombre,$genero);
		
		if($idArtista != false){
			$seAgregoFotoConExito = $this->agregarFoto($idArtista, true);
			if($seAgregoFotoConExito != null){
				$exito = $this->actualizarArtista($this->getCamposArtista(NULL), true, false,'Artista agregado satisfactoriamente');
			}
			else{
				$this->actualizarArtista($this->getCamposArtista(NULL), true, true,'Hubo un problema al subir la foto del artista. Inténtelo de nuevo.');	
				$this->artista->borrarArtista($idArtista); //Hubo un problema con la fotografía, se borra el artista
			}
		}
		else{
			$this->actualizarArtista($this->getCamposArtista(NULL), true, true,'Hubo un problema al agregar el artista. Inténtelo de nuevo.');			
		}	
	}
	
	/*
		Este método se encarga de actualizar un artista en la base de datos.
		Si se actualiza correctamente, se redirige al usuario a la página de update, indicándole que la actualización se realizó sin problemas.
		Caso contrario se le indica al usuario que hubo un problema al actualizar el artista
	*/
	private function actualizarArtistaEnBaseDeDatos($idArtista, $nombre, $genero){
		$actualizacionExitosa = $this->artista->actualizarArtista($idArtista, $nombre, $genero);
		if($actualizacionExitosa != NULL){
			$this->agregarFoto($idArtista, true); //TODO: determinar si admin. subió foto o no
				$this->actualizarArtista($this->getCamposArtista($idArtista), false, false,'Artista actualizado satisfactoriamente');
		}
		else{
			$this->actualizarArtista($this->getCamposArtista(NULL), false, true,'Hubo un problema al actualizar el artista. Inténtelo de nuevo.');			
		}	
	}
	
	/*
		Este método se encarga de insertar un album en la base de datos.
		Si se agrega correctamente, se redirige al usuario a la página de inserción, indicándole que la inserción se realizó sin problemas.
		Caso contrario se le indica al usuario que hubo un problema al insertar el album
	*/
	private function agregarAlbumABaseDeDatos($idArtista,$nombre,$genero,$fechaPublicacion){
		$idAlbum = $this->album->insertarAlbum($idArtista,$nombre,$fechaPublicacion, $genero);
			
		if($idAlbum != false){
			$seAgregoFotoConExito = $this->agregarFoto($idAlbum, false);
			if($seAgregoFotoConExito != null){
				$this->actualizarAlbum($this->getCamposAlbum($idArtista, NULL), true, false,'Album agregado satisfactoriamente');
			}
			else{
				$this->actualizarAlbum($this->getCamposAlbum($idArtista, NULL), true, true,'Hubo un problema al subir la foto del album. Inténtelo de nuevo.');	
				$this->album->borrarAlbum($idArtista,$idAlbum);
			}
		}
		else{
			$this->actualizarArtista($this->getCamposAlbum($idArtista,NULL), true, true,'Hubo un problema al agregar el album. Inténtelo de nuevo.');			
		}	
	}
	
	
	/*
		Este método se encarga de actualizar un album en la base de datos.
		Si se actualiza correctamente, se redirige al usuario a la página de update, indicándole que la actualización se realizó sin problemas.
		Caso contrario se le indica al usuario que hubo un problema al actualizar el album
	*/
	private function actualizarAlbumEnBaseDeDatos($idArtista,$idAlbum,$nombre,$genero,$fechaPublicacion, $idCanciones, $nombreCanciones,$deleteCanciones){
		$actualizacionExitosa = $this->album->actualizarAlbum($idArtista,$idAlbum, $nombre,$fechaPublicacion, $genero);
		if($actualizacionExitosa != NULL){
			$this->agregarFoto($idAlbum, false); //TODO: determinar si admin. subió foto o no
			if(isset($idCanciones) && $idCanciones != null && count($idCanciones) > 0){
				$this->actualizarCancionesEnBaseDeDatos($idAlbum, $idCanciones,$nombreCanciones,$deleteCanciones);
			}
			$this->actualizarAlbum($this->getCamposAlbum($idArtista,$idAlbum), false, false,'Album actualizado satisfactoriamente');
		}
		else{
			$this->actualizarAlbum($this->getCamposAlbum($idArtista,NULL), false, true,'Hubo un problema al actualizar el album. Inténtelo de nuevo.');			
		}	
	}
	
	/*
		Este método se encarga de insertar una cancion a la base de datos.
		Si se agrega correctamente, se redirige al usuario a la página de inserción, indicándole que la inserción se realizó sin problemas.
		Caso contrario se le indica al usuario que hubo un problema al insertar la cancion
	*/
	private function agregarCancionABaseDeDatos($idAlbum,$nombre,$duracion){
		$idCancion = $this->cancion->insertarCancion($idAlbum,$nombre,$duracion);
			
		if($idCancion != false){
			$seAgregoArchivoConExito = $this->agregarMP3($idCancion);
			if($seAgregoArchivoConExito != null){
				$this->insertarCancion($idAlbum, false, 'Canción agregada satisfactoriamente');
			}
			else{
				$this->insertarCancion($idAlbum, true,'Hubo un problema al subir el archivo de la canción. Inténtelo de nuevo.');	
				$this->cancion->borrarCancion($idAlbum,$idCancion);
			}
		}
		else{
			$this->insertarCancion($idAlbum, true,'Hubo un problema al agregar la canción. Inténtelo de nuevo.');	
		}	
	}
	
	private function actualizarCancionesEnBaseDeDatos($idAlbum, $idCanciones, $nombreCanciones,$deleteCanciones){
		$contador = 0;
		foreach($idCanciones as $id){
			$this->cancion->actualizarCancion($idAlbum, $id, $nombreCanciones[$contador], $contador+1);
			$contador = $contador+1;
		}
		if(isset($deleteCanciones) && $deleteCanciones != NULL){
			foreach($deleteCanciones as $id){
				$this->cancion->borrarCancion($idAlbum, $id);
			}
		}
	}
	
	
	
	private function agregarFoto($idFoto, $esArtista){
		$config['upload_path'] = './images/temp/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size'] = '2048';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$config['overwrite']  = true;
		$this->load->library('upload', $config);
		$exito = false;
		if($this->upload->do_upload('imagen')){
			$exito = true;
			$data = $this->upload->data();
						
			$this->load->library('image_lib');
			$file_name = $idFoto;
			$file_path = $data['full_path'];
			
			$this->createThumbnail($file_name, $file_path, $esArtista);
						
			//Borra el archivo original
			unlink($file_path);
			
		}
		return $exito;
	}
	
	/*
		Agrega un thumbnail de la imagen con path $old_file_path y la guarda en tamaño reducido 76x76
		en el path ./images/thumb_home/$file_name.jpg
	*/
	private function createThumbnail($fileName, $oldFilePath, $esArtista){
	
		$newTempPath = './images/img_album/'.$fileName. '_temp.jpg';
		$newPath	 = './images/img_album/'.$fileName. '.jpeg';
		if($esArtista == true){
			$newTempPath = './images/img_artista/'.$fileName. '_temp.jpg';
			$newPath	 = './images/img_artista/'.$fileName. '.jpeg';
		}
	
		$config['image_library'] = 'gd2';
		$config['library_path']='/usr/bin';
		$config['source_image'] = $oldFilePath;
		
		
		$config['new_image'] = $newTempPath;
		$config['maintain_ratio'] = FALSE;
		$config['width']   = 76;
		$config['height']  = 76;    
		
		$this->image_lib->initialize($config); 
		$this->image_lib->resize();
		
		imagejpeg(imagecreatefromstring(file_get_contents($newTempPath)), $newPath);
		unlink($newTempPath);
	}
	
	/*
		Graba el mp3 en el directorio songs/ con nombre el id de la cancion
	*/
	private function agregarMP3($idCancion){
		$config['upload_path'] = './songs/';
		$config['allowed_types'] = 'mp3'; 
		$config['file_name'] = $idCancion;

		$config['overwrite']  = true;
		$this->load->library('upload', $config);
		$exito = false;
		if($this->upload->do_upload('cancion')){
			$exito = true;
			$data = $this->upload->data();

						
			//Borra el archivo original
			//unlink($file_path);
			
		}
		return $exito;
	}

	
	/*
	 * Obtiene los valores de los campos para el artista identificado por idArtista
	 * @param $idArtista   identificador del artista. Si idArtista = NULL devuelve los campos vacios.
	 * @return $valorCampos valor de los campos para el artista identificado por $idArtista
	 */
	private function getCamposArtista($idArtista){
		if($idArtista == NULL){ //se desean los campos vacios
			$valorCampos = array(
		       'id_artista'=>'',
		       'nombre_artista'=>'',
		       'genero_artista'=>'',
		       'cantidad_downloads'=>''
			 );
			 return $valorCampos;
		}
		else{ //obtengo los campos de la base de datos
			$valorCampos =  $this->artista->getArtista($idArtista);
			if($valorCampos != false){
				$valorCampos = $valorCampos[0];
			}
			return $valorCampos;
		}
	}
	
	/*
		Devuelve todas las canciones del album $idAlbum
	*/
	private function getCamposCancion($idAlbum){
		if( $idAlbum == NULL){
			return false;
		}
		else{
			$valorCampos =  $this->cancion->getCanciones($idAlbum);
			return $valorCampos;
		}
	}
	
	/*
	 * Obtiene los valores de los campos para el album identificado por idArtista,idAlbum
	 * @param $idArtista   identificador del artista. Si idArtista = NULL devuelve los campos vacios.
	 * @param $idAlbum   identificador del album. Si idAlbum = NULL devuelve los campos vacios.
	 * @return $valorCampos valor de los campos para el album identificado por $idArtista, $idAlbum
	 */
	private function getCamposAlbum($idArtista, $idAlbum){
		if($idArtista == NULL || $idAlbum == NULL){
			$valorCampos = array(
		  		'id_artista' => $idArtista,
			    'id_album' => '',
				'nombre_album' => '',
				'fecha_publicacion' => '',
				'genero_album' => '',
				'rating' => '',
				'cantidad_votos' => '',
				'cantidad_downloads' => ''
			);
		}
		else{
			$valorCampos =  $this->album->getAlbumDeArtista($idArtista, $idAlbum);
			if($valorCampos != false){
				$valorCampos = $valorCampos[0];
				$valorCampos['fecha_publicacion'] = date('d-m-Y',strtotime($valorCampos['fecha_publicacion']));
			}
		}
		return $valorCampos;
	}

	private function actualizarArtista($valorCampos, $insert, $error, $feedback){
		$data['valor_campos']  = $valorCampos;
		$data['insert']        = $insert;
		$data['feedback']	   = $feedback;
		$data['error']		   = $error;
	    $this->load->helper('form');
		$this->load->view('insert_update_artista', $data);
	}	

	private function actualizarAlbum($valorCampos, $insert,$error,$feedback){
		$data['valor_campos'] = $valorCampos;
		if($valorCampos != false){
			$artista = $this->artista->getArtista($valorCampos['id_artista']);
			$data['nombre_artista'] = $artista[0]['nombre_artista'];
			if($valorCampos['id_album'] != ''){
				$data['canciones'] = $this->cancion->getCancionesDeAlbum($valorCampos['id_album']);
			}
		}
		$data['insert']        = $insert;
		$data['feedback']	   = $feedback;
		$data['error']		   = $error;
	    $this->load->helper('form');
		$this->load->view('insert_update_album', $data);
	}
	
	private function insertarCancion($idAlbum,$error,$feedback){
		if($idAlbum != false){
			$album = $this->album->getAlbum($idAlbum);
			$data['nombre_album']   = $album[0]['nombre_album'];
		}
		$data['id_album']       = $idAlbum;
		$data['feedback']	    = $feedback;
		$data['error']		    = $error;
	    $this->load->helper('form');
		$this->load->view('insert_cancion', $data);
	}

}
