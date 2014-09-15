<?
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$bbcodfacturatmp=$_POST["bbcodfacturatmp"];
$codcliente=$_POST["codcliente"];
$fecha=explota($_POST["fecha"]);
$iva=$_POST["iva"];
$remito=$_POST["remito"];
$numfactura=$_POST["numfactura"];
$minimo=0;

if ($accion=="alta") {
	$query_operacion="INSERT INTO eefacturas (bbcodfactura, numfactura, fecha, iva, codcliente, estado, borrado, remito) VALUES ('', '$numfactura', '$fecha', '$iva', '$codcliente', '1', '0', '$remito')";				
	$rs_operacion=mysql_query($query_operacion);
	$bbcodfactura=mysql_insert_id();
	
	// Se guarda la nueva numeracion de factura
	if ($setnumbol==1)
	{
		$setnumbol=0;
		$sel_articulos="UPDATE eefacturas SET bbcodfactura='$numeracionboleta' WHERE bbcodfactura='$bbcodfactura'";
		$rs_articulos=mysql_query($sel_articulos);

		$sel_articulos="UPDATE parametros SET setnumbol=0 WHERE indice=1";
		$rs_articulos=mysql_query($sel_articulos);
		$bbcodfactura=$numeracionboleta;
	}
		
	
	if ($rs_operacion) { $mensaje="La Boleta ha sido dada de alta correctamente"; }
	$query_tmp="SELECT * FROM eefactulineatmp WHERE bbcodfactura='$bbcodfacturatmp' ORDER BY numlinea ASC";
	$bbrs_tmp=mysql_query($query_tmp);
	$bbcontador=0;
	$baseimponible=0;
	while ($bbcontador < mysql_num_rows($bbrs_tmp)) {
		$codfamilia=mysql_result($bbrs_tmp,$bbcontador,"codfamilia");
		$numlinea=mysql_result($bbrs_tmp,$bbcontador,"numlinea");
		$codigo=mysql_result($bbrs_tmp,$bbcontador,"codigo");
		$cantidad=mysql_result($bbrs_tmp,$bbcontador,"cantidad");
		$precio=mysql_result($bbrs_tmp,$bbcontador,"precio");
		$importe=mysql_result($bbrs_tmp,$bbcontador,"importe");
		$baseimponible=$baseimponible+$importe;
		$dcto=mysql_result($bbrs_tmp,$bbcontador,"dcto");
		$sel_insertar="INSERT INTO eefactulinea (bbcodfactura,numlinea,codfamilia,codigo,cantidad,precio,importe,dcto) VALUES 
		('$bbcodfactura','$numlinea','$codfamilia','$codigo','$cantidad','$precio','$importe','$dcto')";
		$rs_insertar=mysql_query($sel_insertar);		
		$sel_articulos="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_articulos=mysql_query($sel_articulos);
		$sel_minimos = "SELECT stock,stock_minimo,descripcion FROM articulos where codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_minimos= mysql_query($sel_minimos);
		if ((mysql_result($rs_minimos,0,"stock") < mysql_result($rs_minimos,0,"stock_minimo")) or (mysql_result($rs_minimos,0,"stock") <= 0))
	   		{ 
		  		$mensaje_minimo=$mensaje_minimo . " " . mysql_result($rs_minimos,0,"descripcion")."<br>";
				$minimo=1;
   			};
		$bbcontador++;
	}
	$baseimpuestos=$baseimponible*($iva/100);
	$preciototal=$baseimponible+$baseimpuestos;
	//$preciototal=number_format($preciototal,2);	
	$sel_act="UPDATE eefacturas SET totalfactura='$preciototal' WHERE bbcodfactura='$bbcodfactura'";
	$rs_act=mysql_query($sel_act);
	$baseimpuestos=0;
	$baseimponible=0;
	$preciototal=0;
	$cabecera1="Inicio >> Ventas &gt;&gt; Nueva Factura ";
	$cabecera2="INSERTAR BOLETA ";
}

