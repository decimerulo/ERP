<?php

include ("../conectar.php");



$cadena_busqueda=$_GET["cadena_busqueda"];



if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }



if ($cadena_busqueda<>"") {

	$array_cadena_busqueda=split("~",$cadena_busqueda);

	$codproveedor=$array_cadena_busqueda[1];

	$nombre=$array_cadena_busqueda[2];

	$nif=$array_cadena_busqueda[3];

	$provincia=$array_cadena_busqueda[4];

	$localidad=$array_cadena_busqueda[5];

	$telefono=$array_cadena_busqueda[6];

} else {

	$codproveedor="";

	$nombre="";

	$nif="";

	$provincia="";

	$localidad="";

	$telefono="";

}



?>

<html>

	<head>

		<title>Proveedores</title>

		<link href="../../maxcodeka/estilos/estilos.css" type="text/css" rel="stylesheet">

		<script language="javascript">

		

		function inicio() {

			document.getElementById("form_busqueda").submit();

		}

		

		function nuevo_proveedor() {

			location.href="nuevo_proveedor.php";

		}

		

		var cursor;

		if (document.all) {

		// Está utilizando EXPLORER

		cursor='hand';

		} else {

		// Está utilizando MOZILLA/NETSCAPE

		cursor='pointer';

		}

		

		function imprimir() {

			var codproveedor=document.getElementById("codproveedor").value;

			var nombre=document.getElementById("nombre").value;

			var nif=document.getElementById("nif").value;			

			var provincia=document.getElementById("codprovincia").value;

			var localidad=document.getElementById("localidad").value;

			var telefono=document.getElementById("telefono").value;

			window.open("../fpdf/proveedores.php?codproveedor="+codproveedor+"&nombre="+nombre+"&nif="+nif+"&provincia="+provincia+"&localidad="+localidad+"&telefono="+telefono);

		}

		

		function buscar() {

			var cadena;

			cadena=hacer_cadena_busqueda();

			document.getElementById("cadena_busqueda").value=cadena;

			if (document.getElementById("iniciopagina").value=="") {

				document.getElementById("iniciopagina").value=1;

			} else {

				document.getElementById("iniciopagina").value=document.getElementById("paginas").value;

			}

			document.getElementById("form_busqueda").submit();

		}

		

		function paginar() {

			document.getElementById("iniciopagina").value=document.getElementById("paginas").value;

			document.getElementById("form_busqueda").submit();

		}

		

		function hacer_cadena_busqueda() {

			var codproveedor=document.getElementById("codproveedor").value;

			var nombre=document.getElementById("nombre").value;

			var nif=document.getElementById("nif").value;			

			var provincia=document.getElementById("cboProvincias").value;

			var localidad=document.getElementById("localidad").value;

			var telefono=document.getElementById("telefono").value;

			var cadena="";

			cadena="~"+codproveedor+"~"+nombre+"~"+nif+"~"+provincia+"~"+localidad+"~"+telefono+"~";

			return cadena;

			}

			

		function limpiar() {

			document.getElementById("form_busqueda").reset();

		}

		

		var miPopup

		function abreVentana(){

			miPopup = window.open("ventana_proveedores.php","miwin","width=700,height=380,scrollbars=yes");

			miPopup.focus();

		}

		

		function validarproveedor(){

			var codigo=document.getElementById("codproveedor").value;

			miPopup = window.open("comprobarproveedor.php?codproveedor="+codigo,"frame_datos","width=700,height=80,scrollbars=yes");



		}



		</script>

	</head>

	<body onLoad="inicio()">

		<div id="pagina">

			<div id="zonaContenido">

			<div align="center">

				<div id="tituloForm" class="header">BUSCAR PROVEEDOR </div>

				<div id="frmBusqueda">

				<form id="form_busqueda" name="form_busqueda" method="post" action="../../maxcodeka/proveedores/rejilla.php" target="frame_rejilla">

					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>					

						<tr>

							<td width="16%">RPC / RUC </td>

							<td width="68%"><input id="nif" type="text" class="cajaPequena" NAME="nif" maxlength="10" value="<? echo $nif?>">  <img src="../../maxcodeka/img/ver.png" width="16" height="16" onClick="abreVentana()" title="Buscar proveedor" onMouseOver="style.cursor=cursor"> <img src="../../maxcodeka/img/cliente.png" width="16" height="16" onClick="validarproveedor()" title="Validar proveedor" onMouseOver="style.cursor=cursor"></td>

							<td width="5%">&nbsp;</td>

							<td width="5%">&nbsp;</td>

							<td width="6%" align="right"></td>

						</tr>

	

					</table>

			  </div>

			 	<div id="botonBusqueda"><img src="../../maxcodeka/img/botonbuscar.jpg" width="69" height="22" border="1" onClick="buscar()" onMouseOver="style.cursor=cursor">

			 	  <img src="../../maxcodeka/img/botonlimpiar.jpg" width="69" height="22" border="1" onClick="limpiar()" onMouseOver="style.cursor=cursor">

					<img src="../../maxcodeka/img/botonnuevoproveedor.jpg" width="130" height="22" border="1" onClick="nuevo_proveedor()" onMouseOver="style.cursor=cursor">

					<img src="../../maxcodeka/img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir()" onMouseOver="style.cursor=cursor">

				  </div>

			  <div id="lineaResultado">

			  <table class="fuente8" width="80%" cellspacing=0 cellpadding=3 border=0>

			  	<tr>

				<td width="50%" align="left">N de proveedores encontrados <input id="filas" type="text" class="cajaPequena" NAME="filas" maxlength="5" readonly></td>

				<td width="50%" align="right">Mostrados <select name="paginas" id="paginas" onChange="paginar()">

		          </select></td>

			  </table>

				</div>

				<div id="cabeceraResultado" class="header">

					RELACI&Oacute;N DE PROVEEDORES </div>

				<div id="frmResultado">

				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">

						<tr class="cabeceraTabla">

							<td width="8%">ITEM</td>

							<td width="6%">CODIGO</td>

							<td width="38%">NOMBRE </td>

							<td width="13%">RUC</td>

							<td width="19%">TELEFONO</td>

							<td width="5%">&nbsp;</td>

							<td width="5%">&nbsp;</td>

							<td width="6%">&nbsp;</td>

						</tr>

				</table>

				</div>

				<input type="hidden" id="iniciopagina" name="iniciopagina">

				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">

			</form>

				<div id="lineaResultado_pagos">
				  <iframe width="100%" height="250" id="frame_rejilla" name="frame_rejilla" frameborder="0">
                  <ilayer width="100%" height="250" id="frame_rejilla" name="frame_rejilla"></ilayer>
				  </iframe>
				  <iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">

					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>

				  </iframe>

			  </div>

			</div>

		  </div>			

		</div>

	</body>

</html>

