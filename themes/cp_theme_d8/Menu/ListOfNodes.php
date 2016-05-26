<?php

include ('Node.php');

class ListOfNodes {
	
	private $nodes;
	private $menu = array();
	private $breadcrumbs = array();
	
	function __construct() {
		$this->nodes = $this->_prepare_nodes();
	}
	
	function getBreadcrumbs($active_path) {
		
		$active_node = null;
		foreach ($this->nodes as $node) {
			if ('/' . $node->getPath() == $active_path) {
				$active_node = $node;
			}
		}
		
		if ($active_node != null && $active_node->getDepth() > '1') {
			$in = 1;
			$list[$in] = $active_node;
			
			$co = $active_node->getDepth();
			while ($co > '1') {
				foreach ($this->nodes as $node) {
					if ($list[$in]->getParent() == $node->getId()) {
						$list[$in + 1] = $node;
					}
				}
				
				$in ++;
				$co --;
			}
			
			$rev_list = array_reverse($list);
			$this->breadcrumbs[] = '<ul>';
			
			//$in = count($rev_list) - 1;
			foreach ($rev_list as $node) {
				if ('/' . $node->getPath() != $active_path) {
					
					//$delimiter = '';
					//if ($in > 1) {
						$delimiter = '<span>&raquo;</span>';
					//}
					
					$url = $GLOBALS['base_url'] . '/';
					$this->breadcrumbs[] = '<li><a href="' . $url . $node->getPath().'">' . $node->getTitle() . '</a>' . $delimiter . '</li>';
					
					$in --;
				}
			}
			
			$this->breadcrumbs[] = '<li><a href="' . $url . $active_node->getPath().'">' . $active_node->getTitle() . '</a></li>';
			$this->breadcrumbs[] = '</ul>';
			
		} 
		
		else {
			$this->breadcrumbs[] = '';
		}
		
		return implode($this->breadcrumbs);
		
	}
	
	function getMenu() {
		
		$top_nodes = array();
		foreach ($this->nodes as $node) {
			if ($node->getDepth() == '1') {
				$top_nodes[] = $node;	
			}
		}
		
		if (count($top_nodes) > 1) { usort($top_nodes, array('ListOfNodes','_compare')); }
		
		$this->menu[] = '<ul>';
		
		foreach ($top_nodes as $node) {
			$this->menu[] = $this->_build_menu($node, $output, $this->nodes);
		}
		
		$this->menu[] = '</ul>';
		
		return implode('', $this->menu);
		
	}
	
	function _build_menu($node, &$output, $nodes) {
		
		$url = $GLOBALS['base_url'] . '/';
		
		if ($node->getHasChildren() == '1') {
			
			$nodetype = 'is_topnode';
			$title = $node->getTitle();
			if ($node->getDepth() > '1') {
				$nodetype = 'has_subnodes';
				$title .= '<img src="/themes/cp_theme_d8/images/arrow-down.svg">'; 
			}
			
			$this->menu[] = '<li class="' . $nodetype . '"><a href="'. $url . $node->getPath() .'">'. $title . '</a>';
			$this->menu[] = '<ul>';
			
			$sub_nodes = array();
			foreach ($nodes as $sub_node) {
				if ($node->getId() == $sub_node->getParent()) {
					$sub_nodes[] = $sub_node;
				}
			}
			
			if (count($sub_nodes) > 1) { usort($sub_nodes, array('ListOfNodes','_compare')); }
			
			foreach ($sub_nodes as $sub_node) {
				$this->_build_menu($sub_node, $output, $nodes);
			}
			
			$this->menu[] = '</ul>';
			$this->menu[] = '</li>';
			
		} 
		
		else {
			$this->menu[] = '<li><a href="'. $url . $node->getPath() .'">'. $node->getTitle() .'</a></li>';
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