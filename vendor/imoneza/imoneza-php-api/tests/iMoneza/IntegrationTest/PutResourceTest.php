<?php
/**
 * Tests putting the resource
 *
 * @author Aaron Saray
 */

namespace iMoneza\IntegrationTest;


class PutTheResourceTest extends \PHPUnit_Framework_TestCase
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

    public function testPutResource()
    {
        $options = new \iMoneza\Options\Management\SaveResource();
        $options->setApiBaseURL(getenv('MANAGEMENT_API_URL')); // only for testing

        $externalId = uniqid();
        $name = 'NAME:' . $externalId;
        $title = 'TITLE:' . $externalId;
        $options->setExternalKey($externalId)->setName($name)->setTitle($title)->setPricingGroupId('b527e274-5ae1-425f-ad8f-320dd369452d');

        $result = $this->connection->request($options, new \iMoneza\Data\None());
        $this->assertNotEmpty($result);
        $this->assertInstanceOf('iMonezaPRO\Data\None', $result);
    }
}