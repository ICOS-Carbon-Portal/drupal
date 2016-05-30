<?php

namespace Drupal\cp_blogs\CPBlogs;

use Drupal\cp_blogs\CPBlogs\Blog;

class ListOfBlogs {
	
	function getListOfBlogs() {
		return $this->_prepare_blogs();
	}
	
	function _prepare_blogs() {
		
		$list = array();
		
		foreach ($this->_collect_blogs() as $b) {
			$b = $this->_add_picture($b);
			$b = $this->_add_text($b);
				
			$list[] = $b;
		}
		
		return $list;
	}
	
	function _collect_blogs() {
		
		$list = array();
		
		$result = db_query('
			select n.nid, nfd.title, nfd.created, nfd.changed	
			from {node} as n 
				join {node__field_cp_blog_deprecated} as d on n.nid = d.entity_id
				join {node_field_data} as nfd on n.nid = nfd.nid
			where n.type = :type
			and d.field_cp_blog_deprecated_value = 0
			',
			
			array(':type' => 'cp_blog')
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$blog = new Blog();
				$blog->setId($record->nid);
				$blog->setTitle($record->title);
				$blog->setCreated($record->created);
				$blog->setChanged($record->changed);
				
				$list[] = $blog;
			}
		}
		
		return $list;	
	}
	
	function _add_picture($blog) {
	
		$result = db_query('
			select file.uri, picture.field_cp_blog_picture_alt
			from {node__field_cp_blog_picture} picture
			join {file_managed} file
			on picture.field_cp_blog_picture_target_id = file.fid
			where picture.entity_id = :id
			',
	
			array(':id' => $blog->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$blog->setPictureUrl($record->uri);
				$blog->setPictureTitle($record->field_cp_blog_picture_alt);
			}
		}

		return $blog;
	}
	
	function _add_text($blog) {
	
		$result = db_query('
			select field_cp_blog_text_value
			from {node__field_cp_blog_text}
			where entity_id = :id
			',
	
			array(':id' => $blog->getId())
			)->fetchAll();
	
	
		foreach ($result as $record) {
			if ($record) {
				$blog->setText($record->field_cp_blog_text_value);
			}
		}
	
		return $blog;
	}
}