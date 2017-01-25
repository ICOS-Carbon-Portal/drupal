<?php

namespace Drupal\cp_statistics\Controller;

use Drupal\Core\Controller\ControllerBase;

class CPStatisticsInternalDB extends ControllerBase {
	
	public function createVisitTable() {
	  	
		$do_create = true;
		
		$db = \Drupal::service('database');
		$exists = $db->query('show tables like \'cp_statistics_visit\'')->fetchAll();
		
		if (empty($exists)) {
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
			
							'page' => array(
									'description' => 'Visited page',
									'type' => 'varchar',
									'length' => 255,
									'not null' => TRUE
							),
							
							'referrer' => array(
									'description' => 'Visitor came from url',
									'type' => 'varchar',
									'length' => 255,
									'not null' => TRUE
							),
								
							'browser' => array(
									'description' => 'Visitors browser',
									'type' => 'varchar',
									'length' => 255,
									'not null' => TRUE
							),
							
							'inlogged' => array(
									'description' => 'Is visitor inlogged - yes/no',
									'type' => 'varchar',
									'length' => 3,
									'not null' => TRUE
							),
								
							'timestamp' => array(
									'description' => 'Timestamp',
									'type' => 'varchar',
									'length' => 20,
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
							'country_code' => array(
									'description' => 'Country code',
									'type' => 'varchar',
									'not null' => FALSE,
									'length' => 10
							),
							'lat' => array(
									'description' => 'Latitude',
									'type' => 'varchar',
									'not null' => FALSE,
									'length' => 40
							),
							'lon' => array(
									'description' => 'Longitude',
									'type' => 'varchar',
									'not null' => FALSE,
									'length' => 40
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