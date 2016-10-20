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
			$d = $this->_add_body($d);
			$d = $this->_add_document($d);
			$d = $this->_add_picture($d);
			$d = $this->_add_category($d);
				
			$list[] = $d;
		}
		
		return $list;
	}
	
	
	function _collect_documents() {
		
		$list = array();
		
		$result = db_query('
			select n.nid, nfd.title, nfd.created, nfd.changed	
			from {node} as n 
			join {node_field_data} as nfd on n.nid = nfd.nid
			where n.type = :type
			and nfd.status = 1
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

	
	function _add_body($document) {
	
		$result = db_query('
			select body_value
			from {node__body}
			where entity_id = :id
			',
	
			array(':id' => $document->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$document->setBody($record->body_value);
			}
		}

		return $document;
	}	
	
	
	function _add_document($document) {
	
		$result = db_query('
			select fm.uri, fm.filename, d.field_cp_documents_document_description
			from {file_managed} as fm
			join {node__field_cp_documents_document} as d on fm.fid = d.field_cp_documents_document_target_id
			where d.entity_id = :id
			',
	
			array(':id' => $document->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$document->setDocumentUri($record->uri);
				$document->setDocumentName($record->filename);
				$document->setDocumentDescription($record->field_cp_documents_document_description);
			}
		}

		return $document;
	}

	
	function _add_picture($document) {
	
		$result = db_query('
			select fm.uri, fm.filename, p.field_cp_documents_picture_alt, p.field_cp_documents_picture_width, p.field_cp_documents_picture_height
			from {file_managed} as fm
			join {node__field_cp_documents_picture} as p on fm.fid = p.field_cp_documents_picture_target_id
			where p.entity_id = :id
			',
	
			array(':id' => $document->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$document->setPictureUri($record->uri);
				$document->setPictureName($record->filename);
				$document->setPictureTitle($record->field_cp_documents_picture_alt);
				$document->setPictureWidth($record->field_cp_documents_picture_width);
				$document->setPictureHeight($record->field_cp_documents_picture_height);
			}
		}

		return $document;
	}
	
	
	function _add_category($document) {
	
		$result = db_query('
			select field_cp_documents_category_value
			from {node__field_cp_documents_category}
			where entity_id = :id
			',
	
			array(':id' => $document->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$document->setCategory($record->field_cp_documents_category_value);
			}
		}

		return $document;
	}
}