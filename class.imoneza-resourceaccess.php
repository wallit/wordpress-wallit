<?php
    
class iMoneza_ResourceAccess extends iMoneza_API {

    protected $cookieExpiration;

    public function __construct()
    {
        $options = get_option('imoneza_options');
        parent::__construct($options, $options['ra_api_key_access'], $options['ra_api_key_secret'], IMONEZA__RA_API_URL);

        // 14 days
        $this->cookieExpiration = 60 * 60 * 24 * 14;
    }

    public function getResourceAccess($externalKey, $resourceURL)
    {
        try {
            $userToken = '';

            // Check for excluded user agents
            if (isset($this->options['access_control_excluded_user_agents']) && $this->options['access_control_excluded_user_agents'] != '') {
                foreach (explode("\n", $this->options['access_control_excluded_user_agents']) as $userAgent) {
                    if ($userAgent == $_SERVER['HTTP_USER_AGENT'])
                        return;
                }
            }

            if (isset($_REQUEST['iMonezaTUT'])) {
                // The user just authenticated at iMoneza, and iMoneza is sending the temporary user token back to us
                $temporaryUserToken = $_REQUEST['iMonezaTUT'];
                $resourceAccessData = $this->getResourceAccessDataFromTemporaryUserToken($externalKey, $resourceURL, $temporaryUserToken);
            } else {
                $userToken = $_COOKIE['iMonezaUT'];
                $resourceAccessData = $this->getResourceAccessDataFromExternalKey($externalKey, $resourceURL, $userToken);
            }

            $userToken = $resourceAccessData['UserToken'];
            setcookie('iMonezaUT', $userToken, time() + $this->cookieExpiration);

            if ($resourceAccessData['AccessActionURL'] && strlen($resourceAccessData['AccessActionURL']) > 0)
            {
                $url = $resourceAccessData['AccessActionURL'];
                $url = $url . '&OriginalURL=' . rawurlencode($resourceURL);
                wp_redirect($url);
                exit;
            }
        } catch (Exception $e) {
            // Default to open access if there's some sort of exception
            if (IMONEZA__DEBUG)
                throw $e;
        }
    }

    public function getResourceAccessDataFromExternalKey($externalKey, $resourceURL, $userToken) {
        $request = new iMoneza_RestfulRequest($this);
        $request->method = 'GET';
        $request->uri = '/api/Resource/' . $this->accessKey . '/' . $externalKey;
        $request->getParameters['ResourceURL'] = $resourceURL;
        $request->getParameters['UserToken'] = $userToken;

        $response = $request->getResponse();

        if ($response['response']['code'] == '404') {
            throw new Exception('An error occurred with the Resource Access API key. Make sure you have valid Access Management API keys set in the iMoneza plugin settings.');
        } else {
            return json_decode($response['body'], true);
        }
    }

    public function getResourceAccessDataFromTemporaryUserToken($externalKey, $resourceURL, $temporaryUserToken) {
        $request = new iMoneza_RestfulRequest($this);
        $request->method = 'GET';
        $request->uri = '/api/TemporaryUserToken/' . $this->accessKey . '/' . $temporaryUserToken;
        $request->getParameters['ResourceKey'] = $externalKey;
        $request->getParameters['ResourceURL'] = $resourceURL;

        $response = $request->getResponse();

        if ($response['response']['code'] == '404') {
            throw new Exception('An error occurred with the Resource Access API key. Make sure you have valid Access Management API keys set in the iMoneza plugin settings.');
        } else {
            return json_decode($response['body'], true);
        }
    }
}
?>