<?php
include('../../vendor/autoload.php');   

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

function authenticate()
{
    // Getting the access token from .env file (create your own function)
    $mpAccessToken = getVariableFromEnv('mercado_pago_access_token');
    // Set the token the SDK's config
    MercadoPagoConfig::setAccessToken($mpAccessToken);
    // (Optional) Set the runtime enviroment to LOCAL if you want to test on localhost
    // Default value is set to SERVER
    MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
}

