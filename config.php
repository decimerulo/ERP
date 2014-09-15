<?php

ob_start();

// Variables para la conexión

global $Usuario;                     /*root*/

global $Password;

global $Servidor;                    /*localhost*/

global $BaseDeDatos;

$Usuario="root";        /* nombre de usuario de la base de datos */

$Password="";               /* Contraseña de la base de datos */

$Servidor="localhost";              /* Servidor , generalmente localhost*/

$BaseDeDatos="sisventas";   /* Nombre de la base de datos */

$Passwordtabla ="margarita";

$usuarios_sesion="factu";

$sql_tabla="user_list";

ob_end_clean();

?>