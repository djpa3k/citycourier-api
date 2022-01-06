<?php

require "./vendor/autoload.php";
require "./config.php";
require "./lib/CitycourierApi.php";

$CC = new CitycourierApi($_CONFIG);

if ($CC->passwordGrant($_CONFIG['userId'], $_CONFIG['userPassword']))
{
    $params = [
        'ready_time'       => '2020-04-29 15:00:00',
        'type'             => 1,
        'priority'         => '120',
        'invoice'          => 'faktura_o',
        'car'              => 'osobne',
        'cash_on_delivery' => ['price' => '6.95',
                               'vs'    => '180030'],
        'recipient'        => ['name'    => 'Jozef',
                               'phone'   => '0702123321',
                               'address' => '8. mája 24',
                               'city'    => 'Bratislava',
                               'psc'     => '811 08'],
        'package'          => ['weight'      => '50',
                               'count'       => 2,
                               'description' => 'Balicek plny prekvapeni']
    ];

    $orderId = $CC->postResponse("/order", $params);
    print_r($orderId);
}
