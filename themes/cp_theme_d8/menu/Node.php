<?php

/**
 *
 */
class Node {

	private $id;
	private $title;
	private $path;
	private $parent;
	private $depth;
	private $weight;
    private $has_children; 

    function __construct() {
    
    }
    
    public function getId() {
    	return $this->id;
    }
    
    public function setId($id) {
    	$this->id = $id;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getPath() {
    	return $this->path;
    }
    
    public function setPath($path) {
    	$this->path = $path;
    }

    public function getParent() {
    	return $this->parent;
    }
    
    public function setParent($parent) {
    	$this->parent = $parent;
    }
    
    public function getDepth() {
    	return $this->depth;
    }
    
    public function setDepth($depth) {
    	$this->depth = $depth;
    }
    
    public function getWeight() {
    	return $this->weight;
    }
    
    public function setWeight($weight) {
    	$this->weight = $weight;
    }
    
    public function getHasChildren() {
        return $this->has_children;
    }

    public function setHasChildren($has_children){
        $this->has_children = $has_children;
    }
}