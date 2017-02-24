<?php

namespace Drupal\cp_statistics\CPStatistics;

use Drupal\cp_statistics\CPStatistics\Page;

class RelevantPages {
	
	public function collectRelevantContent() {
		$list = array();
	
		$page = new Page();
		$page->setId('site_home');
		$page->setPage('/');
		$list[] = $page;
	
		$result = db_query('select entity_id, bundle from node__body')->fetchAll();
		foreach ($result as $row) {
			$path = '/node/';
				
			switch ($row->bundle) {
				case 'cp_blog':
					$path = '/blog/';
					break;
						
				case 'cp_event':
					$path = '/event/';
					break;
			}
				
			$page = new Page();
			$page->setId($row->entity_id);
			$page->setPage($path . $row->entity_id);
			$list[] = $page;
		}
	
		foreach ($list as $page) {
			$result = db_query('
					select alias
					from url_alias
					where source = :page
					',
						
					array(':page' => $page->getPage())
					)->fetchAll();
	
					foreach ($result as $row) {
	
						if ($row->alias) {
							$page->setAlias($row->alias);
						}
					}
		}
	
		return $list;
	}
}