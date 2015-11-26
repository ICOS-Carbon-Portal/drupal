<?php

include ('Node.php');

class ListOfNodes {
	
	private $list_of_nodes = array();
	
	function getNodes() {

		$nodes = $this->_prepare_nodes();
		
		$top_nodes = array();
		foreach ($nodes as $node) {
			if ($node->getDepth() == '1') {
				$top_nodes[] = $node;	
			}
		}
		
		if (count($top_nodes) > 1) { usort($top_nodes, array('ListOfNodes','_compare')); }
		
		$this->list_of_nodes[] = '<ul>';
		
		foreach ($top_nodes as $node) {
			$this->list_of_nodes[] = $this->_build_html($node, $output, $nodes);
		}
		
		$this->list_of_nodes[] = '</ul>';
		
		return implode('', $this->list_of_nodes);
	}
	
	function _build_html($node, &$output, $nodes) {
		
		$url = $GLOBALS['base_url'] . '/';
		
		if ($node->getHasChildren() == '1') {
			
			$nodetype = 'is_topnode';
			$title = $node->getTitle();
			if ($node->getDepth() > '1') {
				$nodetype = 'has_subnodes';
				$title .= '<img src="/themes/custom/cp_theme_d8/images/arrow-down.svg">'; 
			}
			
			$this->list_of_nodes[] = '<li class="' . $nodetype . '"><a href="#">'. $title . '</a>';
			$this->list_of_nodes[] = '<ul>';
			$this->list_of_nodes[] = '<li><a href="'. $url . $node->getPath() .'">'. $node->getTitle() .'</a></li>';
			
			$sub_nodes = array();
			foreach ($nodes as $sub_node) {
				if ($node->getId() == $sub_node->getParent()) {
					$sub_nodes[] = $sub_node;
				}
			}
			
			if (count($sub_nodes) > 1) { usort($sub_nodes, array('ListOfNodes','_compare')); }
			
			foreach ($sub_nodes as $sub_node) {
				$this->_build_html($sub_node, $output, $nodes);
			}
			
			$this->list_of_nodes[] = '</ul>';
			$this->list_of_nodes[] = '</li>';
			
			
		} else {
			$this->list_of_nodes[] = '<li><a href="'. $url . $node->getPath() .'">'. $node->getTitle() .'</a></li>';
		}
	}
	
	static function _compare($a, $b) {
	
		if ($a->getId() == $b->getId()) {
			return 0;
		}
		
		// Todo ! Should not be possible
		if ($a->getWeight() == $b->getWeight()) {
			return 0;
		}
		
		return $a->getWeight() > $b->getWeight() ? 1 : -1;
	}
	
	function _prepare_nodes() {
		$nodes = array();
		
		foreach ($this->_collect_nodes() as $node) {
			
			// Fix id
			$node->setId(str_replace('menu_link_content:', '', $node->getId()));
			
			// Fix parent
			$node->setParent(str_replace('menu_link_content:', '', $node->getParent()));
			
			// Add values
			$node = $this->_add_attributes($node);
			$node->setPath(str_replace('entity:', '', $node->getPath()));
			
			// Fix weight
			$node->setWeight($node->getWeight() + 50);
			
			
			$nodes[$node->getId()] = $node;
		}
		
		return $nodes;
	}
		
	function _collect_nodes() {
		
		$list = array();
		
		$result = db_query('
			select mt.id, mt.weight, mt.parent, mt.has_children, mt.depth
			from {menu_tree} mt
			where mt.menu_name = :menu_name
			and id <> \'standard.front_page\'
			',
		
			array(':menu_name' => 'main')
		)->fetchAll();
	
	
		foreach ($result as $record) {
			if ($record) {
				$node = new Node();
				$node->setId($record->id);
				$node->setParent($record->parent);
				$node->setDepth($record->depth);
				$node->setWeight($record->weight);
				$node->setHasChildren($record->has_children);
				
				$list[] = $node;
			}
		}

		return $list;
		
	}
	
	function _add_attributes($node) {
		
		$result = db_query('
			select mlcd.title, mlcd.link__uri
			from {menu_link_content_data} mlcd join {menu_link_content} mlc on mlcd.id = mlc.id
			where mlc.uuid = :uuid
			',
		
			array(':uuid' => $node->getId())
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$node->setTitle($record->title);
				$node->setPath($record->link__uri);
			}
		}

		return $node;
	}
}