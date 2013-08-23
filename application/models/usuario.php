<?php

Class usuario extends CI_Model
{

var $nombreTabla='usuario';
var $camposConcatenados = 'id_usuario, nombre, apellido1, apellido2, pais, username, password, email, id_perfil';
var $camposConcatenadosSinP = 'id_usuario, nombre, apellido1, apellido2, pais, username, password, email, id_perfil';

var $camposConcatenadosSinPassword = '';
var $nombreCamposBD = array(
       'id_usuario'=>'id_usuario',
       'nombre_usuario'=>'nombre',
       'apellido1_usuario'=>'apellido1',
	   'apellido2_usuario'=>'apellido2',
	   'pais'=>'pais',
	   'username'=>'username',
       'password'=>'password',
       'email'=>'email',
	   'id_perfil'=>'id_perfil'
	   
 );
 
   function __construct()
   {
       parent::__construct();        
   }



//Devuelve un ?nico usuario por Id
  function getUsuario($id)
  {
    $this->db->select($this->camposConcatenadosSinP);
    $this->db->from($this->nombreTabla);
    $this->db->where($this->nombreCamposBD['id_usuario'],$id);
    $this->db->limit(1);
    
    $query = $this->db->get();
    
    if($query -> num_rows() == 1)
    {
      return $query->result();
    }
    else
    {
      return false;
    }    
  }
  
  //Devuelve todos los usuarios
  function getAllUsuarios(){
    $this->db->select($this->camposConcatenadosSinP);
    $this->db->from($this->nombreTabla);
    $artistas = $this->db->get()->result();
    return $artistas;
  }
  
  
//Devuelve un ?nico usuario por username
  function getUsuarioPorUsername($username)
  {
    $this->db->select($this->camposConcatenadosSinP);
    $this->db->from($this->nombreTabla);
    $this->db->where($this->nombreCamposBD['username'],$username);
    $this->db->limit(1);
    
    $query = $this->db->get();
    
    if($query -> num_rows() == 1)
    {
      return $query->result();
    }
    else
    {
      return FALSE;
    }    
  }
  
  //Devuelve si existe un username
  //Util para cuando se quiere crear un perfil
  function verificarSiExisteUsername($username)
  {
    /*
    $this->db->select($this->$camposConcatenadosSinP);
    $this->db->from($this->$nombreTabla);
    $this->db->where($this->$nombreCamposBD['username'],$username);
    */
    $this->db->select('*');
    $this->db->from('usuario');
    $this->db->where('username',$username);
	
    $query = $this->db->get();
    
    if($query -> num_rows() == 1)
    {
      return TRUE;
    }
    else
    {
      return false;
    }    
  }
  
  
  
  
  

  //Inserta un usuario
  function insertarUsuario(/*$id,*/$nombre, $apellido1, $apellido2,$pais,$username,$password,$email,$perfil){
  	
  	if ( $this->verificarSiExisteUsername($username) ){
  		return FALSE;
  	}
	else{
		
		$data = array(
	      /*$nombreCamposBD['id_usuario'] => $id,*/
	      $this->nombreCamposBD['nombre_usuario']  => $nombre,
	      $this->nombreCamposBD['apellido1_usuario'] => $apellido1,
		  $this->nombreCamposBD['apellido2_usuario'] => $apellido2,
		  $this->nombreCamposBD['pais'] => $pais,
		  $this->nombreCamposBD['username'] => $username,
		  $this->nombreCamposBD['password'] => MD5($password),
		  $this->nombreCamposBD['email'] => $email,
		  $this->nombreCamposBD['id_perfil'] => $perfil
	    );
		$this->db->insert($this->nombreTabla, $data);    
		
		//Considerar posibilidad de fallo de insercion por otras razones aparte de user repetido
		if ( $this->db->affected_rows() == 1){
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
  }
  
  
  //verifica usuario que se intenta loguear
  function login($username, $password){
  	$this->db->select($this->camposConcatenados);
	$this->db->from($this->nombreTabla);
	$this->db->where($this->nombreCamposBD['username'], $username);
    $this->db->where($this->nombreCamposBD['password'] . ' = ' . "'" . MD5($password) . "'");
	$this->db->limit(1);
	
	$query = $this->db->get();
    
    if($query -> num_rows() == 1)
    {
      return $query->result();
    }
    else
    {
      return false;
    }    
  }
  
 	public function getFavoritos($id_usuario){
		$query = $this->db->query(
			"SELECT A.id_artista, A.nombre_artista, A.genero_artista, A.cantidad_downloads
			 FROM favoritos AS F, artista AS A
			 WHERE F.id_usuario = ?
			 AND F.id_artista = A.id_artista", $id_usuario
		);
	  	return $query->result_array();
	}
  
}

?>
