<?php 
	
	
	
?>

<!DOCTYPE html>	
<html>
	<head>
		<!--[if IE]>
      	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    	<meta charset="utf-8"/>
       	<link href='http://fonts.googleapis.com/css?family=Muli:300' rel='stylesheet' type='text/css'>
		<link href="<?php echo base_url(); ?>css/estilo.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="<?php echo base_url('js/jquery-1.8.3.min.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/jquery.validate.min.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/input_header.js') ?>"></script>
		<link rel="stylesheet" href="<?php echo base_url(); ?>js/development-bundle/themes/base/jquery.ui.all.css"/>
		<script src="<?php echo base_url(); ?>js/development-bundle/ui/jquery.ui.core.js"></script>
		<script src="<?php echo base_url(); ?>js/development-bundle/ui/jquery.ui.widget.js"></script>
		<script src="<?php echo base_url(); ?>js/development-bundle/ui/jquery.ui.mouse.js"></script>
		<script src="<?php echo base_url(); ?>js/development-bundle/ui/jquery.ui.datepicker.js"></script>
		<script src="<?php echo base_url(); ?>js/development-bundle/ui/jquery.ui.sortable.js"></script>
		<script src="<?php echo base_url(); ?>js/development-bundle/ui/jquery.ui.accordion.js"></script>

		<script type="text/javascript">
			$(document).ready(function(){

				$("#album_insert_update_form").validate({
					rules: {
						nombre_album: "required",
						fecha_publicacion: "required",
						genero_album: "required",
						imagen_album: "required"
					},
					messages: {
						nombre_album: "Nombre requerido",
						fecha_publicacion: "Fecha requerida",
						genero_album: "Género requerido",
						imagen_album: "Imagen requerida"
					}
				});

				$('li.popup_list_element').each(function(index) {
				    colorInicio = '#'+(index+1)+'A'+(index+1)+'A'+(index+1)+'A';
				    colorFin   = '#'+(index+2)+'A'+(index+2)+'A'+(index+2)+'A';
				    $( this ).css({ 
				    	'background-color' : colorInicio,
				    	'background-repeat' : 'repeat-y',
				    	
						'background' : '-webkit-gradient(linear, left top, right top, from('+colorInicio+'), to('+colorFin+'))' //, TODO agregar coma si se quita el comentario
					//	'background' : '-webkit-linear-gradient(left, '+colorInicio+', '+colorFin+')', 
					//	'background' : '-moz-linear-gradient(left, '+colorInicio+', '+colorFin+')', TODO: Por ahora el gradient solo aplica a chrome
					//	'background' : '-ms-linear-gradient(left, '+colorInicio+', '+colorFin+')', 
				    	
				    });

				});

		 	 });	
		 	 
			$(function() {
				$( "#fecha_publicacion" ).datepicker({
					defaultDate: "+1w",
					changeMonth: true,
					numberOfMonths: 1,
					dateFormat: 'dd-mm-yy'
				});
			});	
			
			$(function() {
				$( "#accordion" )
					.accordion({
						header: "> div > h3",
						collapsible: true,
						active:false
						
					})
					.sortable({
						axis: "y",
						handle: "h3",
						stop: function( event, ui ) {
							// IE doesn't register the blur when sorting
							// so trigger focusout handlers to remove .ui-state-focus
							ui.item.children( "h3" ).triggerHandler( "focusout" );
						}
					});
			});
			

		</script>
		<title>musicst</title> 

	</head>
	<body>
		<div id="page">
			<header>	
				<form id="form_buscar" style="DISPLAY: none;" method="get" action="<?=site_url('buscador/buscar')?>">		

					<input style = "DISPLAY: none;" type="hidden" id="buscar_por" name="buscar_por" value="artista" />		<!-- TODO: Mecanismo para buscar por... -->								
					<input style = "DISPLAY: none;" type="text" name="campo_busqueda" id="campo_busqueda" class="required" <?php echo (isset($campo_busqueda)) ? "value = '" . $campo_busqueda . "'" : "value = ''"; ?> />
					<input style = "DISPLAY: none;" type="submit" value="buscar" id="boton_submit_busqueda" /> 
				</form>
				
				<form id="form_login" style="DISPLAY: none;" method="post" action="<?=site_url('login/checklogin')?>">	<!-- TODO: Agregar mecanismo de login y setear action! -->												
				<input style = "DISPLAY: none;" type="text" name="campo_usuario_login" id="campo_usuario_login" placeholder="username" class="required" />
					<input style = "DISPLAY: none;" type="text" name="campo_password_login" id="campo_password_login" placeholder="password" class="required"/>
					<input style = "DISPLAY: none;" type="submit" value="Login" id="boton_submit_login" />			
				</form>
				
				<h1 class="hidden_text">musicst</h1>
				<h2 class="hidden_text">Tienda de música online</h2>
				<div class="empty_row">
					
				</div>
				<span class="titulo_header">
					<a href="<?php echo base_url('') ?>"> musicst </a>
				</span>
				<span class="subtitulo_header">
					<a href="<?php echo base_url('') ?>"> Tienda de música online </a>
				</span>
				<span class="search_bar"> <!-- TODO: El form me agrega un cambio de linea. Preguntar al profe. -->
			
						
						<label for="campo_busqueda_visible">  </label>
						<input type="text" name="campo_busqueda_visible" id="campo_busqueda_visible" value=""/>

					   <span class="submit">
							<input type="submit" value="Buscar" id="boton_submit_busqueda_visible" class="shadow" onclick="enviarBusqueda('campo_busqueda_visible', 'campo_busqueda');" />
					   </span> 
				</span>
				
				<span class="login_bar">
				<!-- Esta validacion se agrego para el login -->													
				<?php
					$logueado = false;
					$id_perfil = 1;
					if (!$this->session->userdata('logged_in')){
						
				?>
						<label for="campo_usuario_login"> Usuario  </label>
						<input type="text" name="campo_usuario_login_visible" id="campo_usuario_login_visible" value=""/>

						<label for="campo_password_login"> Password  </label>
						<input type="password" name="campo_password_login_visible" id="campo_password_login_visible" value=""/>
	
					   <span class="submit">
							<input type="submit" value="Login" id="boton_submit_login_visible" class="shadow" onclick="enviarLogin('campo_usuario_login_visible','campo_usuario_login', 'campo_password_login_visible', 'campo_password_login');" />
					   </span> 
					   
					   <?php
					}	else {
							$logueado = true;
							$session_data = $this->session->userdata('logged_in');
							$id_perfil=$session_data['id_perfil'];
							
				?>
					<span id="div_logged_in">
						<a href="<?php echo site_url('gestor_perfiles/mostrarPerfilUsuario/'. $session_data['id_usuario'] ) ?>" class="blue no_underline"> <?php echo $session_data['username'];?> </a> 
						<a href="<?=site_url('login/logout')?>">(Logout)</a> 		
					</span>
					
				<?php
					}					
				?>
					   
				</span>


			</header>

						
			<div class = "left_column">
				<nav>
					
					<div class="left_menu">
						<ul>
							
							<!--Se agrega el margen dependiendo de la cantidad de elementos del nav -->
							<li class="popup_list_element shadow" <?php if ($logueado){ if($id_perfil==2){echo "style='margin-left: 80px;'";}}?>>
								<a href="<?php echo base_url('') ?>"class="popup_list_cell_content">Home</a>
							</li> 
							<li class="popup_list_element shadow"><a href="<?php echo site_url('welcome/browse') ?>" class="popup_list_cell_content">Browse</a></li>
							
							<!--Necesario para controlar lo que puede ver cada usuario segun su roll en el menu -->
							<?php
								if (!$this->session->userdata('logged_in')){
							?>
							<li class="popup_list_element shadow"><a href="<?php echo site_url('gestor_perfiles/index') ?>" class="popup_list_cell_content">Crear Perfil</a></li>	
							
							<?php
							}else{
								$session_data = $this->session->userdata('logged_in');
								//Perfil de administrador
								if ($session_data['id_perfil']==1){
							?>															
									<li class="popup_list_element shadow"><a href="<?php echo site_url('admin_edit/insertArtista') ?>" class="popup_list_cell_content">Agregar Artista</a></li>
									
							<?php
								}
							}
							?>
							
							<!--li class="popup_list_element shadow"><a href="#" class="popup_list_cell_content">Profile</a></li-->
							<li class="popup_list_element shadow"><a href="<?php echo site_url('welcome/acercaDe') ?>" class="popup_list_cell_content">Acerca de</a></li>	
							<li class="popup_list_element shadow"><a href="<?php echo site_url('welcome/contactenos') ?>" class="popup_list_cell_content">Contacto</a></li>
							
						</ul> 
					</div>
				</nav>
				
				
			</div>								

					
			<div id="main_view" class="shadow">		
				
			   <div id="contenedor_contenido">		
			   	
				<?php if(isset($valor_campos) == false || $valor_campos == false || isset($nombre_artista) == false || $nombre_artista == NULL){ ?>
						<p>El artista o album indicado no existen. </p>
				<?php }else{ 	?>			   	
					<div class="center">
			   			<h1> musicst </h1>
			   			<?php if(isset($insert) == false || $insert == true){ ?>
			   		   			<h3> Agregar album a artista <a href="<?php echo site_url('browser/ver_profile_artista/' . $valor_campos['id_artista'] ) ?>" class="link" > <?php echo $nombre_artista ?> </a></h3>
			   			<?php }else{ ?>
			   					<h3> Actualizar album a artista <a href="<?php echo site_url('browser/ver_profile_artista/' . $valor_campos['id_artista'] ) ?>" class="link" > <?php echo $nombre_artista ?> </a></h3>

			   			<?php } ?>
						
						
						<?php if($insert == false){ ?>
						<div id="perfil_artista_nav_header">
							<a href="<?php echo site_url('browser/ver_album/' . $valor_campos['id_album'] ) ?>" class="img_busqueda "> <!-- TODO: Setear imagen del artista/album -->
							
								<img src="<?php echo site_url('imagenes/viewAlbum/' . $valor_campos['id_album'] ); ?>" alt width="50">
							</a>
						</div>
						
						<span class="admin_edit"> <a href="<?php echo site_url('admin_edit/insertCancion/' . $valor_campos['id_album'] ); ?>"> Agregar canción </a> </span>

						
						<?php } else{ ?>
						
							<div id="perfil_artista_nav_header">
								<a href="<?php echo site_url('browser/ver_profile_artista/' . $valor_campos['id_artista'] ) ?>" class="img_busqueda "> <!-- TODO: Setear imagen del artista/album -->
								
									<img src="<?php echo site_url('imagenes/viewArtista/' . $valor_campos['id_artista'] ); ?>" alt width="50">
								</a>
							</div>
								
							
						<?php } ?>
			   		</div>



		   		        <?php
				          $attributes = array('id' => 'album_insert_update_form');
				          echo form_open_multipart('admin_edit/insertBDAlbum', $attributes); 
				        ?>
						<input type="hidden" style="DISPLAY: none;" name="id_artista" id="id_artista" 
						value="<?php echo $valor_campos['id_artista'] ?>"/>
		
						<input type="hidden" style="DISPLAY: none;" name="id_album" id="id_album" 
						value="<?php echo $valor_campos['id_album'] ?>"/>
						
						<p class="campo_form_registro">
							<!--Nombre:--> 
							<label for="nombre_album">Nombre:</label>
							<br/>
							<input type="text" name="nombre_album" id = "nombre_album" 
							value="<?php echo $valor_campos['nombre_album'] ?>"/>
							<br/>
							
						</p>
						
						<p class="campo_form_registro">
							<!--Nombre:--> 
							<label for="fecha_publicacion">Fecha de publicación:</label>
							<br/>
							<input type="text" name="fecha_publicacion" id = "fecha_publicacion" 
							value="<?php echo $valor_campos['fecha_publicacion'] ?>"/>
							<br/>
							
						</p>
						<p class="campo_form_registro">
							<!--Nombre:--> 
							<label for="genero_album">Género:</label>
							<br/>
							<input type="text" name="genero_album" id = "genero_album" 
							value="<?php echo $valor_campos['genero_album'] ?>"/>
							<br/>
							
						</p>
						<p class="campo_form_registro">
							<!--Nombre:--> 
							<label for="imagen">Imagen:</label>
							<br/>
        					<input type="file" name="imagen" id="imagen" />
							
						</p>						
						<?php if($insert == false){ ?>
							<p class="campo_form_registro">
								Cantidad downloads: <?php echo $valor_campos['cantidad_downloads'] ?>
							</p>
							<p class="campo_form_registro">
								Rating: <?php echo $valor_campos['rating'] ?>
							</p>
							<p class="campo_form_registro">
								Cantidad votos: <?php echo $valor_campos['cantidad_votos'] ?>
							</p>
						<?php if(isset($canciones) && $canciones != null && count($canciones) > 0){ ?>

							 <p>
								Si desea, modifique las canciones del album (arrástrelas para modificar el orden)
							 </p>
							<div id="accordion">
						<?php	$contador = 1;
								foreach($canciones as $cancion){ ?>
									<div class="group">
										<input type="hidden" style="DISPLAY: none;" name="id_cancion[]" id="id_cancion[]" 
											value="<?php echo $cancion['id_cancion'] ?>"/>
										<h3> <p style="margin-left: 30px;">Track <?php echo $contador; ?> </p> </h3>
										<div>
											<p class="campo_form_registro">
												<label for="nombre_cancion[]">Nombre:</label>
												<input type="text" name="nombre_cancion[]" id = "nombre_cancion[]" 
												value="<?php echo $cancion['nombre_cancion'] ?>" />
											</p>
											<p class="campo_form_registro">
												Duración: <?php echo $cancion['duracion']; ?>
											</p>		
											<p class="campo_form_registro">
												Cantidad downloads: <?php echo $cancion['cantidad_downloads']; ?>
											</p>
											<p class="campo_form_registro">
												Eliminar la canción
												<input type="checkbox" name="delete_cancion[]" id="delete_cancion[]" value="<?php echo $cancion['id_cancion'] ?>" />
											</p>
											
											<br />

										</div>
									</div>
									<?php $contador = $contador + 1; ?>
							
						<?php	} ?>							</div>
							
						<?php } else{ ?>
								<p> Este album no tiene canciones asociadas </p>
						
						<?php } ?>

							

						<?php } ?>
						<br />
				   		
						<p>
							<input class="boton_enviar_form" type="submit" value="Enviar" />
						</p>
						
				   	</form>
					
						
					<?php 
							if(isset($feedback) && $feedback != NULL){
								echo "<p class='feedback'>".$feedback.'</p>';
							}
					} ?>
				</div>
			</div>
			
			<footer>
						
				<div id="contenido_footer">
					Todos los derechos reservados.
				</div>
				
			</footer>
			
		</div>
		
	</body>
</html>
