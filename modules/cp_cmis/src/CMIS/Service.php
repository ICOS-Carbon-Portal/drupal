<?php

namespace Drupal\cp_cmis\CMIS;

require_once 'cmis_repository_wrapper.php';

use Drupal\cp_cmis\CMIS\Document;

class Service {
	
	private $service = null;
	
	function __construct() {
		
		$config = \Drupal::config('cp_cmis.settings');
		
		if (($config->get('url') != null && $config->get('url') != '')
				&& ($config->get('username') != null && $config->get('username') != '')
				&& ($config->get('password') != null && $config->get('password') != '')) {
			
					$this->service = new \CMISService($config->get('url'), $config->get('username'), $config->get('password'));
		}	
	}
	
	function getDocuments($path) {
		$list = array();
		
		if ($this->service != null && $path != null && $path != '') {
			$folder = $this->service->getObjectByPath($path);
			
			if ($this->check()) {
					
				$contents = $this->service->getChildren($folder->id);
				if ($this->check()) {
			
					foreach ($contents->objectList as $object) {
						
						if ($object->properties['cmis:baseTypeId'] == "cmis:document") {
							$document = new Document();
							$document->setId($object->properties['cmis:objectId']);
							$document->setName($object->properties['cmis:name']);
							//$document->setDescription($object->properties['cmis:description']);
							$document->setLastModifiedBy($object->properties['cmis:lastModifiedBy']);
							$document->setLastModifiedDate($object->properties['cmis:lastModificationDate']);
							
							$list[] = $document;
						}
					}
				}
			}
		}

		return $list;
	}
	
	private function check() {
		if ($this->service->getLastRequest()->code > 299) {
			return false;
		}
		
		return true;
	}
}