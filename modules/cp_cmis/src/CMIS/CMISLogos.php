<?php

namespace Drupal\cp_cmis\CMIS;

class CMISLogos {
	
	const ATC = 'ATC.png';
	const CAL = 'CAL.png';
	const CP = 'CP.png';
	const ETC = 'ETC.png';
	const OTC = 'OTC.png';
	const RI = 'RI.png';
	
	const ANY = 'logo_any.png';

	static private $types = array (
			'ATC Public' => self::ATC,
			'CAL Public' => self::CAL,
			'CP Public' => self::CP,
			'ETC Public' => self::ETC,
			'OTC Public' => self::OTC,
			'HO Public' => self::RI,
			'ICOS ERIC Public' => self::RI,
			'Webinars' => self::RI
	);
	
	function __construct() {
		
	}
	
	public static function getLogo($type) {
		
		if (array_key_exists($type, self::$types)) {
			return '/' . drupal_get_path('module', 'cp_cmis') . '/images/' . self::$types[$type];
			
		} else {
			return '/' . drupal_get_path('module', 'cp_cmis') . '/images/' . self::ANY;
		}
	}
}
