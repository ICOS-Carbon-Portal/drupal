<?php

namespace Drupal\cp_external_auth\Controller;

use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * A module that take for granted that some authentication has been externally done
 * and take a few URL params to compose an unique username and then load the user from the database.
 * If the user doesn't exists the user will be created.
 *
 * The querystring must contain ?givenName=value&surname=value&mail=value
 */
class ExternalAuth {
	
	public function initialize() {
		
		$request = \Drupal::request();
		
		if (($request->query->get('givenName') != null && $request->query->get('givenName') != '')
			&& ($request->query->get('surname') != null && $request->query->get('surname') != '')
			&& ($request->query->get('mail') != null && $request->query->get('mail') != '' && $this->checkEmail($request->query->get('mail')))) {
			
				$username = $this->buildUsername($request->query->get('givenName'), $request->query->get('surname'), $request->query->get('mail'));
				
				$user = user_load_by_name($username);
				
				if ($user == false) {
					$user = $this->createUser($username, $request->query->get('mail'));
				}
				
				if ($user != null) {
					user_login_finalize($user);	
				}
		
		} 
		
		$response = new RedirectResponse('/node/1');
		return $response;	
	}
	
	/**
	 * Creates a user account
	 * 
	 * @param String $username
	 * @param String $email
	 */
	private function createUser($username, $email) {
		$password = mt_rand(1000, mt_getrandmax());
		
		$user = User::create();
		$user->setUsername($username);
		$user->setEmail($email);
		$user->setPassword($password);
		$user->activate();
		$user->enforceIsNew();
		$result = $user->save();
		
		if ($result) {
			\Drupal::logger('cp_external_auth')->notice('Create user: ' . $user->getUsername());	
			return $user;
		}
		
		return null;
	}

	/**
	 * Builds up an unique username based on all params
	 *
	 * @param String $givenName
	 * @param String $surname
	 * @param String $email
	 */
	private function buildUsername($givenName, $surname, $email) {
		$givenName = trim($givenName);
		$givenName = ucfirst($givenName);
	
		$surname = trim($surname);
		$surname = ucfirst($surname);
	
		$email = trim($email);
		$email = str_replace('@', ' at ', $email);
		
		return $givenName . ' ' . $surname . ' ' . $email;
	}
	
	/**
	 * Checks if an email address is valid
	 *
	 * @param String $email
	 */
	function checkEmail($email) {
		if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
	
		return true;
	}	
}