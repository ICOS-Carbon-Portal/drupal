<?php

namespace Drupal\cp_cmis\Plugin\Block;

class CMISIcons {
	
	const TXT = 'icon_txt.svg';
	const PDF = 'icon_pdf.svg';
	const JPG = 'icon_jpg.svg';
	const DOCX = 'icon_docx.svg';
	const XLSX = 'icon_xlsx.svg';
	const PPTX = 'icon_pptx.svg';
	const DOC = 'icon_doc.svg';	
	const XLS = 'icon_xls.svg';
	const PPT = 'icon_ppt.svg';
	
	const ATC = 'icon_atc.svg';
	const CAL = 'icon_cal.svg';
	const CP = 'icon_cp.svg';
	const ETC = 'icon_etc.svg';
	const OTC = 'icon_otc.svg';
	
	const ANY = 'icon_any.svg';

	static private $types = array (
			'txt' => self::TXT,
			'pdf' => self::PDF,
			'jpg' => self::JPG,
			'docx' => self::DOCX,
			'xlsx' => self::XLSX,
			'pptx' => self::PPTX,
			'doc' => self::DOC,
			'xls' => self::XLS,
			'ppt' => self::PPT,
			'atc' => self::ATC,
			'cal' => self::CAL,
			'cp' => self::CP,
			'etc' => self::ETC,
			'otc' => self::OTC
	);
	
	function __construct() {
		
	}
	
	public static function getIcon($type) {
		
		if (array_key_exists($type, self::$types)) {
			return '/' . drupal_get_path('module', 'cp_cmis') . '/icons/' . self::$types[$type];
			
		} else {
			return '/' . drupal_get_path('module', 'cp_cmis') . '/icons/' . self::ANY;
		}
	}
}