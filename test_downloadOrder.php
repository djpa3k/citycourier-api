<?php

require "./vendor/autoload.php";
require "./config.php";
require "./lib/CitycourierApi.php";

$CC = new CitycourierApi($_CONFIG);

if ($CC->passwordGrant($_CONFIG['userId'], $_CONFIG['userPassword']))
{
    $response = $CC->getResponse("/order-download/AA036192");

    if (!empty($response['status']))
        print_r($response);
    else
        file_put_contents('out.pdf',$response);
}
