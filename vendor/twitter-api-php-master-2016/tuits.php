




<?php

include_once('TwitterAPIExchange.php');

/**
 * Class TwitterAPIExchangeTest
 *
 * Contains ALL the integration tests
 *
 * @note This test account is not actively monitored so you gain nothing by hi-jacking it :-)
 */
class TwitterAPIExchangeTest //extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    const CONSUMER_KEY = '48vcnktQZtbrZd3MLR5zRAxFR';

    /**
     * @var string
     */
    const CONSUMER_SECRET = '4HHlrYkh7q6WkiMjD4Nlbl3DDXgmTFoRgON5088yzB60y6EOAS';

    /**
     * @var string
     */
    const OAUTH_ACCESS_TOKEN = '3351640672-qloeho6NasLJq3rbKXUsDyaZd7qB5iQcDpH8GKO';

    /**
     * @var string
     */
    const OAUTH_ACCESS_TOKEN_SECRET = '60VFcXGaaBvDzOAjvaPPbefjrSQ7UDds5sDPfaeYllqoS';

    /**
     * @var \TwitterAPIExchange
     */
    protected $exchange;

    /**
     * @var int Stores a tweet id (for /update) to be deleted later (by /destroy)
     */
    private static $tweetId;

    /**
     * @var int Stores uploaded media id
     */
    private static $mediaId;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $settings = array(
				 'oauth_access_token' => '3351640672-qloeho6NasLJq3rbKXUsDyaZd7qB5iQcDpH8GKO',
				 'oauth_access_token_secret' => '60VFcXGaaBvDzOAjvaPPbefjrSQ7UDds5sDPfaeYllqoS',
				 'consumer_key' => '48vcnktQZtbrZd3MLR5zRAxFR',
				 'consumer_secret' => '4HHlrYkh7q6WkiMjD4Nlbl3DDXgmTFoRgON5088yzB60y6EOAS');

        $this->exchange = new TwitterAPIExchange($settings);
        
        print_r($this->exchange);
    }


    /**
     * POST media/upload
     *
     * @see https://dev.twitter.com/rest/reference/post/media/upload
     *
     * @note Uploaded unattached media files will be available for attachment to a tweet for 60 minutes
     */
    public function testMediaUpload()
    {
        $file = file_get_contents(__DIR__ . '/img.png');
        $data = base64_encode($file);

        $url    = 'https://upload.twitter.com/1.1/media/upload.json';
        $method = 'POST';
        $params = array(
            'media_data' => $data
        );

        $data     = $this->exchange->request($url, $method, $params);
        $expected = 'image\/png';

        //$this->assertContains($expected, $data);

        /** Store the media id for later **/
        $data = @json_decode($data, true);

        //$this->assertArrayHasKey('media_id', is_array($data) ? $data : array());

        self::$mediaId = $data['media_id'];
        
        echo "<br><br>";
        var_dump(self::$mediaId);
        
    }

    /**
     * POST statuses/update
     *
     * @see https://dev.twitter.com/rest/reference/post/statuses/update
     */
    public function testStatusesUpdate()
    {
        if (!self::$mediaId)
        {
            $this->fail('Cannot /update status because /upload failed');
        }

        $url    = 'https://api.twitter.com/1.1/statuses/update.json';
        $method = 'POST';
        $params = array(
            'status' => 'TEST TWEET TO BE DELETED' . rand(),
            'possibly_sensitive' => false,
            'media_ids' => self::$mediaId
        );

        $data     = $this->exchange->request($url, $method, $params);
        $expected = 'TEST TWEET TO BE DELETED';
        
        

        //$this->assertContains($expected, $data);

        /** Store the tweet id for testStatusesDestroy() **/
        $data = @json_decode($data, true);
        
        echo "<br><br>";
        print_r($data);
        
        

        //$this->assertArrayHasKey('id_str', is_array($data) ? $data : array());

        self::$tweetId = $data['id_str'];

        /** We've done this now, yay **/
        self::$mediaId = null;
    }

   
}

$a = new TwitterAPIExchangeTest();
$a->setUp();
$a->testMediaUpload();
$a->testStatusesUpdate();




?>

