<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login extends CI_Controller {

  function __construct()
  {
   parent::__construct();
   
   //User queda disponible en todo
   // el controlador
   $this->load->model('usuario','',TRUE);   
  }

  
  private function autenticado(){
		$auth = FALSE;
		if ($this->session->userdata('logged_in')){
			$auth = TRUE;
		}
		
		return $auth;
  }
  
  public function ver_login(){
	if (!$this->autenticado()){
    $this->load->view('login');
	}else{
			echo "Directory access is forbidden.";
	}
  }

  
  public function checklogin(){
	if (!$this->autenticado()){
   $this->load->library('form_validation');

   $this->form_validation->set_rules('campo_usuario_login', 'Username', 'trim|required|xss_clean');
   $this->form_validation->set_rules('campo_password_login', 'Password', 'trim|required|xss_clean|callback_check_database');

   
   
   if($this->form_validation->run() == FALSE)
   {
     //Hack to support redirect instead of loading view
     $this->session->set_flashdata('validation_errors', validation_errors());
     redirect('login/ver_login', 'refresh');
   }
   else
   {
     //Go to private area
     redirect('welcome/index', 'refresh');
   }
   }else{
			echo "Directory access is forbidden.";
	}

 }

 function check_database($password)
 {
     
   //User queda disponible SOLO en 
   //este metodo
   //$this->load->model('user','',TRUE); //cargando un model
   $username = $this->input->post('campo_usuario_login');

   $result = $this->usuario->login($username, $password);

   if($result)
   {
     $sess_array = array();
     foreach($result as $row)
     {
       $sess_array = array(
         'id_usuario' => $row->id_usuario,
         'nombre_usuario' => $row->nombre,
         'apellido1_usuario' => $row->apellido1,
         'apellido2_usuario' => $row->apellido2,
         'pais' => $row->pais,
		 'username' => $row->username,
		 'password' => $row->password,
		 'id_perfil' => $row->id_perfil
       );
       $this->session->set_userdata('logged_in', $sess_array);
     }
     return TRUE;
   }
   else
   {
     $this->form_validation->set_message('check_database', 'Usuario o password incorrecto');     
     return false;
   }
 }
 
 
 public function logout()
  {
    $this->session->unset_userdata('logged_in');	
    //session_destroy();
    redirect('welcome', 'refresh');
  }
 
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */