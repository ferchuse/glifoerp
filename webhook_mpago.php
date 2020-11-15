<?php
	http_response_code(200);
	// if (isset($_GET["id"], $_GET["topic"])) {
    // http_response_code(200);
    // return;
	// }
	require __DIR__ .  '/lib/mpago/vendor/autoload.php';
	
	
	include("conexi.php");
	
	$link = Conectarse();
	
	// $content = json_encode($_POST);
	$content = file_get_contents('php://input');
	
	$decoded = json_decode($content);
	
	$insert = "INSERT INTO webhooks SET content = '$content' , fecha = NOW()";
	
	$result = mysqli_query($link, $insert);
	
	
	// curl_setopt($ch, CURLOPT_URL, $url); //url
	// curl_setopt($ch, CURLOPT_POST, true); // method
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 
	// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datos_factura)) ; // data
	
	// $output = curl_exec($ch);
	
	$parameters = implode(",",$_GET);
	
	
	$insert = "INSERT INTO webhooks SET content = '$parameters ' , fecha = NOW()";
	
	$result = mysqli_query($link, $insert);
	
	
	
	
	MercadoPago\SDK::setAccessToken("TEST-3710248158721318-103003-8334d320761e1338f9c10925c43c1841-665671200");
	
    $merchant_order = null;
	
    switch($_GET["topic"]) {
        case "payment":
		$payment = MercadoPago\Payment::find_by_id($_GET["id"]);
		// Get the payment and the corresponding merchant_order reported by the IPN.
		$merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
		break;
        case "merchant_order":
		$merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
		break;
	}
	
    // $paid_amount = 0;
    // foreach ($merchant_order->payments as $payment) {
	// if ($payment['status'] == 'approved'){
	// $paid_amount += $payment['transaction_amount'];
	// }
	// }
	
    // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
    // if($paid_amount >= $merchant_order->total_amount){
	// if (count($merchant_order->shipments)>0) { // The merchant_order has shipments
	// if($merchant_order->shipments[0]->status == "ready_to_ship") {
	// print_r("Totally paid. Print the label and release your item.");
	// }
	// } else { // The merchant_order don't has any shipments
	// print_r("Totally paid. Release your item.");
	// }
	// } else {
	// print_r("Not paid yet. Do not release your item.");
	// }
	
	if ($payment->status == 'approved'){
		$external_reference =$payment->external_reference;
		
		
		$update = "UPDATE cargos SET estatus = 'Pagado' WHERE id_cargos = '{$external_reference}'";
		
		$result_update = mysqli_query($link, $update);
		
		echo "result".$result_update;
	}
	
	
	echo "<pre >"; print_r($payment); echo "</pre>";
	// echo "<pre >"; print_r($merchant_order); echo "</pre>";
	
	
	
	
	//Enviar email de pago y desbloquear sistema
	
?>