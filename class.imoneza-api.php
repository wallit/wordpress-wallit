<?php
    
abstract class iMoneza_API {
	public $options;
    public $privateKey;
    public $publicKey;
    public $server;

    protected function __construct($options, $accessKey, $secretKey, $server)
    {
        $this->options = $options;
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->server = $server;
    }
}
?>