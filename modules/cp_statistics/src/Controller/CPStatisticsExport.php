<?php

namespace Drupal\cp_statistics\Controller;

use Drupal\Core\Controller\ControllerBase;

class CPStatisticsExport extends ControllerBase {
	
	public function export() {
	  	
		$result = db_query('select id, ip, page, referrer, browser, inlogged, timestamp, year, month, day, clock, country_code, lat, lon from cp_statistics_visit');
				
		$curl = curl_init("restheart:8080/db/drupal_cp_visits");
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "post");
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			
		$file = "public://cp_stat.log";	
		
		foreach ($result as $row) {
			
			$body = array(
					"ip" => $row->ip,
					"page" => $row->page,
					"referrer" => $row->referrer,
					"browser" => $row->browser,
					"inlogged" => $row->inlogged,
					"timestamp" => $row->timestamp,
					"year" => $row->year,
					"month" => $row->month,
					"day" => $row->day,
					"clock" => $row->clock,
					"country_code" => $row->country_code,
					"lat" => $row->lat,
					"lon" => $row->lon
			);
		
			
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
			curl_exec($curl);
			
			if (curl_errno($curl)) {
				file_put_contents($file, 'Error: ' . $row->id . ' - ' . curl_errno($curl) . "\r\n", FILE_APPEND | LOCK_EX);
			} else {
				file_put_contents($file, 'Inserted: ' . $row->id . "\r\n", FILE_APPEND | LOCK_EX);
			}
			
		}
		
		curl_close($curl);

		return array('#markup' => 'Done, check log file.');
		
	}
}