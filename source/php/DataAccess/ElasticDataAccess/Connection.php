<?php
require 'composer/vendor/autoload.php';
final class ElasticConnection
{
    //MARK:- Properties

    //@var: Server's infomation
    private $hosts = [
        [
            'host' => '7aba05a15783420a9353c67a7f176344.ap-southeast-1.aws.found.io',
            'port' => '9243',
            'scheme' => 'https',
            'user' => 'elastic',
            'pass' => '8uaAv0WHhtPdhez6ElSbyqzD',
        ],
    ];
    private $client;

    //@var: Elastic instance
    private static $instance = null;

    //MARK:- Private Constructor
    private function __construct()
    {
        try {
            $this->client = Elasticsearch\ClientBuilder::create()
                ->setHosts($this->hosts)
                ->build();
        } catch (Elasticsearch\Common\Exceptions\Curl\CouldNotConnectToHost $e) {
            echo $e;
        }
    }

    //MARK:- Function

    public static function open_connection()
    {
        if (static::$instance === null) {
            static::$instance = new ElasticConnection();
        }
        return static::$instance;
    }

    public function get_client()
    {
        return $this->client;
    }
}
