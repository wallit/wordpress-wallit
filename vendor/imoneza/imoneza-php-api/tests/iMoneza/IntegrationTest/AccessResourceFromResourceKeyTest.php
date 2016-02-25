<?php
/**
 * Tests getting the resource
 *
 * @author Aaron Saray
 */

namespace iMoneza\IntegrationTest;


use iMoneza\Data\Resource;
use iMoneza\Helper;

class AccessResourceFromResourceKeyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \iMoneza\Connection
     */
    protected $connection;

    public static function setUpBeforeClass()
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__);
        $dotenv->load();
    }

    public function setUp()
    {
        $accessApiKey = getenv('ACCESS_API_KEY');
        $accessSecretKey = getenv('ACCESS_API_SECRET');
        $apiKey = getenv('MANAGEMENT_API_KEY');
        $secretKey = getenv('MANAGEMENT_API_SECRET');

        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));

        $this->connection = new \iMoneza\Connection($apiKey, $secretKey, $accessApiKey, $accessSecretKey, new \iMoneza\Request\Curl(), $logger);
    }

    public function testGetResourceFromResourceKey()
    {
        $options = new \iMoneza\Options\Access\ResourceFromResourceKey();
        $options->setApiBaseURL(getenv('ACCESS_API_URL')); // only for testing
        $options->setResourceKey('x')->setIP(Helper::getCurrentIP())
            ->setResourceURL('x');

        $data = new Resource();
        $result = $this->connection->request($options, $data);

        $this->assertNotEmpty($result);
        $this->assertInstanceOf('\iMoneza\Data\Resource', $result);
    }
}