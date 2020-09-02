<?php

function mf_phpversion()
{
    $version = phpversion();
    $numversion = '';
    for($i = 0, $punto = 0; $i < strlen($version); $i++)
    {
        if($version[$i] == '.')
        {
            if($punto == 0)
            {
                $numversion .= $version[$i];
                $punto++;
            }
        }
        else
        {
            $numversion .= $version[$i];
        }
    }

	$version_final="$numversion";
	
	$ver=$version_final[0].'.'.$version_final[2];
	return $ver;
    //return doubleval($numversion);
}
///////////////////////////////////////////////////////
//phpinfo();
function mf_postfijo_php()
{
    $ruta = __DIR__.'/';
    $ruta=str_replace('\\','/',$ruta);

    $rutaweb=$_SERVER['REQUEST_URI'];
    $ruta_local='ifacturas_docs/sdk2_desa';
    $mystring = 'abc';
    $findme   = 'a';
    if (strpos($rutaweb, $ruta_local)>0)
    {
        return 'XX';
    }
/*   
    if($_SERVER["SERVER_NAME"]=='55.cfdi.red' || $_SERVER['SERVER_ADDR']=='192.168.10.11')
    {
        if(file_exists($ruta.'sdk2XX.php')==true)
        {

            return 'XX';
        }
        else
        {

            return '55';
        }
        
    }
*/
    $php_version = mf_phpversion();

    switch($php_version)
    {
        case 5.2: return '53'; break;
    	case 5.3: return '53'; break;
    	case 5.4: return '54'; break;
    	case 5.5: return '55'; break;
    	case 5.6: return '56'; break;
    	case 7.0: return '56'; break;
    	case 7.1: return '71'; break;
    	case 7.2: return '72'; break;
    	case 7.3: return '73'; break;
        case 7.4: return '74'; break;
        case 7.5: return '75'; break;
        case 7.6: return '76'; break;
        default : echo "La version '$php_version' no es compatible";die();
    }

}
////////////////////////////////////////////////
$php_version = mf_postfijo_php();
	$ruta=$ruta."sdk2$php_version.php";
    require_once $ruta;  


