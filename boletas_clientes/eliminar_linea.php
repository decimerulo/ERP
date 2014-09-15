<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache'); 

include ("../conectar.php");

$bbcodfactura=$_GET["bbcodfacturatmp"];
$numlinea=$_GET["numlinea"];

$consulta = "DELETE FROM eefactulineatmp WHERE bbcodfactura ='".$bbcodfactura."' AND numlinea='".$numlinea."'";
$rs_consulta = mysql_query($consulta);
echo "<script>parent.location.href='frame_lineas.php?bbcodfacturatmp=".$bbcodfactura."';</script>";

?>