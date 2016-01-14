<?php

namespace Drupal\cp_external_auth\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * 
 */
class Logout {
	
	public function leave() {
		
		if (! \Drupal::currentUser()->isAnonymous()) {
			user_logout();
		}
		
		$response = new RedirectResponse('/');
		return $response;	
	}
}