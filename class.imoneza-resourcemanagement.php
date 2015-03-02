<?php
    
class iMoneza_ResourceManagement {
	public $options;
    public $privateKey;
    public $publicKey;
    public $server;

    public function __construct()
    {
        $this->options = get_option('imoneza_options');
        $this->publicKey = $this->options['rm_api_key_public'];
        $this->privateKey = $this->options['rm_api_key_private'];
        $this->server = IMONEZA__RM_API_URL;
    }

    public function getProperty()
    {
        $request = new iMoneza_RestfulRequest($this);
        $request->method = 'GET';
        $request->uri = '/api/Property/' . $this->publicKey;

        $response = $request->getResponse();

        if ($response['response']['code'] == '404') {
            throw new Exception('An error occurred with the Resource Management API key. Make sure you have valid Resource Management API keys set in the iMoneza plugin settings.');
        } else {
            return json_decode($response['body'], true);
        }
    }

    public function getResource($externalKey, $includePropertyData = false)
    {
        $request = new iMoneza_RestfulRequest($this);
        $request->method = 'GET';
        $request->uri = '/api/Property/' . $this->publicKey . '/Resource/' . $externalKey;

        if ($includePropertyData)
            $request->getParameters['includePropertyData'] = 'true';

        $response = $request->getResponse();

        if ($response['response']['code'] == '404') {
            return array('IsManaged' => 0);
        } else {
            $retObj = json_decode($response['body'], true);
            $retObj['IsManaged'] = 1;
            return $retObj;
        }
    }

    public function putResource($externalKey, $data)
    {
        $request = new iMoneza_RestfulRequest($this);
        $request->method = 'PUT';
        $request->uri = '/api/Property/' . $this->publicKey . '/Resource/' . $externalKey;
        $request->body = json_encode($data);
        $request->contentType = 'application/json';

        $response = $request->getResponse();

        if ($response['response']['code'] != '200') {
            if (IMONEZA__DEBUG) {
                echo '<html><pre>';
                print_r($response);
                echo '</pre></html>';
                die();
            } else {
                if (isset($response['body'])) {
                    $obj = json_decode($response['body']);
                    if (isset($obj->Message))
                        throw new Exception($obj->Message);
                    else
                        throw new Exception('An error occurred while sending your changes to iMoneza.');
                } else {
                    throw new Exception('An error occurred while sending your changes to iMoneza.');
                }
            }
        }

        return $response;
    }
}
?>