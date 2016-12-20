<?php

namespace Drupal\cp_cmis\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\cp_cmis\CMIS\Service;
use Drupal\cp_cmis\CMIS\CMISLogos;
use Drupal\cp_cmis\CMIS\CMISIcons;

require_once drupal_get_path('module', 'cp_cmis') . '/src/CMIS/cmis_repository_wrapper.php';

class ListOfDocuments extends ControllerBase {
	
	public function contents($resource) {
		
		$this->service = new Service();
		$settings = \Drupal::config('cp_cmis.settings');
		
		$list = $this->service->getDocuments( $settings->get('public_base_path') . $resource );
		
		$output = '<div class="list_of_cp_cmis_documents">';
		
		$output .= '<h1 class="page-title">' . $resource . '</h1>';
		
		$output .= '<div class="logo"><img src="' . CMISLogos::getLogo($resource) . '" /></div>';
		
		if ($list) {
			
			$output .= '<ul>';
			
			foreach ($list as $document) {
				$type = 'none_typed';
				if (strpos($document->getName(), '.') !== false) { $type = substr($document->getName(), strripos($document->getName(), '.') + 1); }
				
				$output .= '<li>';
				
				$output .= '<div class="type"><img src="' . CMISIcons::getIcon($type) . '" /></div>';
				$output .= '<div class="document"><a href="/fetch/'. $document->getId() .'" target="_blank">'.$document->getName().'</a></div>';
				
				$output .= '</li>';
			}
			
			$output .= '</ul>';
		
		}
		
		$output .= '</div>';
		
		return array(
				'#markup' => $output,
				'#attached' => array(
						'library' =>  array(
								'cp_cmis/style',
								'cp_cmis/script'
						),
				),
		);		
	}
}