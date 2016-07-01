<?php

namespace Drupal\cp_pictures\CPPictures;

use Drupal\cp_pictures\CPPictures\Picture;

class ListOfPictures {
	
	function getListOfPictures() {
		return $this->_prepare_pictures();
	}
	
	function _prepare_pictures() {
		
		$list = array();
		
		foreach ($this->_collect_pictures() as $p) {
			$p = $this->_add_body($p);
			$p = $this->_add_picture($p);
			$p = $this->_add_category($p);
			$p = $this->_add_historical($p);
				
			$list[] = $p;
		}
		
		return $list;
	}
	
	
	function _collect_pictures() {
		
		$list = array();
		
		$result = db_query('
			select n.nid, nfd.title, nfd.created, nfd.changed	
			from {node} as n 
				join {node__field_cp_pictures_deprecated} as d on n.nid = d.entity_id
				join {node_field_data} as nfd on n.nid = nfd.nid
			where n.type = :type
			and d.field_cp_pictures_deprecated_value = 0
			',
			
			array(':type' => 'cp_pictures')
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$picture = new Picture();
				$picture->setId($record->nid);
				$picture->setTitle($record->title);
				$picture->setCreated($record->created);
				$picture->setChanged($record->changed);
				
				$list[] = $picture;
			}
		}
		
		return $list;	
	}

	
	function _add_body($picture) {
	
		$result = db_query('
			select body_value
			from {node__body}
			where entity_id = :id
			',
	
			array(':id' => $picture->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$picture->setBody($record->body_value);
			}
		}

		return $picture;
	}	
	
	
	function _add_picture($picture) {
	
		$result = db_query('
			select fm.uri, fm.filename, p.field_cp_pictures_picture_alt, p.field_cp_pictures_picture_width, p.field_cp_pictures_picture_height
			from {file_managed} as fm
			join {node__field_cp_pictures_picture} as p on fm.fid = p.field_cp_pictures_picture_target_id
			where p.entity_id = :id
			',
	
			array(':id' => $picture->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$picture->setPictureUri($record->uri);
				$picture->setPictureName($record->filename);
				$picture->setPictureDescription($record->field_cp_pictures_picture_alt);
				$picture->setPictureWidth($record->field_cp_pictures_picture_width);
				$picture->setPictureHeight($record->field_cp_pictures_picture_height);
			}
		}

		return $picture;
	}
	
	
	function _add_category($picture) {
	
		$result = db_query('
			select field_cp_pictures_category_value
			from {node__field_cp_pictures_category}
			where entity_id = :id
			',
	
			array(':id' => $picture->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$picture->setCategory($record->field_cp_pictures_category_value);
			}
		}

		return $picture;
	}
	
	
	function _add_historical($picture) {
	
		$result = db_query('
			select field_cp_pictures_historical_value
			from {node__field_cp_pictures_historical}
			where entity_id = :id
			',
	
			array(':id' => $picture->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$picture->setHistorical($record->field_cp_pictures_historical_value);
			}
		}

		return $picture;
	}
	
}