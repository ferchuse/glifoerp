<?php
	// error_reporting(0 );
// SDK de Mercado Pago
require __DIR__ .  '/vendor/autoload.php';

// Agrega credenciales

//TEST TOKEN 

$token = "TEST-2599583807479799-071201-647536aad8d58734906bd5ae1ea91d39-88027643"; //TEST GLIFO
$token = "APP_USR-2599583807479799-071201-57bd8dadef36a95264421bf038610bd6-88027643"; //PROD GLIFO
$token = "APP_USR-3710248158721318-103003-e4eef00b089df93a1f253cee549ec9c6-665671200";
/*
	Crear Usuarios 
	
	curl -X POST \
-H "Content-Type: application/json" \
-H 'Authorization: Bearer PROD_ACCESS_TOKEN' \
"https://api.mercadopago.com/users/test_user" \
-d '{"site_id":"MLM"}'

	Ejecutar aqui https://reqbin.com/curl
	
	
	
	Vendedor
{
    "id": 665671200,
    "nickname": "TESTCXBNE9WJ",
    "password": "qatest5041",
    "site_status": "active",
    "email": "test_user_23222645@testuser.com"
	
	TOKEN PROD APP_USR-3710248158721318-103003-e4eef00b089df93a1f253cee549ec9c6-665671200
}

Comprador
{
    "id": 665664608,
    "nickname": "TETE5013781",
    "password": "qatest4115",
    "site_status": "active",
    "email": "test_user_48866708@testuser.com"
}
*/


MercadoPago\SDK::setAccessToken($token);

// Crea un objeto de preferencia
$preference = new MercadoPago\Preference();

// Crea un ítem en la preferencia
$item = new MercadoPago\Item();
$item->title = 'Sistema Brujaaz.com OCT 2020';
$item->quantity = 1;
$item->unit_price = 100;
$preference->items = array($item);
$preference->notification_url = "https://glifo.mx/app/webhook_mpago.php";
$preference->save();

echo "<pre>"; print_r($preference); echo "</pre>";
$preference->status();
?>

<script
  src="https://www.mercadopago.com.mx/integrations/v1/web-payment-checkout.js"
  data-preference-id="<?php echo $preference->id; ?>">
</script>