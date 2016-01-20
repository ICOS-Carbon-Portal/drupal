<?php

namespace Drupal\cp_cmis\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StreamWrapper\PublicStream;
use Symfony\Component\HttpFoundation\RedirectResponse;

require_once drupal_get_path('module', 'cp_cmis') . '/src/CMIS/cmis_repository_wrapper.php';

class CMISResourceFetcher extends ControllerBase {

	public function fetch($id) {
		
		$config = \Drupal::config('cp_cmis.settings');
		
		if (($config->get('url') != null && $config->get('url') != '')
				&& ($config->get('username') != null && $config->get('username') != '')
				&& ($config->get('password') != null && $config->get('password') != '')) {
					
					$service = new \CMISService($config->get('url'), $config->get('username'), $config->get('password'));
					
					$title = $service->getTitle($id);
					
					if (! file_exists(PublicStream::basePath() . '/cmis')) {
						mkdir(PublicStream::basePath() . '/cmis');
					}
					
					$file = fopen(PublicStream::basePath() . '/cmis/' . $title, 'w');
					fwrite($file, $service->getContentStream($id));
					fclose($file);
					
					$response = new RedirectResponse(PublicStream::baseUrl() . '/cmis/' . $title);
					return $response;
		}
		
		return array('#markup' => '');
		
	}
}