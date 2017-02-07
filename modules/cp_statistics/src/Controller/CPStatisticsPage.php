<?php

namespace Drupal\cp_statistics\Controller;

use Drupal\Core\Controller\ControllerBase;

class CPStatisticsPage extends ControllerBase {

	function show() {
		
		return array(
			'#markup' => $this->build_html(),
			'form' => \Drupal::formBuilder()->getForm('\Drupal\cp_statistics\Form\CPStatisticsForm'),
			'#attached' => array(
				'library' =>  array(
				'cp_statistics/style',
				'cp_statistics/script'
				),
			),
		);
	}

	function build_html() {
		$output = '<div id="cp_statistics">';
		$output .= '<h3 class="page-title">Visit statistics</h3>';
		$output .= '<div id="cp_statistics_selection"></div>';
		$output .= '<h4 id="cp_statistics_selected_year"></h4>';
		$output .= '<div id="cp_statistics_view"></div>';
		$output .= '</div>';

		return $output;
	}

}
