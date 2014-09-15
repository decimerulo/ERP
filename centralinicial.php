<?php
include ("./conectar.php");
include_once("./popupmsg/popup.class.php");

// Normal Usage  , with time out.

$HeadlineStyleArr["text-align"] = "left";
$HeadlineStyleArr["color"] = "purple"; 
$HeadlineStyleArr["background-color"] = "silver";
$HeadlineStyleArr["font-style"] = "italic";
$HeadlineStyleArr["font-family"] = "arial, sans-serif";
$MessageStyleArr["border"] = "black 8px solid";
$MessageStyleArr["filter"] =  "alpha(opacity=80)"; // IE
$MessageStyleArr["moz-opacity"] = 0.8;  //FF
$MessageStyleArr["opacity"] = 0.8;    // FF

$TextStyleArr["text-align"] = "center";
$TextStyleArr["color"] = "silver"; 
$TextStyleArr["background-color"] = "purple";
$TextStyleArr["font-weight"] = "bold";
$TextStyleArr["font-family"] = "arial, sans-serif";

$msg2 = new popupMsg (150,30,450,100,"Sistema Ventas",12000);
$msg2->populateHTML();
$msg2->PrintMsg();

$hoy=date("Y-m-d");



$sel_tmp="SELECT codalbaran FROM albaranestmp WHERE datediff('$hoy',fecha) > 2";

$rs_tmp=mysql_query($sel_tmp);

$contador=0;

while ($contador < mysql_num_rows($rs_tmp)) {

	$codalbaran=mysql_result($rs_tmp,$contador,"codalbaran");

	$sel_borrar="DELETE FROM albalineatmp WHERE codalbaran='$codalbaran'";

	$rs_borrar=mysql_query($sel_borrar);

	$contador++;

}



$sel_borrar="DELETE FROM albaranestmp WHERE datediff('$hoy',fecha) > 2";

$rs_borrar=mysql_query($sel_borrar);



$sel_tmp="SELECT codalbaran FROM albaranesptmp WHERE datediff('$hoy',fecha) > 2";

$rs_tmp=mysql_query($sel_tmp);

$contador=0;

while ($contador < mysql_num_rows($rs_tmp)) {

	$codalbaran=mysql_result($rs_tmp,$contador,"codalbaran");

	$sel_borrar="DELETE FROM albalineaptmp WHERE codalbaran='$codalbaran'";

	$rs_borrar=mysql_query($sel_borrar);

	$contador++;

}



$sel_borrar="DELETE FROM albaranesptmp WHERE datediff('$hoy',fecha) > 2";

$rs_borrar=mysql_query($sel_borrar);


//facturasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
$sel_tmp="SELECT codfactura FROM facturastmp WHERE datediff('$hoy',fecha) > 2";

$rs_tmp=mysql_query($sel_tmp);

$contador=0;

while ($contador < mysql_num_rows($rs_tmp)) {

	$codfactura=mysql_result($rs_tmp,$contador,"codfactura");

	$sel_borrar="DELETE FROM factulineatmp WHERE codfactura='$codfactura'";

	$rs_borrar=mysql_query($sel_borrar);

	$contador++;

}



$sel_borrar="DELETE FROM facturastmp WHERE datediff('$hoy',fecha) > 2";

$rs_borrar=mysql_query($sel_borrar);



$sel_tmp="SELECT codfactura FROM facturasptmp WHERE datediff('$hoy',fecha) > 2";

$rs_tmp=mysql_query($sel_tmp);

$contador=0;

while ($contador < mysql_num_rows($rs_tmp)) {

	$codfactura=mysql_result($rs_tmp,$contador,"codfactura");

	$sel_borrar="DELETE FROM factulineaptmp WHERE codfactura='$codfactura'";

	$rs_borrar=mysql_query($sel_borrar);

	$contador++;

}



$sel_borrar="DELETE FROM facturasptmp WHERE datediff('$hoy',fecha) > 2";

