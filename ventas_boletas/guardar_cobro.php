<? 
include ("../conectar.php");
include ("../funciones/fechas.php");  

$bbcodfactura=$_POST["bbcodfactura"];
$codcliente=$_POST["codcliente"];
$importe=$_POST["importe"];
$importevale=$_POST["importevale"];
$importe=$importe-$importevale;
$numdocumento=$_POST["numdocumento"];
$fechacobro=$_POST["fechacobro"];
$fechacobro=explota($_POST["fechacobro"]);
$formapago=$_POST["formapago"];

$sel_comprueba="SELECT * FROM cobros WHERE bbcodfactura='$bbcodfactura'";
$rs_comprueba=mysql_query($sel_comprueba);

if (mysql_num_rows($rs_comprueba) > 0 ) {
	?>
		<script>
		alert("Esta Boleta ya se cobro con anterioridad");
		parent.document.getElementById("botticket").disabled=false;
		parent.document.getElementById("botaceptar").disabled=true;
		//parent.window.close()
		</script>; <?

} else {

		$sel_insert="INSERT INTO cobros (id,bbcodfactura,codcliente,importe,codformapago,numdocumento,fechacobro,observaciones) VALUES ('','$bbcodfactura','$codcliente','$importe','$formapago','$numdocumento','$fechacobro','')";
		$rs_insert=mysql_query($sel_insert);
		
		$sel_insert2="INSERT INTO librodiario (id,fecha,tipodocumento,coddocumento,codcomercial,codformapago,numpago,total) VALUES ('','$fechacobro','2','$bbcodfactura','$codcliente','$formapago','$numdocumento','$importe')";
		$rs_insert2=mysql_query($sel_insert2);
		
		$sel_insert3="UPDATE eefacturas SET estado=2 WHERE bbcodfactura='$bbcodfactura'";
		$rs_insert3=mysql_query($sel_insert3);
		
		?>
		<script>
		alert("El cobro se ha efectuado correctamente");
		parent.document.getElementById("botticket").disabled=false;
		parent.document.getElementById("botaceptar").disabled=true;
		//parent.window.close()
		</script>;
		
<? } ?>
