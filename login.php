<?php 
include 'dbc.php';

$user_email = mysql_real_escape_string($_POST['email']);

if ($_POST['Submit']=='Aceptar')
{
	$md5pass = md5($_POST['pwd']);
	$sql = "SELECT id,user_name,country FROM users WHERE 
            user_name = '$user_email' AND 
            user_pwd = '$md5pass' AND country<>'' AND user_activated='1'"; 
			
	$result = mysql_query($sql) or die (mysql_error()); 
	$num = mysql_num_rows($result);
	
    if ( $num != 0 ) { 

       // A matching row was found - the user is authenticated. 
       session_start(); 
	   list($user_id,$user_name,$country) = mysql_fetch_row($result);
	   
		$_SESSION['user']= $user_name; 
		$_SESSION['rol']= $country;
		$_SESSION['estado']= TRUE;  // se crea para identificar si esta logueado ..daniel
					
		if (isset($_GET['ret']) && !empty($_GET['ret']))
		{
			header("Location: $_GET[ret]");
		} 
		else
		{
		    header("Location: index2.php");
	   	}
		echo "Logged in...";
		exit();
    } 

	header("Location: login.php?msg= Datos incorrectos|Verifique");
	//echo "Error:";
	exit();		
}

?>

<style>
.sombra {
   float:left;
   background-color: #A7A7A7;
   margin: 10px 0 0 5px;
}

.sombra img {
   display: block;
   position: relative;
  background-color: #fff;
  margin: -6px 6px 6px -6px;
}
</style>

<link href="styles.css" rel="stylesheet" type="text/css">
<?php if (isset($_GET['msg'])) { echo "<div class=\"msg\"> $_GET[msg] </div>"; } ?>
<center>
<p>&nbsp;</p>
<div id="caja4" align="center" ><font size="7" color="#FE2E2E"  style="text-shadow: 0.1em 0.1em 0.2em gray"><strong>Sistema Negocio</strong></font>
<center>
	<img src="img/menu/vicky.png"  width="120" height="120" border="0">
	</center>

<form name="form1" method="post" action="">
<table  width="40%" height="230" border="0" align="center" cellpadding="8" cellspacing="0">
	<!--<tr> 
			<td  class="mnuheader" colspan="2">
				<div id="caja" align="center" ><font size="6" color="white"><strong>Ingresar al Sistema</strong></font></div>
			</td>
	<tr>
	<td colspan="2">
	<center>
	<img src="img/menu/vicky.png"  width="120" height="120" border="0">
	</center>
	</td>
	</tr>-->
  	<tr>	 			
				
			<td bgcolor="FFFFFF" class="mnuheader">
				<div id="caja1" align="center" ><font size="5" color="white"><strong>Usuario</strong></font></div>
			</td>
			<td bgcolor="FFFFFF" class="mnuheader">
				<div id="caja2" align="center" ><font size="7" color="white"><strong><input name="email" type="text" id="email" style="width:150px;height:35px;background-color:#FFFFFF;border-width:thin;border-style:solid;border-color:#ffffff;color:#1C1C1C;font-size:20pt;font-family:Verdana;font-weight:bold;"></strong></font></div>
				
			</td>
	</tr>
	<tr>
			<td bgcolor="FFFFFF" class="mnuheader">
				<div id="caja1" align="center" ><font size="5" color="white"><strong>Clave</strong></font></div>
			</td>	
			<td bgcolor="FFFFFF" class="mnuheader">
				
				<div id="caja2" align="center" ><font size="5" color="white"><strong><input name="pwd" type="password" id="pwd" style="width:150px;height:35px;background-color:#FFFFFF;border-width:thin;border-style:solid;border-color:#FFFFFF;color:#1C1C1C;font-size:20pt;font-family:Verdana;font-weight:bold;"> </strong></font></div>
				
			</td>	
	</tr>
	<tr>
			<td bgcolor="FFFFFF" class="mnuheader" colspan="2" height="100">
				
				<center><div id="caja3" align="center" ><font size="7" color="white"><strong><input type="submit" name="Submit" value="Aceptar" style="width:150px;height:43px;background-color:#B40404;border-width:thin;border-style:solid;border-color:#B40404;color:#ffffff;font-size:25pt;font-family:Verdana;font-weight:bold;" ></strong></font></div></center>
				
			</td>
	</tr>	
		<!--<tr>
			<td bgcolor="FFFFFF" class="mnuheader" colspan="2">	
				<p align="center">
				<a href="register.php">Registrarse</a> | <a href="forgot.php">Olvide la Clave</a>
				</p>
			</td>
        </tr>-->		
	</form>
	
  
</table>

</div>
</center>	
