<?php

namespace Drupal\cp_files\CPFiles;

use Drupal\cp_files\CPFiles\Files;
use Drupal\cp_files\CPFiles\File;

class ListOfFiles {
	
	function getListOfFiles() {
		return $this->_prepare_files();
	}
	
	function _prepare_files() {
		
		$list = array();
		
		foreach ($this->_collect_files() as $f) {
			$f = $this->_add_files($f);
			$f = $this->_add_category($f);
				
			$list[] = $f;
		}
		
		return $list;
	}
	
	
	function _collect_files() {
		
		$list = array();
		
		$result = db_query('
			select n.nid, nfd.title, nfd.created, nfd.changed	
			from {node} as n 
			join {node_field_data} as nfd on n.nid = nfd.nid
			where n.type = :type
			and nfd.status = 1
			',
			
			array(':type' => 'cp_files')
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$files = new Files();
				$files->setId($record->nid);
				$files->setTitle($record->title);
				$files->setCreated($record->created);
				$files->setChanged($record->changed);
				
				$list[] = $files;
			}
		}
		
		return $list;	
	}
	
	
	function _add_files($files) {
	
		$list = array();
		
		$result = db_query('
			select fm.uri, fm.filename, fm.filemime, f.field_cp_files_files_description
			from {file_managed} as fm
			join {node__field_cp_files_files} as f on fm.fid = f.field_cp_files_files_target_id
			where f.entity_id = :id
			',
	
			array(':id' => $files->getId())
		)->fetchAll();

		
		foreach ($result as $record) {
			
			if ($record) {
				$file = new File();
				$file->setUri($record->uri);
				$file->setName($record->filename);
				$file->setMime($record->filemime);
				$file->setDescription($record->field_cp_files_files_description);
				
				$list[] = $file;	
			}
		}
		
		$files->setFiles($list);
		
		return $files;
	}

	
	function _add_category($files) {
	
		$result = db_query('
			select field_cp_files_category_value
			from {node__field_cp_files_category}
			where entity_id = :id
			',
	
			array(':id' => $files->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$files->setCategory($record->field_cp_files_category_value);
			}
		}

		return $files;
	}	
}