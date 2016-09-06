<?php

namespace Drupal\ns_stationdata\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\ns_stationdata\NSStationdata\SortedListOfStationdata;

/**
 * @Block(
 *   id = "list_of_ns_stationdata",
 *   admin_label = @Translation("List of NordSpec stationdata"),
 * )
 */
class ListOfNSStationdata extends BlockBase {
	
	function build() {
		$listOfStationdata = new SortedListOfStationdata();
		$list = $listOfStationdata->getListAsc();
		
		return array('#markup' => $this->build_html($list));
	}
	
	private function build_html($list) {
		$output = '<table id="ns_stationdata_list">';	
		
		$output .= '<tr>';
		$output .= '<th>Name</th>';
		$output .= '<th>Vegetation</th>';
		$output .= '<th>Country</th>';
		$output .= '</tr>';
		
		foreach ($list as $data) {
			$output .= '<tr>';
			
			$output .= '<td>';
			$output .= '<a href="/nordspec/node/' . $data->getId() . '">' . $data->getTitle() . '</a>';
			$output .= '</td>';
			
			$output .= '<td>';
			$output .= $data->getVegetationType();
			$output .= '</td>';
			
			$output .= '<td>';
			$output .= $data->getCountry();
			$output .= '</td>';	
			
			$output .= '</tr>';
		}
		
		$output .= '</table>';
		
		return $output;
	}
}