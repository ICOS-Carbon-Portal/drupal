<?php

$base_url = 'url_to_site';

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

$settings['install_profile'] = 'standard';
$settings['container_yamls'][] = __DIR__ . '/services.yml';

$settings['trusted_host_patterns'] = array(
	'^url_to_site$',
);


