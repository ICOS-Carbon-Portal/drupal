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
			$b = $this->_add_heading($b);
			$b = $this->_add_picture($b);
			$b = $this->_add_text($b);
			$b = $this->_add_created($b);
				
			$list[] = $b;
		}
		
		return $list;
	}
	
	function _collect_blogs() {
		
		$list = array();
		
		$result = db_query('
			select n.nid	
			from {node} as n join {node__field_cp_blog_deprecated} as d on n.nid = d.entity_id
			where n.type = :type
			and d.field_cp_blog_deprecated_value = 0
			',
			
			array(':type' => 'cp_blog')
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$blog = new Blog();
				$blog->setId($record->nid);
				
				
				$list[] = $blog;
			}
		}
		
		return $list;	
	}

	function _add_heading($blog) {
	
		$result = db_query('
			select field_cp_blog_heading_value
			from {node__field_cp_blog_heading}
			where entity_id = :id
			',
	
			array(':id' => $blog->getId())
		)->fetchAll();
	
	
		foreach ($result as $record) {
			if ($record) {
				$blog->setHeading($record->field_cp_blog_heading_value);
			}
		}
	
		return $blog;
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
	
	function _add_created($blog) {
	
		$result = db_query('
			select created
			from {node_field_data}
			where vid = :id
			',
	
			array(':id' => $blog->getId())
			)->fetchAll();


			foreach ($result as $record) {
				if ($record) {
					$blog->setCreated($record->created);
				}
			}

			return $blog;
	}
}