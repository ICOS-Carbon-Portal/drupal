<?php

namespace Drupal\cp_media\CPMedia;

use Drupal\cp_media\CPMedia\Media;

class ListOfMedia {
	
	function getListOfMedia() {
		return $this->_prepare_media();
	}
	
	function _prepare_media() {
		
		$list = array();
		
		foreach ($this->_collect_media() as $m) {
			$m = $this->_add_picture($m);
			$m = $this->_add_video($m);
			$m = $this->_add_audio($m);
			$m = $this->_add_body_text($m);
			$m = $this->_add_category($m);
			$m = $this->_add_historical($m);
				
			$list[] = $m;
		}
		
		return $list;
	}
	
	
	function _collect_media() {
		
		$list = array();
		
		$result = db_query('
			select n.nid, nfd.title, nfd.created, nfd.changed	
			from {node} as n 
				join {node__field_cp_media_deprecated} as d on n.nid = d.entity_id
				join {node_field_data} as nfd on n.nid = nfd.nid
			where n.type = :type
			and d.field_cp_media_deprecated_value = 0
			',
			
			array(':type' => 'cp_media')
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$media = new Media();
				$media->setId($record->nid);
				$media->setTitle($record->title);
				$media->setCreated($record->created);
				$media->setChanged($record->changed);
				
				$list[] = $media;
			}
		}
		
		return $list;	
	}
	
	
	function _add_picture($media) {
	
		$result = db_query('
			select fm.uri, fm.filename, p.field_cp_media_picture_alt, p.field_cp_media_picture_width, p.field_cp_media_picture_height
			from {file_managed} as fm
			join {node__field_cp_media_picture} as p on fm.fid = p.field_cp_media_picture_target_id
			where p.entity_id = :id
			',
	
			array(':id' => $media->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$media->setPictureUri($record->uri);
				$media->setPictureName($record->filename);
				$media->setPictureTitle($record->field_cp_media_picture_alt);
				$media->setPictureWidth($record->field_cp_media_picture_width);
				$media->setPictureHeight($record->field_cp_media_picture_height);
			}
		}

		return $media;
	}
	
	
	function _add_video($media) {
	
		$result = db_query('
			select fm.uri, fm.filename, v.field_cp_media_video_description
			from {file_managed} as fm
			join {node__field_cp_media_video} as v on fm.fid = v.field_cp_media_video_target_id
			where v.entity_id = :id
			',
	
			array(':id' => $media->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$media->setVideoUri($record->uri);
				$media->setVideoName($record->filename);
				$media->setVideoDescription($record->field_cp_media_video_description);
			}
		}

		return $media;
	}	
	
	
	function _add_audio($media) {
	
		$result = db_query('
			select fm.uri, fm.filename, a.field_cp_media_audio_description
			from {file_managed} as fm
			join {node__field_cp_media_audio} as a on fm.fid = a.field_cp_media_audio_target_id
			where a.entity_id = :id
			',
	
			array(':id' => $media->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$media->setAudioUri($record->uri);
				$media->setAudioName($record->filename);
				$media->setAudioDescription($record->field_cp_media_audio_description);
			}
		}

		return $media;
	}	
	
	
	function _add_text($media) {
	
		$result = db_query('
			select field_cp_media_text_value
			from {node__field_cp_media_text}
			where entity_id = :id
			',
	
			array(':id' => $media->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$media->setText($record->field_cp_media_text_value);
			}
		}

		return $media;
	}	

	function _add_body_text($media) {
	
		$result = db_query('
			select body_value
			from {node__body}
			where entity_id = :id
			',
	
			array(':id' => $media->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$media->setText($record->body_value);
			}
		}

		return $media;
	}
	
	function _add_category($media) {
	
		$result = db_query('
			select field_cp_media_category_value
			from {node__field_cp_media_category}
			where entity_id = :id
			',
	
			array(':id' => $media->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$media->setCategory($record->field_cp_media_category_value);
			}
		}

		return $media;
	}

	
	function _add_historical($media) {
	
		$result = db_query('
			select field_cp_media_historical_value
			from {node__field_cp_media_historical}
			where entity_id = :id
			',
	
			array(':id' => $media->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$media->setHistorical($record->field_cp_media_historical_value);
			}
		}

		return $media;
	}
	
}