<?php
//var_dump (is_file(realpath(dirname(__FILE__)).'/DiasHorasAmdin.php'));die;
include_once(realpath(dirname(__FILE__)).'/DiasHorasAmdin.php');

class ValidarModulo
{
    public function ValidarIngresoModulo($user, $ip, $modulo)
    {
        
        $C_ValidarFecha = new AdminDiasHorasssss(); 
        $t = $C_ValidarFecha->ingresoFecha($user, $ip, $modulo);
               
        if($t == true)
        {
            $mensaje = "<strong>La funci�n a la que est� intentando ingresar no se encuentra activa temporalmente, por favor int�ntelo durante los d�as y horas de oficina.</strong>";
            return $mensaje; 
        }
    } 
}


?>