<?php 
session_start();

include ('config.php'); 
include ('dbc.php'); 

if ($_POST['Submit'] == 'Registrar')
{
   //if (strlen($_POST['email']) < 5)
   //{
    //die ("Incorrect email. Please enter valid email address..");
    //}
   if (strcmp($_POST['pass1'],$_POST['pass2']) || empty($_POST['pass1']) )
	{ 
	//die ("Password does not match");
	die("ERROR: Password does not match or empty..");
	}
	
	//Aqui nombre de usuario
	
	$rs_duplicates = mysql_query("select id from users where user_name='$_POST[nusuario]'");
	$duplicates = mysql_num_rows($rs_duplicates);
	
	if ($duplicates > 0)
	{	
	//die ("ERROR: User account already exists.");
	header("Location: register.php?msg=ERROR: Nombre de usuario ya existe..");
	exit();
	}
	
	//Fin nombre de usuario
	
	//Verificar email duplicado
	
	//$rs_duplicates = mysql_query("select id from users where user_email='$_POST[email]'");
	//$duplicates = mysql_num_rows($rs_duplicates);
	
	//if ($duplicates > 0)
	//{	
	//die ("ERROR: User account already exists.");
	//header("Location: register.php?msg=ERROR: User account already exists..");
	//exit();
	//}
	//Fin Verificar email duplicado
		
	$md5pass = md5($_POST['pass2']);
	$activ_code = rand(1000,9999);
	$server = $_SERVER['HTTP_HOST'];
	//$host = ereg_replace('www.','',$server);
	$host = str_ireplace('www.','',$server);
	mysql_query("INSERT INTO users
	              (`user_name`,`user_pwd`,`country`,`joined`,`activation_code`,`full_name`,`user_activated`)
				  VALUES
				  ('$_POST[nusuario]','$md5pass','$_POST[country]',now(),'$activ_code','$_POST[full_name]','1')") or die(mysql_error());
	
	$message = 
"Thank you for registering an account with $server. Here are the login details...\n
Gracias por registrarse en $server. Este es el detalle de sus datos ...\n\n


Email: $_POST[nusuario] \n
Clave: $_POST[pass2] \n

______________________________________________________________
Thank you. This is an automated response. PLEASE DO NOT REPLY.
Gracias, esta es una respuesta autom�tica, favor no responda.
";

	mail($_POST['nusuario'], "Registro de usuario", $message, "From: Sistema de Facturación <administrador@$host>\r\n" );
	unset($_SESSION['ckey']);
	echo("Se ha registrado exitosamente!");	
	exit; }
?> 
<link href="styles.css" rel="stylesheet" type="text/css">
<?php if (isset($_GET['msg'])) { echo "<div class=\"msg\"> $_GET[msg] </div>"; } ?>
<p>&nbsp;</p>


<table width="50%" border="1" align="Center" cellpadding="5" cellspacing="0">
<form name="form1" method="post" action="register.php" style="padding:5px;">
  <tr> 
    <td bgcolor="B40404" align="Center" class="mnuheader" colspan="2"><strong><font size="5" color="white" face="arial">Registro de Usuarios</font></strong></td>
  
  

  <tr> 
		<td bgcolor="E6E6E6" class="forumposts">
        <p>
          Ingrese Nombre: 
		</td>
		<td bgcolor="ffffff" class="forumposts">	
          <input name="full_name" type="text" id="full_name">
          Ej. Paolo Guerrero</p>
		</td>	
  </tr>
  <tr>
		<td bgcolor="E6E6E6" class="forumposts">
		<p>
		Ingrese Usuario: 
		</td>	
		<td bgcolor="ffffff" class="forumposts">
          <input name="nusuario" type="text" id="nusuario">
          Ej. pguerrero</p>
		</td>
   </tr>
  <!-- <tr> 
		<td bgcolor="E6E6E6" class="forumposts">
        <p>Ingrese E-mail: 
		</td>
		<td bgcolor="E6E6E6" class="forumposts">
          <input name="email" type="text" id="email">
          Ej. dguerrero343@gmail.com</p>
		</td>  
	</tr>	-->
	<tr>
		<td bgcolor="E6E6E6" class="forumposts">
        <p>Ingrese Clave: 
		</td>
		<td bgcolor="ffffff" class="forumposts">
          <input name="pass1" type="password" id="pass1">
          Minimo 5 caracteres</p>
		</td>  
	</tr>	
	<tr>
		<td bgcolor="E6E6E6" class="forumposts">	  
        <p>Repita la Clave: 
		</td>
		<td bgcolor="ffffff" class="forumposts">
          <input name="pass2" type="password" id="pass2">
		  Vuelva escribir</p>
		</td>  
	<tr>
		<td bgcolor="E6E6E6" class="forumposts">	
        <p>Seleccione rol: 
		</td>
		<td bgcolor="ffffff" class="forumposts">
		
          <select name="country" id="select8" style="width:100px;border:1px solid #2E2E2E;background-color:#1C1C1C;color:#ffffff;font-size:18px" onchange="this.style.width=200">
		    <option value=2>Vendedor</option>
            <option value=1>Admin</option>
          </select></p>
		 
		  </td>
	<tr>
		<td bgcolor="E6E6E6" class="forumposts">	  
        <p>Ingrese Codigo de Seguridad: 
		</td>
		<td bgcolor="ffffff" class="forumposts">
          <input name="user_code" type="text" height="20" size="20" id="user_code">
          <img src="pngimg.php" height="30" align="top"> </p>
		 </td> 
	</tr>
	<tr>
	    <td bgcolor="E6E6E6" class="forumposts" colspan="2">
        <p align="center"> 
          <input type="submit" name="Submit" value="Registrar" style="width:100px;border:3px solid #848484;background-color:#848484;color:#ffffff;">
        </p>
      
	  </td>
  </tr>
</form>
</table>

<div align="left"></div>
</body>
</html>