<?php

namespace Drupal\cp_statistics\Controller;

use Drupal\Core\Controller\ControllerBase;

class CPStatisticsDB extends ControllerBase {
	
	public function createVisitTable() {
	  	
		$do_create = true;
		
		$db = \Drupal::service('database');
		
		
		$result = $db->query('show tables like \'cp_statistics_visit\'')->fetchAll();
		if ($result) {
			foreach ($result as $post) {
				$do_create = false;
			}
		}
		
		if ($do_create) {
			$table = array(
					'description' => 'CP Statistics visit table',
					
					'fields' => array(
							'id' => array(
									'description' => 'ID',
									'type' => 'serial',
									'not null' => TRUE
							),
							
							'ip' => array(
									'description' => 'Visitors IP address',
									'type' => 'varchar',
									'length' => 255,
									'not null' => TRUE
							),
						
							'path' => array(
									'description' => 'Visited page',
									'type' => 'varchar',
									'length' => 255,
									'not null' => TRUE
							),
							
							'year' => array(
									'description' => 'Year',
									'type' => 'varchar',
									'not null' => TRUE,
									'length' => 10
							),
							
							'month' => array(
									'description' => 'Month',
									'type' => 'varchar',
									'not null' => TRUE,
									'length' => 10
							),
							
							'day' => array(
									'description' => 'Day',
									'type' => 'varchar',
									'not null' => TRUE,
									'length' => 10
							),
							
							'clock' => array(
									'description' => 'Clock',
									'type' => 'varchar',
									'not null' => TRUE,
									'length' => 10
							),
					),
					
				'primary key' => array('id'),
			);
			
			$db->schema()->createTable('cp_statistics_visit', $table);
			
			return array('#markup' => 'CP Statistics visit table created.');
			
		} else {
			return array('#markup' => 'CP Statistics visit table already created. Nothing done.');
		}
	}
}