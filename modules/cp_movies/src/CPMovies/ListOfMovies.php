<?php

namespace Drupal\cp_movies\CPMovies;

use Drupal\cp_movies\CPMovies\Movie;

class ListOfMovies {
	
	function getListOfMovies() {
		return $this->_prepare_movies();
	}
	
	function _prepare_movies() {
		
		$list = array();
		
		foreach ($this->_collect_movies() as $m) {
			$m = $this->_add_body($m);
			$m = $this->_add_movie($m);
			$m = $this->_add_picture($m);
			$m = $this->_add_category($m);
			$m = $this->_add_historical($m);
				
			$list[] = $m;
		}
		
		return $list;
	}
	
	
	function _collect_movies() {
		
		$list = array();
		
		$result = db_query('
			select n.nid, nfd.title, nfd.created, nfd.changed	
			from {node} as n 
				join {node__field_cp_movies_deprecated} as d on n.nid = d.entity_id
				join {node_field_data} as nfd on n.nid = nfd.nid
			where n.type = :type
			and d.field_cp_movies_deprecated_value = 0
			',
			
			array(':type' => 'cp_movies')
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$movie = new Movie();
				$movie->setId($record->nid);
				$movie->setTitle($record->title);
				$movie->setCreated($record->created);
				$movie->setChanged($record->changed);
				
				$list[] = $movie;
			}
		}
		
		return $list;	
	}

	
	function _add_body($movie) {
	
		$result = db_query('
			select body_value
			from {node__body}
			where entity_id = :id
			',
	
			array(':id' => $movie->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$movie->setBody($record->body_value);
			}
		}

		return $movie;
	}	
	
	
	function _add_movie($movie) {
	
		$result = db_query('
			select fm.uri, fm.filename, m.field_cp_movies_movie_description
			from {file_managed} as fm
			join {node__field_cp_movies_movie} as m on fm.fid = m.field_cp_movies_movie_target_id
			where m.entity_id = :id
			',
	
			array(':id' => $movie->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$movie->setMovieUri($record->uri);
				$movie->setMovieName($record->filename);
				$movie->setMovieDescription($record->field_cp_movies_movie_description);
			}
		}

		return $movie;
	}	
	
	
	function _add_picture($movie) {
	
		$result = db_query('
			select fm.uri, fm.filename, p.field_cp_movies_picture_alt, p.field_cp_movies_picture_width, p.field_cp_movies_picture_height
			from {file_managed} as fm
			join {node__field_cp_movies_picture} as p on fm.fid = p.field_cp_movies_picture_target_id
			where p.entity_id = :id
			',
	
			array(':id' => $movie->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$movie->setPictureUri($record->uri);
				$movie->setPictureName($record->filename);
				$movie->setPictureTitle($record->field_cp_movies_picture_alt);
				$movie->setPictureWidth($record->field_cp_movies_picture_width);
				$movie->setPictureHeight($record->field_cp_movies_picture_height);
			}
		}

		return $movie;
	}	
	
	
	function _add_category($movie) {
	
		$result = db_query('
			select field_cp_movies_category_value
			from {node__field_cp_movies_category}
			where entity_id = :id
			',
	
			array(':id' => $movie->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$movie->setCategory($record->field_cp_movies_category_value);
			}
		}

		return $movie;
	}

	
	function _add_historical($movie) {
	
		$result = db_query('
			select field_cp_movies_historical_value
			from {node__field_cp_movies_historical}
			where entity_id = :id
			',
	
			array(':id' => $movie->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$movie->setHistorical($record->field_cp_movies_historical_value);
			}
		}

		return $movie;
	}
	
}