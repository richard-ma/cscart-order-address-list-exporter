<?php

$schema['top']['addons']['items']['order_address_list_exporter'] = array(
    'href' => 'exporter.address_list',
    'position' => 99,
);

$schema['top']['addons']['items']['order_exporter'] = array(
    'href' => 'exporter.orders',
    'position' => 100,
);

$schema['top']['addons']['items']['error_test'] = array(
    'href' => 'exporter.none',
    'position' => 101,
);

return $schema;
