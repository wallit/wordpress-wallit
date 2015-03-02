<?php

class iMoneza_RestfulRequest {

    private $resourceManagement;
    
    public $method;
    public $uri;
    public $getParameters;
    public $accept;
    public $body;
    public $contentType;

    public function __construct($resourceManagement)
    {
        $this->resourceManagement = $resourceManagement;
        $this->getParameters = array();
        $this->method = 'GET';
        $this->accept = 'application/json';
        $this->body = '';
        $this->contentType = '';
    }

    public function getResponse()
    {
        $this->method = strtoupper($this->method);
        if ($this->method != 'GET' && $this->method != 'POST' && $this->method != 'PUT' && $this->method != 'DELETE')
            throw new Exception('Invalid method');

        if ($this->method != 'POST' && $this->method != 'PUT' && $this->body != '')
            throw new Exception('You can only specify a body with a POST');

        if ($this->method != 'POST' && $this->method != 'PUT' && $this->contentType != '')
            throw new Exception('You can only specify a content type with a POST');

        if ($this->body != '' && $this->contentType == '')
            throw new Exception('You must provide a content type with a body');

        if (strpos($this->uri, '?') !== FALSE)
            throw new Exception('Illegal character in URI - make sure you include query string parameters in the getParams dictionary, not the URI');

        $timestamp = gmdate('D, d M Y H:i:s \G\M\T');

        $sortedParams = $this->getSortedParams();
        $paramStrings = $this->getParamString($sortedParams);

        $baseString = implode("\n", array($this->method, $timestamp, strtolower($this->uri), $paramStrings));
        $hash = base64_encode(hash_hmac('sha256', $baseString, $this->resourceManagement->privateKey, true));

        $url = $this->resourceManagement->server . $this->uri;
        if (count($this->getParameters) > 0)
        {
            $getParamStrings = array();
            foreach ($this->getParameters as $key => $value)
                $getParamStrings[] = $key . '=' . $value;

            $url .= '?' . implode('&', $getParamStrings);
        }

        $rawResponse = wp_remote_request($url, array(
            'method' => $this->method,
            'body' => $this->body,
            'headers' => array(
                'Timestamp' => $timestamp,
                'Authentication' => $this->resourceManagement->publicKey . ':' . $hash,
                'Accept' => $this->accept,
                'Content-Type' => $this->contentType
            )
        ));

        if (is_a($rawResponse, WP_Error)) {
            $exMessage = "An error occurred connecting to the iMoneza Resource Management API. This may be a temporary connectivity issue; refresh the page to try again.";
            if (IMONEZA__DEBUG) {
                $exMessage .= "\r\n\r\nResponse: " . var_export($rawResponse, TRUE);
            }

            throw new Exception($exMessage);
        }

        return $rawResponse;
    }

    private function getSortedParams()
    {
        $sortedParams = array();
        // This won't handle conflicting GET/POST params the same way as the .NET module
        foreach ($this->getParameters as $key => $value)
            $sortedParams[strtolower($key)] = $value;
        ksort($sortedParams);

        return $sortedParams;
    }

    private function getParamString($sortedParams)
    {
        $paramStrings = array();
        foreach ($sortedParams as $key => $value)
            $paramStrings[] = $key . '=' . $value;

        return implode('&', $paramStrings);
    }

}
?>