$rs_borrar=mysql_query($sel_borrar);

//fin facturas


///BOLETASSSSSSSSSSSSSSSSSSSSSSSSSSSSS--------------------------------------------------------------------------

$bbsel_tmp="SELECT bbcodfactura FROM eefacturastmp WHERE datediff('$hoy',fecha) > 2";

$bbrs_tmp=mysql_query($bbsel_tmp);

$bbcontador=0;


while ($bbcontador < mysql_num_rows($bbrs_tmp)) {

	$bbcodfactura=mysql_result($bbrs_tmp,$bbcontador,"bbcodfactura");

	$bbsel_borrar="DELETE FROM eefactulineatmp WHERE bbcodfactura='$bbcodfactura'";

	$bbrs_borrar=mysql_query($bbsel_borrar);

	$bbcontador++;

}



$bbsel_borrar="DELETE FROM eefacturastmp WHERE datediff('$hoy',fecha) > 2";

$bbrs_borrar=mysql_query($bbsel_borrar);



$bbsel_tmp="SELECT bbcodfactura FROM eefacturasptmp WHERE datediff('$hoy',fecha) > 2";

$bbrs_tmp=mysql_query($bbsel_tmp);

$bbcontador=0;

while ($bbcontador < mysql_num_rows($bbrs_tmp)) {

	$bbcodfactura=mysql_result($bbrs_tmp,$bbcontador,"bbcodfactura");

	$bbsel_borrar="DELETE FROM eefactulineaptmp WHERE bbcodfactura='$bbcodfactura'";

	$bbrs_borrar=mysql_query($bbsel_borrar);

	$bbcontador++;

}



$bbsel_borrar="DELETE FROM eefacturasptmp WHERE datediff('$hoy',fecha) > 2";

$bbrs_borrar=mysql_query($bbsel_borrar);

//FIN BOLETASSSSSSSSSSSS



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Documento sin t&iacute;tulo</title>

<style type="text/css">



</style>



<script type="text/javascript">

function CambiaColor(esto,fondo,texto)

 {

    esto.style.background=fondo;

    esto.style.color=texto;

 }



function doBlink() { 

var blink = document.all.tags("BLINK") 

for (var i=0; i < blink.length; i++) 

blink[i].style.visibility = blink[i].style.visibility == "" ? "hidden" : "" 

} 



function startBlink() { 

if (document.all) setInterval("doBlink()",500) 

} 

window.onload = startBlink; 

 

 

</script>



</head>

<body>

<table width="90%" border="0" align="center">

	<tr height="200px">

		<td align="center"><img src="img/maxcodekaini.jpg" border="0" /></td>

	</tr>

	<tr>		

		<td height="27">&nbsp;</td>

	</tr>

</table>



<tr>

    <td>&nbsp;</td>

    <td><div align="center" class="Estilo6"><strong>Sistema Control Gestion Ventas </strong></div></td>

    <td>&nbsp;	</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

    <td><div align="center" class="Estilo6"> </div></td>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

    <td><div align="center" class="Estilo6"></div></td>

    <td>&nbsp;</td>

  </tr>

    <tr>

      <td>&nbsp;</td>

      <td>&nbsp;</td>

      <td>&nbsp;</td>

    </tr>

    <tr>

      <td>&nbsp;</td>

      <td><table width="50%" border="0" align="center">

        <tr>

          <td><div align="center"><span class="Estilo5">  </span></div></td>

        </tr>

      </table></td>

      <td>&nbsp;</td>

    </tr>

    <tr>

      <td height="27">&nbsp;</td>

      <td><table width="50%" border="0" align="center">

        <tr>

          <td width="38%">&nbsp;</td>

        </tr>











<table width="90%" border="0" align="center">

	<tr>

		<td width="38%"><div align="center"><img src="img/fx-80x15.png" width="80" height="15" /> Sistema Optimizado para Firefox </div></td>

    </tr>    

</table>

</body>

</html>