<?php

namespace Drupal\cp_cmis\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\cp_cmis\CMIS\Service;

/**
 * @Block(
 *   id = "cp_cmis_contents",
 *   admin_label = @Translation("CP CMIS folder contents"),
 * )
 */
class CMISContents extends BlockBase implements BlockPluginInterface {
	
	private $service;
	
	/**
	 * {@inheritdoc}
	 */
	public function build() {
		
		$config = \Drupal::config('cp_cmis.settings');
		$paths = explode(',', $config->get('paths'));
		
		$this->service = new Service();
		
		$output = '<div id="cp_cmis">';
		
		$co = 1000;
		foreach ($paths as $path) {
			$output .= $this->buildHtml(trim($path), $co);
			$co ++;
		}	
		
		$output .= '</div>';
		
		return array(
			'#markup' => $output,
			'#attached' => array(
				'library' =>  array(
					'cp_cmis/style',
					'cp_cmis/script'
				),
				'drupalSettings' => array(
					'color' => '#0A96F0',
				),
			),
		);
	}
	
	
	private function buildHtml($path, $co) {
		$id = 'cp_cmis_object_' . $co;
		$title = substr($path, strripos($path, '/') + 1);
		
		$icon = '';
		// ATC
		if ($title == 'ATC Public') {
			$icon = '<img src="/' . drupal_get_path('module', 'cp_cmis') . '/icons/icon_cloud_atc.svg" />';
		
		// ETC
		} else if ($title == 'ETC Public') {
			$icon = '<img src="/' . drupal_get_path('module', 'cp_cmis') . '/icons/icon_leaf_etc.svg" />';
		
		// OTC
		} else if ($title == 'OTC Public') {
			$icon = '<img src="/' . drupal_get_path('module', 'cp_cmis') . '/icons/icon_wave_otc.svg" />';
		
		// CP
		} else if ($title == 'CP Public') {
			$icon = '<img src="/' . drupal_get_path('module', 'cp_cmis') . '/icons/icon_database_cp.svg" />';
		
		// CAL
		} else if ($title == 'CAL Public') {
			$icon = '<img src="/' . drupal_get_path('module', 'cp_cmis') . '/icons/icon_graph_cal.svg" />';
		
		}
		
		$output = '<div id="cp_cmis_accordion_' . $id . '" class="cp_cmis panel-group" role="tablist" aria-multiselectable="true">';
		$output .= '<div class="panel panel-default">';
		$output .= '<div id="cp_cmis_accordion_heading_' . $id .'" class="panel-heading" role="tab">';
		$output .= '<h3 class="panel-title">';
		$output .= $icon;
		$output .= '<a role="button" data-toggle="collapse" data-parent="#cp_cmis_accordion_' . $id . '" href="#cp_cmis_accordion_collapse_'. $id . '" aria-expanded="true" aria-controls="cp_cmis_accordion_collapse_' . $id . '">';
		$output .= $title;
		$output .= '</a>';
		$output .= '</h3>';
		$output .= '</div>';
		$output .= '<div id="cp_cmis_accordion_collapse_' . $id . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="cp_cmis_accordion_heading_' . $id . '">';
		$output .= '<div class="panel-body">';
		
		$output .= '<table class="table table-hover table-responsive">';
		
		$list = $this->service->getDocuments($path);
		foreach ($list as $document) {
			$output .= '<tr>';
			$output .= '<td>';
			
			$output .= '<a href="/fetch/'. $document->getId() .'">';
			$output .= $document->getName();
			$output .= '</a>';
			
			$output .= '</td>';
			$output .= '</tr>';
		}
		
		$output .= '</table>';
		
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		
		return $output;
	}
}