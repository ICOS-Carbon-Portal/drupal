<?php

namespace Drupal\ns_stationdata\NSStationdata;

use Drupal\ns_stationdata\NSStationdata\Stationdata;

class ListOfStationdata {
	
	public function getListOfData() {
		return $this->prepareData();
	}
	
	private function prepareData() {
		
		$list = array();
		
		foreach ($this->collectData() as $data) {
			$data = $this->addCountry($data);
			$data = $this->addVegetationType($data);
				
			$list[] = $data;
		}
		
		return $list;
	}
	
	private function collectData() {
		
		$list = array();
		
		$result = db_query('
			select n.nid, nfd.title	
			from {node} as n join {node_field_data} as nfd on n.nid = nfd.nid
			where n.type = :type
			and nfd.status = 1
			',
			
			array(':type' => 'ns_stationdata')
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$data = new Stationdata();
				$data->setId($record->nid);
				$data->setTitle($record->title);
				
				$list[] = $data;
			}
		}
		
		return $list;	
	}

	private function addCountry($data) {
	
		$result = db_query('
			select field_ns_country_value
			from {node__field_ns_country}
			where entity_id = :id
			',
	
			array(':id' => $data->getId())
		)->fetchAll();
	
	
		foreach ($result as $record) {
			if ($record) {
				$data->setCountry($record->field_ns_country_value);
			}
		}
	
		return $data;
	}
	
	private function addVegetationType($data) {
	
		$result = db_query('
			select field_ns_vegetation_type_value
			from {node__field_ns_vegetation_type}
			where entity_id = :id
			',
	
			array(':id' => $data->getId())
		)->fetchAll();
	
	
		foreach ($result as $record) {
			if ($record) {
				$data->setVegetationType($record->field_ns_vegetation_type_value);
			}
		}

		return $data;
	}
}