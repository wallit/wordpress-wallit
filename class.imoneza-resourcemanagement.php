<?php
    
class iMoneza_ResourceManagement extends iMoneza_API {

    public function __construct()
    {
        $options = get_option('imoneza_options');
        parent::__construct($options, $options['rm_api_key_access'], $options['rm_api_key_secret'], IMONEZA__RM_API_URL);
    }

    public function getProperty()
    {
        $request = new iMoneza_RestfulRequest($this);
        $request->method = 'GET';
        $request->uri = '/api/Property/' . $this->accessKey;

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
        $request->uri = '/api/Property/' . $this->accessKey . '/Resource/' . $externalKey;

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
        $request->uri = '/api/Property/' . $this->accessKey . '/Resource/' . $externalKey;
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