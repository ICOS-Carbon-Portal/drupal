<?php

namespace Drupal\cp_events\CPEvents;

use Drupal\cp_events\CPEvents\Event;

class ListOfEvents {
	
	function getListOfEvents() {
		return $this->_prepare_events();
	}
	
	function _prepare_events() {
		
		$list = array();
		
		foreach ($this->_collect_events() as $c) {
			$c = $this->_add_heading($c);
			$c = $this->_add_text($c);
			$c = $this->_add_picture($c);
			$c = $this->_add_link($c);
			$c = $this->_add_from_date($c);
			$c = $this->_add_to_date($c);
			$c = $this->_add_news($c);
			$c = $this->_add_historical($c);
			$c = $this->_add_created($c);
				
			$list[] = $c;
		}
		
		return $list;
	}
	
	function _collect_events() {
		
		$list = array();
		
		$result = db_query('
			select n.nid	
			from {node} as n join {node__field_cp_event_deprecated} as d on n.nid = d.entity_id
			where n.type = :type
			and d.field_cp_event_deprecated_value = 0
			',
			
			array(':type' => 'cp_event')
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$event = new Event();
				$event->setId($record->nid);
				
				
				$list[] = $event;
			}
		}
		
		return $list;	
	}

	function _add_heading($event) {
	
		$result = db_query('
			select field_cp_event_heading_value
			from {node__field_cp_event_heading}
			where entity_id = :id
			',
	
			array(':id' => $event->getId())
		)->fetchAll();
	
	
		foreach ($result as $record) {
			if ($record) {
				$event->setHeading($record->field_cp_event_heading_value);
			}
		}
	
		return $event;
	}
	
	function _add_text($event) {
		
		$result = db_query('
			select field_cp_event_text_value	
			from {node__field_cp_event_text}
			where entity_id = :id
			',
		
			array(':id' => $event->getId())
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$event->setText($record->field_cp_event_text_value);
			}
		}
		
		return $event;
	}

	function _add_picture($event) {
	
		$result = db_query('
			select file.uri, picture.field_cp_event_picture_alt
			from {node__field_cp_event_picture} picture
			join {file_managed} file
			on picture.field_cp_event_picture_target_id = file.fid
			where picture.entity_id = :id
			',
	
			array(':id' => $event->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$event->setPictureUrl($record->uri);
				$event->setPictureTitle($record->field_cp_event_picture_alt);
			}
		}

		return $event;
	}
	
	function _add_link($event) {
	
		$result = db_query('
			select field_cp_event_link_uri, field_cp_event_link_title
			from {node__field_cp_event_link}
			where entity_id = :id
			',
	
			array(':id' => $event->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$event->setLinkUrl($record->field_cp_event_link_uri);
				$event->setLinkTitle($record->field_cp_event_link_title);
			}
		}

		return $event;
	}
	
	function _add_from_date($event) {
	
		$result = db_query('
			select field_cp_event_from_date_value
			from {node__field_cp_event_from_date}
			where entity_id = :id
			',
	
			array(':id' => $event->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$event->setFromDate($record->field_cp_event_from_date_value);
			}
		}

		return $event;
	}
	
	function _add_to_date($event) {
	
		$result = db_query('
			select field_cp_event_to_date_value
			from {node__field_cp_event_to_date}
			where entity_id = :id
			',
	
			array(':id' => $event->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$event->setToDate($record->field_cp_event_to_date_value);
			}
		}

		return $event;
	}
	
	function _add_news($event) {
	
		$result = db_query('
			select field_cp_event_news_value
			from {node__field_cp_event_news}
			where entity_id = :id
			',
	
				array(':id' => $event->getId())
				)->fetchAll();
	
	
				foreach ($result as $record) {
					if ($record) {
						$event->setNews($record->field_cp_event_news_value);
					}
				}
	
				return $event;
	}
	
	function _add_historical($event) {
	
		$result = db_query('
			select field_cp_event_historical_value
			from {node__field_cp_event_historical}
			where entity_id = :id
			',
	
			array(':id' => $event->getId())
		)->fetchAll();
	
	
		foreach ($result as $record) {
			if ($record) {
				$event->setHistorical($record->field_cp_event_historical_value);
			}
		}
	
		return $event;
	}
	
	function _add_created($event) {
	
		$result = db_query('
			select created, changed
			from {node_field_data}
			where vid = :id
			',
	
			array(':id' => $event->getId())
		)->fetchAll();
	
	
		foreach ($result as $record) {
			if ($record) {
				$event->setCreated($record->created);
				$event->setChanged($record->changed);
			}
		}
	
		return $event;
	}
}