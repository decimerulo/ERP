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
	//boleta
	//$sel_act="UPDATE boletas SET totalfactura='$preciototal' WHERE bbcodfactura='$bbcodfactura'";
	$rs_act=mysql_query($sel_act);
	$baseimpuestos=0;
	$baseimponible=0;
	$preciototal=0;
	$cabecera1="Inicio >> Ventas &gt;&gt; Venta Mostrador ";
	$cabecera2="NUEVA BOLETA ";
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
		
		function efectuarpago(bbcodfactura,codcliente,importe) {
			miPopup = window.open("efectuarpago.php?bbcodfactura="+bbcodfactura+"&codcliente="+codcliente+"&importe="+importe,"miwin","width=500,height=360,scrollbars=yes");			
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
							<td width="15%">&nbsp;</td>
						    <td width="85%" colspan="2">&nbsp;</td>
					    </tr>
						<tr>
						  <td>Direcci&oacute;n</td>
						  <td colspan="2"><?php echo mysql_result($rs_cliente,0,"direccion"); ?></td>
					  </tr>
						<tr>
						  <td>C&oacute;digo</td>
						  <td colspan="2"><?php echo $bbcodfactura?></td>
					  </tr>
					  <tr>
						  <td>Fecha</td>
						  <td colspan="2"><?php echo implota($fecha)?></td>
					  </tr>
					  <tr>
						  <td>&nbsp;</td>
						  <td colspan="2">&nbsp;</td>
					  </tr>
					  <tr>
						  <td>&nbsp;</td>
						  <td colspan="2">&nbsp; </td>
					  </tr>
					  <tr>
						  <td>&nbsp;</td>
						  <td colspan="2">&nbsp; </td>
					  </tr>
					  <tr>
						  <td></td>
						  <td colspan="2"></td>
					  </tr>
				  </table>
					 <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="5%">ITEM</td>
							<td width="20%">FAMILIA</td>
							<td width="15%">REFERENCIA</td>
							<td width="30%">DESCRIPCION</td>
							<td width="7%">CANTIDAD</td>
							<td width="8%">PRECIO</td>
							<td width="7%">DCTO %</td>
							<td width="8%">IMPORTE</td>
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
							$referencia=mysql_result($rs_lineas,$i,"referencia");
							$descripcion=mysql_result($rs_lineas,$i,"descripcion");
							$cantidad=mysql_result($rs_lineas,$i,"cantidad");
							$precio=mysql_result($rs_lineas,$i,"precio");
							$importe=mysql_result($rs_lineas,$i,"importe");
							$baseimponible=$baseimponible+$importe;
							$descuento=mysql_result($rs_lineas,$i,"dcto");
							if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
									<tr class="<? echo $fondolinea?>">
										<td width="5%" class="aCentro"><? echo $i+1?></td>
										<td width="20%"><? echo $nombrefamilia?></td>
										<td width="15%"><? echo $referencia?></td>
										<td width="30%"><? echo $descripcion?></td>
										<td width="7%" class="aCentro"><? echo $cantidad?></td>
										<td width="8%" class="aCentro"><? echo $precio?></td>
										<td width="7%" class="aCentro"><? echo $descuento?></td>
										<td width="8%" class="aCentro"><? echo $importe?></td>
									</tr>
					<? } ?>
					</table>
			  </div>
				  <?
				  $baseimpuestos=$baseimponible*($iva/100);
			      $preciototal=$baseimponible+$baseimpuestos;
			      $preciototal=$preciototal;
			  	  ?>
					<div id="frmBusqueda">
					<table width="25%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
						<tr>
							<td width="35%"><b>Base imponible</b></td>
							<td width="25%" align="right"><? echo $simbolomoneda ?> <?php echo number_format($baseimponible,2,".",",");?>&nbsp&nbsp&nbsp</td>
						</tr>    
						<tr>
							<td width="35%"><b>&nbsp;</b></td>
							<td width="25%" align="right">&nbsp;</td>
						</tr>
						<tr>
							<td width="35%"><b>Total</b></td>
							<td width="25%" align="right"><? echo $simbolomoneda ?> <?php echo number_format($preciototal,2,".",",");?>&nbsp&nbsp&nbsp</td>
						</tr>
					</table>
			  </div>
				<div id="botonBusqueda">
					<div align="center">
					  <img src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
					   <img src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir(<? echo $bbcodfactura?>)" onMouseOver="style.cursor=cursor">
				        </div>
						<br>
						<div align="center" id="cajareg">
					  <img src="../img/caja.jpg" width="80" height="77" border="1" onClick="efectuarpago(<? echo $bbcodfactura?>,<? echo $codcliente?>,<? echo $preciototal?>)" onMouseOver="style.cursor=cursor" title="Efectuar pago">
				        </div>
					</div>
			  </div>
		  </div>
		</div>
	</body>
</html>
