<?php
$databases['default']['default'] = [
    'driver' => 'mysql',
    'database' => 'gamdonglove',
    'username' => 'root',
    'password' => '',
    'host' => '127.0.0.1',
];
$databases['donation']['default'] = array (
    'database' => '../sqlite3/development.db',
    'prefix' => '',
    'namespace' => 'Drupal\\Core\\Database\\Driver\\sqlite',
    'driver' => 'sqlite',
);

$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

$settings['cache']['bins']['page'] = 'cache.backend.null';
$settings['extension_discovery_scan_tests'] = FALSE;

$settings['encryption_key'] = 'xvhIVrkUhw0uGsjw6AVcMDZD7wHW4fuZEzjz/o7QFyo=';
