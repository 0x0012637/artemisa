<?
/**
 * Esta pagina permitirá hacer el download de los archivos. Los administradores podrán bajarse cualquier archivo,
 * sin restricción alguna. Para los usuarios comunes, descontará de la cuenta corriente el monto corresopondiente 
 * por el download. 
 * @param string $codigo_archivo el id del archivo que se va a bajar
 */
 
require_once "../common/includes.php";
SessionHandler::validar_nivel_acceso(ROL__USUARIO);
$usuario = SessionHandler :: getUsuario();
$rol_usuario = SessionHandler :: getRolUsuario();
$pageName = "downloads"; 
$Mensajes = Comienzo($pageName, $IdiomaSitio);
$archivo = $servicesFacade->getArchivoPedido($codigo_archivo);

if (!empty ($archivo) && (($rol_usuario == ROL__ADMINISTADOR) || ($archivo["Permitir_Download"] == 1))) {
	//para que se pueda bajar un archivo, el usuario tiene que tener permiso o ser un administrador
	
	$directorio = Configuracion::getDirectorioUploads();
	if (is_a($directorio, "File_Exception")){
    		return $directorio;
    	 }
	
	$filename = $archivo["nombre_archivo"];
	$pathCompleto = $directorio . "/" . $filename;
	
	if (file_exists($pathCompleto)){
		$size = filesize($pathCompleto);
		header("Content-type: application/pdf");
		header("Content-Disposition:attachment; filename= $filename");
		header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Cache-Control: public");
		header("Accept-Ranges: bytes");
		header("Content-Length:$size");
		@ readfile($pathCompleto);
	
		//ver q pasa si el usuario cancela la descarga
		$res = $servicesFacade->registrarDescargaArchivoPedido($archivo, $usuario["Id"], $rol_usuario);
		if (is_a($res, "Celsius_Exception")) {
			$mensaje_error = $Mensajes["error.registrarDescarga"];;
			$excepcion = $res;
			require "../common/mostrar_error.php";
		}
	}
	else{
		$mensaje_error= $Mensajes["error.archivoInexistente"];
		$excepcion = $res= new File_Exception($mensaje_error);
		require "../common/mostrar_error.php";
	}
	
}else 
{
	require "../layouts/top_layout_admin.php";
	?>
	<table bgcolor='#B7CFE1' align='center' width='70%' cellspacing=0>
	<tr height="40">
		<td bgcolor='#B7CFE1' valign='middle' align='center' colspan='2'>
			<font face='MS Sans Serif' size='1' color='#333399'>
				<? if (empty ($archivo)){
					//el archivo no existe
					echo $Mensajes["warning.archivosNoExisten"];
				}else{
					//el usuario ya bajo el archivo
					echo $Mensajes['err-1'];
				} ?>
				</font>
			</td>
		</tr>
	</table>
	<? require "../layouts/base_layout_admin.php"; 
}
?>