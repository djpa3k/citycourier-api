<?php

require "./vendor/autoload.php";
require "./config.php";
require "./lib/CitycourierApi.php";

$CC = new CitycourierApi($_CONFIG);

if ($CC->passwordGrant($_CONFIG['userId'], $_CONFIG['userPassword']))
{
    $info = $CC->getResponse("/customer");
    print_r($info);
}
