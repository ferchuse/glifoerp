<?php
	//si se manda llamar por ajax 
	if(isset($_GET["pk"])){
		
		include("../conexi.php");
		
		$link = Conectarse();
	 	
		echo generar_select($link, $_GET["tabla"], $_GET["pk"], $_GET["label"]);
		
		
	}
	
	
	function generar_select($link, $tabla, $llave_primaria, $campo_etiqueta ,$filtro = false, $disabled = false ,$required = false , $id_selected = 0, $data_indice = 0, $name = ""){
		$consulta = "SELECT * FROM $tabla ORDER BY $campo_etiqueta ";
		
		if($name == ""){
			$name = $llave_primaria;
		}
		
		
		$select = "<select data-indice='$data_indice'";
		
		$select .= $required ? " required " : " ";
		$select .= $disabled ? " disabled " : " ";
		$select.= "class='form-control' name='$name' id='$llave_primaria' >";
		if($filtro){
			$select .= "<option value=''>Todos</option>";
		} 
		else{
			$select .= "<option value=''>Seleccione...</option>";
		}
		
		$result = mysqli_query($link, $consulta);
		
		while($fila = mysqli_fetch_assoc($result)){
			$select.="<option value='".$fila[$llave_primaria]."'";
			$select.=$fila[$llave_primaria] == $id_selected ? " selected" : "" ;
			$select.=" >".$fila[$campo_etiqueta] ."</option>";
			
		}
		$select.="</select>";
		
		if(isset($_SESSION["debug"])){
			
			$select+="	<pre>
			$$consulta
		</pre>";
			}
		
		return $select;
	}
	
?>