<?php
include ("../conectar.php");
include ("../funciones/fechas.php");

$codcliente=$_POST["codcliente"];
$nombre=$_POST["nombre"];
$numfactura=$_POST["numfactura"];
$estado=$_POST["cboEstados"];
$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }
$remito=$_POST["remito"];
$numfactura2=$_POST["numfactura2"];

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND eefacturas.codcliente='$codcliente'"; }
if ($remito <> "") { $where.=" AND eefacturas.remito='$remito'"; }
if ($numfactura2 <> "") { $where.=" AND eefacturas.numfactura='$numfactura2'"; }
if ($nombre <> "") { $where.=" AND clientes.nombre like '%".$nombre."%'"; }
if ($numfactura <> "") { $where.=" AND bbcodfactura='$numfactura'"; }
if ($estado > "0") { $where.=" AND estado='$estado'"; }
if (($fechainicio<>"") and ($fechafin<>"")) {
	$where.=" AND fecha between '".$fechainicio."' AND '".$fechafin."'";
} else {
	if ($fechainicio<>"") {
		$where.=" and fecha>='".$fechainicio."'";
	} else {
		if ($fechafin<>"") {
			$where.=" and fecha<='".$fechafin."'";
		}
	}
}

$where.=" ORDER BY bbcodfactura DESC";
$query_busqueda="SELECT count(*) as filas FROM eefacturas,clientes WHERE eefacturas.borrado=0 AND eefacturas.codcliente=clientes.codcliente AND ".$where;
$rs_busqueda=mysql_query($query_busqueda);
$filas=mysql_result($rs_busqueda,0,"filas");

$query_busqueda="SELECT * FROM eefacturas,clientes WHERE eefacturas.borrado=0 AND eefacturas.estado=1 AND eefacturas.codcliente=clientes.codcliente AND ".$where;
$rs_busqueda=mysql_query($query_busqueda);

$totalfacturas=0;
while($row=mysql_fetch_array($rs_busqueda, MYSQL_ASSOC)){
	$totalfacturas+=$row["totalfactura"];
}

?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		colorfondo;
		
		function ver_factura(bbcodfactura) {
			parent.location.href="ver_factura.php?bbcodfactura=" + bbcodfactura + "&cadena_busqueda=<? echo $cadena_busqueda?>";
		}
		
		function modificar_factura(bbcodfactura,marcaestado) {
			if (marcaestado==1) {
				parent.location.href="modificar_factura.php?bbcodfactura=" + bbcodfactura + "&cadena_busqueda=<? echo $cadena_busqueda?>";
			} else {
				alert ("No puede modificar una Boleta ya pagada.");
			}
		}
		
		function eliminar_factura(bbcodfactura) {
			if (confirm("Atencion va a proceder a la eliminacion de una Boleta. Desea continuar?")) {
				parent.location.href="eliminar_factura.php?bbcodfactura=" + bbcodfactura + "&cadena_busqueda=<? echo $cadena_busqueda?>";
			}
		}

		function inicio() {
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			if (indi>numfilas) { 
				indi=1; 
			}
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.totalfacturas.value=document.getElementById("totalfacturas").value;
			parent.document.form_busqueda.paginas.innerHTML="";		
			while (contador<=numfilas) {
				texto=contador + "-" + parseInt(contador+9);
				if (indi==contador) {
					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.form_busqueda.paginas.options[indice].selected=true;
				} else {
					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
				}
				indice++;
				contador=contador+10;
			}
		}
		
		function CambiaColor(esto,fondo,texto)
		{
			colorfondo=esto.style.background;
			esto.style.background=fondo;
			esto.style.color=texto;
			return colorfondo;
		}
		
		function CambiaColor2(esto,texto)
		{
			esto.style.background=colorfondo;
			esto.style.color=texto;
		}
		
		</script>
	</head>

	<body onload=inicio()>	
		<div id="pagina">
			<div id="zonaContenidoPP">
			<div align="center">
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
			<input type="hidden" name="numfilas" id="numfilas" value="<? echo $filas?>">
			<input type="hidden" name="totalfacturas" id="totalfacturas" value="<? echo $totalfacturas?>">
				<? $iniciopagina=$_POST["iniciopagina"];
				if (empty($iniciopagina)) { $iniciopagina=$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if (empty($iniciopagina)) { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<? $sel_resultado="SELECT bbcodfactura,clientes.nombre as nombre,eefacturas.fecha as fecha,totalfactura,estado FROM eefacturas,clientes WHERE eefacturas.borrado=0 AND eefacturas.codcliente=clientes.codcliente AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",10";
						   $res_resultado=mysql_query($sel_resultado);
						   $bbcontador=0;
						   $marcaestado=0;						   
						   while ($bbcontador < mysql_num_rows($res_resultado)) { 
								
								$marcaestado=mysql_result($res_resultado,$bbcontador,"estado");
								if (mysql_result($res_resultado,$bbcontador,"estado") == 1) { $estado="Sin pagar"; } else { $estado="Pagada"; } 
								if ($bbcontador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
								
								<tr class="<?php echo $fondolinea ?>" onMouseOver="CambiaColor(this,'#000000','#ffffff')" onMouseOut="CambiaColor2(this,'#000000')" >

								<td class="aCentro" width="8%"><? echo $bbcontador+1;?></td>
								<td width="8%"><div align="center"><? echo mysql_result($res_resultado,$bbcontador,"bbcodfactura")?></div></td>
								<td width="38%"><div align="left"><? echo mysql_result($res_resultado,$bbcontador,"nombre")?></div></td>							
								<td width="8%"><div align="right"><? echo number_format(mysql_result($res_resultado,$bbcontador,"totalfactura"),2,".",",")?></div></td>
								<td class="aDerecha" width="10%"><div align="center"><? echo implota(mysql_result($res_resultado,$bbcontador,"fecha"))?></div></td>
								<td class="aDerecha" width="10%"><div align="center"><? echo $estado?></div></td>
								<td width="6%"><div align="center"><a href="#"><img src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_factura(<?php echo mysql_result($res_resultado,$bbcontador,"bbcodfactura")?>,<? echo $marcaestado?>)" title="Modificar"></a></div></td>
								<td width="6%"><div align="center"><a href="#"><img src="../img/ver.png" width="16" height="16" border="0" onClick="ver_factura(<?php echo mysql_result($res_resultado,$bbcontador,"bbcodfactura")?>)" title="Visualizar"></a></div></td>
								<td width="6%"><div align="center"><a href="#"><img src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_factura(<?php echo mysql_result($res_resultado,$bbcontador,"bbcodfactura")?>)" title="Eliminar"></a></div></td>
						</tr>
						</tr>
						<? $bbcontador++;
							}
						?>			
					</table>
					<? } else { ?>
					<table class="fuente8" width="87%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ninguna Boleta que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<? } ?>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
