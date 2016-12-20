<?php

namespace Drupal\cp_cmis\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\cp_cmis\CMIS\Service;
use Drupal\cp_cmis\CMIS\CMISIcons;


/**
 * @Block(
 *   id = "cp_cmis_files",
 *   admin_label = @Translation("CP CMIS files"),
 * )
 */
class CMISFiles extends BlockBase implements BlockPluginInterface {
	
	private $service;
	
	/**
	 * {@inheritdoc}
	 */
	public function build() {
	
		$config = $this->getConfiguration();
		
		$output = '<div class="cp_cmis">';
		$output .= '<h3 class="heading">';
		$output .= '<img src="' . CMISIcons::getIcon($config['cp_cmis_files_icon']) . '" />';
		$output .= $config['cp_cmis_files_heading'];
		$output .= '</h3>';
		
		if ($config['cp_cmis_files_path'] != '') {
			$this->service = new Service();
			$list = $this->service->getDocuments( $config['cp_cmis_files_path'] );
			
			$output .= '<div class="table-responsive">';
			$output .= '<table class="table table-striped sortable">';
			$output .= '<thead>';
			$output .= '<tr>';
			$output .= '<th></th>';
			$output .= '<th>Name</th>';
			$output .= '<th>Description</th>';
			$output .= '<th>Last modyfied by</th>';
			$output .= '<th>Last modyfied</th>';
			$output .= '</tr>';
			$output .= '</thead>';
			
			$output .= '<tbody>';
			
			foreach ($list as $document) {
				
				$lastMastModifiedDate = new \DateTime($document->getLastModifiedDate());
				$type = substr($document->getName(), strripos($document->getName(), '.') + 1);
				
				$output .= '<tr>';
				$output .= '<td class="type" data-value="'.$type.'">';
				$output .= '<img src="' . CMISIcons::getIcon($type) . '" />';
				$output .= '</td>';
				$output .= '<td class="name">';
				$output .= '<a href="/fetch/'. $document->getId() .'">';
				$output .= $document->getName();
				$output .= '</a>';				
				$output .= '</td>';
				$output .= '<td class="description">' . $document->getDescription() . '</td>';
				$output .= '<td class="modified_by">' . $document->getLastModifiedBy() . '</td>';
				$output .= '<td class="modified_date">' . $lastMastModifiedDate->format('Y-m-d H:i') . '</td>';	
				$output .= '</tr>';
			}
			
			$output .= '</tbody>';
			
			$output .= '</table>';
			$output .= '</div>';
		}
		
		$output .= '</div>';
		
		return array(
			'#markup' => $output,
			'#attached' => array(
				'library' =>  array(
					'cp_cmis/style_files',
					'cp_cmis/script_files'
				),
				'drupalSettings' => array(
					'color' => '#0A96F0',
				),
			),
		);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
	
		$form = parent::blockForm($form, $form_state);
		$config = $this->getConfiguration();

		$heading = '';
		if ($config['cp_cmis_files_heading'] != null) { $heading = $config['cp_cmis_files_heading']; }
		
		$form['heading'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Heading'),
				'#default_value' => $heading
		);
		
		$icon = '';
		$options_icon = array('' => 'none', 'atc' => 'ATC', 'cal' => 'CAL', 'cp' => 'CP', 'etc' => 'ETC', 'otc' => 'OTC');
		if ($config['cp_cmis_files_icon'] != null) { $icon = $config['cp_cmis_files_icon']; }
		$form['icon'] = array(
				'#type' => 'select',
				'#options' => $options_icon,
				'#title' => t('Icon for..'),
				'#default_value' => $icon
		);
		
		$path = '';
		if ($config['cp_cmis_files_path'] != null) { $path = $config['cp_cmis_files_path']; }
		
		$form['path'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Path to the CMIS folder'),
				'#default_value' => $path
		);
	
		return $form;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfiguration( [ 
				'cp_cmis_files_heading' => $form_state->getValue('heading'),
				'cp_cmis_files_icon' => $form_state->getValue('icon'),
				'cp_cmis_files_path' => $form_state->getValue('path')
				
		] );
	}
}
