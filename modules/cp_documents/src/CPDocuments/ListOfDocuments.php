<?php

namespace Drupal\cp_documents\CPDocuments;

use Drupal\cp_documents\CPDocuments\Document;

class ListOfDocuments {
	
	function getListOfDocuments() {
		return $this->_prepare_documents();
	}
	
	function _prepare_documents() {
		
		$list = array();
		
		foreach ($this->_collect_documents() as $d) {
			$d = $this->_add_file($d);
			$d = $this->_add_picture($d);
			$d = $this->_add_category_key($d);
			$d = $this->_add_historical($d);
				
			$list[] = $d;
		}
		
		return $list;
	}
	
	
	function _collect_documents() {
		
		$list = array();
		
		$result = db_query('
			select n.nid, nfd.title, nfd.created, nfd.changed	
			from {node} as n 
				join {node__field_cp_documents_deprecated} as d on n.nid = d.entity_id
				join {node_field_data} as nfd on n.nid = nfd.nid
			where n.type = :type
			and d.field_cp_documents_deprecated_value = 0
			',
			
			array(':type' => 'cp_documents')
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$document = new Document();
				$document->setId($record->nid);
				$document->setTitle($record->title);
				$document->setCreated($record->created);
				$document->setChanged($record->changed);
				
				$list[] = $document;
			}
		}
		
		return $list;	
	}
	
	
	function _add_file($document) {
	
		$result = db_query('
			select fm.uri, fm.filename, f.field_cp_documents_file_description
			from {file_managed} as fm
			join {node__field_cp_documents_file} as f on fm.fid = f.field_cp_documents_file_target_id
			where f.entity_id = :id
			',
	
			array(':id' => $document->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$document->setFileUrl($record->uri);
				$document->setFileName($record->filename);
				$document->setFileDescription($record->field_cp_documents_file_description);
			}
		}

		return $document;
	}

	
	function _add_picture($document) {
	
		$result = db_query('
			select fm.uri, p.field_cp_documents_picture_alt, p.field_cp_documents_picture_width, p.field_cp_documents_picture_height
			from {file_managed} as fm
			join {node__field_cp_documents_picture} as p on fm.fid = p.field_cp_documents_picture_target_id
			where p.entity_id = :id
			',
	
			array(':id' => $document->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$document->setPictureUrl($record->uri);
				$document->setPictureTitle($record->field_cp_documents_picture_alt);
				$document->setPictureWidth($record->field_cp_documents_picture_width);
				$document->setPictureHeight($record->field_cp_documents_picture_height);
			}
		}

		return $document;
	}
	
	
	function _add_category_key($document) {
	
		$result = db_query('
			select field_cp_documents_category_key_value
			from {node__field_cp_documents_category_key}
			where entity_id = :id
			',
	
			array(':id' => $document->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$document->setCategoryKey($record->field_cp_documents_category_key_value);
			}
		}

		return $document;
	}

	
	function _add_historical($document) {
	
		$result = db_query('
			select field_cp_documents_historical_value
			from {node__field_cp_documents_historical}
			where entity_id = :id
			',
	
			array(':id' => $document->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$document->setHistorical($record->field_cp_documents_historical_value);
			}
		}

		return $document;
	}
	
}