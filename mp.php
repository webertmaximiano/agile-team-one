<?php
include('_core/_includes/config.php'); 
global $db_con;
global $mp_acess_token;
$url = 'https://api.mercadopago.com/users/test_user?access_token='.$mp_acess_token;
$fields_string ='';
$vars = "{\"site_id\":\"MLB\"}";
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $vars);
$result = curl_exec($ch);
$result = json_decode($result);
curl_close($ch);
print("<pre>".print_r($result,true)."</pre>");
?>