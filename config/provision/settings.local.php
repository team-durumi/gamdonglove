<?php
$databases['default']['default'] = [
    'driver' => 'mysql',
    'database' => 'gamdonglove',
    'username' => 'root',
    'password' => '',
    'host' => '127.0.0.1',
];

$settings['hash_salt'] = 'bO4KXupxop14q3WUSOsjRTIRl1-qM-KnoA-baHLTvJyfHie8ILbR6O9CUNyABD0TDexHoPmGNw';
$settings["config_sync_directory"] = '../config/sync';
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
$settings['cache']['bins']['page'] = 'cache.backend.null';

$settings['extension_discovery_scan_tests'] = FALSE;
