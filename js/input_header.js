function asignar(src, dest){
	var srcElement = document.getElementById(src);
	var destElement = document.getElementById(dest);
	
	destElement.value = srcElement.value;
}



function enviarBusqueda(idCampoBusqueda, idCampoBusquedaForm)
{
  asignar(idCampoBusqueda, idCampoBusquedaForm);

  var formulario = document.getElementById("form_buscar");
  if(formulario != null){
  	if($('#form_buscar').valid()){
  		formulario.submit();
  	}

  }
  
}

function enviarLogin(idCampoUsername, idCampoUsernameForm, idCampoPassword, idCampoPasswordForm)
{
 
  asignar(idCampoUsername, idCampoUsernameForm);
  asignar(idCampoPassword,idCampoPasswordForm);		  
	
  var formulario = document.getElementById("form_login");
  if(formulario != null){
  	formulario.submit();
  }			  
}