if ($accion=="modificar") {
	$bbcodfactura=$_POST["bbcodfactura"];
	$act_albaran="UPDATE eefacturas SET codcliente='$codcliente', fecha='$fecha', iva='$iva', remito='$remito', numfactura='$numfactura' WHERE bbcodfactura='$bbcodfactura'";
	$rs_albaran=mysql_query($act_albaran);
	$sel_lineas = "SELECT codigo,codfamilia,cantidad FROM eefactulinea WHERE bbcodfactura='$bbcodfactura' order by numlinea";
	$rs_lineas = mysql_query($sel_lineas);
	$bbcontador=0;
	while ($bbcontador < mysql_num_rows($rs_lineas)) {
		$codigo=mysql_result($rs_lineas,$bbcontador,"codigo");
		$codfamilia=mysql_result($rs_lineas,$bbcontador,"codfamilia");
		$cantidad=mysql_result($rs_lineas,$bbcontador,"cantidad");
		$sel_actualizar="UPDATE `articulos` SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_actualizar = mysql_query($sel_actualizar);
		$bbcontador++;
	}
	$bbsel_borrar = "DELETE FROM eefactulinea WHERE bbcodfactura='$bbcodfactura'";
	$bbrs_borrar = mysql_query($bbsel_borrar);
	$sel_lineastmp = "SELECT * FROM eefactulineatmp WHERE bbcodfactura='$bbcodfacturatmp' ORDER BY numlinea";
	$rs_lineastmp = mysql_query($sel_lineastmp);
	$bbcontador=0;
	$baseimponible=0;
	while ($bbcontador < mysql_num_rows($rs_lineastmp)) {
		$numlinea=mysql_result($rs_lineastmp,$bbcontador,"numlinea");
		$codigo=mysql_result($rs_lineastmp,$bbcontador,"codigo");
		$codfamilia=mysql_result($rs_lineastmp,$bbcontador,"codfamilia");
		$cantidad=mysql_result($rs_lineastmp,$bbcontador,"cantidad");
		$precio=mysql_result($rs_lineastmp,$bbcontador,"precio");
		$importe=mysql_result($rs_lineastmp,$bbcontador,"importe");
		$baseimponible=$baseimponible+$importe;
		$dcto=mysql_result($rs_lineastmp,$bbcontador,"dcto");
	
		$sel_insert = "INSERT INTO eefactulinea (bbcodfactura,numlinea,codigo,codfamilia,cantidad,precio,importe,dcto) 
		VALUES ('$bbcodfactura','','$codigo','$codfamilia','$cantidad','$precio','$importe','$dcto')";
		$rs_insert = mysql_query($sel_insert);
		
		$sel_actualiza="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_actualiza = mysql_query($sel_actualiza);
		$sel_bajominimo = "SELECT codarticulo,codfamilia,stock,stock_minimo,descripcion FROM articulos WHERE codarticulo='$codigo' AND codfamilia='$codfamilia' AND borrado=0";
		$rs_bajominimo= mysql_query($sel_bajominimo);
		$stock=mysql_result($rs_bajominimo,0,"stock");
		$stock_minimo=mysql_result($rs_bajominimo,0,"stock_minimo");
		$descripcion=mysql_result($rs_bajominimo,0,"descripcion");
		
		if (($stock < $stock_minimo) or ($stock <= 0))
		   { 
			  $mensaje_minimo=$mensaje_minimo . " " . $descripcion."<br>";
			  $minimo=1;
		   };
		$bbcontador++;
	}
	$baseimpuestos=$baseimponible*($iva/100);
	$preciototal=$baseimponible+$baseimpuestos;
	//$preciototal=number_format($preciototal,2);	
	$sel_act="UPDATE eefacturas SET totalfactura='$preciototal' WHERE bbcodfactura='$bbcodfactura'";
	$rs_act=mysql_query($sel_act);
	$baseimpuestos=0;
	$baseimponible=0;
	$preciototal=0;
	if ($rs_query) { $mensaje="Los datos de la Boleta han sido modificados correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Modificar Factura ";
	$cabecera2="MODIFICAR BOLETA ";
}

if ($accion=="baja") {
	$bbcodfactura=$_GET["bbcodfactura"];
	$query="UPDATE eefacturas SET borrado=1 WHERE bbcodfactura='$bbcodfactura'";
	$rs_query=mysql_query($query);
	$query="SELECT * FROM eefactulinea WHERE bbcodfactura='$bbcodfactura' ORDER BY numlinea ASC";
	$bbrs_tmp=mysql_query($query);
	$bbcontador=0;
	$baseimponible=0;
	while ($bbcontador < mysql_num_rows($bbrs_tmp)) {
		$codfamilia=mysql_result($bbrs_tmp,$bbcontador,"codfamilia");
		$codigo=mysql_result($bbrs_tmp,$bbcontador,"codigo");
		$cantidad=mysql_result($bbrs_tmp,$bbcontador,"cantidad");
		$sel_articulos="UPDATE articulos SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_articulos=mysql_query($sel_articulos);
		$bbcontador++;
	}
	if ($rs_query) { $mensaje="La Boleta ha sido eliminada correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Eliminar Factura";
	$cabecera2="ELIMINAR BOLETA";
	$query_mostrar="SELECT * FROM eefacturas WHERE bbcodfactura='$bbcodfactura'";
	$rs_mostrar=mysql_query($query_mostrar);
	$codcliente=mysql_result($rs_mostrar,0,"codcliente");
	$fecha=mysql_result($rs_mostrar,0,"fecha");
	$iva=mysql_result($rs_mostrar,0,"iva");
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function aceptar() {
			location.href="index.php";
		}
		
		function imprimir(bbcodfactura) {
		//window.open("../fpdf/imprimir_facturamx.php?bbcodfactura="+bbcodfactura);
			window.open("../fpdf/imprimir_boletamx.php?bbcodfactura="+bbcodfactura);
			
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2?></div>
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%"></td>
							<td width="85%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
						<? if ($minimo==1) { ?>
						<tr>
							<td width="15%"></td>
							<td width="85%" colspan="2" class="mensajeminimo">Los siguientes art&iacute;culos est&aacute;n bajo m&iacute;nimo:<br><?php echo $mensaje_minimo;?></td>
					    </tr>
						<? } 
						 $sel_cliente="SELECT * FROM clientes WHERE codcliente='$codcliente'"; 
						  $rs_cliente=mysql_query($sel_cliente); ?>
						<tr>
							<td width="15%">Cliente</td>
							<td width="85%" colspan="2"><?php echo mysql_result($rs_cliente,0,"nombre");?></td>
					    </tr>
						<tr>
							<td width="15%">RUC</td>
						    <td width="85%" colspan="2"><?php echo mysql_result($rs_cliente,0,"nif");?></td>
					    </tr>
						<tr>
						  <td>Direcci&oacute;n</td>
						  <td colspan="2"><?php echo mysql_result($rs_cliente,0,"direccion"); ?></td>
					  </tr>
						<tr>
						  <td>C&oacute;digo de factura</td>
						  <td colspan="2"><?php echo $bbcodfactura?></td>
					  </tr>
					  <tr>
						  <td>Fecha</td>
						  <td colspan="2"><?php echo implota($fecha)?></td>
					  </tr>
					  <tr>
						  <td>IGV</td>
						  <td colspan="2"><?php echo $iva?> %</td>
					  </tr>
					  <tr>
						  <td>N&deg; Factura</td>
						  <td colspan="2"><?php echo $numfactura?> </td>
					  </tr>
					  <tr>
						  <td>N&deg; Remito</td>
						  <td colspan="2"><?php echo $remito?> </td>
					  </tr>
					  <tr>
						  <td></td>
						  <td colspan="2"></td>
					  </tr>
				  </table>
					 <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="5%">ITEM</td>
							<td width="25%">REFERENCIA</td>
							<td width="30%">DESCRIPCION</td>
							<td width="10%">CANTIDAD</td>
							<td width="10%">PRECIO</td>
							<td width="10%">DCTO %</td>
							<td width="10%">IMPORTE</td>
						</tr>
					</table>
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
					  <? $sel_lineas="SELECT eefactulinea.*,articulos.*,familias.nombre as nombrefamilia FROM eefactulinea,articulos,familias WHERE eefactulinea.bbcodfactura='$bbcodfactura' AND eefactulinea.codigo=articulos.codarticulo AND eefactulinea.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY eefactulinea.numlinea ASC";
$rs_lineas=mysql_query($sel_lineas);
						for ($i = 0; $i < mysql_num_rows($rs_lineas); $i++) {
							$numlinea=mysql_result($rs_lineas,$i,"numlinea");
							$codfamilia=mysql_result($rs_lineas,$i,"codfamilia");
							$nombrefamilia=mysql_result($rs_lineas,$i,"nombrefamilia");
							$codarticulo=mysql_result($rs_lineas,$i,"codarticulo");
							$descripcion=mysql_result($rs_lineas,$i,"descripcion");
							$referencia=mysql_result($rs_lineas,$i,"referencia");
							$cantidad=mysql_result($rs_lineas,$i,"cantidad");
							$precio=mysql_result($rs_lineas,$i,"precio");
							$importe=mysql_result($rs_lineas,$i,"importe");
							$baseimponible=$baseimponible+$importe;
							$descuento=mysql_result($rs_lineas,$i,"dcto");
							if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
									<tr class="<? echo $fondolinea?>">
										<td width="5%" class="aCentro"><? echo $i+1?></td>
										<td width="25%"><? echo $referencia?></td>
										<td width="30%"><? echo $descripcion?></td>
										<td width="10%" class="aCentro"><? echo $cantidad?></td>
										<td width="10%" class="aCentro"><? echo $precio?></td>
										<td width="10%" class="aCentro"><? echo $descuento?></td>
										<td width="10%" class="aCentro"><? echo $importe?></td>
									</tr>
					<? } ?>
					</table>
			  </div>
				  <?
				  $baseimpuestos=$baseimponible*($iva/100);
			      $preciototal=$baseimponible+$baseimpuestos;
			      $preciototal=number_format($preciototal,2);
			  	  ?>
					<div id="frmBusqueda">
					<table width="25%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
						<tr>
							<td width="15%">Base imponible</td>
							<td width="15%"><?php echo number_format($baseimponible,2);?> <? echo $simbolomoneda ?></td>
						</tr>
						<tr>
							<td width="15%">IGV</td>
							<td width="15%"><?php echo number_format($baseimpuestos,2);?> <? echo $simbolomoneda ?></td>
						</tr>
						<tr>
							<td width="15%">Total</td>
							<td width="15%"><?php echo $preciototal?> <? echo $simbolomoneda ?></td>
						</tr>
					</table>
			  </div>
				<div id="botonBusqueda">
					<div align="center">
					  <img src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
					   <img src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir(<? echo $bbcodfactura?>)" onMouseOver="style.cursor=cursor">
				        </div>
					</div>
			  </div>
		  </div>
		</div>
	</body>
</html>
