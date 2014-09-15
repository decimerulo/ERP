<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php 
include ("../conectar.php");
?> 

<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title>CodeKa Mx, Reloj digital</title>
 <link rel="stylesheet"    type= "text/css"  href="../960gs/code/css/reset.css" />
 <link rel="stylesheet"    type= "text/css"  href="../960gs/code/css/text.css" />
 <link rel="stylesheet"    type= "text/css"  href="../960gs/code/css/960.css" />
 <link rel="stylesheet"    type= "text/css"  />
 
<style type="text/css">
BODY{
   background-color:#FFFFFF;
   color: #000000;
   font-family: verdana, arial, helvetica;
}

p{
   border: 1px solid #999;
   padding: 5px;
   margin: 0px;
}
#cabecera{
   background-color: #00FF66;
   background-image:url(imagenes/header2.jpg)
}
#cabecera01{
   background-color: #ccffcc;
}
#izq{
   background-color: #e0e0ff;
}
#izq1{
   background-color: #ccccff;
}

#izq3{
   background-color: #ccccff;
}

#pie{
   background-color: #ff8800;
}
#icono1{
   background-color: #CCCCCC;
}
</style> 

<script type="text/javascript">

// FECHA
function MostrarFechaActual()    {
var nombre_dia = new Array("domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado")
var nombre_mes = new Array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre")

var hoy_es = new Date()
dia_mes = hoy_es.getDate()
dia_semana = hoy_es.getDay()
mes = hoy_es.getMonth() + 1
anyo = hoy_es.getYear()

if (anyo < 100) anyo = '19' + anyo
else if ( ( anyo > 100 ) && ( anyo < 999 ) ) { var cadena_anyo = new String(anyo) ; anyo = '20' + cadena_anyo.substring(1,3)    }

document.write(nombre_dia[dia_semana] + ",<br> " + dia_mes + " de " + nombre_mes[mes - 1] + " de " + anyo)    }

//  RELOJ 24 HORAS
var Reloj24H = null
var RelojEnMarcha = false

function DetenerReloj24 () {if(RelojEnMarcha)    clearTimeout(Reloj24H); RelojEnMarcha = false }

function MostrarHoraActual() {
   	var ahora = new Date()
   	var hora = ahora.getHours()
   	var minuto = ahora.getMinutes()
   	var segundo = ahora.getSeconds()
   	var HHMMSS
       
if (hora < 10) {HHMMSS = "0" + hora} else {HHMMSS = " " + hora};
if (minuto < 10) {HHMMSS += ":0" + minuto} else {HHMMSS += ":" + minuto};
if (segundo < 10) {HHMMSS += ":0" + segundo} else {HHMMSS += ":" + segundo}
        
document.Reloj24H.digitos.value = HHMMSS;
Reloj24H = setTimeout("MostrarHoraActual()",1000)
RelojEnMarcha = true }

function IniciarReloj24() {
 	DetenerReloj24()
 	MostrarHoraActual() }
// final -->
</script>

 </head>

  <body onload="IniciarReloj24()">
 
<div align="center">
 
  <div style="width:870px; margin:0 0 0 10px;">
   
     <div style="border-bottom:1px solid #4FCC18; padding:6px;" align="left">
     	Reloj Virtual
     </div>
	      
	<div align="center">
     <script type="text/javascript">MostrarFechaActual()</script>
     <form name="Reloj24H" action="">
     	<input style="color:navy; text-align:center; font-size:38pt; font-weight:bold" type="text" size="18" name="digitos" value="  ">
     </form>
	 </div>
	 
     <div style="border-top:1px solid #4FCC18; margin:30px 0 0 0; padding:6px;" align="right">
     	<a href="../central2.php">Volver</a>
     </div>	 
	    	 
  </div>

</div>

  </body>
  </html>