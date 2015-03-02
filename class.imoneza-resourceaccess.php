<?php
    
class iMoneza_ResourceAccess {
	public $options;
    public $privateKey;
    public $publicKey;
    public $server;

    public function __construct()
    {
        $this->options = get_option('imoneza_options');
        $this->publicKey = $this->options['ra_api_key_public'];
        $this->privateKey = $this->options['ra_api_key_private'];
        $this->server = IMONEZA__RA_API_URL;
    }

    public function getResourceAccess($externalKey, $resourceURL)
    {
        $userID = '';

        // The user just authenticated at iMoneza, and iMoneza is sending the user ID back to us. We should store that user ID as a cookie and then redirect to the same page, but without the user ID in the querystring.
        if (isset($_REQUEST['iMoneza_UserID']))
        {
            $userID = $_REQUEST['iMoneza_UserID'];
            setcookie('iMoneza_UserID', $userID, time()+60*60*24*14);
            
            $url = 'http://' . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI];
            $url = str_replace('?iMoneza_UserID=' . $userID, '', $url);
            $url = str_replace('&iMoneza_UserID=' . $userID, '', $url);

            wp_redirect($url);
            exit;
        }

        if (isset($_COOKIE['iMoneza_UserID']))
            $userID = $_COOKIE['iMoneza_UserID'];

        $url = $this->server . '/api/ResourceAccess/' . $this->publicKey . '/' . $externalKey . '?UserID=' . $userID . '&ResourceURL=' . rawurlencode($resourceURL);

        $rawResponse = wp_remote_get($url);
        $response = json_decode($rawResponse['body'], TRUE);

        $userID = $response['UserID'];
        setcookie('iMoneza_UserID', $response['UserID'], time()+60*60*24*14);

        if ($response['AccessAction'] != 'Grant')
        {
            wp_redirect($this->server . '/ResourceAccess?ApiKey=' . $this->publicKey . '&ResourceKey=' . $externalKey . '&UserID=' . $userID . '&OriginalURL=' . rawurlencode($resourceURL));
            exit;
        }
    }
}
?>