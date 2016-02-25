<?php
/**
 * Tests getting the property
 *
 * @author Aaron Saray
 */

namespace iMoneza\IntegrationTest;


class GetPropertyDetailsTest extends \PHPUnit_Framework_TestCase
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

    public function testGetPropertyData()
    {
        $options = new \iMoneza\Options\Management\Property();
        $options->setApiBaseURL(getenv('MANAGEMENT_API_URL')); // only for testing

        $result = $this->connection->request($options, new \iMoneza\Data\Property());

        $this->assertNotEmpty($result);
        $this->assertInstanceOf('\iMoneza\Data\Property', $result);

    }
}