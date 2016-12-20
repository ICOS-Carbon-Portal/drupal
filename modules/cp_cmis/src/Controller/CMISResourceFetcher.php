<?php

namespace Drupal\cp_cmis\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StreamWrapper\PublicStream;
use Symfony\Component\HttpFoundation\RedirectResponse;

require_once drupal_get_path('module', 'cp_cmis') . '/src/CMIS/cmis_repository_wrapper.php';

class CMISResourceFetcher extends ControllerBase {

	public function fetch($id) {
		
		$settings = \Drupal::config('cp_cmis.settings');
		
		if (($settings->get('url') != null && $settings->get('url') != '')
				&& ($settings->get('username') != null && $settings->get('username') != '')
				&& ($settings->get('password') != null && $settings->get('password') != '')) {
					
					$service = new \CMISService($settings->get('url'), $settings->get('username'), $settings->get('password'));
					
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