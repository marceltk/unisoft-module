<?php

namespace Unisoft\Crawler;

use Unisoft\Crawler\Interfaces\CrawlerInterface;
use Zend\ServiceManager\ServiceManager;

class Crawler implements CrawlerInterface
{
    use \Unisoft\Crawler\Traits\CrawlerOutputTrait;

    public $name;
    public $token;

    protected $approbo;

    protected static $serviceManager;
    protected static $loggerService;

    public function __construct(ServiceManager $serviceManager)
    {
        self::setServiceManager($serviceManager);
        self::setLoggerService($serviceManager->get("LoggerService")->getLogger());
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    protected static function getLoggerService()
    {
        return self::$loggerService;
    }

    protected static function setLoggerService($loggerService)
    {
        self::$loggerService = $loggerService;
    }

    public function setToken($name)
    {
        // TODO: Implement setToken() method.
    }

    public function getToken()
    {
        // TODO: Implement getToken() method.
    }

    //    public static function getEventManager() {
    //        return self::$eventManager;
    //    }
    //
    //    public static function setEventManager(EventManager $eventManager) {
    //        self::$eventManager = $eventManager;
    //    }

    protected static function getServiceManager()
    {
        return self::$serviceManager;
    }

    protected static function setServiceManager(ServiceManager $serviceManager)
    {
        self::$serviceManager = $serviceManager;
    }

    public function setApprobo(\Application\Entity\Approbo $approbo)
    {
        $this->approbo = $approbo;
    }

    public function getApprobo()
    {
        return $this->approbo;
    }
}