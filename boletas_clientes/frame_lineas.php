<script>
function eliminar_linea(bbcodfacturatmp,numlinea,importe)
{
	if (confirm(" Desea eliminar esta linea ? "))
		parent.document.formulario_lineas.baseimponible.value=parseFloat(parent.document.formulario_lineas.baseimponible.value) - parseFloat(importe);
		var original=parseFloat(parent.document.formulario_lineas.baseimponible.value);
		var result=Math.round(original*100)/100 ;
		parent.document.formulario_lineas.baseimponible.value=result;

		parent.document.formulario_lineas.baseimpuestos.value=parseFloat(result * parseFloat(parent.document.formulario.iva.value / 100));
		var original1=parseFloat(parent.document.formulario_lineas.baseimpuestos.value);
		var result1=Math.round(original1*100)/100 ;
		parent.document.formulario_lineas.baseimpuestos.value=result1;
		var original2=parseFloat(result + result1);
		var result2=Math.round(original2*100)/100 ;
		parent.document.formulario_lineas.preciototal.value=result2;
		
		document.getElementById("frame_datos").src="eliminar_linea.php?bbcodfacturatmp="+bbcodfacturatmp+"&numlinea=" + numlinea;
}
</script>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<?php 
include ("../conectar.php");
$bbcodfacturatmp=$_POST["bbcodfacturatmp"];
$retorno=0;
$modif=$_POST["modif"];
if ($modif<>1) {
		if (!isset($bbcodfacturatmp)) { 
			$bbcodfacturatmp=$_GET["bbcodfacturatmp"]; 
			$retorno=1; }
		if ($retorno==0) {	
				$codfamilia=$_POST["codfamilia"];
				$codarticulo=$_POST["codarticulo"];
				$cantidad=$_POST["cantidad"];
				$precio=$_POST["precio"];
				$importe=$_POST["importe"];
				$descuento=$_POST["descuento"];
				
				$sel_insert="INSERT INTO eefactulineatmp (bbcodfactura,numlinea,codigo,codfamilia,cantidad,precio,importe,dcto) VALUES ('$bbcodfacturatmp','','$codarticulo','$codfamilia','$cantidad','$precio','$importe','$descuento')";
				$rs_insert=mysql_query($sel_insert);
		}
}
?>
<div id="xdetalleAW">
<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
<?php
$sel_lineas="SELECT eefactulineatmp.*,articulos.*,familias.nombre as nombrefamilia FROM eefactulineatmp,articulos,familias WHERE eefactulineatmp.bbcodfactura='$bbcodfacturatmp' AND eefactulineatmp.codigo=articulos.codarticulo AND eefactulineatmp.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY eefactulineatmp.numlinea ASC";
$rs_lineas=mysql_query($sel_lineas);
for ($i = 0; $i < mysql_num_rows($rs_lineas); $i++) {
	$numlinea=mysql_result($rs_lineas,$i,"numlinea");
	$codfamilia=mysql_result($rs_lineas,$i,"codfamilia");
	$nombrefamilia=mysql_result($rs_lineas,$i,"nombrefamilia");
	$referencia=mysql_result($rs_lineas,$i,"referencia");
	$codarticulo=mysql_result($rs_lineas,$i,"codarticulo");
	$descripcion=mysql_result($rs_lineas,$i,"descripcion");
	$cantidad=mysql_result($rs_lineas,$i,"cantidad");
	$precio=mysql_result($rs_lineas,$i,"precio");
	$importe=mysql_result($rs_lineas,$i,"importe");
	$baseimp=$importe+$baseimp;
	$descuento=mysql_result($rs_lineas,$i,"dcto");
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<? echo $fondolinea?>">
				<td width="5%"><? echo $i+1?></td>
				<td width="18%"><? echo $referencia?></td>
				<td width="41%"><? echo $descripcion?></td>
				<td width="8%" class="aCentro"><? echo $cantidad?></td>
				<td width="8%" class="aCentro"><? echo $precio?></td>
				<td width="7%" class="aCentro"><? echo $descuento?></td>
				<td width="8%" class="aCentro"><? echo $importe?></td>
				<td width="3%"><a href="javascript:eliminar_linea(<?php echo $bbcodfacturatmp?>,<?php echo $numlinea?>,<?php echo $importe ?>)"><img src="../img/eliminar.png" border="0"></a></td>
			</tr>
<? }
$baseiva=$baseimp*($ivaimp/100);
$preciotot=$baseimp+$baseiva; ?>
</table>
</div>

<div id="ztotalesaw" align="right">
			<table width="100%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
			  <tr>
			    <td width="70%" class="busqueda"><b>Sub-total</b></td>
				<td width="30%" align="right"><div align="right"><? echo $simbolomoneda ?>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value="<?echo number_format($baseimp,1,",",".");?>" align="right" readonly> 
		        </div></td>																					 
	

			  </tr>
			  <tr>
				<td class="busqueda"><b>IGV</b></td>
				<td align="right"><div align="right"><? echo $simbolomoneda ?>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right" value="<? echo number_format($baseiva,1,",",".");?>" readonly> 
		        </div></td>
			  </tr>
			  <tr>
				<td class="busqueda"><b>Precio Total</b></td>
				<td align="right"><div align="right"><? echo $simbolomoneda ?>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value="<? echo number_format($preciotot,1,",",".");?>" readonly> 
		        </div></td>
			  </tr>
			</table>
</div>

<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>