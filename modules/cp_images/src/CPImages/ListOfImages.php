<?php

namespace Drupal\cp_images\CPImages;

use Drupal\cp_images\CPImages\Image;

class ListOfImages {
	
	function getListOfImages() {
		return $this->_prepare_images();
	}
	
	function _prepare_images() {
		
		$list = array();
		
		foreach ($this->_collect_images() as $i) {
			$i = $this->_add_picture($i);
			$i = $this->_add_category_key($i);
			$i = $this->_add_historical($i);
				
			$list[] = $i;
		}
		
		return $list;
	}
	
	
	function _collect_images() {
		
		$list = array();
		
		$result = db_query('
			select n.nid, nfd.title, nfd.created, nfd.changed	
			from {node} as n 
				join {node__field_cp_images_deprecated} as d on n.nid = d.entity_id
				join {node_field_data} as nfd on n.nid = nfd.nid
			where n.type = :type
			and d.field_cp_images_deprecated_value = 0
			',
			
			array(':type' => 'cp_images')
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$image = new Image();
				$image->setId($record->nid);
				$image->setTitle($record->title);
				$image->setCreated($record->created);
				$image->setChanged($record->changed);
				
				$list[] = $image;
			}
		}
		
		return $list;	
	}

	
	function _add_picture($image) {
	
		$result = db_query('
			select fm.uri, p.field_cp_images_picture_alt, p.field_cp_images_picture_width, p.field_cp_images_picture_height
			from {file_managed} as fm
			join {node__field_cp_images_picture} as p on fm.fid = p.field_cp_images_picture_target_id
			where p.entity_id = :id
			',
	
			array(':id' => $image->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$image->setPictureUrl($record->uri);
				$image->setPictureTitle($record->field_cp_images_picture_alt);
				$image->setPictureWidth($record->field_cp_images_picture_width);
				$image->setPictureHeight($record->field_cp_images_picture_height);
			}
		}

		return $image;
	}
	
	
	function _add_category_key($image) {
	
		$result = db_query('
			select field_cp_images_category_key_value
			from {node__field_cp_images_category_key}
			where entity_id = :id
			',
	
			array(':id' => $image->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$image->setCategoryKey($record->field_cp_images_category_key_value);
			}
		}

		return $image;
	}

	
	function _add_historical($image) {
	
		$result = db_query('
			select field_cp_images_historical_value
			from {node__field_cp_images_historical}
			where entity_id = :id
			',
	
			array(':id' => $image->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$image->setHistorical($record->field_cp_images_historical_value);
			}
		}

		return $image;
	}
	
}