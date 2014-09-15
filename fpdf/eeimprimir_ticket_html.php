<?php
include ("../conectar.php");
include ("../funciones/fechas.php");
$bbcodfactura=$_GET["bbcodfactura"];
$pagado=$_GET["pagado"];
$adevolver=$_GET["adevolver"];
$hora=date("H:i:s");
$sel_facturas="SELECT * FROM eefacturas INNER JOIN cobros ON eefacturas.bbcodfactura=cobros.bbcodfactura INNER JOIN formapago ON cobros.codformapago=formapago.codformapago WHERE eefacturas.bbcodfactura='$bbcodfactura'";
$rs_factura=mysql_query($sel_facturas); 
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TICKET</title>
<script language="javascript">
function imprimir() {
	window.print();
	<!-- Para evitar que se cierre 
//	window.close(); 
	-->
}
</script>
</head>

<body onLoad="imprimir()">
<style type="text/css">

.Estilo3 {font-family: Arial, Verdana,Helvetica, sans-serif; font-size: 11px; }

</style>

<table width="92%" border="0">
  <tr>
    <td><span class="Estilo3"><div align="center"><strong><h2>Botica <? echo $nomempresa ?></h2></strong></div></span></td>
  </tr>
  <tr>
    <td><span class="Estilo3"><? echo $giro ?></span></td>
  </tr>
  <tr>
    <td><span class="Estilo3"><? echo $giro2 ?></span></td>
  </tr>
  <!-- El RUC no se aplica en PERU
  <tr>
    <td><span class="Estilo3">RUC: <? echo $rutempresa ?></span></td>
  </tr>
  -->
  
  <!-- en lugar de nmero fiscal cambia a RUC -->
  <tr>
    <td><span class="Estilo3">RUC: <? echo $numerofiscal ?></span></td>
  </tr>
  
  <!-- No aplica en PERU
  <tr>
    <td><span class="Estilo3">Res. SII: <? echo $resolucionsii ?></span></td>
  </tr>
  -->
  
  <tr>
    <td><span class="Estilo3"><? echo $direccion ?>, <? echo $comuna ?></span></td>
  </tr>
  <!--<tr>
    <td><span class="Estilo3">Ciudad <? echo $CiudadActual ?> </span></td>
  </tr>
  <tr>-->
    <td><span class="Estilo3">Telefono: <? echo $fonos ?></span></td>
  </tr>
  <tr>
    <td>---------------------------------------------</td>
  </tr>
  <tr>
    <td><span class="Estilo3">TICKET N: <? echo $bbcodfactura?></span></td>
  </tr>
  
    <td><span class="Estilo3">FECHA: <? echo implota(mysql_result($rs_factura,0,"fechacobro"))?></span></td>
  </tr>
  <!--<tr>
    <td><span class="Estilo3">HORA: <? echo $hora?></span></td>
  </tr>-->
  
</table>
<br />

<table width="69%" border="1">
  <tr>
    <td width="10%" class="Estilo3"><div align="center"><strong>CANTIDAD</strong></div></td>
    <td width="38%" class="Estilo3"><div align="center"><strong>ARTICULO</strong></div></td>
    <td width="16%" class="Estilo3"><div align="rigth"> <strong>PRECIO</strong></div></td>
	<td width="9%"  class="Estilo3"><div align="rigth"> <strong>DESC.</strong></div></td>
	<td width="27%" class="Estilo3"><div align="rigth"> <strong>IMPORTE</strong></div></td>
  </tr>
<?

	$sel_lineas="SELECT eefactulinea.*,articulos.* FROM eefactulinea,articulos WHERE eefactulinea.bbcodfactura='$bbcodfactura' AND eefactulinea.codigo=articulos.codarticulo ORDER BY eefactulinea.numlinea ASC";
	$rs_lineas=mysql_query($sel_lineas);
		$preciototal=0;
		$iva=0;
		$preciofinal=0;
	for ($i = 0; $i < mysql_num_rows($rs_lineas); $i++) { 	
		$descripcion=mysql_result($rs_lineas,$i,"descripcion");
		$cantidad=mysql_result($rs_lineas,$i,"cantidad");
		$precio=mysql_result($rs_lineas,$i,"precio_tienda");
		$descuento=mysql_result($rs_lineas,$i,"dcto");
		$importe=mysql_result($rs_lineas,$i,"importe");
		$vendedor=mysql_result($rs_lineas,$i,"vendedor");
		$ivaticket=mysql_result($rs_factura,0,"iva");
		
		//$importe=$cantidad*$precio;
		
		$preciototal=$preciototal+$importe;
		$iva=$preciototal*($ivaticket/100);
		$preciofinal=$preciototal+$iva;?>
		<tr>
   		  <td width="10%" class="Estilo3"><div align="center"><? echo number_format( $cantidad); ?></div></td>
			<td width="38%" class="Estilo3"><div align="left"><? echo substr($descripcion,0,25) ?></div></td>
			<td width="16%" class="Estilo3"><div align="rigth"><? echo $precio ?></div></td>
		  <td width="9%" class="Estilo3"><div align="rigth"><? echo $descuento ?> % </div></td>
			<td width="27%" class="Estilo3"><div align="rigth"><? echo number_format($importe,2,".",","); ?> </div></td>
  		</tr>
		<?
	}
$preciofinal=$preciofinal;	
?>
</table>
<br />
<table width="92%" border="1">
  <tr>
    <td class="Estilo3"><div align="right">----------------------------------------------------------</div></td>
  </tr>			
  <tr>
    <td class="Estilo3"><div align="right">  Subtotal: <? echo $simbolomoneda ?><? echo number_format($preciototal,2,".",",")?></div></td>
  </tr>		
  <tr>
    <td class="Estilo3"><div align="right">       IGV: <? echo $simbolomoneda ?><? echo number_format($iva,2,".",",")?></div></td>
  </tr>	
  <tr>
    <td class="Estilo3"><div align="right">     Total: <? echo $simbolomoneda ?><? echo number_format($preciofinal,2,".",",")?></div></td>
  </tr>
</table>
<table width="69%" border="0">
  <!--<tr>
    <td class="Estilo3">Pago    : <? echo $simbolomoneda ?><? echo number_format($pagado,2, ".",",")?></td>
  </tr>
  <tr>
    <td class="Estilo3">Cambio  : <? echo $simbolomoneda ?><? echo number_format($adevolver,2, ".",",")?></td>
  </tr>
  <tr>
    <td><span class="Estilo3">Forma de pago: <? echo mysql_result($rs_factura,0,"nombrefp")?></span></td>
  </tr>-->
  <tr>
    <td><span class="Estilo3">VENDEDOR: <?php echo $vendedor?></span></td>
  </tr>
  
</table>
<table width="69%" border="0">
<tr>
    <td>---------------------------------------------</td>
  </tr>
  <tr>
    <td><span class="Estilo3"><div align="center">Gracias por su visita a... <strong><? echo $nomempresa ?></strong></span></div></td>
<!--    <td class="Estilo3"><div align="center">Gracias por su visita a <strong> El Mezquite</strong>.</div></td>
 -->
 </tr>
</table>
</body>
</html