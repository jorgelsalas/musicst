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
		<script type="text/javascript">

		 	 

			$(document).ready(function(){
				$("#form_buscar").validate({
					messages: {
					 	campo_busqueda: ""
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
				
				<form id="form_login" style="DISPLAY: none;" method="post" action="">	<!-- TODO: Agregar mecanismo de login y setear action! -->
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
							<li class="popup_list_element shadow" <?php if ($logueado){ if($id_perfil==2){echo "style='margin-left: 80px;'";}}?>><a href="<?php echo base_url('') ?>"class="popup_list_cell_content">Home</a></li> 
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
			   		<?php if( isset($usuario) && ($usuario) != NULL){
			   				foreach ($usuario as $row) {?>
							
			   		
			   		
				   		<div class="center">
				   			<h1> Perfil de Usuario: <?php echo $row->username; ?> </h1>
							
							<p>
								Nombre: <?php echo $row->nombre; ?>
								<br />
								Apellidos: <?php echo $row->apellido1 . ' ' . $row->apellido2; ?>
								<br />										
								Email: <?php echo $row->email; ?>
								<br />										
						
							</p>
				   		</div>
				   		
						
						<nav id="perfil_artista_nav_header">
						</nav>
				   		
					   <div class="contenedor_panel_busqueda">	
								
							<div class="panel">
								<div class="panel_header">
									
									<h2>
										<span>
											Artistas Favoritos
										</span>
									</h2>
									
								</div> <!-- fin div panel_header -->
								<div class="body_panel">
									<?php 
										if(isset($favoritos) && count($favoritos) > 0){ 
											if (count($favoritos) > 2){
									?>
										<div class="contenedor_tabla_resultados_busq">
										<?php
											}else{
										?>
											<div class="contenedor_tabla_resultados_busq_sin_scroll">
										<?php
											}
										?>
											<table class="tabla_resultados_busq">
												<thead>
													<th>
														<h5>  </h5> 
													</th>
													<th>
														<h5> Nombre </h5> 
													</th>
													<th>
														<h5> Genero </h5> 
													</th>
													
													<th>
														<h5> Downloads </h5> 
													</th>
													<th>
														
													</th>
												</thead>
												<?php foreach($favoritos as $row2) { ?>
													<tr>
														<td class="col1 center">
															<a href="<?php echo site_url('browser/ver_profile_artista/' . $row2['id_artista'] ) ?>" class="img_busqueda"> <!-- TODO: Setear imagen del artista/album -->
																<img src="<?php echo site_url('imagenes/viewArtista/' . $row2['id_artista'] ); ?>" alt width="50">
															</a>
														</td>
														<td class="col2 center">
															<a href="<?php echo site_url('browser/ver_profile_artista/' . $row2['id_artista'] ) ?>" class="link">
															 	<?php echo  $row2['nombre_artista'] ?>
															</a> 
														</td>
														
														<td class="col2 center">
															<?php echo  $row2['genero_artista']  ?>
														</td>
														<td class="col2 center">
															<?php echo  $row2['cantidad_downloads']  ?>
														</td>
														<td class="col2 center">
															<a href="<?php echo site_url('gestor_perfiles/eliminarArtistaDeFavoritos/' . $row2['id_artista'] ) ?>" class="link">Eliminar</a>
														</td>
													</tr>																																									 
												<?php } ?>	
											</table>
										</div>	<!-- Fin div contenedor_tabla_resultados_busq	-->		 									 							
									<?php } else { ?>
										<div class="contenedor_tabla_resultados_busq_vacia">
											<table class="tabla_resultados_busq">
												<tr>										
													<td class="col2">  Aún no ha seleccionado un artista favorito <em></em></td>
												</tr>	
											</table>
										</div>
									<?php } ?>
									<div class="body_panel_footer">
										
									</div>
								</div> <!-- Fin div body panel -->
							
								<div class="panel_footer">
									<span></span>
								</div>						
							</div> <!--fin div panel  -->
						</div> <!-- fin div contenedor_panel_busqueda -->
					
						<?php if (isset($reviews) && $reviews != FALSE) { ?>
					
							<div class="center">
								<br />
						   		<h1> Reviews emitidos por <?php echo $row->username; ?>  </h1>
						   	</div>
						   	
						   	
						   	<?php foreach($reviews as $row3) { ?>
						   	
						   	
							   	<div class="contenedor_panel_busqueda">	
									
								<div class="panel">
									<div class="panel_header">
										
										<h2>
											<span>
												Album: <a href="<?php echo site_url('browser/ver_album/' . $row3['id_album'] ) ?>" class="link">
													<?php echo $row3['nombre_album'] ?></a>
												
												Emisión: <?php echo $row3['fecha_hora_review']  ?> 
											</span>
										</h2>
										
									</div> <!-- fin div panel_header -->
									<div class="body_panel">
										
										<!--<div class="contenedor_tabla_resultados_busq"> -->
											
										 <div class="contenedor_tabla_resultados_busq_sin_scroll"> 
											
												<table class="tabla_resultados_busq">
													<thead>
														<th>
															<h5>  </h5> 
														</th>
														
													</thead>
													<tbody>
														<tr>
															<td class="col1 ">
															   <p> <?php echo $row3['review']  ?> </p>
															</td>
														</tr>																																									 
													</tbody>
												</table>
											</div>	<!-- Fin div contenedor_tabla_resultados_busq	-->		 									 							
										
										
										<div class="body_panel_footer">
											
										</div>
									</div> <!-- Fin div body panel -->
								
									<div class="panel_footer">
										<span></span>
									</div>						
								</div> <!--fin div panel  -->
							</div> <!-- fin div contenedor_panel_busqueda -->
						   	
						<?php } ?>
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						   	
						
						<?php } else if ($reviews == FALSE) {?>
							<?php if ($session_data['id_perfil']!=1) {?>
								<div class="center">
							   		<h1> Aún no ha emitido ningún review  </h1>
							   	</div>
						   	<?php } ?>
						<?php } ?>
					
					
					<?php } ?>
					
					<?php } else { ?>
						<div class="center">
				   			<h1> El usuario indicado no existe </h1>
				   		</div>
					
					
					<?php } ?>			
				</div> 
				
				
				
			</div>
					
			<footer>
						
				<div id="contenido_footer">
					Todos los derechos reservados.
				</div>
				
			</footer>
			<!--<aside>
				Aside
			</aside> -->
			
			
		</div>
		
		
	</body>
</html>

<?php
 // if(isset($con) && $con != null){
 //	 mysql_close($con);
 // }
?>
