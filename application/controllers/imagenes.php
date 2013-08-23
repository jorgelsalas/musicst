<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class imagenes extends CI_Controller {
  
  private $file_ext = '.jpg';

  function __construct()
  {
   parent::__construct();   
  }


  //Ejemplo del Profe. Modificar
  public function upload(){
    //Este helper me permite usar el metodo
    //form_open_multipart en la vista
    $this->load->helper('form');
    $this->load->view('image_upload_view'); 
  }
  
  
  //Ejemplo del profe. Modificar si es necesario
  function moverYResize($file_path, $file_name){
    $config['image_library'] = 'gd2';
    $config['library_path']='/usr/bin';
    $config['source_image'] = $file_path;
    $config['new_image'] = 'assets/'. $file_name . $this->file_ext;
    $config['maintain_ratio'] = TRUE;
    $config['master_dim'] = 'width';
    $config['width']   = 700;
    $config['height']  = 325;    
    $this->image_lib->initialize($config); 
    $this->image_lib->resize();
  }

  //Ejemplo del profe. Modificar si es neceesario
  function createThumbnail($file_name){
    $config['image_library'] = 'gd2';
    $config['source_image'] = 'assets/'.$file_name.'.jpg';
    $config['new_image'] = 'assets/thumb/'.$file_name.'_thumb' . $this->file_ext;
    $config['create_thumb'] = TRUE;
    $config['maintain_ratio'] = TRUE;
    $config['master_dim'] = 'width';
    $config['width']   = 152;
    $config['height']  = 114;    
    $this->image_lib->initialize($config); 
    $this->image_lib->resize();
  }
  
  
  
  //url => images/view/$img/$size
  public function viewAlbum($img, $size='large'){
 // echo "<script type=\"text/javascript\"> alert('Hi');</script>";
  
  
	  $path = 'images/img_album/' . $img .'.jpeg';// $this->file_ext;
      if(file_exists($path)){     
        header('Content-Type: image/jpeg');
        $image = imagecreatefromjpeg($path);
        imagejpeg($image);
      }
      else{
	    header('Content-Type: image/png');
        $image = imagecreatefrompng('images/img_album/imagen_default.png');
        imagepng($image);
      }
   }
   
   
   //url => images/view/$img/$size
  public function viewArtista($img, $size='large'){  
  
	  $path = 'images/img_artista/' . $img .'.jpeg';// $this->file_ext;
      if(file_exists($path)){     
        header('Content-Type: image/jpeg');
        $image = imagecreatefromjpeg($path);
        imagejpeg($image);
      }
      else{
	    header('Content-Type: image/png');
        $image = imagecreatefrompng('images/img_artista/imagen_default.png');
        imagepng($image);
      }
   }
   
   
  
  
  
}