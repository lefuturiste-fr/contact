<?php
/*
*	Use a recaptcha api
*/
class recaptcha{

	private $public;
	private $secret;

    /**
     * recaptcha constructor.
     * @param $public
     * @param $secret
     */
    public function __construct($public, $secret) {
		$this->public = $public;
		$this->secret = $secret;		
	}	

	/*
	IsSuccess = vérifier une clé post envoyé par google
	Return true or false
	passer en paramètre la clé de réponse (post)
	*/
    /**
     * @param $postToken
     * @return bool
     */
    public function isSuccess($postToken){
		if (empty($postToken)) {
			return false;
		}
		$url = "https://www.google.com/recaptcha/api/siteverify?secret=".$this->secret."&response=".$postToken;
		if (function_exists('curl_version')) {
	        $curl = curl_init($url);
	        curl_setopt($curl, CURLOPT_HEADER, false);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        $response = curl_exec($curl);
	    } else {
	        $response = file_get_contents($url);
	    }
       	if (empty($response) || is_null($response)) {
	        return false;
	    }

	    $json = json_decode($response);
    	return $json->success;	

	}
}