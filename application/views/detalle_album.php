<!DOCTYPE html>	
<html>
	<head>
		<!--[if IE]>
      	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    	<meta charset="utf-8"/>
       	<link href='http://fonts.googleapis.com/css?family=Muli:300' rel='stylesheet' type='text/css'>
		<link href="<?php echo base_url(); ?>css/estilo.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>css/jquery.rating.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="<?php echo base_url('js/jquery-1.8.3.min.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/jquery.validate.min.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/input_header.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/jquery.rating.pack.js') ?>"></script>
		<script type="text/javascript">

		 	
			$(document).ready(function(){
				$("#form_buscar").validate({
					messages: {
					 	campo_busqueda: ""
					}
				});
				
				$("#review_album").validate({
					rules: {
						textarea_review: "required"
					},
					messages: {
					 	textarea_review: "Su review debe contener texto"
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
				
				$('#rating_promedio:radio.star').rating();
				$valor = <?php foreach ( $datos_album as $row); echo floor($row['rating']); ?>;
				$valor = $valor - 1;
				$('input.promedio', document.getElementById('#rating_promedio')).rating('select', $valor);
				$('input.promedio', document.getElementById('#rating_promedio')).rating('disable');
				
				$valor = <?php echo floor($voto); ?>;
				$valor = $valor - 1;
				if ($valor != -1){
					$('input.voto', document.getElementById('#rating_a_enviar')).rating('select', $valor);
				}
				else {
					$('input.voto', document.getElementById('#rating_a_enviar')).rating('select', 3);
				}
				
				/*$('#rating_a_enviar :radio.star').rating();*/
				/*$('input', document.getElementById('#rating_a_enviar')).rating('enable');*/
				
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
				
				<!-- Esta validacion se agrego para el login -->
														
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
					
			   		<?php if( isset($datos_album) && ($datos_album) != NULL && 
			   				( isset($id_artista) ) && ( ($id_artista) != NULL ) &&
			   				( isset($nombre_artista) ) && ( ($nombre_artista) != NULL )   ){
			   			foreach ($datos_album as $row) {?>
			   			
			   		
			   		
				   		<div class="center">
				   			<h1> Detalle de Album </h1> 
							
				   		</div>
				   		
						<div id="empty_row" >
							
						</div>
						
						<nav id="perfil_artista_nav_header">
							<h2> <?php echo $row['nombre_album']; ?> </h2>
							
							<?php if(isset($session_data['id_perfil']) && $session_data['id_perfil']==1){
								//Si el usuario es administrador, permitir que edite el album si lo desea. ?>
								<span class="admin_edit"> <a href="<?php echo site_url('admin_edit/updateAlbum/' .$id_artista.'/' . $row['id_album'] ); ?>"> Editar album </a> </span>
								<br />						
								<span class="admin_edit"> <a href="<?php echo site_url('admin_edit/deleteAlbum/' .$id_artista.'/' . $row['id_album'] ); ?>"> Eliminar album </a> </span>
								<br />
								<span class="admin_edit"> <a href="<?php echo site_url('admin_edit/insertCancion/' . $row['id_album'] ); ?>"> Agregar canción </a> </span>
								
							<?php } ?>
							
							<a href="<?php echo site_url('browser/ver_album/' . $row['id_album'] ) ?>" class="img_busqueda "> <!-- TODO: Setear imagen del artista/album -->
								<img src="<?php echo site_url('imagenes/viewAlbum/' . $row['id_album'] ); ?>" alt width="50">
							</a>
							<h4>
								Artista:  
								<a id="detalle_album_link_al_artista" class="link"  href="<?php echo site_url('browser/ver_profile_artista/' . $id_artista ) ?>" >
									<?php echo  $nombre_artista ?>
								</a> 
							</h4>
							<h5>Genero: <?php echo  $row['genero_album'] ?> </h5>
							<h5>Fecha de publicación: <?php echo  $row['fecha_publicacion'] ?> </h5>
							<h5>
								Rating: 
							</h5>
							<table id="perfil_artista_tabla_contenedora_rating">
								<tr>
									<td>
										<form id="rating_promedio">
											<input name="star1" type="radio" class="star promedio"  value=""/>
											<input name="star1" type="radio" class="star promedio"  value=""/>
											<input name="star1" type="radio" class="star promedio"  value=""/>
											<input name="star1" type="radio" class="star promedio"  value=""/>
											<input name="star1" type="radio" class="star promedio"  value=""/>
											
										</form>
									</td>
								</tr>
							</table>
							<h5> Votos:	</h5> 
							<h5> <?php echo  $row['cantidad_votos'] ?> </h5> 
							<?php if($logueado && ($id_perfil == 2) ) {?> 
								<table id="perfil_artista_tabla_contenedora_rating">
									<tr>
										<td>
											<form id="rating_a_enviar" method="get" action="<?=site_url('gestor_ratings_reviews/calificarAlbum')?>">
												<input name="star2" type="radio" class="star voto"  value="1"/>
												<input name="star2" type="radio" class="star voto"  value="2"/>
												<input name="star2" type="radio" class="star voto"  value="3"/>
												<input name="star2" type="radio" class="star voto"  value="4"/>
												<input name="star2" type="radio" class="star voto"  value="5"/>
												<br />
												
												<input name="id_album" type="hidden" style="DISPLAY: none;" class=""  value="<?php echo $row['id_album'] ?>"/>
												<?php $session_data['id_album'] = $row['id_album'] ?>
												
												<input class="detalle_album_boton_descarga" type="submit" onclick="" value="Califícalo!"/>
											</form>
										</td>
									</tr>
								</table>
								
								<?php if(isset($mensaje) ) {?> 
									<br />
									<h2> <?php echo $mensaje; ?> </h2>
								<?php } ?>
								
							<?php }?>
							
						</nav>
				   		
					   <div class="contenedor_panel_busqueda">	
								
							<div class="panel">
								<div class="panel_header">
									
									<h2>
										<span>
											Tracklist de <?php echo $row['nombre_album']; ?>
										</span>
									</h2>
									
								</div> <!-- fin div panel_header -->
								<div class="body_panel">
									<?php 
										if(isset($canciones) && count($canciones) > 0){ 
											if (count($canciones) > 5){
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
														<h5> Num. Track </h5> 
													</th>
													<th>
														<h5> Titulo </h5> 
													</th>
													<th>
														<h5> Duración </h5> 
													</th>
													<th>
														<h5> Preview </h5> 
													</th>
													<th>
														<h5> Cantidad de Downloads </h5> 
													</th>
													
													<th>
														<h5> 
														<?php 
															if ($logueado){
														?>
														
															<a class="link" href="<?php echo site_url("downloader/descargarAlbum/" .  $row['id_album'] . "/" . $row['nombre_album']);?>">Descargar todo</a>
															
														
														<?php 
															}
														?>																											
														
														
														</h5> 
													</th>
												</thead>
												<?php foreach($canciones as $row2) { ?>
													<tr>
														<td class="col2 center">
															<?php echo  $row2['numero_track'] ?>
														</td>
														<td class="col2 center">
															<?php echo  $row2['nombre_cancion'] ?>
														</td>
														<td class="col2 center">
															<?php echo  $row2['duracion'] ?>
														</td>
														<td class="col2 center">
															
															<a href="<?php echo base_url('songs/' . $row2['id_cancion'].'.mp3') ?>" class="link" >
																Play
															</a>
														</td>
														
														<td class="col2 center">
															<?php echo  $row2['cantidad_downloads']  ?>
														</td>
														<?php 
															if ($logueado){
														?>
														<td class="col2 center">
															<a class="detalle_album_boton_descarga" href="<?php echo site_url("downloader/descargarCancion/" .  $row2['id_cancion'] . "/" . $row2['nombre_cancion'] . "/" . $row2['id_album']);?>">Descargar</a>
														</td>
														<?php 
																}
														?>
															
													</tr>																																									 
												<?php } ?>	
											</table>
										</div>	<!-- Fin div contenedor_tabla_resultados_busq	-->		 									 							
									<?php } else { ?>
										<div class="contenedor_tabla_resultados_busq_vacia">
											<table class="tabla_resultados_busq">
												<tr>										
													<td class="col2">  Aún no existen canciones asociadas a este álbum <em></em></td>
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
				
					<?php }  ?>
						
					<?php } else { ?>
						<div class="center">
				   			<h1> El álbum indicado no existe </h1>
				   		</div>
					<?php } ?>
								
				</div> 
				
				
				
				<!--contenedor de reviews del album-->
				<?php if(isset($reviews) && ($reviews != FALSE) ) {?> 
					<div class="contenedor_panel_busqueda">	
								
							<div class="panel">
								<div class="panel_header">
									
									<h2>
										<span>
											Reviews
										</span>
									</h2>
									
								</div> <!-- fin div panel_header -->
								<div class="body_panel">
									<?php if (count($reviews) > 5){ ?>
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
														<h5> Username </h5> 
													</th>
													<th>
														<h5> Fecha emisión </h5> 
													</th>
													<th>
														<h5> Review </h5> 
													</th>
												</thead>
												<tbody>
													<?php foreach($reviews as $row3) { ?>
														<tr>
															<td class="col2 center">
																<?php echo  $row3['username'] ?>
															</td>
															<td class="col2 center">
																<?php echo  $row3['fecha_hora_review'] ?>
															</td>
															<td class="col2 review_panel">
																<?php echo  $row3['review'] ?>
															</td>
														</tr>																																									 
													<?php } ?>	
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
					

				<?php } else if (isset ($session_data['id_album'])){?>
					<div class="center">
			   			<h1> Aún no se han ingresado reviews para este álbum</h1>
			   			<br />
			   		</div>
				<?php }?>
				<!--FIN contenedor de reviews del album-->
				
				
				<!--contenedor del comentario de review-->
				<?php if($logueado && ($id_perfil == 2) && isset ($session_data['id_album'])) {?> 
					<div class="center">
						<h2>
							<?php if(isset($hizoReview) && ($hizoReview != NULL) && ($hizoReview == TRUE)) {?> 
								<span>
									¡Modifica tu review de este álbum!
								</span>
							<?php } else {?>
								<span>
									¡Crea tu propio review de este álbum!
								</span>
							<?php }?>
						</h2>
						<form id="review_album" method="post" action="<?=site_url('gestor_ratings_reviews/darReviewAlbum')?>">
							<div>
								<textarea id='textarea_review' name="textarea_review" > </textarea>
							</div>
							
							<input name="id_album" type="hidden" style="DISPLAY: none;" class=""  value="<?php if(isset ($session_data['id_album']) ) { echo $session_data['id_album']; }?>"/>
							<input name="id_usuario" type="hidden" style="DISPLAY: none;" class=""  value="<?php if(isset ($session_data['id_usuario']) ) { echo $session_data['id_usuario']; }?>"/>
							
							<br />
							<input class="detalle_album_boton_descarga" type="submit" onclick="" value="Enviar Review"/>
						</form>
					</div>
				<?php }?>
				<!--FIN contenedor del comentario de review-->
				
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
	<script src="http://mediaplayer.yahoo.com/js"></script> 
</html>

<?php
 // if(isset($con) && $con != null){
 //	 mysql_close($con);
 // }
?>
