# CityCourier API

Slúži zákazníkom prepravnej spoločnosti CITY COURIER SLOVAKIA, s.r.o., ktorí požiadali o prístup k API.

**V prípade potreby API prístupu kontaktujte prosím priamo info@citycourier.sk**

## Obsah

Trieda `CitycourierApi.php` s priloženými "skriptami" `test_*` obsahujú ukážkové prístupy ako pomocou oauth komunikovať s endpointom. Toto všetko za pomoci projektu https://github.com/thephpleague/oauth2-client 

Tento krátky projekt teda ukazuje, ako zjednodušene môžete zadať prepravy do zákazníckeho portálu z externých systémov/obchodov/skladov.

### Inštalujte potrebnú závislosť 

```
composer install
```

Nakonfigurujte prihlasovacie údaje do `config.php` - budú pridelené individuálne zákazníkom. 

### Príklady
```
#informacie o prihlasenom uzivatelovi - endpoint /api/customer
php test_customerInfo.php

#priklad vytvorenia objednavky - endpoint /api/order
php test_createOrder.php

#stiahnutie prepravneho listka - endpoint /api/order-download
php test_downloadOrder.php
```

Podrobnosti parametrov každého endpointu nájdete priamo v dokumentácii portal.citycourier.sk/api/ (prístup dostanú zákazníci využívajúci API).

## TODO
Implementované je iba vytváranie pre BA prepavu (neobsahuje vzdialené vyzdvohnutie, 3-objednávku atď).
Ostatné prepravy budú do API v prápade potreby doprogramované do endpointov.