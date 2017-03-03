<?php

namespace Drupal\cp_statistics\Controller;

use Drupal\Core\Controller\ControllerBase;

class CPStatisticsReview extends ControllerBase {

	function show($year, $month) {
		
		return array(
			'#markup' => $this->build_html($year, $month),
			'#attached' => array(
				'library' =>  array(
				'cp_statistics/style',
				'cp_statistics/script_review'
				),
				'drupalSettings' => array(
						'year' => $year,
						'month' => $month
				),
			),
		);
	}

	function build_html($year, $month) {
		$output = '<div id="cp_statistics">';
		$output .= '<h3 class="page-title">Visit statistics</h3>';
		$output .= '<h4 id="cp_statistics_title">Review ' . $this->getMonthName($month) . '&nbsp;&nbsp;' . $year . '</h4>';
		$output .= '<div id="cp_statistics_view"></div>';
		$output .= '</div>';

		return $output;
	}
	
	private function getMonthName($month) {
		$monthNames = array(
				'01' => 'January',
				'02' => 'February',
				'03' => 'March',
				'04' => 'April',
				'05' => 'May',
				'06' => 'June',
				'07' => 'July',
				'08' => 'August',
				'09' => 'September',
				'10' => 'October',
				'11' => 'November',
				'12' => 'December'
		);
		
		return $monthNames[$month];
	}

}


