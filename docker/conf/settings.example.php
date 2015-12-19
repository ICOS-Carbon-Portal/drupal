<?php

$databases['default']['default'] = array (
  'database' => 'database',
  'username' => 'user',
  'password' => 'xxxxxxxx',
  'prefix' => '',
  'host' => 'host',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['reverse_proxy'] = true;
$settings['reverse_proxy_addresses'] = array('172.17.42.1');

$settings['trusted_host_patterns'] = array(
		'^url_to_site$',
);

$settings['container_yamls'][] = __DIR__ . '/services.yml';


