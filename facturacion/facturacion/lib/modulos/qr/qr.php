<?php
/*
error_reporting(E_ALL);

$datos['RESPUESTA_UTF8'] = "SI";

$datos['PAC']['usuario'] = "DEMO700101XXX";
$datos['PAC']['pass'] = "DEMO700101XXX";
$datos['PAC']['produccion'] = "NO";


$datos['modulo']="qr";                                  //NOMBRE DEL MODULO
$datos['archivo_png']="../../../timbrados/qr_defactura.png";     //RUTA DONDE SE GUARDARA EL  QR.PNG
$datos['cadena']="hola hola";  

___qr($datos);
*/
function ___qr($datos)
{
    
  
    
    
    //print_r($datos);
    $cadenaqr=$datos['cadena'];
    $archivo_png=$datos['archivo_png'];
    include('qrcode/qrlib.php');
    $fileName = $archivo_png;
    QRcode::png($cadenaqr, $fileName, QR_ECLEVEL_L, 4);
    $base64= base64_encode(file_get_contents($archivo_png));
    
    $res['cadena']=$cadenaqr;
    $res['archivo_png']=$archivo_png;
    $res['archivo_base64']=$base64;
    return $res;    
}
     
    
?>